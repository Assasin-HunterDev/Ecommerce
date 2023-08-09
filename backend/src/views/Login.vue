<template>
    <GuestLayout title="Sign in to your account">
        <form method="POST" @submit.prevent="login" class="mt-8 space-y-6">
            <div v-if="errorMsg" class="flex items-center justify-between py-3 px-5 bg-red-500 text-white rounded">
                {{ errorMsg }}
                <span
                    class="w-8 h-8 flex items-center justify-center rounded-full transition-colors cursor-pointer hover:bg-[rgba(0,0,0,0.2)]"
                    @click="errorMsg = ''"
                >
                    <XIcon class="h-5 w-5 text-white"/>
                </span>
            </div>
            <input type="hidden" name="remember" value="true"/>
            <div class="-space-y-px rounded-md shadow-sm">
                <div>
                    <label for="email-address" class="sr-only">Email address</label>
                    <input id="email-address" name="email" type="email" autocomplete="email" required=""
                           v-model="user.email"
                           class="relative block w-full appearance-none rounded-none rounded-t-md border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-500 focus:z-10 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm"
                           placeholder="Email address"/>
                </div>
                <div>
                    <label for="password" class="sr-only">Password</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required=""
                           v-model="user.password"
                           class="relative block w-full appearance-none rounded-none rounded-b-md border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-500 focus:z-10 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm"
                           placeholder="Password"/>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember-me" v-model="user.remember" name="remember-me" type="checkbox"
                           class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"/>
                    <label for="remember-me" class="ml-2 block text-sm text-gray-900">Remember me</label>
                </div>

                <div class="text-sm">
                    <router-link :to="{name: 'forgotPassword'}"
                                 class="font-medium text-indigo-600 hover:text-indigo-500">Forgot your password?
                    </router-link>
                </div>
            </div>

            <div>
                <button type="submit"
                        :class="{
                            'cursor-not-allowed': loading,
                            'hover:bg-indigo-500': loading
                        }"
                        :disabled="loading"
                        class="group relative flex w-full justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    <svg v-if="loading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none"
                         viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                        <path class="opacity-75"
                              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                              fill="currentColor"></path>
                    </svg>
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                          <LockClosedIcon class="h-5 w-5 text-indigo-500 group-hover:text-indigo-400"
                                          aria-hidden="true"/>
                    </span>
                    Sign in
                </button>
            </div>
        </form>
    </GuestLayout>
</template>

<script setup lang="ts">
import {ref} from "vue";
import {useRouter} from "vue-router";
import store from "../store";
import GuestLayout from "../components/GuestLayout.vue";
import {LockClosedIcon, XIcon} from "@heroicons/vue/solid";

interface UserModel {
    email: string;
    password: string;
    remember: boolean;
}

const router = useRouter();

let loading = ref(false);
let errorMsg = ref("");

const user: UserModel = {
    email: "",
    password: "",
    remember: false,
};

function login() {
    loading.value = true;
    store.dispatch("login", user)
        .then(() => {
            loading.value = false;
            router.push({name: "app.dashboard"})
        })
        .catch(({response}) => {
            loading.value = false;
            errorMsg.value = response.data.message;
        })
}
</script>
