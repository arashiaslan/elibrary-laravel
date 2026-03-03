@extends('library.layouts.app')

@section('title', $book->title . ' - Minimalibrary')

@section('content')
    <div>
        @if(session('success'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                <div class="bg-green-600 text-white p-4 mb-4 rounded flex items-center space-x-2">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                <div class="bg-red-600 text-white p-4 mb-4 rounded flex items-center space-x-2">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <div class="bg-white border-b-2 border-black">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <nav class="flex items-center space-x-2 text-sm text-black uppercase font-bold">
                    <a href="{{ route('home') }}" class="text-accent hover:brightness-90">home</a>
                    <i class="fas fa-chevron-right"></i>
                    <a href="{{ route('books.index') }}" class="text-accent hover:brightness-90">katalog buku</a>
                    <i class="fas fa-chevron-right"></i>
                    <span>{{ $book->title }}</span>
                </nav>
            </div>
        </div>
        <section class="py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                    <div class="lg:col-span-1">
                        <div class="sticky top-20">
                            <div class="bg-gray-200 overflow-hidden mb-6 border-2 border-black brutal-shadow">
                                <img 
                                    src="{{ $book->cover_image ? asset('storage/' . $book->cover_image) : 'https://via.placeholder.com/400x500?text=' . urlencode($book->title) }}" 
                                    alt="{{ $book->title }}" 
                                    class="w-full h-auto object-cover"
                                >
                            </div>

                            @if($book->stock > 0)
                                <div class="bg-green-600 text-white p-4 mb-6 rounded">
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-check-circle"></i>
                                        <div>
                                            <p class="font-bold">Tersedia</p>
                                            <p class="text-sm">{{ $book->stock }} eksemplar tersedia</p>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="bg-red-600 text-white p-4 mb-6 rounded">
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-times-circle"></i>
                                        <div>
                                            <p class="font-bold">Stok Habis</p>
                                            <p class="text-sm">Buku sedang dipinjam semua</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="space-y-3 mb-6">
                                <a href="#" class="flex items-center justify-center space-x-2 w-full bg-accent text-white font-semibold py-3 rounded-lg hover:brightness-90 transition-smooth">
                                    <i class="fas fa-book-open"></i>
                                    <span>Baca Sinopsis</span>
                                </a>
                                
                                <button 
                                    type="button"
                                    onclick="openModal()"
                                    @if($book->stock < 1) disabled @endif
                                    class="flex items-center justify-center space-x-2 w-full font-semibold py-3 rounded-lg transition-smooth 
                                    {{ $book->stock > 0 ? 'bg-accent text-white hover:brightness-90' : 'bg-gray-300 text-gray-500 cursor-not-allowed' }}">
                                    <i class="fas fa-plus-circle"></i>
                                    <span>Pinjam Buku</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-2">
                        <h1 class="text-4xl font-bold text-accent uppercase mb-2">{{ $book->title }}</h1>
                        <p class="text-xl text-black mb-4">oleh {{ $book->author }}</p>

                        <div class="bg-white border-2 border-black p-6 mb-8 mt-6">
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                                <div>
                                    <p class="text-sm text-gray-600 font-semibold mb-1">Penerbit</p>
                                    <p class="text-lg font-semibold text-gray-900">{{ $book->publisher ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 font-semibold mb-1">Kategori</p>
                                    <p class="text-lg font-semibold text-gray-900">{{ $book->category->name ?? 'Umum' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 font-semibold mb-1">Tanggal Rilis</p>
                                    <p class="text-lg font-semibold text-gray-900">{{ $book->created_at->format('Y') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-8">
                            <h2 class="text-2xl font-bold text-accent mb-4">Sinopsis</h2>
                            <p class="text-gray-700 leading-relaxed mb-4 whitespace-pre-line">
                                {{ $book->synopsis ?? 'Belum ada sinopsis untuk buku ini.' }}
                            </p>
                        </div>

                        @if($relatedBooks->count() > 0)
                        <div>
                            <h2 class="text-2xl font-bold text-accent uppercase mb-6">Buku Serupa</h2>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach($relatedBooks as $related)
                                <a href="{{ route('book.show', $related->slug) }}" class="group">
                                    <div class="bg-white overflow-hidden border-2 border-black brutal-shadow">
                                        <div class="relative h-40 bg-gray-200 overflow-hidden">
                                            <img 
                                                src="{{ $related->cover_image ? asset('storage/' . $related->cover_image) : 'https://via.placeholder.com/200x300?text=' . urlencode($related->title) }}" 
                                                alt="{{ $related->title }}" 
                                                class="w-full h-full object-cover group-hover:scale-105 transition-smooth"
                                            >
                                        </div>
                                        <div class="p-3">
                                            <h4 class="font-semibold text-gray-900 text-xs mb-1 group-hover:text-blue-600 transition-smooth truncate">{{ $related->title }}</h4>
                                            <p class="text-gray-600 text-xs">{{ $related->author }}</p>
                                        </div>
                                    </div>
                                </a>
                                @endforeach
                            </div>
                        </div>
                        @endif

                    </div>
                </div>
            </div>
        </section>

        <div id="modalOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 transition-opacity hidden" onclick="closeModal()"></div>
        
        <div id="borrowModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-0 pointer-events-none hidden">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md pointer-events-auto overflow-hidden transform transition-all">
                
                <div class="bg-blue-600 px-6 py-4 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-white">Konfirmasi Peminjaman</h3>
                    <button type="button" onclick="closeModal()" class="text-white hover:text-gray-200 focus:outline-none">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div class="p-6">
                    <p class="text-gray-700 mb-4">Apakah Anda yakin ingin meminjam buku ini?</p>
                    <div class="bg-gray-50 p-4 rounded-lg mb-6 border border-gray-200">
                        <p class="font-semibold text-gray-900 mb-1">{{ $book->title }}</p>
                        <p class="text-sm text-gray-600">Durasi: <span class="font-medium text-blue-600">7 Hari</span></p>
                        <p class="text-sm text-gray-600">Batas Pengembalian: <span class="font-medium text-red-600">{{ now()->addDays(7)->format('d F Y') }}</span></p>
                    </div>

                    <form action="{{ route('book.borrow', $book->id) }}" method="POST" class="flex justify-end space-x-3">
                        @csrf
                        <button type="button" onclick="closeModal()" class="px-4 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg font-semibold transition-smooth">
                            Batal
                        </button>
                        
                        @auth
                            <button type="submit" class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-700 rounded-lg font-semibold transition-smooth">
                                Ya, Pinjam Sekarang
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-700 rounded-lg font-semibold transition-smooth">
                                Login untuk Meminjam
                            </a>
                        @endauth
                    </form>
                </div>
            </div>
        </div>

    </div>

    <script>
        function openModal() {
            // Hapus class 'hidden' agar elemen muncul
            document.getElementById('modalOverlay').classList.remove('hidden');
            document.getElementById('borrowModal').classList.remove('hidden');
        }

        function closeModal() {
            // Tambahkan class 'hidden' agar elemen sembunyi
            document.getElementById('modalOverlay').classList.add('hidden');
            document.getElementById('borrowModal').classList.add('hidden');
        }
    </script>
@endsection