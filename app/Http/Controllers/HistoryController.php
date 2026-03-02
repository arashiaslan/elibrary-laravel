<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        $query = Borrowing::with('book')->where('user_id', $userId);

        // Filter Status Tab
        if ($request->filter == 'borrowed') {
            $query->whereIn('status', ['borrowed', 'return_pending']);
        } elseif ($request->filter == 'returned') {
            $query->where('status', 'returned');
        } elseif ($request->filter == 'overdue') {
            $query->where('status', 'late');
        }

        // Filter Pencarian Judul/Penulis
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('book', function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%");
            });
        }

        $transactions = $query->latest()->paginate(5)->withQueryString();

        // Data untuk Summary Cards
        $activeCount = Borrowing::where('user_id', $userId)->whereIn('status', ['borrowed', 'return_pending'])->count();
        $dueSoonCount = Borrowing::where('user_id', $userId)
                                ->where('status', 'borrowed')
                                ->whereBetween('due_date', [Carbon::now(), Carbon::now()->addDays(7)])
                                ->count();
        $lateCount = Borrowing::where('user_id', $userId)->where('status', 'late')->count();
        
        // Logika Denda (Contoh: Rp 5.000 per hari keterlambatan)
        $totalFine = 0;
        $overdueTransactions = Borrowing::with('book')->where('user_id', $userId)->where('status', 'late')->get();
        
        foreach ($overdueTransactions as $trx) {
            $daysLate = Carbon::now()->diffInDays(Carbon::parse($trx->due_date));
            $fineAmount = $daysLate * 5000;
            $trx->fine_amount = $fineAmount; // Simpan sementara di object untuk ditampilkan
            $trx->days_late = $daysLate;
            $totalFine += $fineAmount;
        }

        return view('library.pages.transaction-history', compact(
            'transactions', 'activeCount', 'dueSoonCount', 'lateCount', 'totalFine', 'overdueTransactions'
        ));
    }

    public function returnBook(Borrowing $borrowing)
    {
        // Pastikan hanya pemilik yang bisa mengklik
        if ($borrowing->user_id !== Auth::id()) {
            abort(403);
        }

        if ($borrowing->status === 'returned' || $borrowing->status === 'return_pending') {
            return back()->with('error', 'Buku sudah dikembalikan atau sedang menunggu konfirmasi.');
        }

        // Ubah status menjadi pending
        $borrowing->update([
            'status' => 'return_pending'
        ]);

        return back()->with('success', 'Pengajuan berhasil! Harap bawa buku fisik ke meja Pustakawan untuk dikonfirmasi.');
    }
}
