import PageNotFound from '@/PageNotFound.vue';
import Home from '@/views/user/Home.vue';
import { createRouter, createWebHistory, NavigationGuardNext, RouteLocationNormalized, RouteLocationNormalizedLoaded } from 'vue-router';
import AuthLayout from '@/layouts/AuthLayout.vue';
import UserLayout from '@/layouts/UserLayout.vue';
import Login from '@/views/auth/Login.vue';
import Register from '@/views/auth/Register.vue';
import { authService } from '../services';

const routes = [
    {
        path: '/',
        component: UserLayout,
        children: [
            {path: '', redirect: '/home'},
            {path: 'home', name: 'Home', component: Home, meta: {title: 'Nástěnka', requiresAuth: true}},
        ],
    },
    {
        path: '/auth',
        component: AuthLayout,
        children: [
            {path: '', redirect: 'login'},
            {path: 'login', name: 'Login', component: Login, meta: {title: 'Přihlášení', requiresAuth: false}},
            {path: 'register', name: 'Register', component: Register, meta: {title: 'Registrace', requiresAuth: false}},
        ],
    },
    {
        path: '/:pathMatch(.*)*',
        name: 'NotFound',
        component: PageNotFound,
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach(async (to: RouteLocationNormalized, from: RouteLocationNormalizedLoaded, next: NavigationGuardNext) => {
    const typedTo = to as RouteLocationNormalized & {
        meta: { title?: string; requiresAuth?: boolean };
    };

    document.title = `${typedTo.meta.title} | Rezervace stolu`;

    try {
        const loggedUser = await authService.getLoggedUser();

        if (!typedTo.meta.requiresAuth && loggedUser?.id) {
            return next({name: 'Home'});
        }

        return next();
    } catch (error) {
        if (typedTo.meta.requiresAuth) {
            return next({name: 'Login'});
        }

        return next();
    }
});

export default router;
