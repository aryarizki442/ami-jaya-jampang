<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\UserAddress;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

class AdminPaymentController extends Controller
{
    public function index()
    {
        $paymentStatuses = [
            'pending'            => 'Menunggu Pembayaran',
            'paid'               => 'Pembayaran Berhasil',
            'failed'             => 'Pembayaran Gagal',
            'expired'            => 'Pembayaran Kedaluwarsa',
        ];

        return view('backend.pages.payment.index', compact('paymentStatuses'));
    }
public function detail($id)
{
    $payment = \App\Models\Payment::with([

        // PAYMENT
        'paymentMethod',

        // ORDER
        'order',

        // USER
        'order.user',

        // ADDRESS
        'order.address',

        // ITEMS + PRODUCT
        'order.items',
        'order.items.product',

    ])->findOrFail($id);

    return view(
        'backend.pages.payment.detail',
        compact('payment')
    );
}
}
