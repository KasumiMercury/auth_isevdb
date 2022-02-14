<template>
    <v-btn :type="type" class="ml-4" color="info"><slot></slot></v-btn>
</template>

<script>
export default {
    props: {
        type: {
            type: String,
            default: "submit",
        },
    },
}
</script>
