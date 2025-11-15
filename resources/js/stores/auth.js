import { computed, ref } from 'vue';
import axios from 'axios';

const STORAGE_KEY = 'terminal-pintar-auth';
const SESSION_KEY = `${STORAGE_KEY}:session`;

const userState = ref(loadStoredUser());
const statusState = ref('idle');

function loadStoredUser() {
  if (typeof window === 'undefined') {
    return null;
  }

  const sessionValue = window.sessionStorage.getItem(SESSION_KEY);
  const persistentValue = window.localStorage.getItem(STORAGE_KEY);

  try {
    if (sessionValue) {
      return normalizeStoredUser(JSON.parse(sessionValue));
    }

    if (persistentValue) {
      const parsed = normalizeStoredUser(JSON.parse(persistentValue));
      window.sessionStorage.setItem(SESSION_KEY, JSON.stringify(parsed));
      return parsed;
    }
  } catch (error) {
    console.warn('Gagal memuat data sesi pengguna Terminal Pintar', error);
  }

  return null;
}

function persistUser(user, remember) {
  if (typeof window === 'undefined') {
    return;
  }

  if (user) {
    const serialised = JSON.stringify(user);
    window.sessionStorage.setItem(SESSION_KEY, serialised);

    if (remember) {
      window.localStorage.setItem(STORAGE_KEY, serialised);
    } else {
      window.localStorage.removeItem(STORAGE_KEY);
    }
  } else {
    window.sessionStorage.removeItem(SESSION_KEY);
    window.localStorage.removeItem(STORAGE_KEY);
  }
}

function normalizeStoredUser(rawUser) {
  if (!rawUser || typeof rawUser !== 'object') {
    return null;
  }

  const { contact } = rawUser;

  return {
    id: rawUser.id ?? null,
    name: rawUser.name ?? 'Pengguna Terminal Pintar',
    shortName: rawUser.shortName ?? rawUser.name ?? 'Pengguna Terminal Pintar',
    role: rawUser.role ?? 'teacher',
    avatar: rawUser.avatar ?? null,
    organization: rawUser.organization ?? 'Terminal Pintar',
    contact: {
      email: contact?.email ?? null,
    },
  };
}

function sanitizeUser(user) {
  return normalizeStoredUser(user);
}

async function fetchProfile() {
  try {
    const response = await axios.get('/api/auth/profile');
    if (response?.data?.user) {
      return sanitizeUser(response.data.user);
    }
    return null;
  } catch (error) {
    if (error?.response?.status === 401) {
      return null;
    }

    console.warn('Gagal mengambil profil pengguna terkini', error);
    return undefined;
  }
}

function extractErrorMessage(error) {
  if (error?.response?.data) {
    const data = error.response.data;

    if (typeof data.message === 'string' && data.message.trim() !== '') {
      return data.message;
    }

    if (data.errors) {
      const firstError = Object.values(data.errors).find((value) => Array.isArray(value) && value.length > 0);
      if (firstError) {
        return String(firstError[0]);
      }
    }
  }

  if (error instanceof Error && error.message) {
    return error.message;
  }

  return 'Terjadi kendala saat memproses permintaan Anda. Silakan coba kembali.';
}

export function useAuth() {
  const user = computed(() => userState.value);
  const isAuthenticated = computed(() => Boolean(userState.value));
  const status = computed(() => statusState.value);

  const login = async ({ identifier, password, remember, role }) => {
    statusState.value = 'loading';

    try {
      const response = await axios.post('/api/auth/login', {
        identifier: identifier.trim(),
        password,
        role,
        remember,
      });

      const authenticatedUser = sanitizeUser(response.data?.user);

      if (!authenticatedUser) {
        throw new Error('Tidak dapat memuat profil pengguna. Silakan coba kembali.');
      }

      userState.value = authenticatedUser;
      persistUser(authenticatedUser, remember);
      statusState.value = 'success';

      return authenticatedUser;
    } catch (error) {
      statusState.value = 'error';
      throw new Error(extractErrorMessage(error));
    }
  };

  const logout = async () => {
    try {
      await axios.post('/api/auth/logout');
    } catch (error) {
      console.warn('Gagal mengakhiri sesi pengguna secara server-side', error);
    } finally {
      userState.value = null;
      persistUser(null, false);
      statusState.value = 'idle';
    }
  };

  const hydrate = async () => {
    const stored = loadStoredUser();

    if (stored) {
      userState.value = stored;
    }

    const profile = await fetchProfile();

    if (profile) {
      userState.value = profile;
      persistUser(profile, true);
      return;
    }

    if (profile === null) {
      userState.value = null;
      persistUser(null, false);
      return;
    }

    if (!stored) {
      userState.value = null;
      persistUser(null, false);
    }
  };

  return {
    user,
    status,
    isAuthenticated,
    login,
    logout,
    hydrate,
  };
}

export function getCurrentUser() {
  return userState.value;
}

export function isLoggedIn() {
  return Boolean(userState.value);
}
