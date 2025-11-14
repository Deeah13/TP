<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Terminal Pintar</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700" rel="stylesheet" />
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
        <style>
                :root {
                    color-scheme: light;
                    font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
                    --emerald-50: #ecfdf5;
                    --emerald-100: #d1fae5;
                    --emerald-500: #10b981;
                    --emerald-600: #059669;
                    --emerald-700: #047857;
                    --slate-50: #f8fafc;
                    --slate-100: #f1f5f9;
                    --slate-200: #e2e8f0;
                    --slate-400: #94a3b8;
                    --slate-500: #64748b;
                    --slate-700: #334155;
                    --slate-900: #0f172a;
                    background-color: var(--slate-50);
                }

                *,
                *::before,
                *::after {
                    box-sizing: border-box;
                }

                body {
                    margin: 0;
                    min-height: 100vh;
                    background: radial-gradient(circle at 0% 0%, rgba(16, 185, 129, 0.18), transparent 55%),
                        radial-gradient(circle at 100% 0%, rgba(56, 189, 248, 0.16), transparent 55%), var(--slate-50);
                    color: var(--slate-700);
                }

                img {
                    max-width: 100%;
                    display: block;
                }

                a {
                    text-decoration: none;
                    color: inherit;
                }

                header {
                    position: sticky;
                    top: 0;
                    z-index: 30;
                    background: rgba(255, 255, 255, 0.9);
                    backdrop-filter: blur(18px);
                    box-shadow: 0 1px 0 rgba(15, 23, 42, 0.08);
                }

                .max-container {
                    width: min(1120px, 92%);
                    margin-inline: auto;
                }

                .flex-between {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                }

                .nav-links {
                    display: none;
                    gap: 1.5rem;
                    font-size: 0.95rem;
                    font-weight: 500;
                    color: var(--slate-500);
                }

                .nav-links a:hover {
                    color: var(--emerald-600);
                }

                @media (min-width: 768px) {
                    .nav-links {
                        display: flex;
                    }
                }

                .logo-mark {
                    display: grid;
                    place-items: center;
                    width: 3rem;
                    height: 3rem;
                    border-radius: 999px;
                    background: linear-gradient(135deg, var(--emerald-500), var(--emerald-700));
                    color: #fff;
                    font-weight: 700;
                    font-size: 1.2rem;
                    box-shadow: 0 10px 25px rgba(16, 185, 129, 0.25);
                }

                .btn {
                    display: inline-flex;
                    align-items: center;
                    gap: 0.6rem;
                    padding: 0.8rem 1.6rem;
                    border-radius: 999px;
                    font-weight: 600;
                    font-size: 0.95rem;
                    transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
                }

                .hide-mobile {
                    display: none;
                }

                @media (min-width: 768px) {
                    .hide-mobile {
                        display: inline-flex;
                    }
                }

                .btn-primary {
                    background: linear-gradient(135deg, var(--emerald-500), #22d3ee);
                    color: white;
                    box-shadow: 0 18px 35px rgba(45, 212, 191, 0.35);
                }

                .btn-primary:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 22px 45px rgba(6, 182, 212, 0.4);
                }

                .btn-outline {
                    color: var(--emerald-600);
                    border: 1px solid rgba(16, 185, 129, 0.25);
                    background: white;
                    box-shadow: 0 4px 16px rgba(15, 118, 110, 0.1);
                }

                .btn-outline:hover {
                    transform: translateY(-1px);
                    border-color: rgba(16, 185, 129, 0.45);
                }

                .hero {
                    padding-block: clamp(5rem, 7vw, 8rem);
                    display: grid;
                    gap: clamp(3rem, 4vw, 4.5rem);
                    align-items: center;
                }

                @media (min-width: 960px) {
                    .hero {
                        grid-template-columns: 1.1fr 0.9fr;
                    }
                }

                .hero-copy {
                    display: grid;
                    gap: 1.5rem;
                }

                .hero-eyebrow {
                    display: inline-flex;
                    align-items: center;
                    gap: 0.5rem;
                    width: fit-content;
                    padding: 0.4rem 0.9rem;
                    border-radius: 999px;
                    background: rgba(56, 189, 248, 0.12);
                    color: #0ea5e9;
                    font-weight: 600;
                    font-size: 0.9rem;
                }

                .hero-title {
                    font-size: clamp(2.6rem, 5vw, 3.8rem);
                    font-weight: 700;
                    letter-spacing: -0.02em;
                    color: var(--slate-900);
                }

                .hero-desc {
                    font-size: 1.05rem;
                    line-height: 1.7;
                    color: var(--slate-500);
                }

                .hero-stats {
                    display: grid;
                    gap: 1rem;
                    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
                    margin-top: 1.5rem;
                }

                .stat-card {
                    padding: 1.35rem 1.4rem;
                    border-radius: 1.5rem;
                    background: #fff;
                    box-shadow: 0 24px 45px rgba(15, 23, 42, 0.08);
                    border: 1px solid rgba(148, 163, 184, 0.15);
                }

                .stat-number {
                    font-size: 2.1rem;
                    font-weight: 700;
                    color: var(--emerald-600);
                }

                .hero-media {
                    position: relative;
                    padding: 1.6rem;
                }

                .hero-media::before {
                    content: '';
                    position: absolute;
                    inset: 0;
                    border-radius: 2.5rem;
                    background: linear-gradient(135deg, rgba(16, 185, 129, 0.15), rgba(59, 130, 246, 0.1));
                    filter: blur(0);
                }

                .hero-media img {
                    position: relative;
                    border-radius: 1.8rem;
                    box-shadow: 0 35px 60px rgba(13, 148, 136, 0.28);
                }

                .section {
                    padding-block: clamp(4.5rem, 6vw, 6rem);
                }

                .section-header {
                    text-align: center;
                    margin-bottom: 3rem;
                    display: grid;
                    gap: 0.6rem;
                    justify-items: center;
                }

                .section-title {
                    font-size: clamp(2rem, 3.5vw, 2.6rem);
                    color: var(--slate-900);
                    font-weight: 700;
                    letter-spacing: -0.015em;
                }

                .section-desc {
                    max-width: 640px;
                    color: var(--slate-500);
                    font-size: 1rem;
                    line-height: 1.7;
                }

                .program-grid {
                    display: grid;
                    gap: 1.75rem;
                }

                @media (min-width: 768px) {
                    .program-grid {
                        grid-template-columns: repeat(3, minmax(0, 1fr));
                    }
                }

                .program-card {
                    border-radius: 1.5rem;
                    padding: 2rem 1.8rem;
                    background: linear-gradient(135deg, rgba(15, 23, 42, 0.02), rgba(16, 185, 129, 0.08));
                    border: 1px solid rgba(148, 163, 184, 0.18);
                    box-shadow: 0 16px 35px rgba(15, 23, 42, 0.06);
                    display: grid;
                    gap: 1.2rem;
                }

                .program-icon {
                    width: 3rem;
                    height: 3rem;
                    border-radius: 1.1rem;
                    display: grid;
                    place-items: center;
                    font-size: 1.25rem;
                    font-weight: 600;
                    color: var(--emerald-600);
                    background: rgba(16, 185, 129, 0.16);
                }

                .program-title {
                    font-size: 1.2rem;
                    font-weight: 600;
                    color: var(--slate-900);
                }

                .program-desc {
                    color: var(--slate-500);
                    line-height: 1.7;
                }

                .activities {
                    background: white;
                    border-radius: 2rem;
                    padding: clamp(2.5rem, 5vw, 3.5rem);
                    box-shadow: 0 40px 70px rgba(15, 23, 42, 0.12);
                }

                .activity-grid {
                    display: grid;
                    gap: 1.5rem;
                }

                @media (min-width: 880px) {
                    .activity-grid {
                        grid-template-columns: 1.1fr 0.9fr;
                    }
                }

                .activity-cards {
                    display: grid;
                    gap: 1.4rem;
                    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
                }

                .activity-card {
                    border-radius: 1.4rem;
                    overflow: hidden;
                    background: rgba(255, 255, 255, 0.9);
                    border: 1px solid rgba(226, 232, 240, 0.7);
                    box-shadow: 0 18px 35px rgba(15, 23, 42, 0.08);
                }

                .activity-card img {
                    height: 160px;
                    object-fit: cover;
                }

                .activity-card div {
                    padding: 1.1rem 1.3rem 1.3rem;
                }

                .activity-card h3 {
                    margin: 0 0 0.6rem;
                    color: var(--slate-900);
                    font-size: 1.1rem;
                    font-weight: 600;
                }

                .activity-card p {
                    margin: 0;
                    color: var(--slate-500);
                    line-height: 1.6;
                    font-size: 0.95rem;
                }

                .impact {
                    display: grid;
                    gap: 1.2rem;
                }

                .impact-item {
                    display: flex;
                    align-items: flex-start;
                    gap: 1.1rem;
                    padding-bottom: 1.2rem;
                    border-bottom: 1px solid rgba(148, 163, 184, 0.2);
                }

                .impact-item:last-child {
                    border-bottom: none;
                }

                .impact-badge {
                    width: 2.6rem;
                    height: 2.6rem;
                    border-radius: 0.9rem;
                    display: grid;
                    place-items: center;
                    font-weight: 600;
                    color: var(--emerald-600);
                    background: rgba(16, 185, 129, 0.14);
                }

                .impact-item h4 {
                    margin: 0 0 0.4rem;
                    color: var(--slate-900);
                    font-size: 1.05rem;
                }

                .impact-item p {
                    margin: 0;
                    color: var(--slate-500);
                    line-height: 1.6;
                }

                .about-grid {
                    display: grid;
                    gap: 2rem;
                }

                @media (min-width: 900px) {
                    .about-grid {
                        grid-template-columns: 1fr 1fr;
                    }
                }

                .about-card {
                    padding: 2.5rem;
                    border-radius: 2rem;
                    background: linear-gradient(135deg, rgba(14, 165, 233, 0.08), rgba(16, 185, 129, 0.14));
                    color: var(--slate-700);
                    box-shadow: 0 30px 55px rgba(15, 23, 42, 0.1);
                }

                .values-list {
                    display: grid;
                    gap: 1.2rem;
                    margin-top: 1.5rem;
                }

                .values-item {
                    display: flex;
                    gap: 1rem;
                    align-items: flex-start;
                }

                .values-dot {
                    width: 0.75rem;
                    height: 0.75rem;
                    border-radius: 999px;
                    background: linear-gradient(135deg, var(--emerald-500), #38bdf8);
                    margin-top: 0.45rem;
                    flex-shrink: 0;
                }

                .timeline {
                    display: grid;
                    gap: 1.5rem;
                    margin-top: 2.5rem;
                }

                .timeline-step {
                    display: grid;
                    gap: 0.35rem;
                    padding: 1.5rem;
                    border-radius: 1.4rem;
                    background: white;
                    border: 1px solid rgba(226, 232, 240, 0.8);
                    box-shadow: 0 16px 30px rgba(15, 23, 42, 0.08);
                }

                .timeline-step span {
                    font-size: 0.85rem;
                    font-weight: 600;
                    color: var(--emerald-600);
                    text-transform: uppercase;
                    letter-spacing: 0.08em;
                }

                .timeline-step h4 {
                    margin: 0;
                    font-size: 1.1rem;
                    color: var(--slate-900);
                }

                .timeline-step p {
                    margin: 0;
                    color: var(--slate-500);
                    line-height: 1.6;
                }

                .cta-banner {
                    position: relative;
                    overflow: hidden;
                    border-radius: 2rem;
                    padding: clamp(2.8rem, 5vw, 3.5rem);
                    background: radial-gradient(circle at top left, rgba(56, 189, 248, 0.22), transparent 55%),
                        linear-gradient(135deg, rgba(16, 185, 129, 0.95), rgba(14, 165, 233, 0.85));
                    color: white;
                    box-shadow: 0 30px 60px rgba(13, 148, 136, 0.45);
                }

                .cta-banner::after {
                    content: '';
                    position: absolute;
                    inset: -40% 40% 40% -40%;
                    background: radial-gradient(circle, rgba(255, 255, 255, 0.18), transparent 60%);
                    transform: rotate(12deg);
                }

                .cta-content {
                    position: relative;
                    display: grid;
                    gap: 1.5rem;
                    max-width: 520px;
                }

                .cta-actions {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 1rem;
                }

                .contact-section {
                    display: grid;
                    gap: 2rem;
                }

                @media (min-width: 900px) {
                    .contact-section {
                        grid-template-columns: 0.85fr 1fr;
                    }
                }

                .contact-card {
                    padding: 2.2rem;
                    border-radius: 1.8rem;
                    background: white;
                    box-shadow: 0 24px 45px rgba(15, 23, 42, 0.08);
                    border: 1px solid rgba(226, 232, 240, 0.7);
                    display: grid;
                    gap: 1.2rem;
                }

                .contact-card h3 {
                    margin: 0;
                    color: var(--slate-900);
                }

                .contact-info {
                    display: grid;
                    gap: 1rem;
                }

                .contact-info a {
                    display: inline-flex;
                    align-items: center;
                    gap: 0.75rem;
                    color: var(--slate-700);
                }

                .contact-info span {
                    width: 2.4rem;
                    height: 2.4rem;
                    border-radius: 0.9rem;
                    display: grid;
                    place-items: center;
                    background: rgba(56, 189, 248, 0.16);
                    color: #0284c7;
                    font-weight: 600;
                }

                form {
                    display: grid;
                    gap: 1rem;
                }

                label {
                    font-size: 0.9rem;
                    font-weight: 600;
                    color: var(--slate-600);
                    display: flex;
                    flex-direction: column;
                    gap: 0.35rem;
                }

                input,
                textarea {
                    padding: 0.85rem 1rem;
                    border-radius: 0.9rem;
                    border: 1px solid rgba(148, 163, 184, 0.4);
                    background: rgba(255, 255, 255, 0.85);
                    font-family: inherit;
                    font-size: 0.95rem;
                    resize: vertical;
                    transition: border-color 0.2s ease, box-shadow 0.2s ease;
                }

                input:focus,
                textarea:focus {
                    border-color: rgba(56, 189, 248, 0.8);
                    outline: none;
                    box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.15);
                }

                footer {
                    margin-top: 5rem;
                    padding-block: 3rem;
                    background: #0b1629;
                    color: #d1e6ff;
                }

                .footer-grid {
                    display: grid;
                    gap: 2rem;
                }

                @media (min-width: 880px) {
                    .footer-grid {
                        grid-template-columns: repeat(3, minmax(0, 1fr));
                    }
                }

                .footer-brand {
                    display: grid;
                    gap: 0.8rem;
                    max-width: 320px;
                }

                .footer-links {
                    display: grid;
                    gap: 0.6rem;
                    font-size: 0.95rem;
                    color: rgba(226, 232, 240, 0.75);
                }

                .footer-links a:hover {
                    color: white;
                }

                .footer-bottom {
                    margin-top: 2.5rem;
                    padding-top: 1.5rem;
                    border-top: 1px solid rgba(148, 163, 184, 0.25);
                    font-size: 0.85rem;
                    color: rgba(226, 232, 240, 0.6);
                }

                .badges {
                    display: flex;
                    gap: 0.8rem;
                }

                .badge {
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    width: 2.6rem;
                    height: 2.6rem;
                    border-radius: 0.85rem;
                    background: rgba(15, 118, 110, 0.15);
                    color: white;
                    font-weight: 600;
                }

                .tagline {
                    font-size: 0.95rem;
                    color: var(--slate-500);
                }

                .divider {
                    width: 100%;
                    height: 1px;
                    background: linear-gradient(to right, transparent, rgba(148, 163, 184, 0.35), transparent);
                    margin-block: 5rem;
                }
        </style>
    </head>
    <body class="bg-slate-50 text-slate-900 antialiased">
        <header>
            <div class="max-container flex-between" style="padding: 1rem 0;">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div class="logo-mark">TP</div>
                    <div>
                        <div style="font-weight: 700; font-size: 1.1rem; color: var(--slate-900);">Terminal Pintar</div>
                        <div class="tagline">Gerakan belajar kolaboratif untuk semua</div>
                    </div>
                </div>
                <nav class="nav-links">
                    <a href="#hero">Beranda</a>
                    <a href="#programs">Program</a>
                    <a href="#kegiatan">Kegiatan</a>
                    <a href="#tentang">Tentang</a>
                    <a href="#kontak">Kontak</a>
                </nav>
                <a href="{{ url('/login') }}" class="btn btn-outline hide-mobile">Masuk Portal</a>
            </div>
        </header>

        <main class="max-container">
            <section class="hero" id="hero">
                <div class="hero-copy">
                    <span class="hero-eyebrow">Pusat Kegiatan Belajar Terminal Pintar</span>
                    <h1 class="hero-title">Belajar, Berkarya, dan Berbagi Harapan untuk Anak Indonesia</h1>
                    <p class="hero-desc">
                        Terminal Pintar menghadirkan ruang belajar interaktif yang menghubungkan relawan, siswa,
                        dan orang tua. Kami percaya pendidikan berkualitas dimulai dari komunitas yang peduli.
                    </p>
                    <div style="display: flex; flex-wrap: wrap; gap: 0.9rem;">
                        <a href="#programs" class="btn btn-primary">Jelajahi Program</a>
                        <a href="#kontak" class="btn btn-outline">Mari Kolaborasi</a>
                    </div>
                    <div class="hero-stats">
                        <div class="stat-card">
                            <div class="stat-number">250+</div>
                            <div style="color: var(--slate-500); font-size: 0.95rem;">Anak belajar aktif setiap semester</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">120</div>
                            <div style="color: var(--slate-500); font-size: 0.95rem;">Relawan pendidik terlatih</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">35</div>
                            <div style="color: var(--slate-500); font-size: 0.95rem;">Kelas inspiratif dalam sepekan</div>
                        </div>
                    </div>
                </div>
                <div class="hero-media">
                    <img src="https://images.unsplash.com/photo-1523580846011-d3a5bc25702b?auto=format&fit=crop&w=900&q=80" alt="Anak-anak belajar bersama relawan" loading="lazy" />
                </div>
            </section>

            <div class="divider"></div>

            <section class="section" id="programs">
                <div class="section-header">
                    <span class="hero-eyebrow" style="background: rgba(16, 185, 129, 0.12); color: var(--emerald-600);">Program Utama</span>
                    <h2 class="section-title">Kolaborasi yang Mewujudkan Banyak Cerita Baik</h2>
                    <p class="section-desc">
                        Kami menyusun ragam program yang menjangkau kebutuhan belajar anak-anak dampingan secara utuh,
                        sekaligus membuka ruang kontribusi bagi Anda untuk terlibat.
                    </p>
                </div>
                <div class="program-grid">
                    <article class="program-card">
                        <div class="program-icon">A</div>
                        <div>
                            <h3 class="program-title">Akademi Terminal Pintar</h3>
                            <p class="program-desc">
                                Kurikulum tematik dengan pendekatan pengalaman nyata. Anak belajar literasi, numerasi,
                                hingga teknologi dasar secara bertahap dengan pendampingan intensif.
                            </p>
                        </div>
                        <div class="values-list" style="margin-top: 0;">
                            <div class="values-item">
                                <div class="values-dot"></div>
                                <div style="font-size: 0.92rem; color: var(--slate-500);">Kelas tatap muka di lingkungan komunitas</div>
                            </div>
                            <div class="values-item">
                                <div class="values-dot"></div>
                                <div style="font-size: 0.92rem; color: var(--slate-500);">Rencana belajar personal untuk tiap anak</div>
                            </div>
                        </div>
                    </article>
                    <article class="program-card">
                        <div class="program-icon">P</div>
                        <div>
                            <h3 class="program-title">Perpustakaan Digital</h3>
                            <p class="program-desc">
                                Koleksi bacaan dan multimedia terpilih yang dapat diakses kapan pun. Admin mencatat sirkulasi,
                                memastikan bahan bacaan selalu relevan dan menarik.
                            </p>
                        </div>
                        <div class="values-list" style="margin-top: 0;">
                            <div class="values-item">
                                <div class="values-dot"></div>
                                <div style="font-size: 0.92rem; color: var(--slate-500);">Katalog interaktif dan pencarian cepat</div>
                            </div>
                            <div class="values-item">
                                <div class="values-dot"></div>
                                <div style="font-size: 0.92rem; color: var(--slate-500);">Riwayat peminjaman selalu terpantau</div>
                            </div>
                        </div>
                    </article>
                    <article class="program-card">
                        <div class="program-icon">K</div>
                        <div>
                            <h3 class="program-title">Kolaborasi Orang Tua</h3>
                            <p class="program-desc">
                                Portal khusus untuk memantau progres anak melalui laporan perkembangan dan kehadiran.
                                Orang tua mudah mengakses catatan guru tanpa khawatir kehilangan informasi penting.
                            </p>
                        </div>
                        <div class="values-list" style="margin-top: 0;">
                            <div class="values-item">
                                <div class="values-dot"></div>
                                <div style="font-size: 0.92rem; color: var(--slate-500);">Notifikasi berkala dan ringkasan mingguan</div>
                            </div>
                            <div class="values-item">
                                <div class="values-dot"></div>
                                <div style="font-size: 0.92rem; color: var(--slate-500);">Akses aman sesuai hak masing-masing</div>
                            </div>
                        </div>
                    </article>
                </div>
            </section>

            <section class="section" id="kegiatan" style="padding-top: 0;">
                <div class="activities">
                    <div class="section-header" style="margin-bottom: 2.5rem;">
                        <span class="hero-eyebrow" style="background: rgba(14, 165, 233, 0.15); color: #0284c7;">Kilas Kegiatan</span>
                        <h2 class="section-title" style="margin-bottom: 0.2rem;">Cerita Lapangan dari Terminal Pintar</h2>
                        <p class="section-desc">
                            Dokumentasi rutin memastikan Anda selalu terhubung dengan perkembangan terbaru. Inilah
                            beberapa momen yang membanggakan dari komunitas kami.
                        </p>
                    </div>
                    <div class="activity-grid">
                        <div class="activity-cards">
                            <article class="activity-card">
                                <img src="https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?auto=format&fit=crop&w=900&q=80" alt="Workshop literasi" loading="lazy" />
                                <div>
                                    <h3>Workshop Literasi Kreatif</h3>
                                    <p>Anak mengeksplorasi cerita rakyat Nusantara dan mempresentasikan versi modern
                                        dengan media kolase.</p>
                                </div>
                            </article>
                            <article class="activity-card">
                                <img src="https://images.unsplash.com/photo-1517048676732-d65bc937f952?auto=format&fit=crop&w=900&q=80" alt="Kelas teknologi" loading="lazy" />
                                <div>
                                    <h3>Kelas Teknologi Dasar</h3>
                                    <p>Relawan mengenalkan konsep logika komputasi melalui permainan unplugged yang seru
                                        dan mudah dipahami.</p>
                                </div>
                            </article>
                            <article class="activity-card">
                                <img src="https://images.unsplash.com/photo-1531497865144-0464ef8fb9a9?auto=format&fit=crop&w=900&q=80" alt="Pertemuan orang tua" loading="lazy" />
                                <div>
                                    <h3>Forum Orang Tua</h3>
                                    <p>Berbagi insight perkembangan anak dan menyusun rencana dukungan belajar di rumah
                                        secara kolaboratif.</p>
                                </div>
                            </article>
                        </div>
                        <div class="impact">
                            <div class="impact-item">
                                <div class="impact-badge">01</div>
                                <div>
                                    <h4>Fokus pada Pembelajaran Bermakna</h4>
                                    <p>Kami merancang sesi yang memadukan teori dan praktik, memastikan setiap materi
                                        beresonansi dengan kehidupan nyata anak.</p>
                                </div>
                            </div>
                            <div class="impact-item">
                                <div class="impact-badge">02</div>
                                <div>
                                    <h4>Ekosistem Relawan Terhubung</h4>
                                    <p>Guru dan relawan mendapatkan akses dashboard khusus untuk mengelola sesi mereka,
                                        berbagi materi, dan memantau kehadiran.</p>
                                </div>
                            </div>
                            <div class="impact-item">
                                <div class="impact-badge">03</div>
                                <div>
                                    <h4>Transparansi untuk Orang Tua</h4>
                                    <p>Setiap progres anak tercatat rapi. Orang tua tinggal masuk ke portal untuk
                                        mendapatkan ringkasan yang mudah dipahami.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <div class="divider"></div>

            <section class="section" id="tentang">
                <div class="about-grid">
                    <article class="about-card">
                        <span class="hero-eyebrow" style="background: rgba(255, 255, 255, 0.3); color: white;">Mengapa Terminal Pintar</span>
                        <h2 class="section-title" style="color: white; font-size: 2.2rem;">Kami tumbuh dari semangat kolaborasi</h2>
                        <p style="color: rgba(255, 255, 255, 0.85); line-height: 1.7;">
                            Sejak 2018, Terminal Pintar hadir sebagai ruang pertemuan komunitas, relawan, dan anak-anak.
                            Platform digital kami memastikan setiap proses terdokumentasi dengan baik dan akuntabel.
                        </p>
                        <div class="values-list">
                            <div class="values-item">
                                <div class="values-dot"></div>
                                <div style="color: rgba(255, 255, 255, 0.85);">Manajemen data siswa yang aman dan terstruktur</div>
                            </div>
                            <div class="values-item">
                                <div class="values-dot"></div>
                                <div style="color: rgba(255, 255, 255, 0.85);">Rangkaian program berkembang sesuai kebutuhan lapangan</div>
                            </div>
                            <div class="values-item">
                                <div class="values-dot"></div>
                                <div style="color: rgba(255, 255, 255, 0.85);">Komunitas relawan solid dengan dukungan pelatihan berkala</div>
                            </div>
                        </div>
                    </article>
                    <div>
                        <div class="section-header" style="justify-items: flex-start; text-align: left; margin-bottom: 1.8rem;">
                            <span class="hero-eyebrow" style="background: rgba(16, 185, 129, 0.12); color: var(--emerald-600);">Cara Terlibat</span>
                            <h2 class="section-title" style="font-size: 2.1rem;">Langkah mudah untuk ikut berkontribusi</h2>
                            <p class="section-desc" style="text-align: left;">
                                Baik sebagai relawan maupun mitra, kami menyediakan jalur kolaborasi yang transparan dan
                                berdampak nyata bagi penerima manfaat.
                            </p>
                        </div>
                        <div class="timeline">
                            <div class="timeline-step">
                                <span>Langkah 1</span>
                                <h4>Daftar sebagai relawan/mitra</h4>
                                <p>Isi formulir minat dan pilih bidang keahlian Anda. Tim kami akan menjadwalkan sesi
                                    perkenalan untuk memahami keselarasan visi.</p>
                            </div>
                            <div class="timeline-step">
                                <span>Langkah 2</span>
                                <h4>Ikuti orientasi dan pelatihan</h4>
                                <p>Mendapatkan panduan penggunaan platform, alur dokumentasi kegiatan, serta standar
                                    keterlibatan di Terminal Pintar.</p>
                            </div>
                            <div class="timeline-step">
                                <span>Langkah 3</span>
                                <h4>Mulai berkarya bersama kami</h4>
                                <p>Rencanakan sesi belajar, unggah materi, atau dukung operasional perpustakaan digital.
                                    Dampak Anda akan dirasakan langsung oleh anak-anak.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="section" style="padding-top: 0;">
                <div class="cta-banner" id="participate">
                    <div class="cta-content">
                        <h2 style="font-size: clamp(2rem, 3.5vw, 2.6rem); font-weight: 700; letter-spacing: -0.015em; margin: 0;">Wujudkan ekosistem belajar yang lebih inklusif bersama Terminal Pintar</h2>
                        <p style="margin: 0; font-size: 1.05rem; line-height: 1.8; color: rgba(255, 255, 255, 0.85);">
                            Jadwalkan sesi kunjungan ke pusat kegiatan kami atau hubungi tim untuk memulai kolaborasi.
                            Kami siap membantu menyiapkan pengalaman terbaik bagi relawan dan anak.</p>
                        <div class="cta-actions">
                            <a href="mailto:halo@terminalpintar.id" class="btn btn-outline" style="background: white; color: var(--emerald-600);">Hubungi Kami</a>
                            <a href="{{ url('/login') }}" class="btn btn-primary" style="background: rgba(255, 255, 255, 0.18); color: white; box-shadow: none;">Masuk Portal</a>
                        </div>
                    </div>
                </div>
            </section>

            <section class="section" id="kontak">
                <div class="section-header" style="margin-bottom: 2.8rem;">
                    <span class="hero-eyebrow" style="background: rgba(15, 23, 42, 0.08); color: var(--slate-900);">Hubungi Kami</span>
                    <h2 class="section-title">Sampaikan pertanyaan atau jadwalkan kunjungan</h2>
                    <p class="section-desc">
                        Tim Terminal Pintar siap menjawab kebutuhan informasi publik maupun kolaborasi. Silakan tinggalkan
                        pesan dan kami akan menghubungi Anda maksimal dalam 2x24 jam kerja.
                    </p>
                </div>
                <div class="contact-section">
                    <div class="contact-card">
                        <h3>Informasi Kontak</h3>
                        <p style="margin: 0; color: var(--slate-500); line-height: 1.6;">
                            Kami beroperasi di Jakarta Selatan dan rutin menyelenggarakan kegiatan komunitas di berbagai
                            titik dampingan. Hubungi kami melalui kanal berikut.
                        </p>
                        <div class="contact-info">
                            <a href="mailto:halo@terminalpintar.id"><span>@</span>halo@terminalpintar.id</a>
                            <a href="tel:+622112345678"><span>☎</span>+62 21 1234 5678</a>
                            <a href="https://instagram.com/terminalpintar" target="_blank" rel="noopener"><span>#</span>@terminalpintar</a>
                        </div>
                        <div style="display: grid; gap: 0.8rem; margin-top: 0.6rem;">
                            <div style="display: flex; align-items: center; gap: 1rem;">
                                <div class="badge">ID</div>
                                <div>
                                    <div style="font-weight: 600; color: var(--slate-900);">Layanan publik transparan</div>
                                    <div style="font-size: 0.88rem; color: var(--slate-500);">Portal dapat diakses kapan pun</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <form>
                        <label>Nama Lengkap
                            <input type="text" placeholder="Isi nama Anda" required />
                        </label>
                        <label>Email
                            <input type="email" placeholder="nama@email.com" required />
                        </label>
                        <label>Pesan
                            <textarea rows="4" placeholder="Ceritakan kebutuhan informasi Anda"></textarea>
                        </label>
                        <button type="submit" class="btn btn-primary" style="justify-content: center;">Kirim Pesan</button>
                    </form>
                </div>
            </section>
        </main>

        <footer>
            <div class="max-container">
                <div class="footer-grid">
                    <div class="footer-brand">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div class="logo-mark" style="box-shadow: none;">TP</div>
                            <div>
                                <div style="font-weight: 700; font-size: 1.1rem;">Terminal Pintar</div>
                                <div style="font-size: 0.92rem; color: rgba(226, 232, 240, 0.75);">Belajar bersama, bertumbuh bersama</div>
                            </div>
                        </div>
                        <p style="margin: 0; color: rgba(226, 232, 240, 0.75); line-height: 1.7;">
                            Portal publik kami menghadirkan informasi terbaru mengenai program, dokumentasi kegiatan,
                            dan panduan kontribusi untuk masyarakat luas.
                        </p>
                    </div>
                    <div class="footer-links">
                        <strong style="color: white; font-size: 1rem;">Navigasi</strong>
                        <a href="#hero">Beranda</a>
                        <a href="#programs">Program</a>
                        <a href="#kegiatan">Kegiatan</a>
                        <a href="#tentang">Tentang</a>
                        <a href="#kontak">Kontak</a>
                    </div>
                    <div class="footer-links">
                        <strong style="color: white; font-size: 1rem;">Tetap Terhubung</strong>
                        <a href="mailto:halo@terminalpintar.id">Email</a>
                        <a href="https://instagram.com/terminalpintar" target="_blank" rel="noopener">Instagram</a>
                        <a href="https://youtube.com" target="_blank" rel="noopener">YouTube</a>
                        <a href="https://medium.com" target="_blank" rel="noopener">Blog</a>
                    </div>
                </div>
                <div class="footer-bottom">© {{ date('Y') }} Terminal Pintar. Seluruh hak cipta dilindungi.</div>
            </div>
        </footer>
    </body>
</html>
