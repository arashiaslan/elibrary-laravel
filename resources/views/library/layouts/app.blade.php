<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="E-Library - Your digital library for borrowing and reading books online">
    <meta name="keywords" content="e-library, books, digital library, borrowing">
    <meta name="author" content="E-Library Team">
    <meta name="theme-color" content="#1F2937">
    <title>@yield('title', 'Minimalibrary')</title>

    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Custom Styles Stack -->
    @stack('styles')

    <style>
        :root {
            --accent: #2563EB; /* blue-600 */
        }
        .text-accent { color: var(--accent); }
        .bg-accent { background-color: var(--accent); }
        .border-accent { border-color: var(--accent); }

        * {
            font-family: 'Space Mono', monospace;
            font-weight: 600;
        }
        
        body {
            background-color: #ffffff;
            color: #111827;
        }

        /* Minimal transitions */
        .transition-smooth {
            transition: all 0.1s linear;
        }

        /* Neubrutalism shadow */
        .brutal-shadow {
            box-shadow: 8px 8px 0 0 #000;
        }

        /* Brutalist scrollbar */
        ::-webkit-scrollbar {
            width: 12px;
        }

        ::-webkit-scrollbar-track {
            background: #ffffff;
        }

        ::-webkit-scrollbar-thumb {
            background: #000000;
            border-radius: 0;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #333333;
        }

        /* Pagination accent */
        .pagination a,
        .pagination span {
            color: var(--accent);
        }
        .pagination .active span {
            background-color: var(--accent);
            color: #fff;
            border-color: var(--accent);
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased">
    <!-- Navbar Component -->
    @include('library.components.navbar')

    <!-- Main Content Area -->
    <main class="min-h-screen">
        @yield('content')
    </main>

    <!-- Footer Component -->
    @include('library.components.footer')

    <!-- Scripts Stack -->
    @stack('scripts')

    <!-- Default Scripts -->
    <script>
        // Toggle mobile menu
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            if (menu) {
                menu.classList.toggle('hidden');
            }
        }

        // Toggle user dropdown
        function toggleUserDropdown() {
            const dropdown = document.getElementById('user-dropdown');
            if (dropdown) {
                dropdown.classList.toggle('hidden');
            }
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const userMenu = document.getElementById('user-dropdown');
            const userButton = document.querySelector('[data-user-menu-button]');
            
            if (userMenu && !userMenu.contains(event.target) && !userButton.contains(event.target)) {
                userMenu.classList.add('hidden');
            }
        });

        // Search functionality (placeholder)
        function handleSearch(event) {
            if (event.key === 'Enter') {
                const query = event.target.value;
                console.log('Search query:', query);
                // Will be connected to backend route when ready
            }
        }
    </script>
</body>
</html>