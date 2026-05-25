<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PageTurn Books')</title>
    
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
<body class="bg-brand-cream text-gray-800 font-sans antialiased min-h-screen flex flex-col transition-colors duration-300 dark:bg-gray-900 dark:text-gray-100">

    <!-- Navigation -->
    <nav class="bg-white shadow-sm sticky top-0 z-50 transition-colors duration-300 dark:bg-gray-800 dark:border-gray-700 border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="flex-shrink-0 flex items-center gap-2">
                        <svg class="w-8 h-8 text-brand-forest transition-transform duration-300 hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        <span class="font-bold text-xl text-brand-brown tracking-tight dark:text-brand-gold">PageTurn Books</span>
                    </a>
                    <div class="hidden sm:ml-8 sm:flex sm:space-x-8">
                        <a href="/catalog" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200">Catalog</a>
                        <a href="/categories" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200">Categories</a>
                    </div>
                </div>
                
                <div class="hidden sm:flex sm:items-center space-x-4">
                    <!-- Theme Toggle -->
                    <button onclick="toggleTheme()" class="text-gray-500 dark:text-gray-400 hover:text-brand-forest dark:hover:text-brand-gold p-2 rounded-full focus:outline-none transition-colors duration-200" title="Toggle dark mode">
                        <i id="theme-toggle-icon" class="fas fa-moon text-lg"></i>
                    </button>
                    
                    <a href="/cart" class="text-gray-400 hover:text-brand-forest relative transition-colors duration-200">
                        <span class="sr-only">Shopping Cart</span>
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold px-1.5 py-0.5 rounded-full">0</span>
                    </a>
                    
                    @if(session('token'))
                        <div class="relative">
                            <a href="/profile" class="flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white transition-colors">
                                <img class="h-8 w-8 rounded-full object-cover border border-gray-200 dark:border-gray-700" src="https://ui-avatars.com/api/?name=User&color=7F9CF5&background=EBF4FF" alt="Avatar">
                                <span>My Account</span>
                            </a>
                        </div>
                    @else
                        <a href="/login" class="text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white transition-colors">Log in</a>
                        <a href="/register" class="btn-primary text-sm">Sign up</a>
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
    <footer class="bg-brand-brown text-brand-cream mt-auto transition-colors duration-300 dark:bg-gray-900 dark:text-gray-300 dark:border-t dark:border-gray-800">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="flex items-center gap-2 mb-4 md:mb-0">
                    <svg class="w-6 h-6 text-brand-cream dark:text-brand-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    <span class="font-bold text-lg text-brand-cream dark:text-brand-gold">PageTurn Books</span>
                </div>
                <div class="text-sm">
                    &copy; {{ date('Y') }} PageTurn Books ERP. All rights reserved.
                </div>
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
