import { computed, ref } from 'vue';

const STORAGE_KEY = 'terminal-pintar-auth';
const SESSION_KEY = `${STORAGE_KEY}:session`;

const hashedDemoCredentials = {
  teacher: {
    email: '5589a8bd78b74755ac286cf545763d6b3f1b9a8e585c5a62f50411fe1a467972',
    username: '47ebe3be4eef57414e9d34322c2e169799dc8fc6c8e647954e1905df46f41951',
    password: 'ce9b3046d7c40b86c413ba3451a1dec297c9a883193effe99761f64a6932bfd9',
  },
  admin: {
    email: '5539b7ba840c5f48701946996b9dd6dd43af2ea5d2675ebc88ea016b0ad08713',
    username: '52f0499937c04e647fb52b9ecb6e1d6ed8d96c7eed2154303d0fd346c63b6520',
    password: 'ce9b3046d7c40b86c413ba3451a1dec297c9a883193effe99761f64a6932bfd9',
  },
  parent: {
    email: '70436e13236173807952f5a094b88d15dc2cc7fc7538fad3bd1b468ea13a55c4',
    username: 'c3a8d54064bf3521f92bbb09d856af3ba88d94cead6d3f17d6a33f1afa813d3c',
    password: 'ce9b3046d7c40b86c413ba3451a1dec297c9a883193effe99761f64a6932bfd9',
  },
};

const demoUsers = [
  {
    id: 1,
    name: 'Ica Bali Tri Susmita',
    shortName: 'Ibu Ica',
    role: 'teacher',
    avatar: 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=200&q=80',
    organization: 'Terminal Pintar',
    contact: {
      email: 'guru@terminalpintar.id',
    },
    credentials: hashedDemoCredentials.teacher,
  },
  {
    id: 2,
    name: 'Ratih Sasmita',
    shortName: 'Bu Ratih',
    role: 'admin',
    avatar: 'https://images.unsplash.com/photo-1544723795-3fb6469f5b39?auto=format&fit=crop&w=200&q=80',
    organization: 'Terminal Pintar',
    contact: {
      email: 'admin@terminalpintar.id',
    },
    credentials: hashedDemoCredentials.admin,
  },
  {
    id: 3,
    name: 'Arman Pratama',
    shortName: 'Pak Arman',
    role: 'parent',
    avatar: 'https://images.unsplash.com/photo-1543966888-7c1dc482a810?auto=format&fit=crop&w=200&q=80',
    organization: 'Terminal Pintar',
    contact: {
      email: 'ortu@terminalpintar.id',
    },
    credentials: hashedDemoCredentials.parent,
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

export function useAuth() {
  const user = computed(() => userState.value);
  const isAuthenticated = computed(() => Boolean(userState.value));
  const status = computed(() => statusState.value);

  const login = async ({ identifier, password, remember, role }) => {
    statusState.value = 'loading';
    await delay(420);

    const sanitizedIdentifier = identifier.trim().toLowerCase();
    let hashedIdentifier = '';
    let hashedPassword = '';

    try {
      [hashedIdentifier, hashedPassword] = await Promise.all([
        hashIdentifier(sanitizedIdentifier),
        hashSecret(password),
      ]);
    } catch (error) {
      statusState.value = 'error';
      console.error('Gagal melakukan hashing kredensial demo secara aman', error);
      throw new Error('Terjadi kendala saat memproses kredensial. Silakan coba lagi nanti.');
    }

    const candidate = demoUsers.find((demoUser) => {
      if (demoUser.role !== role) {
        return false;
      }

      const { credentials } = demoUser;
      const matchesIdentifier =
        credentials.email === hashedIdentifier ||
        credentials.username === hashedIdentifier;

      return matchesIdentifier;
    });

    if (!candidate || candidate.credentials.password !== hashedPassword) {
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
  const { credentials: _credentials, ...publicProfile } = user;
  return {
    ...publicProfile,
    contact: {
      ...user.contact,
    },
  };
}

function normalizeStoredUser(rawUser) {
  if (!rawUser || typeof rawUser !== 'object') {
    return null;
  }

  const {
    password: _password,
    credentials: _credentials,
    email,
    username,
    contact,
    ...rest
  } = rawUser;

  const normalizedContact = {
    ...(typeof contact === 'object' && contact ? contact : {}),
  };

  if (email && !normalizedContact.email) {
    normalizedContact.email = email;
  }

  const sanitizedUser = {
    ...rest,
  };

  if (Object.keys(normalizedContact).length > 0) {
    sanitizedUser.contact = normalizedContact;
  }

  return sanitizedUser;
}

function delay(duration) {
  return new Promise((resolve) => {
    if (typeof window === 'undefined') {
      return resolve();
    }

    window.setTimeout(resolve, duration);
  });
}

async function hashIdentifier(identifier) {
  const normalised = typeof identifier === 'string' ? identifier.trim() : '';
  return hashCredential(normalised);
}

async function hashSecret(secret) {
  const normalised = typeof secret === 'string' ? secret : '';
  return hashCredential(normalised);
}

async function hashCredential(value) {
  if (!value) {
    return '';
  }

  const encoder = new TextEncoder();
  const data = encoder.encode(value);

  if (typeof globalThis !== 'undefined' && globalThis.crypto && globalThis.crypto.subtle) {
    const digest = await globalThis.crypto.subtle.digest('SHA-256', data);
    return bufferToHex(digest);
  }

  if (typeof process !== 'undefined' && process.versions && process.versions.node) {
    try {
      const nodeCrypto = await import('node:crypto');
      return nodeCrypto.createHash('sha256').update(value).digest('hex');
    } catch (error) {
      console.warn('Hash fallback gagal dimuat', error);
    }
  }

  throw new Error('Lingkungan saat ini tidak mendukung hashing kredensial secara aman.');
}

function bufferToHex(buffer) {
  return Array.from(new Uint8Array(buffer))
    .map((byte) => byte.toString(16).padStart(2, '0'))
    .join('');
}
