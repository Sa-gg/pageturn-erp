@extends('layouts.app')

@section('title', 'Log In | PageTurn Books')

@section('content')
<div class="flex items-center justify-center min-h-[calc(100vh-200px)] py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white dark:bg-gray-900 p-10 rounded-2xl shadow-xl border border-gray-100 dark:border-white/10">
        <div>
            <div class="flex justify-center">
                <svg class="w-12 h-12 text-brand-forest" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900 dark:text-white">Sign in to your account</h2>
            <p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-400">
                Or
                <a href="/register" class="font-medium text-brand-forest hover:text-green-700 dark:text-green-400 dark:hover:text-green-300">
                    create a new account
                </a>
            </p>
        </div>

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-md">
                <p class="text-sm text-red-700">{{ session('error') }}</p>
            </div>
        @endif

        <form class="mt-8 space-y-6" action="/login" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email address</label>
                    <input id="email" name="email" type="email" autocomplete="email" required class="appearance-none block w-full px-4 py-3 border border-gray-300 dark:border-white/15 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-brand-forest focus:border-brand-forest sm:text-sm">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required class="appearance-none block w-full px-4 py-3 border border-gray-300 dark:border-white/15 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-brand-forest focus:border-brand-forest sm:text-sm">
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 text-brand-forest focus:ring-brand-forest border-gray-300 rounded">
                    <label for="remember-me" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                        Remember me
                    </label>
                </div>

                <div class="text-sm">
                    <a href="#" class="font-medium text-brand-forest hover:text-green-700">
                        Forgot your password?
                    </a>
                </div>
            </div>

            <div>
                <button type="submit" class="w-full flex justify-center btn-primary py-3 text-lg">
                    Sign in
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
