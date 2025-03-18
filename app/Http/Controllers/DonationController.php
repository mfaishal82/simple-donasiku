<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Donation;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;
use Xendit\Configuration;
use Xendit\Invoice\CreateInvoiceRequest;
use Xendit\Invoice\InvoiceApi;
use Xendit\Invoice\InvoiceItem;

class DonationController extends Controller
{

    public function __construct()
    {
        Configuration::setXenditKey(env('XENDIT_SECRET_KEY'));
    }

    public function index($username)
    {
        $user = User::where('username', $username)->firstOrFail();
        return view('donation', compact('user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'amount' => ['required', 'integer', 'min:100'],
            'message' => ['required', 'string'],
        ]);

        DB::beginTransaction();

        try {
            Log::info('Memulai proses pembuatan donasi', ['user_id' => $request->user_id]);

            $donation = Donation::create([
                'user_id' => $request->user_id,
                'name' => $request->name,
                'email' => $request->email,
                'amount' => $request->amount,
                'message' => $request->message,
                'status' => 'pending',
            ]);

            Log::info('Donasi berhasil dibuat', ['donation_id' => $donation->id]);

            $invoiceItems = new InvoiceItem([
                'name' => 'Donation',
                'price' => $request->amount,
                'quantity' => 1,
            ]);

            $createInvoice = new CreateInvoiceRequest([
                'external_id' => 'donation-' . $donation->id,
                'payer_email' => $request->email,
                'amount' => $request->amount,
                'items' => [$invoiceItems],
                'invoice_duration' => 24,
                'success_redirect_url' => route('donations.success', ['id' => $donation->id]),
            ]);

            $api = new InvoiceApi();
            $generateInvoice = $api->createInvoice($createInvoice);

            Log::info('Invoice berhasil dibuat', ['invoice_id' => $generateInvoice['id']]);

            $payment = Payment::create([
                'donation_id' => $donation->id,
                'payment_id' => $generateInvoice['id'],
                'payment_method' => 'xendit',
                'status' => 'pending',
                'payment_url' => $generateInvoice['invoice_url'],
            ]);

            DB::commit();

            Log::info('Pembayaran berhasil dibuat', ['payment_id' => $payment->id]);

            return redirect($payment->payment_url);

        } catch (\Throwable $th) {
            Log::error('Terjadi kesalahan saat membuat donasi', ['error' => $th->getMessage()]);
            DB::rollBack();
            return back()->with('error', 'Failed to create donation');
        }
    }
}
