<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'PageTurn Books')</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600;1,700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('styles')
    <style>
        /* Dark mode: modal-box */
        .dark .modal-box { background: #1e2130 !important; color: #e2e8f0 !important; border: 1px solid rgba(255,255,255,0.1) !important; }
        .dark .modal-box h3 { color: #f1f5f9 !important; }
        .dark .modal-box p { color: #94a3b8 !important; }
        .dark .modal-box button[type="button"] { background: #0f172a !important; color: #94a3b8 !important; border-color: rgba(255,255,255,0.12) !important; }
        /* Dark mode: ALL inputs/selects/textareas */
        .dark input:not([type="hidden"]):not([type="checkbox"]):not([type="radio"]),
        .dark select, .dark textarea { background-color: #0f172a !important; color: #e2e8f0 !important; border-color: rgba(255,255,255,0.15) !important; }
        .dark input::placeholder, .dark textarea::placeholder { color: #64748b !important; }
        .dark label { color: #cbd5e1 !important; }
        /* Dark mode: cards */
        .dark .bg-white { background-color: #1e2130 !important; color: #e2e8f0 !important; }
        .dark .bg-gray-50 { background-color: #141824 !important; }
        .dark .text-gray-700 { color: #cbd5e1 !important; }
        .dark .text-gray-800 { color: #e2e8f0 !important; }
        .dark .text-gray-900 { color: #f1f5f9 !important; }
        .dark .border-gray-200 { border-color: rgba(255,255,255,0.08) !important; }
        .dark .brand-wordmark { color: #f1f5f9 !important; }
    </style>
    
    <script>
        // Apply dark mode theme immediately to avoid flash of light screen
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="bg-brand-cream text-gray-800 font-sans antialiased min-h-screen flex flex-col transition-colors duration-300 dark:bg-brand-ink dark:text-gray-100">

    <!-- Navigation -->
    @php
        $userRole = data_get(session('user'), 'role');
    @endphp
    <nav id="main-nav" class="bg-white/95 backdrop-blur-md shadow-sm sticky top-0 z-50 transition-all duration-300 dark:bg-brand-ink-2/95 dark:border-brand-ink-3 border-b border-gray-100 dark:border-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    @include('partials.brand-logo', ['href' => '/', 'subline' => 'Bookstore', 'variant' => 'light'])
                    <div class="hidden sm:ml-10 sm:flex sm:space-x-1">
                        <a href="/catalog" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white inline-flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 hover:bg-gray-50 dark:hover:bg-white/5">Catalog</a>
                        <a href="/categories" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white inline-flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 hover:bg-gray-50 dark:hover:bg-white/5">Categories</a>
                    </div>
                </div>
                
                <div class="hidden sm:flex sm:items-center gap-2">
                    <!-- Theme Toggle -->
                    <button onclick="toggleTheme()" id="theme-btn" class="text-gray-500 dark:text-gray-400 hover:text-brand-forest dark:hover:text-brand-gold p-2 rounded-lg focus:outline-none transition-all duration-200 hover:bg-gray-50 dark:hover:bg-white/5" title="Toggle dark mode">
                        <i id="theme-toggle-icon" class="fas fa-moon text-base"></i>
                    </button>
                    
                    <a href="/cart" class="text-gray-500 dark:text-gray-400 hover:text-brand-forest dark:hover:text-brand-gold relative p-2 rounded-lg transition-all duration-200 hover:bg-gray-50 dark:hover:bg-white/5">
                        <span class="sr-only">Shopping Cart</span>
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <span class="absolute top-1 right-1 bg-brand-gold text-white text-[10px] font-bold w-4 h-4 flex items-center justify-center rounded-full leading-none">0</span>
                    </a>
                    
                    @if(session('token'))
                        @if(in_array($userRole, ['admin', 'super_admin', 'catalog_admin', 'orders_admin', 'inventory_admin', 'finance_admin', 'staff']))
                            <a href="/admin/dashboard" class="text-sm font-medium text-brand-gold hover:text-brand-gold-light transition-colors mr-2">Admin Panel</a>
                        @endif
                        <a href="/profile" class="flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white transition-colors ml-2">
                            <img class="h-8 w-8 rounded-full object-cover border-2 border-brand-gold/30" src="https://ui-avatars.com/api/?name=User&color=D4A853&background=1E1E2A" alt="Avatar">
                        </a>
                    @else
                        <a href="/login" class="text-sm font-medium text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white transition-colors px-3 py-2">Log in</a>
                        <a href="/register" class="text-sm font-semibold bg-brand-gold hover:bg-brand-gold-light text-brand-ink px-4 py-2 rounded-lg transition-all duration-200 shadow-sm">Sign up</a>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-brand-ink dark:bg-brand-ink border-t border-white/5 mt-auto">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10 mb-10">
                <div class="md:col-span-2">
                    @include('partials.brand-logo', ['href' => '/', 'subline' => 'Curated Reads', 'class' => 'mb-4', 'variant' => 'dark'])
                    <p class="text-gray-500 text-sm leading-relaxed max-w-xs">Your premium destination for curated literature. From classics to contemporary fiction, we have your next great read.</p>
                </div>
                <div>
                    <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-4">Shop</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="/catalog" class="text-gray-500 hover:text-white transition-colors">All Books</a></li>
                        <li><a href="/categories" class="text-gray-500 hover:text-white transition-colors">Categories</a></li>
                        <li><a href="/catalog" class="text-gray-500 hover:text-white transition-colors">New Arrivals</a></li>
                        <li><a href="/catalog" class="text-gray-500 hover:text-white transition-colors">Bestsellers</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-4">Account</h4>
                    <ul class="space-y-2 text-sm">
                        @if(session('token'))
                            <li><a href="/profile" class="text-gray-500 hover:text-white transition-colors">My Profile</a></li>
                            <li><a href="/cart" class="text-gray-500 hover:text-white transition-colors">My Cart</a></li>
                            <li><a href="/logout" class="text-gray-500 hover:text-white transition-colors">Logout</a></li>
                        @else
                            <li><a href="/login" class="text-gray-500 hover:text-white transition-colors">Login</a></li>
                            <li><a href="/register" class="text-gray-500 hover:text-white transition-colors">Create Account</a></li>
                            <li><a href="/cart" class="text-gray-500 hover:text-white transition-colors">My Cart</a></li>
                            <li><a href="/profile" class="text-gray-500 hover:text-white transition-colors">Orders</a></li>
                        @endif
                    </ul>
                </div>
            </div>
            <div class="border-t border-white/5 pt-6 flex flex-col sm:flex-row justify-between items-center gap-4">
                <p class="text-xs text-gray-600">&copy; {{ date('Y') }} PageTurn Books. All rights reserved.</p>
                <p class="text-xs text-gray-700">Built with Laravel &amp; love ❤️</p>
            </div>
        </div>
    </footer>

    <!-- Dynamic Toast Container -->
    <div class="toast-container" id="toast-container"></div>

    <!-- Dynamic Custom Confirmation Modal -->
    <div id="confirmModal" class="modal-overlay" style="display:none;">
        <div class="modal-box">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white" id="confirmTitle">Confirm Action</h3>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400" id="confirmText">Are you sure you want to proceed?</p>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" id="confirmCancelBtn" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-forest">Cancel</button>
                <button type="button" id="confirmOkBtn" class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">Confirm</button>
            </div>
        </div>
    </div>

    <!-- UI/UX Script Helpers -->
    <script>
        // Initialize Theme Toggle Icon
        function updateThemeToggleIcon() {
            const icon = document.getElementById('theme-toggle-icon');
            if (icon) {
                if (document.documentElement.classList.contains('dark')) {
                    icon.className = 'fas fa-sun text-lg text-yellow-400';
                } else {
                    icon.className = 'fas fa-moon text-lg';
                }
            }
        }
        
        document.addEventListener('DOMContentLoaded', () => {
            updateThemeToggleIcon();
            
            // Wake up backend microservices in the background (Render Free Tier Helper)
            const services = [
                "{{ config('services.catalog.base_url') }}",
                "{{ config('services.auth.base_url') }}",
                "{{ config('services.inventory.base_url') }}",
                "{{ config('services.orders.base_url') }}",
                "{{ config('services.finance.base_url') }}"
            ];
            services.forEach(url => {
                if (url && url.includes('onrender.com')) {
                    const healthUrl = url.replace(/\/api\/?$/, '') + '/api/health';
                    fetch(healthUrl, { mode: 'no-cors' }).catch(() => {});
                }
            });
        });

        function toggleTheme() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
            updateThemeToggleIcon();
        }

        // Global Toast Notification Helper
        window.showToast = function(message, type = 'info') {
            const container = document.getElementById('toast-container');
            if (!container) return;

            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            
            // Map types to Font Awesome icons
            let iconClass = 'fa-info-circle text-sky-500';
            if (type === 'success') iconClass = 'fa-check-circle text-emerald-500';
            if (type === 'error') iconClass = 'fa-exclamation-circle text-rose-500';
            if (type === 'warning') iconClass = 'fa-exclamation-triangle text-amber-500';

            toast.innerHTML = `
                <i class="fas ${iconClass} text-lg"></i>
                <div class="flex-grow">${message}</div>
                <button onclick="this.parentElement.remove()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <i class="fas fa-times"></i>
                </button>
            `;

            container.appendChild(toast);

            // Auto dismiss toast after 4.5 seconds
            setTimeout(() => {
                toast.classList.add('dismissing');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 4500);
        };

        // Custom Confirm Modal Helper
        window.showConfirm = function(title, text, onConfirm) {
            const modal = document.getElementById('confirmModal');
            const titleEl = document.getElementById('confirmTitle');
            const textEl = document.getElementById('confirmText');
            const cancelBtn = document.getElementById('confirmCancelBtn');
            const okBtn = document.getElementById('confirmOkBtn');

            if (!modal) return;

            titleEl.textContent = title;
            textEl.textContent = text;
            modal.style.display = 'flex';

            // Unbind previous actions
            const newCancelBtn = cancelBtn.cloneNode(true);
            const newOkBtn = okBtn.cloneNode(true);
            cancelBtn.parentNode.replaceChild(newCancelBtn, cancelBtn);
            okBtn.parentNode.replaceChild(newOkBtn, okBtn);

            newCancelBtn.addEventListener('click', () => {
                modal.style.display = 'none';
            });

            newOkBtn.addEventListener('click', () => {
                modal.style.display = 'none';
                if (typeof onConfirm === 'function') onConfirm();
            });
        };
    </script>

    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                window.showToast("{{ session('success') }}", 'success');
            });
        </script>
    @endif
    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                window.showToast("{{ session('error') }}", 'error');
            });
        </script>
    @endif

    @yield('scripts')
</body>
</html>

