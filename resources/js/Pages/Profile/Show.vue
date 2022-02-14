<template>
    <app-layout>
        <template #header> Profile </template>

        <v-card>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <logout-other-browser-sessions-form :sessions="sessions" class="mt-10 sm:mt-0" />

                <template v-if="$page.props.jetstream.hasAccountDeletionFeatures">
                    <jet-section-border />

                    <delete-user-form class="mt-10 sm:mt-0" />
                </template>
            </div>
        </v-card>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/TopLayout"
import DeleteUserForm from "./DeleteUserForm"
import JetSectionBorder from "@/Jetstream/SectionBorder"
import LogoutOtherBrowserSessionsForm from "./LogoutOtherBrowserSessionsForm"

export default {
    props: ["sessions"],

    components: {
        AppLayout,
        DeleteUserForm,
        JetSectionBorder,
        LogoutOtherBrowserSessionsForm,
    },
}
</script>
