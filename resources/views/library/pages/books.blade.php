
@extends('library.layouts.app')

@section('title', 'Katalog Buku - E-Library')

@section('content')
    <div class="bg-white border-b-2 border-black">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <nav class="flex items-center space-x-2 text-sm text-black uppercase font-bold">
                <a href="{{ route('home') }}" class="text-accent hover:brightness-90">home</a>
                <i class="fas fa-chevron-right"></i>
                <span>katalog buku</span>
            </nav>
        </div>
    </div>

    <header class="py-8 bg-white border-b-2 border-black">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-2xl md:text-4xl font-bold text-accent uppercase mb-2">Jelajahi Koleksi Kami</h1>
            <p class="text-black max-w-2xl">
                Temukan ribuan buku dari berbagai kategori. Mulai dari fiksi, sains, hingga modul kejuruan.
            </p>
        </div>
    </header>

    <section class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <form action="{{ route('books.index') }}" method="GET" class="flex flex-col lg:flex-row gap-8">

                <div class="w-full lg:w-1/4">
                    <div class="bg-white border-2 border-black p-6 sticky top-20">
                        
                        <div class="mb-6">
                            <h3 class="font-bold uppercase text-accent mb-3">Cari Buku</h3>
                            <div class="relative">
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Judul atau Penulis..." class="w-full pl-10 pr-4 py-2 border-2 border-black bg-white focus:outline-none">
                                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                            </div>
                        </div>

                        <hr class="border-black mb-6">

                        <div class="mb-6">
                            <h3 class="font-bold uppercase text-accent mb-3">Kategori</h3>
                            <div class="space-y-2 max-h-48 overflow-y-auto">
                                @foreach($categories as $category)
                                <label class="flex items-center space-x-3 cursor-pointer group">
                                    <input type="checkbox" name="categories[]" value="{{ $category->id }}" 
                                        @checked(in_array($category->id, request('categories', [])))
                                        class="form-checkbox h-4 w-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                                    <span class="text-gray-700 group-hover:text-blue-600 transition-smooth">
                                        {{ $category->name }} ({{ $category->books_count }})
                                    </span>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <hr class="border-gray-100 mb-6">

                        <div class="mb-6">
                            <h3 class="font-bold uppercase text-accent mb-3">Ketersediaan</h3>
                            <div class="space-y-2">
                                <label class="flex items-center space-x-3 cursor-pointer group">
                                    <input type="radio" name="status" value="all" @checked(request('status', 'all') == 'all') class="form-radio h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                    <span class="text-gray-700 group-hover:text-blue-600 transition-smooth">Tampilkan Semua</span>
                                </label>
                                <label class="flex items-center space-x-3 cursor-pointer group">
                                    <input type="radio" name="status" value="available" @checked(request('status') == 'available') class="form-radio h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                    <span class="text-gray-700 group-hover:text-blue-600 transition-smooth">Hanya yang Tersedia</span>
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-black text-white font-bold py-2 border-2 border-black hover:bg-gray-800">
                            Terapkan Filter
                        </button>

                    </div>
                </div>

                <div class="w-full lg:w-3/4">
                    
                    <div class="flex flex-col sm:flex-row justify-between items-center bg-white p-4 border-2 border-black mb-6">
                        <p class="text-gray-600 mb-4 sm:mb-0">
                            Menampilkan <span class="font-bold text-gray-900">{{ $books->firstItem() ?? 0 }}-{{ $books->lastItem() ?? 0 }}</span> dari <span class="font-bold text-gray-900">{{ $books->total() }}</span> buku
                        </p>
                        <div class="flex items-center space-x-2">
                            <span class="text-gray-600 text-sm">Urutkan:</span>
                            <select name="sort" onchange="this.form.submit()" class="border-2 border-black py-1 px-3 text-black outline-none">
                                <option value="latest" @selected(request('sort') == 'latest')>Terbaru</option>
                                <option value="oldest" @selected(request('sort') == 'oldest')>Terlama</option>
                                <option value="az" @selected(request('sort') == 'az')>A-Z</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">
                        @forelse($books as $book)
                        <a href="{{ route('book.show', $book->slug) }}" class="group">
                            <div class="bg-white overflow-hidden border-2 border-black flex flex-col h-full brutal-shadow">
                                <div class="relative h-64 bg-gray-200 overflow-hidden">
                                    <img 
                                        src="{{ $book->cover_image ? asset('storage/' . $book->cover_image) : 'https://via.placeholder.com/300x400?text=' . urlencode($book->title) }}" 
                                        alt="{{ $book->title }}" 
                                        class="w-full h-full object-cover"
                                    >
                                </div>
                                <div class="p-4 flex-grow flex flex-col justify-between">
                                    <div>
                                        <h3 class="font-bold uppercase text-black text-sm mb-1 line-clamp-2">{{ $book->title }}</h3>
                                        <p class="text-gray-600 text-xs mb-3">{{ $book->author }}</p>
                                    </div>
                                    <div class="flex items-center justify-between border-t-2 border-black pt-2 mt-2">
                                        @if($book->stock > 0)
                                            <span class="text-xs font-semibold text-green-600">Tersedia ({{ $book->stock }})</span>
                                        @else
                                            <span class="text-xs font-semibold text-red-600">Habis</span>
                                        @endif
                                        <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 truncate max-w-[100px]">{{ $book->category->name ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                        @empty
                        <div class="col-span-full text-center py-12 bg-white rounded-lg border border-gray-100">
                            <i class="fas fa-search text-gray-300 text-4xl mb-3"></i>
                            <p class="text-gray-500">Buku yang Anda cari tidak ditemukan.</p>
                        </div>
                        @endforelse
                    </div>

                    <div class="mt-8 flex justify-center">
                        {{ $books->links() }}
                    </div>

                </div>
            </form>
            </div>
    </section>
@endsection
