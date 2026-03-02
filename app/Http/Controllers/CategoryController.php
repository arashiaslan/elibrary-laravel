<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // 1. Menampilkan daftar semua kategori (category.blade.php)
    public function index()
    {
        // Ambil semua kategori beserta jumlah buku di dalamnya
        $categories = Category::withCount('books')->get();
        
        return view('library.pages.category', compact('categories'));
    }

    // 2. Menampilkan buku berdasarkan kategori yang dipilih (category-detail.blade.php)
    public function show(Request $request, $slug)
    {
        // Cari kategori berdasarkan slug
        $category = Category::where('slug', $slug)->firstOrFail();

        // Mulai query buku berdasarkan kategori ID
        $query = Book::where('category_id', $category->id);

        // Fitur Pengurutan (Sorting)
        if ($request->sort == 'az') {
            $query->orderBy('title', 'asc');
        } elseif ($request->sort == 'oldest') {
            $query->oldest();
        } else {
            $query->latest(); // Default
        }

        // Pagination 8 buku per halaman
        $books = $query->paginate(8)->withQueryString();

        return view('library.pages.category-detail', compact('category', 'books'));
    }

}
