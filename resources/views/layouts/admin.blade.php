<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | PageTurn Books</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('styles')
    
    <script>
        // Apply dark mode theme immediately to avoid flash of light screen
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="bg-gray-100 text-gray-800 font-sans antialiased min-h-screen flex flex-col md:flex-row transition-colors duration-300 dark:bg-gray-900 dark:text-gray-100">

    <!-- Mobile Hamburger Header -->
    <header class="bg-white border-b border-gray-200 h-16 flex md:hidden items-center justify-between px-6 transition-colors duration-300 dark:bg-gray-800 dark:border-gray-700">
        <a href="/admin/dashboard" class="flex items-center gap-2">
            <svg class="w-6 h-6 text-brand-forest" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            <span class="font-bold text-lg text-brand-brown dark:text-brand-gold">Admin Panel</span>
        </a>
        <button onclick="toggleMobileMenu()" class="text-gray-500 hover:text-brand-forest p-2 rounded-md focus:outline-none dark:text-gray-300">
            <i class="fas fa-bars text-xl"></i>
        </button>
    </header>

    <!-- Sidebar (Drawer) -->
    <aside id="admin-sidebar" class="w-64 bg-white border-r border-gray-200 flex flex-col fixed md:sticky top-0 h-screen z-50 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out dark:bg-gray-850 dark:border-gray-700 dark:bg-gray-800">
        <!-- Sidebar Header -->
        <div class="h-16 flex items-center justify-between px-6 border-b border-gray-200 dark:border-gray-700">
            <a href="/admin/dashboard" class="flex items-center gap-2">
                <svg class="w-6 h-6 text-brand-forest" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                <span class="font-bold text-lg text-brand-brown dark:text-brand-gold">Admin Panel</span>
            </a>
            <button onclick="toggleMobileMenu()" class="md:hidden text-gray-500 hover:text-brand-forest focus:outline-none dark:text-gray-300">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <!-- Sidebar Navigation -->
        <div class="flex-grow overflow-y-auto py-4">
            <nav class="space-y-1 px-3">
                @php $route = request()->getPathInfo(); @endphp
                <a href="/admin/dashboard" class="{{ $route == '/admin/dashboard' ? 'bg-gray-100 text-brand-forest dark:bg-gray-700 dark:text-brand-gold' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900' }} group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors duration-150">
                    <i class="fas fa-chart-line mr-3 text-lg w-5 text-center"></i>
                    Dashboard
                </a>
                
                <p class="px-3 pt-4 pb-1 text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">Book Catalog</p>
                <a href="/admin/books" class="{{ str_starts_with($route, '/admin/books') ? 'bg-gray-100 text-brand-forest dark:bg-gray-700 dark:text-brand-gold' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900' }} group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors duration-150">
                    <i class="fas fa-book mr-3 text-lg w-5 text-center"></i>
                    Books
                </a>
                <a href="/admin/authors" class="{{ str_starts_with($route, '/admin/authors') ? 'bg-gray-100 text-brand-forest dark:bg-gray-700 dark:text-brand-gold' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900' }} group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors duration-150">
                    <i class="fas fa-feather mr-3 text-lg w-5 text-center"></i>
                    Authors
                </a>
                <a href="/admin/categories" class="{{ str_starts_with($route, '/admin/categories') ? 'bg-gray-100 text-brand-forest dark:bg-gray-700 dark:text-brand-gold' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900' }} group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors duration-150">
                    <i class="fas fa-tags mr-3 text-lg w-5 text-center"></i>
                    Categories
                </a>
                
                <p class="px-3 pt-4 pb-1 text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">Order Management</p>
                <a href="/admin/orders" class="{{ str_starts_with($route, '/admin/orders') ? 'bg-gray-100 text-brand-forest dark:bg-gray-700 dark:text-brand-gold' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900' }} group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors duration-150">
                    <i class="fas fa-shopping-bag mr-3 text-lg w-5 text-center"></i>
                    Orders
                </a>

                <p class="px-3 pt-4 pb-1 text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">Stock & Supply</p>
                <a href="/admin/inventory" class="{{ str_starts_with($route, '/admin/inventory') ? 'bg-gray-100 text-brand-forest dark:bg-gray-700 dark:text-brand-gold' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900' }} group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors duration-150">
                    <i class="fas fa-boxes mr-3 text-lg w-5 text-center"></i>
                    Stock Levels
                </a>
                <a href="/admin/suppliers" class="{{ str_starts_with($route, '/admin/suppliers') ? 'bg-gray-100 text-brand-forest dark:bg-gray-700 dark:text-brand-gold' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900' }} group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors duration-150">
                    <i class="fas fa-truck-moving mr-3 text-lg w-5 text-center"></i>
                    Suppliers
                </a>
                <a href="/admin/purchase-orders" class="{{ str_starts_with($route, '/admin/purchase-orders') ? 'bg-gray-100 text-brand-forest dark:bg-gray-700 dark:text-brand-gold' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900' }} group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors duration-150">
                    <i class="fas fa-file-invoice mr-3 text-lg w-5 text-center"></i>
                    Purchase Orders
                </a>
                
                <p class="px-3 pt-4 pb-1 text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">Finance & Admin</p>
                <a href="/admin/invoices" class="{{ str_starts_with($route, '/admin/invoices') ? 'bg-gray-100 text-brand-forest dark:bg-gray-700 dark:text-brand-gold' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900' }} group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors duration-150">
                    <i class="fas fa-file-invoice-dollar mr-3 text-lg w-5 text-center"></i>
                    Invoices
                </a>
                <a href="/admin/expenses" class="{{ str_starts_with($route, '/admin/expenses') ? 'bg-gray-100 text-brand-forest dark:bg-gray-700 dark:text-brand-gold' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900' }} group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors duration-150">
                    <i class="fas fa-receipt mr-3 text-lg w-5 text-center"></i>
                    Expenses
                </a>
                <a href="/admin/reports" class="{{ str_starts_with($route, '/admin/reports') ? 'bg-gray-100 text-brand-forest dark:bg-gray-700 dark:text-brand-gold' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900' }} group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors duration-150">
                    <i class="fas fa-poll mr-3 text-lg w-5 text-center"></i>
                    Reports
                </a>
                <a href="/admin/users" class="{{ str_starts_with($route, '/admin/users') ? 'bg-gray-100 text-brand-forest dark:bg-gray-700 dark:text-brand-gold' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900' }} group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors duration-150">
                    <i class="fas fa-users mr-3 text-lg w-5 text-center"></i>
                    Users
                </a>
            </nav>
        </div>
    </aside>

    <!-- Overlay behind sidebar on mobile -->
    <div id="mobile-sidebar-overlay" onclick="toggleMobileMenu()" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden md:hidden"></div>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        <!-- Header -->
        <header class="bg-white border-b border-gray-200 h-16 flex justify-between items-center px-6 transition-colors duration-300 dark:bg-gray-800 dark:border-gray-700">
            <h1 class="text-xl font-bold text-gray-800 dark:text-brand-gold font-display">@yield('page_title', 'Dashboard')</h1>
            
            <div class="flex items-center gap-4">
                <!-- Theme Toggle -->
                <button onclick="toggleTheme()" class="text-gray-500 dark:text-gray-400 hover:text-brand-forest dark:hover:text-brand-gold p-2 rounded-full focus:outline-none transition-colors duration-200" title="Toggle dark mode">
                    <i id="theme-toggle-icon" class="fas fa-moon text-lg"></i>
                </button>
                
                <a href="/" target="_blank" class="text-sm font-medium text-gray-500 hover:text-brand-forest dark:text-gray-300 dark:hover:text-white transition-colors duration-150">
                    <i class="fas fa-external-link-alt mr-1"></i> View Store
                </a>
                <a href="/logout" class="text-sm font-medium text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 transition-colors duration-150">
                    <i class="fas fa-sign-out-alt mr-1"></i> Logout
                </a>
            </div>
        </header>

        <!-- Main Workspace -->
        <main class="flex-1 overflow-y-auto p-6 bg-gray-50 transition-colors duration-300 dark:bg-gray-900">
            @yield('content')
        </main>
    </div>

    <!-- Toast container inside admin -->
    <div class="toast-container" id="toast-container"></div>

    <!-- Custom Confirmation Modal inside admin -->
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

    <!-- Mobile Navigation & Theme Scripts -->
    <script>
        function toggleMobileMenu() {
            const sidebar = document.getElementById('admin-sidebar');
            const overlay = document.getElementById('mobile-sidebar-overlay');
            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            }
        }

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
        
        document.addEventListener('DOMContentLoaded', updateThemeToggleIcon);

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

        // Dynamic Toast Notification Helper
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
