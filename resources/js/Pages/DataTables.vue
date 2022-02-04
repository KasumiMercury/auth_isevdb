<template>
    <v-app>
        <v-main>
            <v-card flat tile>
                <v-app-bar flat outlined>
                    <v-toolbar-title>サンプルテーブル</v-toolbar-title>
                </v-app-bar>
                <v-data-table :headers="headers" :items="desserts" :items-per-page="5" class="elevation-1"></v-data-table>
            </v-card>
        </v-main>
    </v-app>
</template>

<script>
export default {
    name: "DataTables",
    props: ["desserts"],
    data() {
        return {
            headers: [
                {
                    text: "Dessert (100g serving)",
                    align: "start",
                    sortable: false,
                    value: "name",
                },
                { text: "Calories", value: "calories" },
                { text: "Fat (g)", value: "fat" },
                { text: "Carbs (g)", value: "carbs" },
                { text: "Protein (g)", value: "protein" },
                { text: "Iron (%)", value: "iron" },
            ],
        }
    },
}
</script>

<style scoped></style>
