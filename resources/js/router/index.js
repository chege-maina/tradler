import { createRouter, createWebHistory } from "vue-router";

import Login from "../components/pages/Users.vue";

const routes = [
    {
        name: "Users",
        path: "/",
        component: Login,
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