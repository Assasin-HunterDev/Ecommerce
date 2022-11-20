import {createRouter, createWebHistory} from "vue-router";
import store from "../store";
import AppLayout from "../components/AppLayoutLayout.vue";
import Dashboard from "../views/Dashboard.vue";
import Login from "../views/Login.vue";
import ForgotPassword from "../views/ForgotPassword.vue";
import ResetPassword from "../views/ResetPassword.vue";
import NotFound from "../views/NotFound.vue";

const routes: any = [
    {
        path: "/app",
        name: "app",
        component: AppLayout,
        children: [
            {
                path: 'dashboard',
                name: 'app.dashboard',
                component: Dashboard
            },
        ],
        meta: {
            requiresAuth: true
        }
    },
    {
        path: "/login",
        name: "login",
        component: Login,
        meta: {
            requiresGuest: true
        }
    },
    {
        path: "/forgot-password",
        name: "forgotPassword",
        component: ForgotPassword,
        meta: {
            requiresGuest: true
        }
    },
    {
        path: "/reset-password/:token",
        name: "resetPassword",
        component: ResetPassword,
        meta: {
            requiresGuest: true
        }
    },
    {
        path: "/:pathMatch(.*)",
        name: "notfound",
        component: NotFound
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

router.beforeEach((to, from, next) => {
    to.meta.requiresAuth && !store.state.user.token
        ? (next({name: "login"}))
        : to.meta.requiresGuest && store.state.user.token
            ? (next({name: "app.dashboard"}))
            : (next());
});

export default router;
