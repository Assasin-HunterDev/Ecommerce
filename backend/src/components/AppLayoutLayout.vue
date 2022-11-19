<template>
    <div class="flex min-h-full bg-gray-200">
        <!--    Sidebar    -->
        <Sidebar/>
        <!--    Sidebar    -->
        <div class="flex-1">
            <!--     Header       -->
            <Navbar/>
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

const {title} = defineProps({
    title: String
});
</script>

<style scoped>

</style>
