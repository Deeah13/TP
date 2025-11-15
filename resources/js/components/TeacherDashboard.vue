<template>
  <TeacherWorkspaceLayout
    :notifications="notifications"
    notification-heading="Notifikasi"
    notification-subheading="Terbaru minggu ini"
    @logout="handleLogout"
  >
    <section class="dashboard__hero" aria-label="Sambutan guru">
      <div class="dashboard__hero-copy">
        <p class="dashboard__hero-eyebrow">Selamat Datang,</p>
        <h1>{{ greetingName }}</h1>
        <p>
          Kelola kegiatan belajar mengajar, pantau perkembangan siswa, dan akses materi pembelajaran secara terpadu di Terminal Pintar.
        </p>
        <div class="dashboard__hero-actions">
          <button type="button" @click="openSchedule('create-schedule')">
            <span>+ Buat Jadwal Baru</span>
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 5v14M5 12h14" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" /></svg>
          </button>
          <button type="button" class="dashboard__hero-secondary" @click="toAttendance">
            Pantau Kehadiran
          </button>
        </div>
      </div>
      <article class="dashboard__next-class" role="status">
        <span>Mapel Berikutnya</span>
        <h2>{{ nextSession.subject }}</h2>
        <p>{{ nextSession.topic }}</p>
        <footer>{{ nextSession.schedule }}</footer>
      </article>
    </section>

    <section class="dashboard__summary" aria-label="Ringkasan kelas">
      <article
        v-for="card in summaryCards"
        :key="card.title"
        class="dashboard-summary"
        :style="{ '--accent': card.accent }"
      >
        <span class="dashboard-summary__meta">{{ card.meta }}</span>
        <div class="dashboard-summary__icon">
          <component :is="card.icon" />
        </div>
        <strong>{{ card.value }}</strong>
        <p>{{ card.title }}</p>
        <small>{{ card.description }}</small>
      </article>
    </section>

    <section class="dashboard__quick" aria-label="Aksi cepat guru">
      <header>
        <h2>Aksi Cepat</h2>
        <p>Mulai sesi, unggah materi, dan catat perkembangan siswa secara instan.</p>
      </header>
      <div class="dashboard__quick-grid">
        <article
          v-for="action in quickActions"
          :key="action.title"
          class="dashboard-quick"
          :style="{ '--accent': action.accent }"
        >
          <div class="dashboard-quick__icon">
            <component :is="action.icon" />
          </div>
          <div class="dashboard-quick__content">
            <h3>{{ action.title }}</h3>
            <p>{{ action.description }}</p>
          </div>
          <button type="button" @click="action.action()">
            <span>{{ action.cta }}</span>
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" /></svg>
          </button>
        </article>
      </div>
    </section>

    <section class="dashboard__schedule" aria-label="Jadwal pembelajaran">
      <header>
        <div>
          <h2>Agenda Minggu Ini</h2>
          <p>Tinjau sesi yang akan datang dan siapkan materi pendukungnya.</p>
        </div>
        <button type="button" @click="openSchedule('create-schedule')">Kelola Jadwal</button>
      </header>
      <ul>
        <li v-for="session in sessions" :key="session.id" class="dashboard-session">
          <div class="dashboard-session__time">
            <span>{{ session.day }}</span>
            <strong>{{ session.date }}</strong>
          </div>
          <div class="dashboard-session__details">
            <h3>{{ session.subject }}</h3>
            <p>{{ session.topic }}</p>
          </div>
          <div class="dashboard-session__meta">
            <span class="dashboard-session__badge" :style="{ background: session.badge.bg, color: session.badge.color }">
              {{ session.badge.label }}
            </span>
            <button type="button" @click="goToMaterial(session)">Kelola Materi</button>
          </div>
        </li>
      </ul>
    </section>
  </TeacherWorkspaceLayout>
</template>

<script setup>
import { computed } from 'vue';
import { useRouter } from 'vue-router';
import TeacherWorkspaceLayout from './teacher/TeacherWorkspaceLayout.vue';
import { useAuth } from '../stores/auth';
import { createIcon } from '../utils/iconFactory';

const router = useRouter();
const { user, logout } = useAuth();

const greetingName = computed(() => user.value?.shortName ?? user.value?.name ?? 'Guru Terminal Pintar');

const nextSession = {
  subject: 'Bahasa Indonesia',
  topic: 'Menulis Narasi Kreatif',
  schedule: 'Senin, 20 Oktober 2025 • 09.00 WIB',
};

const summaryCards = [
  {
    title: 'Total Siswa',
    value: '120',
    description: 'Terdaftar pada kelas Anda',
    meta: 'Seluruh kelas',
    accent: 'linear-gradient(135deg, rgba(118,179,64,0.25), rgba(118,179,64,0.05))',
    icon: createIcon({ d: 'M12 12a5 5 0 10-5-5 5 5 0 005 5zm0 2c-3.33 0-10 1.67-10 5v1h20v-1c0-3.33-6.67-5-10-5z', fill: 'currentColor' }),
  },
  {
    title: 'Sesi Minggu Ini',
    value: '8',
    description: 'Telah terjadwal dan siap dijalankan',
    meta: 'Terjadwal',
    accent: 'linear-gradient(135deg, rgba(96,125,255,0.22), rgba(96,125,255,0.1))',
    icon: createIcon({ d: 'M7 3h10a2 2 0 012 2v16l-7-3-7 3V5a2 2 0 012-2z', fill: 'currentColor' }),
  },
  {
    title: 'Penilaian Siswa',
    value: '15 / 30',
    description: 'Rapor formatif yang sudah diperbarui',
    meta: 'Progres',
    accent: 'linear-gradient(135deg, rgba(244,143,177,0.2), rgba(255,255,255,0.08))',
    icon: createIcon({ d: 'M5 3a2 2 0 00-2 2v14l5-3 5 3 5-3 5 3V5a2 2 0 00-2-2zM7 8h10v2H7zm0 4h6v2H7z', fill: 'currentColor' }),
  },
  {
    title: 'Materi Terunggah',
    value: '24',
    description: 'Dokumen dan media siap dibagikan',
    meta: 'Perpustakaan',
    accent: 'linear-gradient(135deg, rgba(255,193,7,0.22), rgba(255,255,255,0.1))',
    icon: createIcon({ d: 'M14 2H6a2 2 0 00-2 2v16l4-4h8a2 2 0 002-2V4a2 2 0 00-2-2zm6 4v9h-2V6z', fill: 'currentColor' }),
  },
];

const quickActions = computed(() => [
  {
    title: 'Tambah Sesi',
    description: 'Rancang jadwal pembelajaran baru untuk kelas Anda.',
    cta: 'Mulai',
    accent: 'rgba(118,179,64,0.18)',
    icon: createIcon({
      d: 'M12 5v14M5 12h14',
      fill: 'none',
      stroke: 'currentColor',
      'stroke-width': '1.6',
      'stroke-linecap': 'round',
      'stroke-linejoin': 'round',
    }),
    action: () => openSchedule('create-schedule'),
  },
  {
    title: 'Unggah Materi',
    description: 'Tambahkan modul dan referensi terbaru untuk siswa.',
    cta: 'Unggah',
    accent: 'rgba(96,125,255,0.18)',
    icon: createIcon({
      d: 'M4 17v2h16v-2M12 5v10m0-10l-4 4m4-4l4 4',
      fill: 'none',
      stroke: 'currentColor',
      'stroke-width': '1.6',
      'stroke-linecap': 'round',
      'stroke-linejoin': 'round',
    }),
    action: () => openSchedule('create-material'),
  },
  {
    title: 'Catat Kehadiran',
    description: 'Segera update kehadiran dan catatan siswa.',
    cta: 'Buka',
    accent: 'rgba(255,193,7,0.18)',
    icon: createIcon({
      d: 'M5 4a2 2 0 00-2 2v14l4-2.5L11 20l4-2.5L19 20V6a2 2 0 00-2-2zm2 4h10v2H7zm0 4h6v2H7z',
      fill: 'currentColor',
    }),
    action: () => router.push({ name: 'teacher-attendance' }),
  },
  {
    title: 'Evaluasi Siswa',
    description: 'Susun catatan perkembangan untuk pertemuan berikutnya.',
    cta: 'Lihat',
    accent: 'rgba(244,143,177,0.18)',
    icon: createIcon({
      d: 'M5 5h14v14H5z',
      fill: 'none',
      stroke: 'currentColor',
      'stroke-width': '1.6',
      'stroke-linecap': 'round',
      'stroke-linejoin': 'round',
    }),
    action: () => router.push({ name: 'teacher-attendance', query: { focus: 'notes' } }),
  },
]);

const sessions = [
  {
    id: 'session-1',
    day: 'Senin',
    date: '21 Okt 2025',
    subject: 'Bahasa Indonesia',
    topic: 'Menulis Narasi Kreatif',
    badge: { label: 'Tersisa 2 hari', bg: 'rgba(96,125,255,0.12)', color: '#4154d1' },
  },
  {
    id: 'session-2',
    day: 'Rabu',
    date: '23 Okt 2025',
    subject: 'Matematika',
    topic: 'Bangun Ruang & Aplikasinya',
    badge: { label: 'Butuh Materi', bg: 'rgba(244,143,177,0.18)', color: '#b8326a' },
  },
  {
    id: 'session-3',
    day: 'Jumat',
    date: '25 Okt 2025',
    subject: 'Ilmu Pengetahuan Alam',
    topic: 'Eksperimen Energi Terbarukan',
    badge: { label: 'Terjadwal', bg: 'rgba(118,179,64,0.18)', color: '#3f6f2a' },
  },
];

const notifications = [
  {
    id: 'notif-1',
    title: 'Materi berhasil diunggah',
    subtitle: 'Modul “Bangun Ruang” siap dibagikan ke siswa',
    status: 'success',
  },
  {
    id: 'notif-2',
    title: 'Laporan kehadiran terkirim',
    subtitle: 'Rekap minggu lalu sudah tersimpan di arsip',
    status: 'info',
  },
];

const openSchedule = (modal) => {
  router.push({ name: 'teacher-schedule', query: modal ? { modal } : {} });
};

const goToMaterial = (session) => {
  router.push({ name: 'teacher-schedule', query: { focus: 'materials', session: session.id } });
};

const toAttendance = () => {
  router.push({ name: 'teacher-attendance' });
};

const handleLogout = async () => {
  await logout();
  router.replace({ name: 'login', query: { role: 'teacher' } });
};
</script>

<style scoped>
.dashboard__hero {
  display: grid;
  grid-template-columns: minmax(0, 1fr) minmax(240px, 320px);
  gap: 2.5rem;
  padding: 2.25rem;
  border-radius: 28px;
  background: linear-gradient(135deg, rgba(118, 179, 64, 0.18), rgba(96, 125, 255, 0.15));
  border: 1px solid rgba(118, 179, 64, 0.2);
  box-shadow: 0 22px 70px -38px rgba(26, 43, 70, 0.55);
  backdrop-filter: blur(12px);
}

.dashboard__hero-copy > p {
  margin: 0;
  color: rgba(31, 31, 31, 0.7);
  line-height: 1.6;
}

.dashboard__hero-eyebrow {
  margin: 0;
  font-size: 0.9rem;
  font-weight: 600;
  color: rgba(31, 31, 31, 0.65);
  text-transform: uppercase;
  letter-spacing: 0.08em;
}

.dashboard__hero h1 {
  margin: 0.2rem 0 1rem;
  font-size: 2.2rem;
  color: #1f2937;
}

.dashboard__hero-actions {
  margin-top: 1.75rem;
  display: flex;
  flex-wrap: wrap;
  gap: 0.85rem;
}

.dashboard__hero-actions button {
  display: inline-flex;
  align-items: center;
  gap: 0.65rem;
  padding: 0.8rem 1.4rem;
  border-radius: 16px;
  border: none;
  font-weight: 600;
  cursor: pointer;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.dashboard__hero-actions button span {
  display: inline-flex;
  align-items: center;
}

.dashboard__hero-actions button svg {
  width: 18px;
  height: 18px;
}

.dashboard__hero-actions button:first-of-type {
  background: rgba(118, 179, 64, 0.18);
  color: #2f6721;
  box-shadow: 0 18px 40px -24px rgba(47, 103, 33, 0.45);
}

.dashboard__hero-actions button:first-of-type:hover {
  transform: translateY(-2px);
}

.dashboard__hero-secondary {
  background: rgba(255, 255, 255, 0.85);
  color: rgba(31, 31, 31, 0.75);
  border: 1px solid rgba(118, 179, 64, 0.18);
}

.dashboard__hero-secondary:hover {
  transform: translateY(-2px);
  box-shadow: 0 16px 36px -24px rgba(31, 41, 55, 0.3);
}

.dashboard__next-class {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  padding: 1.75rem;
  border-radius: 22px;
  background: rgba(255, 255, 255, 0.78);
  border: 1px solid rgba(255, 255, 255, 0.4);
  box-shadow: 0 20px 60px -35px rgba(23, 43, 77, 0.5);
}

.dashboard__next-class span {
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.08em;
  color: rgba(31, 31, 31, 0.55);
}

.dashboard__next-class h2 {
  margin: 0;
  font-size: 1.35rem;
  color: #2f4f2f;
}

.dashboard__next-class p {
  margin: 0;
  color: rgba(31, 31, 31, 0.65);
}

.dashboard__next-class footer {
  margin-top: auto;
  font-size: 0.85rem;
  color: rgba(31, 31, 31, 0.55);
}

.dashboard__summary {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 1.5rem;
}

.dashboard-summary {
  position: relative;
  padding: 1.6rem;
  border-radius: 24px;
  background: rgba(255, 255, 255, 0.85);
  border: 1px solid rgba(118, 179, 64, 0.12);
  box-shadow: 0 16px 48px -28px rgba(17, 24, 39, 0.45);
  overflow: hidden;
}

.dashboard-summary::before {
  content: '';
  position: absolute;
  inset: 0;
  background: var(--accent);
  opacity: 0.95;
  z-index: -1;
}

.dashboard-summary__meta {
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  font-size: 0.75rem;
  text-transform: uppercase;
  letter-spacing: 0.08em;
  color: rgba(31, 31, 31, 0.55);
}

.dashboard-summary__icon {
  margin: 0.85rem 0;
  width: 48px;
  height: 48px;
  border-radius: 16px;
  background: rgba(255, 255, 255, 0.55);
  color: #4a7c2c;
  display: inline-flex;
  align-items: center;
  justify-content: center;
}

.dashboard-summary strong {
  font-size: 2rem;
  color: #1f2937;
}

.dashboard-summary p {
  margin: 0.4rem 0 0;
  font-weight: 600;
  color: rgba(31, 31, 31, 0.78);
}

.dashboard-summary small {
  display: block;
  margin-top: 0.6rem;
  color: rgba(31, 31, 31, 0.6);
}

.dashboard__quick header {
  display: flex;
  flex-direction: column;
  gap: 0.35rem;
}

.dashboard__quick header h2 {
  margin: 0;
  font-size: 1.4rem;
  color: #1f2937;
}

.dashboard__quick header p {
  margin: 0;
  color: rgba(31, 31, 31, 0.6);
}

.dashboard__quick-grid {
  margin-top: 1.5rem;
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 1.3rem;
}

.dashboard-quick {
  position: relative;
  display: flex;
  flex-direction: column;
  gap: 0.85rem;
  padding: 1.4rem;
  border-radius: 22px;
  background: rgba(255, 255, 255, 0.9);
  border: 1px solid rgba(118, 179, 64, 0.12);
  box-shadow: 0 18px 48px -32px rgba(23, 43, 77, 0.45);
}

.dashboard-quick::before {
  content: '';
  position: absolute;
  inset: 0;
  border-radius: inherit;
  background: var(--accent);
  opacity: 0.85;
  z-index: -1;
}

.dashboard-quick__icon {
  width: 40px;
  height: 40px;
  border-radius: 14px;
  background: rgba(255, 255, 255, 0.55);
  color: #4a7c2c;
  display: inline-flex;
  align-items: center;
  justify-content: center;
}

.dashboard-quick__content h3 {
  margin: 0;
  font-size: 1.1rem;
  color: #1f2937;
}

.dashboard-quick__content p {
  margin: 0.35rem 0 0;
  color: rgba(31, 31, 31, 0.6);
  line-height: 1.5;
}

.dashboard-quick button {
  margin-top: auto;
  display: inline-flex;
  align-items: center;
  justify-content: space-between;
  gap: 0.5rem;
  padding: 0.65rem 1rem;
  border-radius: 14px;
  border: none;
  background: rgba(255, 255, 255, 0.88);
  color: #2f6721;
  font-weight: 600;
  cursor: pointer;
}

.dashboard-quick button svg {
  width: 18px;
  height: 18px;
}

.dashboard__schedule {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
  padding: 1.75rem;
  border-radius: 24px;
  background: rgba(255, 255, 255, 0.92);
  border: 1px solid rgba(118, 179, 64, 0.12);
  box-shadow: 0 20px 60px -38px rgba(23, 43, 77, 0.45);
}

.dashboard__schedule header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 1rem;
}

.dashboard__schedule header h2 {
  margin: 0;
  font-size: 1.35rem;
  color: #1f2937;
}

.dashboard__schedule header p {
  margin: 0.2rem 0 0;
  color: rgba(31, 31, 31, 0.6);
}

.dashboard__schedule header button {
  padding: 0.65rem 1.2rem;
  border-radius: 14px;
  border: none;
  background: rgba(118, 179, 64, 0.18);
  color: #2f6721;
  font-weight: 600;
  cursor: pointer;
}

.dashboard__schedule ul {
  list-style: none;
  margin: 0;
  padding: 0;
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.dashboard-session {
  display: grid;
  grid-template-columns: 140px 1fr auto;
  gap: 1.25rem;
  align-items: center;
  padding: 1.1rem 1.35rem;
  border-radius: 18px;
  border: 1px solid rgba(118, 179, 64, 0.12);
  background: rgba(255, 255, 255, 0.9);
}

.dashboard-session__time span {
  display: block;
  font-size: 0.78rem;
  text-transform: uppercase;
  letter-spacing: 0.08em;
  color: rgba(31, 31, 31, 0.55);
}

.dashboard-session__time strong {
  font-size: 1.1rem;
  color: #1f2937;
}

.dashboard-session__details h3 {
  margin: 0;
  font-size: 1.1rem;
  color: #1f2937;
}

.dashboard-session__details p {
  margin: 0.25rem 0 0;
  color: rgba(31, 31, 31, 0.6);
}

.dashboard-session__meta {
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  gap: 0.65rem;
}

.dashboard-session__badge {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 0.35rem 0.75rem;
  border-radius: 999px;
  font-size: 0.75rem;
  font-weight: 600;
}

.dashboard-session__meta button {
  padding: 0.5rem 0.9rem;
  border-radius: 12px;
  border: none;
  background: rgba(118, 179, 64, 0.15);
  color: #2f6721;
  font-weight: 600;
  cursor: pointer;
}

@media (max-width: 960px) {
  .dashboard__hero {
    grid-template-columns: 1fr;
  }

  .dashboard__next-class {
    order: -1;
  }

  .dashboard-session {
    grid-template-columns: 1fr;
    align-items: flex-start;
  }

  .dashboard-session__meta {
    align-items: flex-start;
  }
}

@media (max-width: 640px) {
  .dashboard__hero {
    padding: 1.8rem;
  }

  .dashboard__schedule header {
    flex-direction: column;
    align-items: flex-start;
  }
}
</style>
