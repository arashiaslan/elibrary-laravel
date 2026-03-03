@extends('library.layouts.app')

@section('title', 'Kategori Buku - Minimalibrary')

@section('content')
    <div class="bg-white border-b-2 border-black">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <nav class="flex items-center space-x-2 text-sm text-black uppercase font-bold">
                <a href="{{ route('home') }}" class="text-accent hover:brightness-90">home</a>
                <i class="fas fa-chevron-right"></i>
                <span>semua kategori</span>
            </nav>
        </div>
    </div>

    <header class="py-8 bg-white border-b-2 border-black">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-2xl md:text-4xl font-bold text-accent uppercase">Pilih Kategori Minatmu</h1>
            <p class="mt-2 text-black max-w-2xl">
                Jelajahi koleksi perpustakaan kami yang disusun rapi berdasarkan bidang studi dan genre untuk memudahkan pencarian Anda.
            </p>
        </div>
    </header>

    <section class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

                @php
                    $styles = [
                        ['bg' => 'from-blue-500 to-indigo-600', 'icon' => 'fa-book', 'text' => 'text-blue-600', 'hover' => 'group-hover:text-blue-600'],
                        ['bg' => 'from-pink-500 to-rose-600', 'icon' => 'fa-book', 'text' => 'text-pink-600', 'hover' => 'group-hover:text-pink-600'],
                        ['bg' => 'from-emerald-500 to-teal-600', 'icon' => 'fa-book', 'text' => 'text-teal-600', 'hover' => 'group-hover:text-teal-600'],
                        ['bg' => 'from-purple-500 to-violet-600', 'icon' => 'fa-book', 'text' => 'text-purple-600', 'hover' => 'group-hover:text-purple-600'],
                        ['bg' => 'from-amber-500 to-orange-600', 'icon' => 'fa-book', 'text' => 'text-orange-600', 'hover' => 'group-hover:text-orange-600'],
                        ['bg' => 'from-stone-500 to-stone-700', 'icon' => 'fa-book', 'text' => 'text-stone-600', 'hover' => 'group-hover:text-stone-600'],
                    ];
                @endphp

                @forelse($categories as $index => $cat)
                    @php 
                        // Mengulang gaya jika jumlah kategori lebih dari 6
                        $style = $styles[$index % count($styles)]; 
                    @endphp

                    <a href="{{ route('categories.show', $cat->slug) }}" class="group block h-full">
                        <div class="bg-white border-2 border-black overflow-hidden h-full flex flex-col brutal-shadow">
                            <div class="h-24 flex items-center justify-center">
                                <div class="w-12 h-12 {{ $style['bg'] }} flex items-center justify-center">
                                    <i class="fas {{ $style['icon'] }} text-xl {{ $style['text'] }}"></i>
                                </div>
                            </div>
                            <div class="p-5 flex-grow flex flex-col justify-between text-center">
                                <div>
                                    <h3 class="text-lg font-bold uppercase {{ $style['text'] }} mb-1">{{ $cat->name }}</h3>
                                    <p class="text-xs text-white inline-block px-2 py-1 mt-2 bg-accent">{{ $cat->books_count }} Buku</p>
                                </div>
                                <div class="mt-4 text-sm font-bold {{ $style['text'] }} uppercase">
                                    Lihat Koleksi <i class="fas fa-arrow-right ml-1"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full text-center py-12 text-gray-500">
                        Belum ada kategori yang dibuat.
                    </div>
                @endforelse

            </div>
        </div>
    </section>
@endsection

