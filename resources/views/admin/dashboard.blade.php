@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page_title', 'Dashboard Overview')

@section('styles')
<style>
    .stat-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 24px;
        margin-bottom: 2.5rem;
    }

    .stat-card {
        background: var(--color-white);
        border-radius: var(--radius-lg);
        padding: 24px;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--color-gray-200);
        position: relative;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-md);
    }

    .stat-card::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 4px;
    }

    .stat-card.green::after { background: var(--color-forest); }
    .stat-card.gold::after { background: var(--color-gold); }
    .stat-card.brown::after { background: var(--color-brown); }
    .stat-card.blue::after { background: var(--color-info); }

    .stat-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
    }

    .stat-card-title {
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--color-gray-500);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-card-icon {
        width: 44px;
        height: 44px;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        transition: transform 0.3s ease;
    }

    .stat-card:hover .stat-card-icon {
        transform: scale(1.1);
    }

    .stat-card-icon.green { background: rgba(45,95,43,0.1); color: var(--color-forest); }
    .stat-card-icon.gold { background: rgba(212,168,83,0.1); color: var(--color-gold); }
    .stat-card-icon.brown { background: rgba(107,66,38,0.1); color: var(--color-brown); }
    .stat-card-icon.blue { background: rgba(2,132,199,0.1); color: var(--color-info); }

    .stat-card-value {
        font-family: var(--font-display);
        font-size: 2.2rem;
        font-weight: 800;
        color: var(--color-gray-800);
        line-height: 1.1;
    }

    .stat-card-change {
        font-size: 0.8rem;
        margin-top: 8px;
        color: var(--color-gray-500);
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    
    .chart-container {
        background: var(--color-white);
        border: 1px solid var(--color-gray-200);
        border-radius: var(--radius-lg);
        padding: 24px;
        box-shadow: var(--shadow-sm);
    }

    @media (max-width: 768px) {
        .stat-grid { gap: 16px; }
    }
</style>
@endsection

@section('content')
<div>
    <!-- Stat Cards -->
    <div class="stat-grid">
        <div class="stat-card green animate-fade-in">
            <div class="stat-card-header">
                <span class="stat-card-title">Total Revenue</span>
                <div class="stat-card-icon green"><i class="fas fa-wallet"></i></div>
            </div>
            <div class="stat-card-value">₱{{ number_format($stats['total_revenue'] ?? 0, 2) }}</div>
            <div class="stat-card-change text-emerald-600"><i class="fas fa-arrow-trend-up"></i> Live Financial Status</div>
        </div>

        <div class="stat-card gold animate-fade-in">
            <div class="stat-card-header">
                <span class="stat-card-title">Total Orders</span>
                <div class="stat-card-icon gold"><i class="fas fa-shopping-bag"></i></div>
            </div>
            <div class="stat-card-value">{{ $stats['total_orders'] ?? 0 }}</div>
            <div class="stat-card-change text-amber-600"><i class="fas fa-shipping-fast"></i> Active Customer Orders</div>
        </div>

        <div class="stat-card brown animate-fade-in">
            <div class="stat-card-header">
                <span class="stat-card-title">Books in Catalog</span>
                <div class="stat-card-icon brown"><i class="fas fa-book"></i></div>
            </div>
            <div class="stat-card-value">{{ $stats['books_count'] ?? 0 }}</div>
            <div class="stat-card-change text-yellow-800 dark:text-amber-400"><i class="fas fa-check-circle"></i> Curated Titles Seeded</div>
        </div>

        <div class="stat-card blue animate-fade-in">
            <div class="stat-card-header">
                <span class="stat-card-title">Active Users</span>
                <div class="stat-card-icon blue"><i class="fas fa-users"></i></div>
            </div>
            <div class="stat-card-value">{{ $stats['active_users'] ?? 0 }}</div>
            <div class="stat-card-change text-sky-600"><i class="fas fa-user-shield"></i> Authorized Accounts</div>
        </div>
    </div>

    <!-- Beautiful Dashboard Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Sales Performance Trend -->
        <div class="chart-container lg:col-span-2">
            <h3 class="font-bold text-lg text-gray-800 dark:text-brand-gold font-display mb-4 flex items-center gap-2">
                <i class="fas fa-chart-line text-brand-forest"></i> Sales Performance Trend
            </h3>
            <div class="relative h-72">
                <canvas id="salesDashboardChart"></canvas>
            </div>
        </div>

        <!-- Catalog Composition doughnut chart -->
        <div class="chart-container">
            <h3 class="font-bold text-lg text-gray-800 dark:text-brand-gold font-display mb-4 flex items-center gap-2">
                <i class="fas fa-chart-pie text-brand-brown"></i> Book Categories
            </h3>
            <div class="relative h-72">
                <canvas id="categoryDashboardChart"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Load Chart.js from CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Sales Performance line chart
        const salesCtx = document.getElementById('salesDashboardChart').getContext('2d');
        new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Sales Revenue (₱)',
                    data: [12000, 19000, 15000, 25000, 22000, {{ $stats['total_revenue'] ?? 0 }}],
                    borderColor: '#2D5F2B',
                    backgroundColor: 'rgba(45, 95, 43, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#2D5F2B',
                    pointBorderColor: '#fff',
                    pointHoverRadius: 8
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

        // Category distribution doughnut chart
        const categoryCtx = document.getElementById('categoryDashboardChart').getContext('2d');
        new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: ['Fiction', 'Non-Fiction', 'Sci-Fi', 'Biography', 'History', 'Kids'],
                datasets: [{
                    data: [30, 20, 15, 12, 13, 10],
                    backgroundColor: ['#2D5F2B', '#6B4226', '#D4A853', '#0284C7', '#4A2D18', '#E8C878'],
                    borderWidth: 2,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#888',
                            boxWidth: 12,
                            font: { size: 11 }
                        }
                    }
                },
                cutout: '70%'
            }
        });
    });
</script>
@endsection

