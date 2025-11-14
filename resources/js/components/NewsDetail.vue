<template>
  <div class="doc-detail" id="top">
    <header v-if="article" class="doc-detail__header" role="banner">
      <div class="doc-detail__brand">
        <RouterLink class="brand__logo" :to="{ name: 'home' }" aria-label="Kembali ke beranda">
          TP
        </RouterLink>
        <div class="brand__copy">
          <span class="brand__name">Terminal Pintar</span>
          <span class="brand__tagline">Portal Dokumentasi &amp; Berita</span>
        </div>
      </div>
      <nav class="doc-detail__nav" role="navigation" aria-label="Navigasi utama">
        <RouterLink :to="{ name: 'home' }">Beranda</RouterLink>
        <a href="#article">Kegiatan</a>
        <RouterLink :to="{ name: 'news' }">Dokumentasi</RouterLink>
      </nav>
      <div class="doc-detail__actions">
        <button type="button" class="icon-button" aria-label="Notifikasi">
          🔔
        </button>
        <a class="doc-detail__login" href="/login">Login</a>
      </div>
    </header>

    <main v-if="article" class="doc-detail__main">
      <section class="doc-detail__breadcrumb">
        <p class="breadcrumb__label">{{ article.detail.breadcrumbLabel }}</p>
        <h1 class="breadcrumb__title">{{ article.title }}</h1>
      </section>

      <figure class="doc-detail__hero">
        <img :src="article.detail.heroImage" :alt="article.detail.heroAlt" />
      </figure>

      <article class="doc-detail__body" id="article">
        <time class="doc-detail__date" :datetime="article.detail.isoDate">{{ article.date }}</time>
        <h2 class="doc-detail__headline">{{ article.detail.highlightTitle }}</h2>
        <div class="doc-detail__paragraphs">
          <p v-for="(paragraph, index) in article.detail.paragraphs" :key="index">
            {{ paragraph }}
          </p>
        </div>

        <figure class="doc-detail__gallery">
          <img :src="article.detail.galleryImage" :alt="article.detail.galleryAlt" />
        </figure>

        <section class="doc-detail__team" aria-label="Tim penyelenggara kegiatan">
          <div v-for="member in article.detail.team" :key="member.name" class="team-card">
            <p class="team-card__name">{{ member.name }}</p>
            <p class="team-card__role">{{ member.role }}</p>
          </div>
        </section>

        <a class="doc-detail__scroll-top" href="#top" aria-label="Kembali ke atas">
          ⬆️
        </a>
      </article>

      <section class="doc-detail__contribution">
        <h2 class="contribution__title">Mari Berkontribusi Membangun Harapan!</h2>
        <div class="contribution__grid">
          <article
            v-for="contribution in article.detail.contributions"
            :key="contribution.title"
            class="contribution-card"
          >
            <img :src="contribution.image" :alt="contribution.imageAlt" class="contribution-card__image" />
            <h3 class="contribution-card__heading">{{ contribution.title }}</h3>
            <p class="contribution-card__copy">{{ contribution.description }}</p>
            <a class="contribution-card__cta" :href="contribution.primaryCta.href">
              {{ contribution.primaryCta.label }}
            </a>
            <a class="contribution-card__link" :href="contribution.secondaryCta.href">
              {{ contribution.secondaryCta.label }}
            </a>
          </article>
        </div>

        <div class="doc-detail__ornaments" aria-hidden="true">
          <span class="ornament ornament--one">🎁</span>
          <span class="ornament ornament--two">🤝</span>
          <span class="ornament ornament--three">📚</span>
        </div>
      </section>
    </main>

    <footer v-if="article" class="doc-detail__footer" role="contentinfo">
      <div class="footer__column">
        <h3>Kontak Kami</h3>
        <p class="footer__contact">{{ article.detail.contacts.phone }}</p>
        <a class="footer__contact" :href="`mailto:${article.detail.contacts.email}`">
          {{ article.detail.contacts.email }}
        </a>
      </div>
      <div class="footer__column">
        <h3>Media Sosial</h3>
        <ul class="footer__socials">
          <li v-for="social in article.detail.contacts.socials" :key="social.label">
            <a :href="social.href" target="_blank" rel="noopener">{{ social.label }}</a>
          </li>
        </ul>
      </div>
      <a class="footer__to-top" href="#top" aria-label="Kembali ke atas">⬆️</a>
    </footer>

    <div v-else class="doc-detail__empty">
      Konten dokumentasi tidak ditemukan.
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted, watch } from 'vue';
import { RouterLink, useRoute, useRouter } from 'vue-router';
import { newsMap } from '../data/news';

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

onMounted(() => {
  ensureArticleOrRedirect();
});

watch(
  () => route.params.slug,
  () => {
    ensureArticleOrRedirect();
  }
);
</script>

<style scoped>
.doc-detail {
  background: #fef7ff;
  border: 8px solid #cac4d0;
  color: #1f2933;
  display: flex;
  flex-direction: column;
  font-family: 'Poppins', 'Plus Jakarta Sans', system-ui, sans-serif;
  min-height: 100vh;
  min-width: 1127px;
  position: relative;
}

.doc-detail__header {
  align-items: center;
  background: rgba(255, 255, 255, 0.92);
  backdrop-filter: blur(18px);
  border-bottom: 1px solid rgba(202, 196, 208, 0.6);
  display: flex;
  gap: 2rem;
  justify-content: space-between;
  padding: 1.25rem 2.5rem;
}

.doc-detail__brand {
  align-items: center;
  display: flex;
  gap: 1rem;
}

.brand__logo {
  align-items: center;
  background: linear-gradient(135deg, #76b340 0%, #4c8a13 100%);
  border-radius: 16px;
  color: #fff;
  display: inline-flex;
  font-weight: 700;
  height: 48px;
  justify-content: center;
  text-decoration: none;
  width: 48px;
}

.brand__copy {
  display: flex;
  flex-direction: column;
  font-size: 0.85rem;
  line-height: 1.3;
}

.brand__name {
  font-size: 1.05rem;
  font-weight: 600;
}

.brand__tagline {
  color: #5f6b7c;
  font-size: 0.75rem;
}

.doc-detail__nav {
  align-items: center;
  display: flex;
  gap: 1.5rem;
}

.doc-detail__nav a {
  color: #4c5c72;
  font-weight: 500;
  text-decoration: none;
  transition: color 0.2s ease;
}

.doc-detail__nav a:hover {
  color: #2f3e57;
}

.doc-detail__actions {
  align-items: center;
  display: flex;
  gap: 1rem;
}

.icon-button {
  align-items: center;
  background: #fff;
  border: 1px solid rgba(118, 179, 64, 0.25);
  border-radius: 50%;
  color: #4c5c72;
  cursor: pointer;
  display: inline-flex;
  font-size: 1rem;
  height: 40px;
  justify-content: center;
  width: 40px;
  transition: background 0.2s ease;
}

.icon-button:hover {
  background: rgba(118, 179, 64, 0.1);
}

.doc-detail__login {
  background: linear-gradient(135deg, #76b340 0%, #4c8a13 100%);
  border-radius: 999px;
  color: #fff;
  font-weight: 600;
  padding: 0.45rem 1.6rem;
  text-decoration: none;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.doc-detail__login:hover {
  box-shadow: 0 12px 30px rgba(118, 179, 64, 0.3);
  transform: translateY(-1px);
}

.doc-detail__main {
  padding: 2.5rem;
}

.doc-detail__breadcrumb {
  margin: 0 auto 2rem;
  max-width: 960px;
  text-align: center;
}

.breadcrumb__label {
  color: #76b340;
  font-size: 0.95rem;
  font-weight: 600;
  letter-spacing: 0.05em;
  margin-bottom: 0.5rem;
  text-transform: uppercase;
}

.breadcrumb__title {
  font-size: 2.35rem;
  margin: 0;
}

.doc-detail__hero {
  border-radius: 32px;
  margin: 0 auto;
  max-width: 1040px;
  overflow: hidden;
}

.doc-detail__hero img {
  display: block;
  height: 320px;
  object-fit: cover;
  width: 100%;
}

.doc-detail__body {
  background: #ffffff;
  border-radius: 32px;
  box-shadow: 0 25px 60px rgba(31, 41, 51, 0.1);
  margin: -4rem auto 0;
  max-width: 900px;
  padding: 3rem;
  position: relative;
}

.doc-detail__date {
  color: #6d6d6d;
  font-size: 0.95rem;
  letter-spacing: 0.05em;
  text-transform: uppercase;
}

.doc-detail__headline {
  font-size: 1.75rem;
  margin: 1rem 0 1.5rem;
  line-height: 1.4;
}

.doc-detail__paragraphs {
  display: flex;
  flex-direction: column;
  gap: 1.25rem;
  font-size: 1rem;
  line-height: 1.8;
  color: #425066;
}

.doc-detail__gallery {
  border-radius: 24px;
  margin: 2rem 0;
  overflow: hidden;
}

.doc-detail__gallery img {
  display: block;
  width: 100%;
  object-fit: cover;
  height: 280px;
}

.doc-detail__team {
  display: grid;
  gap: 1.5rem;
  grid-template-columns: repeat(3, minmax(0, 1fr));
  margin-top: 2.5rem;
}

.team-card {
  background: linear-gradient(135deg, rgba(118, 179, 64, 0.12), rgba(255, 255, 255, 0.95));
  border: 1px solid rgba(118, 179, 64, 0.2);
  border-radius: 18px;
  padding: 1.25rem;
  text-align: center;
}

.team-card__name {
  font-weight: 600;
  margin: 0 0 0.25rem;
}

.team-card__role {
  color: #5f6b7c;
  font-size: 0.9rem;
  margin: 0;
}

.doc-detail__scroll-top {
  align-items: center;
  background: #ffffff;
  border: 1px solid rgba(118, 179, 64, 0.25);
  border-radius: 50%;
  bottom: -1.8rem;
  color: #76b340;
  display: inline-flex;
  font-size: 1.1rem;
  height: 48px;
  justify-content: center;
  left: 50%;
  position: absolute;
  text-decoration: none;
  transform: translateX(-50%);
  width: 48px;
  box-shadow: 0 12px 24px rgba(118, 179, 64, 0.15);
}

.doc-detail__contribution {
  margin: 5rem auto 0;
  max-width: 960px;
  position: relative;
  text-align: center;
}

.contribution__title {
  font-size: 2rem;
  margin-bottom: 2rem;
}

.contribution__grid {
  display: grid;
  gap: 2rem;
  grid-template-columns: repeat(2, minmax(0, 1fr));
}

.contribution-card {
  background: #ffffff;
  border: 1px solid rgba(202, 196, 208, 0.5);
  border-radius: 28px;
  box-shadow: 0 20px 45px rgba(31, 41, 51, 0.08);
  display: flex;
  flex-direction: column;
  gap: 1.25rem;
  padding: 2rem;
  position: relative;
  text-align: left;
}

.contribution-card__image {
  border-radius: 18px;
  height: 150px;
  object-fit: cover;
  width: 100%;
}

.contribution-card__heading {
  color: #76b340;
  font-size: 1.25rem;
  margin: 0;
}

.contribution-card__copy {
  color: #4d596a;
  margin: 0;
  line-height: 1.6;
}

.contribution-card__cta,
.contribution-card__link {
  border-radius: 999px;
  display: inline-flex;
  font-weight: 600;
  justify-content: center;
  padding: 0.55rem 1.5rem;
  text-decoration: none;
  transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
}

.contribution-card__cta {
  background: linear-gradient(135deg, #76b340 0%, #4c8a13 100%);
  color: #fff;
}

.contribution-card__cta:hover {
  transform: translateY(-1px);
  box-shadow: 0 12px 24px rgba(118, 179, 64, 0.25);
}

.contribution-card__link {
  border: 1px solid #76b340;
  color: #76b340;
  margin-top: -0.5rem;
}

.contribution-card__link:hover {
  background: rgba(118, 179, 64, 0.1);
}

.doc-detail__ornaments {
  display: flex;
  justify-content: center;
  gap: 1rem;
  margin-top: 2.5rem;
}

.ornament {
  align-items: center;
  background: rgba(118, 179, 64, 0.15);
  border-radius: 50%;
  color: #4c8a13;
  display: inline-flex;
  font-size: 1.2rem;
  height: 48px;
  justify-content: center;
  width: 48px;
}

.doc-detail__footer {
  background: #1f2933;
  color: #fff;
  display: flex;
  gap: 3rem;
  justify-content: center;
  margin-top: 4rem;
  padding: 3rem 2.5rem;
  position: relative;
}

.footer__column h3 {
  margin-top: 0;
  margin-bottom: 1rem;
}

.footer__contact {
  color: #f1f5f9;
  display: block;
  margin-bottom: 0.5rem;
  text-decoration: none;
}

.footer__contact:hover {
  text-decoration: underline;
}

.footer__socials {
  list-style: none;
  margin: 0;
  padding: 0;
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.footer__socials a {
  color: #d1e7d6;
  text-decoration: none;
}

.footer__socials a:hover {
  text-decoration: underline;
}

.footer__to-top {
  align-items: center;
  background: #fff;
  border-radius: 50%;
  color: #1f2933;
  display: inline-flex;
  height: 44px;
  justify-content: center;
  position: absolute;
  right: 2.5rem;
  top: 50%;
  transform: translateY(-50%);
  text-decoration: none;
  width: 44px;
}

.doc-detail__empty {
  align-items: center;
  color: #4c5c72;
  display: flex;
  font-size: 1.1rem;
  justify-content: center;
  min-height: 60vh;
  padding: 2rem;
}
</style>
