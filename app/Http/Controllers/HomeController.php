<?php

namespace App\Http\Controllers;

use App\Models\Book;
// use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Mengambil 1 Buku Random
        $featuredBook = Book::with('category')->inRandomOrder()->first();

        // Mengambil 4 buku terbaru
        $latestBooks = Book::with('category')->latest()->take(4)->get();

        // Mengambil 4 buku "Trending" (Sebagai contoh, kita ambil secara acak)
        $trendingBooks = Book::with('category')->inRandomOrder()->take(4)->get();

        return view('library.pages.home', compact('featuredBook', 'latestBooks', 'trendingBooks'));
    }
}
