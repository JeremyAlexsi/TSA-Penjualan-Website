<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Midtrans\Snap;
use Midtrans\Config;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        // Konfigurasi midtrans
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $orderId = 'ORDER-' . Str::upper(Str::random(10));
        $user = Auth::user();
        $total = 0;

        $items = [];

        // Hitung total & buat array item untuk Midtrans
        foreach ($user->cartItems as $item) {
            $price = $item->product->sale_price ?? $item->product->regular_price;
            $quantity = $item->quantity;
            $taxPercent = $item->product->tax_percent ?? 0;

            $subtotal = $price * $quantity;
            $tax = ($price * $taxPercent / 100) * $quantity;

            $items[] = [
                'id' => $item->product->id,
                'price' => $price,
                'quantity' => $quantity,
                'name' => $item->product->name,
            ];

            if ($tax > 0) {
                $items[] = [
                    'id' => $item->product->id . '-TAX',
                    'price' => $tax,
                    'quantity' => 1,
                    'name' => 'Pajak untuk ' . $item->product->name,
                ];
            }

            $total += $subtotal + $tax;
        }

        // ✅ Simpan transaksi utama dulu
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'order_id' => $orderId,
            'total_price' => $total,
            'status' => 'pending',
        ]);

        // Simpan setiap item ke tabel transaction_items
        foreach ($user->cartItems as $item) {
            $product = $item->product;
            $price = $product->sale_price ?? $product->regular_price;
            $quantity = $item->quantity;
            $taxPercent = $product->tax_percent ?? 0;
            $taxAmount = ($price * $taxPercent / 100) * $quantity;
            $subtotal = $price * $quantity;

            TransactionItem::create([
                'transaction_id' => $transaction->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'price' => $price,
                'quantity' => $quantity,
                'tax_percent' => $taxPercent,
                'tax_amount' => $taxAmount,
                'subtotal' => $subtotal + $taxAmount,
            ]);
        }

        // Hapus isi keranjang setelah checkout
        $user->cartItems()->delete();

        // Request ke Midtrans
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $total,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
                'phone' => $user->no_hp,
            ],
            'item_details' => $items,
        ];

        $snapToken = Snap::getSnapToken($params);

        return view('payment', compact('snapToken', 'transaction'));
    }

    public function success($order_id)
    {
        $transaction = Transaction::with('items') // Tambahkan relasi 'items'
            ->where('order_id', $order_id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('checkout_success', compact('transaction'));
    }

    public function notificationHandler(Request $request)
    {
        // Konfigurasi midtrans
        \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
        \Midtrans\Config::$isProduction = config('services.midtrans.is_production');

        // Ambil notifikasi dari Midtrans
        $notif = new \Midtrans\Notification();

        $transactionStatus = $notif->transaction_status;
        $paymentType = $notif->payment_type;
        $orderId = $notif->order_id;
        $fraudStatus = $notif->fraud_status;

        // Cari transaksi berdasarkan order_id
        $transaction = \App\Models\Transaction::where('order_id', $orderId)->first();

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        // Update payment_type jika perlu
        $transaction->payment_type = $paymentType;

        // Perbarui status transaksi
        if ($transactionStatus == 'capture') {
            if ($paymentType == 'credit_card') {
                if ($fraudStatus == 'challenge') {
                    $transaction->status = 'challenge';
                } else {
                    $transaction->status = 'success';
                }
            }
        } elseif ($transactionStatus == 'settlement') {
            $transaction->status = 'success';
        } elseif ($transactionStatus == 'pending') {
            $transaction->status = 'pending';
        } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
            $transaction->status = 'failed';
        }

        $transaction->save();

        return response()->json(['message' => 'Notification processed']);
    }
}
