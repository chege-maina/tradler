<template>
    <Nav />
    <div v-if="$store.getters.menu === 'users'"><Users /></div>
    <div v-if="$store.getters.menu === 'customers'"><Customers /></div>
</template>

<script>
import Nav from "../../components/includes/Nav.vue";
import Customers from "../../components/dashboard/Customers.vue";
import Users from "../../components/dashboard/Users.vue";

import { onMounted, ref, onUnmounted } from "vue";
import { useRouter } from "vue-router";
import { useStore } from "vuex";
export default {
    components: { Nav, Users, Customers },
    setup() {
        const router = useRouter();
        const store = useStore();

        onMounted(() => {
            if (store.getters.menu === "customers") {
                store.dispatch("dashMenus", "customers");
            } else {
                store.dispatch("dashMenus", "users");
            }
        });

        return {};
    },
};
</script>

<style></style>
