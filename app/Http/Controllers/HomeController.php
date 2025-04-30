<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Invoice;
use App\Models\Receipt;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function __construct()
    {

        $this->middleware('permission:approval invoices')->only(['approvalPage']);
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = auth()->user();
        $totalUsers = User::count();
        $totalInvoice = Invoice::count();
        $totalReceipt = Receipt::count();
        // --- Ambil aktivitas terbaru dari Invoice ---
        if ($user->hasRole('admin pusat')) {
            $invoiceActivities = Invoice::with('creator')
                ->whereIn('status', ['Draft', 'Menunggu Approval', 'Disetujui', 'Ditolak'])
                ->latest()
                ->take(5)
                ->get();

            $receiptActivities = Receipt::with('invoice.creator')
                ->whereIn('status', ['Draft', 'Menunggu Approval', 'Disetujui', 'Ditolak'])
                ->latest()
                ->take(5)
                ->get();
        } else {
            $invoiceActivities = Invoice::with('creator')
                ->where('created_by', $user->id)
                ->whereIn('status', ['Draft', 'Menunggu Approval', 'Disetujui', 'Ditolak'])
                ->latest()
                ->take(5)
                ->get();

            $receiptActivities = Receipt::with('invoice.creator')
                ->whereHas('invoice', function ($q) use ($user) {
                    $q->where('created_by', $user->id);
                })
                ->whereIn('status', ['Draft', 'Menunggu Approval', 'Disetujui', 'Ditolak'])
                ->latest()
                ->take(5)
                ->get();
        }

        return view('pages.dashboard', compact('invoiceActivities', 'receiptActivities', 'totalUsers', 'totalInvoice', 'totalReceipt'));
    }

    public function approvalPage()
    {
        $approvedInvoices = Invoice::where('status', ['Draft', 'Menunggu Approval'])->get();
        $approvedReceipts = Receipt::where('status', ['Draft', 'Menunggu Approval'])->get();

        return view('pages.approval', compact('approvedInvoices', 'approvedReceipts'));
    }
}