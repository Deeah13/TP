<template>
  <div class="detail" id="top">
    <header v-if="article" class="detail__navigation" role="banner">
      <RouterLink class="detail__brand" :to="{ name: 'home' }" aria-label="Kembali ke beranda">
        <img :src="assets.logo" alt="Logo Terminal Pintar" />
        <div>
          <span class="detail__brand-name">Terminal Pintar</span>
          <span class="detail__brand-tag">Dokumentasi &amp; Berita</span>
        </div>
      </RouterLink>
      <nav class="detail__menu" aria-label="Navigasi utama">
        <RouterLink :to="{ name: 'home' }">Beranda</RouterLink>
        <a href="#article" @click.prevent="scrollTo('article')">Kegiatan</a>
        <RouterLink :to="{ name: 'news' }">Dokumentasi</RouterLink>
      </nav>
      <div class="detail__actions">
        <button type="button" class="detail__icon" aria-label="Notifikasi">
          <img :src="assets.bell" alt="" aria-hidden="true" />
        </button>
        <a class="detail__login" href="/login">Login</a>
      </div>
    </header>

    <main v-if="article" class="detail__content">
      <section class="detail__breadcrumb">
        <p class="detail__breadcrumb-label">{{ article.detail.breadcrumbLabel }}</p>
        <h1 class="detail__breadcrumb-title">{{ article.title }}</h1>
      </section>

      <figure class="detail__hero">
        <img :src="article.detail.heroImage" :alt="article.detail.heroAlt" />
      </figure>

      <article class="detail__article" id="article">
        <div class="detail__meta">
          <time :datetime="article.detail.isoDate">{{ article.date }}</time>
          <span aria-hidden="true">•</span>
          <span>{{ article.readTime }} menit baca</span>
        </div>
        <h2 class="detail__headline">{{ article.detail.highlightTitle }}</h2>
        <div class="detail__paragraphs">
          <p v-for="(paragraph, index) in article.detail.paragraphs" :key="index">
            {{ paragraph }}
          </p>
        </div>
        <figure class="detail__gallery">
          <img :src="article.detail.galleryImage" :alt="article.detail.galleryAlt" />
        </figure>
        <button class="detail__back-top" type="button" aria-label="Kembali ke atas" @click="scrollTo('top')">
          <img :src="assets.arrowUp" alt="" aria-hidden="true" />
        </button>
      </article>
    </main>

    <footer v-if="article" class="detail__footer" role="contentinfo">
      <div>
        <h3>Kontak Kami</h3>
        <a class="detail__footer-link" :href="`tel:${article.detail.contacts.phone}`">
          <img :src="assets.phone" alt="" aria-hidden="true" />
          <span>{{ article.detail.contacts.phone }}</span>
        </a>
        <a class="detail__footer-link" :href="`mailto:${article.detail.contacts.email}`">
          <img :src="assets.gmail" alt="" aria-hidden="true" />
          <span>{{ article.detail.contacts.email }}</span>
        </a>
      </div>
      <div>
        <h3>Media Sosial</h3>
        <ul class="detail__socials">
          <li v-for="social in article.detail.contacts.socials" :key="social.label">
            <a :href="social.href" target="_blank" rel="noopener noreferrer">
              {{ social.label }}
            </a>
          </li>
        </ul>
      </div>
      <button class="detail__footer-top" type="button" aria-label="Kembali ke atas" @click="scrollTo('top')">
        <img :src="assets.arrowUp" alt="" aria-hidden="true" />
      </button>
    </footer>

    <div v-else class="detail__empty">Konten dokumentasi tidak ditemukan.</div>
  </div>
</template>

<script setup>
import { computed, onMounted, watch } from 'vue';
import { RouterLink, useRoute, useRouter } from 'vue-router';
import { newsMap } from '../data/news';

const assets = {
  logo: 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=160&q=80',
  bell: 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCIgZmlsbD0ibm9uZSIgc3Ryb2tlPSJ3aGl0ZSIgc3Ryb2tlLXdpZHRoPSIxLjgiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCI+PHBhdGggZD0iTTYgOWE2IDYgMCAxMTEyIDBjMCA0IDEgNiAyIDdINGMxLTEgMi0zIDItNyIvPjxwYXRoIGQ9Ik0xMCAxOWEyIDIgMCAwMDQgMCIvPjwvc3ZnPg==',
  arrowUp: 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0OCIgaGVpZ2h0PSI0OCIgdmlld0JveD0iMCAwIDQ4IDQ4IiBmaWxsPSJub25lIj48Y2lyY2xlIGN4PSIyNCIgY3k9IjI0IiByPSIyNCIgZmlsbD0id2hpdGUiLz48cGF0aCBkPSJNMjQgMTZsLTggOGg2djEyaDRWMjRoNmwtOC04eiIgZmlsbD0iIzRDQUY1MCIvPjwvc3ZnPg==',
  phone: 'https://img.icons8.com/ios-filled/50/4CAF50/phone.png',
  gmail: 'https://img.icons8.com/color/48/gmail--v1.png',
};

const route = useRoute();
const router = useRouter();

const article = computed(() => newsMap[route.params.slug]);

const updateMetadata = (item) => {
  if (typeof document === 'undefined') {
    return;
  }

  document.title = item.detail.metaTitle;

  let description = document.querySelector('meta[name="description"]');
  if (!description) {
    description = document.createElement('meta');
    description.setAttribute('name', 'description');
    document.head.appendChild(description);
  }
  description.setAttribute('content', item.detail.metaDescription);
};

const ensureArticleOrRedirect = () => {
  const current = article.value;

  if (!current) {
    router.replace({ name: 'news' });
    return;
  }

  updateMetadata(current);

  if (typeof window !== 'undefined') {
    window.scrollTo({ top: 0, behavior: 'auto' });
  }
};

const scrollTo = (id) => {
  if (typeof document === 'undefined') {
    return;
  }

  const target = document.getElementById(id);

  if (target) {
    target.scrollIntoView({ behavior: 'smooth' });
  }
};

onMounted(ensureArticleOrRedirect);

watch(
  () => route.params.slug,
  () => {
    ensureArticleOrRedirect();
  }
);
</script>

<style scoped>
.detail {
  background: #f5f7fb;
  border: 8px solid #cac4d0;
  border-radius: 28px;
  color: #1f2b3d;
  font-family: 'Poppins', 'Plus Jakarta Sans', system-ui, sans-serif;
  min-height: 100vh;
  min-width: 1127px;
  overflow: hidden;
}

.detail__navigation {
  align-items: center;
  background: linear-gradient(90deg, rgba(120, 174, 78, 0.95), rgba(76, 175, 80, 0.95));
  display: grid;
  gap: 1.5rem;
  grid-template-columns: auto 1fr auto;
  padding: 1.25rem 2.75rem;
}

.detail__brand {
  align-items: center;
  display: inline-flex;
  gap: 0.9rem;
  text-decoration: none;
}

.detail__brand img {
  border-radius: 14px;
  box-shadow: 0 10px 18px rgba(33, 80, 32, 0.22);
  height: 52px;
  object-fit: cover;
  width: 52px;
}

.detail__brand-name {
  color: #ffffff;
  display: block;
  font-size: 1.35rem;
  font-weight: 700;
}

.detail__brand-tag {
  color: rgba(255, 255, 255, 0.8);
  font-size: 0.85rem;
  font-weight: 500;
}

.detail__menu {
  display: inline-flex;
  gap: 2rem;
  justify-content: center;
}

.detail__menu a {
  color: rgba(255, 255, 255, 0.85);
  font-weight: 600;
  position: relative;
  text-decoration: none;
  transition: color 0.2s ease;
}

.detail__menu a::after {
  background: rgba(255, 255, 255, 0.9);
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

.detail__menu a:hover {
  color: #ffffff;
}

.detail__menu a:hover::after,
.detail__menu .router-link-active::after {
  transform: scaleX(1);
}

.detail__actions {
  align-items: center;
  display: inline-flex;
  gap: 1rem;
}

.detail__icon {
  background: rgba(255, 255, 255, 0.25);
  border: none;
  border-radius: 999px;
  cursor: pointer;
  padding: 0.55rem;
  transition: background 0.2s ease, transform 0.2s ease;
}

.detail__icon img {
  height: 20px;
  width: 20px;
}

.detail__icon:hover {
  background: rgba(255, 255, 255, 0.35);
  transform: translateY(-1px);
}

.detail__login {
  background: #ffffff;
  border-radius: 999px;
  color: #4c8a13;
  font-weight: 600;
  padding: 0.6rem 1.9rem;
  text-decoration: none;
  transition: box-shadow 0.2s ease, transform 0.2s ease;
}

.detail__login:hover {
  box-shadow: 0 14px 24px rgba(255, 255, 255, 0.25);
  transform: translateY(-2px);
}

.detail__content {
  padding: 3.5rem 4rem 3rem;
}

.detail__breadcrumb {
  margin-bottom: 2rem;
  max-width: 720px;
}

.detail__breadcrumb-label {
  color: #4c8a13;
  font-size: 0.95rem;
  font-weight: 600;
  letter-spacing: 0.06em;
  text-transform: uppercase;
}

.detail__breadcrumb-title {
  color: #1f2b3d;
  font-size: 2.4rem;
  font-weight: 700;
  line-height: 1.3;
  margin: 0.6rem 0 0;
}

.detail__hero {
  border-radius: 26px;
  margin: 0;
  overflow: hidden;
}

.detail__hero img {
  display: block;
  height: 420px;
  object-fit: cover;
  width: 100%;
}

.detail__article {
  background: #ffffff;
  border-radius: 26px;
  box-shadow: 0 20px 40px rgba(31, 43, 61, 0.14);
  margin-top: -80px;
  padding: 3rem 3.5rem;
  position: relative;
}

.detail__meta {
  align-items: center;
  color: #64748b;
  display: inline-flex;
  gap: 0.75rem;
  font-size: 0.95rem;
}

.detail__headline {
  color: #1f2b3d;
  font-size: 2rem;
  font-weight: 700;
  line-height: 1.4;
  margin: 1.5rem 0 1.5rem;
}

.detail__paragraphs {
  display: flex;
  flex-direction: column;
  gap: 1.35rem;
  font-size: 1rem;
  line-height: 1.8;
  color: #4a5568;
}

.detail__gallery {
  border-radius: 22px;
  margin: 2.5rem 0 0;
  overflow: hidden;
}

.detail__gallery img {
  display: block;
  height: 320px;
  object-fit: cover;
  width: 100%;
}

.detail__back-top {
  align-items: center;
  background: #f1f5f9;
  border: none;
  border-radius: 999px;
  cursor: pointer;
  display: inline-flex;
  height: 52px;
  justify-content: center;
  margin-top: 2.5rem;
  padding: 0;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
  width: 52px;
}

.detail__back-top img {
  height: 22px;
  width: 22px;
}

.detail__back-top:hover {
  box-shadow: 0 12px 24px rgba(76, 138, 19, 0.18);
  transform: translateY(-2px);
}

.detail__footer {
  align-items: center;
  background: linear-gradient(180deg, #5a8d2c 0%, #3f6a14 100%);
  display: grid;
  gap: 2.5rem;
  grid-template-columns: repeat(2, minmax(0, 1fr)) auto;
  padding: 2.75rem 4rem;
}

.detail__footer h3 {
  color: #ffffff;
  font-size: 1.15rem;
  font-weight: 700;
  margin-bottom: 1rem;
}

.detail__footer-link {
  align-items: center;
  color: #f0fdf4;
  display: inline-flex;
  gap: 0.75rem;
  margin-bottom: 0.85rem;
  text-decoration: none;
}

.detail__footer-link img {
  height: 24px;
  width: 24px;
}

.detail__socials {
  color: #f0fdf4;
  display: grid;
  gap: 0.6rem;
  list-style: none;
  margin: 0;
  padding: 0;
}

.detail__socials a {
  color: inherit;
  text-decoration: none;
}

.detail__footer-top {
  background: rgba(255, 255, 255, 0.18);
  border: none;
  border-radius: 999px;
  cursor: pointer;
  height: 52px;
  padding: 0;
  transition: background 0.2s ease, transform 0.2s ease;
  width: 52px;
}

.detail__footer-top img {
  height: 22px;
  width: 22px;
}

.detail__footer-top:hover {
  background: rgba(255, 255, 255, 0.3);
  transform: translateY(-2px);
}

.detail__empty {
  align-items: center;
  display: flex;
  font-size: 1.05rem;
  justify-content: center;
  min-height: 320px;
}
</style>
