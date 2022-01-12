<?php

namespace Webkul\SendcloudShipping\Http\Controllers\Admin;

use Exception;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Repositories\OrderItemRepository;
use Webkul\Sales\Repositories\ShipmentRepository;
use Webkul\SendcloudShipping\Repositories\SendcloudRepository as SendCloudRepository;
use Webkul\SendcloudShipping\Models\Sendcloud;
use Illuminate\Support\Facades\DB;

class ShipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $_config;

    /**
     * OrderRepository object
     *
     * @var \Webkul\Sales\Repositories\OrderRepository
     */
    protected $orderRepository;

    /**
     * OrderItemRepository object
     *
     * @var \Webkul\Sales\Repositories\OrderItemRepository
     */
    protected $orderItemRepository;

    /**
     * ShipmentRepository object
     *
     * @var \Webkul\Sales\Repositories\ShipmentRepository
     */
    protected $shipmentRepository;

    protected $sendCloud;

    protected $weight = 0;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Sales\Repositories\ShipmentRepository   $shipmentRepository
     * @param  \Webkul\Sales\Repositories\OrderRepository  $orderRepository
     * @param  \Webkul\Sales\Repositories\OrderitemRepository  $orderItemRepository
     * @return void
     */
    public function __construct(
        ShipmentRepository $shipmentRepository,
        OrderRepository $orderRepository,
        OrderItemRepository $orderItemRepository,
        SendCloudRepository $sendCloud
    )
    {
        $this->middleware('admin');

        $this->_config = request('_config');

        $this->orderRepository = $orderRepository;

        $this->orderItemRepository = $orderItemRepository;

        $this->shipmentRepository = $shipmentRepository;

        $this->sendCloud = $sendCloud;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view($this->_config['view']);
    }

    /**
     * Show the form for creating a new resource and create shipment.
     *
     * @param  int  $orderId
     * @return \Illuminate\View\View
     */
    public function create($orderId)
    {
        $order = $this->orderRepository->findOrFail($orderId);

        if (! $order->channel || !$order->canShip()) {
            session()->flash('error', trans('admin::app.sales.shipments.creation-error'));

            return redirect()->back();
        }

        return view($this->_config['view'], compact('order'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  int  $orderId
     * @return \Illuminate\Http\Response
     */
    public function store($orderId)
    {
        $order = $this->orderRepository->findOrFail($orderId);

        if (! $order->canShip()) {
            session()->flash('error', trans('admin::app.sales.shipments.order-error'));

            return redirect()->back();
        }

        $data = request()->all();

        if (isset($data['issendcloud']) && $data['issendcloud'] == '0') {
            $this->validate(request(), [
                'shipment.carrier_title' => 'required',
                'shipment.track_number'  => 'required',
                'shipment.source'        => 'required',
                'shipment.items.*.*'     => 'required|numeric|min:0',
            ]);

            unset($data['issendcloud']);
            $isdefault = true;
        } else {
            $this->validate(request(), [
                // 'shipment.carrier_title' => 'required',
                // 'shipment.track_number'  => 'required',
                'shipment.source'        => 'required',
                'shipment.items.*.*'     => 'required|numeric|min:0',
            ]);

            $isdefault = false;
        }

        if (! $this->isInventoryValidate($data)) {
            session()->flash('error', trans('admin::app.sales.shipments.quantity-invalid'));

            return redirect()->back();
        }

        DB::beginTransaction();

        try {

            $shipment = $this->shipmentRepository->create(array_merge($data, ['order_id' => $orderId]));

            if (! $isdefault) {

                foreach ($shipment->items as $items) {
                    $this->weight +=$items->weight;
                }

                if (isset($shipment) && $shipment != null) {

                    $result = $this->sendCloud->createShipment($order, $this->weight);

                    if ($result != null && !isset($result->error)) {

                        //on success
                        $parcel = json_decode($result, true);

                        $sendcloudData = [
                            'tracking_number' => $parcel['parcel']['tracking_number'],
                            'tracking_url' => $parcel['parcel']['tracking_url'],
                            'order_id' => $parcel['parcel']['order_number'],
                            'parcel_id' => $parcel['parcel']['id'],
                            'shipment_id' => $shipment->id
                        ];

                        $Model = Sendcloud::create($sendcloudData);

                        $this->shipmentRepository->update([
                            'track_number'  => $parcel['parcel']['tracking_number'],
                            'carrier_title' => 'SendCloud'], $shipment->id
                        );

                    } else if($result != null && isset($result->error)) {

                        //on error
                        throw new Exception('code : ' . $result->error->code . ', ' . $result->error->message);
                    } else {

                        //null return
                        throw new Exception('Sothing wrong with creating parcel.');
                    }
                }
            }

        } catch (Exception $e) {
            DB::rollBack();

            session()->flash('info', $e->getMessage());
            return back();
        }

        DB::commit();

        session()->flash('success', trans('admin::app.response.create-success', ['name' => 'Shipment']));

        return redirect()->route($this->_config['redirect'], $orderId);
    }

    /**
     * Get the label for shipment.
     *
     * @param  int  $shipmentId
     * @return \Illuminate\Http\Response
     */
    public function getLabel($shipmentId)
    {
        $labelFormate = core()->getConfigData('sales.carriers.sendcloud.label');

        if (core()->getConfigData('sales.carriers.sendcloud.shipment_type') == 'manual'
        ) {
            session()->flash('error', 'Shipment Created Manually!');
            return back();
        }

        $fileName = 'ShipmentLabel_' . $shipmentId . '_' . $labelFormate . '.pdf';

        $filepath = storage_path('app/public/shipping-label/') . $fileName ;

        if (file_exists($filepath)) {
            //if already saved
            $headers = array(
                'Content-Type' => 'application/octet-stream',
            );

            return response()->download($filepath , $fileName, $headers);
        } else {

            $newSavedPdfpath = $this->sendCloud->getLabelPdf($shipmentId);

            if ($newSavedPdfpath = 'manual_shipment') {
                session()->flash('error', 'Shipment Created Manually!');
                return back();
            }

            if (file_exists($newSavedPdfpath)){

                $headers = array(
                    'Content-Type' => 'application/octet-stream',
                );

                return response()->download($newSavedPdfpath , $fileName, $headers);
            } else {
                session()->flash('error', 'Somthing went wrong.');
                return back();
            }
        }


    }

    /**
     * Checks if requested quantity available or not
     *
     * @param  array  $data
     * @return bool
     */
    public function isInventoryValidate(&$data)
    {
        if (! isset($data['shipment']['items'])) {
            return ;
        }

        $valid = false;

        $inventorySourceId = $data['shipment']['source'];

        foreach ($data['shipment']['items'] as $itemId => $inventorySource) {
            if ($qty = $inventorySource[$inventorySourceId]) {
                $orderItem = $this->orderItemRepository->find($itemId);

                if ($orderItem->qty_to_ship < $qty) {
                    return false;
                }

                if ($orderItem->getTypeInstance()->isComposite()) {
                    foreach ($orderItem->children as $child) {
                        if (! $child->qty_ordered) {
                            continue;
                        }

                        $finalQty = ($child->qty_ordered / $orderItem->qty_ordered) * $qty;

                        $availableQty = $child->product->inventories()
                            ->where('inventory_source_id', $inventorySourceId)
                            ->sum('qty');

                        if ($child->qty_to_ship < $finalQty || $availableQty < $finalQty) {
                            return false;
                        }
                    }
                } else {
                    $availableQty = $orderItem->product->inventories()
                            ->where('inventory_source_id', $inventorySourceId)
                            ->sum('qty');

                    if ($orderItem->qty_to_ship < $qty || $availableQty < $qty) {
                        return false;
                    }
                }

                $valid = true;
            } else {
                unset($data['shipment']['items'][$itemId]);
            }
        }

        return $valid;
    }
}
