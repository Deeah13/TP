<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Terminal Pintar</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <style>
                :root {
                    color-scheme: light;
                    font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
                    --primary: #4caf50;
                    --primary-dark: #3a8f3d;
                    --primary-light: #e8f7ea;
                    --accent: #f9b934;
                    --text: #1f2933;
                    --muted: #6b7280;
                    background-color: #f5f7fa;
                }

                *,
                *::before,
                *::after {
                    box-sizing: border-box;
                }

                body {
                    margin: 0;
                    color: var(--text);
                    background-color: #f5f7fa;
                }

                a {
                    color: inherit;
                    text-decoration: none;
                }

                .container {
                    max-width: 1120px;
                    margin: 0 auto;
                    padding: 0 1.5rem;
                }

                .btn-primary {
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    padding: 0.75rem 1.75rem;
                    border-radius: 9999px;
                    background: linear-gradient(135deg, var(--primary), #6fda73);
                    color: #fff;
                    font-weight: 600;
                    transition: transform 0.2s ease, box-shadow 0.2s ease;
                    box-shadow: 0 12px 30px rgba(76, 175, 80, 0.25);
                }

                .btn-primary:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 16px 35px rgba(76, 175, 80, 0.35);
                }

                header {
                    position: sticky;
                    top: 0;
                    z-index: 10;
                    background: rgba(255, 255, 255, 0.85);
                    backdrop-filter: blur(12px);
                    box-shadow: 0 1px 0 rgba(15, 23, 42, 0.08);
                }

                .hero {
                    padding: 5rem 0 4rem;
                }

                .hero-content {
                    display: grid;
                    gap: 3rem;
                    align-items: center;
                    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
                }

                .hero-image {
                    border-radius: 32px;
                    overflow: hidden;
                    box-shadow: 0 35px 70px rgba(74, 222, 128, 0.2);
                }

                .hero-image img {
                    width: 100%;
                    display: block;
                }

                .section-title {
                    text-align: center;
                    font-size: clamp(1.75rem, 3vw, 2.4rem);
                    font-weight: 700;
                    margin-bottom: 1rem;
                }

                .section-subtitle {
                    text-align: center;
                    color: var(--muted);
                    margin-bottom: 2.5rem;
                }

                .card-grid {
                    display: grid;
                    gap: 1.75rem;
                    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
                }

                .card {
                    border-radius: 24px;
                    background: #fff;
                    padding: 1.75rem;
                    box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);
                    display: flex;
                    flex-direction: column;
                    gap: 1.25rem;
                }

                .card img {
                    width: 100%;
                    border-radius: 18px;
                    object-fit: cover;
                    height: 180px;
                }

                .tag {
                    display: inline-flex;
                    align-items: center;
                    gap: 0.35rem;
                    padding: 0.35rem 0.85rem;
                    border-radius: 999px;
                    background: rgba(76, 175, 80, 0.12);
                    color: var(--primary-dark);
                    font-weight: 600;
                    font-size: 0.85rem;
                }

                .about {
                    display: grid;
                    gap: 2rem;
                    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
                    align-items: start;
                }

                .pill {
                    display: inline-flex;
                    align-items: center;
                    gap: 0.5rem;
                    padding: 0.35rem 1rem;
                    border-radius: 999px;
                    background: rgba(79, 70, 229, 0.08);
                    color: #4338ca;
                    font-weight: 600;
                    margin-bottom: 1rem;
                }

                .team-grid {
                    display: grid;
                    gap: 1.5rem;
                    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
                    margin-top: 2.5rem;
                }

                .team-card {
                    background: #fff;
                    border-radius: 20px;
                    padding: 1.75rem 1.5rem 1.5rem;
                    text-align: center;
                    box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);
                }

                .team-card img {
                    width: 88px;
                    height: 88px;
                    object-fit: cover;
                    border-radius: 50%;
                    margin-bottom: 1rem;
                }

                .cta-grid {
                    display: grid;
                    gap: 1.5rem;
                    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
                }

                .cta-card {
                    border-radius: 24px;
                    background: linear-gradient(135deg, #fff, #f2fdf4);
                    padding: 2rem;
                    display: flex;
                    flex-direction: column;
                    gap: 1.25rem;
                    box-shadow: 0 12px 30px rgba(76, 175, 80, 0.15);
                    border: 1px solid rgba(76, 175, 80, 0.15);
                }

                footer {
                    background: #0f172a;
                    color: #e2e8f0;
                    padding: 2.5rem 0;
                }

                .footer-grid {
                    display: grid;
                    gap: 2rem;
                    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
                }

                .socials {
                    display: grid;
                    gap: 0.75rem;
                }

                .socials a {
                    display: inline-flex;
                    align-items: center;
                    gap: 0.75rem;
                    color: inherit;
                }

                .badge {
                    width: 44px;
                    height: 44px;
                    border-radius: 50%;
                    background: var(--primary-light);
                    display: grid;
                    place-content: center;
                    color: var(--primary-dark);
                    font-weight: 700;
                    font-size: 1.1rem;
                }

                nav {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    padding: 1rem 0;
                }

                .nav-links {
                    display: flex;
                    align-items: center;
                    gap: 1.5rem;
                    color: var(--muted);
                    font-weight: 500;
                }

                .login-btn {
                    padding: 0.65rem 1.25rem;
                    border-radius: 999px;
                    background: #fff;
                    color: var(--primary-dark);
                    font-weight: 600;
                    border: 1px solid rgba(76, 175, 80, 0.25);
                    box-shadow: 0 4px 12px rgba(76, 175, 80, 0.18);
                }
            </style>
        @endif
    </head>
    <body class="bg-slate-50 text-slate-900 antialiased">
        <header class="sticky top-0 z-20 bg-white/80 backdrop-blur border-b border-slate-200/60">
            <div class="container mx-auto flex max-w-6xl items-center justify-between px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-emerald-500 text-white text-xl font-bold shadow-lg shadow-emerald-200">TP</div>
                    <div>
                        <div class="text-xl font-semibold text-slate-900">Terminal Pintar</div>
                        <div class="text-sm text-slate-500">Membuka ruang belajar untuk semua</div>
                    </div>
                </div>
                <nav class="hidden items-center gap-8 text-sm font-medium text-slate-600 md:flex">
                    <a href="#activities" class="hover:text-emerald-600 transition-colors">Kegiatan</a>
                    <a href="#about" class="hover:text-emerald-600 transition-colors">Tentang Kami</a>
                    <a href="#contact" class="hover:text-emerald-600 transition-colors">Kontak</a>
                </nav>
                <a href="{{ url('/login') }}" class="hidden rounded-full border border-emerald-200 bg-white px-6 py-2 text-sm font-semibold text-emerald-600 shadow-sm transition hover:border-emerald-300 hover:shadow-md md:inline-flex">
                    Login
                </a>
            </div>
        </header>

        <main class="mx-auto max-w-6xl px-6">
            <section class="hero grid gap-12 py-20 md:grid-cols-2" id="hero">
                <div class="flex flex-col justify-center gap-6">
                    <span class="inline-flex w-fit items-center gap-2 rounded-full bg-emerald-50 px-4 py-1 text-sm font-semibold text-emerald-600">
                        Selamat Datang di Terminal Pintar
                    </span>
                    <h1 class="text-4xl font-bold leading-tight text-slate-900 md:text-5xl">
                        Halo, Selamat Datang di Terminal Pintar!
                    </h1>
                    <p class="text-lg leading-relaxed text-slate-600">
                        Mari bergabung bersama kami membantu Fullday School untuk anak-anak dampingan agar mereka bisa belajar dengan riang dan penuh harapan.
                    </p>
                    <div class="flex flex-wrap items-center gap-3">
                        <a href="#participate" class="btn-primary inline-flex items-center rounded-full bg-gradient-to-r from-emerald-500 to-emerald-400 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-200 transition hover:-translate-y-0.5 hover:shadow-xl">
                            Jelajahi Lebih Lanjut
                        </a>
                        <a href="#activities" class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-white px-6 py-3 text-sm font-semibold text-emerald-600 shadow-sm transition hover:border-emerald-300 hover:shadow-md">
                            Lihat Kegiatan Terbaru
                        </a>
                    </div>
                    <dl class="grid gap-4 pt-4 sm:grid-cols-2">
                        <div class="rounded-2xl border border-slate-200 bg-white px-5 py-4 shadow-sm">
                            <dt class="text-3xl font-bold text-emerald-500">250+</dt>
                            <dd class="text-sm text-slate-500">Anak binaan aktif belajar setiap minggunya</dd>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-white px-5 py-4 shadow-sm">
                            <dt class="text-3xl font-bold text-emerald-500">120</dt>
                            <dd class="text-sm text-slate-500">Relawan dan guru yang berkolaborasi</dd>
                        </div>
                    </dl>
                </div>
                <div class="relative">
                    <div class="absolute -left-10 top-10 hidden h-28 w-28 rounded-3xl bg-emerald-100 blur-2xl md:block"></div>
                    <div class="absolute -right-6 bottom-10 hidden h-32 w-32 rounded-full bg-emerald-200 blur-3xl md:block"></div>
                    <div class="relative overflow-hidden rounded-[32px] border border-emerald-100 bg-white shadow-2xl shadow-emerald-100">
                        <img src="https://images.unsplash.com/photo-1529390079861-591de354faf5?auto=format&fit=crop&w=1100&q=80" alt="Anak-anak belajar bersama" class="h-full w-full object-cover" />
                    </div>
                </div>
            </section>

            <section id="activities" class="py-16">
                <div class="text-center">
                    <h2 class="section-title text-3xl font-bold text-slate-900">Dokumentasi Kegiatan Terbaru Kami</h2>
                    <p class="section-subtitle text-slate-600">
                        Mengabadikan setiap proses belajar yang penuh semangat dan cerita kebaikan.
                    </p>
                </div>
                <div class="card-grid grid gap-6 md:grid-cols-3">
                    <article class="card flex flex-col overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-lg shadow-slate-200/40">
                        <img src="https://images.unsplash.com/photo-1544776193-352d25ca81b5?auto=format&fit=crop&w=800&q=80" alt="Workshop coding untuk anak" class="h-48 w-full object-cover" />
                        <div class="flex flex-1 flex-col gap-3 p-6">
                            <span class="tag inline-flex w-fit items-center gap-2 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-emerald-600">Workshop</span>
                            <h3 class="text-xl font-semibold text-slate-900">Workshop Coding untuk Anak</h3>
                            <p class="text-sm leading-relaxed text-slate-600">Belajar logika dan kreativitas melalui permainan sederhana yang menyenangkan.</p>
                            <div class="mt-auto flex items-center justify-between text-sm text-slate-500">
                                <span>Sabtu, 23 Maret 2024</span>
                                <a href="#" class="font-semibold text-emerald-600 hover:text-emerald-500">Baca Selengkapnya</a>
                            </div>
                        </div>
                    </article>

                    <article class="card flex flex-col overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-lg shadow-slate-200/40">
                        <img src="https://images.unsplash.com/photo-1519455953755-af066f52f1ea?auto=format&fit=crop&w=800&q=80" alt="Kelas bahasa interaktif" class="h-48 w-full object-cover" />
                        <div class="flex flex-1 flex-col gap-3 p-6">
                            <span class="tag inline-flex w-fit items-center gap-2 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-emerald-600">Kelas Bahasa</span>
                            <h3 class="text-xl font-semibold text-slate-900">Kelas Bahasa Interaktif</h3>
                            <p class="text-sm leading-relaxed text-slate-600">Memperkaya kosakata melalui permainan dan simulasi percakapan sehari-hari.</p>
                            <div class="mt-auto flex items-center justify-between text-sm text-slate-500">
                                <span>Minggu, 17 Maret 2024</span>
                                <a href="#" class="font-semibold text-emerald-600 hover:text-emerald-500">Baca Selengkapnya</a>
                            </div>
                        </div>
                    </article>

                    <article class="card flex flex-col overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-lg shadow-slate-200/40">
                        <img src="https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?auto=format&fit=crop&w=800&q=80" alt="Perpustakaan mini" class="h-48 w-full object-cover" />
                        <div class="flex flex-1 flex-col gap-3 p-6">
                            <span class="tag inline-flex w-fit items-center gap-2 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-emerald-600">Perpustakaan</span>
                            <h3 class="text-xl font-semibold text-slate-900">Perpustakaan Mini Kini Dibuka</h3>
                            <p class="text-sm leading-relaxed text-slate-600">Ruang baca nyaman untuk anak-anak mengeksplorasi cerita dan pengetahuan baru.</p>
                            <div class="mt-auto flex items-center justify-between text-sm text-slate-500">
                                <span>Kamis, 14 Maret 2024</span>
                                <a href="#" class="font-semibold text-emerald-600 hover:text-emerald-500">Baca Selengkapnya</a>
                            </div>
                        </div>
                    </article>
                </div>
                <div class="mt-10 flex justify-center">
                    <a href="#" class="inline-flex items-center gap-2 rounded-full bg-white px-6 py-3 text-sm font-semibold text-emerald-600 shadow-lg shadow-emerald-100 transition hover:-translate-y-0.5 hover:shadow-xl">
                        Lihat Semua Kegiatan
                    </a>
                </div>
            </section>

            <section id="about" class="rounded-[36px] bg-white px-8 py-16 shadow-2xl shadow-emerald-100">
                <div class="flex flex-col gap-16 lg:flex-row">
                    <div class="flex-1">
                        <span class="inline-flex items-center gap-2 rounded-full bg-indigo-50 px-4 py-1 text-sm font-semibold text-indigo-600">Tentang Kami</span>
                        <h2 class="mt-4 text-3xl font-bold text-slate-900">Terminal Pintar adalah rumah belajar yang tumbuh dari semangat kolaborasi</h2>
                        <p class="mt-4 text-base leading-relaxed text-slate-600">
                            Dimulai pada tanggal 17 Agustus 1950 bertepatan dengan hari kemerdekaan Republik Indonesia, Terminal Pintar menjadi ruang berdaya bagi anak-anak dari berbagai latar belakang. Kami percaya setiap anak berhak mendapatkan kesempatan belajar terbaik, didampingi oleh relawan dan guru yang berdedikasi.
                        </p>
                        <p class="mt-4 text-base leading-relaxed text-slate-600">
                            Melalui program pembelajaran kreatif, sesi pengembangan karakter, dan akses literasi di perpustakaan mini, kami terus menghadirkan pengalaman belajar yang bermakna. Dukungan dari orang tua, relawan, dan mitra komunitas menjadi kekuatan kami untuk terus bertumbuh.
                        </p>
                    </div>
                    <div class="flex-1 space-y-8">
                        <div class="rounded-3xl border border-slate-200 bg-slate-50 p-8">
                            <h3 class="text-2xl font-semibold text-slate-900">Sejarah</h3>
                            <p class="mt-3 text-base leading-relaxed text-slate-600">
                                Program belajar Terminal Pintar berawal dari kegiatan komunitas di terminal kota yang ingin menyediakan ruang aman bagi anak-anak. Kini kami telah memiliki pusat belajar dengan fasilitas lengkap dan dukungan ratusan relawan.
                            </p>
                        </div>
                        <div class="rounded-3xl border border-slate-200 bg-slate-50 p-8">
                            <h3 class="text-2xl font-semibold text-slate-900">Visi</h3>
                            <p class="mt-3 text-base leading-relaxed text-slate-600">
                                Membangun generasi yang cerdas, berkarakter, dan peduli melalui pengalaman belajar yang menyenangkan, kolaboratif, serta inklusif.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-16">
                    <h3 class="text-center text-2xl font-bold text-slate-900">Pengajar dan Relawan Unggulan Kami</h3>
                    <p class="mt-3 text-center text-slate-600">Mereka hadir sebagai sahabat belajar yang penuh empati dan profesional.</p>
                    <div class="team-grid mt-10 grid gap-6 md:grid-cols-3">
                        <div class="team-card">
                            <img src="https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=160&q=80" alt="Nabila Nurhaini" />
                            <h4 class="text-lg font-semibold text-slate-900">Nabila Nurhaini</h4>
                            <p class="text-sm text-emerald-600">Pengajar</p>
                        </div>
                        <div class="team-card">
                            <img src="https://images.unsplash.com/photo-1544723795-3fb6469f5b39?auto=format&fit=crop&w=160&q=80" alt="Andi Rahmadi" />
                            <h4 class="text-lg font-semibold text-slate-900">Andi Rahmadi</h4>
                            <p class="text-sm text-emerald-600">Pengajar</p>
                        </div>
                        <div class="team-card">
                            <img src="https://images.unsplash.com/photo-1544723795-432537e91e9f?auto=format&fit=crop&w=160&q=80" alt="Hanifah Ahmad" />
                            <h4 class="text-lg font-semibold text-slate-900">Hanifah Ahmad</h4>
                            <p class="text-sm text-emerald-600">Pengajar</p>
                        </div>
                    </div>
                </div>
            </section>

            <section id="participate" class="py-20">
                <div class="text-center">
                    <h2 class="text-3xl font-bold text-slate-900">Mari Berkontribusi Membangun Harapan!</h2>
                    <p class="mt-3 text-base text-slate-600">
                        Ada banyak cara untuk mendukung perjalanan belajar anak-anak Terminal Pintar. Pilih peran Anda hari ini.
                    </p>
                </div>
                <div class="cta-grid mt-12 grid gap-6 md:grid-cols-2">
                    <div class="cta-card">
                        <div class="flex items-center gap-4">
                            <div class="badge">Rp</div>
                            <div>
                                <h3 class="text-xl font-semibold text-slate-900">Donasi</h3>
                                <p class="text-sm text-slate-500">Amanah Anda akan membantu mereka yang membutuhkan.</p>
                            </div>
                        </div>
                        <p class="text-sm leading-relaxed text-slate-600">
                            Setiap donasi akan digunakan untuk mendukung kebutuhan operasional kelas, penyediaan alat tulis, makanan sehat, serta perawatan fasilitas belajar.
                        </p>
                        <a href="#" class="inline-flex w-fit items-center gap-2 rounded-full bg-emerald-500 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-200 transition hover:-translate-y-0.5 hover:bg-emerald-600">
                            Lihat Cara Berdonasi
                        </a>
                    </div>
                    <div class="cta-card">
                        <div class="flex items-center gap-4">
                            <div class="badge">❤</div>
                            <div>
                                <h3 class="text-xl font-semibold text-slate-900">Relawan</h3>
                                <p class="text-sm text-slate-500">Mari jadi relawan untuk berbagi ilmu dan inspirasi.</p>
                            </div>
                        </div>
                        <p class="text-sm leading-relaxed text-slate-600">
                            Bergabunglah bersama komunitas relawan Terminal Pintar. Anda bisa mengajar, mendampingi kegiatan, atau membantu operasional perpustakaan mini kami.
                        </p>
                        <a href="#" class="inline-flex w-fit items-center gap-2 rounded-full bg-white px-6 py-3 text-sm font-semibold text-emerald-600 shadow-lg shadow-emerald-100 transition hover:-translate-y-0.5 hover:shadow-xl">
                            Lihat Cara Mendaftar
                        </a>
                    </div>
                </div>
            </section>
        </main>

        <footer id="contact" class="bg-slate-900 py-12 text-slate-200">
            <div class="container mx-auto grid max-w-6xl gap-10 px-6 md:grid-cols-2 lg:grid-cols-3">
                <div>
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-emerald-500 text-white text-xl font-bold">TP</div>
                        <div>
                            <p class="text-lg font-semibold text-white">Terminal Pintar</p>
                            <p class="text-sm text-slate-400">Membangun masa depan cerah melalui pendidikan.</p>
                        </div>
                    </div>
                </div>
                <div>
                    <h4 class="text-sm font-semibold uppercase tracking-wide text-white">Kontak Kami</h4>
                    <ul class="mt-3 space-y-2 text-sm text-slate-300">
                        <li>📞 +6289123456789</li>
                        <li>✉️ terminalpintar@gmail.com</li>
                        <li>📍 Terminal Pintar Learning Hub</li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-sm font-semibold uppercase tracking-wide text-white">Media Sosial</h4>
                    <ul class="mt-3 space-y-2 text-sm text-slate-300">
                        <li>Instagram: @terminalpintar_id</li>
                        <li>Facebook: Terminal Pintar</li>
                        <li>Twitter: @terminalpintar</li>
                        <li>Youtube: Terminal Pintar TV</li>
                    </ul>
                </div>
            </div>
            <div class="mt-12 border-t border-slate-700 pt-6 text-center text-xs text-slate-500">
                © {{ date('Y') }} Terminal Pintar. Seluruh hak cipta dilindungi.
            </div>
        </footer>
    </body>
</html>
