<template>
    <div class="container w-full md:w-4/5 xl:w-3/5 mx-auto px-2 my-3">
        <div class="sm:flex items-center justify-between m-9 mb-0">
            <h1 class="font-bold text-[#002D74] text-4xl">Users</h1>
            <button
                class="bg-[#002D74] rounded-full text-white py-2 px-4 hover:scale-105 duration-300 my-3 sm:mt-0 sm:w-auto w-full"
                @click="toggleModal(), setHeading('Add New User')"
            >
                Add New User
            </button>
        </div>
        <p class="mt-3 text-sm text-[#002c74a9] m-9 hidden sm:block">
            List of system users with their customers.
        </p>
        <div
            class="py-4 mt-2 lg:mt-0 rounded-lg overflow-auto hidden md:block shadow bg-gray-100"
        >
            <div class="flex items-center justify-between my-3 px-7">
                <div
                    class="relative flex items-center justify-between text-sm text-[#002c74a9]"
                >
                    <span class="mr-1">Show</span>
                    <select
                        v-model="currentOptions"
                        class="p-1 rounded-xl border bg-gray-50 text-center"
                        @change="paginateOptions"
                    >
                        <option
                            v-for="option in showOptions"
                            :key="option"
                            :value="option"
                        >
                            {{ option }}
                        </option>
                    </select>
                    <span class="ml-1">entries</span>
                </div>
                <div class="relative flex items-center justify-between text-sm">
                    <input
                        class="p-1 px-2 rounded-xl border w-full"
                        type="text"
                        name="search"
                        v-model="SearchItem"
                        id="search"
                        placeholder="Search Here..."
                        @keyup="searchList"
                    />
                </div>
            </div>
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b-2 border-gray-200">
                        <th
                            class="w-20 p-3 text-sm font-semibold tracking-wide text-left"
                        >
                            No.
                        </th>
                        <th
                            class="p-3 text-sm font-semibold tracking-wide text-left"
                        >
                            Email
                        </th>
                        <th
                            class="p-3 text-sm font-semibold tracking-wide text-left"
                        >
                            Name
                        </th>
                        <th
                            class="w-32 p-3 text-sm font-semibold tracking-wide text-center"
                        >
                            User Role
                        </th>
                        <th
                            class="w-36 p-3 text-sm font-semibold tracking-wide text-center"
                        >
                            View Customers
                        </th>
                        <th
                            class="w-20 p-3 text-sm font-semibold tracking-wide text-center"
                        >
                            Edit
                        </th>
                        <th
                            class="w-20 p-3 text-sm font-semibold tracking-wide text-center"
                        >
                            Delete
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr
                        v-for="(option, index) in filteredOptions"
                        :key="option.id"
                        :class="{
                            'bg-gray-100': (1 + index) % 2 === 0,
                            'bg-gray-50': (1 + index) % 2 !== 0,
                        }"
                    >
                        <td class="p-3 text-sm text-gray-700 whitespace-nowrap">
                            {{ 1 + index }}
                        </td>
                        <td class="p-3 text-sm text-gray-700 whitespace-nowrap">
                            {{ option.email }}
                        </td>
                        <td class="p-3 text-sm text-gray-700 whitespace-nowrap">
                            {{ option.name }}
                        </td>
                        <td
                            class="p-3 text-sm text-gray-700 whitespace-nowrap text-center"
                        >
                            {{ option.roles.role }}
                        </td>
                        <td
                            class="p-3 text-xl text-[#002D74] whitespace-nowrap text-center"
                            data-bs-toggle="tooltip"
                            title="View"
                            @click="getCustomers(option.id)"
                        >
                            <i class="bi bi-eye-fill cursor-pointer"></i>
                        </td>
                        <td
                            class="p-3 text-[#002D74] text-xl whitespace-nowrap text-center"
                        >
                            <i
                                class="bi bi-pencil-square cursor-pointer"
                                @click="
                                    toggleModal(),
                                        setHeading('Edit User'),
                                        setEdit(
                                            option.email,
                                            option.name,
                                            option.roles.id,
                                            option.id
                                        )
                                "
                            ></i>
                        </td>

                        <td
                            class="p-3 text-red-700 text-xl whitespace-nowrap text-center"
                        >
                            <i
                                class="bi bi-trash-fill cursor-pointer"
                                @click="
                                    toggleModal(), setHeading('Delete User');
                                    setDelete(option.id, option.name);
                                "
                            ></i>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="flex items-center justify-between my-3 px-7">
                <div
                    class="relative flex items-center justify-between text-sm text-[#002c74a9]"
                >
                    <span class="mr-1">Showing</span>
                    <span class="mr-1">{{ firstPosition }}</span>
                    <span class="mr-1">to</span>
                    <span class="mr-1">{{ secondPosition }}</span>
                    <span class="mr-1">of</span>
                    <span>{{ totalRec }}</span>
                    <span class="ml-1">entries</span>
                </div>
                <div
                    class="relative flex items-center justify-between text-sm text-[#002c74a9]"
                >
                    <span class="mr-1">Total Pages</span>
                    <span class="mr-1">{{ totalPages }}</span>
                </div>
                <div
                    class="relative flex items-center justify-between text-sm text-[#002c74a9]"
                >
                    <button
                        class="hover:bg-[#002D74] rounded-full hover:text-white py-1 px-2 hover:scale-105 duration-300 mx-1"
                        data-bs-toggle="tooltip"
                        title="First"
                        @click="goToPage(1)"
                    >
                        <i class="bi bi-caret-left-fill"></i>
                    </button>
                    <div v-for="page in FilteredPages" :key="page">
                        <button
                            class="hover:bg-[#002D74] rounded-full hover:text-white py-1 px-2 hover:scale-105 duration-300 mx-1"
                            @click="goToPage(page)"
                            :class="{
                                'bg-[#002D74] text-white': page == ActivePage,
                            }"
                        >
                            {{ page }}
                        </button>
                    </div>
                    <button
                        class="hover:bg-[#002D74] rounded-full hover:text-white py-1 px-2 hover:scale-105 duration-300 mx-1"
                        data-bs-toggle="tooltip"
                        title="Last"
                        @click="goToPage(totalPages)"
                    >
                        <i class="bi bi-caret-right-fill"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-1 gap-4 md:hidden">
            <input
                class="p-1 px-2 rounded-xl border w-full"
                type="text"
                name="search"
                v-model="SearchItem"
                placeholder="Search Here..."
                @keyup="searchMobiList"
            />
            <div
                class="bg-white p-4 rounded-lg shadow text-sm"
                v-for="(option, index) in filteredMobiOptions"
                :key="option.id"
            >
                <div>No. {{ 1 + index }}</div>
                <div>{{ option.email }}</div>
                <div>{{ option.name }}</div>
                <div>{{ option.roles.role }}</div>
                <div class="flex items-center space-x-2 justify-end w-full">
                    <div class="text-[#002D74] text-xl px-1 cursor-pointer">
                        <i
                            class="bi bi-pencil-square"
                            @click="
                                toggleModal(),
                                    setHeading('Edit User'),
                                    setEdit(
                                        option.email,
                                        option.name,
                                        option.roles.id,
                                        option.id
                                    )
                            "
                        ></i>
                    </div>

                    <div
                        class="text-[#002D74] text-xl px-1 cursor-pointer"
                        @click="getCustomers(option.id)"
                    >
                        <i class="bi bi-eye-fill"></i>
                    </div>
                    <div class="text-red-500 text-xl px-1 cursor-pointer">
                        <i
                            class="bi bi-trash-fill"
                            @click="
                                toggleModal(), setHeading('Delete User');
                                setDelete(option.id, option.name);
                            "
                        ></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <Modal @close="toggleModal" :modalActive="modalActive" :heading="heading">
        <p
            class="text-red-700 font-bold text-xs px-4 mt-2 text-center"
            v-if="error"
        >
            {{ error }}
        </p>

        <div class="my-8 px-4" v-if="heading === 'Delete User'">
            <div class="grid grid-cols-1 gap-4">
                <p>
                    Are You Sure You Want To Remove
                    <span class="text-red-500 font-semibold"
                        >{{ modalData }}
                    </span>
                    Completely from the System?
                </p>
            </div>
            <div
                class="sm:flex items-right justify-end m-2 border-t p-2 sm:space-x-2"
            >
                <button
                    class="bg-red-500 rounded-full text-white py-2 px-4 hover:scale-105 duration-300 mt-3 sm:mt-0 sm:w-auto w-full"
                    @click="deleteUsr"
                    type="submit"
                >
                    Delete
                </button>
            </div>
        </div>

        <form class="my-8 px-4" @submit.prevent="login" v-else>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <input
                    class="p-2 rounded-xl border my-2"
                    type="email"
                    name="email"
                    id="email"
                    v-model="form.email"
                    placeholder="Email"
                    required
                />
                <input
                    class="p-2 rounded-xl border my-2"
                    type="text"
                    name="name"
                    v-model="form.name"
                    id="name"
                    placeholder="Name"
                    required
                />
                <select
                    v-model="form.role_id"
                    class="p-2 rounded-xl border bg-gray-50 my-2"
                    required
                >
                    <option disabled value="">Please select User Type</option>
                    <option
                        v-for="option in roleOptions"
                        :key="option.id"
                        :value="option.id"
                    >
                        {{ option.role }}
                    </option>
                </select>
            </div>
            <div
                class="sm:flex items-right justify-end m-2 border-t p-2 sm:space-x-2"
            >
                <button
                    class="rounded-full text-[#002D74] py-2 px-4 hover:border hover:border-[#002D74] duration-300 mt-3 sm:mt-0 sm:w-auto w-full"
                    @click="clearForm"
                    :disabled="!buttonsActive"
                >
                    Clear
                </button>
                <button
                    class="bg-[#002D74] rounded-full text-white py-2 px-4 hover:scale-105 duration-300 mt-3 sm:mt-0 sm:w-auto w-full"
                    @click="register"
                    type="submit"
                    :disabled="!buttonsActive"
                >
                    Save
                </button>
            </div>
        </form>
    </Modal>
</template>

<script>
import Modal from "../includes/Modal.vue";
import { ref, onMounted, reactive } from "vue";
import { useStore } from "vuex";
export default {
    components: { Modal },
    setup() {
        const store = useStore();
        const modalActive = ref(false);
        const buttonsActive = ref(true);
        const heading = ref();
        let initialState = {
            id: "",
            name: "",
            email: "",
            role_id: "",
        };
        const form = reactive({ ...initialState });
        let error = ref("");
        let modalData = ref("");
        let userid = ref();
        let userstatus = ref();
        let options = ref([]);
        let showOptions = ref([1, 2, 3, 4, 5, 10, 25, 50, 100]);
        let currentOptions = ref(10);
        let PageRange = ref(5);
        let filteredOptions = ref([]);
        let filteredMobiOptions = ref([]);
        let roleOptions = ref([]);
        let totalRec = ref(0);
        let totalPages = ref(0);
        let Pages = ref([]);
        let ActivePage = ref();
        let SearchItem = ref();
        let FilteredPages = ref([]);
        let firstPosition = ref(0);
        let secondPosition = ref(0);
        onMounted(() => {
            axios
                .get("/api/users")
                .then((res) => {
                    if (res.data.users) {
                        options.value = res.data.users;
                        paginateOptions();
                    }
                })
                .catch((e) => {
                    error.value = e.res.data.message;
                });
            axios
                .get("/api/roles")
                .then((res) => {
                    if (res.data.roles) {
                        roleOptions.value = res.data.roles;
                    }
                })
                .catch((e) => {
                    error.value = e.res.data.message;
                });
        });

        const toggleModal = () => {
            modalActive.value = !modalActive.value;
            buttonsActive.value = true;
            clearForm();
        };
        const clearForm = () => {
            Object.assign(form, initialState);
            error.value = null;
        };
        const setHeading = (headSet) => {
            heading.value = headSet;
        };
        const setEdit = (e_email, e_name, e_role, e_id) => {
            form.email = e_email;
            form.name = e_name;
            form.role_id = e_role;
            form.id = e_id;
        };
        const setDelete = (d_id, d_name) => {
            userid.value = d_id;
            modalData.value = d_name;
        };
        const searchList = () => {
            if (SearchItem.value != "" && SearchItem.value) {
                filteredOptions.value = options.value.filter((item) => {
                    if (
                        item.name.includes(SearchItem.value) ||
                        item.email.includes(SearchItem.value) ||
                        item.roles.role.includes(SearchItem.value)
                    ) {
                        return item;
                    }
                });
                getRange(options.value, filteredOptions.value);
            } else {
                paginateOptions();
            }
        };
        const searchMobiList = () => {
            if (SearchItem.value != "" && SearchItem.value) {
                filteredMobiOptions.value = options.value.filter((item) => {
                    if (
                        item.name.includes(SearchItem.value) ||
                        item.email.includes(SearchItem.value) ||
                        item.roles.role.includes(SearchItem.value)
                    ) {
                        return item;
                    }
                });
                getRange(options.value, filteredMobiOptions.value);
            } else {
                paginateOptions();
            }
        };
        const paginateOptions = () => {
            filteredOptions.value = options.value.slice(
                0,
                currentOptions.value
            );
            filteredMobiOptions.value = options.value;
            totalRec.value = options.value.length;
            totalPages.value = totalRec.value % currentOptions.value;
            totalPages.value =
                totalPages.value == 0
                    ? totalRec.value / currentOptions.value
                    : Math.trunc(totalRec.value / currentOptions.value + 1);
            getRange(options.value, filteredOptions.value);

            Pages.value = [];
            for (var i = 1; i <= totalPages.value; i++) {
                Pages.value.push(i);
            }
            FilteredPages.value = Pages.value.slice(0, PageRange.value);
            ActivePage.value = 1;
        };
        const getRange = (mainData, secData) => {
            var first = secData.slice(0, 1);
            var second = secData.slice(
                currentOptions.value - 1,
                currentOptions.value
            );

            firstPosition.value = mainData.indexOf(first[0]) + 1;
            secondPosition.value = mainData.indexOf(second[0]) + 1;

            if (currentOptions.value > secData.length) {
                secondPosition.value = totalRec.value;
            }
        };
        const goToPage = (page) => {
            if (page <= Math.round(PageRange.value / 2)) {
                FilteredPages.value = Pages.value.slice(0, PageRange.value);
            } else {
                let range = Math.trunc(PageRange.value / 2);
                FilteredPages.value = Pages.value.slice(
                    page - range - 1,
                    page + range
                );
            }

            let lastRange = currentOptions.value * page;
            let firstRange = lastRange - currentOptions.value;

            filteredOptions.value = options.value.slice(firstRange, lastRange);
            getRange(options.value, filteredOptions.value);
            ActivePage.value = page;
        };
        const deleteUsr = async () => {
            await axios
                .delete("/api/users/" + userid.value)
                .then((res) => {
                    if (res.data.success) {
                        location.reload();
                        toggleModal();
                    }
                })
                .catch((e) => {
                    error.value = e.response.data.message;
                });
        };
        const register = async () => {
            buttonsActive.value = false;
            if (heading.value === "Add New User") {
                await axios
                    .post("/api/users", form)
                    .then((res) => {
                        if (res.data.success) {
                            location.reload();
                            toggleModal();
                        }
                    })
                    .catch((e) => {
                        buttonsActive.value = true;
                        error.value = e.response.data.message;
                    });
            } else {
                await axios
                    .put("/api/users/" + form.id, form)
                    .then((res) => {
                        if (res.data.success) {
                            location.reload();
                            toggleModal();
                        }
                    })
                    .catch((e) => {
                        buttonsActive.value = true;
                        error.value = e.response.data.message;
                    });
            }
        };
        const getCustomers = async (id) => {
            store.dispatch("dashMenus", "customers");
            store.dispatch("setVideo", id);
        };
        return {
            SearchItem,
            options,
            roleOptions,
            showOptions,
            currentOptions,
            filteredOptions,
            filteredMobiOptions,
            error,
            modalData,
            userstatus,
            modalActive,
            buttonsActive,
            totalRec,
            totalPages,
            firstPosition,
            secondPosition,
            FilteredPages,
            ActivePage,
            searchList,
            searchMobiList,
            goToPage,
            paginateOptions,
            toggleModal,
            setEdit,
            deleteUsr,
            setDelete,
            setHeading,
            register,
            clearForm,
            getCustomers,
            heading,
            form,
        };
    },
};
</script>
<style scoped></style>
