<script lang="ts" setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import axios from "axios";
import { AuthResponse, TelegramUser } from "@/types";
import TelegramLoginWidget from "@/Components/TelegramLoginWidget.vue";
import { useMutation } from "@tanstack/vue-query";

const { mutate } = useMutation({
    mutationFn: (user: TelegramUser) => axios.post("/login", user),
    onSuccess: (res: { data: AuthResponse }) => {
        router.visit(res.data.url);
    },
});

const submitTelegramLogin = (user: TelegramUser) => {
    mutate(user);
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
