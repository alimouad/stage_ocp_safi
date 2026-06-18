import { createRouter, createWebHistory } from 'vue-router';
import axiosClient from '@/axios.js';
import Login from '@/pages/auth/Login.vue';  
import Register from '@/pages/auth/Register.vue';
import Home from '@/pages/Home.vue';



const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path: '/',
            name: 'home',
            component: Home,
        },
    ]
      
});

router.beforeEach(async (to) => {
    const isAdminRoute = to.path.startsWith('/admin');

    if (!isAdminRoute) {
        return true;
    }

    const hasToken = Boolean(localStorage.getItem('auth_token'));

    if (!hasToken) {
        return { name: 'login' };
    }

    try {
        const response = await axiosClient.get('/user');
        const role = response.data?.role ?? '';

        if (role !== 'admin') {
            return { name: 'home' };
        }

        return true;
    } catch {
        return { name: 'login' };
    }
});

export default router;