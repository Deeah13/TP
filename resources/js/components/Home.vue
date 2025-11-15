<template>
  <main class="home" id="beranda">
    <header :class="['home__navigation', { 'home__navigation--glass': isScrolled }]" role="banner">
      <div class="home__branding">
        <img class="home__logo" :src="assets.logo" alt="Logo Terminal Pintar" />
        <span class="home__brand-name">Terminal Pintar</span>
      </div>
      <nav class="home__menu" aria-label="Navigasi Utama">
        <a href="#kegiatan" class="home__menu-link" @click.prevent="scrollTo('kegiatan')">Kegiatan</a>
        <a href="#tentang-kami" class="home__menu-link" @click.prevent="scrollTo('tentang-kami')">Tentang Kami</a>
        <a href="#kontak" class="home__menu-link" @click.prevent="scrollTo('kontak')">Kontak</a>
      </nav>
      <div class="home__actions">
        <button class="home__icon-button" type="button" aria-label="Notifikasi">
          <img :src="assets.bell" alt="" aria-hidden="true" />
        </button>
        <a class="home__login" href="/login">Login</a>
      </div>
    </header>

    <section class="home__hero" aria-labelledby="hero-title">
      <figure class="home__hero-media" aria-hidden="true">
        <img :src="assets.hero" alt="Anak-anak belajar bersama" loading="lazy" />
      </figure>
      <div class="home__hero-overlay"></div>
      <div class="home__hero-content">
        <p class="home__hero-eyebrow">Ruang Belajar dan Harapan</p>
        <h2 id="hero-title" class="home__hero-title">Halo, Selamat Datang di Terminal Pintar!</h2>
        <p class="home__hero-description">
          Mari bergabung bersama kami untuk belajar, berbagi inspirasi, menjadi relawan, atau berdonasi demi mendukung
          masa depan yang lebih baik.
        </p>
        <RouterLink class="home__hero-cta" role="button" :to="{ name: 'news' }">Jelajah lebih lanjut</RouterLink>
      </div>
    </section>

    <section id="kegiatan" class="home__activities" aria-labelledby="activities-title">
      <div class="home__section-heading">
        <h2 id="activities-title">Dokumentasi Kegiatan Terbaru Kami</h2>
        <p>Ikuti cerita terbaru dari kelas, lokakarya, dan program komunitas Terminal Pintar.</p>
      </div>
      <div class="home__activities-grid">
        <article v-for="activity in activities" :key="activity.slug" class="home__activity-card">
          <div class="home__activity-header">
            <span class="home__activity-badge">
              <img :src="activity.badge" alt="" aria-hidden="true" />
            </span>
            <time class="home__activity-date" :datetime="activity.isoDate">{{ activity.date }}</time>
          </div>
          <img class="home__activity-image" :src="activity.image" :alt="activity.title" loading="lazy" />
          <h3 class="home__activity-title">{{ activity.title }}</h3>
          <p class="home__activity-copy">{{ activity.description }}</p>
          <RouterLink class="home__activity-link" :to="{ name: 'news-detail', params: { slug: activity.slug } }">
            Baca Selengkapnya
            <svg class="home__activity-icon" viewBox="0 0 24 24" aria-hidden="true">
              <path
                d="M5 12h14M13 6l6 6-6 6"
                fill="none"
                stroke="currentColor"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="1.5"
              />
            </svg>
          </RouterLink>
        </article>
      </div>
      <RouterLink class="home__activities-cta" :to="{ name: 'news' }">Lihat Semua Kegiatan</RouterLink>
    </section>

    <section id="tentang-kami" class="home__about" aria-labelledby="about-title">
      <div class="home__section-heading">
        <h2 id="about-title">Tentang Kami</h2>
        <p>Menemani perjalanan belajar anak melalui komunitas yang hangat dan kolaboratif.</p>
      </div>
      <div class="home__about-grid">
        <article class="home__about-card">
          <h3>Sejarah</h3>
          <p>
            Terminal Pintar lahir dari gerakan relawan yang ingin menghadirkan ruang aman untuk belajar dan bertumbuh.
            Dukungan masyarakat membuat program literasi, teknologi, hingga seni terus berkembang sampai hari ini.
          </p>
          <img :src="assets.history" alt="Pendiri Terminal Pintar" loading="lazy" />
          <footer>
            <span class="home__about-name">Andi Mohan</span>
            <span class="home__about-role">Pendiri Terminal Pintar</span>
          </footer>
        </article>
        <article class="home__about-card">
          <h3>Visi</h3>
          <p>
            Kami berkomitmen menjadi teman perjalanan bagi setiap anak untuk menemukan arti rumah, pendidikan, dan
            harapan melalui pengalaman belajar yang relevan, menyenangkan, dan penuh empati.
          </p>
          <img :src="assets.vision" alt="Ilustrasi visi Terminal Pintar" loading="lazy" />
          <footer>
            <span class="home__about-name">Tim Terminal Pintar</span>
            <span class="home__about-role">Kolaborasi Pengajar &amp; Relawan</span>
          </footer>
        </article>
      </div>
      <div class="home__team">
        <figure v-for="member in team" :key="member.name" class="home__team-member">
          <img :src="member.image" :alt="member.name" loading="lazy" />
          <figcaption>
            <p class="home__team-name">{{ member.name }}</p>
            <p class="home__team-role">{{ member.role }}</p>
          </figcaption>
        </figure>
      </div>
    </section>

    <section class="home__contribution" aria-labelledby="contribution-title">
      <div class="home__section-heading home__section-heading--light">
        <h2 id="contribution-title">Mari Berkontribusi Membangun Harapan!</h2>
        <p>Setiap dukungan membantu membuka kesempatan belajar yang lebih luas.</p>
      </div>
      <div class="home__contribution-grid">
        <article class="home__contribution-card">
          <img :src="assets.donation" alt="Ikon Donasi" loading="lazy" />
          <h3>Donasi</h3>
          <p>
            Ikut berdonasi untuk menghadirkan fasilitas belajar, bahan ajar, dan ruang kreatif bagi anak-anak Terminal
            Pintar.
          </p>
          <button type="button">Baca Selengkapnya</button>
          <button type="button" class="home__ghost">Lihat Cara Berdonasi</button>
        </article>
        <article class="home__contribution-card home__contribution-card--accent">
          <img :src="assets.volunteer" alt="Ikon Relawan" loading="lazy" />
          <h3>Relawan</h3>
          <p>
            Bergabung sebagai relawan pengajar, fasilitator, atau mentor agar lebih banyak anak merasakan pengalaman
            belajar yang menyenangkan.
          </p>
          <button type="button">Baca Selengkapnya</button>
          <button type="button" class="home__ghost">Lihat Cara Mendaftar</button>
        </article>
      </div>
    </section>

    <footer id="kontak" class="home__footer" role="contentinfo">
      <div class="home__footer-column">
        <h3>Kontak Kami</h3>
        <a class="home__footer-link" href="tel:+6289123456789">
          <img :src="assets.phone" alt="" aria-hidden="true" />
          <span>+6289123456789</span>
        </a>
        <a class="home__footer-link" href="mailto:terminalpintar@gmail.com">
          <img :src="assets.gmail" alt="" aria-hidden="true" />
          <span>terminalpintar@gmail.com</span>
        </a>
      </div>
      <div class="home__footer-column">
        <h3>Media Sosial</h3>
        <a
          class="home__footer-link"
          href="https://instagram.com/terminalpintar_id"
          target="_blank"
          rel="noopener noreferrer"
        >
          <img :src="assets.instagram" alt="Instagram" />
          <span>@terminalpintar_id</span>
        </a>
        <a
          class="home__footer-link"
          href="https://tiktok.com/@terminalpintar"
          target="_blank"
          rel="noopener noreferrer"
        >
          <img :src="assets.tiktok" alt="TikTok" />
          <span>@terminalpintar</span>
        </a>
        <a
          class="home__footer-link"
          href="https://youtube.com/@terminalpintar"
          target="_blank"
          rel="noopener noreferrer"
        >
          <img :src="assets.youtube" alt="YouTube" />
          <span>Terminal Pintar</span>
        </a>
      </div>
      <button class="home__back-to-top" type="button" aria-label="Kembali ke atas" @click="scrollTo('beranda')">
        <img :src="assets.scrollUp" alt="" aria-hidden="true" />
      </button>
    </footer>
  </main>
</template>

<script setup>
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';
import { RouterLink } from 'vue-router';
import { newsItems } from '../data/news';

const assets = {
  logo: 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=200&q=80',
  hero: 'https://images.unsplash.com/photo-1509062522246-3755977927d7?q=80&w=1600&auto=format&fit=crop',
  bell: 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgc3Ryb2tlPSIjNGM4YTEzIiBzdHJva2Utd2lkdGg9IjEuNSIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIj48cGF0aCBkPSJNMTIgMjBhMiAyIDAgMSAwIDQtMiI+PC9wYXRoPjxwYXRoIGQ9Ik0zLjg2IDE3SDIwLjEzYTEgMSAwIDAwLjg3LTEuNTRBMTEuMDMgMTEuMDMgMCAwMDIwIDExYzAtNS41My00LjQ3LTEwLTEwLTEwUzkgNS40NyA5IDExYTExLjAzIDExLjAzIDAgMDAtNS4wMSA0LjQ2IDEgMSAwIDAwLjg3IDEuNTR6Ij48L3BhdGg+PC9zdmc+',
  history: 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=800&auto=format&fit=crop',
  vision: 'https://images.unsplash.com/photo-1496307042754-b4aa456c4a2d?q=80&w=800&auto=format&fit=crop',
  donation: 'https://images.unsplash.com/photo-1521790361543-f645cf042ec4?q=80&w=600&auto=format&fit=crop',
  volunteer: 'https://images.unsplash.com/photo-1493836512294-502baa1986e2?q=80&w=600&auto=format&fit=crop',
  phone: 'https://img.icons8.com/ios-filled/50/4CAF50/phone.png',
  gmail: 'https://img.icons8.com/color/48/gmail--v1.png',
  instagram: 'https://img.icons8.com/fluency/48/instagram-new.png',
  tiktok: 'https://img.icons8.com/fluency/48/tiktok.png',
  youtube: 'https://img.icons8.com/color/48/youtube-play.png',
  scrollUp: 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0OCIgaGVpZ2h0PSI0OCIgdmlld0JveD0iMCAwIDQ4IDQ4IiBmaWxsPSJub25lIj48Y2lyY2xlIGN4PSIyNCIgY3k9IjI0IiByPSIyNCIgZmlsbD0id2hpdGUiLz48cGF0aCBkPSJNMjQgMTZsLTggOGg2djEyaDRWMjRoNmwtOC04eiIgZmlsbD0iIzRDQUY1MCIvPjwvc3ZnPg==',
};

const badges = [
  'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzIiIGhlaWdodD0iMzIiIHZpZXdCb3g9IjAgMCAzMiAzMiIgeG1sbnM9Imh0dHA6Ly93d3cu'+
    'dzMub3JnLzIwMDAvc3ZnIj48Y2lyY2xlIGN4PSIxNiIgY3k9IjE2IiByPSIxNiIgZmlsbD0iI0VGZkY1QyIgLz48cGF0aCBkPSJNMTYu'+
    'NSAyMkwxMSAxNi43bDIuMy0yLjVM'+
    'MTYuNSAxOC42bDQuNy00LjlsMi4zIDIuNXoiIGZpbGw9IiM0Q0FGNTAiIC8+PC9zdmc+',
  'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzIiIGhlaWdodD0iMzIiIHZpZXdCb3g9IjAgMCAzMiAzMiIgeG1sbnM9Imh0dHA6Ly93d3cu'+
    'dzMub3JnLzIwMDAvc3ZnIj48Y2lyY2xlIGN4PSIxNiIgY3k9IjE2IiByPSIxNiIgZmlsbD0iI0VGRTVGRiIgLz48cGF0aCBkPSJNMT'+
    'YgMTBsMyA3aC02bDMtN3ptMCAxMWMtMS4xIDAtMiAuOS0yIDJzLjkgMiAyIDIgMi0uOSAyLTItLjktMi0yLTJ6IiBmaWxsPSIjNDlCMz'+
    'Q1IiAvPjwvc3ZnPg==',
  'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzIiIGhlaWdodD0iMzIiIHZpZXdCb3g9IjAgMCAzMiAzMiIgeG1sbnM9Imh0dHA6Ly93d3cu'+
    'dzMub3JnLzIwMDAvc3ZnIj48Y2lyY2xlIGN4PSIxNiIgY3k9IjE2IiByPSIxNiIgZmlsbD0iI0VGM0ZGNyIgLz48cGF0aCBkPSJNMTEu'+
    'NSAyMS4xYTEgMSAwIDAgMS0uNy0xLjdsNS03YTEgMSAwIDAgMSAxLjYgMGw1IDdhMSAxIDAgMCAxLTEuNiAxLjRsLTEuOC0yLjQ1TD'+
    'E2IDIwLjIxbC0yLjkgMy43em0yLjUtOS41YTIgMiAwIDEgMS0yLTIgMiAyIDAgMCAxIDIgMnoiIGZpbGw9IiM0ODkzMzciIC8+PC9zdm'+
    'c+',
];

const summaryOverrides = {
  'workshop-coding-untuk-anak':
    'Anak-anak belajar dasar-dasar programming dengan cara yang menyenangkan',
  'kelas-bahasa-interaktif':
    'Sesi pembelajaran bahasa Inggris dengan metode storytelling dan games',
  'perpustakaan-mini-dibuka':
    'Perpustakaan mini Terminal Pintar hadir dengan 200+ koleksi bacaan baru',
};

const activities = computed(() =>
  newsItems.slice(0, 3).map((item, index) => ({
    slug: item.slug,
    title: item.title,
    date: item.date,
    isoDate: item.detail.isoDate,
    description: summaryOverrides[item.slug] || item.summary,
    image: item.image,
    badge: badges[index % badges.length],
  }))
);

const team = [
  {
    name: 'Andi Rahmadi',
    role: 'Pengurus',
    image: 'https://images.unsplash.com/photo-1527980965255-d3b416303d12?q=80&w=400&auto=format&fit=crop',
  },
  {
    name: 'Nabila Nurahani',
    role: 'Pengajar',
    image: 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?q=80&w=400&auto=format&fit=crop',
  },
  {
    name: 'Hanifah Ahmada',
    role: 'Pengajar',
    image: 'https://images.unsplash.com/photo-1544723795-3fb6469f5b39?q=80&w=400&auto=format&fit=crop',
  },
];

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
  const target = document.getElementById(id);

  if (target) {
    target.scrollIntoView({ behavior: 'smooth' });
  }
};
</script>

<style scoped>
:global(body) {
  font-family: 'Poppins', 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
}

.home {
  background: #f5f7fb;
  border: 8px solid #cac4d0;
  border-radius: 28px;
  color: #1f2b3d;
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  min-width: 1127px;
  overflow: hidden;
  position: relative;
}

.home__navigation {
  align-items: center;
  background: rgba(255, 255, 255, 0.98);
  border-bottom: 1px solid rgba(202, 196, 208, 0.45);
  box-shadow: 0 10px 24px rgba(25, 39, 58, 0.08);
  display: grid;
  gap: 1.5rem;
  grid-template-columns: auto 1fr auto;
  padding: 1.05rem 2.8rem;
  position: sticky;
  top: 0;
  transition: background 0.35s ease, box-shadow 0.35s ease, backdrop-filter 0.35s ease, border-color 0.35s ease;
  z-index: 24;
}

.home__navigation--glass {
  background: rgba(255, 255, 255, 0.72);
  border-bottom-color: rgba(255, 255, 255, 0.5);
  box-shadow: 0 20px 45px rgba(21, 35, 54, 0.18);
  backdrop-filter: blur(18px) saturate(150%);
  -webkit-backdrop-filter: blur(18px) saturate(150%);
}

.home__branding {
  align-items: center;
  display: inline-flex;
  gap: 0.75rem;
}

.home__logo {
  border-radius: 14px;
  box-shadow: 0 12px 24px rgba(31, 43, 61, 0.15);
  height: 52px;
  object-fit: cover;
  width: 52px;
}

.home__brand-name {
  color: #233143;
  font-size: 1.4rem;
  font-weight: 700;
  letter-spacing: 0.02em;
}

.home__menu {
  display: inline-flex;
  gap: 2rem;
  justify-content: center;
}

.home__menu-link {
  color: #4b5c72;
  font-weight: 600;
  position: relative;
  text-decoration: none;
  transition: color 0.2s ease;
}

.home__menu-link::after {
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

.home__menu-link:hover {
  color: #4c8a13;
}

.home__menu-link:hover::after {
  transform: scaleX(1);
}

.home__actions {
  align-items: center;
  display: inline-flex;
  gap: 1rem;
}

.home__icon-button {
  background: rgba(120, 174, 78, 0.12);
  border: none;
  border-radius: 12px;
  cursor: pointer;
  display: inline-flex;
  padding: 0.6rem;
  transition: background 0.2s ease, transform 0.2s ease;
}

.home__icon-button img {
  height: 22px;
  width: 22px;
}

.home__icon-button:hover {
  background: rgba(120, 174, 78, 0.22);
  transform: translateY(-1px);
}

.home__login {
  background: linear-gradient(135deg, #78ae4e, #4c8a13);
  border-radius: 999px;
  color: #ffffff;
  display: inline-flex;
  font-weight: 600;
  padding: 0.6rem 1.9rem;
  text-decoration: none;
  transition: box-shadow 0.2s ease, transform 0.2s ease;
}

.home__login:hover {
  box-shadow: 0 16px 30px rgba(72, 137, 33, 0.28);
  transform: translateY(-2px);
}

.home__hero {
  position: relative;
  overflow: hidden;
}

.home__hero-media {
  margin: 0;
}

.home__hero-media img {
  display: block;
  height: 460px;
  object-fit: cover;
  width: 100%;
}

.home__hero-overlay {
  background: linear-gradient(110deg, rgba(31, 43, 61, 0.78) 0%, rgba(44, 98, 38, 0.82) 55%, rgba(76, 138, 19, 0.7) 100%);
  inset: 0;
  pointer-events: none;
  position: absolute;
  z-index: 1;
}

.home__hero-content {
  align-items: flex-start;
  color: #ffffff;
  display: flex;
  flex-direction: column;
  gap: 1rem;
  left: 8%;
  max-width: 520px;
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  z-index: 2;
}

.home__hero-eyebrow {
  background: rgba(255, 255, 255, 0.2);
  border-radius: 999px;
  font-size: 0.9rem;
  font-weight: 600;
  letter-spacing: 0.08em;
  padding: 0.45rem 1.1rem;
  text-transform: uppercase;
}

.home__hero-title {
  font-size: 2.6rem;
  font-weight: 700;
  line-height: 1.3;
  margin: 0;
}

.home__hero-description {
  font-size: 1.05rem;
  line-height: 1.8;
  margin: 0;
}

.home__hero-cta {
  align-items: center;
  background: linear-gradient(135deg, #ffee58, #ffca28);
  border-radius: 999px;
  color: #3d4e17;
  display: inline-flex;
  font-weight: 600;
  gap: 0.6rem;
  letter-spacing: 0.01em;
  padding: 0.85rem 2.1rem;
  text-decoration: none;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.home__hero-cta:hover {
  box-shadow: 0 12px 25px rgba(255, 241, 118, 0.35);
  transform: translateY(-2px);
}

.home__section-heading {
  margin: 0 auto 2rem;
  max-width: 640px;
  text-align: center;
}

.home__section-heading h2 {
  color: #1f2b3d;
  font-size: 2rem;
  font-weight: 700;
  margin-bottom: 0.75rem;
}

.home__section-heading p {
  color: #64748b;
  font-size: 1rem;
  line-height: 1.6;
  margin: 0;
}

.home__activities {
  padding: 4rem 5rem 3.5rem;
}

.home__activities-grid {
  display: grid;
  gap: 2rem;
  grid-template-columns: repeat(3, minmax(0, 1fr));
}

.home__activity-card {
  background: rgba(255, 255, 255, 0.82);
  border: 1px solid rgba(255, 255, 255, 0.55);
  border-radius: 22px;
  box-shadow: 0 24px 48px rgba(21, 62, 19, 0.14);
  display: flex;
  flex-direction: column;
  gap: 1.1rem;
  overflow: hidden;
  padding: 1.75rem;
  position: relative;
  transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
  backdrop-filter: blur(22px) saturate(140%);
  -webkit-backdrop-filter: blur(22px) saturate(140%);
}

.home__activity-card::after {
  background: linear-gradient(135deg, rgba(120, 174, 78, 0.12), rgba(76, 138, 19, 0.08));
  content: '';
  inset: 0;
  opacity: 0;
  position: absolute;
  transition: opacity 0.25s ease;
  z-index: 0;
}

.home__activity-card > * {
  position: relative;
  z-index: 1;
}

.home__activity-card:hover {
  border-color: rgba(120, 174, 78, 0.4);
  box-shadow: 0 32px 60px rgba(21, 62, 19, 0.22);
  transform: translateY(-8px);
}

.home__activity-card:hover::after {
  opacity: 1;
}

.home__activity-header {
  align-items: center;
  display: flex;
  justify-content: space-between;
}

.home__activity-badge {
  align-items: center;
  background: rgba(120, 174, 78, 0.12);
  border-radius: 999px;
  display: inline-flex;
  padding: 0.45rem;
}

.home__activity-badge img {
  height: 28px;
  width: 28px;
}

.home__activity-date {
  color: #4c8a13;
  font-size: 0.9rem;
  font-weight: 600;
}

.home__activity-image {
  border-radius: 16px;
  height: 160px;
  object-fit: cover;
  width: 100%;
}

.home__activity-title {
  color: #1f2b3d;
  font-size: 1.2rem;
  font-weight: 600;
  margin: 0;
}

.home__activity-copy {
  color: #64748b;
  font-size: 0.95rem;
  line-height: 1.6;
  margin: 0;
}

.home__activity-link {
  align-items: center;
  color: #4c8a13;
  display: inline-flex;
  font-weight: 600;
  gap: 0.35rem;
  text-decoration: none;
}

.home__activity-icon {
  height: 18px;
  width: 18px;
}

.home__activities-cta {
  align-items: center;
  background: linear-gradient(135deg, #78ae4e, #4c8a13);
  border-radius: 999px;
  color: #ffffff;
  display: inline-flex;
  font-weight: 600;
  margin: 2.5rem auto 0;
  padding: 0.85rem 2.5rem;
  text-decoration: none;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.home__activities-cta:hover {
  box-shadow: 0 18px 32px rgba(72, 137, 33, 0.28);
  transform: translateY(-3px);
}

.home__about {
  background: #ffffff;
  padding: 4rem 5rem;
}

.home__about-grid {
  display: grid;
  gap: 2rem;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  margin-bottom: 2.5rem;
}

.home__about-card {
  background: linear-gradient(180deg, #f8fbf2 0%, #ffffff 100%);
  border-radius: 20px;
  box-shadow: 0 18px 36px rgba(33, 61, 37, 0.12);
  display: flex;
  flex-direction: column;
  gap: 1rem;
  padding: 2rem;
}

.home__about-card h3 {
  color: #4c8a13;
  font-size: 1.2rem;
  font-weight: 700;
  margin: 0;
  text-transform: uppercase;
}

.home__about-card p {
  color: #4a5568;
  font-size: 0.98rem;
  line-height: 1.7;
  margin: 0;
}

.home__about-card img {
  border-radius: 16px;
  height: 150px;
  object-fit: cover;
  width: 100%;
}

.home__about-card footer {
  border-top: 1px solid rgba(76, 138, 19, 0.15);
  display: flex;
  flex-direction: column;
  gap: 0.2rem;
  padding-top: 0.75rem;
}

.home__about-name {
  color: #1f2b3d;
  font-weight: 600;
}

.home__about-role {
  color: #64748b;
  font-size: 0.9rem;
}

.home__team {
  display: flex;
  gap: 2.5rem;
  justify-content: center;
}

.home__team-member {
  align-items: center;
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.home__team-member img {
  border-radius: 50%;
  box-shadow: 0 16px 28px rgba(31, 43, 61, 0.14);
  height: 96px;
  object-fit: cover;
  width: 96px;
}

.home__team-name {
  color: #1f2b3d;
  font-weight: 600;
  margin: 0;
}

.home__team-role {
  color: #64748b;
  margin: 0;
}

.home__contribution {
  background: linear-gradient(180deg, #f8f3ff 0%, #f0f7ff 100%);
  padding: 4rem 5rem 3.5rem;
}

.home__section-heading--light h2 {
  color: #324156;
}

.home__section-heading--light p {
  color: #556176;
}

.home__contribution-grid {
  display: grid;
  gap: 2rem;
  grid-template-columns: repeat(2, minmax(0, 1fr));
}

.home__contribution-card {
  background: rgba(255, 255, 255, 0.78);
  border: 1px solid rgba(255, 255, 255, 0.5);
  border-radius: 22px;
  box-shadow: 0 26px 50px rgba(35, 57, 92, 0.16);
  display: flex;
  flex-direction: column;
  gap: 1rem;
  padding: 2.2rem;
  position: relative;
  transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
  backdrop-filter: blur(20px) saturate(150%);
  -webkit-backdrop-filter: blur(20px) saturate(150%);
}

.home__contribution-card::before {
  border-radius: inherit;
  content: '';
  inset: 0;
  position: absolute;
  z-index: 0;
  opacity: 0;
  transition: opacity 0.25s ease;
  background: linear-gradient(135deg, rgba(120, 174, 78, 0.12), rgba(72, 137, 33, 0.18));
}

.home__contribution-card > * {
  position: relative;
  z-index: 1;
}

.home__contribution-card:hover {
  border-color: rgba(120, 174, 78, 0.35);
  box-shadow: 0 34px 65px rgba(35, 57, 92, 0.22);
  transform: translateY(-8px);
}

.home__contribution-card:hover::before {
  opacity: 1;
}

.home__contribution-card img {
  height: 64px;
  width: 64px;
}

.home__contribution-card h3 {
  color: #334155;
  font-size: 1.2rem;
  font-weight: 700;
  margin: 0;
}

.home__contribution-card p {
  color: #556176;
  font-size: 0.98rem;
  line-height: 1.6;
  margin: 0;
}

.home__contribution-card button {
  align-items: center;
  background: linear-gradient(135deg, #78ae4e, #4c8a13);
  border: none;
  border-radius: 999px;
  color: #ffffff;
  cursor: pointer;
  font-weight: 600;
  padding: 0.75rem 1.8rem;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.home__contribution-card button:hover {
  box-shadow: 0 18px 30px rgba(72, 137, 33, 0.28);
  transform: translateY(-2px);
}

.home__contribution-card--accent::before {
  background: linear-gradient(135deg, rgba(255, 157, 66, 0.12), rgba(255, 123, 44, 0.18));
}

.home__contribution-card--accent button {
  background: linear-gradient(135deg, #ff9d42, #ff7b2c);
}

.home__ghost {
  background: rgba(76, 138, 19, 0.1) !important;
  color: #4c8a13 !important;
}

.home__footer {
  align-items: center;
  background: #1f2b3d;
  display: grid;
  gap: 2rem;
  grid-template-columns: repeat(2, minmax(0, 1fr)) auto;
  padding: 2.75rem 4rem;
}

.home__footer-column h3 {
  color: #ffffff;
  font-size: 1.15rem;
  font-weight: 700;
  margin-bottom: 1rem;
}

.home__footer-link {
  align-items: center;
  color: rgba(241, 245, 249, 0.92);
  display: inline-flex;
  gap: 0.75rem;
  margin-bottom: 0.75rem;
  text-decoration: none;
}

.home__footer-link img {
  height: 24px;
  width: 24px;
}

.home__back-to-top {
  align-items: center;
  background: rgba(255, 255, 255, 0.12);
  border: 1px solid rgba(255, 255, 255, 0.25);
  border-radius: 18px;
  cursor: pointer;
  display: inline-flex;
  height: 58px;
  justify-content: center;
  align-self: end;
  padding: 0;
  transition: background 0.2s ease, transform 0.2s ease;
  width: 58px;
  justify-self: end;
}

.home__back-to-top img {
  height: 26px;
  width: 26px;
}

.home__back-to-top:hover {
  background: rgba(255, 255, 255, 0.28);
  transform: translateY(-2px);
}
</style>
