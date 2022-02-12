<template>
    <v-app>
        <v-card>
            <v-tabs show-arrows fixed-tabs>
                <inertia-link as="v-tab" :href="'/' + memberName + '/latest'">Latest</inertia-link>

                <template v-for="(category, index) in this.$page.props.setting.category">
                    <inertia-link as="v-tab" :key="index" :href="'/' + memberName + '/' + category.id">{{ category.title }}</inertia-link>
                </template>
                <v-tab class="d-none">hidden</v-tab>
            </v-tabs>
        </v-card>
    </v-app>
</template>

<script>
export default {
    props: ["memberName"],
}
</script>
