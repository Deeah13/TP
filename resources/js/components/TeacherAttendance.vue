<template>
  <TeacherWorkspaceLayout
    :notifications="notifications"
    notification-heading="Ringkasan Kehadiran"
    notification-subheading="Aktivitas terbaru siswa"
    @logout="handleLogout"
  >
    <section class="attendance__hero" aria-label="Kehadiran siswa">
      <div class="attendance__hero-copy">
        <p class="attendance__hero-eyebrow">Kehadiran</p>
        <h1>Monitor Kehadiran & Catatan Siswa</h1>
        <p>
          Catat kehadiran secara real time, tambahkan catatan pembelajaran, dan bagikan umpan balik kepada orang tua dalam satu tempat.
        </p>
        <div class="attendance__hero-actions">
          <button type="button" @click="exportSummary">Unduh Rekap</button>
          <button type="button" class="attendance__hero-secondary" @click="openNoteModal(records[0])">Tambah Catatan</button>
        </div>
      </div>
      <article class="attendance__status" role="status">
        <span>Rekap Hari Ini</span>
        <strong>{{ presentToday }} siswa hadir</strong>
        <p>{{ summaryCopy }}</p>
        <button type="button" @click="toSchedule">Kelola Jadwal</button>
      </article>
    </section>

    <section class="attendance__summary" aria-label="Ringkasan statistik">
      <article v-for="card in summaryCards" :key="card.title" class="attendance-card" :style="{ '--accent': card.accent }">
        <div class="attendance-card__icon">
          <component :is="card.icon" />
        </div>
        <div class="attendance-card__content">
          <span>{{ card.meta }}</span>
          <strong>{{ card.value }}</strong>
          <p>{{ card.title }}</p>
        </div>
      </article>
    </section>

    <section class="attendance__controls" aria-label="Filter kehadiran">
      <div class="attendance__filters">
        <label>
          <span>Tanggal</span>
          <input type="date" v-model="filters.date" />
        </label>
        <label>
          <span>Kelas</span>
          <select v-model="filters.classroom">
            <option value="7A">Kelas 7A</option>
            <option value="7B">Kelas 7B</option>
            <option value="7C">Kelas 7C</option>
          </select>
        </label>
        <label class="attendance__search">
          <span class="sr-only">Cari siswa</span>
          <input type="search" v-model="filters.search" placeholder="Cari nama siswa" />
        </label>
      </div>
      <div class="attendance__control-actions">
        <button type="button" @click="markAllPresent">Tandai Semua Hadir</button>
      </div>
    </section>

    <section class="attendance__table" aria-label="Daftar kehadiran siswa">
      <header>
        <h2>Daftar Kehadiran</h2>
        <p>Perbarui status kehadiran dan catatan pembelajaran siswa.</p>
      </header>
      <div class="attendance__table-wrapper">
        <table>
          <thead>
            <tr>
              <th>Nama Siswa</th>
              <th>Kelas</th>
              <th>Status</th>
              <th>Catatan</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="record in filteredRecords" :key="record.id">
              <td>
                <div class="attendance__student">
                  <img :src="record.avatar" :alt="`Avatar ${record.name}`" />
                  <div>
                    <strong>{{ record.name }}</strong>
                    <small>{{ record.idNumber }}</small>
                  </div>
                </div>
              </td>
              <td>{{ record.classroom }}</td>
              <td>
                <span class="attendance__badge" :class="`attendance__badge--${record.status}`">{{ statusLabel(record.status) }}</span>
              </td>
              <td>
                <button type="button" class="attendance__note" @click="openNoteModal(record)">
                  {{ record.note ? 'Lihat Catatan' : 'Tambah Catatan' }}
                </button>
              </td>
              <td>
                <div class="attendance__actions">
                  <button type="button" @click="updateStatus(record, 'present')">Hadir</button>
                  <button type="button" @click="updateStatus(record, 'permission')">Izin</button>
                  <button type="button" @click="updateStatus(record, 'absent')">Alpa</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <footer class="attendance__footer">
        <span>{{ filteredRecords.length }} siswa ditemukan</span>
        <button type="button" @click="exportSummary">Ekspor CSV</button>
      </footer>
    </section>

    <transition-group name="attendance-toast" tag="ol" class="attendance__toasts" aria-live="polite" aria-atomic="true">
      <li v-for="toast in toasts" :key="toast.id" class="attendance-toast">
        <span class="attendance-toast__icon">✓</span>
        <div class="attendance-toast__content">
          <p class="attendance-toast__title">{{ toast.title }}</p>
          <p class="attendance-toast__subtitle">{{ toast.subtitle }}</p>
        </div>
        <button type="button" class="attendance-toast__close" aria-label="Tutup notifikasi" @click="dismissToast(toast.id)">×</button>
      </li>
    </transition-group>

    <transition name="attendance-modal">
      <section v-if="selectedStudent" class="attendance-modal" role="dialog" aria-modal="true">
        <div class="attendance-modal__backdrop" @click="closeNoteModal"></div>
        <div class="attendance-modal__content" role="document">
          <header>
            <h2>Catatan untuk {{ selectedStudent.name }}</h2>
            <p>Tambahkan catatan perkembangan pembelajaran dan rencana tindak lanjut.</p>
          </header>
          <form class="attendance-modal__form" @submit.prevent="submitNote">
            <label>
              <span>Catatan Guru</span>
              <textarea v-model="noteDraft" rows="5" placeholder="Tuliskan catatan perkembangan siswa"></textarea>
            </label>
            <footer>
              <button type="button" class="attendance-modal__ghost" @click="closeNoteModal">Batal</button>
              <button type="submit">Simpan Catatan</button>
            </footer>
          </form>
        </div>
      </section>
    </transition>
  </TeacherWorkspaceLayout>
</template>

<script setup>
import { computed, onBeforeUnmount, onMounted, reactive, ref, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import TeacherWorkspaceLayout from './teacher/TeacherWorkspaceLayout.vue';
import { useAuth } from '../stores/auth';
import { createIcon } from '../utils/iconFactory';

const router = useRouter();
const route = useRoute();
const { logout } = useAuth();

const filters = reactive({
  date: '2025-10-21',
  classroom: '7A',
  search: '',
});

const records = ref([
  {
    id: 'student-1',
    name: 'Nadia Putri',
    idNumber: 'TP-2025-001',
    classroom: '7A',
    status: 'present',
    note: 'Sangat aktif bertanya dan membantu teman.',
    avatar: 'https://images.unsplash.com/photo-1544723795-3fb6469f5b39?auto=format&fit=crop&w=120&q=80',
  },
  {
    id: 'student-2',
    name: 'Raka Aditya',
    idNumber: 'TP-2025-002',
    classroom: '7A',
    status: 'permission',
    note: 'Izin mengikuti perlombaan debat.',
    avatar: 'https://images.unsplash.com/photo-1527980965255-d3b416303d12?auto=format&fit=crop&w=120&q=80',
  },
  {
    id: 'student-3',
    name: 'Salsa Ramadhani',
    idNumber: 'TP-2025-003',
    classroom: '7B',
    status: 'absent',
    note: '',
    avatar: 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?auto=format&fit=crop&w=120&q=80',
  },
  {
    id: 'student-4',
    name: 'Bagas Nugraha',
    idNumber: 'TP-2025-004',
    classroom: '7A',
    status: 'present',
    note: '',
    avatar: 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=120&q=80',
  },
]);

const filteredRecords = computed(() => {
  return records.value.filter((record) => {
    const matchesClass = record.classroom === filters.classroom;
    const matchesSearch = record.name.toLowerCase().includes(filters.search.toLowerCase());
    return matchesClass && matchesSearch;
  });
});

const presentToday = computed(() => records.value.filter((record) => record.status === 'present').length);

const summaryCopy = computed(() => {
  const permission = records.value.filter((record) => record.status === 'permission').length;
  const absent = records.value.filter((record) => record.status === 'absent').length;
  if (!permission && !absent) {
    return 'Semua siswa hadir tepat waktu.';
  }
  return `${permission} siswa izin dan ${absent} siswa alpa.`;
});

const summaryIcons = {
  present: createIcon({ d: 'M5 11l4 4L19 7', fill: 'none', stroke: 'currentColor', 'stroke-width': 1.6, 'stroke-linecap': 'round', 'stroke-linejoin': 'round' }),
  permission: createIcon({ d: 'M4 4h16v4H4zm0 6h10v10H4z', fill: 'currentColor' }),
  notes: createIcon({ d: 'M5 3a2 2 0 00-2 2v16l6-3 6 3 6-3V5a2 2 0 00-2-2z', fill: 'currentColor' }),
};

const summaryCards = computed(() => [
  {
    title: 'Hadir',
    value: `${presentToday.value} siswa`,
    meta: 'Kehadiran',
    accent: 'linear-gradient(135deg, rgba(118,179,64,0.2), rgba(118,179,64,0.05))',
    icon: summaryIcons.present,
  },
  {
    title: 'Izin',
    value: `${records.value.filter((r) => r.status === 'permission').length} siswa`,
    meta: 'Perizinan',
    accent: 'linear-gradient(135deg, rgba(96,125,255,0.18), rgba(96,125,255,0.05))',
    icon: summaryIcons.permission,
  },
  {
    title: 'Catatan Baru',
    value: `${records.value.filter((r) => r.note).length} siswa`,
    meta: 'Umpan Balik',
    accent: 'linear-gradient(135deg, rgba(244,143,177,0.2), rgba(255,255,255,0.1))',
    icon: summaryIcons.notes,
  },
]);

const notifications = [
  {
    id: 'notif-1',
    title: '3 catatan baru dari pertemuan hari ini',
    subtitle: 'Bagikan umpan balik kepada orang tua sebelum pukul 17.00.',
    status: 'info',
  },
  {
    id: 'notif-2',
    title: 'Siswa izin mengikuti lomba',
    subtitle: 'Raka Aditya tidak mengikuti sesi Matematika hari ini.',
    status: 'warning',
  },
];

const selectedStudent = ref(null);
const noteDraft = ref('');

const openNoteModal = (record) => {
  if (!record) return;
  selectedStudent.value = record;
  noteDraft.value = record.note ?? '';
};

const closeNoteModal = () => {
  selectedStudent.value = null;
  noteDraft.value = '';
};

const submitNote = () => {
  if (!selectedStudent.value) return;
  selectedStudent.value.note = noteDraft.value.trim();
  pushToast({
    title: 'Catatan Disimpan',
    subtitle: `${selectedStudent.value.name} telah diperbarui.`,
  });
  closeNoteModal();
};

const statusLabel = (status) => {
  switch (status) {
    case 'present':
      return 'Hadir';
    case 'permission':
      return 'Izin';
    default:
      return 'Alpa';
  }
};

const toasts = ref([]);
const toastTimers = new Map();

const pushToast = ({ title, subtitle }) => {
  const id = `toast-${Date.now()}-${Math.random().toString(36).slice(2, 6)}`;
  toasts.value.push({ id, title, subtitle });
  const timer = (typeof window !== 'undefined' ? window.setTimeout : setTimeout)(() => dismissToast(id), 4000);
  toastTimers.set(id, timer);
};

const dismissToast = (id) => {
  const timer = toastTimers.get(id);
  if (timer) {
    (typeof window !== 'undefined' ? window.clearTimeout : clearTimeout)(timer);
    toastTimers.delete(id);
  }
  toasts.value = toasts.value.filter((toast) => toast.id !== id);
};

const updateStatus = (record, status) => {
  record.status = status;
  pushToast({
    title: 'Status Kehadiran Diperbarui',
    subtitle: `${record.name} ditandai sebagai ${statusLabel(status)}.`,
  });
};

const markAllPresent = () => {
  records.value.forEach((record) => {
    record.status = 'present';
  });
  pushToast({
    title: 'Semua Siswa Ditandai Hadir',
    subtitle: 'Rekap kehadiran telah diperbarui.',
  });
};

const exportSummary = () => {
  pushToast({ title: 'Menyiapkan Rekap', subtitle: 'File CSV akan siap diunduh.' });
};

const toSchedule = () => {
  router.push({ name: 'teacher-schedule' });
};

const handleLogout = async () => {
  closeNoteModal();
  toastTimers.forEach((timer) => clearTimeout(timer));
  toastTimers.clear();
  await logout();
  router.replace({ name: 'login', query: { role: 'teacher' } });
};

const handleKeydown = (event) => {
  if (event.key === 'Escape' && selectedStudent.value) {
    closeNoteModal();
  }
};

watch(
  () => route.query,
  (query) => {
    if (query.focus === 'notes' && !selectedStudent.value) {
      openNoteModal(records.value.find((record) => record.classroom === filters.classroom) ?? records.value[0]);
    }
  },
  { immediate: true },
);

onMounted(() => {
  if (typeof document !== 'undefined') {
    document.addEventListener('keydown', handleKeydown);
  }
});

onBeforeUnmount(() => {
  if (typeof document !== 'undefined') {
    document.removeEventListener('keydown', handleKeydown);
  }
  toastTimers.forEach((timer) => clearTimeout(timer));
  toastTimers.clear();
});
</script>

<style scoped>
.attendance__hero {
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

.attendance__hero-copy > p {
  margin: 0;
  color: rgba(31, 31, 31, 0.7);
  line-height: 1.6;
}

.attendance__hero-eyebrow {
  margin: 0;
  font-size: 0.9rem;
  font-weight: 600;
  color: rgba(31, 31, 31, 0.65);
  text-transform: uppercase;
  letter-spacing: 0.08em;
}

.attendance__hero h1 {
  margin: 0.2rem 0 1rem;
  font-size: 2.05rem;
  color: #1f2937;
}

.attendance__hero-actions {
  margin-top: 1.75rem;
  display: flex;
  flex-wrap: wrap;
  gap: 0.85rem;
}

.attendance__hero-actions button {
  padding: 0.8rem 1.4rem;
  border-radius: 16px;
  border: none;
  font-weight: 600;
  cursor: pointer;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.attendance__hero-actions button:first-of-type {
  background: rgba(118, 179, 64, 0.18);
  color: #2f6721;
  box-shadow: 0 18px 40px -24px rgba(47, 103, 33, 0.45);
}

.attendance__hero-actions button:first-of-type:hover {
  transform: translateY(-2px);
}

.attendance__hero-secondary {
  background: rgba(255, 255, 255, 0.85);
  color: rgba(31, 31, 31, 0.75);
  border: 1px solid rgba(118, 179, 64, 0.18);
}

.attendance__hero-secondary:hover {
  transform: translateY(-2px);
  box-shadow: 0 16px 36px -24px rgba(31, 41, 55, 0.3);
}

.attendance__status {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  padding: 1.75rem;
  border-radius: 22px;
  background: rgba(255, 255, 255, 0.78);
  border: 1px solid rgba(255, 255, 255, 0.4);
  box-shadow: 0 20px 60px -35px rgba(23, 43, 77, 0.5);
}

.attendance__status span {
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.08em;
  color: rgba(31, 31, 31, 0.55);
}

.attendance__status strong {
  font-size: 1.3rem;
  color: #2f4f2f;
}

.attendance__status p {
  margin: 0;
  color: rgba(31, 31, 31, 0.6);
}

.attendance__status button {
  margin-top: auto;
  padding: 0.6rem 1.1rem;
  border-radius: 14px;
  border: none;
  background: rgba(118, 179, 64, 0.18);
  color: #2f6721;
  font-weight: 600;
  cursor: pointer;
}

.attendance__summary {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 1.5rem;
}

.attendance-card {
  display: flex;
  gap: 1rem;
  padding: 1.4rem;
  border-radius: 22px;
  background: rgba(255, 255, 255, 0.9);
  border: 1px solid rgba(118, 179, 64, 0.12);
  box-shadow: 0 18px 48px -32px rgba(23, 43, 77, 0.45);
}

.attendance-card::before {
  content: '';
  position: absolute;
}

.attendance-card__icon {
  width: 44px;
  height: 44px;
  border-radius: 16px;
  background: rgba(255, 255, 255, 0.55);
  color: #4a7c2c;
  display: inline-flex;
  align-items: center;
  justify-content: center;
}

.attendance-card__content span {
  font-size: 0.78rem;
  text-transform: uppercase;
  letter-spacing: 0.08em;
  color: rgba(31, 31, 31, 0.55);
}

.attendance-card__content strong {
  display: block;
  margin: 0.35rem 0 0;
  font-size: 1.5rem;
  color: #1f2937;
}

.attendance-card__content p {
  margin: 0.25rem 0 0;
  color: rgba(31, 31, 31, 0.6);
}

.attendance__controls {
  display: flex;
  justify-content: space-between;
  gap: 1.5rem;
  align-items: flex-end;
}

.attendance__filters {
  display: flex;
  flex-wrap: wrap;
  gap: 1rem;
}

.attendance__filters label {
  display: flex;
  flex-direction: column;
  gap: 0.35rem;
  font-size: 0.9rem;
  color: rgba(31, 31, 31, 0.7);
}

.attendance__filters input,
.attendance__filters select {
  padding: 0.75rem 1rem;
  border-radius: 14px;
  border: 1px solid rgba(118, 179, 64, 0.18);
  background: rgba(255, 255, 255, 0.95);
  font-size: 0.95rem;
}

.attendance__search {
  flex: 1 1 220px;
}

.attendance__control-actions button {
  padding: 0.75rem 1.4rem;
  border-radius: 16px;
  border: none;
  background: rgba(118, 179, 64, 0.18);
  color: #2f6721;
  font-weight: 600;
  cursor: pointer;
}

.attendance__table {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
  padding: 1.75rem;
  border-radius: 24px;
  background: rgba(255, 255, 255, 0.92);
  border: 1px solid rgba(118, 179, 64, 0.12);
  box-shadow: 0 20px 60px -38px rgba(23, 43, 77, 0.45);
}

.attendance__table header h2 {
  margin: 0;
  font-size: 1.35rem;
  color: #1f2937;
}

.attendance__table header p {
  margin: 0.3rem 0 0;
  color: rgba(31, 31, 31, 0.6);
}

.attendance__table-wrapper {
  overflow-x: auto;
}

.attendance__table table {
  width: 100%;
  border-collapse: collapse;
  min-width: 640px;
}

.attendance__table th,
.attendance__table td {
  padding: 0.9rem 1rem;
  text-align: left;
  border-bottom: 1px solid rgba(31, 31, 31, 0.08);
  font-size: 0.92rem;
}

.attendance__student {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.attendance__student img {
  width: 42px;
  height: 42px;
  border-radius: 14px;
  object-fit: cover;
}

.attendance__student strong {
  display: block;
  color: #1f2937;
}

.attendance__student small {
  color: rgba(31, 31, 31, 0.55);
}

.attendance__badge {
  padding: 0.35rem 0.75rem;
  border-radius: 999px;
  font-size: 0.78rem;
  font-weight: 600;
}

.attendance__badge--present {
  background: rgba(118, 179, 64, 0.18);
  color: #2f6721;
}

.attendance__badge--permission {
  background: rgba(96, 125, 255, 0.16);
  color: #4154d1;
}

.attendance__badge--absent {
  background: rgba(244, 143, 177, 0.2);
  color: #b8326a;
}

.attendance__note {
  padding: 0.45rem 0.85rem;
  border-radius: 12px;
  border: 1px solid rgba(118, 179, 64, 0.18);
  background: rgba(255, 255, 255, 0.85);
  color: rgba(31, 31, 31, 0.7);
  font-weight: 600;
  cursor: pointer;
}

.attendance__actions {
  display: flex;
  gap: 0.5rem;
}

.attendance__actions button {
  padding: 0.45rem 0.85rem;
  border-radius: 12px;
  border: none;
  background: rgba(118, 179, 64, 0.15);
  color: #2f6721;
  font-weight: 600;
  cursor: pointer;
}

.attendance__actions button:nth-child(2) {
  background: rgba(96, 125, 255, 0.16);
  color: #4154d1;
}

.attendance__actions button:nth-child(3) {
  background: rgba(244, 143, 177, 0.2);
  color: #b8326a;
}

.attendance__footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 0.85rem;
  color: rgba(31, 31, 31, 0.6);
}

.attendance__footer button {
  padding: 0.45rem 0.85rem;
  border-radius: 10px;
  border: none;
  background: rgba(118, 179, 64, 0.15);
  color: #2f6721;
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
  background: rgba(255, 255, 255, 0.94);
  border: 1px solid rgba(118, 179, 64, 0.22);
  box-shadow: 0 20px 45px -30px rgba(57, 70, 62, 0.4);
}

.attendance-toast__icon {
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

.attendance-toast__title {
  margin: 0;
  font-weight: 600;
  color: #1f1f1f;
}

.attendance-toast__subtitle {
  margin: 0.2rem 0 0;
  font-size: 0.82rem;
  color: rgba(31, 31, 31, 0.6);
}

.attendance-toast__close {
  background: none;
  border: none;
  font-size: 1.25rem;
  line-height: 1;
  color: rgba(31, 31, 31, 0.45);
  cursor: pointer;
}

.attendance-modal {
  position: fixed;
  inset: 0;
  display: grid;
  place-items: center;
  z-index: 40;
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

.attendance-modal__form {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.attendance-modal__form label {
  display: flex;
  flex-direction: column;
  gap: 0.35rem;
  font-size: 0.9rem;
  color: rgba(31, 31, 31, 0.7);
}

.attendance-modal__form textarea {
  padding: 0.85rem 1rem;
  border-radius: 16px;
  border: 1px solid rgba(118, 179, 64, 0.18);
  background: rgba(255, 255, 255, 0.95);
  font-size: 0.95rem;
  font-family: inherit;
}

.attendance-modal__form footer {
  display: flex;
  justify-content: flex-end;
  gap: 0.75rem;
}

.attendance-modal__form footer button {
  padding: 0.75rem 1.4rem;
  border-radius: 14px;
  border: none;
  font-weight: 600;
  cursor: pointer;
}

.attendance-modal__ghost {
  background: rgba(255, 255, 255, 0.85);
  color: rgba(31, 31, 31, 0.7);
  border: 1px solid rgba(118, 179, 64, 0.18);
}

.attendance-modal__form footer button:last-child {
  background: rgba(118, 179, 64, 0.18);
  color: #2f6721;
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

.attendance-modal-enter-active,
.attendance-modal-leave-active {
  transition: opacity 0.25s ease;
}

.attendance-modal-enter-from,
.attendance-modal-leave-to {
  opacity: 0;
}

@media (max-width: 960px) {
  .attendance__hero {
    grid-template-columns: 1fr;
  }

  .attendance__status {
    order: -1;
  }
}

@media (max-width: 640px) {
  .attendance__hero {
    padding: 1.8rem;
  }

  .attendance__controls {
    flex-direction: column;
    align-items: stretch;
  }

  .attendance__table-wrapper {
    overflow-x: auto;
  }
}
</style>
