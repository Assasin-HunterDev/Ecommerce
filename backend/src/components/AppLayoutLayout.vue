<template>
    <div class="flex min-h-full">
        <!--    Sidebar    -->
        <div class="w-[200px] bg-indigo-600">
            Sidebar
        </div>
        <div>
            <!--     Header       -->
            <header class="h-8 shadow bg-gray-50">
                Header
            </header>
            <!--    Content        -->
            <main>
                <router-view>
                    Content
                </router-view>
            </main>
        </div>
    </div>
</template>

<script setup lang="ts">
const {title} = defineProps({
    title: String
});
</script>

<style scoped>

</style>
