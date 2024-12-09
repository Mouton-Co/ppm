<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\NotReadyRequest;
use App\Mail\OrderConfirmationResponse1;
use App\Mail\OrderConfirmationResponse2;
use App\Mail\OrderConfirmationResponse3;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ResponseController extends Controller
{
    /**
     * Display the confirmation page.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function confirmation(Request $request): \Illuminate\View\View
    {
        return view('order.confirmation', [
            'title' => $request->get('title') ?? 'Thank you!',
            'message' => $request->get('message') ?? 'Thank you for confirming that the order has been placed! Our staff has been notified and we look forward to hearing from you.',
        ]);
    }

    /**
     * Mark the order as ready for pick up.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function orderReady(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        /**
         * make sure that the token is valid and matches the order id
         */
        if (!$request->has('token') && $request->get('token') !== hash('sha256', $id)) {
            abort(403);
        }

        $order = Order::find($id);

        if (!$order) {
            abort(404);
        }

        /**
         * send email to PPM that order is ready to be picked up
         */
        Mail::to('orders@proproject.co.za')->send(new OrderConfirmationResponse1($order));

        return redirect()->route('confirmation', [
            'title' => 'Thank you!',
            'message' => 'The order has been marked as ready for pick up. Our staff has been notified and we look forward to seeing you soon.',
        ]);
    }

    /**
     * Mark the order as shipped.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function orderShipped(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        /**
         * make sure that the token is valid and matches the order id
         */
        if (!$request->has('token') && $request->get('token') !== hash('sha256', $id)) {
            abort(403);
        }

        $order = Order::find($id);

        if (!$order) {
            abort(404);
        }

        /**
         * send email to PPM that order is ready to be picked up
         */
        Mail::to('orders@proproject.co.za')->send(new OrderConfirmationResponse2($order));

        return redirect()->route('confirmation', [
            'title' => 'Thank you!',
            'message' => 'The order has been marked as shipped. Our staff has been notified and we look forward to seeing you soon.',
        ]);
    }

    /**
     * Mark the order as shipped.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\View\View
     */
    public function orderNotReady(Request $request, $id): \Illuminate\View\View
    {
        /**
         * make sure that the token is valid and matches the order id
         */
        if (!$request->has('token') && $request->get('token') !== hash('sha256', $id)) {
            abort(403);
        }

        $order = Order::find($id);

        if (!$order) {
            abort(404);
        }

        return view('order.not-ready', [
            'order' => $order,
        ]);
    }

    /**
     * Mark the order as not ready for pick up.
     *
     * @param NotReadyRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function orderNotReadySubmit(NotReadyRequest $request, $id): \Illuminate\Http\RedirectResponse
    {
        /**
         * make sure that the token is valid and matches the order id
         */
        if (!$request->has('token') && $request->get('token') !== hash('sha256', $id)) {
            abort(403);
        }

        $order = Order::find($id);

        if (!$order) {
            abort(404);
        }

        /**
         * update the order's due date
         */
        $order->update([
            'due_date' => $request->get('due_date'),
        ]);

        /**
         * send email to PPM that order is not ready to be picked up
         */
        Mail::to('orders@proproject.co.za')->send(new OrderConfirmationResponse3($order, $request->get('reason')));

        return redirect()->route('confirmation', [
            'title' => 'Thank you!',
            'message' => 'The order has been marked as not yet ready. Our staff has been notified of the new due date.',
        ]);
    }
}
