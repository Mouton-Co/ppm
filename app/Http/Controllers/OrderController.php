<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\StoreRequest;
use App\Http\Requests\Order\UpdateRequest;
use App\Models\Order;
use App\Models\Part;
use App\Models\Submission;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $orders = $this->filter(Order::class, Order::query(), $request)->paginate(15);

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
        $order = Order::find($request->id);

        if (! empty($order)) {
            $order->update($request->validated());
        }

        return redirect()->route('orders.index')->withErrors(
            'Order not found.'
        );
    }

    /**
     * Generate all oustanding orders from the procurement table.
     * Orders are generated for all the parts that have PO numbers, suppliers and haven't been ordered yet.
     */
    public function generate()
    {
        $poNumbers = Part::whereNotNull('po_number')
            ->whereNotNull('supplier_id')
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

            // create order
            Order::create([
                'po_number' => $poNumber,
                'supplier_id' => $parts[0]->supplier_id,
                'submission_id' => $parts[0]->submission_id,
                'total_parts' => $totalParts,
            ]);
        }

        return redirect()->route('orders.index')->withSuccess(
            'Orders generated.'
        );
    }

    /**
     * Mark the order as ordered.
     */
    public function markOrdered($id)
    {
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
                    ? $order->submission->project->related_pos . ', ' . $order->po_number
                    : $order->po_number,
            ]);
        }

        return redirect()->route('orders.index')->withSuccess(
            'Parts marked as ordered.'
        );
    }
}
