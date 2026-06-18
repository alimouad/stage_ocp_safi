import { createRouter, createWebHistory } from 'vue-router';
import api from '@/services/api';
import Login from '@/views/auth/Login.vue';
import Dashboard from '@/views/dashbord/Dashboard.vue';  




const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path: '/',
            redirect: '/dashboard',
        },
        {
            path: '/login',
            name: 'login',
            component: Login,
        },
        {
            path: '/dashboard',
            name: 'dashboard',
            component: Dashboard,
        }
    ]
      
});

// router.beforeEach(async (to) => {
//     const isAdminRoute = to.path.startsWith('/admin');

//     if (!isAdminRoute) {
//         return true;
//     }

//     const hasToken = Boolean(localStorage.getItem('token'));

//     if (!hasToken) {
//         return { name: 'login' };
//     }

//     try {
//         const response = await api.get('/user');
//         const role = response.data?.role ?? '';

//         if (role !== 'admin') {
//             return { name: 'login' };
//         }

//         return true;
//     } catch {
//         return { name: 'login' };
//     }
// });

export default router;