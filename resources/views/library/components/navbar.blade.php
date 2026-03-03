<!-- Responsive Navigation Bar -->
<nav class="bg-white text-black border-b-4 border-black sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">

            <!-- Logo Section -->
            <div class="flex items-center space-x-2 flex-shrink-0">
                <a href="#" class="flex items-center space-x-2 hover:text-gray-900">
                    <div class="bg-blue-500 p-2">
                        <i class="fas fa-book text-white text-lg"></i>
                    </div>
                    <span class="font-bold text-xl hidden sm:inline">Minimalibrary</span>
                </a>
            </div>

            <!-- Desktop Navigation Menu -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('home') }}" class="uppercase font-bold text-sm text-accent hover:text-accent-dark">HOME</a>
                <a href="{{ route('books.index') }}" class="uppercase font-bold text-sm text-accent hover:text-accent-dark">CATALOG</a>
                <a href="{{ route('categories.index') }}" class="uppercase font-bold text-sm text-accent hover:text-accent-dark">CATEGORIES</a>
            </div>

            <!-- Right Section: Notifications, User Menu -->
            <div class="flex items-center space-x-4">
                
                <!-- Notifications -->
                <div class="relative">
                    <button class="relative text-gray-600 hover:text-gray-800 transition-colors">
                        <i class="fas fa-bell text-lg"></i>
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">3</span>
                    </button>
                </div>

                <!-- User Profile Dropdown -->
                <div class="relative">
                    <button 
                        data-user-menu-button
                        onclick="toggleUserDropdown()" 
                        class="flex items-center space-x-2 hover:text-gray-800 transition-colors"
                    >
                        <i class="fas fa-user-circle text-2xl"></i>
                        @if (Auth::check())
                        <span class="hidden sm:inline text-sm font-medium">{{ Auth::user()->name }}</span>
                        @else
                        <span class="hidden sm:inline text-sm font-medium">Guest</span>
                        @endif
                        <i class="fas fa-chevron-down text-xs ml-1"></i>
                    </button>

                    <!-- Dropdown Menu -->
                    <div id="user-dropdown" class="hidden absolute right-0 mt-2 w-48 bg-white text-gray-800 rounded-lg shadow-lg overflow-hidden">
                        @if (Auth::check())
                        <div class="px-4 py-3 border-b border-gray-200">
                            <p class="font-semibold">{{ Auth::user()->name }}</p>
                            <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="flex items-center space-x-2 px-4 py-2 hover:bg-gray-100 transition-colors">
                            <i class="fas fa-user text-blue-500"></i>
                            <span>My Profile</span>
                        </a>
                        <a href="{{ route('history.index') }}" class="flex items-center space-x-2 px-4 py-2 hover:bg-gray-100 transition-colors">
                            <i class="fas fa-history text-green-500"></i>
                            <span>Borrowed Books</span>
                        </a>
                        <hr class="my-1">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full text-left flex items-center space-x-2 px-4 py-2 hover:bg-red-50 transition-colors text-red-600">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                        @else
                        <a href="{{ route('login') }}" class="flex items-center space-x-2 px-4 py-2 hover:bg-gray-100 transition-colors">
                            <i class="fas fa-sign-in-alt text-green-500"></i>
                            <span>Login</span>
                        </a>
                        <a href="{{ route('register') }}" class="flex items-center space-x-2 px-4 py-2 hover:bg-gray-100 transition-colors">
                            <i class="fas fa-user-plus text-blue-500"></i>
                            <span>Register</span>
                        </a>
                        @endif
                    </div>
                </div>

                <!-- Mobile Menu Toggle -->
                <button 
                    onclick="toggleMobileMenu()" 
                    class="md:hidden text-gray-600 hover:text-gray-800 transition-colors"
                >
                    <i class="fas fa-bars text-lg"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden pb-4 border-t-2 border-black">
            <a href="{{ route('home') }}" class="block px-2 py-2 text-sm uppercase font-bold hover:bg-gray-100 transition-colors">HOME</a>
            <a href="{{ route('books.index') }}" class="block px-2 py-2 text-sm uppercase font-bold hover:bg-gray-100 transition-colors">CATALOG</a>
            <a href="{{ route('categories.index') }}" class="block px-2 py-2 text-sm uppercase font-bold hover:bg-gray-100 transition-colors">CATEGORIES</a>
            <a href="{{ route('history.index') }}" class="block px-2 py-2 text-sm uppercase font-bold hover:bg-gray-100 transition-colors">MY BOOKS</a>
        </div>
    </div>
</nav>