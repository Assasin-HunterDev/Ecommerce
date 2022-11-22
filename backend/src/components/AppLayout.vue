<template>
    <div v-if="currentUser.id" class="min-h-full bg-gray-200 flex">
        <!--    Sidebar-->
        <Sidebar :class="{'-ml-[200px]': !sidebarOpened}"/>
        <!--/    Sidebar-->
        <div class="flex-1">
            <Navbar @toggle-sidebar="toggleSidebar"></Navbar>
            <!--      Content-->
            <main class="p-6">
                <router-view></router-view>
            </main>
            <!--      Content-->
        </div>
    </div>
    <div v-else class="min-h-full bg-gray-200 flex items-center justify-center">
        <Spinner/>
    </div>
</template>

<script setup lang="ts">
import {computed, onMounted, onUnmounted, ref} from "vue";
import store from "../store";
import Sidebar from "./Sidebar.vue";
import Navbar from "./Navbar.vue";
import Spinner from "./core/Spinner.vue";

const {title} = defineProps({
    title: String
});

const sidebarOpened = ref(true);
const currentUser = computed(() => store.state.user.data);

function toggleSidebar(): void {
    sidebarOpened.value = !sidebarOpened.value;
}

function handleSidebarOpened(): void {
    sidebarOpened.value = window.outerWidth > 768;
}

onMounted(() => {
    store.dispatch("getUser");
    handleSidebarOpened();
    window.addEventListener("resize", handleSidebarOpened);
});

onUnmounted(() => {
    window.removeEventListener("resize", handleSidebarOpened);
});
</script>

<style scoped>

</style>
