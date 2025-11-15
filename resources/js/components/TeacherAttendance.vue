<template>
  <div class="attendance" :class="{ 'attendance--scrolled': isScrolled }">
    <header ref="headerRef" class="attendance__header" :class="{ 'attendance__header--glass': isScrolled }" role="banner">
      <div class="attendance__brand">
        <img class="attendance__logo" :src="assets.logo" alt="Logo Terminal Pintar" />
        <div class="attendance__brand-text">
          <span class="attendance__brand-name">{{ organizationName }}</span>
          <span class="attendance__brand-sub">{{ roleLabel }}</span>
        </div>
      </div>

      <nav class="attendance__top-nav" aria-label="Navigasi utama guru">
        <button type="button" class="attendance__language" aria-label="Ubah bahasa">
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3a9 9 0 109 9 9.01 9.01 0 00-9-9zm6.93 8h-2.7a13.19 13.19 0 0 0-.9-4.45A7.01 7.01 0 0118.93 11zM12 5a11.48 11.48 0 011.13 6h-2.26A11.48 11.48 0 0112 5zM5.07 11a7.01 7.01 0 012.6-4.45A13.19 13.19 0 006.77 11zm0 2h2.7a13.19 13.19 0 00.9 4.45A7.01 7.01 0 015.07 13zm6.93 6a11.48 11.48 0 01-1.13-6h2.26A11.48 11.48 0 0112 19zm3.3-1.55a13.19 13.19 0 00.9-4.45h2.7a7.01 7.01 0 01-3.6 4.45z" /></svg>
          <span>Bahasa Indonesia</span>
        </button>
      </nav>

      <div class="attendance__actions">
        <button
          ref="notificationTrigger"
          type="button"
          class="attendance__icon-button"
          aria-haspopup="true"
          :aria-expanded="showNotifications"
          aria-controls="notification-panel"
          @click.stop="toggleNotifications"
        >
          <img :src="assets.bell" alt="Notifikasi" />
          <span class="attendance__indicator" aria-hidden="true"></span>
        </button>
        <button
          ref="profileTrigger"
          type="button"
          class="attendance__profile"
          aria-haspopup="true"
          :aria-expanded="showProfile"
          aria-controls="profile-panel"
          @click.stop="toggleProfile"
        >
          <img :src="profileAvatar" :alt="`Profil ${profileName}`" />
        </button>
      </div>

      <transition name="attendance-fade">
        <section
          v-if="showNotifications"
          id="notification-panel"
          ref="notificationPanel"
          class="attendance__popover attendance__popover--notifications"
          role="status"
        >
          <header>
            <h2>Notifikasi</h2>
            <p>Update terakhir hari ini</p>
          </header>
          <ul>
            <li v-for="notification in notifications" :key="notification.id">
              <span class="attendance__bullet" :class="`attendance__bullet--${notification.status}`"></span>
              <div>
                <p class="attendance__popover-title">{{ notification.title }}</p>
                <p class="attendance__popover-sub">{{ notification.subtitle }}</p>
              </div>
            </li>
          </ul>
          <button type="button" class="attendance__popover-action">Tandai semua telah dibaca</button>
        </section>
      </transition>

      <transition name="attendance-fade">
        <section
          v-if="showProfile"
          id="profile-panel"
          ref="profilePanel"
          class="attendance__popover attendance__popover--profile"
          role="dialog"
          aria-label="Informasi profil guru"
        >
          <img :src="profileAvatar" :alt="`Avatar ${profileName}`" />
          <p class="attendance__profile-name">{{ profileName }}</p>
          <p class="attendance__profile-email">{{ profileEmail }}</p>
          <button type="button" class="attendance__logout" @click="handleLogout">Keluar</button>
        </section>
      </transition>
    </header>

    <div class="attendance__layout">
      <aside class="attendance__sidebar" aria-label="Navigasi samping">
        <div class="attendance__sidebar-section">
          <p class="attendance__sidebar-label">Menu Utama</p>
          <ul>
            <li
              v-for="item in mainMenu"
              :key="item.label"
              class="attendance__sidebar-item"
              :class="{ 'attendance__sidebar-item--active': item.active }"
            >
              <button type="button" class="attendance__sidebar-link" @click="handleSidebarClick(item)">
                <span class="attendance__sidebar-icon" :style="{ background: item.accent }">
                  <component :is="item.icon"></component>
                </span>
                <span>{{ item.label }}</span>
              </button>
            </li>
          </ul>
        </div>

        <div class="attendance__sidebar-section">
          <p class="attendance__sidebar-label">Manajemen Akademik</p>
          <ul>
            <li
              v-for="item in academicMenu"
              :key="item.label"
              class="attendance__sidebar-item"
              :class="{ 'attendance__sidebar-item--active': item.active }"
            >
              <button type="button" class="attendance__sidebar-link attendance__sidebar-link--wide" @click="handleSidebarClick(item)">
                <span class="attendance__sidebar-icon attendance__sidebar-icon--outline">
                  <component :is="item.icon"></component>
                </span>
                <div class="attendance__sidebar-text">
                  <span>{{ item.label }}</span>
                  <small>{{ item.subtitle }}</small>
                </div>
              </button>
            </li>
          </ul>
        </div>

        <div class="attendance__sidebar-section">
          <p class="attendance__sidebar-label">Kehadiran</p>
          <ul>
            <li
              v-for="item in attendanceMenu"
              :key="item.label"
              class="attendance__sidebar-item"
              :class="{ 'attendance__sidebar-item--active': item.active }"
            >
              <button type="button" class="attendance__sidebar-link attendance__sidebar-link--wide" @click="handleSidebarClick(item)">
                <span class="attendance__sidebar-icon attendance__sidebar-icon--outline">
                  <component :is="item.icon"></component>
                </span>
                <div class="attendance__sidebar-text">
                  <span>{{ item.label }}</span>
                  <small>{{ item.subtitle }}</small>
                </div>
              </button>
            </li>
          </ul>
        </div>
      </aside>

      <main class="attendance__main" role="main">
        <section class="attendance__hero">
          <header>
            <p class="attendance__hero-eyebrow">Kehadiran Siswa</p>
            <h1>Rekapitulasi Kehadiran dan Catatan Perkembangan</h1>
            <p>Kelola absensi harian, catat perkembangan siswa, dan pantau statistik kehadiran secara real-time.</p>
          </header>
          <button type="button" class="attendance__hero-button" @click="exportAttendance">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 16l6-6h-4V4h-4v6H6z" fill="currentColor" /><path d="M5 18h14v2H5z" fill="currentColor" /></svg>
            <span>Ekspor Rekap</span>
          </button>
        </section>

        <section class="attendance__summary" aria-label="Ringkasan kehadiran">
          <article
            v-for="card in attendanceSummary"
            :key="card.id"
            class="attendance-summary"
            :style="{ '--attendance-accent': card.accent }"
          >
            <header>
              <h2>{{ card.title }}</h2>
              <p>{{ card.subtitle }}</p>
            </header>
            <strong>{{ card.value }}</strong>
            <footer>{{ card.footer }}</footer>
          </article>
        </section>

        <section class="attendance__details" aria-label="Detail kehadiran siswa">
          <header>
            <div>
              <h2>Detail Kehadiran</h2>
              <p>Pilih kelas dan filter data untuk melihat kehadiran siswa.</p>
            </div>
            <div class="attendance__filters">
              <label>
                <span>Kelas</span>
                <select v-model="activeClass">
                  <option v-for="classOption in classOptions" :key="classOption" :value="classOption">{{ classOption }}</option>
                </select>
              </label>
              <label>
                <span>Tanggal</span>
                <input v-model="activeDate" type="date" />
              </label>
            </div>
          </header>

          <div class="attendance__search">
            <label>
              <span>Cari nama siswa...</span>
              <input v-model="searchQuery" type="search" placeholder="Masukkan nama siswa" />
            </label>
          </div>

          <div class="attendance__table-wrapper">
            <table>
              <thead>
                <tr>
                  <th scope="col">No</th>
                  <th scope="col">Nama Siswa</th>
                  <th scope="col">Status</th>
                  <th scope="col">Catatan Perkembangan Siswa</th>
                  <th scope="col">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(student, index) in filteredStudents" :key="student.id">
                  <td>{{ index + 1 }}</td>
                  <td>
                    <div class="attendance__student">
                      <img :src="student.avatar" :alt="`Avatar ${student.name}`" />
                      <div>
                        <p>{{ student.name }}</p>
                        <small>{{ student.class }}</small>
                      </div>
                    </div>
                  </td>
                  <td>
                    <span class="attendance__status" :style="{ color: student.status.color, background: student.status.background }">
                      {{ student.status.label }}
                    </span>
                  </td>
                  <td>
                    <p class="attendance__note" :class="{ 'attendance__note--empty': !student.note }">
                      {{ student.note || 'Belum ada catatan perkembangan.' }}
                    </p>
                  </td>
                  <td>
                    <div class="attendance__actions-cell">
                      <button type="button" class="attendance__action attendance__action--ghost" @click="openNote(student)">
                        Catatan
                      </button>
                      <button type="button" class="attendance__action" @click="markAttendance(student)">
                        Tandai Hadir
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
            <footer class="attendance__table-footer">
              <p>Menampilkan {{ filteredStudents.length }} dari {{ students.length }} siswa</p>
              <button type="button" @click="loadMore">Muat lainnya</button>
            </footer>
          </div>
        </section>
      </main>
    </div>

    <transition name="attendance-modal">
      <section v-if="activeStudent" class="attendance-modal" role="dialog" aria-modal="true">
        <div class="attendance-modal__backdrop" @click="closeModal"></div>
        <div class="attendance-modal__content" role="document">
          <header>
            <h2>Catatan Perkembangan Siswa</h2>
            <p>Deskripsi Singkat Perkembangan Siswa</p>
          </header>
          <form @submit.prevent="saveNote">
            <label>
              <span>Catatan untuk {{ activeStudent.name }}</span>
              <textarea v-model="noteDraft" rows="4" placeholder="Tuliskan deskripsi singkat perkembangan siswa.."></textarea>
            </label>
            <footer>
              <button type="button" class="attendance-modal__ghost" @click="closeModal">Batal</button>
              <button type="submit">Simpan</button>
            </footer>
          </form>
        </div>
      </section>
    </transition>

    <transition-group name="attendance-toast" tag="ol" class="attendance__toasts" aria-live="polite" aria-atomic="true">
      <li v-for="toast in toasts" :key="toast.id" class="attendance-toast">
        <span class="attendance-toast__icon">✓</span>
        <div>
          <p class="attendance-toast__title">{{ toast.title }}</p>
          <p class="attendance-toast__subtitle">{{ toast.subtitle }}</p>
        </div>
        <button type="button" class="attendance-toast__close" @click="dismissToast(toast.id)" aria-label="Tutup notifikasi">×</button>
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
};

const router = useRouter();
const route = useRoute();
const { user, logout } = useAuth();

const organizationName = computed(() => user.value?.organization ?? 'Terminal Pintar');
const roleLabel = computed(() => 'Dashboard Guru');
const profileName = computed(() => user.value?.name ?? 'Guru Terminal Pintar');
const profileEmail = computed(() => user.value?.contact?.email ?? 'guru@terminalpintar.id');
const profileAvatar = computed(() => user.value?.avatar ?? 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=200&q=80');

const notifications = [
  {
    id: 'notif-1',
    title: 'Catatan baru tersimpan',
    subtitle: 'Rencana tindak lanjut untuk kelas 9A',
    status: 'success',
  },
  {
    id: 'notif-2',
    title: 'Izin siswa disetujui',
    subtitle: 'Siswa Nurul memiliki surat izin orang tua',
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

const attendanceSummary = [
  { id: 'present', title: 'Hadir', subtitle: 'Siswa', value: '26', footer: '80% dari total siswa', accent: 'linear-gradient(135deg, rgba(118,179,64,0.12), rgba(118,179,64,0.32))' },
  { id: 'permit', title: 'Izin', subtitle: 'Siswa', value: '3', footer: 'Sesuai surat orang tua', accent: 'linear-gradient(135deg, rgba(241,186,73,0.16), rgba(250,214,138,0.38))' },
  { id: 'sick', title: 'Sakit', subtitle: 'Siswa', value: '1', footer: 'Perlu pemantauan wali kelas', accent: 'linear-gradient(135deg, rgba(120,170,220,0.18), rgba(188,214,245,0.35))' },
  { id: 'alpha', title: 'Alpha', subtitle: 'Siswa', value: '1', footer: 'Tindak lanjut orang tua', accent: 'linear-gradient(135deg, rgba(233,84,84,0.15), rgba(246,173,173,0.32))' },
];

const classOptions = ['Kelas 9A', 'Kelas 9B', 'Kelas 9C'];
const activeClass = ref(classOptions[0]);
const activeDate = ref(new Date().toISOString().slice(0, 10));
const searchQuery = ref('');

const students = reactive([
  {
    id: 'student-1',
    name: 'Hasni Jauziah',
    class: 'Kelas 9A',
    avatar: 'https://images.unsplash.com/photo-1521119989659-a83eee488004?auto=format&fit=crop&w=128&q=80',
    status: { label: 'Hadir', color: '#2f7d31', background: 'rgba(118,179,64,0.14)' },
    note: 'Mampu memimpin diskusi kelompok dengan percaya diri.',
  },
  {
    id: 'student-2',
    name: 'Erythia Sosilabiae',
    class: 'Kelas 9A',
    avatar: 'https://images.unsplash.com/photo-1549068106-b024baf5062d?auto=format&fit=crop&w=128&q=80',
    status: { label: 'Izin', color: '#c27a11', background: 'rgba(236,180,84,0.18)' },
    note: '',
  },
  {
    id: 'student-3',
    name: 'Muhammad Umar',
    class: 'Kelas 9A',
    avatar: 'https://images.unsplash.com/photo-1544723795-3fb6469f5b39?auto=format&fit=crop&w=128&q=80',
    status: { label: 'Hadir', color: '#2f7d31', background: 'rgba(118,179,64,0.14)' },
    note: 'Sudah bisa menciptakan puisi.',
  },
  {
    id: 'student-4',
    name: 'Viya Siti Yopi',
    class: 'Kelas 9A',
    avatar: 'https://images.unsplash.com/photo-1544723795-3fb6469f5b39?auto=format&fit=crop&w=128&q=80',
    status: { label: 'Sakit', color: '#236091', background: 'rgba(120,170,220,0.18)' },
    note: '',
  },
  {
    id: 'student-5',
    name: 'Kolan Mahendra',
    class: 'Kelas 9A',
    avatar: 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=128&q=80',
    status: { label: 'Alpha', color: '#c2332f', background: 'rgba(233,84,84,0.15)' },
    note: 'Belum mengumpulkan tugas minggu ini.',
  },
  {
    id: 'student-6',
    name: 'Dwi Nugraheni',
    class: 'Kelas 9A',
    avatar: 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=128&q=80',
    status: { label: 'Hadir', color: '#2f7d31', background: 'rgba(118,179,64,0.14)' },
    note: '',
  },
]);

const filteredStudents = computed(() =>
  students.filter((student) => {
    const matchesClass = student.class === activeClass.value;
    const matchesQuery = student.name.toLowerCase().includes(searchQuery.value.trim().toLowerCase());
    return matchesClass && matchesQuery;
  }),
);

const toasts = ref([]);
const toastTimers = new Map();

const pushToast = (title, subtitle) => {
  const id = `toast-${Date.now()}-${Math.random().toString(36).slice(2, 6)}`;
  toasts.value.push({ id, title, subtitle });

  const timer = window.setTimeout(() => dismissToast(id), 4200);
  toastTimers.set(id, timer);
};

const dismissToast = (id) => {
  const timer = toastTimers.get(id);
  if (timer) {
    clearTimeout(timer);
    toastTimers.delete(id);
  }
  toasts.value = toasts.value.filter((toast) => toast.id !== id);
};

const notificationPanel = ref(null);
const profilePanel = ref(null);
const notificationTrigger = ref(null);
const profileTrigger = ref(null);
const headerRef = ref(null);
const isScrolled = ref(false);
const showNotifications = ref(false);
const showProfile = ref(false);

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
  closeOverlays();
  logout();
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
    if (activeStudent.value) {
      closeModal();
    } else {
      closeOverlays();
    }
  }
};

const handleSidebarClick = (item) => {
  if (!item?.to) return;
  if (item.to.name === route.name && !item.to.query) return;
  router.push(item.to);
};

const exportAttendance = () => {
  pushToast('Rekap Kehadiran Disiapkan', `${activeClass.value} • ${new Date(activeDate.value).toLocaleDateString('id-ID')}`);
};

const loadMore = () => {
  pushToast('Semua data ditampilkan', 'Seluruh siswa kelas telah dimuat.');
};

const activeStudent = ref(null);
const noteDraft = ref('');

const openNote = (student) => {
  activeStudent.value = student;
  noteDraft.value = student.note;
};

const closeModal = () => {
  activeStudent.value = null;
  noteDraft.value = '';
};

const saveNote = () => {
  if (activeStudent.value) {
    activeStudent.value.note = noteDraft.value.trim();
    pushToast('Catatan Disimpan', `${activeStudent.value.name} • ${activeClass.value}`);
  }
  closeModal();
};

const markAttendance = (student) => {
  student.status = { label: 'Hadir', color: '#2f7d31', background: 'rgba(118,179,64,0.14)' };
  pushToast('Status Kehadiran Diperbarui', `${student.name} ditandai hadir.`);
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

watch(
  () => route.name,
  () => {
    closeOverlays();
  },
);

watch(
  () => ({ focus: route.query.focus, studentId: route.query.student }),
  ({ focus, studentId }) => {
    if (focus !== 'notes') {
      return;
    }

    const sanitizedQuery = { ...route.query };
    delete sanitizedQuery.focus;
    delete sanitizedQuery.student;
    delete sanitizedQuery.session;
    delete sanitizedQuery.from;

    const targetStudent = students.find((student) => student.id === studentId) ?? students[0];
    if (targetStudent) {
      openNote(targetStudent);
    }

    router.replace({ name: route.name ?? 'teacher-attendance', query: sanitizedQuery });
  },
  { immediate: true },
);
</script>

<style scoped>
.attendance {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  background: linear-gradient(160deg, #fef7ff 0%, #f9fff4 55%, #f3fbf0 100%);
  color: #1f1f1f;
}

.attendance__header {
  position: sticky;
  top: 0;
  z-index: 10;
  display: grid;
  grid-template-columns: auto 1fr auto;
  align-items: center;
  gap: 2rem;
  padding: 1.25rem 3vw;
  background: linear-gradient(135deg, rgba(255, 255, 255, 0.88), rgba(255, 255, 255, 0.68));
  border-bottom: 1px solid rgba(118, 179, 64, 0.1);
  backdrop-filter: blur(16px);
  transition: all 0.3s ease;
}

.attendance__header--glass {
  box-shadow: 0 18px 48px -30px rgba(57, 70, 62, 0.45);
}

.attendance__brand {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.attendance__logo {
  width: 44px;
  height: 44px;
  object-fit: cover;
  border-radius: 14px;
  box-shadow: 0 12px 30px -16px rgba(74, 124, 44, 0.8);
}

.attendance__brand-text {
  display: flex;
  flex-direction: column;
  gap: 0.15rem;
}

.attendance__brand-name {
  font-size: 1.35rem;
  font-weight: 700;
  letter-spacing: -0.02em;
  color: #76b340;
}

.attendance__brand-sub {
  font-size: 0.85rem;
  color: rgba(31, 31, 31, 0.72);
}

.attendance__top-nav {
  display: flex;
  justify-content: flex-end;
}

.attendance__language {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.35rem 0.85rem;
  border-radius: 999px;
  border: 1px solid rgba(118, 179, 64, 0.3);
  background: rgba(255, 255, 255, 0.64);
  color: #1f1f1f;
  font-weight: 500;
  transition: all 0.3s ease;
}

.attendance__language svg {
  width: 1rem;
  height: 1rem;
  color: #76b340;
}

.attendance__language:hover {
  border-color: rgba(118, 179, 64, 0.6);
  box-shadow: 0 8px 20px -16px rgba(118, 179, 64, 0.9);
}

.attendance__actions {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.attendance__icon-button,
.attendance__profile {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 42px;
  height: 42px;
  border-radius: 14px;
  border: none;
  background: rgba(255, 255, 255, 0.64);
  box-shadow: 0 16px 32px -20px rgba(31, 31, 31, 0.45);
  cursor: pointer;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.attendance__profile img {
  width: 100%;
  height: 100%;
  border-radius: inherit;
  object-fit: cover;
}

.attendance__indicator {
  position: absolute;
  top: 10px;
  right: 10px;
  width: 10px;
  height: 10px;
  border-radius: 50%;
  background: #ff6b6b;
  border: 2px solid #fff;
}

.attendance__icon-button:hover,
.attendance__profile:hover {
  transform: translateY(-2px);
  box-shadow: 0 18px 36px -24px rgba(31, 31, 31, 0.6);
}

.attendance__popover {
  position: absolute;
  right: 3vw;
  top: calc(100% + 0.75rem);
  width: clamp(260px, 24vw, 320px);
  padding: 1rem 1.25rem;
  border-radius: 20px;
  background: #ffffff;
  box-shadow: 0 30px 60px -40px rgba(31, 31, 31, 0.45);
  border: 1px solid rgba(118, 179, 64, 0.08);
}

.attendance__popover header {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
  margin-bottom: 0.75rem;
}

.attendance__popover header h2 {
  font-size: 1rem;
  font-weight: 600;
}

.attendance__popover header p {
  font-size: 0.85rem;
  color: rgba(31, 31, 31, 0.65);
}

.attendance__popover ul {
  display: flex;
  flex-direction: column;
  gap: 0.85rem;
  margin: 0;
  padding: 0;
  list-style: none;
}

.attendance__popover ul li {
  display: grid;
  grid-template-columns: auto 1fr;
  gap: 0.75rem;
}

.attendance__bullet {
  display: inline-flex;
  width: 12px;
  height: 12px;
  margin-top: 0.25rem;
  border-radius: 50%;
}

.attendance__bullet--success {
  background: #76b340;
}

.attendance__bullet--info {
  background: #5aa1f2;
}

.attendance__popover-title {
  font-size: 0.95rem;
  font-weight: 600;
  color: #1f1f1f;
}

.attendance__popover-sub {
  font-size: 0.82rem;
  color: rgba(31, 31, 31, 0.65);
}

.attendance__popover-action {
  margin-top: 1rem;
  width: 100%;
  padding: 0.65rem 1rem;
  border-radius: 12px;
  border: none;
  background: #f3f9ec;
  color: #4b7d2f;
  font-weight: 600;
  cursor: pointer;
}

.attendance__layout {
  display: grid;
  grid-template-columns: 280px 1fr;
  gap: 2rem;
  padding: 2.5rem 3vw 4rem;
}

.attendance__sidebar {
  display: flex;
  flex-direction: column;
  gap: 2rem;
}

.attendance__sidebar-section ul {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  flex-direction: column;
  gap: 0.8rem;
}

.attendance__sidebar-label {
  font-size: 0.95rem;
  font-weight: 600;
  margin-bottom: 0.65rem;
  color: rgba(31, 31, 31, 0.62);
}

.attendance__sidebar-item {
  position: relative;
}

.attendance__sidebar-item--active::before {
  content: '';
  position: absolute;
  inset: 0;
  border-radius: 16px;
  background: linear-gradient(135deg, rgba(118, 179, 64, 0.12), rgba(118, 179, 64, 0.28));
  z-index: -1;
  transform: translateX(-12px);
}

.attendance__sidebar-link {
  width: 100%;
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.65rem 0.9rem;
  border: none;
  background: transparent;
  font-weight: 600;
  color: #1f1f1f;
  cursor: pointer;
  transition: transform 0.2s ease;
}

.attendance__sidebar-link:hover {
  transform: translateX(4px);
}

.attendance__sidebar-link--wide {
  justify-content: flex-start;
  align-items: flex-start;
}

.attendance__sidebar-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 42px;
  height: 42px;
  border-radius: 14px;
  background: rgba(118, 179, 64, 0.18);
  color: #2f7d31;
}

.attendance__sidebar-icon svg {
  width: 22px;
  height: 22px;
}

.attendance__sidebar-icon--outline {
  background: rgba(255, 255, 255, 0.9);
  border: 1px solid rgba(118, 179, 64, 0.18);
  color: #2f7d31;
}

.attendance__sidebar-text {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  gap: 0.25rem;
}

.attendance__sidebar-text small {
  font-size: 0.78rem;
  color: rgba(31, 31, 31, 0.58);
}

.attendance__main {
  display: flex;
  flex-direction: column;
  gap: 2rem;
}

.attendance__hero {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: 1.5rem;
  padding: 2rem 2.25rem;
  border-radius: 28px;
  background: linear-gradient(135deg, rgba(118, 179, 64, 0.16), rgba(118, 179, 64, 0.05));
  box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.6);
}

.attendance__hero header {
  max-width: 60ch;
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.attendance__hero-eyebrow {
  font-size: 0.85rem;
  font-weight: 600;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  color: rgba(31, 31, 31, 0.6);
}

.attendance__hero h1 {
  font-size: clamp(1.75rem, 4vw, 2.4rem);
  color: #1f1f1f;
  line-height: 1.3;
}

.attendance__hero p {
  font-size: 1rem;
  color: rgba(31, 31, 31, 0.7);
}

.attendance__hero-button {
  display: inline-flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.85rem 1.4rem;
  border-radius: 999px;
  border: none;
  background: #76b340;
  color: #ffffff;
  font-weight: 600;
  cursor: pointer;
  box-shadow: 0 16px 28px -18px rgba(118, 179, 64, 0.75);
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.attendance__hero-button svg {
  width: 20px;
  height: 20px;
}

.attendance__hero-button:hover {
  transform: translateY(-2px);
  box-shadow: 0 20px 36px -20px rgba(118, 179, 64, 0.85);
}

.attendance__summary {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
  gap: 1.25rem;
}

.attendance-summary {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
  padding: 1.4rem 1.6rem;
  border-radius: 22px;
  background: var(--attendance-accent);
  border: 1px solid rgba(255, 255, 255, 0.6);
  box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.5);
}

.attendance-summary header h2 {
  font-size: 1.4rem;
  color: #1f1f1f;
}

.attendance-summary header p {
  font-size: 0.85rem;
  color: rgba(31, 31, 31, 0.65);
}

.attendance-summary strong {
  font-size: 2.6rem;
  font-weight: 700;
  color: #1f1f1f;
}

.attendance-summary footer {
  font-size: 0.85rem;
  color: rgba(31, 31, 31, 0.6);
}

.attendance__details {
  padding: 2rem 2.25rem;
  border-radius: 28px;
  background: rgba(255, 255, 255, 0.82);
  box-shadow: 0 25px 60px -40px rgba(57, 70, 62, 0.35);
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.attendance__details header {
  display: flex;
  justify-content: space-between;
  gap: 1.5rem;
  flex-wrap: wrap;
}

.attendance__details header h2 {
  font-size: 1.6rem;
  margin: 0;
}

.attendance__details header p {
  margin: 0.4rem 0 0;
  color: rgba(31, 31, 31, 0.6);
}

.attendance__filters {
  display: flex;
  gap: 1rem;
}

.attendance__filters label {
  display: flex;
  flex-direction: column;
  gap: 0.4rem;
  font-size: 0.85rem;
  color: rgba(31, 31, 31, 0.6);
}

.attendance__filters select,
.attendance__filters input {
  padding: 0.6rem 0.8rem;
  border-radius: 12px;
  border: 1px solid rgba(31, 31, 31, 0.1);
  background: #ffffff;
  font-size: 0.95rem;
}

.attendance__search label {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.attendance__search input {
  padding: 0.7rem 1rem;
  border-radius: 14px;
  border: 1px solid rgba(31, 31, 31, 0.12);
  font-size: 0.95rem;
  background: rgba(118, 179, 64, 0.04);
}

.attendance__table-wrapper {
  overflow: hidden;
  border-radius: 22px;
  border: 1px solid rgba(31, 31, 31, 0.08);
  background: #ffffff;
  box-shadow: 0 20px 45px -40px rgba(57, 70, 62, 0.45);
}

.attendance__table-wrapper table {
  width: 100%;
  border-collapse: collapse;
}

.attendance__table-wrapper thead {
  background: rgba(118, 179, 64, 0.08);
}

.attendance__table-wrapper th,
.attendance__table-wrapper td {
  text-align: left;
  padding: 1rem;
  font-size: 0.92rem;
}

.attendance__table-wrapper tbody tr:nth-child(even) {
  background: rgba(118, 179, 64, 0.02);
}

.attendance__student {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.attendance__student img {
  width: 42px;
  height: 42px;
  border-radius: 50%;
  object-fit: cover;
}

.attendance__student small {
  color: rgba(31, 31, 31, 0.6);
}

.attendance__status {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 0.35rem 0.8rem;
  border-radius: 999px;
  font-weight: 600;
  font-size: 0.82rem;
}

.attendance__note {
  margin: 0;
  font-size: 0.9rem;
  color: rgba(31, 31, 31, 0.8);
  max-width: 36ch;
}

.attendance__note--empty {
  color: rgba(31, 31, 31, 0.45);
  font-style: italic;
}

.attendance__actions-cell {
  display: flex;
  gap: 0.5rem;
}

.attendance__action {
  padding: 0.5rem 0.9rem;
  border-radius: 12px;
  border: none;
  background: #76b340;
  color: #ffffff;
  font-size: 0.85rem;
  font-weight: 600;
  cursor: pointer;
}

.attendance__action--ghost {
  background: rgba(118, 179, 64, 0.12);
  color: #2f7d31;
}

.attendance__table-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.9rem 1.2rem;
  border-top: 1px solid rgba(31, 31, 31, 0.08);
  font-size: 0.85rem;
  color: rgba(31, 31, 31, 0.6);
}

.attendance__table-footer button {
  padding: 0.45rem 0.85rem;
  border-radius: 10px;
  border: none;
  background: rgba(118, 179, 64, 0.12);
  color: #2f7d31;
  font-weight: 600;
  cursor: pointer;
}

.attendance-modal {
  position: fixed;
  inset: 0;
  display: grid;
  place-items: center;
  z-index: 20;
}

.attendance-modal__backdrop {
  position: absolute;
  inset: 0;
  background: rgba(15, 23, 42, 0.32);
}

.attendance-modal__content {
  position: relative;
  z-index: 1;
  width: min(480px, 92vw);
  padding: 2rem;
  border-radius: 24px;
  background: #ffffff;
  box-shadow: 0 30px 60px -40px rgba(15, 23, 42, 0.45);
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.attendance-modal__content header h2 {
  margin: 0;
  font-size: 1.6rem;
  color: #4b7d2f;
}

.attendance-modal__content header p {
  margin: 0.35rem 0 0;
  color: rgba(31, 31, 31, 0.65);
}

.attendance-modal form {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.attendance-modal label {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  font-size: 0.95rem;
  color: rgba(31, 31, 31, 0.7);
}

.attendance-modal textarea {
  resize: vertical;
  min-height: 140px;
  padding: 0.85rem 1rem;
  border-radius: 16px;
  border: 1px solid rgba(31, 31, 31, 0.12);
  background: rgba(118, 179, 64, 0.04);
  font-size: 0.95rem;
}

.attendance-modal footer {
  display: flex;
  justify-content: flex-end;
  gap: 0.75rem;
}

.attendance-modal__ghost {
  padding: 0.6rem 1.1rem;
  border-radius: 14px;
  border: none;
  background: rgba(118, 179, 64, 0.12);
  color: #2f7d31;
  font-weight: 600;
  cursor: pointer;
}

.attendance-modal footer button[type='submit'] {
  padding: 0.6rem 1.2rem;
  border-radius: 14px;
  border: none;
  background: #76b340;
  color: #ffffff;
  font-weight: 600;
  cursor: pointer;
}

.attendance__toasts {
  position: fixed;
  right: 2.5rem;
  bottom: 2.5rem;
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
  list-style: none;
  margin: 0;
  padding: 0;
  z-index: 30;
}

.attendance-toast {
  display: grid;
  grid-template-columns: auto 1fr auto;
  align-items: center;
  gap: 0.85rem;
  padding: 0.95rem 1.15rem;
  border-radius: 18px;
  background: rgba(255, 255, 255, 0.92);
  border: 1px solid rgba(118, 179, 64, 0.22);
  box-shadow: 0 20px 40px -30px rgba(31, 31, 31, 0.55);
}

.attendance-toast__icon {
  width: 28px;
  height: 28px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  background: rgba(118, 179, 64, 0.16);
  color: #2f7d31;
  font-weight: 700;
}

.attendance-toast__title {
  font-size: 0.95rem;
  font-weight: 600;
  color: #1f1f1f;
}

.attendance-toast__subtitle {
  font-size: 0.82rem;
  color: rgba(31, 31, 31, 0.6);
}

.attendance-toast__close {
  background: none;
  border: none;
  font-size: 1.25rem;
  line-height: 1;
  cursor: pointer;
  color: rgba(31, 31, 31, 0.45);
}

.attendance-fade-enter-active,
.attendance-fade-leave-active {
  transition: opacity 0.2s ease, transform 0.2s ease;
}

.attendance-fade-enter-from,
.attendance-fade-leave-to {
  opacity: 0;
  transform: translateY(-6px);
}

.attendance-modal-enter-active,
.attendance-modal-leave-active {
  transition: opacity 0.25s ease;
}

.attendance-modal-enter-from,
.attendance-modal-leave-to {
  opacity: 0;
}

.attendance-toast-enter-active,
.attendance-toast-leave-active {
  transition: all 0.3s ease;
}

.attendance-toast-enter-from,
.attendance-toast-leave-to {
  opacity: 0;
  transform: translateY(12px);
}

@media (max-width: 1080px) {
  .attendance__layout {
    grid-template-columns: 1fr;
  }

  .attendance__sidebar {
    position: sticky;
    top: 88px;
    flex-direction: row;
    align-items: flex-start;
    gap: 1.25rem;
    overflow-x: auto;
  }

  .attendance__sidebar-section {
    min-width: 220px;
  }
}

@media (max-width: 720px) {
  .attendance__header {
    grid-template-columns: auto auto;
    grid-template-areas:
      'brand actions'
      'language language';
    gap: 1rem;
  }

  .attendance__brand {
    grid-area: brand;
  }

  .attendance__top-nav {
    grid-area: language;
    justify-content: flex-start;
  }

  .attendance__actions {
    grid-area: actions;
  }

  .attendance__hero {
    flex-direction: column;
  }

  .attendance__details header {
    flex-direction: column;
  }

  .attendance__filters {
    width: 100%;
    justify-content: space-between;
  }
}
</style>
