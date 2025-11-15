<template>
  <div class="detail" id="top">
    <header
      v-if="article"
      :class="['detail__navigation', { 'detail__navigation--glass': isScrolled }]"
      role="banner"
    >
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
              <img :src="socialIcon(social.label)" :alt="`Ikon ${social.label}`" aria-hidden="true" />
              <span>{{ social.label }}</span>
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
import { computed, onBeforeUnmount, onMounted, ref, watch } from 'vue';
import { RouterLink, useRoute, useRouter } from 'vue-router';
import { newsMap } from '../data/news';

const assets = {
  logo: 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=160&q=80',
  bell: 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgc3Ryb2tlPSIjNGM4YTEzIiBzdHJva2Utd2lkdGg9IjEuNSIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIj48cGF0aCBkPSJNMTIgMjBhMiAyIDAgMSAwIDQtMiI+PC9wYXRoPjxwYXRoIGQ9Ik0zLjg2IDE3SDIwLjEzYTEgMSAwIDAwLjg3LTEuNTRBMTEuMDMgMTEuMDMgMCAwMDIwIDExYzAtNS41My00LjQ3LTEwLTEwLTEwUzkgNS40NyA5IDExYTExLjAzIDExLjAzIDAgMDAtNS4wMSA0LjQ2IDEgMSAwIDAwLjg3IDEuNTR6Ij48L3BhdGg+PC9zdmc+',
  arrowUp: 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0OCIgaGVpZ2h0PSI0OCIgdmlld0JveD0iMCAwIDQ4IDQ4IiBmaWxsPSJub25lIj48Y2lyY2xlIGN4PSIyNCIgY3k9IjI0IiByPSIyNCIgZmlsbD0id2hpdGUiLz48cGF0aCBkPSJNMjQgMTZsLTggOGg2djEyaDRWMjRoNmwtOC04eiIgZmlsbD0iIzRDQUY1MCIvPjwvc3ZnPg==',
  phone: 'https://img.icons8.com/ios-filled/50/4CAF50/phone.png',
  gmail: 'https://img.icons8.com/color/48/gmail--v1.png',
  instagram: 'https://img.icons8.com/fluency/48/instagram-new.png',
  x: 'https://img.icons8.com/ios-filled/50/FFFFFF/twitterx--v1.png',
  youtube: 'https://img.icons8.com/color/48/youtube-play.png',
};

const route = useRoute();
const router = useRouter();

const socialIcon = (label) => {
  const normalized = label.toLowerCase();

  if (normalized.includes('instagram')) {
    return assets.instagram;
  }

  if (normalized.includes('youtube') || normalized.includes('terminal pintar')) {
    return assets.youtube;
  }

  return assets.x;
};

const article = computed(() => newsMap[route.params.slug]);

const isScrolled = ref(false);

const evaluateScrollState = () => {
  if (typeof window === 'undefined') {
    return;
  }

  isScrolled.value = window.scrollY > 24;
};

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

onMounted(() => {
  ensureArticleOrRedirect();
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
  background: rgba(255, 255, 255, 0.98);
  border-bottom: 1px solid rgba(202, 196, 208, 0.45);
  box-shadow: 0 12px 26px rgba(25, 39, 58, 0.08);
  display: grid;
  gap: 1.5rem;
  grid-template-columns: auto 1fr auto;
  padding: 1.2rem 2.75rem;
  position: sticky;
  top: 0;
  transition: background 0.35s ease, box-shadow 0.35s ease, backdrop-filter 0.35s ease, border-color 0.35s ease;
  z-index: 24;
}

.detail__navigation--glass {
  background: rgba(255, 255, 255, 0.72);
  border-bottom-color: rgba(255, 255, 255, 0.5);
  box-shadow: 0 22px 48px rgba(21, 35, 54, 0.18);
  backdrop-filter: blur(18px) saturate(150%);
  -webkit-backdrop-filter: blur(18px) saturate(150%);
}

.detail__brand {
  align-items: center;
  display: inline-flex;
  gap: 0.9rem;
  text-decoration: none;
}

.detail__brand img {
  border-radius: 14px;
  box-shadow: 0 12px 22px rgba(31, 43, 61, 0.18);
  height: 52px;
  object-fit: cover;
  width: 52px;
}

.detail__brand-name {
  color: #233143;
  display: block;
  font-size: 1.35rem;
  font-weight: 700;
}

.detail__brand-tag {
  color: #4b5c72;
  font-size: 0.85rem;
  font-weight: 500;
}

.detail__menu {
  display: inline-flex;
  gap: 2rem;
  justify-content: center;
}

.detail__menu a {
  color: #4b5c72;
  font-weight: 600;
  position: relative;
  text-decoration: none;
  transition: color 0.2s ease;
}

.detail__menu a::after {
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

.detail__menu a:hover {
  color: #4c8a13;
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
  background: rgba(120, 174, 78, 0.12);
  border: none;
  border-radius: 12px;
  cursor: pointer;
  padding: 0.6rem;
  transition: background 0.2s ease, transform 0.2s ease;
}

.detail__icon img {
  height: 22px;
  width: 22px;
}

.detail__icon:hover {
  background: rgba(120, 174, 78, 0.22);
  transform: translateY(-1px);
}

.detail__login {
  background: linear-gradient(135deg, #78ae4e, #4c8a13);
  border-radius: 999px;
  color: #ffffff;
  font-weight: 600;
  padding: 0.6rem 1.9rem;
  text-decoration: none;
  transition: box-shadow 0.2s ease, transform 0.2s ease;
}

.detail__login:hover {
  box-shadow: 0 16px 30px rgba(72, 137, 33, 0.28);
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
  background: rgba(76, 138, 19, 0.08);
  border: 1px solid rgba(76, 138, 19, 0.2);
  border-radius: 18px;
  cursor: pointer;
  display: inline-flex;
  height: 56px;
  justify-content: center;
  margin-top: 2.5rem;
  margin-left: auto;
  align-self: flex-end;
  padding: 0;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
  width: 56px;
}

.detail__back-top img {
  height: 24px;
  width: 24px;
}

.detail__back-top:hover {
  box-shadow: 0 14px 26px rgba(72, 137, 33, 0.24);
  transform: translateY(-2px);
}

.detail__footer {
  align-items: center;
  background: #1f2b3d;
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
  color: rgba(241, 245, 249, 0.92);
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
  display: grid;
  gap: 0.65rem;
  list-style: none;
  margin: 0;
  padding: 0;
}

.detail__socials a {
  align-items: center;
  color: rgba(241, 245, 249, 0.92);
  display: inline-flex;
  gap: 0.6rem;
  text-decoration: none;
}

.detail__socials img {
  height: 22px;
  width: 22px;
}

.detail__footer-top {
  align-items: center;
  background: rgba(255, 255, 255, 0.12);
  border: 1px solid rgba(255, 255, 255, 0.25);
  border-radius: 18px;
  cursor: pointer;
  display: inline-flex;
  height: 56px;
  justify-content: center;
  align-self: end;
  justify-self: end;
  padding: 0;
  transition: background 0.2s ease, transform 0.2s ease;
  width: 56px;
}

.detail__footer-top img {
  height: 24px;
  width: 24px;
}

.detail__footer-top:hover {
  background: rgba(255, 255, 255, 0.28);
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
