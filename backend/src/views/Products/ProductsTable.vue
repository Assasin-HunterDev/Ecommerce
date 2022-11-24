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
                <input v-model="search"
                       class="appearance-none relative block w-48 px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                       placeholder="Type to Search products"
                       @change="getProducts(null)">
            </div>
        </div>
        <Spinner v-if="products.loading"/>
        <template v-else>
            <table class="table-auto w-full">
                <thead>
                <tr>
                    <TableHeaderCell :sort-direction="sortDirection" :sort-field="sortField"
                                     class="border-b2 p-2 text-left"
                                     field="id" @click="sortProducts">ID
                    </TableHeaderCell>
                    <TableHeaderCell :sort-direction="sortDirection" :sort-field="sortField">Image</TableHeaderCell>
                    <TableHeaderCell :sort-direction="sortDirection" :sort-field="sortField"
                                     class="border-b2 p-2 text-left"
                                     field="title" @click="sortProducts">Title
                    </TableHeaderCell>
                    <TableHeaderCell :sort-direction="sortDirection" :sort-field="sortField"
                                     class="border-b2 p-2 text-left"
                                     field="price" @click="sortProducts">Price
                    </TableHeaderCell>
                    <TableHeaderCell :sort-direction="sortDirection" :sort-field="sortField"
                                     class="border-b2 p-2 text-left"
                                     field="updated_at" @click="sortProducts">Last Updated At
                    </TableHeaderCell>
                </tr>
                </thead>
                <tbody>
                <tr v-for="product of products.data">
                    <td class="border-b p-2">{{ product.id }}</td>
                    <td class="border-b p-2">
                        <img :alt="product.title" :src="product.image" class="w-16">
                    </td>
                    <td class="border-b p-2 max-w-[200px] whitespace-nowrap overflow-hidden text-ellipsis">
                        {{ product.title }}
                    </td>
                    <td class="border-b p-2">{{ product.price }}</td>
                    <td class="border-b p-2">{{ product.updated_at }}</td>
                </tr>
                </tbody>
            </table>
            <div v-if="!products.loading" class="flex justify-between items-center mt-5">
                <span>
                    Showing from {{ products.from }} to {{ products.to }}
                </span>
                <nav
                    v-if="products.total > products.limit"
                    aria-label="Pagination"
                    class="relative z-0 inline-flex justify-center rounded-md shadow-sm -space-x-px"
                >
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
        </template>
    </div>
</template>

<script lang="ts" setup>
import {computed, onMounted, ref} from "vue";
import store from "../../store";
import {PRODUCTS_PER_PAGE} from "../../constants";
import Spinner from "../../components/core/Spinner.vue";
import TableHeaderCell from "../../components/core/Table/TableHeaderCell.vue";

const perPage = ref(PRODUCTS_PER_PAGE);
const search = ref("");
const products = computed(() => store.state.products);
const sortField = ref("updated_at");
const sortDirection = ref("desc");

onMounted(() => {
    getProducts();
});

function getProducts(url = null): void {
    store.dispatch("getProducts", {
        url,
        search: search.value,
        perPage: perPage.value,
        sort_field: sortField.value,
        sort_direction: sortDirection.value
    });
}

function getForPage(ev: Event, link: any) {
    ev.preventDefault();
    if (!link.url || link.active) {
        return;
    }
    getProducts(link.url)
}

function sortProducts(field: any) {
    if (field === sortField.value) {
        if (sortDirection.value === 'desc') {
            sortDirection.value = 'asc'
        } else {
            sortDirection.value = 'desc'
        }
    } else {
        sortField.value = field;
        sortDirection.value = 'asc'
    }
    getProducts()
}
</script>

<style scoped>

</style>
