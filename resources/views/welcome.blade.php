<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Sistem Informasi Manajemen Kinerja (SIMKIN) - eKinerja Puskesmas Bugangan. Kelola penilaian kinerja pegawai secara digital.">

    <title>eKinerja - Puskesmas Bugangan</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #0e7490;
            --primary-light: #22d3ee;
            --surface-glass: rgba(255, 255, 255, 0.08);
            --surface-glass-strong: rgba(255, 255, 255, 0.15);
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #0c4a6e 0%, #0e7490 35%, #155e75 65%, #164e63 100%);
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }

        /* ===== Animated Background ===== */
        .bg-orbs {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 0;
            overflow: hidden;
        }

        .bg-orbs .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.15;
            animation: float 20s ease-in-out infinite;
        }

        .bg-orbs .orb:nth-child(1) {
            width: 600px; height: 600px;
            background: var(--primary-light);
            top: -200px; right: -100px;
        }

        .bg-orbs .orb:nth-child(2) {
            width: 500px; height: 500px;
            background: #6366f1;
            bottom: -150px; left: -100px;
            animation-delay: -7s;
        }

        .bg-orbs .orb:nth-child(3) {
            width: 400px; height: 400px;
            background: #14b8a6;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            animation-delay: -14s;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            25% { transform: translate(30px, -30px) scale(1.05); }
            50% { transform: translate(-20px, 20px) scale(0.95); }
            75% { transform: translate(20px, 10px) scale(1.02); }
        }

        .grid-pattern {
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
            background-size: 60px 60px;
            pointer-events: none;
            z-index: 0;
        }

        /* ===== Main Container ===== */
        .main-container {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ===== Navbar ===== */
        .navbar {
            padding: 1.25rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            max-width: 1280px;
            margin: 0 auto;
            width: 100%;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
        }

        .brand-icon {
            width: 44px; height: 44px;
            background: var(--surface-glass-strong);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.15);
            box-shadow: 0 4px 16px rgba(0,0,0,0.1);
        }

        .brand-icon svg {
            width: 26px; height: 26px;
            color: var(--primary-light);
        }

        .brand-text {
            display: flex;
            flex-direction: column;
        }

        .brand-text .title {
            font-size: 1.15rem;
            font-weight: 700;
            color: #fff;
            letter-spacing: -0.02em;
        }

        .brand-text .subtitle {
            font-size: 0.7rem;
            color: rgba(255,255,255,0.6);
            font-weight: 400;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        .nav-badge {
            background: var(--surface-glass);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.12);
            padding: 0.4rem 1rem;
            border-radius: 999px;
            color: rgba(255,255,255,0.8);
            font-size: 0.75rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-badge .dot {
            width: 7px; height: 7px;
            background: #34d399;
            border-radius: 50%;
            animation: pulse-dot 2s ease-in-out infinite;
        }

        @keyframes pulse-dot {
            0%, 100% { opacity: 1; box-shadow: 0 0 0 0 rgba(52, 211, 153, 0.4); }
            50% { opacity: 0.8; box-shadow: 0 0 0 6px rgba(52, 211, 153, 0); }
        }

        /* ===== Hero Section ===== */
        .hero {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem 1.5rem 3rem;
            text-align: center;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--surface-glass);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.12);
            padding: 0.45rem 1.2rem;
            border-radius: 999px;
            color: rgba(255,255,255,0.85);
            font-size: 0.8rem;
            font-weight: 500;
            margin-bottom: 2rem;
            animation: fadeInDown 0.8s ease-out;
        }

        .hero-badge svg {
            width: 16px; height: 16px;
            color: var(--primary-light);
        }

        .hero h1 {
            font-size: clamp(2.2rem, 5vw, 3.8rem);
            font-weight: 800;
            color: #fff;
            line-height: 1.1;
            letter-spacing: -0.03em;
            margin-bottom: 1.25rem;
            animation: fadeInUp 0.8s ease-out;
        }

        .hero h1 .highlight {
            background: linear-gradient(135deg, var(--primary-light), #a5f3fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-desc {
            font-size: 1.1rem;
            color: rgba(255,255,255,0.65);
            max-width: 560px;
            margin: 0 auto 2.5rem;
            line-height: 1.7;
            font-weight: 400;
            animation: fadeInUp 0.8s ease-out 0.15s both;
        }

        /* ===== Login Button ===== */
        .login-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            background: linear-gradient(135deg, #fff, #e0f2fe);
            color: #0c4a6e;
            font-size: 1.05rem;
            font-weight: 700;
            padding: 1rem 2.5rem;
            border-radius: 14px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.35s cubic-bezier(0.23, 1, 0.32, 1);
            box-shadow: 0 8px 32px rgba(0,0,0,0.18), 0 0 0 1px rgba(255,255,255,0.2);
            animation: fadeInUp 0.8s ease-out 0.3s both;
            letter-spacing: -0.01em;
        }

        .login-btn:hover {
            transform: translateY(-4px) scale(1.03);
            box-shadow: 0 16px 48px rgba(0,0,0,0.25), 0 0 0 1px rgba(255,255,255,0.3), 0 0 60px rgba(34, 211, 238, 0.15);
            background: linear-gradient(135deg, #fff, #cffafe);
        }

        .login-btn:active {
            transform: translateY(-1px) scale(1.01);
        }

        .login-btn svg {
            width: 20px; height: 20px;
            transition: transform 0.3s ease;
        }

        .login-btn:hover svg {
            transform: translateX(4px);
        }

        /* ===== Features ===== */
        .features {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.25rem;
            max-width: 800px;
            width: 100%;
            margin: 3rem auto 0;
            animation: fadeInUp 0.8s ease-out 0.45s both;
        }

        .feature {
            background: var(--surface-glass);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 16px;
            padding: 1.5rem 1.25rem;
            text-align: center;
            transition: all 0.3s ease;
        }

        .feature:hover {
            background: rgba(255,255,255,0.12);
            border-color: rgba(255,255,255,0.2);
            transform: translateY(-3px);
        }

        .feature-icon {
            width: 48px; height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.75rem;
        }

        .feature:nth-child(1) .feature-icon {
            background: rgba(99, 102, 241, 0.2);
            border: 1px solid rgba(99, 102, 241, 0.3);
        }

        .feature:nth-child(2) .feature-icon {
            background: rgba(14, 165, 233, 0.2);
            border: 1px solid rgba(14, 165, 233, 0.3);
        }

        .feature:nth-child(3) .feature-icon {
            background: rgba(20, 184, 166, 0.2);
            border: 1px solid rgba(20, 184, 166, 0.3);
        }

        .feature-icon svg {
            width: 24px; height: 24px;
            color: #fff;
        }

        .feature-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: #fff;
            margin-bottom: 0.3rem;
        }

        .feature-desc {
            font-size: 0.75rem;
            color: rgba(255,255,255,0.5);
            line-height: 1.5;
        }

        /* ===== Footer ===== */
        .footer {
            text-align: center;
            padding: 1.5rem 1.5rem 2rem;
            color: rgba(255,255,255,0.35);
            font-size: 0.78rem;
        }

        /* ===== Animations ===== */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(24px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-16px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ===== Responsive ===== */
        @media (max-width: 768px) {
            .navbar { padding: 1rem 1.25rem; }
            .nav-badge { display: none; }
            .hero { padding: 1.5rem 1.25rem 2rem; }
            .hero-desc { font-size: 0.95rem; margin-bottom: 2rem; }
            .features {
                grid-template-columns: 1fr;
                max-width: 340px;
                gap: 0.75rem;
                margin-top: 2rem;
            }
            .feature {
                display: flex;
                align-items: center;
                gap: 1rem;
                text-align: left;
                padding: 1rem 1.25rem;
            }
            .feature-icon {
                margin: 0;
                min-width: 42px;
                width: 42px; height: 42px;
            }
            .feature-icon svg { width: 20px; height: 20px; }
        }

        @media (max-width: 480px) {
            .hero h1 { font-size: 1.8rem; }
            .brand-text .title { font-size: 1rem; }
            .brand-text .subtitle { font-size: 0.6rem; }
        }
    </style>
</head>
<body>
    <div class="bg-orbs">
        <div class="orb"></div>
        <div class="orb"></div>
        <div class="orb"></div>
    </div>
    <div class="grid-pattern"></div>

    <div class="main-container">
        <!-- Navbar -->
        <header class="navbar">
            <a href="/" class="navbar-brand">
                <div class="brand-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M8 2v4h8V2"/>
                        <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                        <path d="M12 11v6"/>
                        <path d="M9 14h6"/>
                    </svg>
                </div>
                <div class="brand-text">
                    <span class="title">eKinerja</span>
                    <span class="subtitle">Puskesmas Bugangan</span>
                </div>
            </a>
            <div class="nav-badge">
                <span class="dot"></span>
                Sistem Aktif
            </div>
        </header>

        <!-- Hero -->
        <section class="hero">
            <div class="hero-badge">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                </svg>
                Sistem Informasi Manajemen Kinerja
            </div>

            <h1>
                Kelola <span class="highlight">Kinerja</span><br>
                Pegawai Digital
            </h1>

            <p class="hero-desc">
                Platform digital untuk penilaian dan pengelolaan kinerja pegawai
                Puskesmas Bugangan, Kota Semarang. Transparan, akurat, dan efisien.
            </p>

            <!-- Satu Tombol Login -->
            <a href="{{ url('/admin/login') }}" class="login-btn" id="btn-login">
                Masuk ke Sistem
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14"/>
                    <path d="m12 5 7 7-7 7"/>
                </svg>
            </a>

            <!-- Fitur Info -->
            <div class="features">
                <div class="feature">
                    <div class="feature-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                    </div>
                    <div>
                        <div class="feature-title">Multi Role</div>
                        <div class="feature-desc">Admin, Pegawai & Kepala dalam satu sistem</div>
                    </div>
                </div>
                <div class="feature">
                    <div class="feature-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 20h9"/>
                            <path d="M16.376 3.622a1 1 0 0 1 3.002 3.002L7.368 18.635a2 2 0 0 1-.855.506l-2.872.838.838-2.872a2 2 0 0 1 .506-.855L16.376 3.622Z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="feature-title">Penilaian Digital</div>
                        <div class="feature-desc">SKP & realisasi kinerja secara online</div>
                    </div>
                </div>
                <div class="feature">
                    <div class="feature-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="feature-title">Aman & Terenkripsi</div>
                        <div class="feature-desc">Data terlindungi, akses 24/7</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="footer">
            &copy; {{ date('Y') }} Puskesmas Bugangan &mdash; Dinas Kesehatan Kota Semarang<br>
            <span style="opacity: 0.7;">Sistem Informasi Manajemen Kinerja (SIMKIN)</span>
        </footer>
    </div>
</body>
</html>
