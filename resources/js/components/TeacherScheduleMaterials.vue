<template>
  <div class="schedule" :class="{ 'schedule--scrolled': isScrolled }">
    <header ref="headerRef" class="schedule__header" :class="{ 'schedule__header--glass': isScrolled }" role="banner">
      <div class="schedule__brand">
        <img class="schedule__logo" :src="assets.logo" alt="Logo Terminal Pintar" />
        <div class="schedule__brand-text">
          <span class="schedule__brand-name">{{ organizationName }}</span>
          <span class="schedule__brand-sub">{{ roleLabel }}</span>
        </div>
      </div>
      <nav class="schedule__top-nav" aria-label="Navigasi utama guru">
        <button type="button" class="schedule__language" aria-label="Ubah bahasa">
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3a9 9 0 109 9 9.01 9.01 0 00-9-9zm6.93 8h-2.7a13.19 13.19 0 0 0-.9-4.45 7.01 7.01 0 012.6 4.45zM12 5a11.48 11.48 0 011.13 6h-2.26A11.48 11.48 0 0112 5zM5.07 11a7.01 7.01 0 012.6-4.45A13.19 13.19 0 006.77 11zm0 2h2.7a13.19 13.19 0 00.9 4.45A7.01 7.01 0 015.07 13zm6.93 6a11.48 11.48 0 01-1.13-6h2.26A11.48 11.48 0 0112 19zm3.3-1.55a13.19 13.19 0 00.9-4.45h2.7a7.01 7.01 0 01-3.6 4.45z" /></svg>
          <span>Bahasa Indonesia</span>
        </button>
      </nav>
      <div class="schedule__actions">
        <button
          ref="notificationTrigger"
          type="button"
          class="schedule__icon-button"
          aria-haspopup="true"
          :aria-expanded="showNotifications"
          aria-controls="notification-panel"
          @click.stop="toggleNotifications"
        >
          <img :src="assets.bell" alt="Notifikasi" />
          <span class="schedule__indicator" aria-hidden="true"></span>
        </button>
        <button
          ref="profileTrigger"
          type="button"
          class="schedule__profile"
          aria-haspopup="true"
          :aria-expanded="showProfile"
          aria-controls="profile-panel"
          @click.stop="toggleProfile"
        >
          <img :src="profileAvatar" :alt="`Profil ${profileName}`" />
        </button>
      </div>

      <transition name="schedule-fade">
        <section
          v-if="showNotifications"
          id="notification-panel"
          ref="notificationPanel"
          class="schedule__popover schedule__popover--notifications"
          role="status"
        >
          <header>
            <h2>Notifikasi</h2>
            <p>Aktivitas terbaru guru</p>
          </header>
          <ul>
            <li v-for="notification in notifications" :key="notification.id">
              <span class="schedule__bullet" :class="`schedule__bullet--${notification.status}`"></span>
              <div>
                <p class="schedule__popover-title">{{ notification.title }}</p>
                <p class="schedule__popover-sub">{{ notification.subtitle }}</p>
              </div>
            </li>
          </ul>
          <button type="button" class="schedule__popover-action">Tandai semua telah dibaca</button>
        </section>
      </transition>

      <transition name="schedule-fade">
        <section
          v-if="showProfile"
          id="profile-panel"
          ref="profilePanel"
          class="schedule__popover schedule__popover--profile"
          role="dialog"
          aria-label="Informasi profil guru"
        >
          <img :src="profileAvatar" :alt="`Avatar ${profileName}`" />
          <p class="schedule__profile-name">{{ profileName }}</p>
          <p class="schedule__profile-email">{{ profileEmail }}</p>
          <button type="button" class="schedule__logout" @click="handleLogout">Keluar</button>
        </section>
      </transition>
    </header>

    <div class="schedule__layout">
      <aside class="schedule__sidebar" aria-label="Navigasi samping">
        <div class="schedule__sidebar-section">
          <p class="schedule__sidebar-label">Menu Utama</p>
          <ul>
            <li
              v-for="item in mainMenu"
              :key="item.label"
              class="schedule__sidebar-item"
              :class="{ 'schedule__sidebar-item--active': item.active }"
            >
              <button type="button" class="schedule__sidebar-link" @click="handleSidebarClick(item)">
                <span class="schedule__sidebar-icon" :style="{ background: item.accent }">
                  <component :is="item.icon"></component>
                </span>
                <span>{{ item.label }}</span>
              </button>
            </li>
          </ul>
        </div>
        <div class="schedule__sidebar-section">
          <p class="schedule__sidebar-label">Manajemen Akademik</p>
          <ul>
            <li
              v-for="item in academicMenu"
              :key="item.label"
              class="schedule__sidebar-item"
              :class="{ 'schedule__sidebar-item--active': item.active }"
            >
              <button type="button" class="schedule__sidebar-link schedule__sidebar-link--wide" @click="handleSidebarClick(item)">
                <span class="schedule__sidebar-icon schedule__sidebar-icon--outline">
                  <component :is="item.icon"></component>
                </span>
                <div class="schedule__sidebar-text">
                  <span>{{ item.label }}</span>
                  <small>{{ item.subtitle }}</small>
                </div>
              </button>
            </li>
          </ul>
        </div>
        <div class="schedule__sidebar-section">
          <p class="schedule__sidebar-label">Kehadiran</p>
          <ul>
            <li
              v-for="item in attendanceMenu"
              :key="item.label"
              class="schedule__sidebar-item"
              :class="{ 'schedule__sidebar-item--active': item.active }"
            >
              <button type="button" class="schedule__sidebar-link schedule__sidebar-link--wide" @click="handleSidebarClick(item)">
                <span class="schedule__sidebar-icon schedule__sidebar-icon--outline">
                  <component :is="item.icon"></component>
                </span>
                <div class="schedule__sidebar-text">
                  <span>{{ item.label }}</span>
                  <small>{{ item.subtitle }}</small>
                </div>
              </button>
            </li>
          </ul>
        </div>
      </aside>

      <main class="schedule__main" role="main">
        <section class="schedule__intro">
          <div class="schedule__intro-content">
            <p class="schedule__intro-eyebrow">Kelola Jadwal & Materi</p>
            <h1>Unggah Jadwal dan Materi Pembelajaran</h1>
            <p>
              Catat seluruh pertemuan mingguan, tambahkan materi pendukung, dan berikan catatan khusus untuk siswa.
              Seluruh data tersinkron secara otomatis dengan dasbor guru.
            </p>
            <div class="schedule__intro-actions">
              <button type="button" class="schedule__intro-button" @click="openModal('create-schedule')">
                <span>+ Tambah Jadwal</span>
              </button>
              <button type="button" class="schedule__intro-button schedule__intro-button--ghost" @click="openModal('create-material', schedules[0]?.id)">
                Unggah Materi Baru
              </button>
            </div>
          </div>
          <div class="schedule__intro-card" role="status">
            <span>Status Jadwal Mingguan</span>
            <strong>8 dari 10 pertemuan telah tersusun</strong>
            <p>2 jadwal menunggu materi tambahan untuk dibagikan.</p>
            <button type="button" @click="openModal('create-material', schedules[0]?.id)">Lengkapi Materi</button>
          </div>
        </section>

        <section class="schedule__toolbar" aria-label="Pengaturan jadwal">
          <div class="schedule__filters">
            <label>
              <span>Bulan</span>
              <select>
                <option>Oktober 2025</option>
                <option>November 2025</option>
                <option>Desember 2025</option>
              </select>
            </label>
            <label>
              <span>Mata Pelajaran</span>
              <select>
                <option>Semua Mata Pelajaran</option>
                <option>Matematika</option>
                <option>Bahasa Indonesia</option>
                <option>Ilmu Pengetahuan Alam</option>
              </select>
            </label>
          </div>
          <div class="schedule__toolbar-actions">
            <button type="button" class="schedule__toolbar-button" @click="openModal('create-schedule')">+ Tambah Jadwal</button>
          </div>
        </section>

        <section class="schedule__list" aria-label="Daftar jadwal pembelajaran">
          <article v-for="session in schedules" :key="session.id" class="schedule-card">
            <header class="schedule-card__header">
              <div>
                <p class="schedule-card__date">{{ session.day }}</p>
                <p class="schedule-card__subject">{{ session.subject }}</p>
              </div>
              <span class="schedule-card__status" :style="{ color: session.status.color, background: session.status.background }">
                {{ session.status.label }}
              </span>
            </header>
            <div class="schedule-card__meta">
              <p><strong>Topik:</strong> {{ session.topic }}</p>
              <p><strong>Jam:</strong> {{ session.time }}</p>
              <p><strong>Lokasi:</strong> {{ session.location }}</p>
            </div>
            <p class="schedule-card__description">{{ session.description }}</p>

            <div class="schedule-card__materials" aria-label="Materi pembelajaran">
              <header>
                <h2>Materi Pembelajaran ({{ session.materials.length }} File)</h2>
                <span>{{ session.lastUpdated }}</span>
              </header>
              <ul>
                <li v-for="file in session.materials" :key="file.id">
                  <span class="schedule-card__file-icon" :class="`schedule-card__file-icon--${file.type}`">
                    <component :is="fileTypeIcons[file.type] || fileTypeIcons.generic"></component>
                  </span>
                  <div>
                    <p>{{ file.name }}</p>
                    <small>{{ file.size }}</small>
                  </div>
                  <button type="button">Unduh</button>
                </li>
              </ul>
              <footer>
                <div class="schedule-card__progress">
                  <div class="schedule-card__progress-bar" :style="{ width: session.progress + '%' }"></div>
                </div>
                <span>{{ session.progress }}% materi telah dibagikan</span>
              </footer>
            </div>

            <div class="schedule-card__actions">
              <button type="button" @click="openModal('edit-schedule', session.id)">Edit Jadwal</button>
              <button type="button" @click="openModal('edit-material', session.id)">Kelola Materi</button>
              <button type="button" class="schedule-card__actions--ghost" @click="goToAttendance(session)">Catatan</button>
            </div>
          </article>
        </section>
      </main>
    </div>

    <transition name="schedule-modal">
      <section v-if="isAnyModalOpen" class="schedule-modal" role="dialog" aria-modal="true">
        <div class="schedule-modal__backdrop" @click="closeModals()"></div>
        <div class="schedule-modal__content" role="document">
          <header>
            <h2>{{ modalTitle }}</h2>
            <p>{{ modalSubtitle }}</p>
          </header>

          <form v-if="activeModal === 'create-schedule'" class="schedule-form" @submit.prevent="handleSubmit('create-schedule')">
            <label>
              <span>Mata Pelajaran</span>
              <input type="text" placeholder="Masukkan nama Mata Pelajaran - Kegiatan" />
            </label>
            <div class="schedule-form__row">
              <label>
                <span>Hari, Tanggal</span>
                <input type="date" />
              </label>
              <label>
                <span>Jam Mulai</span>
                <input type="time" />
              </label>
              <label>
                <span>Jam Selesai</span>
                <input type="time" />
              </label>
            </div>
            <label>
              <span>Ruangan/Lokasi</span>
              <input type="text" placeholder="Isikan lokasi kegiatan belajar mengajar" />
            </label>
            <label>
              <span>Status Jadwal</span>
              <select>
                <option>Akan Datang</option>
                <option>Berlangsung</option>
                <option>Selesai</option>
              </select>
            </label>
            <label>
              <span>Topik Pembelajaran</span>
              <input type="text" placeholder="Contoh: Latihan membaca puisi modern" />
            </label>
            <label>
              <span>Deskripsi Singkat Materi</span>
              <textarea rows="3" placeholder="Tuliskan deskripsi materi yang diunggah.."></textarea>
            </label>
            <footer>
              <button type="button" class="schedule-form__ghost" @click="closeModals()">Batal</button>
              <button type="submit">Simpan</button>
            </footer>
          </form>

          <form v-else-if="activeModal === 'edit-schedule'" class="schedule-form" @submit.prevent="handleSubmit('edit-schedule')">
            <label>
              <span>Mata Pelajaran</span>
              <input type="text" :value="activeSchedule?.subject" />
            </label>
            <div class="schedule-form__row">
              <label>
                <span>Hari, Tanggal</span>
                <input type="text" :value="activeSchedule?.day" />
              </label>
              <label>
                <span>Jam Mulai</span>
                <input type="text" :value="activeSchedule?.time.split(' - ')[0]" />
              </label>
              <label>
                <span>Jam Selesai</span>
                <input type="text" :value="activeSchedule?.time.split(' - ')[1]" />
              </label>
            </div>
            <label>
              <span>Ruangan/Lokasi</span>
              <input type="text" :value="activeSchedule?.location" />
            </label>
            <label>
              <span>Status Jadwal</span>
              <select :value="activeSchedule?.status.label">
                <option>Akan Datang</option>
                <option>Berlangsung</option>
                <option>Selesai</option>
              </select>
            </label>
            <label>
              <span>Topik Pembelajaran</span>
              <input type="text" :value="activeSchedule?.topic" />
            </label>
            <label>
              <span>Deskripsi Singkat Materi</span>
              <textarea rows="3">{{ activeSchedule?.description }}</textarea>
            </label>
            <footer>
              <button type="button" class="schedule-form__ghost" @click="closeModals()">Batal</button>
              <button type="submit">Simpan</button>
            </footer>
          </form>

          <form v-else-if="activeModal === 'create-material'" class="schedule-form" @submit.prevent="handleSubmit('create-material')">
            <label>
              <span>Judul Materi</span>
              <input type="text" placeholder="Masukkan judul materi baru" />
            </label>
            <label>
              <span>Deskripsi Singkat Materi</span>
              <textarea rows="4" placeholder="Tuliskan deskripsi materi yang diunggah.."></textarea>
            </label>
            <div class="schedule-form__dropzone" role="button" tabindex="0">
              <p>Klik untuk upload atau drag and drop</p>
              <span>PNG, MP4, PPT, DOCX, PDF</span>
            </div>
            <footer>
              <button type="button" class="schedule-form__ghost" @click="closeModals()">Batal</button>
              <button type="submit">Simpan</button>
            </footer>
          </form>

          <form v-else-if="activeModal === 'edit-material'" class="schedule-form" @submit.prevent="handleSubmit('edit-material')">
            <label>
              <span>Judul Materi</span>
              <input type="text" :value="activeSchedule?.topic" />
            </label>
            <label>
              <span>Deskripsi Singkat Materi</span>
              <textarea rows="4">{{ activeSchedule?.description }}</textarea>
            </label>
            <div class="schedule-form__files">
              <p>Materi yang sudah diunggah</p>
              <ul>
                <li v-for="file in activeSchedule?.materials" :key="file.id">
                  <span>{{ file.name }}</span>
                  <button type="button">Hapus</button>
                </li>
              </ul>
            </div>
            <div class="schedule-form__dropzone" role="button" tabindex="0">
              <p>Tambahkan Materi Baru</p>
              <span>Klik untuk upload atau drag and drop</span>
            </div>
            <footer>
              <button type="button" class="schedule-form__ghost" @click="closeModals()">Batal</button>
              <button type="submit">Simpan</button>
            </footer>
          </form>
        </div>
      </section>
    </transition>
    <transition-group name="schedule-toast" tag="ol" class="schedule__toasts" aria-live="polite" aria-atomic="true">
      <li v-for="toast in toasts" :key="toast.id" class="schedule-toast">
        <span class="schedule-toast__icon">✓</span>
        <div>
          <p class="schedule-toast__title">{{ toast.title }}</p>
          <p class="schedule-toast__subtitle">{{ toast.subtitle }}</p>
        </div>
        <button type="button" class="schedule-toast__close" aria-label="Tutup notifikasi" @click="dismissToast(toast.id)">×</button>
      </li>
    </transition-group>
  </div>
</template>

<script setup>
import { computed, h, markRaw, onBeforeUnmount, onMounted, reactive, ref, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
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
const route = useRoute();
const { user, logout } = useAuth();

const organizationName = computed(() => user.value?.organization ?? 'Terminal Pintar');
const roleLabel = computed(() => 'Dashboard Guru');
const profileName = computed(() => user.value?.name ?? 'Guru Terminal Pintar');
const profileEmail = computed(() => user.value?.contact?.email ?? 'Belum tersedia');
const profileAvatar = computed(() => user.value?.avatar ?? assets.avatarFallback);

const notifications = [
  {
    id: 'notif-1',
    title: 'Jadwal berhasil diperbarui',
    subtitle: 'Jadwal Bahasa Indonesia telah diperbarui untuk hari ini',
    status: 'success',
  },
  {
    id: 'notif-2',
    title: '3 materi baru diunggah',
    subtitle: 'Materi Matematika kelas IX siap dibagikan',
    status: 'info',
  },
];

const createAccent = (routeName) =>
  route.name === routeName
    ? 'linear-gradient(135deg, rgba(118,179,64,0.9), rgba(99,144,57,0.75))'
    : 'linear-gradient(135deg, rgba(118,179,64,0.35), rgba(118,179,64,0.15))';

const mainMenu = computed(() => [
  {
    label: 'Beranda',
    accent: createAccent('teacher-dashboard'),
    icon: createIcon({ d: 'M4 10.5L12 4l8 6.5V20a1 1 0 01-1 1h-5v-6h-4v6H5a1 1 0 01-1-1z', fill: 'currentColor' }),
    to: { name: 'teacher-dashboard' },
    active: route.name === 'teacher-dashboard',
  },
]);

const academicMenu = computed(() => [
  {
    label: 'Jadwal & Materi',
    subtitle: 'Atur pertemuan mingguan',
    icon: createIcon({ d: 'M6 4h12a2 2 0 012 2v2H4V6a2 2 0 012-2zm14 6v8a2 2 0 01-2 2H6a2 2 0 01-2-2v-8zm-5 3h-6v2h6z', fill: 'currentColor' }),
    to: { name: 'teacher-schedule' },
    active: route.name === 'teacher-schedule',
  },
]);

const attendanceMenu = computed(() => [
  {
    label: 'Kehadiran',
    subtitle: 'Rekap absensi & catatan',
    icon: createIcon({ d: 'M4 6h16a1 1 0 011 1v11a2 2 0 01-2 2H5a2 2 0 01-2-2V7a1 1 0 011-1zm5 3v2h6V9zm0 4v2h4v-2z', fill: 'currentColor' }),
    to: { name: 'teacher-attendance' },
    active: route.name === 'teacher-attendance',
  },
]);

const schedules = ref([
  {
    id: 'schedule-1',
    day: '15 Oktober 2025 (Selasa)',
    subject: 'Matematika - Teorema Segitiga',
    topic: 'Perkalian alas dan tinggi dalam penerapan bangun ruang',
    time: '09.00 - 10.30 WIB',
    location: 'Ruang 8B - Laboratorium Matematika',
    description:
      'Fokus pada latihan menyelesaikan permasalahan geometri ruang, pengukuran, grafik, dan analisis data dengan contoh aplikatif.',
    progress: 82,
    lastUpdated: 'Diperbarui 12 menit yang lalu',
    status: {
      label: 'Akan Datang',
      color: '#4A7C2C',
      background: 'rgba(118,179,64,0.14)',
    },
    materials: [
      { id: 'file-1', name: 'RPP - Geometri Dasar.pdf', size: '1.2 MB', type: 'pdf' },
      { id: 'file-2', name: 'Latihan Bangun Ruang.docx', size: '890 KB', type: 'doc' },
      { id: 'file-3', name: 'Infografis Sudut.pptx', size: '2.1 MB', type: 'ppt' },
    ],
  },
  {
    id: 'schedule-2',
    day: '17 Oktober 2025 (Kamis)',
    subject: 'Bahasa Indonesia - Materi Puisi',
    topic: 'Latihan membaca dan mendalami diksi puisi modern',
    time: '10.00 - 11.30 WIB',
    location: 'Ruang 9A - Studio Bahasa',
    description:
      'Membahas struktur puisi, latihan vokal, dan penugasan membuat puisi bebas yang akan dipentaskan pekan depan.',
    progress: 64,
    lastUpdated: 'Diperbarui 2 jam yang lalu',
    status: {
      label: 'Sedang Berlangsung',
      color: '#4051B5',
      background: 'rgba(96,125,255,0.18)',
    },
    materials: [
      { id: 'file-4', name: 'Contoh Diksi dan Majas.docx', size: '650 KB', type: 'doc' },
      { id: 'file-5', name: 'Video Latihan Vokal.mp4', size: '38 MB', type: 'video' },
    ],
  },
  {
    id: 'schedule-3',
    day: '20 Oktober 2025 (Senin)',
    subject: 'Ilmu Pengetahuan Alam - Biologi',
    topic: 'Eksperimen fotosintesis dengan alat sederhana',
    time: '08.00 - 09.30 WIB',
    location: 'Laboratorium IPA - Gedung Timur',
    description:
      'Mengamati pengaruh intensitas cahaya terhadap kecepatan fotosintesis melalui percobaan laboratorium.',
    progress: 58,
    lastUpdated: 'Diperbarui kemarin',
    status: {
      label: 'Menunggu Materi',
      color: '#B28704',
      background: 'rgba(255,193,7,0.18)',
    },
    materials: [
      { id: 'file-6', name: 'Panduan Eksperimen.pdf', size: '2.4 MB', type: 'pdf' },
      { id: 'file-7', name: 'Lembar Observasi.xlsx', size: '420 KB', type: 'sheet' },
    ],
  },
]);

const toasts = ref([]);
const toastTimers = new Map();

const pushToast = ({ title, subtitle }) => {
  const id = `toast-${Date.now()}-${Math.random().toString(36).slice(2, 6)}`;
  toasts.value.push({ id, title, subtitle });

  if (typeof window !== 'undefined') {
    const timer = window.setTimeout(() => dismissToast(id), 4200);
    toastTimers.set(id, timer);
  }
};

const dismissToast = (id) => {
  const timer = toastTimers.get(id);
  if (timer) {
    clearTimeout(timer);
    toastTimers.delete(id);
  }
  toasts.value = toasts.value.filter((toast) => toast.id !== id);
};

const fileTypeIcons = {
  pdf: createIcon(
    { d: 'M6 2h7l5 5v15a1 1 0 01-1 1H6a1 1 0 01-1-1V3a1 1 0 011-1z', fill: 'currentColor' },
    { d: 'M13 2v5h5', fill: '#fff', 'fill-opacity': '0.4' },
  ),
  doc: createIcon({ d: 'M4 3h12l4 4v14a1 1 0 01-1 1H4a1 1 0 01-1-1V4a1 1 0 011-1zm5 4H7v10h2zm4 0h-2v10h2zm4 0h-2v10h2z', fill: 'currentColor' }),
  ppt: createIcon({ d: 'M4 2h16a1 1 0 011 1v18a1 1 0 01-1 1H4a1 1 0 01-1-1V3a1 1 0 011-1zm9 10h5v6h-5zm-2 0H5v6h6zm2-8v6h7V4zm-2 0H5v6h6z', fill: 'currentColor' }),
  video: createIcon({ d: 'M4 6a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2v-.586l3.293 3.293A1 1 0 0022 18.586V9.414a1 1 0 00-1.707-.707L17 12.586V8a2 2 0 00-2-2z', fill: 'currentColor' }),
  sheet: createIcon({ d: 'M5 2h9l5 5v15a1 1 0 01-1 1H5a1 1 0 01-1-1V3a1 1 0 011-1zm3 9v2h8v-2zm0 4v2h8v-2zm0-8v2h5V7z', fill: 'currentColor' }),
  generic: createIcon({ d: 'M6 2h7l5 5v15a1 1 0 01-1 1H6a1 1 0 01-1-1V3a1 1 0 011-1z', fill: 'currentColor' }),
};

const modals = reactive({
  'create-schedule': false,
  'edit-schedule': false,
  'create-material': false,
  'edit-material': false,
});

const activeModal = ref(null);
const activeSchedule = ref(null);

const scheduleToastCopy = {
  'create-schedule': () => ({
    title: 'Jadwal Berhasil Ditambahkan',
    subtitle: 'Jadwal baru siap dibagikan ke siswa.',
  }),
  'edit-schedule': () => ({
    title: 'Jadwal Berhasil Diperbarui',
    subtitle: `${activeSchedule.value?.subject ?? 'Jadwal terpilih'} telah disesuaikan.`,
  }),
  'create-material': () => ({
    title: 'Materi Berhasil Ditambahkan',
    subtitle: `${activeSchedule.value?.subject ?? 'Materi baru'} siap dibagikan.`,
  }),
  'edit-material': () => ({
    title: 'Materi Berhasil Diperbarui',
    subtitle: `${activeSchedule.value?.subject ?? 'Materi terpilih'} telah diperbarui.`,
  }),
};

const isAnyModalOpen = computed(() => Object.values(modals).some(Boolean));

const modalCopy = {
  'create-schedule': {
    title: 'Tambah Jadwal dan Materi',
    subtitle: 'Isi data jadwal kegiatan belajar mengajar dengan lengkap',
  },
  'edit-schedule': {
    title: 'Edit Jadwal',
    subtitle: 'Perbarui informasi jadwal yang telah tersimpan',
  },
  'create-material': {
    title: 'Tambah Materi',
    subtitle: 'Unggah materi pembelajaran pendukung untuk siswa',
  },
  'edit-material': {
    title: 'Edit Materi',
    subtitle: 'Perbarui materi pembelajaran yang telah diunggah',
  },
};

const modalTitle = computed(() => (activeModal.value ? modalCopy[activeModal.value].title : ''));
const modalSubtitle = computed(() => (activeModal.value ? modalCopy[activeModal.value].subtitle : ''));

const updateRouteModal = (modal, scheduleId) => {
  const query = { ...route.query };
  if (modal) {
    query.modal = modal;
  } else {
    delete query.modal;
  }

  if (scheduleId) {
    query.scheduleId = scheduleId;
  } else {
    delete query.scheduleId;
  }

  router.replace({ name: route.name ?? 'teacher-schedule', query });
};

const openModal = (modal, scheduleId = null, syncRoute = true) => {
  if (!(modal in modals)) return;

  Object.keys(modals).forEach((key) => {
    modals[key] = key === modal;
  });

  activeModal.value = modal;
  if (scheduleId) {
    activeSchedule.value = schedules.value.find((session) => session.id === scheduleId) ?? schedules.value[0] ?? null;
  } else if (modal !== 'create-schedule') {
    activeSchedule.value = schedules.value[0] ?? null;
  } else {
    activeSchedule.value = null;
  }

  if (syncRoute) {
    updateRouteModal(modal, scheduleId);
  }
};

const closeModals = (syncRoute = true) => {
  Object.keys(modals).forEach((key) => {
    modals[key] = false;
  });
  activeModal.value = null;
  activeSchedule.value = null;

  if (syncRoute) {
    updateRouteModal(null, null);
  }
};

const handleSubmit = (type) => {
  const factory = scheduleToastCopy[type];
  if (!factory) {
    closeModals();
    return;
  }

  const payload = factory();
  pushToast(payload);
  closeModals();
};

watch(
  () => ({ modal: route.query.modal, scheduleId: route.query.scheduleId }),
  ({ modal, scheduleId }) => {
    if (!modal || !(modal in modals)) {
      closeModals(false);
      return;
    }

    openModal(modal, scheduleId ?? null, false);
  },
  { immediate: true },
);

const handleSidebarClick = (item) => {
  if (!item?.to) return;
  if (item.to.name === route.name && !item.to.query) return;
  router.push(item.to);
};

const goToAttendance = (session) => {
  router.push({
    name: 'teacher-attendance',
    query: {
      from: 'schedule',
      focus: 'notes',
      session: session?.id,
    },
  });
};

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

const handleLogout = async () => {
  closeOverlays();
  closeModals();
  await logout();
  router.replace({ name: 'login', query: { role: 'teacher' } });
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
    if (isAnyModalOpen.value) {
      closeModals();
    } else {
      closeOverlays();
    }
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

  toastTimers.forEach((timer) => clearTimeout(timer));
  toastTimers.clear();
});
</script>

<style scoped>
.schedule {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  background: linear-gradient(160deg, #fef7ff 0%, #f9fff4 55%, #f3fbf0 100%);
  color: #1f1f1f;
  transition: background 0.4s ease;
}

.schedule__header {
  position: sticky;
  top: 0;
  z-index: 10;
  display: grid;
  grid-template-columns: auto 1fr auto;
  align-items: center;
  gap: 2rem;
  padding: 1.25rem 3vw;
  background: linear-gradient(135deg, rgba(255, 255, 255, 0.82), rgba(255, 255, 255, 0.55));
  border-bottom: 1px solid rgba(118, 179, 64, 0.1);
  backdrop-filter: blur(16px);
  transition: all 0.3s ease;
}

.schedule__header--glass {
  box-shadow: 0 18px 48px -30px rgba(57, 70, 62, 0.45);
}

.schedule__brand {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.schedule__logo {
  width: 44px;
  height: 44px;
  object-fit: cover;
  border-radius: 14px;
  box-shadow: 0 12px 30px -16px rgba(74, 124, 44, 0.8);
}

.schedule__brand-text {
  display: flex;
  flex-direction: column;
  gap: 0.15rem;
}

.schedule__brand-name {
  font-size: 1.05rem;
  font-weight: 700;
  color: #76b340;
}

.schedule__brand-sub {
  font-size: 0.85rem;
  color: rgba(31, 31, 31, 0.6);
}

.schedule__top-nav {
  justify-self: end;
  display: flex;
  align-items: center;
  gap: 1rem;
}

.schedule__language {
  display: inline-flex;
  align-items: center;
  gap: 0.6rem;
  padding: 0.5rem 0.85rem;
  border-radius: 14px;
  border: 1px solid rgba(118, 179, 64, 0.15);
  background: rgba(255, 255, 255, 0.6);
  color: rgba(31, 31, 31, 0.8);
  cursor: pointer;
  font-weight: 500;
}

.schedule__language svg {
  width: 20px;
  height: 20px;
}

.schedule__actions {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.schedule__icon-button {
  width: 44px;
  height: 44px;
  border-radius: 14px;
  border: none;
  background: rgba(118, 179, 64, 0.12);
  display: grid;
  place-items: center;
  cursor: pointer;
  position: relative;
}

.schedule__icon-button img {
  width: 22px;
  height: 22px;
}

.schedule__indicator {
  position: absolute;
  top: 10px;
  right: 10px;
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background: #22c55e;
  border: 2px solid #fff;
}

.schedule__profile {
  width: 44px;
  height: 44px;
  border-radius: 50%;
  border: 2px solid rgba(118, 179, 64, 0.32);
  padding: 0;
  overflow: hidden;
  cursor: pointer;
  background: transparent;
}

.schedule__profile img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.schedule__popover {
  position: absolute;
  right: 3vw;
  top: calc(100% + 1rem);
  background: rgba(255, 255, 255, 0.95);
  border-radius: 18px;
  border: 1px solid rgba(118, 179, 64, 0.1);
  box-shadow: 0 30px 80px -48px rgba(57, 70, 62, 0.5);
  padding: 1.25rem;
  min-width: 280px;
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.schedule__popover h2 {
  margin: 0;
  font-size: 1rem;
  font-weight: 700;
}

.schedule__popover p {
  margin: 0;
  font-size: 0.85rem;
  color: rgba(31, 31, 31, 0.6);
}

.schedule__popover ul {
  margin: 0 0 1.25rem;
  padding: 0;
  list-style: none;
  display: flex;
  flex-direction: column;
  gap: 0.85rem;
}

.schedule__popover ul li {
  display: flex;
  gap: 0.75rem;
}

.schedule__bullet {
  width: 10px;
  height: 10px;
  border-radius: 50%;
  margin-top: 0.25rem;
}

.schedule__bullet--success {
  background: #22c55e;
}

.schedule__bullet--info {
  background: #60a5fa;
}

.schedule__popover-title {
  margin: 0;
  font-weight: 600;
  font-size: 0.9rem;
}

.schedule__popover-sub {
  margin: 0.15rem 0 0;
  color: rgba(31, 31, 31, 0.6);
  font-size: 0.8rem;
}

.schedule__popover-action {
  width: 100%;
  padding: 0.6rem;
  border-radius: 12px;
  border: 1px solid rgba(118, 179, 64, 0.3);
  background: rgba(118, 179, 64, 0.08);
  color: #4a7c2c;
  font-weight: 500;
  cursor: pointer;
}

.schedule__popover--profile {
  align-items: center;
  text-align: center;
}

.schedule__profile-name {
  margin: 0;
  font-weight: 600;
}

.schedule__profile-email {
  margin: 0;
  font-size: 0.85rem;
  color: rgba(31, 31, 31, 0.65);
}

.schedule__logout {
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

.schedule__layout {
  display: grid;
  grid-template-columns: 260px 1fr;
  gap: 2.5rem;
  padding: 2rem 3vw 3rem;
}

.schedule__sidebar {
  position: sticky;
  top: 96px;
  align-self: start;
  display: flex;
  flex-direction: column;
  gap: 1.75rem;
}

.schedule__sidebar-section {
  background: rgba(255, 255, 255, 0.8);
  border-radius: 20px;
  padding: 1.2rem 1.4rem;
  border: 1px solid rgba(118, 179, 64, 0.12);
  box-shadow: 0 18px 48px -30px rgba(57, 70, 62, 0.4);
  backdrop-filter: blur(16px);
}

.schedule__sidebar-label {
  font-size: 0.8rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.08em;
  color: rgba(31, 31, 31, 0.5);
  margin-bottom: 1rem;
}

.schedule__sidebar-section ul {
  list-style: none;
  margin: 0;
  padding: 0;
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.schedule__sidebar-item {
  margin: 0;
}

.schedule__sidebar-item--active .schedule__sidebar-link {
  background: rgba(118, 179, 64, 0.14);
  border-color: rgba(118, 179, 64, 0.22);
  color: #4a7c2c;
}

.schedule__sidebar-link {
  width: 100%;
  display: flex;
  align-items: center;
  gap: 0.9rem;
  padding: 0.8rem 0.95rem;
  border-radius: 16px;
  border: 1px solid rgba(118, 179, 64, 0.12);
  background: rgba(255, 255, 255, 0.65);
  color: rgba(31, 31, 31, 0.75);
  cursor: pointer;
  text-align: left;
  transition: all 0.25s ease;
}

.schedule__sidebar-link:hover {
  transform: translateY(-1px);
  box-shadow: 0 12px 30px -28px rgba(74, 124, 44, 0.65);
}

.schedule__sidebar-link--wide {
  align-items: flex-start;
}

.schedule__sidebar-icon {
  width: 42px;
  height: 42px;
  border-radius: 14px;
  display: grid;
  place-items: center;
  color: #4a7c2c;
}

.schedule__sidebar-icon svg {
  width: 22px;
  height: 22px;
}

.schedule__sidebar-icon--outline {
  background: rgba(118, 179, 64, 0.08);
  border: 1px solid rgba(118, 179, 64, 0.1);
}

.schedule__sidebar-text span {
  display: block;
  font-weight: 600;
}

.schedule__sidebar-text small {
  display: block;
  margin-top: 0.15rem;
  font-size: 0.75rem;
  color: rgba(31, 31, 31, 0.55);
}

.schedule__main {
  display: flex;
  flex-direction: column;
  gap: 2rem;
}

.schedule__intro {
  display: grid;
  grid-template-columns: minmax(0, 3fr) minmax(0, 2fr);
  gap: 2rem;
  align-items: stretch;
}

.schedule__intro-content {
  background: rgba(255, 255, 255, 0.82);
  border-radius: 24px;
  border: 1px solid rgba(118, 179, 64, 0.14);
  padding: 2rem 2.4rem;
  display: flex;
  flex-direction: column;
  gap: 1rem;
  box-shadow: 0 28px 60px -42px rgba(57, 70, 62, 0.45);
}

.schedule__intro-eyebrow {
  margin: 0;
  font-size: 0.85rem;
  font-weight: 600;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  color: rgba(74, 124, 44, 0.85);
}

.schedule__intro-content h1 {
  margin: 0;
  font-size: 1.65rem;
  font-weight: 700;
}

.schedule__intro-content p {
  margin: 0;
  color: rgba(31, 31, 31, 0.68);
  line-height: 1.6;
}

.schedule__intro-actions {
  display: flex;
  gap: 0.75rem;
}

.schedule__intro-button {
  padding: 0.85rem 1.6rem;
  border-radius: 16px;
  border: none;
  background: linear-gradient(135deg, rgba(118, 179, 64, 0.85), rgba(99, 144, 57, 0.75));
  color: #fff;
  font-weight: 600;
  cursor: pointer;
  display: inline-flex;
  gap: 0.45rem;
  align-items: center;
}

.schedule__intro-button--ghost {
  background: rgba(255, 255, 255, 0.85);
  color: #4a7c2c;
  border: 1px solid rgba(118, 179, 64, 0.18);
}

.schedule__intro-card {
  background: linear-gradient(135deg, rgba(118, 179, 64, 0.2), rgba(96, 125, 255, 0.12));
  border-radius: 24px;
  border: 1px solid rgba(118, 179, 64, 0.18);
  padding: 2rem;
  display: flex;
  flex-direction: column;
  gap: 0.85rem;
  color: #1f1f1f;
  box-shadow: 0 28px 60px -40px rgba(57, 70, 62, 0.45);
}

.schedule__intro-card span {
  font-size: 0.85rem;
  font-weight: 600;
  color: rgba(31, 31, 31, 0.65);
}

.schedule__intro-card strong {
  font-size: 1.3rem;
  line-height: 1.4;
}

.schedule__intro-card button {
  align-self: flex-start;
  padding: 0.75rem 1.4rem;
  border-radius: 14px;
  border: none;
  background: rgba(74, 124, 44, 0.9);
  color: #fff;
  font-weight: 600;
  cursor: pointer;
}

.schedule__toolbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: rgba(255, 255, 255, 0.75);
  border: 1px solid rgba(118, 179, 64, 0.16);
  border-radius: 20px;
  padding: 1rem 1.5rem;
  box-shadow: 0 18px 48px -36px rgba(57, 70, 62, 0.35);
}

.schedule__filters {
  display: flex;
  gap: 1.5rem;
}

.schedule__filters label {
  display: flex;
  flex-direction: column;
  gap: 0.35rem;
  font-size: 0.85rem;
  color: rgba(31, 31, 31, 0.6);
}

.schedule__filters select {
  padding: 0.6rem 0.85rem;
  border-radius: 12px;
  border: 1px solid rgba(118, 179, 64, 0.18);
  background: rgba(255, 255, 255, 0.9);
  font-weight: 500;
}

.schedule__toolbar-actions {
  display: flex;
  gap: 0.75rem;
}

.schedule__toolbar-button {
  padding: 0.7rem 1.4rem;
  border-radius: 14px;
  border: none;
  background: linear-gradient(135deg, rgba(118, 179, 64, 0.85), rgba(99, 144, 57, 0.75));
  color: #fff;
  font-weight: 600;
  cursor: pointer;
}

.schedule__list {
  display: grid;
  gap: 1.5rem;
}

.schedule-card {
  background: rgba(255, 255, 255, 0.88);
  border-radius: 24px;
  border: 1px solid rgba(118, 179, 64, 0.12);
  padding: 1.8rem 2rem;
  display: flex;
  flex-direction: column;
  gap: 1.25rem;
  box-shadow: 0 30px 70px -52px rgba(57, 70, 62, 0.55);
}

.schedule-card__header {
  display: flex;
  justify-content: space-between;
  gap: 1rem;
  align-items: flex-start;
}

.schedule-card__date {
  margin: 0;
  font-weight: 600;
  font-size: 1rem;
}

.schedule-card__subject {
  margin: 0.35rem 0 0;
  color: rgba(31, 31, 31, 0.65);
  font-size: 0.9rem;
}

.schedule-card__status {
  padding: 0.45rem 0.9rem;
  border-radius: 999px;
  font-weight: 600;
  font-size: 0.8rem;
}

.schedule-card__meta {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 0.8rem;
  font-size: 0.9rem;
  color: rgba(31, 31, 31, 0.75);
}

.schedule-card__meta p {
  margin: 0;
}

.schedule-card__description {
  margin: 0;
  color: rgba(31, 31, 31, 0.7);
  line-height: 1.6;
}

.schedule-card__materials {
  background: rgba(249, 253, 247, 0.9);
  border-radius: 18px;
  border: 1px solid rgba(118, 179, 64, 0.16);
  padding: 1.25rem 1.4rem;
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.schedule-card__materials header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 1rem;
}

.schedule-card__materials h2 {
  margin: 0;
  font-size: 1rem;
}

.schedule-card__materials span {
  font-size: 0.8rem;
  color: rgba(31, 31, 31, 0.6);
}

.schedule-card__materials ul {
  list-style: none;
  margin: 0;
  padding: 0;
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.schedule-card__materials li {
  display: grid;
  grid-template-columns: auto 1fr auto;
  gap: 0.9rem;
  align-items: center;
  padding: 0.75rem;
  border-radius: 14px;
  background: rgba(255, 255, 255, 0.85);
}

.schedule-card__materials li p {
  margin: 0;
  font-weight: 600;
}

.schedule-card__materials li small {
  display: block;
  margin-top: 0.2rem;
  font-size: 0.75rem;
  color: rgba(31, 31, 31, 0.55);
}

.schedule-card__materials li button {
  border: none;
  background: rgba(118, 179, 64, 0.18);
  color: #4a7c2c;
  padding: 0.45rem 0.9rem;
  border-radius: 999px;
  font-weight: 600;
  cursor: pointer;
}

.schedule-card__file-icon {
  width: 42px;
  height: 42px;
  border-radius: 14px;
  display: grid;
  place-items: center;
  color: #4a7c2c;
}

.schedule-card__file-icon svg {
  width: 22px;
  height: 22px;
}

.schedule-card__file-icon--pdf {
  background: rgba(220, 38, 38, 0.12);
  color: #b91c1c;
}

.schedule-card__file-icon--doc {
  background: rgba(59, 130, 246, 0.12);
  color: #2563eb;
}

.schedule-card__file-icon--ppt {
  background: rgba(249, 115, 22, 0.12);
  color: #ea580c;
}

.schedule-card__file-icon--video {
  background: rgba(96, 125, 255, 0.12);
  color: #4051b5;
}

.schedule-card__file-icon--sheet {
  background: rgba(16, 185, 129, 0.12);
  color: #0f9d58;
}

.schedule-card__progress {
  height: 10px;
  border-radius: 999px;
  background: rgba(118, 179, 64, 0.12);
  overflow: hidden;
}

.schedule-card__progress-bar {
  height: 100%;
  background: linear-gradient(135deg, rgba(118, 179, 64, 0.9), rgba(99, 144, 57, 0.75));
  border-radius: inherit;
  transition: width 0.4s ease;
}

.schedule-card__actions {
  display: flex;
  gap: 0.75rem;
  flex-wrap: wrap;
}

.schedule-card__actions button {
  padding: 0.65rem 1.2rem;
  border-radius: 14px;
  border: none;
  background: rgba(118, 179, 64, 0.18);
  color: #4a7c2c;
  font-weight: 600;
  cursor: pointer;
}

.schedule-card__actions--ghost {
  background: rgba(255, 255, 255, 0.85);
  border: 1px solid rgba(118, 179, 64, 0.16);
  color: rgba(31, 31, 31, 0.75);
}

.schedule__toasts {
  position: fixed;
  right: 2.5rem;
  bottom: 2.5rem;
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
  list-style: none;
  margin: 0;
  padding: 0;
  z-index: 25;
}

.schedule-toast {
  display: grid;
  grid-template-columns: auto 1fr auto;
  align-items: center;
  gap: 0.85rem;
  padding: 0.95rem 1.15rem;
  border-radius: 18px;
  background: rgba(255, 255, 255, 0.94);
  border: 1px solid rgba(118, 179, 64, 0.22);
  box-shadow: 0 20px 45px -30px rgba(57, 70, 62, 0.4);
}

.schedule-toast__icon {
  width: 28px;
  height: 28px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  background: rgba(118, 179, 64, 0.16);
  color: #4a7c2c;
  font-weight: 700;
}

.schedule-toast__title {
  margin: 0;
  font-weight: 600;
  color: #1f1f1f;
}

.schedule-toast__subtitle {
  margin: 0.2rem 0 0;
  font-size: 0.82rem;
  color: rgba(31, 31, 31, 0.6);
}

.schedule-toast__close {
  background: none;
  border: none;
  font-size: 1.25rem;
  line-height: 1;
  color: rgba(31, 31, 31, 0.45);
  cursor: pointer;
}

.schedule-modal {
  position: fixed;
  inset: 0;
  display: grid;
  place-items: center;
  z-index: 20;
}

.schedule-modal__backdrop {
  position: absolute;
  inset: 0;
  background: rgba(12, 18, 14, 0.45);
}

.schedule-modal__content {
  position: relative;
  z-index: 1;
  width: min(540px, calc(100vw - 3rem));
  max-height: calc(100vh - 4rem);
  overflow-y: auto;
  background: rgba(255, 255, 255, 0.98);
  border-radius: 24px;
  border: 1px solid rgba(118, 179, 64, 0.2);
  padding: 2rem;
  display: flex;
  flex-direction: column;
  gap: 1.25rem;
  box-shadow: 0 40px 120px -60px rgba(12, 18, 14, 0.6);
}

.schedule-modal__content header h2 {
  margin: 0;
  font-size: 1.3rem;
}

.schedule-modal__content header p {
  margin: 0.2rem 0 0;
  color: rgba(31, 31, 31, 0.6);
  font-size: 0.9rem;
}

.schedule-form {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.schedule-form label {
  display: flex;
  flex-direction: column;
  gap: 0.35rem;
  font-size: 0.9rem;
  color: rgba(31, 31, 31, 0.7);
}

.schedule-form input,
.schedule-form select,
.schedule-form textarea {
  padding: 0.75rem 1rem;
  border-radius: 14px;
  border: 1px solid rgba(118, 179, 64, 0.18);
  background: rgba(255, 255, 255, 0.95);
  font-size: 0.95rem;
  font-family: inherit;
  color: rgba(31, 31, 31, 0.85);
}

.schedule-form__row {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
  gap: 0.9rem;
}

.schedule-form__dropzone {
  border: 1.5px dashed rgba(118, 179, 64, 0.35);
  border-radius: 16px;
  padding: 1.5rem;
  text-align: center;
  background: rgba(249, 253, 247, 0.8);
  color: rgba(31, 31, 31, 0.6);
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.schedule-form__files ul {
  list-style: none;
  margin: 0.65rem 0 0;
  padding: 0;
  display: flex;
  flex-direction: column;
  gap: 0.6rem;
}

.schedule-form__files li {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.65rem 0.85rem;
  border-radius: 12px;
  background: rgba(255, 255, 255, 0.9);
  border: 1px solid rgba(118, 179, 64, 0.12);
}

.schedule-form__files button {
  border: none;
  background: rgba(239, 68, 68, 0.12);
  color: #b91c1c;
  padding: 0.35rem 0.75rem;
  border-radius: 10px;
  font-weight: 600;
  cursor: pointer;
}

.schedule-form footer {
  display: flex;
  justify-content: flex-end;
  gap: 0.75rem;
}

.schedule-form__ghost {
  padding: 0.65rem 1.2rem;
  border-radius: 14px;
  border: 1px solid rgba(118, 179, 64, 0.2);
  background: rgba(255, 255, 255, 0.95);
  color: rgba(31, 31, 31, 0.75);
  font-weight: 600;
  cursor: pointer;
}

.schedule-form footer button[type='submit'] {
  padding: 0.65rem 1.4rem;
  border-radius: 14px;
  border: none;
  background: linear-gradient(135deg, rgba(118, 179, 64, 0.85), rgba(99, 144, 57, 0.75));
  color: #fff;
  font-weight: 600;
  cursor: pointer;
}

.schedule-fade-enter-active,
.schedule-fade-leave-active {
  transition: opacity 0.2s ease;
}

.schedule-fade-enter-from,
.schedule-fade-leave-to {
  opacity: 0;
}

.schedule-modal-enter-active,
.schedule-modal-leave-active {
  transition: opacity 0.25s ease;
}

.schedule-modal-enter-from,
.schedule-modal-leave-to {
  opacity: 0;
}

.schedule-toast-enter-active,
.schedule-toast-leave-active {
  transition: all 0.3s ease;
}

.schedule-toast-enter-from,
.schedule-toast-leave-to {
  opacity: 0;
  transform: translateY(12px);
}

@media (max-width: 1200px) {
  .schedule__layout {
    grid-template-columns: 1fr;
  }

  .schedule__sidebar {
    position: relative;
    top: auto;
    flex-direction: row;
    overflow-x: auto;
    padding-bottom: 1rem;
  }

  .schedule__sidebar-section {
    min-width: 240px;
  }

  .schedule__intro {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 768px) {
  .schedule__header {
    grid-template-columns: 1fr;
    justify-items: stretch;
    gap: 1rem;
  }

  .schedule__top-nav {
    justify-self: start;
  }

  .schedule__layout {
    padding: 1.5rem 5vw 2.5rem;
  }

  .schedule__toolbar {
    flex-direction: column;
    gap: 1rem;
    align-items: stretch;
  }

  .schedule__filters {
    flex-direction: column;
    gap: 1rem;
  }

  .schedule-card {
    padding: 1.5rem;
  }

  .schedule-card__materials header {
    flex-direction: column;
    align-items: flex-start;
  }

  .schedule-card__materials li {
    grid-template-columns: auto 1fr;
    gap: 0.75rem;
  }

  .schedule-card__materials li button {
    justify-self: start;
  }

  .schedule-card__actions {
    flex-direction: column;
    align-items: stretch;
  }

  .schedule-modal__content {
    width: min(480px, calc(100vw - 2rem));
    padding: 1.5rem;
  }
}
</style>
