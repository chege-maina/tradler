import { createRouter, createWebHistory } from "vue-router";

import Dashboard from "../components/pages/Dashboard.vue";

const routes = [
    {
        name: "Dashboard",
        path: "/",
        component: Dashboard,
        meta: {
            requiresAuth: false,
        },
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});


export default router;