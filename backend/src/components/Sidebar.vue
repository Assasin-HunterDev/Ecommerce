<template>
    <div class="min-w-[200px] w-[200px] transition-all bg-indigo-600 text-white py-4 px-2">
        <router-link :to="{name: 'app.dashboard'}"
                     class="flex items-center p-2 rounded-md transition-colors hover:bg-indigo-700">
      <span class="mr-2 text-white">
        <HomeIcon class="w-5"/>
      </span>
            <span class="text-sm font-normal">
        Dashboard
      </span>
        </router-link>
        <router-link :to="{name: 'app.products'}"
                     class="flex items-center p-2 rounded-md transition-colors hover:bg-indigo-700">
      <span class="mr-2 text-white">
        <ViewListIcon class="w-5"/>
      </span>
            <span class="text-sm font-normal">
        Products
      </span>
        </router-link>
        <router-link to=""
                     class="flex items-center p-2 rounded-md transition-colors hover:bg-indigo-700">
      <span class="mr-2 text-white">
        <ViewListIcon class="w-5"/>
      </span>
            <span class="text-sm font-normal">
        Orders
      </span>
        </router-link>
        <router-link to=""
                     class="flex items-center p-2 rounded-md transition-colors hover:bg-indigo-700">
      <span class="mr-2 text-white">
        <UsersIcon class="w-5"/>
      </span>
            <span class="text-sm font-normal">
        Users
      </span>
        </router-link>
        <router-link to=""
                     class="flex items-center p-2 rounded-md transition-colors hover:bg-indigo-700">
      <span class="mr-2 text-white">
        <UserGroupIcon class="w-5"/>
      </span>
            <span class="text-sm font-normal">
        Customers
      </span>
        </router-link>
        <router-link to=""
                     class="flex items-center p-2 rounded-md transition-colors hover:bg-indigo-700">
      <span class="mr-2 text-white">
        <ChartBarIcon class="w-5"/>
      </span>
            <span class="text-sm font-normal">
        Reports
      </span>
        </router-link>
    </div>
</template>

<script setup lang="ts">
import {ChartBarIcon, HomeIcon, UserGroupIcon, UsersIcon, ViewListIcon} from "@heroicons/vue/outline";</script>

<style scoped>

</style>
