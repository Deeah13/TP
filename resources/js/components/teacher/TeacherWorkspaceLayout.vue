<template>
  <div class="teacher-shell" :class="{ 'teacher-shell--scrolled': isScrolled }">
    <header ref="headerRef" class="teacher-shell__header" :class="{ 'teacher-shell__header--glass': isScrolled }" role="banner">
      <div class="teacher-shell__brand">
        <img class="teacher-shell__logo" :src="assets.logo" alt="Logo Terminal Pintar" />
        <div class="teacher-shell__brand-text">
          <span class="teacher-shell__brand-name">{{ organizationName }}</span>
          <span class="teacher-shell__brand-sub">{{ roleLabel }}</span>
        </div>
      </div>
      <nav class="teacher-shell__top-nav" aria-label="Pengaturan bahasa">
        <button type="button" class="teacher-shell__language" aria-label="Ubah bahasa">
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3a9 9 0 109 9 9.01 9.01 0 00-9-9zm6.93 8h-2.7a13.19 13.19 0 0 0-.9-4.45 7.01 7.01 0 012.6 4.45zM12 5a11.48 11.48 0 011.13 6h-2.26A11.48 11.48 0 0112 5zM5.07 11a7.01 7.01 0 012.6-4.45A13.19 13.19 0 006.77 11zm0 2h2.7a13.19 13.19 0 00.9 4.45A7.01 7.01 0 015.07 13zm6.93 6a11.48 11.48 0 01-1.13-6h2.26A11.48 11.48 0 0112 19zm3.3-1.55a13.19 13.19 0 00.9-4.45h2.7a7.01 7.01 0 01-3.6 4.45z" /></svg>
          <span>Bahasa Indonesia</span>
        </button>
      </nav>
      <div class="teacher-shell__actions">
        <button
          v-if="notifications.length"
          ref="notificationTrigger"
          type="button"
          class="teacher-shell__icon-button"
          aria-haspopup="true"
          :aria-expanded="showNotifications"
          aria-controls="notification-panel"
          @click.stop="toggleNotifications"
        >
          <img :src="assets.bell" alt="Notifikasi" />
          <span class="teacher-shell__indicator" aria-hidden="true"></span>
        </button>
        <button
          ref="profileTrigger"
          type="button"
          class="teacher-shell__profile"
          aria-haspopup="true"
          :aria-expanded="showProfile"
          aria-controls="profile-panel"
          @click.stop="toggleProfile"
        >
          <img :src="profileAvatar" :alt="`Profil ${profileName}`" />
        </button>
      </div>

      <transition name="teacher-shell-fade">
        <section
          v-if="showNotifications"
          id="notification-panel"
          ref="notificationPanel"
          class="teacher-shell__popover teacher-shell__popover--notifications"
          role="status"
        >
          <header>
            <h2>{{ notificationHeading }}</h2>
            <p>{{ notificationSubheading }}</p>
          </header>
          <ul>
            <li v-for="notification in notifications" :key="notification.id">
              <span class="teacher-shell__bullet" :class="`teacher-shell__bullet--${notification.status ?? 'info'}`"></span>
              <div>
                <p class="teacher-shell__popover-title">{{ notification.title }}</p>
                <p class="teacher-shell__popover-sub">{{ notification.subtitle }}</p>
              </div>
            </li>
          </ul>
          <slot name="notification-action">
            <button type="button" class="teacher-shell__popover-action">Tandai semua telah dibaca</button>
          </slot>
        </section>
      </transition>

      <transition name="teacher-shell-fade">
        <section
          v-if="showProfile"
          id="profile-panel"
          ref="profilePanel"
          class="teacher-shell__popover teacher-shell__popover--profile"
          role="dialog"
          aria-label="Informasi profil guru"
        >
          <img :src="profileAvatar" :alt="`Avatar ${profileName}`" />
          <p class="teacher-shell__profile-name">{{ profileName }}</p>
          <p class="teacher-shell__profile-email">{{ profileEmail }}</p>
          <button type="button" class="teacher-shell__logout" @click="requestLogout">Keluar</button>
        </section>
      </transition>
    </header>

    <div class="teacher-shell__layout">
      <aside class="teacher-shell__sidebar" aria-label="Navigasi guru">
        <div v-for="section in navigation" :key="section.label" class="teacher-shell__sidebar-section">
          <p class="teacher-shell__sidebar-label">{{ section.label }}</p>
          <ul>
            <li
              v-for="item in section.items"
              :key="item.label"
              class="teacher-shell__sidebar-item"
              :class="{ 'teacher-shell__sidebar-item--active': item.active }"
            >
              <button type="button" class="teacher-shell__sidebar-link" @click="handleSidebarClick(item)">
                <span class="teacher-shell__sidebar-icon" :class="item.variant" :style="item.accent ? { background: item.accent } : undefined">
                  <component :is="item.icon" />
                </span>
                <div class="teacher-shell__sidebar-text">
                  <span>{{ item.label }}</span>
                  <small v-if="item.subtitle">{{ item.subtitle }}</small>
                </div>
              </button>
            </li>
          </ul>
        </div>
      </aside>

      <main class="teacher-shell__main" role="main">
        <slot />
      </main>
    </div>
  </div>
</template>

<script setup>
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useAuth } from '../../stores/auth';
import { createIcon } from '../../utils/iconFactory';

const props = defineProps({
  notifications: {
    type: Array,
    default: () => [],
  },
  notificationHeading: {
    type: String,
    default: 'Notifikasi',
  },
  notificationSubheading: {
    type: String,
    default: 'Aktivitas terbaru',
  },
});

const emit = defineEmits(['logout']);

const router = useRouter();
const route = useRoute();
const { user } = useAuth();

const assets = {
  logo: 'https://images.unsplash.com/photo-1618005198919-d3d4b5a92eee?auto=format&fit=crop&w=120&q=80',
  bell: 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgc3Ryb2tlPSIjNkE3QjM0IiBzdHJva2Utd2lkdGg9IjEuNSIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIj48cGF0aCBkPSJNMTIgMjBhMiAyIDAgMCAwIDQtMiI+PC9wYXRoPjxwYXRoIGQ9Ik0zLjg2IDE3SDIwLjEzYTEgMSAwIDAwLjg3LTEuNTRBMTEuMDMgMTEuMDMgMCAwMDIwIDExYzAtNS41My00LjQ3LTEwLTEwLTEwUzkgNS40NyA5IDExYTExLjAzIDExLjAzIDAgMDAtNS4wMSA0LjQ2IDEgMSAwIDAwLjg3IDEuNTR6Ij48L3BhdGg+PC9zdmc+',
  avatarFallback: 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=200&q=80',
};

const icons = {
  home: createIcon({ d: 'M4 10.5L12 4l8 6.5V20a1 1 0 01-1 1h-5v-6h-4v6H5a1 1 0 01-1-1z', fill: 'currentColor' }),
  schedule: createIcon({ d: 'M7 2v2H5a2 2 0 00-2 2v13a2 2 0 002 2h14a2 2 0 002-2V6a2 2 0 00-2-2h-2V2h-2v2H9V2zM6 9h12v8H6zm3 3v2h2v-2z', fill: 'currentColor' }),
  attendance: createIcon({ d: 'M5 4a2 2 0 00-2 2v14l4-2.5L11 20l4-2.5L19 20V6a2 2 0 00-2-2zm2 4h10v2H7zm0 4h6v2H7z', fill: 'currentColor' }),
};

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
const profileEmail = computed(() => user.value?.contact?.email ?? 'Belum tersedia');
const profileAvatar = computed(() => user.value?.avatar ?? assets.avatarFallback);

const navigation = computed(() => {
  const currentName = route.name;
  return [
    {
      label: 'Menu Utama',
      items: [
        {
          label: 'Beranda',
          icon: icons.home,
          to: { name: 'teacher-dashboard' },
          active: currentName === 'teacher-dashboard',
          variant: 'teacher-shell__sidebar-icon--solid',
          accent: 'linear-gradient(135deg, rgba(118,179,64,0.45), rgba(99,144,57,0.35))',
        },
      ],
    },
    {
      label: 'Manajemen Akademik',
      items: [
        {
          label: 'Jadwal & Materi',
          subtitle: 'Unggah dan atur materi pembelajaran',
          icon: icons.schedule,
          to: { name: 'teacher-schedule' },
          active: currentName === 'teacher-schedule',
          variant: 'teacher-shell__sidebar-icon--outline',
        },
      ],
    },
    {
      label: 'Kehadiran',
      items: [
        {
          label: 'Kehadiran Siswa',
          subtitle: 'Pantau absensi dan catatan belajar',
          icon: icons.attendance,
          to: { name: 'teacher-attendance' },
          active: currentName === 'teacher-attendance',
          variant: 'teacher-shell__sidebar-icon--outline',
        },
      ],
    },
  ];
});

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

const handleSidebarClick = (item) => {
  if (!item?.to) return;
  if (item.active) return;
  router.push(item.to);
};

const requestLogout = () => {
  closeOverlays();
  emit('logout');
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
    handleScroll();
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
.teacher-shell {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  background: linear-gradient(160deg, #f6f9ff 0%, #fef7ff 55%, #f3fbf0 100%);
  color: #1f1f1f;
  transition: background 0.4s ease;
  overflow-x: hidden;
}

.teacher-shell__header {
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

.teacher-shell__header--glass {
  background: rgba(255, 255, 255, 0.78);
  backdrop-filter: blur(16px) saturate(135%);
  box-shadow: 0 10px 40px -20px rgba(31, 41, 55, 0.28);
}

.teacher-shell__brand {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.teacher-shell__logo {
  width: 42px;
  height: 42px;
  border-radius: 14px;
  object-fit: cover;
  box-shadow: 0 8px 20px rgba(118, 179, 64, 0.25);
}

.teacher-shell__brand-text {
  display: flex;
  flex-direction: column;
}

.teacher-shell__brand-name {
  font-size: 1.15rem;
  font-weight: 600;
  color: #4a7c2c;
}

.teacher-shell__brand-sub {
  font-size: 0.78rem;
  color: rgba(31, 31, 31, 0.6);
}

.teacher-shell__top-nav {
  display: flex;
  justify-content: flex-end;
}

.teacher-shell__language {
  display: inline-flex;
  align-items: center;
  gap: 0.65rem;
  padding: 0.6rem 1.1rem;
  background: rgba(255, 255, 255, 0.92);
  border: 1px solid rgba(118, 179, 64, 0.24);
  border-radius: 16px;
  font-weight: 500;
  color: rgba(31, 31, 31, 0.75);
  cursor: pointer;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.teacher-shell__language:hover {
  transform: translateY(-2px);
  box-shadow: 0 18px 36px -24px rgba(79, 114, 68, 0.45);
}

.teacher-shell__language svg {
  width: 18px;
  height: 18px;
  color: #4a7c2c;
}

.teacher-shell__actions {
  display: flex;
  align-items: center;
  gap: 0.85rem;
}

.teacher-shell__icon-button,
.teacher-shell__profile {
  position: relative;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 42px;
  height: 42px;
  border-radius: 16px;
  border: 1px solid rgba(118, 179, 64, 0.2);
  background: rgba(255, 255, 255, 0.95);
  cursor: pointer;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.teacher-shell__icon-button:hover,
.teacher-shell__profile:hover {
  transform: translateY(-2px);
  box-shadow: 0 18px 40px -18px rgba(79, 114, 68, 0.45);
}

.teacher-shell__icon-button img {
  width: 20px;
  height: 20px;
}

.teacher-shell__indicator {
  position: absolute;
  top: 8px;
  right: 8px;
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background: #ef4444;
  box-shadow: 0 0 0 2px #fff;
}

.teacher-shell__profile img {
  width: 100%;
  height: 100%;
  border-radius: 15px;
  object-fit: cover;
}

.teacher-shell__popover {
  position: absolute;
  top: calc(100% + 0.75rem);
  right: 3vw;
  min-width: 260px;
  padding: 1.25rem;
  border-radius: 18px;
  background: rgba(255, 255, 255, 0.94);
  box-shadow: 0 20px 45px -25px rgba(15, 23, 42, 0.38);
  border: 1px solid rgba(118, 179, 64, 0.12);
  backdrop-filter: blur(20px);
}

.teacher-shell__popover header h2 {
  margin: 0;
  font-size: 1rem;
  font-weight: 600;
}

.teacher-shell__popover header p {
  margin: 0.25rem 0 1rem;
  font-size: 0.75rem;
  color: rgba(31, 31, 31, 0.6);
}

.teacher-shell__popover ul {
  margin: 0 0 1.25rem;
  padding: 0;
  list-style: none;
  display: flex;
  flex-direction: column;
  gap: 0.85rem;
}

.teacher-shell__popover ul li {
  display: flex;
  gap: 0.75rem;
}

.teacher-shell__bullet {
  width: 10px;
  height: 10px;
  border-radius: 50%;
  margin-top: 0.25rem;
}

.teacher-shell__bullet--success {
  background: #22c55e;
}

.teacher-shell__bullet--info {
  background: #3b82f6;
}

.teacher-shell__bullet--warning {
  background: #f59e0b;
}

.teacher-shell__popover-title {
  margin: 0;
  font-weight: 600;
  color: rgba(31, 31, 31, 0.85);
}

.teacher-shell__popover-sub {
  margin: 0.2rem 0 0;
  font-size: 0.78rem;
  color: rgba(31, 31, 31, 0.55);
}

.teacher-shell__popover-action {
  width: 100%;
  padding: 0.65rem 1rem;
  border-radius: 12px;
  border: none;
  background: rgba(118, 179, 64, 0.16);
  color: #3f6f2a;
  font-weight: 600;
  cursor: pointer;
}

.teacher-shell__popover--profile {
  text-align: center;
}

.teacher-shell__popover--profile img {
  width: 64px;
  height: 64px;
  border-radius: 18px;
  object-fit: cover;
  margin-bottom: 0.75rem;
}

.teacher-shell__profile-name {
  margin: 0;
  font-weight: 600;
}

.teacher-shell__profile-email {
  margin: 0.2rem 0 1rem;
  font-size: 0.78rem;
  color: rgba(31, 31, 31, 0.6);
}

.teacher-shell__logout {
  width: 100%;
  padding: 0.65rem 1rem;
  border-radius: 12px;
  border: none;
  background: rgba(239, 68, 68, 0.12);
  color: #b91c1c;
  font-weight: 600;
  cursor: pointer;
}

.teacher-shell__layout {
  display: grid;
  grid-template-columns: 260px 1fr;
  gap: 2.5rem;
  padding: 2rem 3vw 3rem;
}

.teacher-shell__sidebar {
  position: sticky;
  top: 96px;
  align-self: start;
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.teacher-shell__sidebar-section {
  background: rgba(255, 255, 255, 0.86);
  border-radius: 20px;
  padding: 1.2rem 1.4rem;
  border: 1px solid rgba(118, 179, 64, 0.12);
  box-shadow: 0 18px 48px -30px rgba(57, 70, 62, 0.4);
  backdrop-filter: blur(16px);
}

.teacher-shell__sidebar-label {
  font-size: 0.78rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.08em;
  color: rgba(31, 31, 31, 0.5);
  margin-bottom: 1rem;
}

.teacher-shell__sidebar-section ul {
  list-style: none;
  margin: 0;
  padding: 0;
  display: flex;
  flex-direction: column;
  gap: 0.85rem;
}

.teacher-shell__sidebar-item {
  margin: 0;
}

.teacher-shell__sidebar-link {
  width: 100%;
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.7rem 0.85rem;
  border-radius: 16px;
  border: 1px solid rgba(118, 179, 64, 0.12);
  background: rgba(255, 255, 255, 0.7);
  font-weight: 500;
  color: rgba(31, 31, 31, 0.75);
  cursor: pointer;
  text-align: left;
  transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
}

.teacher-shell__sidebar-link:hover {
  transform: translateY(-2px);
  box-shadow: 0 16px 40px -24px rgba(31, 41, 55, 0.35);
  border-color: rgba(118, 179, 64, 0.32);
}

.teacher-shell__sidebar-item--active .teacher-shell__sidebar-link {
  border-color: rgba(118, 179, 64, 0.45);
  background: rgba(118, 179, 64, 0.14);
  color: #35611e;
}

.teacher-shell__sidebar-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  border-radius: 14px;
  color: #4a7c2c;
  background: rgba(118, 179, 64, 0.2);
}

.teacher-shell__sidebar-icon--outline {
  background: rgba(255, 255, 255, 0.85);
  border: 1px solid rgba(118, 179, 64, 0.18);
}

.teacher-shell__sidebar-text {
  display: flex;
  flex-direction: column;
  gap: 0.2rem;
}

.teacher-shell__sidebar-text small {
  font-size: 0.7rem;
  color: rgba(31, 31, 31, 0.55);
}

.teacher-shell__main {
  display: flex;
  flex-direction: column;
  gap: 2.5rem;
}

.teacher-shell-fade-enter-active,
.teacher-shell-fade-leave-active {
  transition: opacity 0.18s ease, transform 0.18s ease;
}

.teacher-shell-fade-enter-from,
.teacher-shell-fade-leave-to {
  opacity: 0;
  transform: translateY(-6px);
}

@media (max-width: 1120px) {
  .teacher-shell__layout {
    grid-template-columns: 1fr;
  }

  .teacher-shell__sidebar {
    position: static;
    flex-direction: row;
    overflow-x: auto;
    gap: 1rem;
  }

  .teacher-shell__sidebar-section {
    min-width: 250px;
  }
}

@media (max-width: 768px) {
  .teacher-shell__header {
    grid-template-columns: 1fr;
    justify-items: center;
    text-align: center;
  }

  .teacher-shell__top-nav,
  .teacher-shell__actions {
    justify-content: center;
  }

  .teacher-shell__layout {
    padding: 1.75rem 6vw 2.5rem;
  }

  .teacher-shell__main {
    gap: 2rem;
  }
}
</style>
