import { createRouter, createWebHistory } from 'vue-router';
import Home from '../components/Home.vue';
import NewsList from '../components/NewsList.vue';
import NewsDetail from '../components/NewsDetail.vue';

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
];

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior() {
    return { left: 0, top: 0 };
  },
});

export default router;
