import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.withCredentials = true;

const tokenElement = typeof document !== 'undefined' ? document.head.querySelector('meta[name="csrf-token"]') : null;

if (tokenElement) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = tokenElement.content;
}
