<template>
    <div class="flex min-h-full bg-gray-200">
        <!--    Sidebar    -->
        <Sidebar :class="{'-ml-[160px]': !sidebarOpened}"/>
        <!--    Sidebar    -->
        <div class="flex-1">
            <!--     Header       -->
            <Navbar @toggle-sidebar="toggleSidebar"/>
            <!--     Header       -->
            <!--    Content        -->
            <main class="p-6">
                <div class="p-4 rounded bg-white">
                    <router-view></router-view>
                </div>
            </main>
            <!--    Content        -->
        </div>
    </div>
</template>

<script setup lang="ts">
import Sidebar from "./Sidebar.vue";
import Navbar from "./Navbar.vue";
import {ref} from "vue";

const {title} = defineProps({
    title: String
});

const sidebarOpened = ref(true);

function toggleSidebar(): void {
    sidebarOpened.value = !sidebarOpened.value;
}
</script>

<style scoped>

</style>
