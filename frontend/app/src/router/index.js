import { createRouter, createWebHistory } from 'vue-router';
import Login from '@/views/auth/Login.vue';
import Dashboard from '@/views/dashbord/Dashboard.vue';  
import EmissionPoints from '@/views/emission-points/EmissionPoints.vue';
import MeasurementCreate from '@/views/measurements/MeasurementCreate.vue';
import ImportCsv from '@/views/imports/ImportCsv.vue';
import Alerts from '@/views/alerts/Alerts.vue';


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
            meta: { requiresAuth: true },
        },
        {
            path: '/emission-points',
            name: 'emission-points',
            component: EmissionPoints,
            meta: { requiresAuth: true },
        },
        {
            path: '/measurements',
            name: 'measurement-create',
            component: MeasurementCreate,
            meta: { requiresAuth: true },
        },
        {
            path: '/measurements/import',
            name: 'measurement-import',
            component: ImportCsv,
            meta: { requiresAuth: true },
        },
        {
            path: '/alerts',
            name: 'alerts',
            component: Alerts,
            meta: { requiresAuth: true },
        },
        {
            path: '/:pathMatch(.*)*',
            redirect: '/dashboard',
        },
    ]
      
});

router.beforeEach((to) => {
    const hasToken = Boolean(localStorage.getItem('token'));

    if (to.meta.requiresAuth && !hasToken) {
        return { name: 'login' };
    }

    if (to.name === 'login' && hasToken) {
        return { name: 'dashboard' };
    }

    return true;
});

export default router;
