<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    
    public function index(Request $request)
    {
        $query = Book::with('category');

        // 1. Fitur Pencarian (Judul atau Penulis)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%");
            });
        }

        // 2. Filter Kategori (Menerima array ID kategori dari checkbox)
        if ($request->filled('categories')) {
            $query->whereIn('category_id', $request->categories);
        }

        // 3. Filter Ketersediaan Stok
        if ($request->status == 'available') {
            $query->where('stock', '>', 0);
        }

        // 4. Pengurutan (Sorting)
        if ($request->sort == 'az') {
            $query->orderBy('title', 'asc');
        } elseif ($request->sort == 'oldest') {
            $query->oldest();
        } else {
            $query->latest(); // Default: Terbaru
        }

        // Pagination: Tampilkan 9 buku per halaman & bawa parameter URL (untuk filter)
        $books = $query->paginate(9)->withQueryString();
        
        // Ambil semua kategori beserta jumlah bukunya untuk ditampilkan di Sidebar
        $categories = Category::withCount('books')->get();

        return view('library.pages.books', compact('books', 'categories'));
    }

    // Menampilkan halaman detail buku
    public function show($slug)
    {
        // Cari buku berdasarkan slug
        $book = Book::with('category')->where('slug', $slug)->firstOrFail();
        
        // Ambil buku terkait di kategori yang sama (kecuali buku ini sendiri)
        $relatedBooks = Book::where('category_id', $book->category_id)
                            ->where('id', '!=', $book->id)
                            ->take(3)
                            ->get();

        return view('library.pages.book-detail', compact('book', 'relatedBooks'));
    }

    // Memproses form peminjaman dari Modal
    public function borrow(Request $request, Book $book)
    {
        // Validasi: Pastikan user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk meminjam buku.');
        }

        // Validasi: Cek apakah stok masih ada
        if ($book->stock < 1) {
            return back()->with('error', 'Maaf, stok buku sedang habis.');
        }

        // Validasi: Cek apakah user sedang meminjam buku ini dan belum dikembalikan
        $alreadyBorrowed = Borrowing::where('user_id', Auth::id())
                                    ->where('book_id', $book->id)
                                    ->whereIn('status', ['borrowed', 'late'])
                                    ->exists();

        if ($alreadyBorrowed) {
            return back()->with('error', 'Anda masih meminjam buku ini dan belum mengembalikannya.');
        }

        // Masukkan data ke tabel borrowings
        Borrowing::create([
            'user_id' => Auth::id(),
            'book_id' => $book->id,
            'borrow_date' => now(),
            'due_date' => now()->addDays(7), // Peminjaman default 7 hari
            'status' => 'borrowed',
        ]);

        // Kurangi stok buku
        $book->decrement('stock');

        return back()->with('success', 'Buku berhasil dipinjam! Silakan konfirmasi ke Perpustakaan untuk mengambil buku.');
    }

    
}
