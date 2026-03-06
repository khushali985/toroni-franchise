<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentSetting;
use App\Models\Franchise;

class AdminPaymentController extends Controller
{
    public function index()
    {
        $franchises = Franchise::all();
        $payments = PaymentSetting::with('franchise')->get();

        return view('admin.payment.index', compact('franchises', 'payments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'franchise_id' => 'required|exists:franchises,id',
            'upi_name' => 'nullable|string|max:255',
            'qr_image' => 'required|image|mimes:png,jpg,jpeg|max:2048'
        ]);

        $file = $request->file('qr_image');
        $filename = time().'_'.$file->getClientOriginalName();
        $file->move(public_path('images/payments/qr/'), $filename);

        PaymentSetting::updateOrCreate(
            ['franchise_id' => $request->franchise_id],
            [
                'upi_name' => $request->upi_name,
                'qr_image' => 'images/payments/qr/'.$filename
            ]
        );

        return back()->with('success', 'QR Saved Successfully');
    }
}