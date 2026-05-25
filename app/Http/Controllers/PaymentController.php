<?php

namespace App\Http\Controllers;

use App\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $payments = Payment::with('invoice')
            ->orderBy('payment_date', 'desc')
            ->paginate($request->get('per_page', 15));
            
        return response()->json($payments);
    }
}
