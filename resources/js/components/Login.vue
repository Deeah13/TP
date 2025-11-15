<template>
  <div class="login" role="main">
    <div class="login__background" aria-hidden="true">
      <span class="login__orb login__orb--primary"></span>
      <span class="login__orb login__orb--secondary"></span>
      <span class="login__orb login__orb--tertiary"></span>
    </div>

    <div class="login__panel" role="region" aria-labelledby="login-heading">
      <header class="login__header">
        <div class="login__logo">
          <span class="login__logo-icon" aria-hidden="true">
            <svg viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><path d="M13.8 3.6C19.8 2 26 2 32 3.6c1.9.5 3.4 2.1 3.7 4l4 24.2c.4 2.7-1.4 5.3-4.1 5.7-8.6 1.4-17.6 1.4-26.2 0-2.7-.4-4.6-3-4.1-5.7l4-24.2c.3-1.9 1.8-3.5 3.7-4Z" fill="#76B340"/><path d="M21.5 9.6a2.5 2.5 0 0 1 5 0v6.2l3.7-3.5a2.5 2.5 0 1 1 3.4 3.7l-8 7.5a2.5 2.5 0 0 1-3.4 0l-8-7.5a2.5 2.5 0 1 1 3.4-3.7l3.9 3.6V9.6Z" fill="#fff"/></g></svg>
          </span>
          <div>
            <p class="login__product">Terminal Pintar</p>
            <p class="login__product-sub">Ruang Kolaborasi Pendidikan</p>
          </div>
        </div>
        <h1 id="login-heading">Masuk ke Akun Anda</h1>
        <p class="login__subtitle">
          Kelola aktivitas pembelajaran, dokumentasi, dan kolaborasi komunitas melalui satu portal terpadu.
        </p>
      </header>

      <form class="login__form" @submit.prevent="handleSubmit" novalidate>
        <fieldset class="login__fieldset">
          <legend class="sr-only">Informasi akun</legend>

          <label class="login__label" for="identifier">Email atau username</label>
          <div class="login__input" :class="{ 'login__input--error': errors.identifier }">
            <input
              id="identifier"
              ref="identifierRef"
              v-model="form.identifier"
              type="text"
              name="identifier"
              placeholder="contoh: nama@contoh.id"
              autocomplete="username"
              :disabled="isSubmitting"
              required
            />
            <span v-if="errors.identifier" class="login__feedback">{{ errors.identifier }}</span>
          </div>

          <label class="login__label" for="password">Kata sandi</label>
          <div class="login__input" :class="{ 'login__input--error': errors.password }">
            <input
              id="password"
              v-model="form.password"
              type="password"
              name="password"
              placeholder="Masukkan kata sandi"
              autocomplete="current-password"
              :disabled="isSubmitting"
              required
            />
            <span v-if="errors.password" class="login__feedback">{{ errors.password }}</span>
          </div>
        </fieldset>

        <input id="role" v-model="form.role" type="hidden" name="role" value="teacher" />

        <div class="login__options">
          <label class="login__checkbox">
            <input v-model="form.remember" type="checkbox" name="remember" :disabled="isSubmitting" />
            <span>Ingat saya di perangkat ini</span>
          </label>
          <a class="login__link" href="#">Lupa kata sandi?</a>
        </div>

        <button class="login__submit" type="submit" :disabled="isSubmitting">
          <span v-if="isSubmitting" class="login__spinner" aria-hidden="true"></span>
          <span>{{ isSubmitting ? 'Memproses...' : 'Masuk' }}</span>
        </button>

        <p v-if="formError" class="login__error" role="alert">
          {{ formError }}
        </p>
      </form>

      <footer class="login__footer">
        <p>
          Belum memiliki kredensial? Hubungi admin Terminal Pintar untuk mendapatkan akses guru, relawan, atau orang tua.
        </p>
        <router-link class="login__back" to="/">
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M15 18l-6-6 6-6" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" /></svg>
          <span>Kembali ke halaman utama</span>
        </router-link>
      </footer>
    </div>

    <aside class="login__showcase" aria-label="Sorotan fitur Terminal Pintar">
      <div class="login__showcase-card">
        <h2>Terminal Pintar</h2>
        <p>Platform kolaboratif untuk mengelola pembelajaran komunitas, laporan perkembangan, serta dokumentasi kegiatan.</p>
        <ul>
          <li>
            <span class="login__bullet"></span>
            <div>
              <p>Monitoring Kelas</p>
              <small>Rencanakan sesi belajar dan lacak progres siswa setiap pekan.</small>
            </div>
          </li>
          <li>
            <span class="login__bullet"></span>
            <div>
              <p>Kolaborasi Relawan</p>
              <small>Koordinasikan materi, jadwal, dan aktivitas komunitas secara real-time.</small>
            </div>
          </li>
          <li>
            <span class="login__bullet"></span>
            <div>
              <p>Laporan Transparan</p>
              <small>Bagikan perkembangan pembelajaran kepada orang tua dan pemangku kepentingan.</small>
            </div>
          </li>
        </ul>
      </div>
    </aside>
  </div>
</template>

<script setup>
import { computed, onMounted, reactive, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useAuth } from '../stores/auth';

const router = useRouter();
const route = useRoute();
const { user, isAuthenticated, status, login } = useAuth();

const form = reactive({
  identifier: '',
  password: '',
  role: 'teacher',
  remember: true,
});

const errors = reactive({
  identifier: '',
  password: '',
});

const formError = ref('');
const isSubmitting = computed(() => status.value === 'loading');
const identifierRef = ref(null);

const extractFirstError = (value) => {
  if (!value) {
    return '';
  }

  if (Array.isArray(value)) {
    const message = value.find((item) => typeof item === 'string' && item.trim() !== '');
    return message ?? '';
  }

  if (typeof value === 'string') {
    return value.trim();
  }

  return '';
};

const applyServerErrors = (fieldErrors) => {
  if (!fieldErrors || typeof fieldErrors !== 'object') {
    return;
  }

  errors.identifier = extractFirstError(fieldErrors.identifier);
  errors.password = extractFirstError(fieldErrors.password);
};

const defaultRedirect = computed(() => {
  if (route.query.redirect) {
    return String(route.query.redirect);
  }

  if (user.value?.role === 'teacher' || form.role === 'teacher') {
    return '/guru/dashboard';
  }

  return '/';
});

const handleSubmit = async () => {
  formError.value = '';
  errors.identifier = '';
  errors.password = '';

  if (!form.identifier.trim()) {
    errors.identifier = 'Masukkan email atau username yang terdaftar.';
  }

  if (!form.password.trim()) {
    errors.password = 'Kata sandi tidak boleh kosong.';
  }

  if (errors.identifier || errors.password) {
    focusFirstError();
    return;
  }

  try {
    await login({
      identifier: form.identifier,
      password: form.password,
      remember: form.remember,
      role: form.role,
    });

    router.replace(defaultRedirect.value);
  } catch (error) {
    applyServerErrors(error?.fieldErrors);
    formError.value = error?.message || 'Terjadi kesalahan saat memproses permintaan Anda.';
    focusFirstError();
  }
};

const focusFirstError = () => {
  if (errors.identifier && identifierRef.value) {
    identifierRef.value.focus();
    return;
  }

  if (typeof document !== 'undefined') {
    const passwordElement = document.getElementById('password');
    if (errors.password && passwordElement) {
      passwordElement.focus();
      return;
    }
  }

  if (formError.value && identifierRef.value) {
    identifierRef.value.focus();
  }
};

onMounted(() => {
  if (isAuthenticated.value) {
    router.replace(defaultRedirect.value);
  }
});
</script>

<style scoped>
.login {
  position: relative;
  min-height: 100vh;
  display: grid;
  grid-template-columns: minmax(0, 520px) minmax(0, 1fr);
  background: radial-gradient(circle at top left, #eef8ea 0%, #f6f4ff 45%, #ffffff 100%);
  color: #1f2a37;
  font-family: 'Poppins', 'Plus Jakarta Sans', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
}

.login__background {
  position: absolute;
  inset: 0;
  overflow: hidden;
  z-index: 0;
}

.login__orb {
  position: absolute;
  border-radius: 50%;
  filter: blur(0);
  opacity: 0.48;
}

.login__orb--primary {
  width: 380px;
  height: 380px;
  background: radial-gradient(circle at 30% 30%, rgba(118, 179, 64, 0.38), rgba(118, 179, 64, 0));
  top: -160px;
  left: -120px;
}

.login__orb--secondary {
  width: 420px;
  height: 420px;
  background: radial-gradient(circle at 70% 40%, rgba(110, 149, 255, 0.28), rgba(110, 149, 255, 0));
  bottom: -200px;
  right: 15%;
}

.login__orb--tertiary {
  width: 260px;
  height: 260px;
  background: radial-gradient(circle at 50% 50%, rgba(255, 214, 102, 0.42), rgba(255, 214, 102, 0));
  bottom: 12%;
  left: 8%;
}

.login__panel {
  position: relative;
  z-index: 1;
  margin: 5vh auto 6vh;
  padding: 3.5rem;
  max-width: 520px;
  background: rgba(255, 255, 255, 0.9);
  border-radius: 32px;
  box-shadow: 0 40px 90px -60px rgba(17, 24, 39, 0.45);
  backdrop-filter: blur(18px);
}

.login__header {
  text-align: left;
}

.login__logo {
  display: flex;
  align-items: center;
  gap: 1rem;
  margin-bottom: 1.5rem;
}

.login__logo-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 64px;
  height: 64px;
  border-radius: 18px;
  background: linear-gradient(135deg, rgba(118, 179, 64, 0.2), rgba(67, 160, 71, 0.4));
  box-shadow: inset 0 0 0 1px rgba(118, 179, 64, 0.32);
}

.login__logo-icon svg {
  width: 34px;
  height: 34px;
}

.login__product {
  font-size: 1.1rem;
  font-weight: 600;
  margin: 0;
  color: #2f4f2f;
}

.login__product-sub {
  margin: 0.15rem 0 0;
  color: rgba(31, 42, 55, 0.65);
  font-size: 0.9rem;
}

.login__header h1 {
  margin: 0;
  font-size: clamp(1.75rem, 1.65rem + 0.5vw, 2.15rem);
  font-weight: 700;
  color: #1f2a37;
}

.login__subtitle {
  margin: 1rem 0 0;
  color: rgba(31, 42, 55, 0.7);
  font-size: 0.95rem;
  line-height: 1.6;
}

.login__form {
  margin-top: 2.5rem;
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.login__fieldset {
  border: 0;
  margin: 0;
  padding: 0;
  display: grid;
  gap: 1rem;
}

.login__label {
  font-size: 0.92rem;
  font-weight: 600;
  color: rgba(31, 42, 55, 0.85);
  display: block;
  margin-bottom: 0.45rem;
}

.login__input {
  position: relative;
}

.login__input input {
  width: 100%;
  border-radius: 16px;
  border: 1.5px solid rgba(118, 179, 64, 0.24);
  padding: 0.95rem 1.1rem;
  font-size: 1rem;
  font-family: inherit;
  transition: border-color 0.2s ease, box-shadow 0.2s ease;
  background-color: rgba(255, 255, 255, 0.8);
}

.login__input input:focus {
  outline: none;
  border-color: rgba(118, 179, 64, 0.9);
  box-shadow: 0 0 0 4px rgba(118, 179, 64, 0.18);
}

.login__input--error input {
  border-color: rgba(239, 68, 68, 0.9);
  box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.15);
}

.login__feedback {
  position: absolute;
  inset-inline-start: 1rem;
  bottom: -1.4rem;
  color: #d1434b;
  font-size: 0.75rem;
}

.login__select {
  display: flex;
  flex-direction: column;
  gap: 0.45rem;
}

.login__select select {
  width: 100%;
  border-radius: 16px;
  border: 1.5px solid rgba(79, 70, 229, 0.2);
  padding: 0.9rem 1.1rem;
  font-size: 0.98rem;
  font-family: inherit;
  background: rgba(255, 255, 255, 0.85);
  transition: border-color 0.2s ease, box-shadow 0.2s ease;
}

.login__select select:focus {
  outline: none;
  border-color: rgba(79, 70, 229, 0.7);
  box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.15);
}

.login__select span {
  font-size: 0.78rem;
  color: rgba(31, 42, 55, 0.55);
}

.login__options {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 0.88rem;
  gap: 1rem;
  flex-wrap: wrap;
}

.login__checkbox {
  display: inline-flex;
  align-items: center;
  gap: 0.55rem;
  color: rgba(31, 42, 55, 0.75);
}

.login__checkbox input {
  width: 1.05rem;
  height: 1.05rem;
  border: 1.5px solid rgba(118, 179, 64, 0.5);
  border-radius: 6px;
  accent-color: #76b340;
}

.login__link {
  color: #4f46e5;
  text-decoration: none;
  font-weight: 600;
}

.login__link:hover,
.login__link:focus {
  text-decoration: underline;
}

.login__submit {
  border: 0;
  border-radius: 16px;
  padding: 0.95rem 1.1rem;
  background: linear-gradient(120deg, #76b340, #4caf50);
  color: white;
  font-weight: 600;
  font-size: 1rem;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 0.75rem;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.login__submit:hover:not(:disabled) {
  transform: translateY(-1px);
  box-shadow: 0 12px 24px -16px rgba(76, 175, 80, 0.8);
}

.login__submit:disabled {
  opacity: 0.72;
  cursor: not-allowed;
}

.login__spinner {
  width: 1rem;
  height: 1rem;
  border-radius: 50%;
  border: 2px solid rgba(255, 255, 255, 0.6);
  border-top-color: rgba(255, 255, 255, 1);
  animation: login-spin 0.8s linear infinite;
}

.login__error {
  margin: -0.5rem 0 0;
  padding: 0.9rem 1rem;
  border-radius: 14px;
  background: rgba(239, 68, 68, 0.12);
  color: #b91c1c;
  font-size: 0.9rem;
  line-height: 1.4;
}

.login__footer {
  margin-top: 2.5rem;
  display: flex;
  flex-direction: column;
  gap: 1rem;
  color: rgba(31, 42, 55, 0.6);
  font-size: 0.85rem;
}

.login__back {
  display: inline-flex;
  align-items: center;
  gap: 0.45rem;
  width: fit-content;
  padding: 0.5rem 0.75rem;
  border-radius: 999px;
  color: #2f4f2f;
  font-weight: 600;
  text-decoration: none;
  background: rgba(118, 179, 64, 0.12);
  transition: background 0.2s ease;
}

.login__back:hover {
  background: rgba(118, 179, 64, 0.22);
}

.login__back svg {
  width: 1rem;
  height: 1rem;
}

.login__showcase {
  position: relative;
  z-index: 1;
  padding: 4rem 6vw 4rem 0;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, rgba(118, 179, 64, 0.12), rgba(52, 211, 153, 0.08));
}

.login__showcase-card {
  max-width: 440px;
  border-radius: 32px;
  padding: 2.5rem;
  background: rgba(255, 255, 255, 0.86);
  box-shadow: 0 24px 60px -40px rgba(15, 23, 42, 0.5);
  backdrop-filter: blur(14px);
  color: #1f2a37;
}

.login__showcase-card h2 {
  margin: 0;
  font-size: 1.85rem;
  color: #2f4f2f;
}

.login__showcase-card p {
  margin: 1rem 0 1.5rem;
  line-height: 1.6;
  color: rgba(31, 42, 55, 0.7);
}

.login__showcase-card ul {
  list-style: none;
  padding: 0;
  margin: 0;
  display: grid;
  gap: 1.1rem;
}

.login__showcase-card li {
  display: grid;
  grid-template-columns: auto 1fr;
  gap: 0.9rem;
  align-items: flex-start;
}

.login__bullet {
  width: 12px;
  height: 12px;
  border-radius: 999px;
  background: linear-gradient(135deg, #76b340, #4caf50);
  margin-top: 0.4rem;
}

.login__showcase-card li p {
  margin: 0;
  font-weight: 600;
}

.login__showcase-card li small {
  color: rgba(31, 42, 55, 0.6);
}

.sr-only {
  position: absolute;
  width: 1px;
  height: 1px;
  padding: 0;
  margin: -1px;
  overflow: hidden;
  clip: rect(0, 0, 0, 0);
  white-space: nowrap;
  border: 0;
}

@keyframes login-spin {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}

@media (max-width: 1080px) {
  .login {
    grid-template-columns: minmax(0, 1fr);
  }

  .login__panel {
    margin: 6vh auto;
  }

  .login__showcase {
    display: none;
  }
}

@media (max-width: 640px) {
  .login {
    padding: 1.5rem 1rem;
  }

  .login__panel {
    padding: 2.5rem 1.75rem;
    margin: 3vh auto;
    border-radius: 26px;
  }

  .login__logo {
    flex-direction: column;
    align-items: flex-start;
  }

  .login__logo-icon {
    width: 56px;
    height: 56px;
  }

  .login__form {
    gap: 1.25rem;
  }

  .login__options {
    flex-direction: column;
    align-items: flex-start;
  }
}
</style>
