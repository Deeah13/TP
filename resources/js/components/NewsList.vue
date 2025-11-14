<template>
  <div class="news">
    <header class="news__nav">
      <div class="nav__brand">
        <span class="brand__logo">TP</span>
        <div class="brand__text">
          <strong>Terminal Pintar</strong>
          <span>Portal Dokumentasi &amp; Berita</span>
        </div>
      </div>
      <nav class="nav__links">
        <RouterLink :to="{ name: 'home' }">Beranda</RouterLink>
        <a href="#highlight">Sorotan</a>
        <a href="#arsip">Arsip</a>
        <a href="#kontak">Kontak</a>
      </nav>
      <RouterLink class="nav__cta" :to="{ name: 'home' }">Kembali ke Landing</RouterLink>
    </header>

    <main class="news__content">
      <section class="hero" id="highlight">
        <p class="hero__intro">
          Selamat Datang di Dokumentasi dan Berita, dapatkan informasi terkait Terminal Pintar di sini!
        </p>
        <h1>Daftar Dokumentasi dan Berita</h1>
        <p class="hero__lead">
          Rangkuman kegiatan, cerita pengajar, dan kabar terbaru kami sajikan untuk menjaga Anda tetap terhubung
          dengan perkembangan Terminal Pintar.
        </p>
      </section>

      <section class="news-list" id="arsip">
        <div class="news-list__grid">
          <article v-for="item in newsItems" :key="item.title" class="news-card">
            <figure class="news-card__media">
              <img :src="item.image" :alt="item.title" loading="lazy" />
              <figcaption>{{ item.category }}</figcaption>
            </figure>
            <div class="news-card__body">
              <div class="news-card__meta">
                <span>{{ item.date }}</span>
                <span>{{ item.readTime }} menit baca</span>
              </div>
              <h2>{{ item.title }}</h2>
              <p>{{ item.summary }}</p>
              <button type="button" class="news-card__link">Baca Selengkapnya</button>
            </div>
          </article>
        </div>
        <div class="news-list__pagination">
          <p>Menampilkan 1-7 dari 57 Berita</p>
          <nav aria-label="Navigasi halaman">
            <button type="button" class="pager pager--prev" aria-label="Sebelumnya">‹</button>
            <button type="button" class="pager pager--active" aria-current="page">1</button>
            <button type="button" class="pager" disabled>2</button>
            <button type="button" class="pager" disabled>3</button>
            <button type="button" class="pager pager--next" aria-label="Berikutnya">›</button>
          </nav>
        </div>
      </section>

      <section class="cta" id="kontak">
        <div class="cta__text">
          <h2>Mari Berkolaborasi untuk Perluasan Dampak</h2>
          <p>
            Cerita dan laporan ini hanya sebagian kecil dari ekosistem Terminal Pintar. Hubungi kami untuk
            berdiskusi, berbagi ide kegiatan, atau menjajaki kemitraan.
          </p>
        </div>
        <div class="cta__channels">
          <a class="channel" href="mailto:terminalpintar@gmail.com">
            <span class="channel__icon">📧</span>
            <span>terminalpintar@gmail.com</span>
          </a>
          <a class="channel" href="https://www.youtube.com/" target="_blank" rel="noopener">
            <span class="channel__icon">▶️</span>
            <span>Terminal Pintar Channel</span>
          </a>
        </div>
      </section>
    </main>
  </div>
</template>

<script setup>
import { RouterLink } from 'vue-router';

const newsItems = [
  {
    title: 'Workshop Coding untuk Anak',
    date: '15 Desember 2025',
    readTime: 4,
    category: 'Pelatihan',
    summary:
      'Anak-anak belajar dasar-dasar programming dengan pendekatan bermain yang dipandu relawan teknologi.',
    image: 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=1200&auto=format&fit=crop',
  },
  {
    title: 'Kelas Bahasa Interaktif',
    date: '12 Desember 2025',
    readTime: 3,
    category: 'Pembelajaran',
    summary: 'Sesi storytelling dan games untuk memperkuat kepercayaan diri anak-anak dalam berbahasa Inggris.',
    image: 'https://images.unsplash.com/photo-1509062522246-3755977927d7?q=80&w=1200&auto=format&fit=crop',
  },
  {
    title: 'Perpustakaan Mini Kini Dibuka',
    date: '31 Desember 2025',
    readTime: 5,
    category: 'Fasilitas',
    summary: 'Lebih dari 200 koleksi buku baru tersedia untuk mendukung budaya literasi di Terminal Pintar.',
    image: 'https://images.unsplash.com/photo-1516979187457-637abb4f9353?q=80&w=1200&auto=format&fit=crop',
  },
  {
    title: 'Sharing Session Orang Tua',
    date: '25 November 2025',
    readTime: 4,
    category: 'Komunitas',
    summary: 'Orang tua berbagi pengalaman mendampingi anak belajar dan berdiskusi strategi kolaboratif.',
    image: 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?q=80&w=1200&auto=format&fit=crop',
  },
  {
    title: 'Peluncuran Modul STEAM',
    date: '20 November 2025',
    readTime: 6,
    category: 'Program',
    summary: 'Modul baru STEAM dikenalkan untuk menumbuhkan daya pikir kritis dan kreatif peserta.',
    image: 'https://images.unsplash.com/photo-1509062522246-3755977927d7?q=80&w=1200&auto=format&fit=crop',
  },
  {
    title: 'Terminal Pintar di Festival Literasi',
    date: '05 November 2025',
    readTime: 3,
    category: 'Kegiatan',
    summary: 'Relawan Terminal Pintar memamerkan karya peserta dan mengajak publik mencoba kelas kilat.',
    image: 'https://images.unsplash.com/photo-1555421689-491a97ff2040?q=80&w=1200&auto=format&fit=crop',
  },
  {
    title: 'Pojok Konseling Diresmikan',
    date: '27 Oktober 2025',
    readTime: 4,
    category: 'Pendampingan',
    summary: 'Layanan konseling untuk anak dan orang tua kini hadir guna memperkuat dukungan psikososial.',
    image: 'https://images.unsplash.com/photo-1544776193-352d25ca81b1?q=80&w=1200&auto=format&fit=crop',
  },
];
</script>

<style scoped>
.news {
  background-color: #f2f2f7;
  border: 8px solid #cac4d0;
  min-height: 100vh;
  min-width: 1127px;
  overflow: hidden;
  font-family: 'Poppins', 'Plus Jakarta Sans', system-ui, sans-serif;
  color: #1f2933;
}

.news__nav {
  align-items: center;
  display: flex;
  justify-content: space-between;
  padding: 1.5rem 3rem;
  background: rgba(255, 255, 255, 0.85);
  border-bottom: 1px solid rgba(202, 196, 208, 0.5);
  position: sticky;
  top: 0;
  z-index: 10;
}

.nav__brand {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.brand__logo {
  align-items: center;
  background: linear-gradient(135deg, #78ae4e 0%, #4b8a13 100%);
  border-radius: 14px;
  color: #fff;
  display: inline-flex;
  font-weight: 700;
  height: 44px;
  justify-content: center;
  width: 44px;
}

.brand__text {
  display: flex;
  flex-direction: column;
  font-size: 0.85rem;
  line-height: 1.2;
}

.brand__text strong {
  font-size: 1.05rem;
}

.nav__links {
  display: flex;
  gap: 1.5rem;
  font-weight: 500;
}

.nav__links a {
  color: #3c4b5f;
  text-decoration: none;
  position: relative;
  transition: color 0.2s ease;
}

.nav__links a::after {
  content: '';
  position: absolute;
  left: 0;
  bottom: -0.35rem;
  width: 100%;
  height: 2px;
  background: linear-gradient(90deg, #78ae4e, #4b8a13);
  transform: scaleX(0);
  transform-origin: center;
  transition: transform 0.2s ease;
}

.nav__links a:hover {
  color: #4b8a13;
}

.nav__links a:hover::after,
.nav__links .router-link-active::after {
  transform: scaleX(1);
}

.nav__cta {
  background: #78ae4e;
  border-radius: 8px;
  color: #fff;
  font-weight: 600;
  padding: 0.75rem 1.5rem;
  text-decoration: none;
  transition: background 0.2s ease, transform 0.2s ease;
}

.nav__cta:hover {
  background: #4b8a13;
  transform: translateY(-2px);
}

.news__content {
  padding: 3rem 3rem 4rem;
}

.hero {
  text-align: center;
  margin-bottom: 3rem;
}

.hero__intro {
  font-family: 'Lato', system-ui, sans-serif;
  font-size: 0.75rem;
  text-transform: uppercase;
  letter-spacing: 0.12em;
  color: #3c4b5f;
  margin-bottom: 1rem;
}

.hero h1 {
  color: #78ae4e;
  font-size: 2.5rem;
  margin-bottom: 1rem;
}

.hero__lead {
  max-width: 720px;
  margin: 0 auto;
  color: #4d596a;
  line-height: 1.6;
}

.news-list__grid {
  display: grid;
  gap: 2rem;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
}

.news-card {
  background: #fff;
  border-radius: 24px;
  border: 2px solid #e6e6e6;
  box-shadow: 0 10px 30px rgba(44, 51, 73, 0.08);
  display: flex;
  flex-direction: column;
  overflow: hidden;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.news-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 16px 40px rgba(44, 51, 73, 0.12);
}

.news-card__media {
  position: relative;
  height: 180px;
  overflow: hidden;
}

.news-card__media img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.news-card__media figcaption {
  position: absolute;
  left: 1.25rem;
  bottom: 1.25rem;
  background: rgba(120, 174, 78, 0.9);
  color: #fff;
  padding: 0.35rem 0.75rem;
  border-radius: 999px;
  font-size: 0.75rem;
}

.news-card__body {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
  padding: 1.75rem;
}

.news-card__meta {
  display: flex;
  justify-content: space-between;
  font-size: 0.75rem;
  color: #6b7280;
}

.news-card__body h2 {
  font-size: 1.15rem;
  color: #1f2933;
  margin: 0;
}

.news-card__body p {
  font-size: 0.95rem;
  line-height: 1.6;
  color: #4d596a;
}

.news-card__link {
  align-self: flex-start;
  background: #fff;
  border: 1px solid #78ae4e;
  border-radius: 6px;
  color: #78ae4e;
  font-size: 0.85rem;
  font-weight: 500;
  padding: 0.5rem 1.25rem;
  cursor: pointer;
  transition: background 0.2s ease, color 0.2s ease;
}

.news-card__link:hover {
  background: #78ae4e;
  color: #fff;
}

.news-list__pagination {
  align-items: center;
  display: flex;
  flex-wrap: wrap;
  gap: 1rem;
  justify-content: space-between;
  margin-top: 3rem;
  color: #4d596a;
}

.news-list__pagination nav {
  display: inline-flex;
  gap: 0.5rem;
}

.pager {
  align-items: center;
  background: #fff;
  border: 1px solid #d1d5db;
  border-radius: 8px;
  color: #4d596a;
  cursor: pointer;
  display: inline-flex;
  font-weight: 600;
  height: 36px;
  justify-content: center;
  padding: 0 0.9rem;
  transition: background 0.2s ease, color 0.2s ease;
}

.pager--active {
  background: #78ae4e;
  border-color: #78ae4e;
  color: #fff;
}

.pager[disabled] {
  cursor: not-allowed;
  opacity: 0.5;
}

.pager--prev,
.pager--next {
  font-size: 1.2rem;
}

.cta {
  background: linear-gradient(135deg, rgba(120, 174, 78, 0.12), rgba(120, 174, 78, 0.35));
  border-radius: 32px;
  display: flex;
  flex-wrap: wrap;
  gap: 2rem;
  margin-top: 4rem;
  padding: 2.5rem 3rem;
  align-items: center;
}

.cta__text {
  flex: 1 1 320px;
}

.cta__text h2 {
  color: #2f4f1f;
  font-size: 1.8rem;
  margin-bottom: 1rem;
}

.cta__text p {
  color: #405163;
  line-height: 1.7;
}

.cta__channels {
  display: flex;
  flex-direction: column;
  gap: 1rem;
  flex: 1 1 240px;
}

.channel {
  align-items: center;
  background: #fff;
  border-radius: 16px;
  box-shadow: 0 10px 24px rgba(47, 79, 31, 0.08);
  color: #2f4f1f;
  display: flex;
  gap: 0.75rem;
  padding: 1rem 1.25rem;
  text-decoration: none;
  font-weight: 600;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.channel:hover {
  transform: translateY(-4px);
  box-shadow: 0 16px 30px rgba(47, 79, 31, 0.12);
}

.channel__icon {
  font-size: 1.5rem;
}

@media (max-width: 1024px) {
  .news {
    min-width: 0;
  }

  .news__nav,
  .news__content {
    padding: 1.5rem;
  }

  .nav__links {
    display: none;
  }
}

@media (max-width: 640px) {
  .hero h1 {
    font-size: 2rem;
  }

  .news-list__grid {
    grid-template-columns: 1fr;
  }

  .news-list__pagination {
    flex-direction: column;
    align-items: flex-start;
  }
}
</style>
