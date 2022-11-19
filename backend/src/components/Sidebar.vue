<template>
    <div class="min-w-[160px] w-[160px] transition-all bg-indigo-600 text-white py-4 px-2">
        <router-link to=""
                     class="flex items-center p-2 rounded transition-colors hover:bg-black/30">
      <span class="mr-2 text-gray-300">
        <HomeIcon class="w-5"/>
      </span>
            <span class="text-xs">
        Dashboard
      </span>
        </router-link>
        <router-link to=""
                     class="flex items-center p-2 rounded transition-colors hover:bg-black/30">
      <span class="mr-2 text-gray-300">
        <ViewListIcon class="w-5"/>
      </span>
            <span class="text-xs">
        Products
      </span>
        </router-link>
        <router-link to=""
                     class="flex items-center p-2 rounded transition-colors hover:bg-black/30">
      <span class="mr-2 text-gray-300">
        <ViewListIcon class="w-5"/>
      </span>
            <span class="text-xs">
        Orders
      </span>
        </router-link>
        <router-link to=""
                     class="flex items-center p-2 rounded transition-colors hover:bg-black/30">
      <span class="mr-2 text-gray-300">
        <UsersIcon class="w-5"/>
      </span>
            <span class="text-xs">
        Users
      </span>
        </router-link>
        <router-link to=""
                     class="flex items-center p-2 rounded transition-colors hover:bg-black/30">
      <span class="mr-2 text-gray-300">
        <UserGroupIcon class="w-5"/>
      </span>
            <span class="text-xs">
        Customers
      </span>
        </router-link>
        <router-link to=""
                     class="flex items-center p-2 rounded transition-colors hover:bg-black/30">
      <span class="mr-2 text-gray-300">
        <ChartBarIcon class="w-5"/>
      </span>
            <span class="text-xs">
        Reports
      </span>
        </router-link>
    </div>
</template>

<script setup lang="ts">
import {HomeIcon, UserGroupIcon, UsersIcon, ViewListIcon, ChartBarIcon} from "@heroicons/vue/outline";
</script>

<style scoped>

</style>
