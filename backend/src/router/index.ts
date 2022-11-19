import {createRouter, createWebHistory} from "vue-router";
import AppLayout from "../components/AppLayoutLayout.vue";
import Login from "../views/Login.vue";
import ForgotPassword from "../views/ForgotPassword.vue";
import ResetPassword from "../views/ResetPassword.vue";

const routes: any = [
    {
        path: '/app',
        name: 'app',
        component: AppLayout,
        children: []
    },
    {
        path: '/login',
        name: 'login',
        component: Login
    },
    {
        path: '/forgot-password',
        name: 'forgotPassword',
        component: ForgotPassword
    },
    {
        path: '/reset-password/:token',
        name: 'resetPassword',
        component: ResetPassword
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

export default router;
