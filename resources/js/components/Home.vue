<template>
  <div class="home">
    <header class="home__header">
      <div class="shell">
        <div class="header__brand">
          <span class="brand__mark">TP</span>
          <div class="brand__copy">
            <strong>Terminal Pintar</strong>
            <span>Membuka peluang belajar bagi semua</span>
          </div>
        </div>
        <nav class="header__nav">
          <a href="#activities">Kegiatan</a>
          <a href="#about">Tentang</a>
          <a href="#collaboration">Kolaborasi</a>
          <a href="#contact">Kontak</a>
          <RouterLink :to="{ name: 'news' }">Dokumentasi</RouterLink>
        </nav>
        <button type="button" class="header__cta" @click="scrollToSection('contact')">
          Hubungi Kami
        </button>
      </div>
    </header>

    <main>
      <section class="hero" id="hero">
        <img
          alt="Ilustrasi anak belajar"
          class="hero__art"
          src="https://images.unsplash.com/photo-1509062522246-3755977927d7?q=80&amp;w=2070&amp;auto=format&amp;fit=crop"
        />
        <div class="hero__overlay"></div>
        <div class="shell hero__content">
          <div>
            <p class="hero__eyebrow">Halo, Selamat Datang di Terminal Pintar!</p>
            <h1>Belajar, Berbagi, dan Bertumbuh Bersama</h1>
            <p class="hero__lead">
              Terminal Pintar adalah ruang kolaboratif untuk menghadirkan kesempatan belajar yang
              inklusif bagi anak-anak. Mari jelajahi program, cerita inspiratif, dan berbagai cara
              untuk ikut berkontribusi.
            </p>
            <div class="hero__actions">
              <button type="button" class="button button--primary" @click="scrollToSection('activities')">
                Jelajah lebih lanjut
              </button>
              <button type="button" class="button button--ghost" @click="scrollToSection('contact')">
                Hubungi Kami
              </button>
            </div>
          </div>
        </div>
      </section>

      <section class="activities" id="activities">
        <div class="shell">
          <h2>Dokumentasi Kegiatan Terbaru Kami</h2>
          <p class="section__lead">
            Berikut beberapa kegiatan terbaru Terminal Pintar yang menggambarkan semangat belajar dan
            berbagi dalam komunitas kami.
          </p>
          <div class="activities__grid">
            <article
              v-for="(activity, index) in activities"
              :key="activity.title"
              class="activity-card"
              :class="`activity-card--${index + 1}`"
            >
              <img :alt="activity.title" class="activity-card__media" :src="activity.image" />
              <div class="activity-card__body">
                <h3>{{ activity.title }}</h3>
                <span class="activity-card__date">{{ activity.date }}</span>
                <p>{{ activity.description }}</p>
                <RouterLink
                  class="activity-card__link"
                  :to="{ name: 'news-detail', params: { slug: activity.slug } }"
                >
                  Baca Selengkapnya
                </RouterLink>
              </div>
            </article>
          </div>
          <RouterLink class="activities__more" :to="{ name: 'news' }">
            Lihat Semua Kegiatan
          </RouterLink>
        </div>
      </section>

      <section class="about" id="about">
        <div class="shell about__layout">
          <div class="about__visual">
            <img
              alt="Relawan mengajar anak-anak"
              src="https://images.unsplash.com/photo-1600880292203-757bb62b4baf?q=80&amp;w=2070&amp;auto=format&amp;fit=crop"
            />
            <div class="about__badge">Belajar dari Relawan Berpengalaman</div>
          </div>
          <div class="about__content">
            <h2>Membangun Wadah Pembelajaran yang Humanis</h2>
            <p>
              Di Terminal Pintar, kami memfasilitasi pembelajaran kreatif dengan dukungan relawan dan
              tenaga pengajar profesional. Anak-anak mendapatkan pengalaman belajar yang hangat,
              relevan, dan terukur sesuai tahap perkembangan mereka.
            </p>
            <ul>
              <li>Modul pembelajaran tematik dengan aktivitas menyenangkan.</li>
              <li>Pendampingan intensif untuk menumbuhkan rasa percaya diri.</li>
              <li>Kolaborasi erat bersama orang tua dan komunitas lokal.</li>
            </ul>
          </div>
        </div>
      </section>

      <section class="collaboration" id="collaboration">
        <div class="shell">
          <h2>Mari Berkontribusi Membangun Harapan!</h2>
          <p class="section__lead">
            Tiga langkah mudah untuk terlibat mendukung program Terminal Pintar.
          </p>
          <div class="collaboration__steps">
            <div v-for="step in steps" :key="step.title" class="step-card">
              <span class="step-card__icon">{{ step.icon }}</span>
              <h3>{{ step.title }}</h3>
              <p>{{ step.copy }}</p>
            </div>
          </div>
        </div>
      </section>

      <section class="cta">
        <div class="shell cta__content">
          <div>
            <h2>Bersama Kita Wujudkan Akses Pendidikan yang Merata</h2>
            <p>
              Setiap kontribusi membawa perubahan. Mari bersama-sama menguatkan ekosistem belajar yang
              ramah dan berkelanjutan bagi anak-anak.
            </p>
          </div>
          <button type="button" class="button button--primary" @click="scrollToSection('contact')">
            Mari Kolaborasi
          </button>
        </div>
      </section>

      <section class="contact" id="contact">
        <div class="shell contact__layout">
          <div class="contact__intro">
            <h2>Terhubung dengan Terminal Pintar</h2>
            <p>
              Kami senantiasa terbuka untuk kolaborasi, pertanyaan, maupun peluang kemitraan. Silakan
              hubungi kami melalui kanal berikut.
            </p>
            <div class="contact__mail">
              <span class="icon-mail">📧</span>
              <a href="mailto:terminalpintar@gmail.com">terminalpintar@gmail.com</a>
            </div>
          </div>
          <div class="contact__channels">
            <a
              v-for="channel in channels"
              :key="channel.label"
              :href="channel.href"
              target="_blank"
              rel="noopener"
              class="channel-card"
            >
              <span class="channel-card__icon">{{ channel.icon }}</span>
              <div>
                <strong>{{ channel.label }}</strong>
                <span>{{ channel.copy }}</span>
              </div>
            </a>
          </div>
        </div>
      </section>
    </main>

    <button type="button" class="scroll-top" @click="scrollToSection('hero')" aria-label="Kembali ke atas">
      ⬆️
    </button>
  </div>
</template>

<script setup>
import { RouterLink } from 'vue-router';
import { newsItems } from '../data/news';

const activities = newsItems.slice(0, 3).map((item) => ({
  title: item.title,
  date: item.date,
  description: item.summary,
  image: item.image,
  slug: item.slug,
}));

const steps = [
  {
    icon: '🤝',
    title: 'Kenali Program',
    copy: 'Pelajari modul pembelajaran dan layanan yang tersedia di Terminal Pintar.',
  },
  {
    icon: '🧑\u200d🏫',
    title: 'Daftar Relawan',
    copy: 'Bergabung sebagai pengajar atau mentor untuk berbagi ilmu dan inspirasi.',
  },
  {
    icon: '🎁',
    title: 'Salurkan Dukungan',
    copy: 'Dukung keberlanjutan kegiatan melalui donasi materi maupun finansial.',
  },
];

const channels = [
  {
    icon: '📱',
    label: 'Instagram',
    copy: '@terminalpintar',
    href: 'https://www.instagram.com/',
  },
  {
    icon: '▶️',
    label: 'YouTube',
    copy: 'Terminal Pintar Channel',
    href: 'https://www.youtube.com/',
  },
  {
    icon: '💬',
    label: 'WhatsApp',
    copy: '+62 812-3456-7890',
    href: 'https://wa.me/6281234567890',
  },
];

const scrollToSection = (section) => {
  const target = section === 'hero' ? document.body : document.getElementById(section);

  if (target) {
    target.scrollIntoView({ behavior: 'smooth' });
  }
};
</script>

<style scoped>
.home {
  background: linear-gradient(180deg, #fef7ff 0%, #ffffff 60%, #f4fbf3 100%);
  color: #2a2a2a;
  font-family: 'Poppins', 'Plus Jakarta Sans', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
  min-height: 100vh;
  position: relative;
}

.shell {
  margin: 0 auto;
  max-width: 1120px;
  padding: 0 1.5rem;
}

.home__header {
  background: rgba(255, 255, 255, 0.9);
  backdrop-filter: blur(12px);
  border-bottom: 1px solid rgba(144, 157, 186, 0.25);
  position: sticky;
  top: 0;
  z-index: 50;
}

.home__header .shell {
  align-items: center;
  display: flex;
  gap: 1.5rem;
  justify-content: space-between;
  padding: 1.25rem 1.5rem;
}

.header__brand {
  align-items: center;
  display: flex;
  gap: 0.75rem;
}

.brand__mark {
  align-items: center;
  background: linear-gradient(135deg, #76b340 0%, #4c8a13 100%);
  border-radius: 16px;
  color: #fff;
  display: inline-flex;
  font-weight: 700;
  height: 48px;
  justify-content: center;
  width: 48px;
}

.brand__copy {
  display: flex;
  flex-direction: column;
  font-size: 0.85rem;
  line-height: 1.2;
}

.brand__copy strong {
  font-size: 1rem;
}

.header__nav {
  align-items: center;
  display: flex;
  gap: 1.5rem;
  font-weight: 500;
}

.header__nav a {
  color: #4c5c72;
  position: relative;
  transition: color 0.2s ease;
}

.header__nav a::after {
  background: linear-gradient(90deg, #76b340 0%, #4c8a13 100%);
  border-radius: 999px;
  bottom: -6px;
  content: '';
  height: 3px;
  left: 0;
  opacity: 0;
  position: absolute;
  transform: translateY(4px);
  transition: opacity 0.2s ease, transform 0.2s ease;
  width: 100%;
}

.header__nav a:hover {
  color: #2f3a4b;
}

.header__nav a:hover::after {
  opacity: 1;
  transform: translateY(0);
}

.header__cta {
  background: linear-gradient(135deg, #76b340 0%, #4c8a13 100%);
  border: none;
  border-radius: 999px;
  color: #fff;
  cursor: pointer;
  font-weight: 600;
  padding: 0.75rem 1.5rem;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.header__cta:hover {
  box-shadow: 0 10px 22px rgba(118, 179, 64, 0.35);
  transform: translateY(-1px);
}

.hero {
  position: relative;
}

.hero__art {
  height: 460px;
  object-fit: cover;
  width: 100%;
}

.hero__overlay {
  background: linear-gradient(180deg, rgba(39, 57, 105, 0.68) 0%, rgba(39, 57, 105, 0.4) 45%, rgba(118, 179, 64, 0.4) 100%);
  height: 100%;
  inset: 0;
  position: absolute;
}

.hero__content {
  align-items: center;
  color: #fff;
  display: flex;
  inset: 0;
  justify-content: flex-start;
  position: absolute;
}

.hero__content > div {
  max-width: 620px;
}

.hero__eyebrow {
  font-weight: 600;
  letter-spacing: 0.08em;
  margin-bottom: 0.5rem;
  text-transform: uppercase;
}

.hero h1 {
  font-size: clamp(2.25rem, 4vw, 3rem);
  line-height: 1.15;
  margin: 0;
}

.hero__lead {
  font-size: 1.05rem;
  line-height: 1.6;
  margin: 1rem 0 1.5rem;
}

.hero__actions {
  display: flex;
  flex-wrap: wrap;
  gap: 0.75rem;
}

.button {
  border: none;
  border-radius: 999px;
  cursor: pointer;
  font-size: 0.95rem;
  font-weight: 600;
  padding: 0.8rem 1.6rem;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.button--primary {
  background: linear-gradient(135deg, #76b340 0%, #4c8a13 100%);
  color: #fff;
}

.button--primary:hover {
  box-shadow: 0 10px 22px rgba(118, 179, 64, 0.35);
  transform: translateY(-1px);
}

.button--ghost {
  background: rgba(255, 255, 255, 0.16);
  color: #fff;
  outline: 1px solid rgba(255, 255, 255, 0.4);
}

.button--ghost:hover {
  background: rgba(255, 255, 255, 0.26);
  transform: translateY(-1px);
}

.activities {
  padding: 5rem 0 4rem;
}

.activities h2 {
  color: #76b340;
  font-size: 2rem;
  margin-bottom: 0.5rem;
  text-align: center;
}

.section__lead {
  color: #5b6475;
  margin: 0 auto 2.5rem;
  max-width: 720px;
  text-align: center;
}

.activities__grid {
  display: grid;
  gap: 1.5rem;
  grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
  margin-bottom: 2rem;
}

.activity-card {
  background: #fff;
  border: 1px solid rgba(186, 193, 209, 0.35);
  border-radius: 24px;
  box-shadow: 0 16px 40px rgba(104, 112, 118, 0.12);
  display: flex;
  flex-direction: column;
  overflow: hidden;
}

.activity-card__media {
  height: 180px;
  object-fit: cover;
  width: 100%;
}

.activity-card__body {
  display: flex;
  flex: 1;
  flex-direction: column;
  gap: 0.75rem;
  padding: 1.5rem;
}

.activity-card h3 {
  font-size: 1.1rem;
  margin: 0;
}

.activity-card__date {
  color: #6d6d6d;
  font-size: 0.9rem;
}

.activity-card__link {
  align-self: flex-start;
  background: transparent;
  border: 1px solid #76b340;
  border-radius: 999px;
  color: #76b340;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  font-weight: 600;
  padding: 0.4rem 1.2rem;
  text-decoration: none;
  transition: background-color 0.2s ease, color 0.2s ease;
}

.activity-card__link:hover {
  background: #76b340;
  color: #fff;
}

.activity-card__link:focus-visible {
  outline: 3px solid rgba(118, 179, 64, 0.4);
  outline-offset: 2px;
}

.activities__more {
  background: #76b340;
  border: none;
  border-radius: 999px;
  color: #fff;
  cursor: pointer;
  display: block;
  font-weight: 600;
  margin: 0 auto;
  padding: 0.8rem 1.9rem;
  text-align: center;
  text-decoration: none;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.activities__more:hover {
  box-shadow: 0 12px 30px rgba(118, 179, 64, 0.3);
  transform: translateY(-1px);
}

.about {
  padding: 4rem 0;
}

.about__layout {
  align-items: center;
  display: grid;
  gap: 3rem;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
}

.about__visual {
  position: relative;
}

.about__visual img {
  border-radius: 28px;
  box-shadow: 0 30px 60px rgba(39, 57, 105, 0.25);
  width: 100%;
}

.about__badge {
  background: rgba(118, 179, 64, 0.9);
  border-radius: 18px;
  bottom: 18px;
  color: #fff;
  font-weight: 600;
  left: 18px;
  padding: 0.65rem 1.2rem;
  position: absolute;
}

.about__content h2 {
  color: #2f3a4b;
  font-size: 2rem;
  margin-bottom: 1rem;
}

.about__content p {
  color: #546175;
  line-height: 1.6;
  margin-bottom: 1.5rem;
}

.about__content ul {
  color: #404b5e;
  display: grid;
  gap: 0.75rem;
  list-style: none;
  margin: 0;
  padding: 0;
}

.about__content li::before {
  color: #76b340;
  content: '•';
  margin-right: 0.65rem;
}

.collaboration {
  background: linear-gradient(135deg, rgba(118, 179, 64, 0.1) 0%, rgba(118, 179, 64, 0.02) 100%);
  padding: 4.5rem 0;
}

.collaboration h2 {
  color: #76b340;
  font-size: 2rem;
  margin-bottom: 0.75rem;
  text-align: center;
}

.collaboration__steps {
  display: grid;
  gap: 1.5rem;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  margin-top: 2.5rem;
}

.step-card {
  background: #fff;
  border-radius: 24px;
  box-shadow: 0 12px 28px rgba(118, 179, 64, 0.12);
  padding: 2rem 1.75rem;
  text-align: center;
}

.step-card__icon {
  font-size: 2rem;
  margin-bottom: 1rem;
}

.cta {
  padding: 4rem 0;
}

.cta__content {
  align-items: center;
  background: linear-gradient(135deg, #76b340 0%, #4c8a13 100%);
  border-radius: 32px;
  color: #fff;
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
  padding: 3rem;
  text-align: center;
}

.cta__content h2 {
  font-size: clamp(1.8rem, 3vw, 2.4rem);
  margin: 0;
}

.cta__content p {
  line-height: 1.6;
  margin: 0;
}

.contact {
  padding: 4rem 0 5rem;
}

.contact__layout {
  align-items: center;
  display: grid;
  gap: 2rem;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
}

.contact__intro h2 {
  font-size: 2rem;
  margin-bottom: 1rem;
}

.contact__intro p {
  color: #5f687a;
  line-height: 1.6;
}

.contact__mail {
  align-items: center;
  background: rgba(118, 179, 64, 0.1);
  border-radius: 16px;
  display: inline-flex;
  gap: 0.75rem;
  margin-top: 1.5rem;
  padding: 0.85rem 1.3rem;
}

.contact__mail a {
  color: #2f3a4b;
  font-weight: 600;
}

.contact__channels {
  display: grid;
  gap: 1rem;
}

.channel-card {
  align-items: center;
  background: #fff;
  border-radius: 20px;
  box-shadow: 0 12px 32px rgba(104, 112, 118, 0.14);
  display: flex;
  gap: 1rem;
  padding: 1.1rem 1.4rem;
  text-decoration: none;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.channel-card strong {
  display: block;
  font-size: 1rem;
  margin-bottom: 0.2rem;
}

.channel-card span {
  color: #5c6676;
  font-size: 0.9rem;
}

.channel-card:hover {
  box-shadow: 0 14px 38px rgba(118, 179, 64, 0.2);
  transform: translateY(-2px);
}

.scroll-top {
  background: #ffffff;
  border: 1px solid rgba(118, 179, 64, 0.3);
  border-radius: 999px;
  bottom: 24px;
  box-shadow: 0 10px 26px rgba(0, 0, 0, 0.12);
  cursor: pointer;
  font-size: 1.4rem;
  height: 48px;
  position: fixed;
  right: 24px;
  width: 48px;
  z-index: 60;
}

.scroll-top:hover {
  background: rgba(118, 179, 64, 0.15);
}

@media (max-width: 960px) {
  .header__nav {
    display: none;
  }

  .hero__content {
    padding: 0 1.5rem;
  }

  .hero__content > div {
    margin-top: 2rem;
  }

  .cta__content {
    align-items: flex-start;
    text-align: left;
  }
}

@media (max-width: 640px) {
  .header__cta {
    display: none;
  }

  .hero__art {
    height: 540px;
  }

  .hero__content {
    align-items: flex-end;
    padding-bottom: 3rem;
  }

  .activities {
    padding-top: 4rem;
  }

  .cta__content {
    padding: 2.5rem 2rem;
  }

  .scroll-top {
    bottom: 16px;
    right: 16px;
  }
}
</style>
