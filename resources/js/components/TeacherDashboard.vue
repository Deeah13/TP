<template>
  <div class="teacher" :class="{ 'teacher--scrolled': isScrolled }">
    <header ref="headerRef" class="teacher__header" :class="{ 'teacher__header--glass': isScrolled }" role="banner">
      <div class="teacher__brand">
        <img class="teacher__logo" :src="assets.logo" alt="Logo Terminal Pintar" />
        <div class="teacher__brand-text">
          <span class="teacher__brand-name">{{ organizationName }}</span>
          <span class="teacher__brand-sub">{{ roleLabel }}</span>
        </div>
      </div>
      <nav class="teacher__top-nav" aria-label="Navigasi utama guru">
        <button type="button" class="teacher__language" aria-label="Ubah bahasa">
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3a9 9 0 109 9 9.01 9.01 0 00-9-9zm6.93 8h-2.7a13.19 13.19 0 00-.9-4.45 7.01 7.01 0 012.6 4.45zM12 5a11.48 11.48 0 011.13 6h-2.26A11.48 11.48 0 0112 5zM5.07 11a7.01 7.01 0 012.6-4.45A13.19 13.19 0 006.77 11zm0 2h2.7a13.19 13.19 0 00.9 4.45A7.01 7.01 0 015.07 13zm6.93 6a11.48 11.48 0 01-1.13-6h2.26A11.48 11.48 0 0112 19zm3.3-1.55a13.19 13.19 0 00.9-4.45h2.7a7.01 7.01 0 01-3.6 4.45z" /></svg>
          <span>Bahasa Indonesia</span>
        </button>
      </nav>
      <div class="teacher__actions">
        <button
          ref="notificationTrigger"
          type="button"
          class="teacher__icon-button"
          aria-haspopup="true"
          :aria-expanded="showNotifications"
          aria-controls="notification-panel"
          @click.stop="toggleNotifications"
        >
          <img :src="assets.bell" alt="Notifikasi" />
          <span class="teacher__indicator" aria-hidden="true"></span>
        </button>
        <button
          ref="profileTrigger"
          type="button"
          class="teacher__profile"
          aria-haspopup="true"
          :aria-expanded="showProfile"
          aria-controls="profile-panel"
          @click.stop="toggleProfile"
        >
          <img :src="profileAvatar" :alt="`Profil ${profileName}`" />
        </button>
      </div>

      <transition name="teacher-fade">
        <section
          v-if="showNotifications"
          id="notification-panel"
          ref="notificationPanel"
          class="teacher__popover teacher__popover--notifications"
          role="status"
        >
          <header>
            <h2>Notifikasi</h2>
            <p>Terbaru minggu ini</p>
          </header>
          <ul>
            <li v-for="notification in notifications" :key="notification.id">
              <span class="teacher__bullet" :class="`teacher__bullet--${notification.status}`"></span>
              <div>
                <p class="teacher__popover-title">{{ notification.title }}</p>
                <p class="teacher__popover-sub">{{ notification.subtitle }}</p>
              </div>
            </li>
          </ul>
          <button type="button" class="teacher__popover-action">Tandai semua telah dibaca</button>
        </section>
      </transition>

      <transition name="teacher-fade">
        <section
          v-if="showProfile"
          id="profile-panel"
          ref="profilePanel"
          class="teacher__popover teacher__popover--profile"
          role="dialog"
          aria-label="Informasi profil guru"
        >
          <img :src="profileAvatar" :alt="`Avatar ${profileName}`" />
          <p class="teacher__profile-name">{{ profileName }}</p>
          <p class="teacher__profile-email">{{ profileEmail }}</p>
          <button type="button" class="teacher__logout" @click="handleLogout">Keluar</button>
        </section>
      </transition>
    </header>

    <div class="teacher__layout">
      <aside class="teacher__sidebar" aria-label="Navigasi samping">
        <div class="teacher__sidebar-section">
          <p class="teacher__sidebar-label">Menu Utama</p>
          <ul>
            <li v-for="item in mainMenu" :key="item.label" class="teacher__sidebar-item" :class="{ 'teacher__sidebar-item--active': item.active }">
              <span class="teacher__sidebar-icon" :style="{ background: item.accent }">
                <component :is="item.icon"></component>
              </span>
              <span>{{ item.label }}</span>
            </li>
          </ul>
        </div>
        <div class="teacher__sidebar-section">
          <p class="teacher__sidebar-label">Manajemen Akademik</p>
          <ul>
            <li v-for="item in academicMenu" :key="item.label" class="teacher__sidebar-item">
              <span class="teacher__sidebar-icon teacher__sidebar-icon--outline">
                <component :is="item.icon"></component>
              </span>
              <div class="teacher__sidebar-text">
                <span>{{ item.label }}</span>
                <small>{{ item.subtitle }}</small>
              </div>
            </li>
          </ul>
        </div>
      </aside>

      <main class="teacher__main" role="main">
        <section class="teacher__welcome">
          <div>
            <p class="teacher__welcome-eyebrow">Selamat Datang,</p>
            <h1>{{ greetingName }}</h1>
            <p class="teacher__welcome-copy">
              Kelola kegiatan belajar mengajar dan pantau performa siswa di {{ organizationName }}.
            </p>
          </div>
          <div class="teacher__next-class">
            <span class="teacher__next-label">Mapel berikutnya</span>
            <p>Bahasa Indonesia</p>
            <span>Senin, 20 Oktober 2025 • 09.00 WIB</span>
          </div>
        </section>

        <section class="teacher__summary" aria-label="Statistik kelas">
          <article
            v-for="card in summaryCards"
            :key="card.title"
            class="teacher__summary-card"
            :style="{ '--summary-accent': card.accent }"
          >
            <div class="teacher__summary-head">
              <span class="teacher__summary-icon">
                <component :is="card.icon"></component>
              </span>
              <span class="teacher__summary-meta">{{ card.meta }}</span>
            </div>
            <h2>{{ card.value }}</h2>
            <p>{{ card.title }}</p>
            <footer>{{ card.description }}</footer>
          </article>
        </section>

        <section class="teacher__quick" aria-label="Aksi cepat">
          <header>
            <h2>Aksi Cepat</h2>
            <p>Mulai sesi, unggah materi, dan pantau evaluasi dalam satu tempat.</p>
          </header>
          <div class="teacher__quick-grid">
            <article
              v-for="action in quickActions"
              :key="action.title"
              class="teacher__quick-card"
              :style="{ '--quick-accent': action.accent }"
            >
              <span class="teacher__quick-icon">
                <component :is="action.icon"></component>
              </span>
              <div>
                <h3>{{ action.title }}</h3>
                <p>{{ action.description }}</p>
              </div>
              <button type="button">
                <span>{{ action.cta }}</span>
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" /></svg>
              </button>
            </article>
          </div>
        </section>

        <section class="teacher__schedule" aria-label="Jadwal pembelajaran">
          <header>
            <div>
              <h2>Jadwal Pembelajaran</h2>
              <p>Tinjau sesi pekan ini dan status kehadiran siswa.</p>
            </div>
            <button type="button">Kelola Jadwal</button>
          </header>
          <ul>
            <li v-for="session in sessions" :key="session.id" class="teacher__session">
              <div class="teacher__session-time">
                <p>{{ session.day }}</p>
                <span>{{ session.date }}</span>
              </div>
              <div class="teacher__session-details">
                <h3>{{ session.subject }}</h3>
                <p>{{ session.topic }}</p>
              </div>
              <div class="teacher__session-meta">
                <span class="teacher__session-badge" :style="{ background: session.badge.bg, color: session.badge.color }">{{ session.badge.label }}</span>
                <span class="teacher__session-stats">{{ session.attendance }}</span>
              </div>
            </li>
          </ul>
        </section>
      </main>
    </div>
  </div>
</template>

<script setup>
import { computed, h, markRaw, onBeforeUnmount, onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuth } from '../stores/auth';

const createIcon = (...paths) =>
  markRaw({
    render() {
      return h(
        'svg',
        {
          viewBox: '0 0 24 24',
          'aria-hidden': 'true',
        },
        paths.map((attrs) => h('path', attrs)),
      );
    },
  });

const assets = {
  logo: 'https://images.unsplash.com/photo-1618005198919-d3d4b5a92eee?auto=format&fit=crop&w=120&q=80',
  bell: 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgc3Ryb2tlPSIjNkE3QjM0IiBzdHJva2Utd2lkdGg9IjEuNSIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIj48cGF0aCBkPSJNMTIgMjBhMiAyIDAgMCAwIDQtMiI+PC9wYXRoPjxwYXRoIGQ9Ik0zLjg2IDE3SDIwLjEzYTEgMSAwIDAwLjg3LTEuNTRBMTEuMDMgMTEuMDMgMCAwMDIwIDExYzAtNS41My00LjQ3LTEwLTEwLTEwUzkgNS40NyA5IDExYTExLjAzIDExLjAzIDAgMDAtNS4wMSA0LjQ2IDEgMSAwIDAwLjg3IDEuNTR6Ij48L3BhdGg+PC9zdmc+',
  avatarFallback: 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=200&q=80',
};

const router = useRouter();
const { user, logout } = useAuth();

const organizationName = computed(() => user.value?.organization ?? 'Terminal Pintar');
const roleLabel = computed(() => {
  switch (user.value?.role) {
    case 'teacher':
      return 'Dashboard Guru';
    case 'admin':
      return 'Dashboard Admin';
    case 'parent':
      return 'Dashboard Orang Tua';
    default:
      return 'Dashboard Pengguna';
  }
});

const profileName = computed(() => user.value?.name ?? 'Pengguna Terminal Pintar');
const profileEmail = computed(() => user.value?.email ?? 'guru@terminalpintar.id');
const profileAvatar = computed(() => user.value?.avatar ?? assets.avatarFallback);
const greetingName = computed(() => user.value?.shortName ?? user.value?.name ?? 'Guru Terminal Pintar');

const notifications = [
  {
    id: 'notif-1',
    title: 'Materi berhasil diunggah',
    subtitle: 'Minggu ini terdapat 5 materi yang berhasil diunggah',
    status: 'success',
  },
  {
    id: 'notif-2',
    title: 'Laporan Penilaian Siswa',
    subtitle: 'Sebanyak 1 siswa telah dinilai untuk pembelajaran minggu ini',
    status: 'info',
  },
];

const mainMenu = [
  {
    label: 'Beranda',
    accent: 'linear-gradient(135deg, rgba(118,179,64,0.85), rgba(99,144,57,0.75))',
    icon: createIcon({ d: 'M4 10.5L12 4l8 6.5V20a1 1 0 01-1 1h-5v-6h-4v6H5a1 1 0 01-1-1z', fill: 'currentColor' }),
    active: true,
  },
];

const academicMenu = [
  {
    label: 'Jadwal & Materi',
    subtitle: 'Atur pertemuan mingguan',
    icon: createIcon({ d: 'M6 4h12a2 2 0 012 2v2H4V6a2 2 0 012-2zm14 6v8a2 2 0 01-2 2H6a2 2 0 01-2-2v-8zm-5 3h-6v2h6z', fill: 'currentColor' }),
  },
  {
    label: 'Kehadiran',
    subtitle: 'Rekap absensi kelas',
    icon: createIcon({ d: 'M12 12a5 5 0 10-5-5 5 5 0 005 5zm0 2c-3.33 0-10 1.67-10 5v1h20v-1c0-3.33-6.67-5-10-5z', fill: 'currentColor' }),
  },
  {
    label: 'Penilaian',
    subtitle: 'Input nilai & catatan',
    icon: createIcon({ d: 'M4 3h16a1 1 0 011 1v13.59l-4-4a1 1 0 00-1.42 0L13 20H4a1 1 0 01-1-1V4a1 1 0 011-1zm5 6H7v2h2zm0-4H7v2h2zm8 0h-6v2h6zm0 4h-6v2h6z', fill: 'currentColor' }),
  },
];

const summaryCards = computed(() => [
  {
    title: 'Total Siswa',
    value: '30',
    description: 'Siswa aktif dalam bimbingan Anda',
    meta: '100% aktif',
    accent: 'linear-gradient(135deg, rgba(118,179,64,0.35), rgba(118,179,64,0.15))',
    icon: createIcon({ d: 'M16 11c1.66 0 2.99-1.79 2.99-4S17.66 3 16 3s-3 1.79-3 4 1.34 4 3 4zm-8 0c1.66 0 2.99-1.79 2.99-4S9.66 3 8 3s-3 1.79-3 4 1.34 4 3 4zm0 2c-2.33 0-7 1.17-7 3.5V20h14v-3.5C15 14.17 10.33 13 8 13zm8 0a6.23 6.23 0 00-1.52.19A4.52 4.52 0 0119 17.5V20h5v-3.5C24 14.17 19.33 13 16 13z', fill: 'currentColor' }),
  },
  {
    title: 'Sesi Minggu Ini',
    value: '2 / 10',
    description: 'Sesi sudah terlaksana hingga hari ini',
    meta: '20% selesai',
    accent: 'linear-gradient(135deg, rgba(235,146,50,0.35), rgba(235,146,50,0.1))',
    icon: createIcon({ d: 'M7 2v2H5a2 2 0 00-2 2v2h18V6a2 2 0 00-2-2h-2V2h-2v2H9V2zm13 6H4v10a2 2 0 002 2h12a2 2 0 002-2zM7 12h5v5H7z', fill: 'currentColor' }),
  },
  {
    title: 'Penilaian Siswa',
    value: '15 / 30',
    description: 'Rapor formatif yang sudah diperbarui',
    meta: '50% tercatat',
    accent: 'linear-gradient(135deg, rgba(118,179,64,0.2), rgba(80,120,220,0.12))',
    icon: createIcon({ d: 'M5 3a2 2 0 00-2 2v14l5-3 5 3 5-3 5 3V5a2 2 0 00-2-2zm2 5h10v2H7zm0 4h6v2H7z', fill: 'currentColor' }),
  },
  {
    title: 'Materi Terunggah',
    value: '5',
    description: 'Materi baru minggu ini',
    meta: '100% terpublikasi',
    accent: 'linear-gradient(135deg, rgba(70,130,180,0.3), rgba(255,255,255,0.05))',
    icon: createIcon({ d: 'M14 2H6a2 2 0 00-2 2v16l4-4h8a2 2 0 002-2V4a2 2 0 00-2-2zm6 4v9h-2V6z', fill: 'currentColor' }),
  },
]);

const quickActions = [
  {
    title: 'Buat Sesi Baru',
    description: 'Tambahkan jadwal dan sesi baru untuk kelas Anda.',
    cta: 'Mulai',
    accent: 'rgba(118,179,64,0.18)',
    icon: createIcon({
      d: 'M12 5v14m7-7H5',
      fill: 'none',
      stroke: 'currentColor',
      'stroke-width': '2',
      'stroke-linecap': 'round',
      'stroke-linejoin': 'round',
    }),
  },
  {
    title: 'Upload Materi',
    description: 'Unggah file dan media pembelajaran terbaru.',
    cta: 'Unggah',
    accent: 'rgba(96,125,255,0.18)',
    icon: createIcon({
      d: 'M4 17v2h16v-2M12 5v10m0-10l-4 4m4-4l4 4',
      fill: 'none',
      stroke: 'currentColor',
      'stroke-width': '2',
      'stroke-linecap': 'round',
      'stroke-linejoin': 'round',
    }),
  },
  {
    title: 'Laporan Kehadiran',
    description: 'Pantau kehadiran siswa dalam sekali klik.',
    cta: 'Lihat',
    accent: 'rgba(255,193,7,0.18)',
    icon: createIcon({ d: 'M5 3h14a2 2 0 012 2v14l-4-3-4 3-4-3-4 3V5a2 2 0 012-2z', fill: 'currentColor' }),
  },
  {
    title: 'Riwayat Penilaian',
    description: 'Tampilkan penilaian guru terhadap siswa secara ringkas.',
    cta: 'Tinjau',
    accent: 'rgba(244,143,177,0.18)',
    icon: createIcon(
      {
        d: 'M9 11l3 3L22 4',
        fill: 'none',
        stroke: 'currentColor',
        'stroke-width': '2',
        'stroke-linecap': 'round',
        'stroke-linejoin': 'round',
      },
      {
        d: 'M2 20h20',
        fill: 'none',
        stroke: 'currentColor',
        'stroke-width': '2',
        'stroke-linecap': 'round',
        'stroke-linejoin': 'round',
      },
    ),
  },
];

const sessions = [
  {
    id: 'session-1',
    day: '17 Oktober 2025',
    date: 'Matematika',
    subject: 'Aljabar Dasar',
    topic: 'Persamaan Linear Satu Variabel',
    attendance: '28 hadir • 2 izin',
    badge: {
      label: 'Akan Datang',
      bg: 'rgba(118,179,64,0.16)',
      color: '#4A7C2C',
    },
  },
  {
    id: 'session-2',
    day: '18 Oktober 2025',
    date: 'Bahasa Indonesia',
    subject: 'Teks Narasi',
    topic: 'Eksplorasi Cerita Pendek',
    attendance: '30 hadir',
    badge: {
      label: 'Hari Ini',
      bg: 'rgba(96,125,255,0.18)',
      color: '#4051B5',
    },
  },
  {
    id: 'session-3',
    day: '19 Oktober 2025',
    date: 'IPA Terpadu',
    subject: 'Sistem Tata Surya',
    topic: 'Simulasi Gerak Planet',
    attendance: '29 hadir • 1 sakit',
    badge: {
      label: 'Disiapkan',
      bg: 'rgba(255,193,7,0.18)',
      color: '#B28704',
    },
  },
];

const isScrolled = ref(false);
const showNotifications = ref(false);
const showProfile = ref(false);

const headerRef = ref(null);
const notificationPanel = ref(null);
const profilePanel = ref(null);
const notificationTrigger = ref(null);
const profileTrigger = ref(null);

const handleScroll = () => {
  isScrolled.value = window.scrollY > 24;
};

const closeOverlays = () => {
  showNotifications.value = false;
  showProfile.value = false;
};

const toggleNotifications = () => {
  showNotifications.value = !showNotifications.value;
  if (showNotifications.value) {
    showProfile.value = false;
  }
};

const toggleProfile = () => {
  showProfile.value = !showProfile.value;
  if (showProfile.value) {
    showNotifications.value = false;
  }
};

const handleLogout = () => {
  showProfile.value = false;
  logout();
  router.replace({ name: 'login' });
};

const handleClickOutside = (event) => {
  const elements = [
    notificationPanel.value,
    profilePanel.value,
    notificationTrigger.value,
    profileTrigger.value,
  ];

  if (elements.every((el) => !el || !el.contains(event.target))) {
    closeOverlays();
  }
};

const handleEscape = (event) => {
  if (event.key === 'Escape') {
    closeOverlays();
  }
};

onMounted(() => {
  if (typeof window !== 'undefined') {
    window.addEventListener('scroll', handleScroll, { passive: true });
  }

  if (typeof document !== 'undefined') {
    document.addEventListener('click', handleClickOutside);
    document.addEventListener('keydown', handleEscape);
  }
});

onBeforeUnmount(() => {
  if (typeof window !== 'undefined') {
    window.removeEventListener('scroll', handleScroll);
  }

  if (typeof document !== 'undefined') {
    document.removeEventListener('click', handleClickOutside);
    document.removeEventListener('keydown', handleEscape);
  }
});
</script>

<style scoped>
.teacher {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  background: linear-gradient(160deg, #f6f9ff 0%, #fef7ff 55%, #f3fbf0 100%);
  color: #1f1f1f;
  transition: background 0.4s ease;
}

.teacher__header {
  position: sticky;
  top: 0;
  z-index: 100;
  display: grid;
  grid-template-columns: auto 1fr auto;
  align-items: center;
  gap: 1.5rem;
  padding: 1rem 3vw;
  background: rgba(255, 255, 255, 0.92);
  border-bottom: 1px solid rgba(120, 174, 78, 0.12);
  transition: backdrop-filter 0.3s ease, box-shadow 0.3s ease, background 0.3s ease;
}

.teacher__header--glass {
  background: rgba(255, 255, 255, 0.75);
  backdrop-filter: blur(16px) saturate(130%);
  box-shadow: 0 10px 40px -20px rgba(31, 41, 55, 0.35);
}

.teacher__brand {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.teacher__logo {
  width: 40px;
  height: 40px;
  border-radius: 12px;
  object-fit: cover;
  box-shadow: 0 8px 20px rgba(118, 179, 64, 0.25);
}

.teacher__brand-text {
  display: flex;
  flex-direction: column;
}

.teacher__brand-name {
  font-size: 1.125rem;
  font-weight: 600;
  color: #4a7c2c;
}

.teacher__brand-sub {
  font-size: 0.75rem;
  color: rgba(31, 31, 31, 0.6);
}

.teacher__top-nav {
  display: flex;
  justify-content: flex-end;
}

.teacher__language {
  display: inline-flex;
  align-items: center;
  gap: 0.65rem;
  padding: 0.6rem 1.1rem;
  background: rgba(255, 255, 255, 0.9);
  border: 1px solid rgba(118, 179, 64, 0.24);
  border-radius: 999px;
  font-size: 0.85rem;
  color: #4a7c2c;
  font-weight: 500;
  cursor: pointer;
  transition: background 0.2s ease, box-shadow 0.2s ease;
}

.teacher__language svg {
  width: 20px;
  height: 20px;
  fill: #4a7c2c;
}

.teacher__language:hover {
  background: rgba(118, 179, 64, 0.1);
  box-shadow: 0 12px 24px -12px rgba(118, 179, 64, 0.35);
}

.teacher__actions {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.teacher__icon-button,
.teacher__profile {
  position: relative;
  width: 42px;
  height: 42px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border-radius: 16px;
  border: 1px solid rgba(118, 179, 64, 0.18);
  background: rgba(255, 255, 255, 0.85);
  box-shadow: 0 12px 32px -18px rgba(79, 114, 68, 0.45);
  cursor: pointer;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.teacher__icon-button:hover,
.teacher__profile:hover {
  transform: translateY(-2px);
  box-shadow: 0 18px 40px -18px rgba(79, 114, 68, 0.5);
}

.teacher__icon-button img {
  width: 20px;
  height: 20px;
}

.teacher__indicator {
  position: absolute;
  top: 8px;
  right: 8px;
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background: #ef4444;
  box-shadow: 0 0 0 2px #fff;
}

.teacher__profile img {
  width: 100%;
  height: 100%;
  border-radius: 15px;
  object-fit: cover;
}

.teacher__popover {
  position: absolute;
  top: calc(100% + 0.75rem);
  right: 3vw;
  min-width: 260px;
  padding: 1.25rem;
  border-radius: 18px;
  background: rgba(255, 255, 255, 0.92);
  box-shadow: 0 20px 45px -25px rgba(15, 23, 42, 0.45);
  border: 1px solid rgba(118, 179, 64, 0.12);
  backdrop-filter: blur(20px);
}

.teacher__popover header h2 {
  margin: 0;
  font-size: 1rem;
  font-weight: 600;
}

.teacher__popover header p {
  margin: 0.25rem 0 1rem;
  font-size: 0.75rem;
  color: rgba(31, 31, 31, 0.6);
}

.teacher__popover ul {
  margin: 0 0 1.25rem;
  padding: 0;
  list-style: none;
  display: flex;
  flex-direction: column;
  gap: 0.85rem;
}

.teacher__popover ul li {
  display: flex;
  gap: 0.75rem;
}

.teacher__bullet {
  width: 10px;
  height: 10px;
  border-radius: 50%;
  margin-top: 0.25rem;
}

.teacher__bullet--success {
  background: #22c55e;
}

.teacher__bullet--info {
  background: #60a5fa;
}

.teacher__popover-title {
  margin: 0;
  font-weight: 600;
  font-size: 0.9rem;
}

.teacher__popover-sub {
  margin: 0.15rem 0 0;
  color: rgba(31, 31, 31, 0.6);
  font-size: 0.8rem;
}

.teacher__popover-action {
  width: 100%;
  padding: 0.6rem;
  border-radius: 12px;
  border: 1px solid rgba(118, 179, 64, 0.3);
  background: rgba(118, 179, 64, 0.08);
  color: #4a7c2c;
  font-weight: 500;
  cursor: pointer;
}

.teacher__popover--profile {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.5rem;
}

.teacher__profile-name {
  margin: 0;
  font-weight: 600;
}

.teacher__profile-email {
  margin: 0;
  font-size: 0.85rem;
  color: rgba(31, 31, 31, 0.65);
}

.teacher__logout {
  margin-top: 0.5rem;
  width: 100%;
  padding: 0.65rem;
  border-radius: 12px;
  border: 1px solid rgba(239, 68, 68, 0.2);
  background: rgba(239, 68, 68, 0.1);
  color: #ef4444;
  font-weight: 600;
  cursor: pointer;
}

.teacher__layout {
  display: grid;
  grid-template-columns: 260px 1fr;
  gap: 2.5rem;
  padding: 2rem 3vw 3rem;
}

.teacher__sidebar {
  position: sticky;
  top: 96px;
  align-self: start;
  display: flex;
  flex-direction: column;
  gap: 1.75rem;
}

.teacher__sidebar-section {
  background: rgba(255, 255, 255, 0.8);
  border-radius: 20px;
  padding: 1.2rem 1.4rem;
  border: 1px solid rgba(118, 179, 64, 0.12);
  box-shadow: 0 18px 48px -30px rgba(57, 70, 62, 0.4);
  backdrop-filter: blur(16px);
}

.teacher__sidebar-label {
  font-size: 0.8rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.08em;
  color: rgba(31, 31, 31, 0.5);
  margin-bottom: 1rem;
}

.teacher__sidebar-section ul {
  list-style: none;
  margin: 0;
  padding: 0;
  display: flex;
  flex-direction: column;
  gap: 0.85rem;
}

.teacher__sidebar-item {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.65rem 0.75rem;
  border-radius: 16px;
  font-weight: 500;
  color: rgba(31, 31, 31, 0.75);
  transition: background 0.2s ease, transform 0.2s ease;
}

.teacher__sidebar-item--active {
  background: rgba(118, 179, 64, 0.15);
  color: #4a7c2c;
}

.teacher__sidebar-item:hover {
  transform: translateX(4px);
  background: rgba(118, 179, 64, 0.1);
}

.teacher__sidebar-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 36px;
  height: 36px;
  border-radius: 12px;
  color: #fff;
}

.teacher__sidebar-icon svg {
  width: 18px;
  height: 18px;
}

.teacher__sidebar-icon--outline {
  background: rgba(255, 255, 255, 0.9);
  border: 1px solid rgba(118, 179, 64, 0.25);
  color: #4a7c2c;
}

.teacher__sidebar-text {
  display: flex;
  flex-direction: column;
  line-height: 1.2;
}

.teacher__sidebar-text small {
  font-size: 0.7rem;
  color: rgba(31, 31, 31, 0.55);
}

.teacher__main {
  display: flex;
  flex-direction: column;
  gap: 2.5rem;
}

.teacher__welcome {
  display: grid;
  grid-template-columns: 1fr auto;
  gap: 2rem;
  align-items: center;
  padding: 2rem;
  border-radius: 28px;
  background: linear-gradient(135deg, rgba(118, 179, 64, 0.18), rgba(96, 125, 255, 0.15));
  border: 1px solid rgba(118, 179, 64, 0.2);
  box-shadow: 0 20px 60px -35px rgba(22, 36, 64, 0.55);
  backdrop-filter: blur(12px);
}

.teacher__welcome-eyebrow {
  margin: 0;
  font-size: 0.9rem;
  font-weight: 600;
  color: rgba(31, 31, 31, 0.65);
}

.teacher__welcome h1 {
  margin: 0.2rem 0 0.75rem;
  font-size: 2.1rem;
  color: #1f2937;
}

.teacher__welcome-copy {
  margin: 0;
  max-width: 520px;
  color: rgba(31, 31, 31, 0.7);
  line-height: 1.6;
}

.teacher__next-class {
  display: flex;
  flex-direction: column;
  gap: 0.35rem;
  padding: 1.5rem 1.75rem;
  border-radius: 20px;
  background: rgba(255, 255, 255, 0.75);
  border: 1px solid rgba(255, 255, 255, 0.4);
  box-shadow: 0 18px 40px -28px rgba(23, 43, 77, 0.5);
}

.teacher__next-label {
  font-size: 0.75rem;
  text-transform: uppercase;
  letter-spacing: 0.08em;
  color: rgba(31, 31, 31, 0.5);
}

.teacher__next-class p {
  margin: 0;
  font-weight: 600;
  color: #2f4f2f;
  font-size: 1.05rem;
}

.teacher__next-class span {
  font-size: 0.85rem;
  color: rgba(31, 31, 31, 0.6);
}

.teacher__summary {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 1.5rem;
}

.teacher__summary-card {
  position: relative;
  padding: 1.5rem;
  border-radius: 24px;
  background: rgba(255, 255, 255, 0.8);
  border: 1px solid rgba(118, 179, 64, 0.12);
  box-shadow: 0 16px 48px -28px rgba(17, 24, 39, 0.45);
  overflow: hidden;
}

.teacher__summary-card::before {
  content: '';
  position: absolute;
  inset: 0;
  background: var(--summary-accent);
  opacity: 0.9;
  z-index: -1;
}

.teacher__summary-head {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1.2rem;
}

.teacher__summary-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  border-radius: 14px;
  background: rgba(255, 255, 255, 0.55);
  color: #4a7c2c;
}

.teacher__summary-icon svg {
  width: 22px;
  height: 22px;
}

.teacher__summary-meta {
  font-size: 0.75rem;
  color: rgba(31, 31, 31, 0.6);
}

.teacher__summary-card h2 {
  margin: 0;
  font-size: 1.9rem;
  color: #1f2937;
}

.teacher__summary-card p {
  margin: 0.35rem 0 0.65rem;
  color: rgba(31, 31, 31, 0.65);
}

.teacher__summary-card footer {
  font-size: 0.78rem;
  color: rgba(31, 31, 31, 0.55);
}

.teacher__quick header {
  display: flex;
  flex-direction: column;
  gap: 0.35rem;
  margin-bottom: 1.5rem;
}

.teacher__quick header h2 {
  margin: 0;
  font-size: 1.4rem;
}

.teacher__quick header p {
  margin: 0;
  color: rgba(31, 31, 31, 0.6);
}

.teacher__quick-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
  gap: 1.25rem;
}

.teacher__quick-card {
  position: relative;
  display: grid;
  grid-template-columns: auto 1fr;
  grid-template-rows: auto auto;
  gap: 0.75rem 1rem;
  padding: 1.35rem;
  border-radius: 22px;
  background: rgba(255, 255, 255, 0.82);
  border: 1px solid rgba(118, 179, 64, 0.1);
  box-shadow: 0 18px 44px -30px rgba(18, 32, 55, 0.5);
  backdrop-filter: blur(14px);
}

.teacher__quick-card::before {
  content: '';
  position: absolute;
  inset: 0;
  border-radius: 22px;
  background: var(--quick-accent);
  opacity: 0.7;
  z-index: -1;
}

.teacher__quick-icon {
  grid-row: span 2;
  align-self: flex-start;
  width: 44px;
  height: 44px;
  border-radius: 14px;
  background: rgba(255, 255, 255, 0.6);
  display: inline-flex;
  align-items: center;
  justify-content: center;
  color: #4a7c2c;
}

.teacher__quick-icon svg {
  width: 22px;
  height: 22px;
}

.teacher__quick-card h3 {
  margin: 0;
  font-size: 1rem;
}

.teacher__quick-card p {
  margin: 0;
  color: rgba(31, 31, 31, 0.6);
  font-size: 0.9rem;
}

.teacher__quick-card button {
  grid-column: span 2;
  justify-self: flex-start;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  margin-top: 0.5rem;
  padding: 0.55rem 1rem;
  border-radius: 999px;
  border: none;
  background: rgba(74, 124, 44, 0.12);
  color: #3d6b23;
  font-weight: 600;
  cursor: pointer;
}

.teacher__quick-card button svg {
  width: 18px;
  height: 18px;
}

.teacher__schedule {
  background: rgba(255, 255, 255, 0.9);
  border-radius: 28px;
  border: 1px solid rgba(118, 179, 64, 0.16);
  box-shadow: 0 22px 60px -35px rgba(17, 24, 39, 0.48);
  padding: 2rem;
  backdrop-filter: blur(10px);
}

.teacher__schedule header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 1rem;
  margin-bottom: 1.75rem;
}

.teacher__schedule header h2 {
  margin: 0;
  font-size: 1.4rem;
}

.teacher__schedule header p {
  margin: 0.25rem 0 0;
  color: rgba(31, 31, 31, 0.6);
}

.teacher__schedule header button {
  padding: 0.6rem 1.2rem;
  border-radius: 12px;
  border: 1px solid rgba(118, 179, 64, 0.25);
  background: rgba(118, 179, 64, 0.12);
  color: #4a7c2c;
  font-weight: 600;
  cursor: pointer;
}

.teacher__schedule ul {
  list-style: none;
  margin: 0;
  padding: 0;
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.teacher__session {
  display: grid;
  grid-template-columns: 160px 1fr auto;
  gap: 1.5rem;
  align-items: center;
  padding: 1.2rem 1.5rem;
  border-radius: 20px;
  background: rgba(255, 255, 255, 0.75);
  border: 1px solid rgba(118, 179, 64, 0.12);
  box-shadow: 0 18px 45px -30px rgba(15, 23, 42, 0.4);
}

.teacher__session-time p {
  margin: 0;
  font-weight: 600;
  color: #1f2937;
}

.teacher__session-time span {
  font-size: 0.85rem;
  color: rgba(31, 31, 31, 0.55);
}

.teacher__session-details h3 {
  margin: 0;
  font-size: 1.05rem;
  color: #2f4f2f;
}

.teacher__session-details p {
  margin: 0.35rem 0 0;
  color: rgba(31, 31, 31, 0.6);
}

.teacher__session-meta {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  align-items: flex-end;
}

.teacher__session-badge {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 0.35rem 0.75rem;
  border-radius: 999px;
  font-size: 0.75rem;
  font-weight: 600;
}

.teacher__session-stats {
  font-size: 0.85rem;
  color: rgba(31, 31, 31, 0.6);
}

.teacher-fade-enter-active,
.teacher-fade-leave-active {
  transition: opacity 0.18s ease, transform 0.18s ease;
}

.teacher-fade-enter-from,
.teacher-fade-leave-to {
  opacity: 0;
  transform: translateY(-6px);
}

@media (max-width: 1120px) {
  .teacher__layout {
    grid-template-columns: 1fr;
  }

  .teacher__sidebar {
    position: static;
    flex-direction: row;
    overflow-x: auto;
    gap: 1rem;
  }

  .teacher__sidebar-section {
    min-width: 260px;
  }

  .teacher__welcome {
    grid-template-columns: 1fr;
  }

  .teacher__session {
    grid-template-columns: 1fr;
    align-items: flex-start;
  }

  .teacher__session-meta {
    flex-direction: row;
    justify-content: space-between;
    width: 100%;
  }
}

@media (max-width: 768px) {
  .teacher__header {
    grid-template-columns: 1fr;
    justify-items: center;
    text-align: center;
  }

  .teacher__top-nav,
  .teacher__actions {
    justify-content: center;
  }

  .teacher__layout {
    padding: 1.5rem 6vw 2.5rem;
  }
}
</style>
