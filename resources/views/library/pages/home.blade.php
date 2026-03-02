@extends('library.layouts.app')

@section('title', 'Home - E-Library')

@section('content')
    <!-- Hero Banner Section -->
    <section class="bg-white py-20 border-b-2 border-black">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">

                <!-- Hero Text Content -->
                <div>
                    <h1 class="text-4xl md:text-5xl font-bold mb-4 leading-tight uppercase text-accent">Welcome to Your Digital Library</h1>
                    <p class="text-lg text-black mb-6 leading-relaxed">
                        Discover, explore, and borrow from thousands of books. Your knowledge, just a click away. Start building your reading collection today.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="#" class="px-6 py-3 bg-accent text-white font-bold border-2 border-accent hover:brightness-90 transition-smooth text-center">
                            EXPLORE BOOKS
                        </a>
                        <a href="#" class="px-6 py-3 bg-white text-accent font-bold border-2 border-accent hover:brightness-90 transition-smooth text-center">
                            LEARN MORE
                        </a>
                    </div>
                </div>

                <!-- Hero Image -->
                <div class="flex justify-center">
                    <div class="relative w-64 h-80 bg-gray-200 overflow-hidden border-2 border-black brutal-shadow">
                        <img 
                            src="{{ asset('storage/' . $featuredBook->cover_image) }}" 
                            alt="Featured Book" 
                            class="w-full h-full object-cover"
                        >
                        <div class="absolute top-4 right-4 bg-accent text-white px-3 py-1 text-sm font-semibold">
                            FEATURED
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- Latest Books Section --}}
     <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex justify-between items-center mb-12">
                <div>
                    <h2 class="text-3xl md:text-4xl font-bold text-black mb-2 uppercase">Latest Additions</h2>
                    <p class="text-black">Freshly added books to our collection</p>
                </div>
                <a href="#" class="hidden md:flex items-center space-x-2 text-accent hover:brightness-90 transition-smooth font-bold uppercase">
                    <span>View All</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                
                @forelse ($latestBooks as $book)
                <a href="#" class="group">
                    <div class="bg-white overflow-hidden border-2 border-black brutal-shadow">
                        <div class="relative h-64 bg-gray-200 overflow-hidden">
                            <img 
                                src="{{ asset('storage/' . $book->cover_image) }}" 
                                alt="{{ $book->title }}" 
                                class="w-full h-full object-cover"
                            >
                            <div class="absolute top-2 right-2 bg-accent text-white px-2 py-1 text-xs font-bold">
                                NEW
                            </div>
                        </div>

                        <div class="p-4">
                            <h3 class="font-bold text-black text-sm mb-1 truncate uppercase">
                                {{ $book->title }}
                            </h3>
                            <p class="text-black text-xs mb-3">{{ $book->author }}</p>
                            <div class="flex items-center justify-between">
                                @if($book->stock > 0)
                                    <span class="inline-block text-xs font-semibold bg-green-600 text-white px-2 py-1 rounded">In Stock ({{ $book->stock }})</span>
                                @else
                                    <span class="inline-block text-xs font-semibold bg-red-600 text-white px-2 py-1 rounded">Out of Stock</span>
                                @endif
                                <span class="text-xs text-black">{{ $book->category->name ?? 'Uncategorized' }}</span>
                            </div>
                        </div>
                    </div>
                </a>
                @empty
                    <div class="col-span-full text-center text-black">
                        Belum ada buku yang ditambahkan.
                    </div>
                @endforelse

            </div>
            </div>
    </section>

    {{-- Trending Books Section --}}
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <h2 class="text-3xl md:text-4xl font-bold text-black mb-12 uppercase">Trending This Week</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                @foreach ($trendingBooks as $book)
                <a href="#" class="group">
                    <div class="bg-white overflow-hidden border-2 border-black brutal-shadow">
                        <div class="relative h-64 bg-gray-200 overflow-hidden">
                            <img 
                                src="{{ $book->cover_image ? asset('storage/' . $book->cover_image) : 'https://via.placeholder.com/300x400?text=' . urlencode($book->title) }}" 
                                alt="{{ $book->title }}" 
                                class="w-full h-full object-cover"
                            >
                            <div class="absolute top-2 left-2 bg-accent text-white px-2 py-1 text-xs font-bold">
                                TRENDING
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="font-bold text-black text-sm mb-1 truncate uppercase">
                                {{ $book->title }}
                            </h3>
                            <p class="text-black text-xs mb-3">{{ $book->author }}</p>
                            <div class="flex items-center justify-between">
                                @if($book->stock > 0)
                                    <span class="inline-block text-xs font-semibold bg-green-600 text-white px-2 py-1 rounded">In Stock</span>
                                @else
                                    <span class="inline-block text-xs font-semibold bg-red-600 text-white px-2 py-1 rounded">Out of Stock</span>
                                @endif
                                <span class="text-xs text-black">{{ $book->category->name ?? 'Uncategorized' }}</span>
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach

            </div>
        </div>
    </section>


    <!-- Features Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Section Header -->
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-black mb-4 uppercase">Why Choose E-Library?</h2>
                <p class="text-black text-lg max-w-2xl mx-auto">
                    Experience reading like never before with our comprehensive digital library platform
                </p>
            </div>

            <!-- Features Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                <!-- Feature 1 -->
                <div class="bg-white border-2 border-black p-8 text-center brutal-shadow">
                    <div class="w-16 h-16 bg-accent text-white flex items-center justify-center mx-auto mb-4 text-2xl">
                        <i class="fas fa-book"></i>
                    </div>
                    <h3 class="text-xl font-bold text-black mb-2 uppercase">Vast Collection</h3>
                    <p class="text-black">
                        Access thousands of books across multiple genres and categories. Find your next favorite read instantly.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-white border-2 border-black p-8 text-center brutal-shadow">
                    <div class="w-16 h-16 bg-accent text-white flex items-center justify-center mx-auto mb-4 text-2xl">
                        <i class="fas fa-lock"></i>
                    </div>
                    <h3 class="text-xl font-bold text-black mb-2 uppercase">Secure & Private</h3>
                    <p class="text-black">
                        Your reading preferences and library are kept completely private and secure with enterprise-grade encryption.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-white border-2 border-black p-8 text-center brutal-shadow">
                    <div class="w-16 h-16 bg-accent text-white flex items-center justify-center mx-auto mb-4 text-2xl">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h3 class="text-xl font-bold text-black mb-2 uppercase">Read Anywhere</h3>
                    <p class="text-black">
                        Access your books on any device - desktop, tablet, or mobile. Seamless reading across all platforms.
                    </p>
                </div>

            </div>
        </div>
    </section>

@endsection