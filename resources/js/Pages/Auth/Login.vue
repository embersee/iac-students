<script lang="ts" setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { telegramLoginTemp } from "vue3-telegram-login";
import { ref } from "vue";
import axios from "axios";
import { TelegramLoginData } from "@/types";

const submit = (user: TelegramLoginData) => {
    // TODO: Rework to use VueQuery
    console.log("sending");
    axios
        .post("/login", user, {
            headers: {
                "Content-Type": "application/json",
            },
        })
        .then((response) => {
            if (response.data.status === "success") {
                router.visit(response.data.url);
            } else {
                // notify(response.data.message);
            }
        });
};

const isLoaded = ref(false);

function telegramLoadedCallbackFunc() {
    console.log("script is loaded");
    isLoaded.value = true;
}
</script>

<template>
    <GuestLayout>
        <Head title="Login" />

        <telegram-login-temp
            mode="callback"
            telegram-login="iacstudent_bot"
            @callback="submit"
            @loaded="telegramLoadedCallbackFunc"
        />
        <component
            async
            data-auth-url="{{route('login')}}"
            data-request-access="write"
            data-size="large"
            data-telegram-login="iacstudents_bot"
            src="https://telegram.org/js/telegram-widget.js?22"
            type="script"
        ></component>
    </GuestLayout>
</template>
