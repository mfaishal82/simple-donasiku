<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Donation;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;

class DonationController extends Controller
{
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

            // Konfigurasi Xendit secara manual
            $apiKey = env('XENDIT_SECRET_KEY');
            $headers = [
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . base64_encode($apiKey . ':')
            ];

            // Data untuk membuat invoice
            $data = [
                'external_id' => 'donation-' . $donation->id,
                'payer_email' => $request->email,
                'description' => 'Donation from ' . $request->name,
                'amount' => $request->amount,
                'invoice_duration' => 86400, // 24 jam dalam detik
                'success_redirect_url' => route('donations.success', ['id' => $donation->id]),
                'items' => [
                    [
                        'name' => 'Donation',
                        'price' => $request->amount,
                        'quantity' => 1
                    ]
                ]
            ];

            // Membuat HTTP client dengan Guzzle
            $client = new \GuzzleHttp\Client();
            $response = $client->request('POST', 'https://api.xendit.co/v2/invoices', [
                'headers' => $headers,
                'json' => $data,
            ]);

            $responseBody = json_decode($response->getBody(), true);

            Log::info('Invoice berhasil dibuat', ['invoice_id' => $responseBody['id']]);

            $payment = Payment::create([
                'donation_id' => $donation->id,
                'payment_id' => $responseBody['id'],
                'payment_method' => 'xendit',
                'status' => 'pending',
                'payment_url' => $responseBody['invoice_url'],
            ]);

            DB::commit();

            Log::info('Pembayaran berhasil dibuat', ['payment_id' => $payment->id]);

            return redirect($payment->payment_url);

        } catch (\GuzzleHttp\Exception\RequestException $e) {
            Log::error('Xendit API Error', [
                'error_code' => $e->getCode(),
                'error_message' => $e->getMessage(),
                'error_details' => $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : null
            ]);
            DB::rollBack();
            return back()->with('error', 'Payment gateway error');
        } catch (\Throwable $th) {
            Log::error('Terjadi kesalahan saat membuat donasi', ['error' => $th->getMessage()]);
            DB::rollBack();
            return back()->with('error', 'Failed to create donation');
        }
    }
}