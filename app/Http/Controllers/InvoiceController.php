<?php

namespace App\Http\Controllers;

use App\Invoice;
use App\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with('items', 'payments');
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $invoices = $query->orderBy('created_at', 'desc')->paginate($request->get('per_page', 15));
        
        return response()->json($invoices);
    }

    public function show($id)
    {
        $invoice = Invoice::with(['items', 'payments'])->findOrFail($id);
        return response()->json(['invoice' => $invoice]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id'       => 'required|integer',
            'order_number'   => 'required|string',
            'customer_name'  => 'required|string',
            'customer_email' => 'required|email',
            'subtotal'       => 'required|numeric',
            'shipping_fee'   => 'required|numeric',
            'tax'            => 'required|numeric',
            'discount'       => 'required|numeric',
            'total'          => 'required|numeric',
            'items'          => 'array',
        ]);

        $invoice = DB::transaction(function () use ($request) {
            $invoice = Invoice::create([
                'order_id'       => $request->order_id,
                'order_number'   => $request->order_number,
                'customer_name'  => $request->customer_name,
                'customer_email' => $request->customer_email,
                'subtotal'       => $request->subtotal,
                'shipping_fee'   => $request->shipping_fee,
                'tax'            => $request->tax,
                'discount'       => $request->discount,
                'total'          => $request->total,
                'status'         => 'pending',
                'issue_date'     => now(),
                'due_date'       => now()->addDays(7),
            ]);

            if ($request->has('items')) {
                foreach ($request->items as $item) {
                    $invoice->items()->create([
                        'book_id'    => $item['book_id'],
                        'book_title' => $item['book_title'],
                        'unit_price' => $item['unit_price'],
                        'quantity'   => $item['quantity'],
                        'subtotal'   => $item['subtotal'],
                    ]);
                }
            }

            return $invoice;
        });

        return response()->json([
            'message' => 'Invoice created successfully',
            'invoice' => $invoice->load('items'),
        ], 201);
    }

    public function pay(Request $request, $id)
    {
        $request->validate([
            'payment_method' => 'required|string',
        ]);

        $invoice = Invoice::findOrFail($id);
        
        if ($invoice->status === 'paid') {
            return response()->json(['message' => 'Invoice is already paid'], 422);
        }

        $payment = DB::transaction(function () use ($invoice, $request) {
            $payment = $invoice->payments()->create([
                'amount'         => $invoice->total,
                'payment_method' => $request->payment_method,
                'payment_date'   => now(),
                'status'         => 'successful',
            ]);

            $invoice->update(['status' => 'paid']);

            return $payment;
        });

        return response()->json([
            'message' => 'Invoice paid successfully',
            'invoice' => $invoice->fresh(['items', 'payments']),
            'payment' => $payment,
        ]);
    }
}
