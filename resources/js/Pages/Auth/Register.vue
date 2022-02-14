<template>
    <app-layout>
        <template #header> Register </template>
        <v-card class="p-10">
            <jet-validation-errors class="mb-4" />

            <form @submit.prevent="submit">
                <div>
                    <jet-label for="name" value="Name" />
                    <jet-input id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus autocomplete="name" />
                </div>

                <div class="mt-4">
                    <jet-label for="password" value="Password" />
                    <jet-input id="password" type="password" class="mt-1 block w-full" v-model="form.password" required autocomplete="new-password" />
                </div>

                <div class="mt-4">
                    <jet-label for="password_confirmation" value="Confirm Password" />
                    <jet-input
                        id="password_confirmation"
                        type="password"
                        class="mt-1 block w-full"
                        v-model="form.password_confirmation"
                        required
                        autocomplete="new-password"
                    />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <inertia-link :href="route('myLogin')" class="underline text-sm text-gray-600 hover:text-gray-900">
                        Already registered?
                    </inertia-link>

                    <jet-button class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"> 登録 </jet-button>
                </div>
            </form>
        </v-card>
        <v-card class="my-3 px-10">
            <v-btn class="mx-auto my-5 py-10" color="#ea4335" style="color: #eee" block :href="route('sns_login.redirect', 'google')"
                ><v-icon>fab fa-google</v-icon>　Googleでログイン</v-btn
            >
        </v-card>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/TopLayout"
import JetAuthenticationCard from "@/Jetstream/AuthenticationCard"
import JetAuthenticationCardLogo from "@/Jetstream/AuthenticationCardLogo"
import JetButton from "@/Jetstream/Button"
import JetInput from "@/Jetstream/Input"
import JetLabel from "@/Jetstream/Label"
import JetValidationErrors from "@/Jetstream/ValidationErrors"

export default {
    components: {
        JetAuthenticationCard,
        JetAuthenticationCardLogo,
        JetButton,
        JetInput,
        JetLabel,
        JetValidationErrors,
        AppLayout,
    },

    data() {
        return {
            form: this.$inertia.form({
                name: "",
                email: "",
                password: "",
                password_confirmation: "",
            }),
        }
    },

    methods: {
        submit() {
            this.form.post(this.route("register"), {
                onFinish: () => this.form.reset("password", "password_confirmation"),
            })
        },
    },
}
</script>
