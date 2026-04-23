<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ PT INL - API Documentation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
        .glass { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); }
        .code-block { background: #1e293b; color: #e2e8f0; padding: 1rem; border-radius: 0.5rem; font-family: monospace; position: relative; }
        .copy-btn { position: absolute; top: 0.5rem; right: 0.5rem; padding: 0.25rem 0.5rem; background: #334155; border-radius: 0.25rem; font-size: 0.75rem; cursor: pointer; }
        .copy-btn:hover { background: #475569; }
    </style>
</head>
<body class="p-4 md:p-8">
    <div class="max-w-5xl mx-auto">
        <!-- Header -->
        <header class="flex flex-col md:flex-row items-center justify-between mb-12 gap-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-indigo-600 rounded-xl flex items-center justify-center text-white font-bold text-2xl shadow-lg">
                    I
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 leading-tight">FAQ PT INL</h1>
                    <p class="text-slate-500">Core API Service & Backend Portal</p>
                </div>
            </div>
            <div class="flex gap-3">
                <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full flex items-center gap-1.5">
                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                    API Active
                </span>
                <span class="px-3 py-1 bg-slate-100 text-slate-600 text-xs font-semibold rounded-full">v1.0.0</span>
            </div>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Installation & Auth -->
            <div class="lg:col-span-1 space-y-8">
                <section class="glass p-6 rounded-2xl shadow-sm">
                    <h2 class="text-sm font-bold uppercase tracking-wider text-slate-400 mb-4">Quick Setup</h2>
                    <div class="space-y-4">
                        <div>
                            <p class="text-xs font-medium text-slate-500 mb-2">1. Dependencies</p>
                            <div class="code-block text-xs">
                                <code>composer install</code>
                            </div>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-slate-500 mb-2">2. Environment</p>
                            <div class="code-block text-xs">
                                <code>cp .env.example .env</code><br>
                                <code>php artisan key:generate</code>
                            </div>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-slate-500 mb-2">3. Database</p>
                            <div class="code-block text-xs">
                                <code>php artisan migrate --seed</code>
                            </div>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-slate-500 mb-2">4. Run Server</p>
                            <div class="code-block text-xs">
                                <code>php artisan serve</code>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="glass p-6 rounded-2xl shadow-sm">
                    <h2 class="text-sm font-bold uppercase tracking-wider text-slate-400 mb-4">Authentication</h2>
                    <p class="text-sm text-slate-600 mb-4">Uses <strong>Laravel Sanctum</strong>. Include the token in the header for protected routes.</p>
                    <div class="code-block text-xs">
                        <code>Authorization: Bearer {token}</code><br>
                        <code>Accept: application/json</code>
                    </div>
                </section>
                
                <section class="bg-indigo-600 p-6 rounded-2xl shadow-lg text-white">
                    <h2 class="text-sm font-bold uppercase tracking-wider text-indigo-200 mb-2">Frontend</h2>
                    <p class="text-sm mb-4">Access the main user dashboard and FAQ portal.</p>
                    <a href="http://localhost:3000" class="inline-flex items-center gap-2 bg-white text-indigo-600 px-4 py-2 rounded-lg font-bold text-sm transition hover:bg-indigo-50">
                        Go to Dashboard
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    </a>
                </section>
            </div>

            <!-- Right Column: API Endpoints -->
            <div class="lg:col-span-2">
                <section class="bg-white p-6 md:p-8 rounded-3xl shadow-xl border border-slate-100">
                    <h2 class="text-xl font-bold text-slate-900 mb-6 flex items-center gap-2">
                        <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        API Endpoints Reference
                    </h2>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="border-b border-slate-100 text-slate-400 text-xs font-bold uppercase tracking-tighter">
                                    <th class="py-4 px-2">Method</th>
                                    <th class="py-4 px-2">Endpoint</th>
                                    <th class="py-4 px-2">Access</th>
                                    <th class="py-4 px-2">Description</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-slate-50">
                                <tr>
                                    <td class="py-4 px-2"><span class="px-2 py-1 bg-blue-100 text-blue-700 font-bold rounded text-[10px]">POST</span></td>
                                    <td class="py-4 px-2 font-mono text-slate-700">/api/login</td>
                                    <td class="py-4 px-2 text-slate-400">Public</td>
                                    <td class="py-4 px-2 text-slate-600">Login and get token</td>
                                </tr>
                                <tr>
                                    <td class="py-4 px-2"><span class="px-2 py-1 bg-green-100 text-green-700 font-bold rounded text-[10px]">GET</span></td>
                                    <td class="py-4 px-2 font-mono text-slate-700">/api/faqs</td>
                                    <td class="py-4 px-2 text-slate-400">Public</td>
                                    <td class="py-4 px-2 text-slate-600">Get all FAQ topics</td>
                                </tr>
                                <tr>
                                    <td class="py-4 px-2"><span class="px-2 py-1 bg-blue-100 text-blue-700 font-bold rounded text-[10px]">POST</span></td>
                                    <td class="py-4 px-2 font-mono text-slate-700">/api/user-inquiries</td>
                                    <td class="py-4 px-2 text-slate-400">Public</td>
                                    <td class="py-4 px-2 text-slate-600">Submit new question</td>
                                </tr>
                                <tr>
                                    <td class="py-4 px-2"><span class="px-2 py-1 bg-green-100 text-green-700 font-bold rounded text-[10px]">GET</span></td>
                                    <td class="py-4 px-2 font-mono text-slate-700">/api/dashboard/stats</td>
                                    <td class="py-4 px-2 text-indigo-600 font-semibold">Admin</td>
                                    <td class="py-4 px-2 text-slate-600">Overview statistics</td>
                                </tr>
                                <tr>
                                    <td class="py-4 px-2"><span class="px-2 py-1 bg-green-100 text-green-700 font-bold rounded text-[10px]">GET</span></td>
                                    <td class="py-4 px-2 font-mono text-slate-700">/api/questions</td>
                                    <td class="py-4 px-2 text-indigo-600 font-semibold">Admin</td>
                                    <td class="py-4 px-2 text-slate-600">Manage FAQ questions</td>
                                </tr>
                                <tr>
                                    <td class="py-4 px-2"><span class="px-2 py-1 bg-green-100 text-green-700 font-bold rounded text-[10px]">GET</span></td>
                                    <td class="py-4 px-2 font-mono text-slate-700">/api/user-inquiries</td>
                                    <td class="py-4 px-2 text-indigo-600 font-semibold">Admin</td>
                                    <td class="py-4 px-2 text-slate-600">View user inquiries</td>
                                </tr>
                                <tr>
                                    <td class="py-4 px-2"><span class="px-2 py-1 bg-amber-100 text-amber-700 font-bold rounded text-[10px]">PATCH</span></td>
                                    <td class="py-4 px-2 font-mono text-slate-700">/api/user-inquiries/{id}/status</td>
                                    <td class="py-4 px-2 text-indigo-600 font-semibold">Admin</td>
                                    <td class="py-4 px-2 text-slate-600">Update inquiry status</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>

                <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-slate-900 p-6 rounded-2xl text-white">
                        <h3 class="font-bold mb-2 flex items-center gap-2">
                            <span class="w-2 h-2 bg-indigo-400 rounded-full"></span>
                            Production Ready
                        </h3>
                        <p class="text-slate-400 text-sm">Optimized for deployment on Render.com with PostgreSQL support.</p>
                    </div>
                    <div class="bg-white p-6 rounded-2xl border border-slate-100">
                        <h3 class="font-bold text-slate-900 mb-2 flex items-center gap-2">
                            <span class="w-2 h-2 bg-indigo-600 rounded-full"></span>
                            Docker Support
                        </h3>
                        <p class="text-slate-500 text-sm">Multi-stage Dockerfile included for easy containerized deployment.</p>
                    </div>
                </div>
            </div>
        </div>

        <footer class="mt-16 pt-8 border-t border-slate-200 text-center text-slate-400 text-sm pb-8">
            &copy; {{ date('Y') }} PT Industri Nabati Lestari. All rights reserved. Built with Laravel 9.
        </footer>
    </div>
</body>
</html>
