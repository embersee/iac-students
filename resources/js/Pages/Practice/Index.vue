<script lang="ts" setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import CreatePracticeForm from "@/Pages/Practice/Partials/CreatePracticeForm.vue";
</script>

<template>
    <Head title="Practice" />

    <AuthenticatedLayout>
        <template #header> Practice</template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <CreatePracticeForm />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
