<template>
  <div class="LIST-BERITA" id="top">
    <header class="navigation" role="banner">
      <RouterLink class="logo-link" :to="{ name: 'home' }" aria-label="Kembali ke beranda">
        <img class="logo-terminal-pintar-2" :src="assets.brandMark" alt="Logo Terminal Pintar" />
      </RouterLink>
      <h1 class="text-wrapper-13">Terminal Pintar</h1>
      <nav aria-label="Navigasi utama">
        <RouterLink :to="{ name: 'home' }" class="text-wrapper-16">Beranda</RouterLink>
        <a href="#arsip" class="text-wrapper-15" @click.prevent="scrollTo('arsip')">Kegiatan</a>
      </nav>
      <a class="div-wrapper" href="/login">
        <span class="text-wrapper-14">Login</span>
      </a>
    </header>

    <img class="logo-terminal-pintar" :src="assets.badge" alt="Terminal Pintar" />

    <main>
      <h2 class="text-wrapper">Daftar Dokumentasi dan Berita</h2>
      <p class="welkam">
        Selamat Datang di Dokumentasi dan Berita, dapatkan informasi terkait Terminal Pintar di sini!
      </p>

      <section class="news-section" aria-label="Daftar berita" id="arsip">
        <article v-for="(item, index) in items" :key="item.slug" :class="['news-card', cardClasses[index] ?? '']">
          <img class="news-card__image" :src="item.image" :alt="item.title" loading="lazy" />
          <h3 class="news-card__title">{{ item.title }}</h3>
          <time class="news-card__date" :datetime="item.detail.isoDate">{{ item.date }}</time>
          <span class="news-card__relative">{{ item.relativeTime }}</span>
          <RouterLink class="cart-button" :to="{ name: 'news-detail', params: { slug: item.slug } }">
            <span class="text-wrapper-4">Baca Selengkapnya</span>
          </RouterLink>
        </article>
      </section>

      <footer class="pagination-footer">
        <p class="text-wrapper-11">Menampilkan 1-7 dari 57 Berita</p>
        <nav class="group" aria-label="Navigasi halaman">
          <button type="button" class="pagination-prev" aria-label="Halaman sebelumnya">
            <span aria-hidden="true">‹</span>
          </button>
          <span class="rectangle" aria-current="page">
            <span class="text-wrapper-12">1</span>
          </span>
          <button type="button" class="pagination-next" aria-label="Halaman berikutnya">
            <span aria-hidden="true">›</span>
          </button>
        </nav>
      </footer>
    </main>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { RouterLink } from 'vue-router';
import { newsItems } from '../data/news';

const assets = {
  brandMark: 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=160&q=80',
  badge: 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=200&auto=format&fit=crop',
};

const cardClasses = [
  'news-card--primary',
  'news-card--secondary',
  'news-card--tertiary',
  'news-card--accent',
  'news-card--mint',
  'news-card--sky',
  'news-card--warm',
];

const items = computed(() => newsItems.slice(0, 7));

const scrollTo = (id) => {
  if (typeof document === 'undefined') {
    return;
  }

  const target = document.getElementById(id);

  if (target) {
    target.scrollIntoView({ behavior: 'smooth' });
  }
};
</script>

<style scoped>
.LIST-BERITA {
  background-color: #f2f2f7;
  border: 8px solid #cac4d0;
  border-radius: 24px;
  color: #1f2933;
  font-family: 'Poppins', 'Plus Jakarta Sans', system-ui, sans-serif;
  min-height: 1019px;
  min-width: 1127px;
  overflow: hidden;
  position: relative;
}

.navigation {
  align-items: center;
  background: rgba(255, 255, 255, 0.85);
  border-bottom: 1px solid rgba(202, 196, 208, 0.6);
  display: grid;
  gap: 1rem;
  grid-template-columns: auto auto 1fr auto;
  padding: 1.25rem 2.5rem;
}

.logo-link {
  display: inline-flex;
  align-items: center;
}

.logo-terminal-pintar-2 {
  border-radius: 12px;
  height: 44px;
  object-fit: cover;
  width: 44px;
}

.text-wrapper-13 {
  color: #2a2a2a;
  font-size: 1.5rem;
  font-weight: 700;
  margin: 0;
}

.navigation nav {
  align-items: center;
  display: inline-flex;
  gap: 1.5rem;
  justify-content: flex-end;
}

.navigation a {
  color: #4b5c72;
  font-size: 0.95rem;
  font-weight: 600;
  text-decoration: none;
  position: relative;
  transition: color 0.2s ease;
}

.navigation a::after {
  background: linear-gradient(90deg, #78ae4e, #4b8a13);
  border-radius: 999px;
  bottom: -6px;
  content: '';
  height: 3px;
  left: 0;
  position: absolute;
  transform: scaleX(0);
  transform-origin: center;
  transition: transform 0.2s ease;
  width: 100%;
}

.navigation a:hover {
  color: #4b8a13;
}

.navigation a:hover::after,
.navigation .router-link-active::after {
  transform: scaleX(1);
}

.div-wrapper {
  background: linear-gradient(135deg, #78ae4e 0%, #4b8a13 100%);
  border-radius: 999px;
  color: #fff;
  display: inline-flex;
  padding: 0.6rem 1.75rem;
  text-decoration: none;
  transition: box-shadow 0.2s ease, transform 0.2s ease;
}

.div-wrapper:hover {
  box-shadow: 0 12px 24px rgba(72, 137, 33, 0.3);
  transform: translateY(-2px);
}

.text-wrapper-14 {
  font-size: 0.95rem;
  font-weight: 600;
}

.logo-terminal-pintar {
  border-radius: 16px;
  box-shadow: 0 10px 25px rgba(47, 79, 31, 0.15);
  height: 56px;
  left: 2rem;
  object-fit: cover;
  position: absolute;
  top: 0.85rem;
  width: 56px;
  z-index: 0;
}

main {
  padding: 3rem 2.5rem 2.25rem;
  position: relative;
  z-index: 1;
}

.text-wrapper {
  color: #78ae4e;
  font-size: 2rem;
  font-weight: 700;
  margin-bottom: 0.75rem;
}

.welkam {
  color: #4d596a;
  font-family: 'Lato', system-ui, sans-serif;
  font-size: 0.95rem;
  line-height: 1.6;
  margin-bottom: 2.5rem;
  max-width: 680px;
}

.news-section {
  display: grid;
  gap: 1.75rem;
  grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
}

.news-card {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 24px;
  box-shadow: 0 12px 30px rgba(44, 51, 73, 0.08);
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
  padding: 1.75rem 1.5rem;
  position: relative;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.news-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 18px 40px rgba(44, 51, 73, 0.12);
}

.news-card::before {
  content: '';
  height: 4px;
  left: 1.5rem;
  position: absolute;
  top: 1.5rem;
  width: 54px;
  border-radius: 999px;
  background: #78ae4e;
}

.news-card__image {
  border-radius: 18px;
  height: 160px;
  object-fit: cover;
  width: 100%;
}

.news-card__title {
  font-size: 1.05rem;
  font-weight: 600;
  margin: 0;
  padding-top: 0.35rem;
}

.news-card__date {
  color: #6d6d6d;
  font-size: 0.85rem;
  font-weight: 500;
}

.news-card__relative {
  color: #8f9bad;
  font-size: 0.8rem;
}

.cart-button {
  align-items: center;
  background: #ffffff;
  border: 1px solid #76b340;
  border-radius: 6px;
  color: #76b340;
  display: inline-flex;
  font-size: 0.85rem;
  font-weight: 600;
  justify-content: center;
  margin-top: auto;
  padding: 0.55rem 1.25rem;
  text-decoration: none;
  transition: background 0.2s ease, color 0.2s ease;
}

.cart-button:hover {
  background: #76b340;
  color: #ffffff;
}

.text-wrapper-4 {
  white-space: nowrap;
}

.news-card--primary::before {
  background: linear-gradient(90deg, #78ae4e, #4b8a13);
}

.news-card--secondary::before {
  background: linear-gradient(90deg, #5e72eb, #4b8a13);
}

.news-card--tertiary::before {
  background: linear-gradient(90deg, #f97316, #facc15);
}

.news-card--accent::before {
  background: linear-gradient(90deg, #f43f5e, #f97316);
}

.news-card--mint::before {
  background: linear-gradient(90deg, #34d399, #10b981);
}

.news-card--sky::before {
  background: linear-gradient(90deg, #38bdf8, #2563eb);
}

.news-card--warm::before {
  background: linear-gradient(90deg, #fb7185, #facc15);
}

.pagination-footer {
  align-items: center;
  color: #4d596a;
  display: flex;
  flex-wrap: wrap;
  gap: 1rem;
  justify-content: space-between;
  margin-top: 3rem;
}

.text-wrapper-11 {
  font-size: 0.9rem;
  margin: 0;
}

.group {
  align-items: center;
  display: inline-flex;
  gap: 0.5rem;
}

.pagination-prev,
.pagination-next {
  align-items: center;
  background: #ffffff;
  border: 1px solid #d1d5db;
  border-radius: 8px;
  color: #4d596a;
  cursor: pointer;
  display: inline-flex;
  font-size: 1.1rem;
  height: 36px;
  justify-content: center;
  width: 36px;
}

.rectangle {
  align-items: center;
  background: #78ae4e;
  border-radius: 8px;
  color: #ffffff;
  display: inline-flex;
  font-weight: 600;
  height: 36px;
  justify-content: center;
  width: 36px;
}

.text-wrapper-12 {
  font-size: 0.95rem;
}

@media (max-width: 1024px) {
  .LIST-BERITA {
    min-width: 0;
  }

  .navigation {
    grid-template-columns: auto 1fr auto;
  }

  .navigation nav {
    display: none;
  }

  main {
    padding: 2rem 1.5rem 2.5rem;
  }

  .news-section {
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  }
}

@media (max-width: 640px) {
  .text-wrapper {
    font-size: 1.6rem;
  }

  .welkam {
    font-size: 0.9rem;
  }

  .pagination-footer {
    flex-direction: column;
    align-items: flex-start;
  }
}
</style>
