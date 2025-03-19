<?php

namespace App\Http\Controllers;

use App\Mail\DonationInvoiceMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Donation;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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
            // Log::info('Memulai proses pembuatan donasi', ['user_id' => $request->user_id]);

            $donation = Donation::create([
                'user_id' => $request->user_id,
                'name' => $request->name,
                'email' => $request->email,
                'amount' => $request->amount,
                'message' => $request->message,
                'status' => 'pending',
            ]);

            // Log::info( 'Donasi berhasil dibuat', ['donation_id' => $donation->id]);

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
                'invoice_duration' => 172800,
                'success_redirect_url' => route('donations.success', ['id' => $donation->id]),
            ]);

            $api = new InvoiceApi();
            $generateInvoice = $api->createInvoice($createInvoice);

            // Log::info('Invoice berhasil dibuat', ['invoice_id' => $generateInvoice['id']]);

            $payment = Payment::create([
                'donation_id' => $donation->id,
                'payment_id' => $generateInvoice['id'],
                'payment_method' => 'xendit',
                'status' => 'pending',
                'payment_url' => $generateInvoice['invoice_url'],
            ]);

            DB::commit();

            // Log::info('Pembayaran berhasil dibuat', ['payment_id' => $payment->id]);

            Mail::to($request->email)->queue(new DonationInvoiceMail($donation, $payment));

            return redirect($payment->payment_url);

        } catch (\Throwable $th) {
            Log::error('Terjadi kesalahan saat membuat donasi', ['error' => $th->getMessage()]);
            DB::rollBack();
            return back()->with('error', 'Failed to create donation');
        }
    }

    public function callbackXendit(Request $request){

        // Log::info('Callback received', ['request' => $request->all()]);
        $getToken = $request->header('x-callback-token');
        $callbackToken = env('XENDIT_CALLBACK_TOKEN');

        // Log::info('Headers received', [
        //     'x-callback-token' => $getToken,
        //     'XENDIT_CALLBACK_TOKEN' => $callbackToken
        // ]);

        if(!$callbackToken || $getToken !== $callbackToken){
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $payment = Payment::where('payment_id', $request->id)->first();

        if (!$payment) {
            Log::error('Payment not found', ['payment_id' => $request->id]);
            return response()->json(['message' => 'Payment not found'], 404);
        }

        $payment->update([
            'status' => $request->status === 'PAID' ? 'complete' : 'failed',
        ]);

        if($request->status === 'PAID'){
            $donation = Donation::find($payment->donation_id);
            $donation->update([
                'status' => 'complete',
            ]);
        }

        Mail::to($donation->email)->queue(new DonationInvoiceMail($donation, $payment));

        return response()->json(['message' => 'Payment updated'], 200);
    }

    public function success($id) {
        $donation = Donation::find($id);
        return view('success', compact('donation'));
    }
}
