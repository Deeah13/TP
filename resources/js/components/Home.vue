<template>
  <main class="HOME" id="beranda">
    <header class="navigation" role="banner">
      <img class="logo-terminal-pintar" :src="assets.logo" alt="Logo Terminal Pintar" />
      <h1 class="text-wrapper-18">Terminal Pintar</h1>
      <nav class="frame-2" aria-label="Navigasi Utama">
        <a href="#kegiatan" class="text-wrapper-22" @click.prevent="scrollTo('kegiatan')">Kegiatan</a>
      </nav>
      <a href="#tentang-kami" class="text-wrapper-20" @click.prevent="scrollTo('tentang-kami')">Tentang Kami</a>
      <a href="#kontak" class="text-wrapper-21" @click.prevent="scrollTo('kontak')">Kontak</a>
      <button class="bell" type="button" aria-label="Notifikasi">
        <span aria-hidden="true">🔔</span>
      </button>
      <a class="button" href="/login">
        <span class="text-wrapper-19">Login</span>
      </a>
    </header>

    <section class="hero-section">
      <img
        class="a-importncia-do"
        :src="assets.hero"
        alt="Anak-anak belajar bersama di Terminal Pintar"
      />
      <div class="hero-content">
        <h2 class="text-wrapper-23">Halo, Selamat Datang di Terminal Pintar!</h2>
        <p class="text-wrapper-26">
          Mari bergabung bersama kami untuk belajar, berbagi inspirasi, menjadi relawan, atau berdonasi demi mendukung
          masa depan yang lebih baik.
        </p>
        <button class="cart-button-4" type="button" @click="scrollTo('kegiatan')">
          <span class="text-wrapper-31">Jelajah lebih lanjut</span>
        </button>
      </div>
    </section>

    <section id="kegiatan" class="activities-section">
      <h2 class="text-wrapper-24">Dokumentasi Kegiatan Terbaru Kami</h2>

      <article
        v-for="(activity, index) in activities"
        :key="activity.slug"
        :class="activity.cardClass"
      >
        <img class="buket" :src="activity.image" :alt="activity.title" />
        <h3 class="text-wrapper-27">{{ activity.title }}</h3>
        <time class="text-wrapper-28" :datetime="activity.isoDate">{{ activity.date }}</time>
        <p :class="activity.copyClass">{{ activity.description }}</p>
        <RouterLink
          :class="activity.ctaClass"
          role="button"
          :to="{ name: 'news-detail', params: { slug: activity.slug } }"
        >
          <span class="text-wrapper-2">Baca Selengkapnya</span>
        </RouterLink>
      </article>

      <RouterLink class="cart-button-3" :to="{ name: 'news' }" role="button">
        <span class="text-wrapper-30">Lihat Semua Kegiatan</span>
      </RouterLink>
    </section>

    <section id="tentang-kami" class="frame">
      <h2 class="text-wrapper-12">Tentang Kami</h2>
      <img class="line" :src="assets.divider" alt="" role="presentation" />

      <article class="tomato-3">
        <h3 class="text-wrapper-8">SEJARAH</h3>
        <p class="p">
          Dibangun pada tanggal 17 Agustus 1502 berdasarkan perintah Kaisar dari Dinasti Ming. Pendiri diberikan
          kewenangan untuk mengurus komunitas dengan bantuan penuh dari Kaisar dan kemudian komunitas ini makin
          membesar dan terkenal di masyarakat umum. Beberapa tahun setelahnya, banyak relawan yang ikut meramaikan
          komunitas ini dan memberi bantuan yang sangat berarti untuk mencerdasakan kehidupan bangsa.
        </p>
        <img class="download-donation-3" :src="assets.history" alt="Pendiri Terminal Pintar" />
        <p class="text-wrapper-6">Andi Mohan</p>
        <p class="text-wrapper-7">Pendiri Terminal Pintar</p>
      </article>

      <article class="tomato-4">
        <h3 class="text-wrapper-9">VISI</h3>
        <p class="text-wrapper-10">
          Visi yang diusung adalah menjadikan Terminal Pintar sebagai "teman perjalanan" bagi anak-anak untuk
          menemukan kembali arti rumah, pendidikan, dan harapan. Artinya, tempat ini tidak dimaksudkan menggantikan
          sekolah formal, melainkan melengkapi dengan nuansa yang lebih hangat, menyenangkan, dan relevan dengan
          kehidupan mereka.
        </p>
        <img class="img" :src="assets.vision" alt="Ilustrasi visi Terminal Pintar" />
        <p class="text-wrapper-6">Andi Mohan</p>
        <p class="text-wrapper-7">Pendiri Terminal Pintar</p>
      </article>

      <div class="team-members">
        <figure class="team-member" v-for="member in team" :key="member.name">
          <img class="team-photo" :src="member.image" :alt="member.name" />
          <figcaption>
            <p :class="member.nameClass">{{ member.name }}</p>
            <p :class="member.roleClass">{{ member.role }}</p>
          </figcaption>
        </figure>
      </div>
    </section>

    <section class="contribution-section">
      <h2 class="text-wrapper-25">Mari Berkontribusi Membangun Harapan!</h2>
      <article class="tomato">
        <img class="download-donation" :src="assets.donation" alt="Ikon Donasi" />
        <h3 class="text-wrapper">DONASI</h3>
        <p class="div">
          Mari ikut berdonasi untuk membantu mereka yang membutuhkan. Dukungan Anda sangat berarti..
        </p>
        <button class="cart-button" type="button">
          <span class="text-wrapper-2">Baca Selengkapnya</span>
        </button>
        <button class="div-wrapper" type="button">
          <span class="text-wrapper-3">Lihat Cara Berdonasi</span>
        </button>
      </article>
      <article class="tomato-2">
        <img class="download-donation" :src="assets.volunteer" alt="Ikon Relawan" />
        <h3 class="text-wrapper-4">RELAWAN</h3>
        <p class="div">
          Mari ikut menjadi relawan untuk membantu mereka yang membutuhkan. Bantuan Anda sangat berarti..
        </p>
        <button class="cart-button" type="button">
          <span class="text-wrapper-2">Baca Selengkapnya</span>
        </button>
        <button class="cart-button-2" type="button">
          <span class="text-wrapper-5">Lihat Cara Mendaftar</span>
        </button>
      </article>
    </section>

    <footer id="kontak" class="frame-3">
      <section class="contact-info">
        <h2 class="text-wrapper-38">Kontak Kami</h2>
        <address>
          <div class="group">
            <img class="group-2" :src="assets.phone" alt="" aria-hidden="true" />
            <a href="tel:+6289123456789" class="text-wrapper-36">+6289123456789</a>
          </div>
          <div class="group-3">
            <img class="gmail-logo" :src="assets.gmail" alt="Gmail" />
            <a href="mailto:terminalpintar@gmail.com" class="text-wrapper-39">terminalpintar@gmail.com</a>
          </div>
        </address>
      </section>
      <section class="social-media">
        <h2 class="text-wrapper-37">Media Sosial</h2>
        <nav aria-label="Media Sosial">
          <ul>
            <li>
              <a
                href="https://instagram.com/terminalpintar_id"
                target="_blank"
                rel="noopener noreferrer"
                class="social-link"
              >
                <img class="instagram" :src="assets.instagram" alt="Instagram" />
                <span class="text-wrapper-33">@terminalpintar_id</span>
              </a>
            </li>
            <li>
              <a
                href="https://tiktok.com/@terminalpintar"
                target="_blank"
                rel="noopener noreferrer"
                class="social-link"
              >
                <img class="tik-tok" :src="assets.tiktok" alt="TikTok" />
                <span class="text-wrapper-34">@terminalpintar</span>
              </a>
            </li>
            <li>
              <a
                href="https://youtube.com/@terminalpintar"
                target="_blank"
                rel="noopener noreferrer"
                class="social-link"
              >
                <img class="you-tube" :src="assets.youtube" alt="YouTube" />
                <span class="text-wrapper-35">Terminal Pintar</span>
              </a>
            </li>
          </ul>
        </nav>
      </section>
    </footer>

    <button class="arrow-up-circle" type="button" aria-label="Kembali ke atas" @click="scrollTo('beranda')">
      <img class="icon" :src="assets.scrollUp" alt="" aria-hidden="true" />
    </button>
  </main>
</template>

<script setup>
import { computed } from 'vue';
import { RouterLink } from 'vue-router';
import { newsItems } from '../data/news';

const assets = {
  logo: 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=200&q=80',
  hero:
    'https://images.unsplash.com/photo-1509062522246-3755977927d7?q=80&w=1600&auto=format&fit=crop',
  divider: 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNDAiIGhlaWdodD0iMiIgdmlld0JveD0iMCAwIDI0MCAyIj48cmVjdCB3aWR0aD0iMjQwIiBoZWlnaHQ9IjIiIGZpbGw9IiNkMGQ1ZGQiLz48L3N2Zz4=',
  history: 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=800&auto=format&fit=crop',
  vision: 'https://images.unsplash.com/photo-1496307042754-b4aa456c4a2d?q=80&w=800&auto=format&fit=crop',
  donation: 'https://images.unsplash.com/photo-1521790361543-f645cf042ec4?q=80&w=600&auto=format&fit=crop',
  volunteer: 'https://images.unsplash.com/photo-1493836512294-502baa1986e2?q=80&w=600&auto=format&fit=crop',
  phone: 'https://img.icons8.com/ios-filled/50/000000/phone.png',
  gmail: 'https://img.icons8.com/color/48/gmail--v1.png',
  instagram: 'https://img.icons8.com/fluency/48/instagram-new.png',
  tiktok: 'https://img.icons8.com/fluency/48/tiktok.png',
  youtube: 'https://img.icons8.com/color/48/youtube-play.png',
  scrollUp: 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCIgdmlld0JveD0iMCAwIDQwIDQwIj48Y2lyY2xlIGN4PSIyMCIgY3k9IjIwIiByPSIyMCIgZmlsbD0iI2ZmZmZmZiIvPjxwYXRoIGQ9Ik0yMCAxMGwtNi41IDhoNC41djEyaDR2LTEyaDQuNXoiIGZpbGw9IiM0Y2FmNTAiLz48L3N2Zz4=',
};

const summaryOverrides = {
  'workshop-coding-untuk-anak':
    'Anak-anak belajar dasar-dasar programming dengan cara yang menyenangkan',
  'kelas-bahasa-interaktif':
    'Sesi pembelajaran bahasa inggris dengan metode storytelling dan games',
  'perpustakaan-mini-dibuka':
    'Terminal Pintar kini memiliki perpustakaan mini dengan 200+ buku untuk anak-anak',
};

const cardClasses = ['tomato-5', 'tomato-6', 'tomato-7'];
const copyClasses = ['text-wrapper-29', 'text-wrapper-32', 'text-wrapper-29'];
const ctaClasses = ['cart-button', 'cart-button-5', 'cart-button'];

const activities = computed(() =>
  newsItems.slice(0, 3).map((item, index) => ({
    slug: item.slug,
    title: item.title,
    date: item.date,
    isoDate: item.detail.isoDate,
    description: summaryOverrides[item.slug] || item.summary,
    image: item.image,
    cardClass: cardClasses[index] || 'tomato-5',
    copyClass: copyClasses[index] || 'text-wrapper-29',
    ctaClass: ctaClasses[index] || 'cart-button',
  }))
);

const team = [
  {
    name: 'Andi Rahmadi',
    role: 'Pengurus',
    image: 'https://images.unsplash.com/photo-1527980965255-d3b416303d12?q=80&w=400&auto=format&fit=crop',
    nameClass: 'text-wrapper-11',
    roleClass: 'text-wrapper-13',
  },
  {
    name: 'Nabila Nurahani',
    role: 'Pengajar',
    image: 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?q=80&w=400&auto=format&fit=crop',
    nameClass: 'text-wrapper-14',
    roleClass: 'text-wrapper-15',
  },
  {
    name: 'Hanifah Ahmada',
    role: 'Pengajar',
    image: 'https://images.unsplash.com/photo-1544723795-3fb6469f5b39?q=80&w=400&auto=format&fit=crop',
    nameClass: 'text-wrapper-16',
    roleClass: 'text-wrapper-17',
  },
];

const scrollTo = (id) => {
  const target = document.getElementById(id);

  if (target) {
    target.scrollIntoView({ behavior: 'smooth' });
  }
};
</script>

<style scoped>
.HOME {
  background-color: #fef7ff;
  border: 8px solid #cac4d0;
  border-radius: 24px;
  color: #1f2933;
  display: flex;
  flex-direction: column;
  font-family: 'Poppins', 'Plus Jakarta Sans', system-ui, sans-serif;
  min-height: 100vh;
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
  grid-template-columns: auto auto 1fr auto auto auto auto;
  padding: 1rem 2.5rem;
}

.logo-terminal-pintar {
  border-radius: 12px;
  height: 48px;
  object-fit: cover;
  width: 48px;
}

.text-wrapper-18 {
  color: #2a2a2a;
  font-size: 1.5rem;
  font-weight: 700;
  margin: 0;
}

.frame-2,
.text-wrapper-20,
.text-wrapper-21 {
  display: flex;
  align-items: center;
}

.text-wrapper-22 {
  display: inline-flex;
  align-items: center;
}

.frame-2 a,
.text-wrapper-20,
.text-wrapper-21 {
  color: #4b5c72;
  font-size: 0.95rem;
  font-weight: 600;
  text-decoration: none;
  position: relative;
}

.frame-2 a::after,
.text-wrapper-20::after,
.text-wrapper-21::after {
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

.frame-2 a:hover::after,
.text-wrapper-20:hover::after,
.text-wrapper-21:hover::after {
  transform: scaleX(1);
}

.bell {
  align-items: center;
  background: #f2f2f7;
  border: none;
  border-radius: 50%;
  color: #4b5c72;
  cursor: pointer;
  display: inline-flex;
  font-size: 1.1rem;
  height: 40px;
  justify-content: center;
  width: 40px;
}

.button {
  background: linear-gradient(135deg, #78ae4e 0%, #4b8a13 100%);
  border-radius: 999px;
  color: #fff;
  padding: 0.65rem 1.75rem;
  text-decoration: none;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.button:hover {
  box-shadow: 0 10px 25px rgba(72, 137, 33, 0.35);
  transform: translateY(-2px);
}

.text-wrapper-19 {
  font-size: 0.95rem;
  font-weight: 600;
}

.hero-section {
  display: grid;
  grid-template-columns: 2fr 1fr;
  gap: 2rem;
  padding: 2.5rem 2.5rem 1.5rem;
}

.a-importncia-do {
  border-radius: 24px;
  height: 320px;
  object-fit: cover;
  width: 100%;
}

.hero-content {
  align-self: center;
  background: linear-gradient(180deg, rgba(120, 174, 78, 0.12), rgba(120, 174, 78, 0));
  border-radius: 24px;
  padding: 2rem;
}

.text-wrapper-23 {
  color: #78ae4e;
  font-size: 1.75rem;
  font-weight: 700;
  margin: 0 0 1rem;
}

.text-wrapper-26 {
  color: #4b5c72;
  font-size: 1rem;
  line-height: 1.7;
  margin: 0 0 1.5rem;
}

.cart-button-4 {
  background: linear-gradient(135deg, #78ae4e 0%, #4b8a13 100%);
  border: none;
  border-radius: 999px;
  color: #fff;
  cursor: pointer;
  padding: 0.9rem 2.2rem;
}

.text-wrapper-31 {
  font-size: 1rem;
  font-weight: 600;
}

.activities-section {
  align-items: flex-start;
  display: grid;
  gap: 1.5rem;
  grid-template-columns: repeat(3, minmax(0, 1fr));
  justify-items: stretch;
  padding: 2rem 2.5rem 1.5rem;
  position: relative;
}

.activities-section > h2 {
  grid-column: 1 / -1;
  margin: 0;
}

.text-wrapper-24 {
  color: #78ae4e;
  font-size: 1.4rem;
  font-weight: 700;
}

.tomato-5,
.tomato-6,
.tomato-7 {
  background: #fff;
  border-radius: 24px;
  box-shadow: 0 18px 50px rgba(31, 41, 51, 0.08);
  display: flex;
  flex-direction: column;
  padding: 1.5rem;
  position: relative;
}

.buket {
  border-radius: 18px;
  height: 170px;
  margin-bottom: 1.25rem;
  object-fit: cover;
  width: 100%;
}

.text-wrapper-27 {
  color: #1f2933;
  font-size: 1.1rem;
  font-weight: 700;
  margin: 0 0 0.5rem;
}

.text-wrapper-28 {
  color: #6d6d6d;
  font-size: 0.85rem;
  font-weight: 500;
  margin-bottom: 0.75rem;
}

.text-wrapper-29,
.text-wrapper-32 {
  color: #6d6d6d;
  font-size: 0.95rem;
  line-height: 1.6;
  margin-bottom: 1.25rem;
}

.cart-button,
.cart-button-5 {
  align-items: center;
  border: 1px solid #78ae4e;
  border-radius: 12px;
  display: inline-flex;
  justify-content: center;
  min-height: 44px;
  padding: 0 1.5rem;
  text-decoration: none;
  transition: background 0.2s ease, color 0.2s ease;
}

.cart-button {
  background: #fff;
  color: #78ae4e;
}

.cart-button-5 {
  background: #78ae4e;
  color: #fff;
}

.cart-button:hover,
.cart-button-5:hover {
  background: #4b8a13;
  color: #fff;
}

.text-wrapper-2 {
  font-size: 0.9rem;
  font-weight: 600;
}

.cart-button-3 {
  align-items: center;
  background: #78ae4e;
  border-radius: 999px;
  color: #fff;
  display: inline-flex;
  grid-column: 1 / -1;
  justify-self: center;
  margin-top: 1rem;
  min-height: 44px;
  padding: 0 2.5rem;
  text-decoration: none;
}

.text-wrapper-30 {
  font-size: 0.95rem;
  font-weight: 600;
}

.frame {
  background: linear-gradient(180deg, rgba(120, 174, 78, 0.1), rgba(255, 255, 255, 0));
  border-radius: 32px;
  display: grid;
  gap: 2rem;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  margin: 1.5rem 2.5rem 0;
  padding: 2.5rem;
}

.text-wrapper-12 {
  color: #1f2933;
  font-size: 1.5rem;
  font-weight: 700;
  grid-column: 1 / -1;
  margin: 0;
}

.line {
  display: none;
}

.tomato-3,
.tomato-4 {
  background: #fff;
  border-radius: 24px;
  box-shadow: 0 20px 40px rgba(31, 41, 51, 0.12);
  padding: 2rem;
  position: relative;
}

.text-wrapper-8,
.text-wrapper-9 {
  color: #78ae4e;
  font-size: 1.1rem;
  font-weight: 700;
  margin: 0 0 1rem;
}

.p,
.text-wrapper-10 {
  color: #4b5c72;
  font-size: 0.95rem;
  line-height: 1.8;
  margin-bottom: 1.75rem;
}

.download-donation-3,
.img {
  border-radius: 18px;
  height: 160px;
  object-fit: cover;
  width: 100%;
}

.text-wrapper-6 {
  color: #1f2933;
  font-size: 1rem;
  font-weight: 600;
  margin: 1.25rem 0 0.25rem;
}

.text-wrapper-7 {
  color: #6d6d6d;
  margin: 0;
}

.team-members {
  align-items: stretch;
  display: flex;
  gap: 1.5rem;
  grid-column: 1 / -1;
}

.team-member {
  align-items: center;
  background: #fff;
  border-radius: 20px;
  box-shadow: 0 18px 36px rgba(31, 41, 51, 0.1);
  display: flex;
  flex-direction: column;
  padding: 1.5rem;
  text-align: center;
}

.team-member img {
  border-radius: 14px;
  height: 120px;
  margin-bottom: 1rem;
  object-fit: cover;
  width: 120px;
}

.team-photo {
  border-radius: 14px;
  height: 120px;
  margin-bottom: 1rem;
  object-fit: cover;
  width: 120px;
}

.text-wrapper-11,
.text-wrapper-14,
.text-wrapper-16 {
  color: #1f2933;
  font-size: 1rem;
  font-weight: 600;
  margin: 0 0 0.25rem;
}

.text-wrapper-13,
.text-wrapper-15,
.text-wrapper-17 {
  color: #6d6d6d;
  font-size: 0.85rem;
  margin: 0;
}

.contribution-section {
  background: linear-gradient(180deg, rgba(120, 174, 78, 0.08), rgba(120, 174, 78, 0));
  display: grid;
  gap: 2rem;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  margin: 2rem 2.5rem 0;
  padding: 2.5rem;
}

.text-wrapper-25 {
  color: #1f2933;
  font-size: 1.5rem;
  font-weight: 700;
  grid-column: 1 / -1;
  margin: 0;
}

.tomato,
.tomato-2 {
  background: #fff;
  border-radius: 24px;
  box-shadow: 0 20px 40px rgba(31, 41, 51, 0.1);
  padding: 2rem;
  position: relative;
  text-align: center;
}

.download-donation {
  border-radius: 16px;
  height: 140px;
  margin-bottom: 1.25rem;
  object-fit: cover;
  width: 100%;
}

.text-wrapper,
.text-wrapper-4 {
  color: #78ae4e;
  font-size: 1.2rem;
  font-weight: 700;
  margin: 0 0 1rem;
}

.div {
  color: #4b5c72;
  font-size: 0.95rem;
  line-height: 1.6;
  margin-bottom: 1.5rem;
}

.div-wrapper,
.cart-button-2 {
  align-items: center;
  background: #f2f2f7;
  border: none;
  border-radius: 12px;
  color: #78ae4e;
  cursor: pointer;
  display: inline-flex;
  justify-content: center;
  min-height: 44px;
  padding: 0 1.5rem;
}

.frame-3 {
  background: #fff;
  border-top: 1px solid rgba(202, 196, 208, 0.4);
  display: grid;
  gap: 2rem;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  margin: 2.5rem 2.5rem;
  padding: 2.5rem;
}

.contact-info address {
  display: grid;
  gap: 1rem;
  font-style: normal;
}

.group,
.group-3 {
  align-items: center;
  display: flex;
  gap: 0.75rem;
}

.group-2,
.gmail-logo,
.instagram,
.tik-tok,
.you-tube {
  height: 28px;
  width: 28px;
}

.text-wrapper-36,
.text-wrapper-39,
.text-wrapper-33,
.text-wrapper-34,
.text-wrapper-35 {
  color: #4b5c72;
  font-size: 0.95rem;
  text-decoration: none;
}

.social-media ul {
  list-style: none;
  margin: 0;
  padding: 0;
  display: grid;
  gap: 1rem;
}

.social-link {
  align-items: center;
  display: inline-flex;
  gap: 0.75rem;
  text-decoration: none;
}

.social-link:hover span {
  color: #78ae4e;
}

.arrow-up-circle {
  align-items: center;
  background: #78ae4e;
  border: none;
  border-radius: 50%;
  bottom: 2rem;
  cursor: pointer;
  display: inline-flex;
  height: 48px;
  justify-content: center;
  position: sticky;
  margin-left: auto;
  margin-right: 2.5rem;
  width: 48px;
}

.arrow-up-circle .icon {
  height: 28px;
  width: 28px;
}

@media (max-width: 1280px) {
  .navigation {
    grid-template-columns: auto auto auto auto auto;
    grid-template-rows: repeat(2, auto);
  }

  .hero-section {
    grid-template-columns: 1fr;
  }

  .activities-section {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }

  .frame {
    grid-template-columns: 1fr;
  }

  .contribution-section {
    grid-template-columns: 1fr;
  }

  .frame-3 {
    grid-template-columns: 1fr;
  }
}
</style>
