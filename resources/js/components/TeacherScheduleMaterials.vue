<template>
  <TeacherWorkspaceLayout
    :notifications="notifications"
    notification-heading="Aktivitas Jadwal"
    notification-subheading="Ringkasan pekan ini"
    @logout="handleLogout"
  >
    <section class="manager__hero" aria-label="Kelola jadwal dan materi">
      <div class="manager__hero-copy">
        <p class="manager__hero-eyebrow">Kelola Jadwal & Materi</p>
        <h1>Unggah Jadwal dan Materi Pembelajaran</h1>
        <p>
          Catat seluruh pertemuan mingguan, tambah materi pendukung, dan beri catatan khusus untuk siswa. Semua perubahan tersinkron otomatis ke dashboard guru.
        </p>
        <div class="manager__hero-actions">
          <button type="button" @click="openModal('create-schedule')">+ Tambah Jadwal</button>
          <button type="button" class="manager__hero-secondary" @click="openModal('create-material')">Unggah Materi Baru</button>
        </div>
      </div>
      <article class="manager__status" role="status">
        <span>Status Jadwal Mingguan</span>
        <strong>{{ schedules.length }} pertemuan terencana</strong>
        <p>{{ pendingSessionsCopy }}</p>
        <button type="button" @click="openModal('edit-material', focusSessionId)">Lengkapi Materi</button>
      </article>
    </section>

    <section class="manager__toolbar" aria-label="Filter jadwal">
      <div class="manager__filters">
        <label>
          <span>Bulan</span>
          <select v-model="filters.month">
            <option value="2025-10">Oktober 2025</option>
            <option value="2025-11">November 2025</option>
            <option value="2025-12">Desember 2025</option>
          </select>
        </label>
        <label>
          <span>Mata Pelajaran</span>
          <select v-model="filters.subject">
            <option value="all">Semua Mata Pelajaran</option>
            <option value="indonesia">Bahasa Indonesia</option>
            <option value="matematika">Matematika</option>
            <option value="ipa">Ilmu Pengetahuan Alam</option>
          </select>
        </label>
      </div>
      <div class="manager__toolbar-actions">
        <button type="button" @click="openModal('create-schedule')">+ Tambah Jadwal</button>
      </div>
    </section>

    <section class="manager__grid" aria-label="Daftar jadwal dan materi">
      <div class="manager__column">
        <header>
          <h2>Jadwal Pembelajaran</h2>
          <p>Pantau pertemuan terdekat dan lengkapi materinya.</p>
        </header>
        <ul class="manager__sessions">
          <li
            v-for="session in schedules"
            :key="session.id"
            :class="['manager-session', { 'manager-session--focus': session.id === focusSessionId }]"
          >
            <div class="manager-session__time">
              <span>{{ session.day }}</span>
              <strong>{{ session.date }}</strong>
            </div>
            <div class="manager-session__details">
              <h3>{{ session.subject }}</h3>
              <p>{{ session.topic }}</p>
              <small>{{ session.class }}</small>
            </div>
            <div class="manager-session__actions">
              <span class="manager-session__badge" :style="{ background: session.badge.bg, color: session.badge.color }">{{ session.badge.label }}</span>
              <div class="manager-session__buttons">
                <button type="button" @click="openModal('edit-schedule', session.id)">Edit Jadwal</button>
                <button type="button" @click="openModal('edit-material', session.id)">Kelola Materi</button>
              </div>
              <button type="button" class="manager-session__link" @click="goToAttendance(session)">Lihat Kehadiran</button>
            </div>
          </li>
        </ul>
      </div>

      <div class="manager__column">
        <header>
          <h2>Perpustakaan Materi</h2>
          <p>Materi terbaru yang siap dibagikan ke siswa.</p>
        </header>
        <ul class="manager__materials">
          <li v-for="material in materials" :key="material.id" class="manager-material">
            <div class="manager-material__meta">
              <span>{{ material.type }}</span>
              <small>{{ material.updatedAt }}</small>
            </div>
            <h3>{{ material.title }}</h3>
            <p>{{ material.description }}</p>
            <div class="manager-material__footer">
              <button type="button" @click="openModal('edit-material', material.sessionId)">Perbarui</button>
              <button type="button" class="manager-material__ghost" @click="downloadMaterial(material)">Unduh</button>
            </div>
          </li>
        </ul>
      </div>
    </section>

    <transition-group name="manager-toast" tag="ol" class="manager__toasts" aria-live="polite" aria-atomic="true">
      <li v-for="toast in toasts" :key="toast.id" class="manager-toast">
        <span class="manager-toast__icon">✓</span>
        <div class="manager-toast__content">
          <p class="manager-toast__title">{{ toast.title }}</p>
          <p class="manager-toast__subtitle">{{ toast.subtitle }}</p>
        </div>
        <button type="button" class="manager-toast__close" aria-label="Tutup notifikasi" @click="dismissToast(toast.id)">×</button>
      </li>
    </transition-group>

    <transition name="manager-modal">
      <section v-if="isModalOpen" class="manager-modal" role="dialog" aria-modal="true">
        <div class="manager-modal__backdrop" @click="closeModal"></div>
        <div class="manager-modal__content" role="document">
          <header>
            <h2>{{ modalTitle }}</h2>
            <p>{{ modalSubtitle }}</p>
          </header>
          <form class="manager-form" @submit.prevent="handleSubmit(modalState.type)">
            <div class="manager-form__row">
              <label>
                <span>Tanggal</span>
                <input type="date" :value="activeSchedule?.rawDate ?? defaultDate" required />
              </label>
              <label>
                <span>Waktu Mulai</span>
                <input type="time" :value="activeSchedule?.start ?? '09:00'" required />
              </label>
              <label>
                <span>Waktu Selesai</span>
                <input type="time" :value="activeSchedule?.end ?? '10:00'" required />
              </label>
            </div>
            <label>
              <span>Mata Pelajaran</span>
              <input type="text" :value="activeSchedule?.subject ?? ''" required />
            </label>
            <label>
              <span>Topik Pembelajaran</span>
              <input type="text" :value="activeSchedule?.topic ?? ''" required />
            </label>
            <label>
              <span>Deskripsi Materi</span>
              <textarea rows="4" :value="activeSchedule?.description ?? ''"></textarea>
            </label>
            <label class="manager-form__dropzone">
              <input type="file" hidden />
              <strong>Tarik & letakkan berkas atau pilih dari perangkat</strong>
              <small>PDF, PPT, atau Video • Maksimal 50 MB</small>
            </label>
            <footer class="manager-form__actions">
              <button type="button" class="manager-form__ghost" @click="closeModal">Batal</button>
              <button type="submit">Simpan</button>
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
import { TeacherWorkspaceLayout } from './teacher';
import { useAuth } from '../stores/auth';

const router = useRouter();
const route = useRoute();
const { logout } = useAuth();

const filters = reactive({
  month: '2025-10',
  subject: 'all',
});

const schedules = ref([
  {
    id: 'schedule-1',
    day: 'Senin',
    date: '21 Okt 2025',
    rawDate: '2025-10-21',
    start: '09:00',
    end: '10:00',
    subject: 'Bahasa Indonesia',
    topic: 'Menulis Narasi Kreatif',
    description: 'Menulis narasi berdasarkan pengalaman pribadi.',
    class: 'Kelas 7A',
    badge: { label: 'Tersisa 2 hari', bg: 'rgba(96,125,255,0.12)', color: '#4154d1' },
  },
  {
    id: 'schedule-2',
    day: 'Rabu',
    date: '23 Okt 2025',
    rawDate: '2025-10-23',
    start: '10:00',
    end: '11:30',
    subject: 'Matematika',
    topic: 'Bangun Ruang & Aplikasinya',
    description: 'Menghitung volume bangun ruang sederhana.',
    class: 'Kelas 7B',
    badge: { label: 'Butuh materi', bg: 'rgba(244,143,177,0.18)', color: '#b8326a' },
  },
  {
    id: 'schedule-3',
    day: 'Jumat',
    date: '25 Okt 2025',
    rawDate: '2025-10-25',
    start: '08:30',
    end: '09:30',
    subject: 'IPA',
    topic: 'Eksperimen Energi Terbarukan',
    description: 'Praktik sederhana panel surya mini.',
    class: 'Kelas 7A',
    badge: { label: 'Terjadwal', bg: 'rgba(118,179,64,0.18)', color: '#3f6f2a' },
  },
]);

const materials = computed(() => [
  {
    id: 'material-1',
    sessionId: 'schedule-1',
    title: 'Lembar Kerja Narasi',
    description: 'Panduan langkah menulis narasi untuk siswa kelas 7.',
    type: 'Dokumen • PDF',
    updatedAt: 'Diperbarui 2 hari lalu',
  },
  {
    id: 'material-2',
    sessionId: 'schedule-2',
    title: 'Slide Bangun Ruang',
    description: 'Materi visual untuk mempermudah siswa memahami konsep volume.',
    type: 'Presentasi • PPTX',
    updatedAt: 'Diperbarui kemarin',
  },
  {
    id: 'material-3',
    sessionId: 'schedule-3',
    title: 'Video Eksperimen Energi',
    description: 'Video 5 menit eksperimen energi terbarukan di laboratorium.',
    type: 'Video • MP4',
    updatedAt: 'Diperbarui 3 jam lalu',
  },
]);

const pendingSessionsCopy = computed(() => {
  const pending = schedules.value.filter((session) => session.badge.label.toLowerCase().includes('materi')).length;
  if (!pending) {
    return 'Seluruh sesi telah dilengkapi materi.';
  }
  return `${pending} jadwal menunggu materi tambahan.`;
});

const modalState = reactive({ open: false, type: null, scheduleId: null });
const isModalOpen = computed(() => modalState.open);
const defaultDate = computed(() => schedules.value[0]?.rawDate ?? '2025-10-21');

const activeSchedule = computed(() => {
  if (!modalState.scheduleId) {
    return schedules.value[0] ?? null;
  }
  return schedules.value.find((session) => session.id === modalState.scheduleId) ?? schedules.value[0] ?? null;
});

const modalCopy = {
  'create-schedule': {
    title: 'Tambah Jadwal dan Materi',
    subtitle: 'Isi data jadwal kegiatan belajar mengajar dengan lengkap.',
  },
  'edit-schedule': {
    title: 'Edit Jadwal Pembelajaran',
    subtitle: 'Perbarui informasi jadwal yang telah tersimpan.',
  },
  'create-material': {
    title: 'Unggah Materi Pembelajaran',
    subtitle: 'Tambahkan materi pendukung sebelum sesi dimulai.',
  },
  'edit-material': {
    title: 'Perbarui Materi Pembelajaran',
    subtitle: 'Sesuaikan materi agar relevan dengan sesi terbaru.',
  },
};

const modalTitle = computed(() => (modalState.type ? modalCopy[modalState.type]?.title ?? '' : ''));
const modalSubtitle = computed(() => (modalState.type ? modalCopy[modalState.type]?.subtitle ?? '' : ''));

const focusSessionId = computed(() => (typeof route.query.session === 'string' ? route.query.session : null));

const updateRoute = (modal, scheduleId) => {
  const query = { ...route.query };
  if (modal) {
    query.modal = modal;
  } else {
    delete query.modal;
  }

  if (scheduleId) {
    query.session = scheduleId;
  } else if (query.session && !focusSessionId.value) {
    delete query.session;
  }

  router.replace({ name: 'teacher-schedule', query });
};

const openModal = (type, scheduleId = null, sync = true) => {
  modalState.open = true;
  modalState.type = type;
  modalState.scheduleId = scheduleId ?? focusSessionId.value ?? schedules.value[0]?.id ?? null;
  if (sync) {
    updateRoute(type, modalState.scheduleId);
  }
};

const closeModal = (sync = true) => {
  modalState.open = false;
  modalState.type = null;
  modalState.scheduleId = null;
  if (sync) {
    updateRoute(null, focusSessionId.value ?? null);
  }
};

watch(
  () => ({ modal: route.query.modal, session: route.query.session }),
  ({ modal, session }) => {
    if (!modal) {
      closeModal(false);
      return;
    }

    if (!(modal in modalCopy)) {
      closeModal(false);
      return;
    }

    openModal(modal, typeof session === 'string' ? session : null, false);
  },
  { immediate: true },
);

const toasts = ref([]);
const toastTimers = new Map();

const pushToast = ({ title, subtitle }) => {
  const id = `toast-${Date.now()}-${Math.random().toString(36).slice(2, 6)}`;
  toasts.value.push({ id, title, subtitle });
  const timer = (typeof window !== 'undefined' ? window.setTimeout : setTimeout)(() => dismissToast(id), 4500);
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

const handleSubmit = (type) => {
  const toastMap = {
    'create-schedule': {
      title: 'Jadwal Berhasil Ditambahkan',
      subtitle: 'Sesi baru siap dibagikan kepada siswa.',
    },
    'edit-schedule': {
      title: 'Jadwal Diperbarui',
      subtitle: `${activeSchedule.value?.subject ?? 'Jadwal'} telah disesuaikan.`,
    },
    'create-material': {
      title: 'Materi Berhasil Diunggah',
      subtitle: `${activeSchedule.value?.subject ?? 'Materi baru'} siap dibagikan.`,
    },
    'edit-material': {
      title: 'Materi Diperbarui',
      subtitle: `${activeSchedule.value?.subject ?? 'Materi'} sudah disesuaikan.`,
    },
  };

  if (type && toastMap[type]) {
    pushToast(toastMap[type]);
  }

  closeModal();
};

const notifications = [
  {
    id: 'notif-1',
    title: '3 jadwal membutuhkan materi',
    subtitle: 'Segera lengkapi materi untuk kelas Matematika dan IPA.',
    status: 'warning',
  },
  {
    id: 'notif-2',
    title: 'Materi baru terunggah',
    subtitle: 'Slide Bangun Ruang telah dibagikan ke siswa Kelas 7B.',
    status: 'success',
  },
];

const goToAttendance = (session) => {
  router.push({ name: 'teacher-attendance', query: { from: 'schedule', session: session.id } });
};

const downloadMaterial = (material) => {
  pushToast({ title: 'Mengunduh Materi', subtitle: material.title });
};

const handleLogout = async () => {
  closeModal(false);
  toastTimers.forEach((timer) => clearTimeout(timer));
  toastTimers.clear();
  await logout();
  router.replace({ name: 'login', query: { role: 'teacher' } });
};

const handleKeydown = (event) => {
  if (event.key === 'Escape' && isModalOpen.value) {
    closeModal();
  }
};

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
.manager__hero {
  display: grid;
  grid-template-columns: minmax(0, 1fr) minmax(240px, 320px);
  gap: 2.5rem;
  padding: 2.25rem;
  border-radius: 28px;
  background: linear-gradient(135deg, rgba(118, 179, 64, 0.15), rgba(96, 125, 255, 0.12));
  border: 1px solid rgba(118, 179, 64, 0.2);
  box-shadow: 0 22px 70px -38px rgba(26, 43, 70, 0.55);
  backdrop-filter: blur(12px);
}

.manager__hero-copy > p {
  margin: 0;
  color: rgba(31, 31, 31, 0.7);
  line-height: 1.6;
}

.manager__hero-eyebrow {
  margin: 0;
  font-size: 0.9rem;
  font-weight: 600;
  color: rgba(31, 31, 31, 0.65);
  text-transform: uppercase;
  letter-spacing: 0.08em;
}

.manager__hero h1 {
  margin: 0.2rem 0 1rem;
  font-size: 2rem;
  color: #1f2937;
}

.manager__hero-actions {
  margin-top: 1.75rem;
  display: flex;
  flex-wrap: wrap;
  gap: 0.85rem;
}

.manager__hero-actions button {
  padding: 0.8rem 1.4rem;
  border-radius: 16px;
  border: none;
  font-weight: 600;
  cursor: pointer;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.manager__hero-actions button:first-of-type {
  background: rgba(118, 179, 64, 0.18);
  color: #2f6721;
  box-shadow: 0 18px 40px -24px rgba(47, 103, 33, 0.45);
}

.manager__hero-actions button:first-of-type:hover {
  transform: translateY(-2px);
}

.manager__hero-secondary {
  background: rgba(255, 255, 255, 0.85);
  color: rgba(31, 31, 31, 0.75);
  border: 1px solid rgba(118, 179, 64, 0.18);
}

.manager__hero-secondary:hover {
  transform: translateY(-2px);
  box-shadow: 0 16px 36px -24px rgba(31, 41, 55, 0.3);
}

.manager__status {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  padding: 1.75rem;
  border-radius: 22px;
  background: rgba(255, 255, 255, 0.78);
  border: 1px solid rgba(255, 255, 255, 0.4);
  box-shadow: 0 20px 60px -35px rgba(23, 43, 77, 0.5);
}

.manager__status span {
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.08em;
  color: rgba(31, 31, 31, 0.55);
}

.manager__status strong {
  font-size: 1.3rem;
  color: #2f4f2f;
}

.manager__status p {
  margin: 0;
  color: rgba(31, 31, 31, 0.6);
}

.manager__status button {
  margin-top: auto;
  padding: 0.6rem 1.1rem;
  border-radius: 14px;
  border: none;
  background: rgba(118, 179, 64, 0.18);
  color: #2f6721;
  font-weight: 600;
  cursor: pointer;
}

.manager__toolbar {
  display: flex;
  justify-content: space-between;
  gap: 1.5rem;
  align-items: flex-end;
}

.manager__filters {
  display: flex;
  flex-wrap: wrap;
  gap: 1rem;
}

.manager__filters label {
  display: flex;
  flex-direction: column;
  gap: 0.35rem;
  font-size: 0.9rem;
  color: rgba(31, 31, 31, 0.7);
}

.manager__filters select {
  padding: 0.7rem 1rem;
  border-radius: 14px;
  border: 1px solid rgba(118, 179, 64, 0.18);
  background: rgba(255, 255, 255, 0.95);
  font-size: 0.95rem;
}

.manager__toolbar-actions button {
  padding: 0.75rem 1.4rem;
  border-radius: 16px;
  border: none;
  background: rgba(118, 179, 64, 0.18);
  color: #2f6721;
  font-weight: 600;
  cursor: pointer;
}

.manager__grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
  gap: 2rem;
}

.manager__column {
  display: flex;
  flex-direction: column;
  gap: 1.25rem;
  padding: 1.75rem;
  border-radius: 24px;
  background: rgba(255, 255, 255, 0.92);
  border: 1px solid rgba(118, 179, 64, 0.12);
  box-shadow: 0 20px 60px -38px rgba(23, 43, 77, 0.45);
}

.manager__column header h2 {
  margin: 0;
  font-size: 1.35rem;
  color: #1f2937;
}

.manager__column header p {
  margin: 0.3rem 0 0;
  color: rgba(31, 31, 31, 0.6);
}

.manager__sessions,
.manager__materials {
  list-style: none;
  margin: 0;
  padding: 0;
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.manager-session {
  display: grid;
  grid-template-columns: 140px 1fr auto;
  gap: 1.25rem;
  padding: 1.1rem 1.35rem;
  border-radius: 18px;
  border: 1px solid rgba(118, 179, 64, 0.12);
  background: rgba(255, 255, 255, 0.9);
}

.manager-session--focus {
  border-color: rgba(118, 179, 64, 0.45);
  box-shadow: 0 12px 34px -22px rgba(47, 103, 33, 0.35);
}

.manager-session__time span {
  display: block;
  font-size: 0.78rem;
  text-transform: uppercase;
  letter-spacing: 0.08em;
  color: rgba(31, 31, 31, 0.55);
}

.manager-session__time strong {
  font-size: 1.1rem;
  color: #1f2937;
}

.manager-session__details h3 {
  margin: 0;
  font-size: 1.1rem;
  color: #1f2937;
}

.manager-session__details p {
  margin: 0.25rem 0 0;
  color: rgba(31, 31, 31, 0.6);
}

.manager-session__details small {
  display: block;
  margin-top: 0.35rem;
  color: rgba(31, 31, 31, 0.5);
}

.manager-session__actions {
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  gap: 0.6rem;
}

.manager-session__badge {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 0.35rem 0.75rem;
  border-radius: 999px;
  font-size: 0.75rem;
  font-weight: 600;
}

.manager-session__buttons {
  display: flex;
  gap: 0.5rem;
}

.manager-session__buttons button {
  padding: 0.55rem 0.9rem;
  border-radius: 12px;
  border: none;
  background: rgba(118, 179, 64, 0.15);
  color: #2f6721;
  font-weight: 600;
  cursor: pointer;
}

.manager-session__link {
  padding: 0.45rem 0.85rem;
  border-radius: 12px;
  border: 1px solid rgba(96, 125, 255, 0.24);
  background: rgba(96, 125, 255, 0.12);
  color: #4154d1;
  font-weight: 600;
  cursor: pointer;
}

.manager-material {
  display: flex;
  flex-direction: column;
  gap: 0.65rem;
  padding: 1.2rem 1.35rem;
  border-radius: 18px;
  border: 1px solid rgba(118, 179, 64, 0.12);
  background: rgba(255, 255, 255, 0.9);
}

.manager-material__meta {
  display: flex;
  justify-content: space-between;
  font-size: 0.78rem;
  color: rgba(31, 31, 31, 0.55);
}

.manager-material h3 {
  margin: 0;
  font-size: 1.05rem;
  color: #1f2937;
}

.manager-material p {
  margin: 0;
  color: rgba(31, 31, 31, 0.6);
  line-height: 1.5;
}

.manager-material__footer {
  margin-top: auto;
  display: flex;
  gap: 0.6rem;
}

.manager-material__footer button {
  padding: 0.55rem 1rem;
  border-radius: 12px;
  border: none;
  background: rgba(118, 179, 64, 0.16);
  color: #2f6721;
  font-weight: 600;
  cursor: pointer;
}

.manager-material__ghost {
  background: rgba(255, 255, 255, 0.85);
  color: rgba(31, 31, 31, 0.7);
  border: 1px solid rgba(118, 179, 64, 0.18);
}

.manager__toasts {
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

.manager-toast {
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

.manager-toast__icon {
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

.manager-toast__title {
  margin: 0;
  font-weight: 600;
  color: #1f1f1f;
}

.manager-toast__subtitle {
  margin: 0.2rem 0 0;
  font-size: 0.82rem;
  color: rgba(31, 31, 31, 0.6);
}

.manager-toast__close {
  background: none;
  border: none;
  font-size: 1.25rem;
  line-height: 1;
  color: rgba(31, 31, 31, 0.45);
  cursor: pointer;
}

.manager-modal {
  position: fixed;
  inset: 0;
  display: grid;
  place-items: center;
  z-index: 40;
}

.manager-modal__backdrop {
  position: absolute;
  inset: 0;
  background: rgba(12, 18, 14, 0.45);
}

.manager-modal__content {
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

.manager-modal__content header h2 {
  margin: 0;
  font-size: 1.3rem;
  color: #1f2937;
}

.manager-modal__content header p {
  margin: 0.2rem 0 0;
  color: rgba(31, 31, 31, 0.6);
  font-size: 0.9rem;
}

.manager-form {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.manager-form__row {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
  gap: 0.9rem;
}

.manager-form label {
  display: flex;
  flex-direction: column;
  gap: 0.35rem;
  font-size: 0.9rem;
  color: rgba(31, 31, 31, 0.7);
}

.manager-form input,
.manager-form select,
.manager-form textarea {
  padding: 0.75rem 1rem;
  border-radius: 14px;
  border: 1px solid rgba(118, 179, 64, 0.18);
  background: rgba(255, 255, 255, 0.95);
  font-size: 0.95rem;
  font-family: inherit;
  color: rgba(31, 31, 31, 0.85);
}

.manager-form__dropzone {
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

.manager-form__dropzone strong {
  font-weight: 600;
  color: #2f6721;
}

.manager-form__actions {
  display: flex;
  justify-content: flex-end;
  gap: 0.75rem;
  margin-top: 0.5rem;
}

.manager-form__actions button {
  padding: 0.7rem 1.4rem;
  border-radius: 14px;
  border: none;
  font-weight: 600;
  cursor: pointer;
}

.manager-form__ghost {
  background: rgba(255, 255, 255, 0.85);
  color: rgba(31, 31, 31, 0.7);
  border: 1px solid rgba(118, 179, 64, 0.18);
}

.manager-form__actions button:last-child {
  background: rgba(118, 179, 64, 0.18);
  color: #2f6721;
}

.manager-toast-enter-active,
.manager-toast-leave-active {
  transition: all 0.3s ease;
}

.manager-toast-enter-from,
.manager-toast-leave-to {
  opacity: 0;
  transform: translateY(12px);
}

.manager-modal-enter-active,
.manager-modal-leave-active {
  transition: opacity 0.25s ease;
}

.manager-modal-enter-from,
.manager-modal-leave-to {
  opacity: 0;
}

@media (max-width: 1024px) {
  .manager__hero {
    grid-template-columns: 1fr;
  }

  .manager__status {
    order: -1;
  }

  .manager-session {
    grid-template-columns: 1fr;
    align-items: flex-start;
  }

  .manager-session__actions {
    align-items: flex-start;
  }
}

@media (max-width: 640px) {
  .manager__hero {
    padding: 1.8rem;
  }

  .manager__toolbar {
    flex-direction: column;
    align-items: stretch;
  }
}
</style>
