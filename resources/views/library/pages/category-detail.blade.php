@extends('library.layouts.app')

@section('title', 'Kategori: ' . $category->name . ' - E-Library')

@section('content')
    <div class="bg-white border-b-2 border-black">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <nav class="flex items-center space-x-2 text-sm text-black uppercase font-bold">
                <a href="{{ route('home') }}" class="text-accent hover:brightness-90">home</a>
                <i class="fas fa-chevron-right"></i>
                <a href="{{ route('categories.index') }}" class="text-accent hover:brightness-90">semua kategori</a>
                <i class="fas fa-chevron-right"></i>
                <span>{{ $category->name }}</span>
            </nav>
        </div>
    </div>

    <header class="py-12 bg-white border-b-2 border-black">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-accent uppercase mb-4">{{ $category->name }}</h1>
            <p class="text-black max-w-2xl mx-auto text-lg">
                Jelajahi semua buku dan referensi yang berkaitan dengan {{ $category->name }}.
            </p>
        </div>
    </header>

    <section class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form action="{{ route('categories.show', $category->slug) }}" method="GET" class="flex flex-col sm:flex-row justify-between items-center bg-white p-4 border-2 border-black mb-8">
                <p class="text-black mb-4 sm:mb-0">
                    Menampilkan <span class="font-bold text-black">{{ $books->firstItem() ?? 0 }}-{{ $books->lastItem() ?? 0 }}</span> dari <span class="font-bold text-black">{{ $books->total() }}</span> buku dalam kategori ini
                </p>
                <div class="flex items-center space-x-2">
                    <span class="text-black text-sm font-bold uppercase">Urutkan:</span>
                    <select name="sort" onchange="this.form.submit()" class="border border-black rounded-lg py-1 px-3 text-black focus:ring-accent focus:border-accent outline-none">
                        <option value="latest" @selected(request('sort') == 'latest')>Terbaru</option>
                        <option value="oldest" @selected(request('sort') == 'oldest')>Terlama</option>
                        <option value="az" @selected(request('sort') == 'az')>A-Z</option>
                    </select>
                </div>
            </form>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-12">
                @forelse($books as $book)
                    <a href="{{ route('book.show', $book->slug) }}" class="group">
                        <div class="bg-white overflow-hidden border-2 border-black flex flex-col h-full brutal-shadow">
                            <div class="relative h-64 bg-gray-200 overflow-hidden">
                                <img src="{{ $book->cover_image ? asset('storage/' . $book->cover_image) : 'https://via.placeholder.com/300x400?text=' . urlencode($book->title) }}" alt="{{ $book->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-smooth">
                            </div>
                            <div class="p-4 flex-grow flex flex-col justify-between">
                                <div>
                                    <h3 class="font-bold text-black text-sm mb-1 uppercase line-clamp-2">{{ $book->title }}</h3>
                                    <p class="text-black text-xs mb-3">{{ $book->author }}</p>
                                </div>
                                <div class="flex items-center justify-between mt-2 pt-3 border-t border-black">
                                    @if($book->stock > 0)
                                        <span class="inline-block text-xs font-bold bg-green-600 text-white px-2 py-1 rounded">Tersedia ({{ $book->stock }})</span>
                                    @else
                                        <span class="inline-block text-xs font-bold bg-red-600 text-white px-2 py-1 rounded">Habis</span>
                                    @endif
                                    <span class="text-xs text-black bg-gray-200 px-2 py-1 truncate uppercase">{{ $category->name }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full text-center py-12 text-gray-500">
                        Belum ada buku di kategori ini.
                    </div>
                @endforelse
            </div>

            <div class="flex justify-center">
                {{ $books->links() }}
            </div>
        </div>
    </section>
@endsection
