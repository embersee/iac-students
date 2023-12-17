<script lang="ts" setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import axios from "axios";
import { TelegramLoginData } from "@/types";
import TelegramLoginWidget from "@/Components/TelegramLoginWidget.vue";

const submitTelegramLogin = (user: TelegramLoginData) => {
    // TODO: Rework to use VueQuery
    axios.post("/login", user).then((response) => {
        if (response.data.status === "success") {
            router.visit(response.data.url);
        } else {
            // notify(response.data.message);
        }
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Login" />

        <div class="flex justify-center">
            <telegram-login-widget
                mode="callback"
                telegram-login="iacstudent_bot"
                @callback="submitTelegramLogin"
            />
        </div>
    </GuestLayout>
</template>
