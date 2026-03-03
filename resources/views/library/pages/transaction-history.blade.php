@extends('library.layouts.app')

@section('title', 'Transaction History - Minimalibrary')

@section('content')
    <section class="bg-white py-6 border-b-2 border-black">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-2xl font-bold text-accent uppercase">Transaction History</h1>
            <p class="mt-1 text-black">Review your borrowing history and manage your transactions.</p>
        </div>
    </section>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        @if(session('success'))
            <div class="bg-green-600 text-white p-4 mb-4 rounded flex items-center space-x-2">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-600 text-white p-4 mb-4 rounded flex items-center space-x-2">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif
    </div>

    <section class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <form action="{{ route('history.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4 mb-8">
                
                <div class="flex flex-wrap gap-2">
                    <button type="submit" name="filter" value="all" class="px-4 py-2 border-2 border-black font-bold uppercase {{ request('filter', 'all') == 'all' ? 'bg-accent text-white' : 'bg-white text-black' }}">
                        All Transactions
                    </button>
                    <button type="submit" name="filter" value="borrowed" class="px-4 py-2 border-2 border-black font-bold uppercase {{ request('filter') == 'borrowed' ? 'bg-accent text-white' : 'bg-white text-black' }}">
                        Currently Borrowed
                    </button>
                    <button type="submit" name="filter" value="returned" class="px-4 py-2 border-2 border-black font-bold uppercase {{ request('filter') == 'returned' ? 'bg-accent text-white' : 'bg-white text-black' }}">
                        Returned
                    </button>
                    <button type="submit" name="filter" value="overdue" class="px-4 py-2 border-2 border-black font-bold uppercase {{ request('filter') == 'overdue' ? 'bg-accent text-white' : 'bg-white text-black' }}">
                        Overdue
                    </button>
                </div>

                <div class="flex-1">
                    <input 
                        type="text" 
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search by book title or author..." 
                        class="w-full px-4 py-2 border-2 border-black focus:outline-none"
                        onchange="this.form.submit()"
                    >
                </div>
            </form>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white border-2 border-black p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-black text-sm font-bold uppercase mb-1">Active Borrowings</p>
                            <p class="text-3xl font-bold text-black">{{ $activeCount }}</p>
                        </div>
                        <div class="w-12 h-12 bg-accent flex items-center justify-center text-white text-xl">
                            <i class="fas fa-book-open"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white border-2 border-black p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-black text-sm font-bold uppercase mb-1">Due Within 7 Days</p>
                            <p class="text-3xl font-bold text-black">{{ $dueSoonCount }}</p>
                        </div>
                        <div class="w-12 h-12 bg-accent flex items-center justify-center text-white text-xl">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white border-2 border-black p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-black text-sm font-bold uppercase mb-1">Overdue Books</p>
                            <p class="text-3xl font-bold text-black">{{ $lateCount }}</p>
                        </div>
                        <div class="w-12 h-12 bg-accent flex items-center justify-center text-white text-xl">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white border-2 border-black p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-black text-sm font-bold uppercase mb-1">Pending Fines</p>
                            <p class="text-2xl font-bold text-black">Rp {{ number_format($totalFine, 0, ',', '.') }}</p>
                        </div>
                        <div class="w-12 h-12 bg-accent flex items-center justify-center text-white text-xl">
                            <i class="fas fa-credit-card"></i>
                        </div>
                    </div>
                </div>
            </div>

            @if($transactions->count() > 0)
                <div class="bg-white border-2 border-black overflow-hidden mb-8 hidden md:block brutal-shadow">
                    <table class="w-full">
                        <thead class="bg-white border-b-2 border-black">
                            <tr>
                                <th class="px-6 py-4 text-left font-bold text-black uppercase">Book Title</th>
                                <th class="px-6 py-4 text-left font-bold text-black uppercase">Author</th>
                                <th class="px-6 py-4 text-left font-bold text-black uppercase">Borrowed Date</th>
                                <th class="px-6 py-4 text-left font-bold text-black uppercase">Due Date</th>
                                <th class="px-6 py-4 text-left font-bold text-black uppercase">Status</th>
                                <th class="px-6 py-4 text-center font-bold text-black uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $trx)
                            <tr class="border-b border-gray-200 hover:bg-gray-50 transition-smooth {{ $trx->status == 'late' ? 'bg-red-50' : '' }}">
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <img src="{{ $trx->book->cover_image ? asset('storage/' . $trx->book->cover_image) : 'https://via.placeholder.com/50x60?text=Cover' }}" class="w-12 h-16 object-cover rounded">
                                        <a href="{{ route('book.show', $trx->book->slug) }}" class="font-semibold text-accent hover:brightness-90 transition-smooth">
                                            {{ $trx->book->title }}
                                        </a>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-700">{{ $trx->book->author }}</td>
                                <td class="px-6 py-4 text-gray-700">{{ \Carbon\Carbon::parse($trx->borrow_date)->format('M d, Y') }}</td>
                                <td class="px-6 py-4 text-gray-700 font-medium {{ $trx->status == 'late' ? 'text-red-600' : '' }}">{{ \Carbon\Carbon::parse($trx->due_date)->format('M d, Y') }}</td>
                                <td class="px-6 py-4">
                                    @if($trx->status == 'borrowed')
                                        <span class="inline-flex items-center px-3 py-1 text-sm font-medium bg-accent text-white rounded">Active</span>
                                    @elseif($trx->status == 'return_pending')
                                        <span class="inline-flex items-center px-3 py-1 text-sm font-medium bg-yellow-500 text-black rounded">Pending</span>
                                    @elseif($trx->status == 'returned')
                                        <span class="inline-flex items-center px-3 py-1 text-sm font-medium bg-gray-400 text-white rounded">Returned</span>
                                    @elseif($trx->status == 'late')
                                        <span class="inline-flex items-center px-3 py-1 text-sm font-medium bg-red-600 text-white rounded">Overdue</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($trx->status == 'borrowed' || $trx->status == 'late')
                                        <button onclick="openReturnModal({{ $trx->id }}, '{{ addslashes($trx->book->title) }}')" class="text-white font-semibold text-sm bg-accent px-3 py-1 rounded transition-smooth hover:brightness-90" title="Return Book">
                                            <i class="fas fa-undo-alt mr-1"></i> Return
                                        </button>
                                    @else
                                        <span class="text-gray-400 text-sm">-</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="md:hidden space-y-4 mb-8">
                    @foreach($transactions as $trx)
                    <div class="bg-white border-2 border-black p-4 
                        {{ $trx->status == 'returned' ? 'border-gray-400' : ($trx->status == 'late' ? 'border-red-500 bg-red-50' : ($trx->status == 'return_pending' ? 'border-yellow-500' : 'border-green-500')) }}">
                        <div class="mb-3">
                            <div class="flex items-center space-x-3 mb-2">
                                <img src="{{ $trx->book->cover_image ? asset('storage/' . $trx->book->cover_image) : 'https://via.placeholder.com/50x60?text=Cover' }}" class="w-10 h-14 object-cover rounded">
                                <div>
                                    <h3 class="font-semibold text-gray-900 line-clamp-1">{{ $trx->book->title }}</h3>
                                    <p class="text-sm text-gray-600">{{ $trx->book->author }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-2 text-sm mb-4">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Borrowed:</span>
                                <span class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($trx->borrow_date)->format('M d, Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Due Date:</span>
                                <span class="font-medium text-gray-900 {{ $trx->status == 'late' ? 'text-red-600' : '' }}">{{ \Carbon\Carbon::parse($trx->due_date)->format('M d, Y') }}</span>
                            </div>
                            <div class="flex justify-between mt-2">
                                <span class="text-gray-600">Status:</span>
                                <span>
                                    @if($trx->status == 'borrowed') <span class="inline-block text-xs font-bold bg-accent text-white px-2 py-1 rounded">Active</span>
                                    @elseif($trx->status == 'return_pending') <span class="inline-block text-xs font-bold bg-yellow-500 text-black px-2 py-1 rounded">Pending</span>
                                    @elseif($trx->status == 'returned') <span class="inline-block text-xs font-bold bg-gray-400 text-white px-2 py-1 rounded">Returned</span>
                                    @elseif($trx->status == 'late') <span class="inline-block text-xs font-bold bg-red-600 text-white px-2 py-1 rounded">Overdue</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                        
                        <div class="flex gap-2">
                            @if($trx->status == 'borrowed' || $trx->status == 'late')
                                <button onclick="openReturnModal({{ $trx->id }}, '{{ addslashes($trx->book->title) }}')" class="flex-1 flex items-center justify-center space-x-1 bg-accent text-white py-2 rounded font-medium hover:brightness-90 transition-smooth text-sm">
                                    <i class="fas fa-undo-alt"></i> <span>Return</span>
                                </button>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="flex justify-center">
                    {{ $transactions->links() }}
                </div>
            @else
                <div class="text-center py-16 bg-white rounded-lg shadow-sm border border-gray-100">
                    <div class="mb-6"><i class="fas fa-inbox text-6xl text-gray-300"></i></div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">No Transactions Found</h2>
                    <p class="text-gray-600 mb-6">You haven't borrowed any books with this filter.</p>
                    <a href="{{ route('books.index') }}" class="inline-flex items-center space-x-2 px-6 py-3 bg-black text-white border-2 border-black hover:bg-gray-800 font-bold uppercase">
                        <i class="fas fa-book"></i> <span>Browse Books</span>
                    </a>
                </div>
            @endif

        </div>
    </section>

    @if($totalFine > 0)
    <section class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-8">Outstanding Fines</h2>

            <div class="bg-white border-2 border-black overflow-hidden">
                <table class="w-full">
                    <thead class="bg-white border-b-2 border-black">
                        <tr>
                            <th class="px-6 py-4 text-left font-bold text-black uppercase">Book Title</th>
                            <th class="px-6 py-4 text-left font-bold text-black uppercase">Days Overdue</th>
                            <th class="px-6 py-4 text-left font-bold text-black uppercase">Fine Amount</th>
                            <th class="px-6 py-4 text-left font-bold text-black uppercase">Due Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($overdueTransactions as $trx)
                        <tr class="border-b border-gray-200 hover:bg-orange-50 transition-smooth">
                            <td class="px-6 py-4 font-semibold text-gray-900">{{ $trx->book->title }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ $trx->days_late }} days</td>
                            <td class="px-6 py-4"><span class="font-bold text-orange-600">Rp {{ number_format($trx->fine_amount, 0, ',', '.') }}</span></td>
                            <td class="px-6 py-4 text-gray-700">{{ \Carbon\Carbon::parse($trx->due_date)->format('M d, Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6 bg-white border-2 border-black p-6 max-w-md ml-auto">
                <div class="flex justify-between items-center mb-4">
                    <p class="text-lg font-semibold text-gray-900">Total Outstanding Fines:</p>
                    <p class="text-3xl font-bold text-orange-600">Rp {{ number_format($totalFine, 0, ',', '.') }}</p>
                </div>
                <p class="text-sm text-gray-500 mb-4">* Harap lunasi denda langsung ke meja Pustakawan.</p>
            </div>
        </div>
    </section>
    @endif

    <div id="modalReturnOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 transition-opacity hidden" onclick="closeReturnModal()"></div>
    
    <div id="returnModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-0 pointer-events-none hidden">
            <div class="bg-white border-2 border-black w-full max-w-md pointer-events-auto overflow-hidden transform transition-all">
                <div class="bg-red-600 px-6 py-4 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-white uppercase">Return Confirmation</h3>
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="p-6">
                <p class="text-gray-700 mb-4">Are you sure you want to return this book?</p>
                <div class="bg-gray-50 p-4 rounded-lg mb-4 border border-gray-200 text-center">
                    <p id="modalBookTitle" class="font-semibold text-gray-900">-</p>
                </div>
                <p class="text-sm text-yellow-700 bg-yellow-50 p-3 rounded mb-6 border border-yellow-100">
                    <i class="fas fa-info-circle mr-1"></i> Once you click "Yes", the status will change to <b>Pending Admin</b>. Please return the physical book to the Librarian immediately to stop fine accumulation.
                </p>

                <form id="returnForm" action="" method="POST" class="flex justify-end space-x-3">
                    @csrf
                    <button type="button" onclick="closeReturnModal()" class="px-4 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg font-semibold transition-smooth">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 text-white bg-red-600 hover:bg-red-700 rounded-lg font-semibold transition-smooth">
                        Yes, Return It
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openReturnModal(transactionId, bookTitle) {
            document.getElementById('modalBookTitle').innerText = bookTitle;
            let form = document.getElementById('returnForm');
            form.action = `/history/${transactionId}/return`;

            document.getElementById('modalReturnOverlay').classList.remove('hidden');
            document.getElementById('returnModal').classList.remove('hidden');
        }

        function closeReturnModal() {
            document.getElementById('modalReturnOverlay').classList.add('hidden');
            document.getElementById('returnModal').classList.add('hidden');
        }
    </script>
@endsection

