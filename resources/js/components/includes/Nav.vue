<template>
    <div
        class="bg-white py-3.5 px-6 shadow md:flex justify-between items-center"
    >
        <div class="flex items-center cursor-pointer text-[#002D74]">
            <span class="text-4xl mr-1">
                <img
                    class="h-11 w-11"
                    src="../../components/assets/logo.png"
                    alt=""
                />
            </span>
        </div>
        <span
            class="absolute md:hidden right-6 top-1.5 cursor-pointer text-4xl"
            @click="openMenu()"
        >
            <i :class="[open ? 'bi bi-x' : 'bi bi-filter-left']"></i
        ></span>
        <ul
            class="md:flex md:items-center md:px-0 px-3 md:pb-0 pb-10 md:static absolute bg-white md:w-auto w-full top-14 duration-700 ease-in"
            :class="[open ? 'left-0' : 'left-[-100%]']"
        >
            <li class="md:mx-4 md:my-0 my-6">
                <div
                    @click="setMenu('users'), closeMenu()"
                    class="text-xl hover:text-[#002D74] duration-500 cursor-pointer"
                >
                    <router-link
                        :to="{ name: 'Dashboard' }"
                        class="text-xl hover:text-[#002D74] duration-500"
                        >Users</router-link
                    >
                </div>
            </li>
        </ul>
    </div>
</template>
<script>
import { useRouter } from "vue-router";
import { useStore } from "vuex";
import { ref } from "vue";
export default {
    setup() {
        const router = useRouter();
        const store = useStore();
        let open = ref(false);
        function logout() {
            router.push({ name: "Login" });
            store.dispatch("removeToken");
        }
        function setMenu(menu) {
            store.dispatch("dashMenus", menu);
        }
        function openMenu() {
            open.value = !open.value;
        }
        function closeMenu() {
            open.value = false;
        }
        return {
            logout,
            setMenu,
            open,
            openMenu,
            closeMenu,
        };
    },
};
</script>
