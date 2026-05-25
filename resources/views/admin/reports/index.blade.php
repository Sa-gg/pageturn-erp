@extends('layouts.admin')

@section('title', 'Financial Reports')
@section('page_title', 'Financial Reports')

@section('styles')
<style>
    .report-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 24px;
        margin-bottom: 2.5rem;
    }

    .admin-card {
        background: var(--color-white);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--color-gray-200);
        overflow: hidden;
        padding: 24px;
    }

    .card-title {
        font-size: 0.82rem;
        color: var(--color-gray-500);
        margin-bottom: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .card-value {
        font-family: var(--font-display);
        font-size: 2.2rem;
        font-weight: 800;
        color: var(--color-gray-800);
        line-height: 1.1;
    }

    .value-green { color: var(--color-forest); }
    .value-red { color: var(--color-danger); }
    
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 12px;
    }

    th, td {
        padding: 14px 16px;
        text-align: left;
        border-bottom: 1px solid var(--color-gray-200);
    }

    th {
        background-color: var(--color-gray-100);
        color: var(--color-gray-600);
        font-weight: 600;
        font-size: 0.82rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    td {
        color: var(--color-gray-800);
        font-size: 0.95rem;
    }
</style>
@endsection

@section('content')
<div>
    <!-- Profit & Loss Summary -->
    <div class="report-grid">
        <div class="admin-card">
            <div class="card-title">Total Revenue</div>
            <div class="card-value value-green">₱{{ number_format($profit['total_revenue'] ?? 0, 2) }}</div>
        </div>
        <div class="admin-card">
            <div class="card-title">Total Expenses</div>
            <div class="card-value value-red">₱{{ number_format($profit['total_expenses'] ?? 0, 2) }}</div>
        </div>
        <div class="admin-card" style="border-bottom: 4px solid var(--color-forest);">
            <div class="card-title">Net Profit</div>
            <div class="card-value">₱{{ number_format($profit['profit'] ?? 0, 2) }}</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Selling Books -->
        <div class="admin-card">
            <h2 class="text-lg font-bold text-gray-800 dark:text-brand-gold font-display mb-4 border-b border-gray-200 dark:border-gray-700 pb-3 flex items-center gap-2">
                <i class="fas fa-trophy text-amber-500"></i> Top Selling Books
            </h2>
            <table>
                <thead>
                    <tr>
                        <th>Book</th>
                        <th>Sold</th>
                        <th>Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topBooks as $book)
                    <tr>
                        <td><strong>{{ $book['book_title'] ?? 'Book #'.$book['book_id'] }}</strong></td>
                        <td>{{ $book['total_sold'] ?? $book['total_quantity'] }} units</td>
                        <td>₱{{ number_format($book['total_revenue'], 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" style="text-align:center; padding:20px; color:var(--color-gray-500);">No sales data available.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Revenue Breakdown -->
        <div class="admin-card">
            <h2 class="text-lg font-bold text-gray-800 dark:text-brand-gold font-display mb-4 border-b border-gray-200 dark:border-gray-700 pb-3 flex items-center gap-2">
                <i class="fas fa-chart-bar text-brand-forest"></i> Monthly Revenue Trend
            </h2>
            <div class="relative h-72">
                <canvas id="monthlyRevenueChart"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const rawMonthly = @json($revenue['revenue_by_month'] ?? []);
        
        let labels = [];
        let data = [];
        
        if (Object.keys(rawMonthly).length > 0) {
            labels = Object.keys(rawMonthly);
            data = Object.values(rawMonthly);
        } else {
            // Default sample data if empty
            labels = ['Jan 2026', 'Feb 2026', 'Mar 2026', 'Apr 2026', 'May 2026'];
            data = [5000, 12000, 8000, 15000, {{ $profit['total_revenue'] ?? 0 }}];
        }

        const ctx = document.getElementById('monthlyRevenueChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Monthly Revenue (₱)',
                    data: data,
                    backgroundColor: '#2D5F2B',
                    borderRadius: 6,
                    hoverBackgroundColor: '#3A7D38'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.05)' },
                        ticks: { color: '#888' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#888' }
                    }
                }
            }
        });
    });
</script>
@endsection

