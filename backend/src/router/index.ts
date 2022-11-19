import {createRouter, createWebHistory} from "vue-router";
import Login from "../views/Login.vue";

const routes: any = [
    {
        path: '/login',
        name: 'login',
        component: Login
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

export default router;
