<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'payment_description' => 'required|string|max:255',
            'payment_receipt' => 'required|file|mimes:pdf,jpg,png|max:2048',
            'user_id' => 'required|exists:users,id',
        ]);

        // Handle the file upload
        if ($request->hasFile('payment_receipt')) {
            $filePath = $request->file('payment_receipt')->store('payment_receipts', 'public');
            $validatedData['payment_receipt'] = $filePath;
        }

        // Save the payment record
        Payment::create($validatedData);

        return response()->json(['message' => 'Payment successfully recorded!'], 201);
    }
}
