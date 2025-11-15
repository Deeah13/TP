import { computed, ref } from 'vue';

const STORAGE_KEY = 'terminal-pintar-auth';
const SESSION_KEY = `${STORAGE_KEY}:session`;

const demoUsers = [
  {
    id: 1,
    name: 'Ica Bali Tri Susmita',
    shortName: 'Ibu Ica',
    email: 'guru@terminalpintar.id',
    username: 'ica.guru',
    role: 'teacher',
    avatar: 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=200&q=80',
    organization: 'Terminal Pintar',
    password: 'Terminal123!',
  },
  {
    id: 2,
    name: 'Ratih Sasmita',
    shortName: 'Bu Ratih',
    email: 'admin@terminalpintar.id',
    username: 'ratih.admin',
    role: 'admin',
    avatar: 'https://images.unsplash.com/photo-1544723795-3fb6469f5b39?auto=format&fit=crop&w=200&q=80',
    organization: 'Terminal Pintar',
    password: 'Terminal123!',
  },
  {
    id: 3,
    name: 'Arman Pratama',
    shortName: 'Pak Arman',
    email: 'ortu@terminalpintar.id',
    username: 'arman.ortu',
    role: 'parent',
    avatar: 'https://images.unsplash.com/photo-1543966888-7c1dc482a810?auto=format&fit=crop&w=200&q=80',
    organization: 'Terminal Pintar',
    password: 'Terminal123!',
  },
];

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
      return JSON.parse(sessionValue);
    }

    if (persistentValue) {
      const parsed = JSON.parse(persistentValue);
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

export function useAuth() {
  const user = computed(() => userState.value);
  const isAuthenticated = computed(() => Boolean(userState.value));
  const status = computed(() => statusState.value);

  const login = async ({ identifier, password, remember, role }) => {
    statusState.value = 'loading';
    await delay(420);

    const sanitizedIdentifier = identifier.trim().toLowerCase();
    const candidate = demoUsers.find((demoUser) => {
      const matchesIdentifier =
        demoUser.email.toLowerCase() === sanitizedIdentifier ||
        demoUser.username.toLowerCase() === sanitizedIdentifier;

      return matchesIdentifier && demoUser.role === role;
    });

    if (!candidate || candidate.password !== password) {
      statusState.value = 'error';
      throw new Error('Email, username, atau kata sandi yang Anda masukkan belum sesuai.');
    }

    const authenticatedUser = sanitizeUser(candidate);
    userState.value = authenticatedUser;
    persistUser(authenticatedUser, remember);
    statusState.value = 'success';

    return authenticatedUser;
  };

  const logout = () => {
    userState.value = null;
    persistUser(null, false);
    statusState.value = 'idle';
  };

  const hydrate = () => {
    userState.value = loadStoredUser();
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

function sanitizeUser(user) {
  const { password: _password, ...publicProfile } = user;
  return { ...publicProfile };
}

function delay(duration) {
  return new Promise((resolve) => {
    if (typeof window === 'undefined') {
      return resolve();
    }

    window.setTimeout(resolve, duration);
  });
}
