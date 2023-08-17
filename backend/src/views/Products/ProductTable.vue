<template>
    <div class="bg-white p-4 rounded-lg shadow animate-fade-in-down">
        <div class="flex justify-between border-b-2 pb-3">
            <div class="flex items-center">
                <span class="whitespace-nowrap mr-3">Per Page</span>
                <select v-model="perPage"
                        class="appearance-none relative block w-24 px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                        @change="getProducts(null)">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span class="ml-3">Found {{ products.total }} products</span>
            </div>
            <div>
                <input v-model="search" placeholder="Type to Search products"
                       class="appearance-none relative block w-48 px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                       @change="getProducts(null)">
            </div>
        </div>

        <table class="table-auto w-full">
            <thead>
            <tr>
                <TableHeaderCell :sort-direction="sortDirection" :sort-field="sortField" field="id"
                                 @click="sortProducts('id')">
                    ID
                </TableHeaderCell>
                <TableHeaderCell :sort-direction="sortDirection" :sort-field="sortField" field="image">
                    Image
                </TableHeaderCell>
                <TableHeaderCell :sort-direction="sortDirection" :sort-field="sortField" field="title"
                                 @click="sortProducts('title')">
                    Title
                </TableHeaderCell>
                <TableHeaderCell :sort-direction="sortDirection" :sort-field="sortField" field="price"
                                 @click="sortProducts('price')">
                    Price
                </TableHeaderCell>
                <TableHeaderCell :sort-direction="sortDirection" :sort-field="sortField" field="updated_at"
                                 @click="sortProducts('updated_at')">
                    Last Updated At
                </TableHeaderCell>
                <TableHeaderCell field="actions">
                    Actions
                </TableHeaderCell>
            </tr>
            </thead>
            <tbody v-if="products.loading || !products.data.length">
            <tr>
                <td colspan="6">
                    <Spinner v-if="products.loading"/>
                    <p v-else class="text-center py-8 text-gray-700">
                        There are no products
                    </p>
                </td>
            </tr>
            </tbody>
            <tbody v-else>
            <tr v-for="(product) of products.data">
                <td class="border-b p-2 ">{{ product.id }}</td>
                <td class="border-b p-2 ">
                    <img :alt="product.title" :src="product.image_url" class="w-16 h-16 object-cover">
                </td>
                <td class="border-b p-2 max-w-[200px] whitespace-nowrap overflow-hidden text-ellipsis">
                    {{ product.title }}
                </td>
                <td class="border-b p-2">
                    ${{ product.price }}
                </td>
                <td class="border-b p-2 ">
                    {{ product.updated_at }}
                </td>
                <td class="border-b p-2 ">
                    <Menu as="div" class="relative inline-block text-left">
                        <div>
                            <MenuButton
                                class="inline-flex items-center justify-center w-full justify-center rounded-full w-10 h-10 bg-black bg-opacity-0 text-sm font-medium text-white hover:bg-opacity-5 focus:bg-opacity-5 focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75"
                            >
                                <DotsVerticalIcon
                                    aria-hidden="true"
                                    class="h-5 w-5 text-indigo-500"/>
                            </MenuButton>
                        </div>

                        <transition
                            enter-active-class="transition duration-100 ease-out"
                            enter-from-class="transform scale-95 opacity-0"
                            enter-to-class="transform scale-100 opacity-100"
                            leave-active-class="transition duration-75 ease-in"
                            leave-from-class="transform scale-100 opacity-100"
                            leave-to-class="transform scale-95 opacity-0"
                        >
                            <MenuItems
                                class="absolute z-10 right-0 mt-2 w-32 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                            >
                                <div class="px-1 py-1">
                                    <MenuItem v-slot="{ active }">
                                        <button
                                            :class="[
                        active ? 'bg-indigo-600 text-white' : 'text-gray-900',
                        'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                      ]"
                                            @click="editProduct(product)"
                                        >
                                            <PencilIcon
                                                :active="active"
                                                aria-hidden="true"
                                                class="mr-2 h-5 w-5 text-indigo-400"
                                            />
                                            Edit
                                        </button>
                                    </MenuItem>
                                    <MenuItem v-slot="{ active }">
                                        <button
                                            :class="[
                        active ? 'bg-indigo-600 text-white' : 'text-gray-900',
                        'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                      ]"
                                            @click="deleteProduct(product)"
                                        >
                                            <TrashIcon
                                                :active="active"
                                                aria-hidden="true"
                                                class="mr-2 h-5 w-5 text-indigo-400"
                                            />
                                            Delete
                                        </button>
                                    </MenuItem>
                                </div>
                            </MenuItems>
                        </transition>
                    </Menu>
                </td>
            </tr>
            </tbody>
        </table>

        <div v-if="!products.loading" class="flex justify-between items-center mt-5">
            <div v-if="products.data.length">
                Showing from {{ products.from }} to {{ products.to }}
            </div>
            <nav
                v-if="products.total > products.limit"
                aria-label="Pagination"
                class="relative z-0 inline-flex justify-center rounded-md shadow-sm -space-x-px"
            >
                <!-- Current: "z-10 bg-indigo-50 border-indigo-500 text-indigo-600", Default: "bg-white border-gray-300 text-gray-500 hover:bg-gray-50" -->
                <a
                    v-for="(link, i) of products.links"
                    :key="i"
                    :class="[
                        link.active
                            ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600'
                            : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                        i === 0 ? 'rounded-l-md' : '',
                        i === products.links.length - 1 ? 'rounded-r-md' : '',
                        !link.url ? ' bg-gray-100 text-gray-700': ''
                    ]"
                    :disabled="!link.url"
                    aria-current="page"
                    class="relative inline-flex items-center px-4 py-2 border text-sm font-medium whitespace-nowrap"
                    href="#"
                    @click="getForPage($event, link)"
                    v-html="link.label"
                >
                </a>
            </nav>
        </div>
    </div>
</template>

<script lang="ts" setup>
import {computed, onMounted, ref} from "vue";
import store from "../../store";
import {PRODUCTS_PER_PAGE} from "../../constants";
import {Menu, MenuButton, MenuItem, MenuItems} from "@headlessui/vue";
import {DotsVerticalIcon, PencilIcon, TrashIcon} from "@heroicons/vue/outline"
import TableHeaderCell from "../../components/core/Table/TableHeaderCell.vue";
import Spinner from "../../components/core/Spinner.vue";

const perPage = ref(PRODUCTS_PER_PAGE);
const search = ref("");
const products = computed(() => store.state.products);
const sortField = ref("updated_at");
const sortDirection = ref("desc");

const emit = defineEmits(["clickEdit"]);

onMounted(() => {
    getProducts();
})

function getForPage(ev: Event, link: any) {
    ev.preventDefault();
    if (!link.url || link.active) {
        return;
    }
    getProducts(link.url);
}

function getProducts(url = null): void {
    store.dispatch("getProducts", {
        url,
        search: search.value,
        per_page: perPage.value,
        sort_field: sortField.value,
        sort_direction: sortDirection.value
    });
}

function sortProducts(field: any): void {
    if (field === sortField.value) {
        if (sortDirection.value === "desc") {
            sortDirection.value = "asc"
        } else {
            sortDirection.value = "desc"
        }
    } else {
        sortField.value = field;
        sortDirection.value = "asc"
    }
    getProducts();
}

function deleteProduct(product: any) {
    if (!confirm("Are you sure you want to delete the product?")) {
        return;
    }
    store.dispatch("deleteProduct", product.id)
        .then(res => {
            // TODO Show notification
            store.dispatch("getProducts");
        });
}

function editProduct(product: any): void {
    emit("clickEdit", product);
}
</script>

<style scoped>

</style>
