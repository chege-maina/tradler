<template>
    <div class="container w-full md:w-4/5 xl:w-4/5 mx-auto px-2 my-3">
        <div class="sm:flex items-center justify-between m-9 mb-5">
            <h1 class="font-bold text-[#002D74] text-4xl">
                {{ userName }}'s Customers
            </h1>
        </div>
        <div
            class="py-4 mt-5 lg:mt-0 rounded-lg overflow-auto hidden md:block shadow bg-gray-100"
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
                            Name
                        </th>
                        <th
                            class="p-3 text-sm font-semibold tracking-wide text-left"
                        >
                            Email
                        </th>
                        <th
                            class="w-42 p-3 text-sm font-semibold tracking-wide text-left"
                        >
                            Job Title
                        </th>
                        <th
                            class="w-36 p-3 text-sm font-semibold tracking-wide text-left"
                        >
                            Phone
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
                            {{ option["First Name"] }} {{ option["Last Name"] }}
                        </td>
                        <td class="p-3 text-sm text-gray-700 whitespace-nowrap">
                            {{ option.Email }}
                        </td>
                        <td class="p-3 text-sm text-gray-700 whitespace-nowrap">
                            {{ option["Job Title"] }}
                        </td>
                        <td class="p-3 text-sm text-gray-700 whitespace-nowrap">
                            {{ option.Phone }}
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
        </div>
    </div>
</template>

<script>
import { ref, onMounted, reactive } from "vue";
import { useStore } from "vuex";
export default {
    setup() {
        const store = useStore();
        let options = ref([]);
        let userName = ref("");
        let showOptions = ref([1, 2, 3, 4, 5, 10, 25, 50, 100]);
        let currentOptions = ref(10);
        let PageRange = ref(5);
        let filteredOptions = ref([]);
        let filteredMobiOptions = ref([]);
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
                .get("/api/users/" + store.getters.video)
                .then((res) => {
                    if (res.data.user) {
                        options.value = res.data.user;
                        userName.value = options.value[0].name;
                        options.value = options.value[0].customers;
                        paginateOptions();
                    }
                })
                .catch((e) => {
                    error.value = e.response.data.message;
                });
        });

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
        return {
            SearchItem,
            options,
            showOptions,
            currentOptions,
            filteredOptions,
            filteredMobiOptions,
            totalRec,
            totalPages,
            firstPosition,
            secondPosition,
            FilteredPages,
            ActivePage,
            userName,
            searchList,
            searchMobiList,
            goToPage,
            paginateOptions,
        };
    },
};
</script>
<style scoped></style>
