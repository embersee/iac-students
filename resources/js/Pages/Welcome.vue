<script lang="ts" setup>
import { Head, Link } from "@inertiajs/vue3";

defineProps<{
    canLogin?: boolean;
    laravelVersion: string;
    phpVersion: string;
}>();
</script>

<template>
    <Head title="Welcome" />

    <div
        class="bg-dots-darker dark:bg-dots-lighter relative min-h-screen bg-gray-100 bg-center selection:bg-red-500 selection:text-white dark:bg-gray-900 sm:flex sm:items-center sm:justify-center"
    >
        <div v-if="canLogin" class="p-6 text-end sm:fixed sm:right-0 sm:top-0">
            <Link
                v-if="$page.props.auth.user"
                :href="route('dashboard')"
                class="font-semibold text-gray-600 hover:text-gray-900 focus:rounded-sm focus:outline focus:outline-2 focus:outline-red-500 dark:text-gray-400 dark:hover:text-white"
                >Dashboard
            </Link>

            <template v-else>
                <Link
                    :href="route('login')"
                    class="font-semibold text-gray-600 hover:text-gray-900 focus:rounded-sm focus:outline focus:outline-2 focus:outline-red-500 dark:text-gray-400 dark:hover:text-white"
                    >Log in
                </Link>
            </template>
        </div>

        <footer class="mx-auto mt-auto max-w-7xl p-6 lg:p-8">
            <div
                class="ms-4 text-center text-sm text-gray-500 dark:text-gray-400 sm:ms-0 sm:text-end"
            >
                Laravel v{{ laravelVersion }} (PHP v{{ phpVersion }})
            </div>
        </footer>
    </div>
</template>

<style></style>
