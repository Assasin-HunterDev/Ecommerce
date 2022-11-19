<template>
    <div class="flex min-h-full">
        <!--    Sidebar    -->
        <Sidebar />
        <!--    Sidebar    -->
        <div>
            <!--     Header       -->
            <header class="h-8 shadow bg-gray-50">
                Header
            </header>
            <!--    Content        -->
            <main>
                <router-view>Content</router-view>
            </main>
        </div>
    </div>
</template>

<script setup lang="ts">
import Sidebar from "./Sidebar.vue";

const {title} = defineProps({
    title: String
});
</script>

<style scoped>

</style>
