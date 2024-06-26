<template>
    <jet-action-section>
        <template #title> Delete Account </template>

        <template #description> 完全にアカウントを削除 </template>

        <template #content>
            <div class="max-w-xl text-sm text-gray-600">一度アカウントを削除するとアカウントとデータの紐付けは永久に削除されます。</div>

            <div class="mt-5">
                <jet-danger-button @click.native="confirmUserDeletion"> アカウントを削除 </jet-danger-button>
            </div>

            <!-- Delete Account Confirmation Modal -->
            <jet-dialog-modal :show="confirmingUserDeletion" @close="closeModal">
                <template #title> アカウントを削除 </template>

                <template #content>
                    一度アカウントを削除するとアカウントとデータの紐付けは永久に削除されます。
                    確認のためパスワードを入力してください。（Googleでログインした方は「google」と入力してください。）

                    <div class="mt-4">
                        <jet-input
                            type="password"
                            class="mt-1 block w-3/4"
                            placeholder="Password"
                            ref="password"
                            v-model="form.password"
                            @keyup.enter.native="deleteUser"
                        />

                        <jet-input-error :message="form.errors.password" class="mt-2" />
                    </div>
                </template>

                <template #footer>
                    <jet-secondary-button @click.native="closeModal"> Nevermind </jet-secondary-button>

                    <jet-danger-button class="ml-2" @click.native="deleteUser" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Delete Account
                    </jet-danger-button>
                </template>
            </jet-dialog-modal>
        </template>
    </jet-action-section>
</template>

<script>
import JetActionSection from "@/Jetstream/ActionSection"
import JetDialogModal from "@/Jetstream/DialogModal"
import JetDangerButton from "@/Jetstream/DangerButton"
import JetInput from "@/Jetstream/Input"
import JetInputError from "@/Jetstream/InputError"
import JetSecondaryButton from "@/Jetstream/SecondaryButton"

export default {
    components: {
        JetActionSection,
        JetDangerButton,
        JetDialogModal,
        JetInput,
        JetInputError,
        JetSecondaryButton,
    },

    data() {
        return {
            confirmingUserDeletion: false,

            form: this.$inertia.form({
                password: "",
            }),
        }
    },

    methods: {
        confirmUserDeletion() {
            this.confirmingUserDeletion = true

            setTimeout(() => this.$refs.password.focus(), 250)
        },

        deleteUser() {
            this.form.delete(route("current-user.destroy"), {
                preserveScroll: true,
                onSuccess: () => this.closeModal(),
                onError: () => this.$refs.password.focus(),
                onFinish: () => this.form.reset(),
            })
        },

        closeModal() {
            this.confirmingUserDeletion = false

            this.form.reset()
        },
    },
}
</script>
