<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\StoreRequest;
use App\Http\Requests\Order\UpdateRequest;
use App\Http\Services\EmailService;
use App\Models\Order;
use App\Models\Part;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * OrderController constructor.
     */
    public function __construct()
    {
        $this->model = Order::class;
        $this->route = 'orders';
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->user()->cannot('read', Order::class)) {
            abort(403);
        }

        if ($request->has('status') && $request->status === 'All except ordered') {
            $orders = $this->filter(Order::class, Order::query(), $request)
                ->where('status', '!=', 'ordered')
                ->paginate(15);
        } else {
            $orders = $this->filter(Order::class, Order::query(), $request)->paginate(15);
        }

        if ($orders->currentPage() > 1 && $orders->lastPage() < $orders->currentPage()) {
            return redirect()->route('orders.index', array_merge(['page' => $orders->lastPage()], $request->except(['page'])));
        }

        return view('generic.index-custom')->with([
            'heading' => 'Orders',
            'indexRoute' => 'orders.index',
            'data' => $orders,
            'model' => Order::class,
            'slot' => 'components.table.orders.list',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        if ($request->user()->cannot('create', Order::class)) {
            abort(403);
        }

        Order::create($request->validated());

        return redirect()->route('orders.index')->withSuccess(
            'Order created.'
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request)
    {
        if ($request->user()->cannot('update', Order::class)) {
            abort(403);
        }

        $order = Order::find($request->id);

        if (! empty($order)) {
            $order->update($request->validated());
        }

        return redirect()->route('orders.index')->withErrors(
            'Order not found.'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        if ($request->user()->cannot('delete', Order::class)) {
            abort(403);
        }

        $order = Order::find($id);

        if (! empty($order)) {
            $order->delete();

            return redirect()->route('orders.index')->withSuccess(
                'Order deleted.'
            );
        }

        return redirect()->route('orders.index')->withErrors(
            'Order not found.'
        );
    }

    /**
     * Generate all oustanding orders from the procurement table.
     * Orders are generated for all the parts that have PO numbers, suppliers and haven't been ordered yet.
     */
    public function generate(Request $request)
    {
        if ($request->user()->cannot('create', Order::class)) {
            abort(403);
        }

        $poNumbers = Part::whereNotNull('po_number')
            ->whereNotNull('supplier_id')
            ->where('po_number', 'not like', '%dno%')
            ->where('part_ordered', false)
            ->get()
            ->groupBy('po_number');

        foreach ($poNumbers as $poNumber => $parts) {
            // get total parts
            $totalParts = 0;
            foreach ($parts as $part) {
                $totalParts += $part->quantity;
            }

            $order = Order::where('po_number', $poNumber)->first();

            if (! empty($order)) {
                $order->update([
                    'total_parts' => $totalParts,
                    'status' => 'processing',
                    'supplier_id' => $parts[0]->supplier_id,
                    'submission_id' => $parts[0]->submission_id,
                ]);

                continue;
            }

            $dueDate = null;
            if (! empty($parts[0]->supplier->average_lead_time)) {
                $dueDate = now()->addDays($parts[0]->supplier->average_lead_time);
            }

            // create order
            $order = Order::create([
                'po_number' => $poNumber,
                'supplier_id' => $parts[0]->supplier_id,
                'submission_id' => $parts[0]->submission_id,
                'total_parts' => $totalParts,
                'due_date' => $dueDate,
            ]);

            // set token
            $order->update([
                'token' => hash('sha256', $order->id),
            ]);
        }

        return redirect()->back()->withSuccess(
            'Orders generated.'
        );
    }

    /**
     * Mark the order as ordered.
     */
    public function markOrdered(Request $request, $id)
    {
        if (
            $request->user()?->cannot('update', Order::class) &&
            ! $request->has('token') &&
            $request->get('token') !== hash('sha256', $id)
        ) {
            abort(403);
        }

        $order = Order::find($id);

        if (empty($order)) {
            return redirect()->route('orders.index')->withErrors(
                'Order not found.'
            );
        }

        $order->update([
            'status' => 'ordered',
        ]);

        // mark all parts as complete
        foreach ($order->parts()->get() as $part) {
            $part->update([
                'part_ordered' => true,
                'part_ordered_at' => now(),
                'status' => 'supplier',
                'qty_received' => 0,
            ]);
        }

        // if project is attached to submission update related POs
        if (! empty($order?->submission?->project)) {
            $order->submission->project->update([
                'related_pos' => ! empty($order->submission->project->related_pos)
                    ? $order->submission->project->related_pos.', '.$order->po_number
                    : $order->po_number,
            ]);
        }

        if ($request->has('token')) {

            /**
             * send confirmation email to PPM that client confirmed the order if token was present
             */
            EmailService::sendClientConfirmedEmail($order);

            return view('order.confirmation');
        }

        return redirect()->back()->withSuccess(
            'Parts marked as ordered.'
        );
    }

    /**
     * Update the date of the order with ajax.
     */
    public function updateDate(Request $request): \Illuminate\Http\JsonResponse
    {
        if ($request->user()->cannot('update', Order::class)) {
            abort(403);
        }

        $order = Order::find($request->id);

        if (! empty($order)) {
            $order->update([
                $request->get('field') => $request->get('value'),
            ]);

            return response()->json([
                'message' => 'Order updated.',
                'id' => $order->id,
                'days' => $order->due_days,
                'date' => $order->due_date,
            ]);
        }

        return response()->json([
            'message' => 'Order not found.',
        ]);
    }
}
