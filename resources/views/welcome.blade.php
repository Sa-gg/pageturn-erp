<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="PageTurn Books — Your premium online bookstore. Discover, browse, and shop thousands of curated titles.">

    <title>PageTurn Books — Your Premium Online Bookstore</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --color-cream: #FDF8F0;
            --color-forest: #2D5F2B;
            --color-forest-light: #3A7D38;
            --color-brown: #6B4226;
            --color-brown-dark: #4A2D18;
            --color-gold: #D4A853;
            --color-gold-light: #E8C878;
            --color-dark: #1A1A2E;
            --color-white: #FFFFFF;
            --font-primary: 'Inter', sans-serif;
            --font-display: 'Outfit', sans-serif;
        }

        *, *::before, *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-primary);
            background: var(--color-cream);
            color: #444;
            -webkit-font-smoothing: antialiased;
        }

        /* ===== Hero ===== */
        .hero {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--color-dark) 0%, #1E2A1D 40%, var(--color-brown-dark) 100%);
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at 25% 75%, rgba(45, 95, 43, 0.2) 0%, transparent 50%),
                radial-gradient(circle at 75% 25%, rgba(212, 168, 83, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 50% 50%, rgba(107, 66, 38, 0.08) 0%, transparent 50%);
        }

        /* Floating particles */
        .hero-particle {
            position: absolute;
            opacity: 0.03;
            color: white;
            animation: floatParticle 25s ease-in-out infinite;
        }

        .hero-particle:nth-child(1) { top: 15%; left: 8%; font-size: 12rem; animation-delay: 0s; }
        .hero-particle:nth-child(2) { top: 60%; right: 5%; font-size: 8rem; animation-delay: -8s; }
        .hero-particle:nth-child(3) { bottom: 10%; left: 30%; font-size: 6rem; animation-delay: -16s; }
        .hero-particle:nth-child(4) { top: 30%; right: 30%; font-size: 10rem; animation-delay: -5s; }

        @keyframes floatParticle {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            25% { transform: translateY(-30px) rotate(5deg); }
            75% { transform: translateY(20px) rotate(-3deg); }
        }

        /* Navbar */
        .hero-nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 4rem;
            position: relative;
            z-index: 10;
        }

        .hero-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            color: white;
            font-family: var(--font-display);
            font-weight: 700;
            font-size: 1.4rem;
            text-decoration: none;
        }

        .hero-brand-icon {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, var(--color-forest), var(--color-forest-light));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            box-shadow: 0 4px 15px rgba(45, 95, 43, 0.4);
        }

        .hero-brand span { color: var(--color-gold); }

        .hero-nav-links {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .hero-nav-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 22px;
            border-radius: 8px;
            font-family: var(--font-primary);
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .hero-nav-btn-outline {
            color: rgba(255,255,255,0.8);
            border: 1.5px solid rgba(255,255,255,0.2);
        }

        .hero-nav-btn-outline:hover {
            background: rgba(255,255,255,0.08);
            border-color: rgba(255,255,255,0.4);
            color: white;
        }

        .hero-nav-btn-primary {
            background: linear-gradient(135deg, var(--color-forest), var(--color-forest-light));
            color: white;
            border: none;
            box-shadow: 0 4px 15px rgba(45, 95, 43, 0.3);
        }

        .hero-nav-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(45, 95, 43, 0.4);
        }

        /* Hero Content */
        .hero-content {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 4rem;
            position: relative;
            z-index: 5;
        }

        .hero-text {
            text-align: center;
            max-width: 700px;
            color: white;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 18px;
            background: rgba(212, 168, 83, 0.15);
            border: 1px solid rgba(212, 168, 83, 0.2);
            border-radius: 50px;
            color: var(--color-gold);
            font-size: 0.8rem;
            font-weight: 600;
            margin-bottom: 2rem;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            backdrop-filter: blur(10px);
        }

        .hero-title {
            font-family: var(--font-display);
            font-size: 4rem;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            letter-spacing: -2px;
        }

        .hero-title span {
            background: linear-gradient(135deg, var(--color-gold), var(--color-gold-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-subtitle {
            font-size: 1.2rem;
            color: rgba(255,255,255,0.55);
            line-height: 1.7;
            margin-bottom: 2.5rem;
            max-width: 550px;
            margin-left: auto;
            margin-right: auto;
        }

        .hero-actions {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        .hero-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 16px 36px;
            border-radius: 12px;
            font-family: var(--font-primary);
            font-size: 1rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .hero-btn-primary {
            background: linear-gradient(135deg, var(--color-forest), var(--color-forest-light));
            color: white;
            box-shadow: 0 8px 30px rgba(45, 95, 43, 0.4);
        }

        .hero-btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 40px rgba(45, 95, 43, 0.5);
        }

        .hero-btn-secondary {
            background: rgba(255,255,255,0.06);
            color: white;
            border: 1.5px solid rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
        }

        .hero-btn-secondary:hover {
            background: rgba(255,255,255,0.12);
            border-color: rgba(255,255,255,0.3);
            transform: translateY(-2px);
        }

        /* Stats Bar */
        .hero-stats {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 48px;
            margin-top: 4rem;
            padding-bottom: 3rem;
            position: relative;
            z-index: 5;
        }

        .hero-stat {
            text-align: center;
            color: white;
        }

        .hero-stat-number {
            font-family: var(--font-display);
            font-size: 2rem;
            font-weight: 700;
            color: var(--color-gold);
            line-height: 1;
            margin-bottom: 4px;
        }

        .hero-stat-label {
            font-size: 0.8rem;
            color: rgba(255,255,255,0.4);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .hero-stat-divider {
            width: 1px;
            height: 40px;
            background: rgba(255,255,255,0.1);
        }

        /* ===== Features Section ===== */
        .features-section {
            padding: 6rem 4rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .features-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .features-header h2 {
            font-family: var(--font-display);
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--color-brown-dark);
            margin-bottom: 1rem;
            letter-spacing: -1px;
        }

        .features-header p {
            font-size: 1.1rem;
            color: #888;
            max-width: 500px;
            margin: 0 auto;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }

        .feature-card {
            background: white;
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            border: 1px solid rgba(0,0,0,0.04);
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 40px rgba(0,0,0,0.1);
        }

        .feature-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            margin-bottom: 20px;
        }

        .feature-icon.green { background: rgba(45,95,43,0.1); color: var(--color-forest); }
        .feature-icon.brown { background: rgba(107,66,38,0.1); color: var(--color-brown); }
        .feature-icon.gold { background: rgba(212,168,83,0.1); color: var(--color-gold); }

        .feature-title {
            font-family: var(--font-display);
            font-size: 1.15rem;
            font-weight: 600;
            color: var(--color-brown-dark);
            margin-bottom: 8px;
        }

        .feature-desc {
            font-size: 0.9rem;
            color: #888;
            line-height: 1.6;
        }

        /* ===== CTA Section ===== */
        .cta-section {
            background: linear-gradient(135deg, var(--color-forest-dark), var(--color-dark));
            padding: 5rem 4rem;
            text-align: center;
            color: white;
        }

        .cta-section h2 {
            font-family: var(--font-display);
            font-size: 2.2rem;
            font-weight: 800;
            margin-bottom: 1rem;
            color: white;
        }

        .cta-section p {
            color: rgba(255,255,255,0.55);
            font-size: 1.05rem;
            margin-bottom: 2rem;
            max-width: 450px;
            margin-left: auto;
            margin-right: auto;
        }

        /* ===== Footer ===== */
        .landing-footer {
            background: var(--color-dark);
            color: rgba(255,255,255,0.5);
            padding: 2rem 4rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .landing-footer-brand {
            font-family: var(--font-display);
            font-weight: 700;
            font-size: 1.1rem;
            color: white;
        }

        .landing-footer-brand span { color: var(--color-gold); }

        .landing-footer-copy {
            font-size: 0.82rem;
        }

        /* ===== Responsive ===== */
        @media (max-width: 768px) {
            .hero-nav { padding: 16px 1.5rem; }
            .hero-content { padding: 0 1.5rem; }
            .hero-title { font-size: 2.5rem; }
            .hero-subtitle { font-size: 1rem; }
            .hero-stats { gap: 24px; flex-wrap: wrap; }
            .hero-stat-divider { display: none; }
            .features-section { padding: 3rem 1.5rem; }
            .features-grid { grid-template-columns: 1fr; }
            .cta-section { padding: 3rem 1.5rem; }
            .landing-footer { flex-direction: column; gap: 12px; text-align: center; padding: 2rem 1.5rem; }
            .hero-nav-links { gap: 6px; }
        }
    </style>
</head>
<body>
    <!-- ===== Hero Section ===== -->
    <section class="hero">
        <i class="fas fa-book hero-particle"></i>
        <i class="fas fa-bookmark hero-particle"></i>
        <i class="fas fa-book-reader hero-particle"></i>
        <i class="fas fa-feather-alt hero-particle"></i>

        <nav class="hero-nav">
            <a class="hero-brand" href="{{ url('/') }}">
                <div class="hero-brand-icon">
                    <i class="fas fa-book-open"></i>
                </div>
                Page<span>Turn</span>
            </a>

            <div class="hero-nav-links">
                @if(session('user'))
                    <a href="{{ url('/home') }}" class="hero-nav-btn hero-nav-btn-primary">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="hero-nav-btn hero-nav-btn-outline">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </a>
                    <a href="{{ route('register') }}" class="hero-nav-btn hero-nav-btn-primary">
                        <i class="fas fa-user-plus"></i> Get Started
                    </a>
                @endif
            </div>
        </nav>

        <div class="hero-content">
            <div class="hero-text">
                <div class="hero-badge">
                    <i class="fas fa-star"></i> Premium Online Bookstore
                </div>
                <h1 class="hero-title">
                    Discover Your<br>Next <span>Great Read</span>
                </h1>
                <p class="hero-subtitle">
                    Browse thousands of curated titles across every genre. From bestsellers to hidden gems, your next favorite book is just a click away.
                </p>
                <div class="hero-actions">
                    <a href="{{ route('register') }}" class="hero-btn hero-btn-primary">
                        <i class="fas fa-rocket"></i> Start Reading
                    </a>
                    <a href="#features" class="hero-btn hero-btn-secondary">
                        <i class="fas fa-info-circle"></i> Learn More
                    </a>
                </div>
            </div>
        </div>

        <div class="hero-stats">
            <div class="hero-stat">
                <div class="hero-stat-number">10,000+</div>
                <div class="hero-stat-label">Books</div>
            </div>
            <div class="hero-stat-divider"></div>
            <div class="hero-stat">
                <div class="hero-stat-number">5,000+</div>
                <div class="hero-stat-label">Happy Readers</div>
            </div>
            <div class="hero-stat-divider"></div>
            <div class="hero-stat">
                <div class="hero-stat-number">500+</div>
                <div class="hero-stat-label">Authors</div>
            </div>
            <div class="hero-stat-divider"></div>
            <div class="hero-stat">
                <div class="hero-stat-number">4.9★</div>
                <div class="hero-stat-label">Rating</div>
            </div>
        </div>
    </section>

    <!-- ===== Features Section ===== -->
    <section class="features-section" id="features">
        <div class="features-header">
            <h2>Why PageTurn Books?</h2>
            <p>Everything you need for a perfect reading experience, all in one place.</p>
        </div>

        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon green">
                    <i class="fas fa-search"></i>
                </div>
                <div class="feature-title">Smart Catalog</div>
                <div class="feature-desc">Browse by category, author, or genre. Advanced filters and search help you find exactly what you're looking for.</div>
            </div>

            <div class="feature-card">
                <div class="feature-icon brown">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="feature-title">Easy Checkout</div>
                <div class="feature-desc">Add books to your cart and checkout in seconds. Multiple payment methods and instant order confirmation.</div>
            </div>

            <div class="feature-card">
                <div class="feature-icon gold">
                    <i class="fas fa-truck"></i>
                </div>
                <div class="feature-title">Order Tracking</div>
                <div class="feature-desc">Track your orders in real-time from confirmation to delivery. Get notifications at every step.</div>
            </div>

            <div class="feature-card">
                <div class="feature-icon green">
                    <i class="fas fa-star"></i>
                </div>
                <div class="feature-title">Reviews & Ratings</div>
                <div class="feature-desc">Read genuine reviews from fellow book lovers. Share your own thoughts and help others discover great books.</div>
            </div>

            <div class="feature-card">
                <div class="feature-icon brown">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <div class="feature-title">Admin Dashboard</div>
                <div class="feature-desc">Powerful admin tools for managing inventory, orders, suppliers, and financial reports all in one place.</div>
            </div>

            <div class="feature-card">
                <div class="feature-icon gold">
                    <i class="fas fa-lock"></i>
                </div>
                <div class="feature-title">Secure & Fast</div>
                <div class="feature-desc">Token-based authentication, role-based access, and microservices architecture for speed and reliability.</div>
            </div>
        </div>
    </section>

    <!-- ===== CTA Section ===== -->
    <section class="cta-section">
        <h2>Ready to Start Reading?</h2>
        <p>Join thousands of readers who've already discovered their next favorite book on PageTurn.</p>
        <a href="{{ route('register') }}" class="hero-btn hero-btn-primary" style="display: inline-flex;">
            <i class="fas fa-user-plus"></i> Create Free Account
        </a>
    </section>

    <!-- ===== Footer ===== -->
    <footer class="landing-footer">
        <div class="landing-footer-brand">
            Page<span>Turn</span> Books
        </div>
        <div class="landing-footer-copy">
            &copy; {{ date('Y') }} PageTurn Books. All rights reserved. Built with ❤️ using Laravel.
        </div>
    </footer>
</body>
</html>
