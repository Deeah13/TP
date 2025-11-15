<template>
  <div class="news" id="top">
    <header :class="['news__navigation', { 'news__navigation--glass': isScrolled }]" role="banner">
      <RouterLink class="news__brand" :to="{ name: 'home' }" aria-label="Kembali ke beranda">
        <img :src="assets.brandMark" alt="Logo Terminal Pintar" />
        <span>Terminal Pintar</span>
      </RouterLink>
      <nav class="news__menu" aria-label="Navigasi utama">
        <RouterLink :to="{ name: 'home' }">Beranda</RouterLink>
        <a href="#arsip" @click.prevent="scrollTo('arsip')">Kegiatan</a>
      </nav>
      <RouterLink class="news__login" :to="{ name: 'login' }">Login</RouterLink>
    </header>

    <main class="news__content">
      <section class="news__intro">
        <h1>Daftar Dokumentasi dan Berita</h1>
        <p>Selamat datang! Temukan kabar terbaru Terminal Pintar dan dokumentasi kegiatan unggulan di sini.</p>
      </section>

      <section class="news__list" aria-label="Daftar berita" id="arsip">
        <article v-for="item in items" :key="item.slug" class="news-card">
          <img class="news-card__image" :src="item.image" :alt="item.title" loading="lazy" />
          <div class="news-card__body">
            <div class="news-card__top">
              <div class="news-card__info">
                <span class="news-card__category">{{ item.category }}</span>
                <h2>{{ item.title }}</h2>
              </div>
              <RouterLink class="news-card__link" :to="{ name: 'news-detail', params: { slug: item.slug } }">
                Baca Selengkapnya
                <img :src="assets.arrowRight" alt="" aria-hidden="true" />
              </RouterLink>
            </div>
            <div class="news-card__meta">
              <time :datetime="item.detail.isoDate">{{ item.date }}</time>
              <span class="news-card__divider" aria-hidden="true"></span>
              <span>{{ item.relativeTime }}</span>
              <span class="news-card__divider" aria-hidden="true"></span>
              <span>{{ item.readTime }} menit baca</span>
            </div>
            <p class="news-card__summary">{{ item.summary }}</p>
          </div>
        </article>
      </section>

      <footer class="news__pagination" aria-label="Navigasi halaman">
        <p>Menampilkan 1-7 dari 57 Berita</p>
        <div class="news__pagination-controls">
          <button type="button" class="news__page-btn" aria-label="Halaman sebelumnya">
            <img :src="assets.chevronLeft" alt="" aria-hidden="true" />
          </button>
          <span class="news__page-current" aria-current="page">1</span>
          <button type="button" class="news__page-btn" aria-label="Halaman berikutnya">
            <img :src="assets.chevronRight" alt="" aria-hidden="true" />
          </button>
        </div>
      </footer>
    </main>
  </div>
</template>

<script setup>
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';
import { RouterLink } from 'vue-router';
import { newsItems } from '../data/news';

const assets = {
  brandMark: 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=160&q=80',
  arrowRight: 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNiIgaGVpZ2h0PSIxNiIgdmlld0JveD0iMCAwIDE2IDE2IiBmaWxsPSJub25lIj48cGF0aCBkPSJNNiA0bDQgNC00IDQiIHN0cm9rZT0iIzRDOEExMyIgc3Ryb2tlLXdpZHRoPSIxLjYiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCIvPjwvc3ZnPg==',
  chevronLeft: 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyMCIgaGVpZ2h0PSIyMCIgdmlld0JveD0iMCAwIDIwIDIwIiBmaWxsPSJub25lIj48cGF0aCBkPSJNMTIuNSA1bC01IDUgNSA1IiBzdHJva2U9IiM0QjVDNzIiIHN0cm9rZS13aWR0aD0iMS44IiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiLz48L3N2Zz4=',
  chevronRight: 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyMCIgaGVpZ2h0PSIyMCIgdmlld0JveD0iMCAwIDIwIDIwIiBmaWxsPSJub25lIj48cGF0aCBkPSJNNy41IDVsNSA1LTUgNSIgc3Ryb2tlPSIjNEI1QzcyIiBzdHJva2Utd2lkdGg9IjEuOCIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIi8+PC9zdmc+',
};

const items = computed(() => newsItems.slice(0, 7));

const isScrolled = ref(false);

const evaluateScrollState = () => {
  if (typeof window === 'undefined') {
    return;
  }

  isScrolled.value = window.scrollY > 24;
};

onMounted(() => {
  evaluateScrollState();

  if (typeof window !== 'undefined') {
    window.addEventListener('scroll', evaluateScrollState, { passive: true });
  }
});

onBeforeUnmount(() => {
  if (typeof window !== 'undefined') {
    window.removeEventListener('scroll', evaluateScrollState);
  }
});

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
.news {
  background: #f2f5fb;
  border: 8px solid #cac4d0;
  border-radius: 28px;
  color: #1f2b3d;
  font-family: 'Poppins', 'Plus Jakarta Sans', system-ui, sans-serif;
  min-height: 100vh;
  min-width: 1127px;
  overflow: hidden;
}

.news__navigation {
  align-items: center;
  background: rgba(255, 255, 255, 0.98);
  border-bottom: 1px solid rgba(202, 196, 208, 0.45);
  box-shadow: 0 10px 24px rgba(25, 39, 58, 0.08);
  display: grid;
  gap: 1.5rem;
  grid-template-columns: auto 1fr auto;
  padding: 1.2rem 2.75rem;
  position: sticky;
  top: 0;
  transition: background 0.35s ease, box-shadow 0.35s ease, backdrop-filter 0.35s ease, border-color 0.35s ease;
  z-index: 24;
}

.news__navigation--glass {
  background: rgba(255, 255, 255, 0.72);
  border-bottom-color: rgba(255, 255, 255, 0.5);
  box-shadow: 0 22px 46px rgba(21, 35, 54, 0.18);
  backdrop-filter: blur(18px) saturate(150%);
  -webkit-backdrop-filter: blur(18px) saturate(150%);
}

.news__brand {
  align-items: center;
  display: inline-flex;
  gap: 0.75rem;
  text-decoration: none;
}

.news__brand img {
  border-radius: 14px;
  box-shadow: 0 10px 20px rgba(31, 43, 61, 0.15);
  height: 48px;
  object-fit: cover;
  width: 48px;
}

.news__brand span {
  color: #2f3a4b;
  font-size: 1.35rem;
  font-weight: 700;
}

.news__menu {
  display: inline-flex;
  gap: 1.75rem;
  justify-content: flex-end;
}

.news__menu a {
  color: #4b5c72;
  font-weight: 600;
  position: relative;
  text-decoration: none;
  transition: color 0.2s ease;
}

.news__menu a::after {
  background: linear-gradient(90deg, #78ae4e, #4c8a13);
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

.news__menu a:hover {
  color: #4c8a13;
}

.news__menu a:hover::after,
.news__menu .router-link-active::after {
  transform: scaleX(1);
}

.news__login {
  background: linear-gradient(135deg, #78ae4e, #4c8a13);
  border-radius: 999px;
  color: #ffffff;
  font-weight: 600;
  padding: 0.6rem 1.8rem;
  text-decoration: none;
  transition: box-shadow 0.2s ease, transform 0.2s ease;
}

.news__login:hover {
  box-shadow: 0 14px 24px rgba(72, 137, 33, 0.28);
  transform: translateY(-2px);
}

.news__content {
  padding: 3rem 3.5rem 3.5rem;
}

.news__intro {
  margin-bottom: 2.5rem;
  max-width: 640px;
}

.news__intro h1 {
  color: #1f2b3d;
  font-size: 2.25rem;
  font-weight: 700;
  margin-bottom: 0.75rem;
}

.news__intro p {
  color: #64748b;
  font-size: 1rem;
  line-height: 1.7;
  margin: 0;
}

.news__list {
  display: flex;
  flex-direction: column;
  gap: 1.75rem;
}

.news-card {
  align-items: center;
  background: rgba(255, 255, 255, 0.8);
  border: 1px solid rgba(255, 255, 255, 0.5);
  border-radius: 24px;
  box-shadow: 0 24px 46px rgba(31, 43, 61, 0.14);
  display: grid;
  gap: 1.5rem;
  grid-template-columns: 220px 1fr;
  overflow: hidden;
  padding: 1.6rem 2.2rem;
  position: relative;
  transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
  backdrop-filter: blur(20px) saturate(140%);
  -webkit-backdrop-filter: blur(20px) saturate(140%);
}

.news-card::before {
  background: linear-gradient(135deg, rgba(120, 174, 78, 0.08), rgba(76, 138, 19, 0.14));
  content: '';
  inset: 0;
  opacity: 0;
  position: absolute;
  transition: opacity 0.25s ease;
  z-index: 0;
}

.news-card > * {
  position: relative;
  z-index: 1;
}

.news-card:hover {
  border-color: rgba(120, 174, 78, 0.35);
  box-shadow: 0 32px 62px rgba(31, 43, 61, 0.22);
  transform: translateY(-8px);
}

.news-card:hover::before {
  opacity: 1;
}

.news-card__image {
  border-radius: 18px;
  height: 160px;
  object-fit: cover;
  width: 100%;
}

.news-card__body {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.news-card__top {
  align-items: flex-start;
  display: flex;
  justify-content: space-between;
  gap: 1rem;
}

.news-card__info h2 {
  color: #1f2b3d;
  font-size: 1.35rem;
  font-weight: 700;
  line-height: 1.4;
  margin: 0.35rem 0 0;
}

.news-card__category {
  background: rgba(120, 174, 78, 0.12);
  border-radius: 999px;
  color: #4c8a13;
  font-size: 0.8rem;
  font-weight: 600;
  letter-spacing: 0.08em;
  padding: 0.35rem 0.8rem;
  text-transform: uppercase;
}

.news-card__link {
  align-items: center;
  color: #4c8a13;
  display: inline-flex;
  font-weight: 600;
  gap: 0.4rem;
  text-decoration: none;
}

.news-card__link img {
  height: 16px;
  width: 16px;
}

.news-card__meta {
  align-items: center;
  color: #64748b;
  display: inline-flex;
  flex-wrap: wrap;
  font-size: 0.92rem;
  gap: 0.6rem;
}

.news-card__divider {
  background: rgba(100, 116, 139, 0.4);
  border-radius: 999px;
  height: 6px;
  width: 6px;
}

.news-card__summary {
  color: #4a5568;
  font-size: 0.98rem;
  line-height: 1.7;
  margin: 0;
}

.news__pagination {
  align-items: center;
  display: flex;
  justify-content: space-between;
  margin-top: 2.5rem;
}

.news__pagination p {
  color: #64748b;
  margin: 0;
}

.news__pagination-controls {
  align-items: center;
  display: inline-flex;
  gap: 0.75rem;
}

.news__page-btn {
  align-items: center;
  background: #ffffff;
  border: 1px solid rgba(148, 163, 184, 0.4);
  border-radius: 12px;
  cursor: pointer;
  display: inline-flex;
  height: 40px;
  justify-content: center;
  transition: border-color 0.2s ease, transform 0.2s ease;
  width: 40px;
}

.news__page-btn:hover {
  border-color: #4c8a13;
  transform: translateY(-1px);
}

.news__page-btn img {
  height: 18px;
  width: 18px;
}

.news__page-current {
  align-items: center;
  background: #78ae4e;
  border-radius: 12px;
  color: #ffffff;
  display: inline-flex;
  font-weight: 600;
  height: 40px;
  justify-content: center;
  padding: 0 1.25rem;
}
</style>
