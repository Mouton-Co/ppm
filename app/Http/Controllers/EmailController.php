<?php

namespace App\Http\Controllers;

use App\Mail\PurchaseOrder;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function sendOrder(Request $request, $id)
    {
        $order = Order::find($id);

        if (! $order) {
            return redirect()->back()->with('error', 'Order not found');
        }
        
        Mail::to($request->to)
            ->cc(['orders@proproject.co.za', 'hanna@proproject.co.za'])
            ->send(new PurchaseOrder($order, $request->subject, $request->body));

        return redirect()->route('orders.index')->with('success', 'Email sent successfully!');
    }

    /**
     * Render the purchase order email.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function renderOrder($id)
    {
        $order = Order::find($id);

        if (! $order) {
            return redirect()->back()->with('error', 'Order not found');
        }

        $body = "<p>Good Day,</p>";
        $body .= "<p>We would like to order the below items, using PO Number: 01-06-0001</p>";
        $body .= "<table style='border-collapse: collapse; width: 99.9915%;' border='1'><colgroup><col style='width: 25.0153%;'><col style='width: 25.0153%;'><col style='width: 25.0153%;'><col style='width: 25.0153%;'></colgroup>";
        $body .= "<tbody><tr>";
        $body .= "<td><strong>Part Name</strong></td>";
        $body .= "<td><strong>Qty</strong></td>";
        $body .= "<td><strong>Material</strong></td>";
        $body .= "<td><strong>Material Thickness</strong></td></tr>";
        foreach ($order->submission->parts as $part) {
            $body .= "<tr>";
            $body .= "<td>{$part->name}</td>";
            $body .= "<td>{$part->quantity}</td>";
            $body .= "<td>{$part->material}</td>";
            $body .= "<td>{$part->material_thickness}</td>";
            $body .= "</tr>";
        }
        $body .= "</tbody></table>";
        $body .= "<p>For any queries, please email hanna@proproject.co.za</p>";
        $body .= "<p>Kind regards,</p>";
        $body .= "<p><strong>PPM ERP System</strong></p>";
        $body .= "<pre>Pro Project Machinery (Pty) Ltd.</pre>";

        return view('emails.purchase-order', [
            'order' => $order,
            'body'  => $body,
        ]);
    }
}
