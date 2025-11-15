import { createRouter, createWebHistory } from 'vue-router';
import Home from '../components/Home.vue';
import NewsList from '../components/NewsList.vue';
import NewsDetail from '../components/NewsDetail.vue';
import TeacherDashboard from '../components/TeacherDashboard.vue';

const routes = [
  {
    path: '/',
    name: 'home',
    component: Home,
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
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior() {
    return { left: 0, top: 0 };
  },
});

export default router;
