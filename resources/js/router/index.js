import { createRouter, createWebHistory } from 'vue-router';
import Home from '../components/Home.vue';
import Login from '../components/Login.vue';
import NewsList from '../components/NewsList.vue';
import NewsDetail from '../components/NewsDetail.vue';
import TeacherDashboard from '../components/TeacherDashboard.vue';
import { getCurrentUser, isLoggedIn } from '../stores/auth';

const routes = [
  {
    path: '/',
    name: 'home',
    component: Home,
  },
  {
    path: '/login',
    name: 'login',
    component: Login,
    meta: {
      layout: 'auth',
      requiresGuest: true,
    },
  },
  {
    path: '/dokumentasi',
    name: 'news',
    component: NewsList,
  },
  {
    path: '/dokumentasi/:slug',
    name: 'news-detail',
    component: NewsDetail,
  },
  {
    path: '/guru/dashboard',
    name: 'teacher-dashboard',
    component: TeacherDashboard,
    meta: {
      requiresAuth: true,
      roles: ['teacher'],
    },
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior() {
    return { left: 0, top: 0 };
  },
});

router.beforeEach((to, _from, next) => {
  const user = getCurrentUser();

  if (to.meta.requiresAuth && !isLoggedIn()) {
    return next({ name: 'login', query: { redirect: to.fullPath, role: to.meta.roles?.[0] ?? undefined } });
  }

  if (to.meta.roles && user && !to.meta.roles.includes(user.role)) {
    return next({ name: 'login', query: { redirect: to.fullPath, role: to.meta.roles[0] } });
  }

  if (to.meta.requiresGuest && isLoggedIn()) {
    const fallback = user?.role === 'teacher' ? 'teacher-dashboard' : 'home';
    return next({ name: fallback });
  }

  return next();
});

export default router;
