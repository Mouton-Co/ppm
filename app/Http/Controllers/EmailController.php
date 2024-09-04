<?php

namespace App\Http\Controllers;

use App\Mail\PurchaseOrder;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    /**
     * Send the purchase order email.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendOrder(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $order = Order::find($id);

        if (! $order) {
            return redirect()->back()->with('error', 'Order not found');
        }

        Mail::to($request->to)
            ->cc(! empty($request->cc) ? explode(',', str_replace(' ', '', $request->cc)) : [])
            ->send(new PurchaseOrder($order, $request->subject, $request->body));

        $order->status = 'emailed';
        $order->save();

        // Mark parts as email sent
        foreach ($order->parts()->get() as $part) {
            $part->status = 'email_sent';
            $part->save();
        }

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
        
        if (empty($order->supplier->template) || $order->supplier->template == 1) {
            $body = $this->template1($order);
        } else {
            $body = $this->template2($order);
        }

        $emails = array_merge(
            $order->supplier->representatives()->pluck('email')->toArray(),
            ['orders@proproject.co.za', 'hanna@proproject.co.za']
        );

        $to = $emails[0];
        $cc = implode(', ', array_slice($emails, 1));

        return view('emails.purchase-order', [
            'order' => $order,
            'body' => $body,
            'to' => $to,
            'cc' => $cc,
        ]);
    }

    /**
     * Template one for purchase order email.
     *
     * @param  Order $order
     * @return string
     */
    protected function template1(Order $order): string
    {
        $body = '<p>Good Day,</p>';
        $body .= '<p>We would like to order the below items, using PO Number: '.$order->po_number.'</p>';
        $body .= "<table style='border-collapse: collapse; width: 99.9915%;' border='1'>";
        $body .= '<tbody><tr>';
        $body .= '<td><strong>Part Name</strong></td>';
        $body .= '<td><strong>Qty</strong></td>';
        $body .= '<td><strong>Material</strong></td>';
        $body .= '<td><strong>Material Thickness</strong></td>';
        $body .= '<td><strong>Stage</strong></td></tr>';
        foreach ($order->parts()->orderBy('stage')->orderBy('name')->get() as $part) {
            $body .= '<tr>';
            $body .= "<td>{$part->name}</td>";
            $body .= "<td>{$part->quantity_ordered}</td>";
            $body .= "<td>{$part->material}</td>";
            $body .= "<td>{$part->material_thickness}</td>";
            $body .= "<td>{$part->stage}</td>";
            $body .= '</tr>';
        }
        $body .= '</tbody></table>';
        $body .= '<p>For any queries, please email hanna@proproject.co.za</p>';
        $body .= '<p>Kind regards,</p>';
        $body .= '<p><strong>PPM ERP System</strong></p>';
        $body .= '<pre>Pro Project Machinery (Pty) Ltd.</pre>';

        return $body;
    }

    /**
     * Template two for purchase order email.
     *
     * @param  Order $order
     * @return string $body
     */
    protected function template2(Order $order): string
    {
        $body = '<p>Good Day,</p>';
        $body .= '<p>We would like to order the below items, using PO Number: '.$order->po_number.'</p>';
        $body .= "<table style='border-collapse: collapse; width: 99.9915%;' border='1'><colgroup><col style='width: 25.0153%;'><col style='width: 25.0153%;'><col style='width: 25.0153%;'></colgroup>";
        $body .= '<tbody><tr>';
        $body .= '<td><strong>Part Name</strong></td>';
        $body .= '<td><strong>Qty</strong></td>';
        $body .= '<td><strong>Stage</strong></td></tr>';
        foreach ($order->parts()->orderBy('stage')->orderBy('name')->get() as $part) {
            $body .= '<tr>';
            $body .= "<td>{$part->name}</td>";
            $body .= "<td>{$part->quantity_ordered}</td>";
            $body .= "<td>{$part->stage}</td>";
            $body .= '</tr>';
        }
        $body .= '</tbody></table>';
        $body .= '<p>For any queries, please email hanna@proproject.co.za</p>';
        $body .= '<p>Kind regards,</p>';
        $body .= '<p><strong>PPM ERP System</strong></p>';
        $body .= '<pre>Pro Project Machinery (Pty) Ltd.</pre>';

        return $body;
    }
}
