<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\Customer;
use App\Models\Service;
use Illuminate\Http\Request;

class SubscriptionPageController extends Controller
{
    public function index()
    {
        // Mengambil semua data subscription beserta relasi customer & service-nya
        $subscriptions = Subscription::with(['customer', 'service'])->latest()->get();
        
        // Mengambil data customer dan service aktif untuk dropdown form modal
        $customers = Customer::where('status', 1)->get();
        $services = Service::where('status', 1)->get();

        return view('subscriptions.index', compact('subscriptions', 'customers', 'services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'service_id' => 'required|exists:services,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|string'
        ]);

        Subscription::create($request->all());

        return redirect('/subscriptions')->with('success', 'Subscription created successfully.');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string'
        ]);

        $subscription = Subscription::findOrFail($id);

        // --- IMPROVEMENT API 2 ---
        // Jika status saat ini sudah 'dismantle', tidak boleh diubah ke status mana pun
        if (strtolower($subscription->status) === 'dismantle') {
            return redirect('/subscriptions')->with('error', 'Status Subscription yang sudah dismantle tidak bisa diubah!');
        }

        // --- IMPROVEMENT FRONTEND 2 (Point 2) - Validasi Sisi API ---
        // Opsional namun baik untuk keamanan: Mencegah ubah status ke status yang sama dengan saat ini
        if (strtolower($subscription->status) === strtolower($request->status)) {
            return redirect('/subscriptions')->with('error', 'Status baru tidak boleh sama dengan status saat ini.');
        }

        $subscription->update(['status' => $request->status]);

        return redirect('/subscriptions')->with('success', 'Subscription status updated to ' . $request->status . '.');
    }

    public function destroy($id)
    {
        $subscription = Subscription::findOrFail($id);
        $subscription->delete();

        return redirect('/subscriptions')->with('success', 'Subscription deleted successfully.');
    }
}