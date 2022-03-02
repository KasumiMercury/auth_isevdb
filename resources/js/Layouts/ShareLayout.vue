<template>
    <v-app>
        <!-- header -->
        <template>
            <!-- PC header -->
            <v-app-bar elevation="3" v-if="width > this.break" style="padding: 5px 10vw" class="memberCol hidden-sm-and-down" app clipped-left>
                <v-toolbar-title class="text-h4 SiteTitle" style="font-family: 'Dela Gothic One', cursive !important">
                    <slot name="title"></slot>
                </v-toolbar-title>

                <template v-slot:extension>
                    <div class="pl-20">
                        <v-divider></v-divider>
                        <v-subheader>
                            <slot name="header"></slot>
                        </v-subheader>
                    </div>
                </template>
            </v-app-bar>
            <!-- SP header -->
            <v-app-bar
                elevation="3"
                v-if="width <= this.break"
                style="padding: 5px 2vw; z-index: 5"
                class="hidden-md-and-up memberCol"
                app
                clipped-left
            >
                <v-toolbar-title class="text-h6" id="SiteTitle"> <slot name="title"></slot> </v-toolbar-title>

                <template v-slot:extension>
                    <div class="pl-2">
                        <v-divider></v-divider>
                        <v-subheader>
                            <slot name="header"></slot>
                        </v-subheader>
                    </div>
                </template>
            </v-app-bar>
        </template>

        <!-- container -->
        <template>
            <!-- PC container -->
            <v-main v-if="width > this.break" class="hidden-sm-and-down mt-5 mx-auto" style="width: 70%; z-index: 2" app absolute>
                <slot></slot>
                <!-- PC footer -->
                <template>
                    <v-card class="hidden-sm-and-down mx-auto my-10 text-center" style="padding-top: 0.5rem; z-index: 2" width="auto">
                        <p style="margin-bottom: 0.2rem; font-size: 0.8rem">非公式いせぶいDB v.4.6.0</p>
                        <p style="margin-bottom: 0.1rem; font-size: 0.5rem">
                            当サイト内コンテンツの著作権、肖像権は、<br />
                            すべて、個人VTuberグループ「いせぶい」及びその所属メンバーに帰属します。
                        </p>
                        <p style="margin-bottom: 0.5rem; font-size: 0.6rem">管理人：@Mmazoku_media</p>
                    </v-card>
                </template>
            </v-main>
            <!-- SP container -->
            <v-main v-if="width <= this.break" class="hidden-md-and-up mt-5 mx-auto" style="width: 95%; z-index: 2" app absolute>
                <slot></slot>
            </v-main>
        </template>

        <!-- SP footer -->
        <template v-if="width <= this.break">
            <v-card style="z-index: 2" class="hidden-md-and-up">
                <v-col class="text-center" cols="12">
                    <p style="margin-bottom: 0.2rem; font-size: 0.8rem">非公式いせぶいDB v.4.6.0</p>
                    <p style="margin-bottom: 0.1rem; font-size: 0.5rem">
                        当サイト内コンテンツの著作権、肖像権は、<br />
                        すべて、個人VTuberグループ「いせぶい」及びその所属メンバーに帰属します。
                    </p>
                    <p style="margin-bottom: 0.5rem; font-size: 0.6rem">管理人：@Mmazoku_media</p>
                </v-col>
            </v-card>
        </template>
    </v-app>
</template>

<style lang="scss">
html {
    height: 100% !important;
    width: 100% !important;
    padding: 0;
    margin: 0;
}
.v-application {
    font-family: "Raleway", "Zen Maru Gothic", sans-serif !important;
    color: #ccc !important;
}
#SiteTitle {
    font-family: "Dela Gothic One", cursive !important;
}
</style>
<script>
export default {
    data() {
        return {
            sheet: false,
            break: 960,
            width: window.innerWidth,
        }
    },
    methods: {
        handleResize: function () {
            this.width = window.innerWidth
        },
    },
    mounted: function () {
        window.addEventListener("resize", this.handleResize)
    },
    beforeDestroy: function () {
        window.removeEventListener("resize", this.handleResize)
    },
}
</script>
