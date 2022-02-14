<template>
    <v-app>
        <!-- PC nav -->
        <v-navigation-drawer v-if="width > this.break" class="memberCol hidden-sm-and-down" elevation="10" app clipped>
            <v-list>
                <v-list-item>
                    <v-list-item-content>
                        <v-list-item-title class="text-h6"> Menu </v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-list>

            <v-divider></v-divider>
            <v-list nav dense>
                <v-list-item @click="sheet = !sheet">
                    <v-list-item-icon>
                        <v-icon>fas fa-database</v-icon>
                    </v-list-item-icon>
                    <v-list-item-title>DB List</v-list-item-title>
                </v-list-item>
                <v-divider inset></v-divider>
                <template v-if="$page.props.user">
                    <inertia-link as="v-list-item" link :href="route('user.book')">
                        <v-list-item-icon>
                            <v-icon>fas fa-bookmark</v-icon>
                        </v-list-item-icon>
                        <v-list-item-title>BookMark</v-list-item-title>
                    </inertia-link>
                </template>
                <template v-else>
                    <v-tooltip top color="error">
                        <template v-slot:activator="{ on, attrs }">
                            <v-list-item link v-bind="attrs" v-on="on">
                                <v-list-item-icon>
                                    <v-icon>fas fa-bookmark</v-icon>
                                </v-list-item-icon>
                                <v-list-item-title>BookMark</v-list-item-title>
                            </v-list-item>
                        </template>
                        <span>ログインユーザーのみ</span>
                    </v-tooltip>
                </template>
                <v-divider inset></v-divider>
                <template v-if="$page.props.user">
                    <inertia-link as="v-list-item" link :href="route('user.added')">
                        <v-list-item-icon>
                            <v-icon>fas fa-folder-open</v-icon>
                        </v-list-item-icon>
                        <v-list-item-title>YourData</v-list-item-title>
                    </inertia-link>
                </template>
                <template v-else>
                    <v-tooltip top color="error">
                        <template v-slot:activator="{ on, attrs }">
                            <v-list-item link v-bind="attrs" v-on="on">
                                <v-list-item-icon>
                                    <v-icon>fas fa-folder-open</v-icon>
                                </v-list-item-icon>
                                <v-list-item-title>YourData</v-list-item-title>
                            </v-list-item>
                        </template>
                        <span>ログインユーザーのみ</span>
                    </v-tooltip>
                </template>
                <v-divider inset></v-divider>
                <inertia-link as="v-list-item" link :href="route('data.add')">
                    <v-list-item-icon>
                        <v-icon>fas fa-cloud-upload-alt</v-icon>
                    </v-list-item-icon>
                    <v-list-item-title>AddData</v-list-item-title>
                </inertia-link>
            </v-list>
        </v-navigation-drawer>

        <!-- header -->
        <template>
            <!-- PC header -->
            <v-app-bar elevation="3" v-if="width > this.break" style="padding: 5px 10vw" class="memberCol hidden-sm-and-down" app clipped-left>
                <v-toolbar-title class="text-h4 SiteTitle" style="font-family: 'Dela Gothic One', cursive !important">
                    非公式いせぶいDB
                </v-toolbar-title>

                <v-spacer></v-spacer>
                <v-menu offset-y v-if="$page.props.user">
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn color="primary" small depressed v-bind="attrs" v-on="on" outlined>
                            <v-icon>mdi-account</v-icon>
                            <span>account</span>
                        </v-btn>
                    </template>
                    <v-list>
                        <v-list-item @click="logout">
                            <v-list-item-title>Logout</v-list-item-title>
                        </v-list-item>
                    </v-list>
                </v-menu>
                <template v-else>
                    <inertia-link class="mr-3" as="v-btn" color="primary" small outlined :href="route('myLogin')">
                        <v-icon small class="pr-1">fas fa-sign-in-alt</v-icon>
                        <span>Login</span>
                    </inertia-link>
                    <inertia-link small as="v-btn" color="primary" outlined :href="route('register')">
                        <v-icon small class="pr-1">fas fa-user-plus</v-icon>
                        <span>register</span>
                    </inertia-link>
                </template>
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
                <v-toolbar-title class="text-h6" id="SiteTitle"> 非公式いせぶいDB </v-toolbar-title>

                <v-spacer></v-spacer>
                <v-menu offset-y v-if="$page.props.user">
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn color="primary" small depressed v-bind="attrs" v-on="on" outlined>
                            <v-icon>mdi-account</v-icon>
                        </v-btn>
                    </template>
                    <v-list>
                        <v-list-item @click="logout">
                            <v-list-item-title>Logout</v-list-item-title>
                        </v-list-item>
                    </v-list>
                </v-menu>
                <template v-else>
                    <inertia-link class="mr-1" small as="v-btn" color="primary" outlined :href="route('myLogin')">
                        <v-icon small>fas fa-sign-in-alt</v-icon>
                    </inertia-link>
                    <inertia-link as="v-btn" small color="primary" outlined :href="route('register')">
                        <v-icon small>fas fa-user-plus</v-icon>
                    </inertia-link>
                </template>
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
            <v-main v-if="width > this.break" class="hidden-sm-and-down mt-5 mx-auto" style="width: 95%; z-index: 2" app absolute>
                <slot></slot>
            </v-main>
            <!-- SP container -->
            <v-main v-if="width <= this.break" class="hidden-md-and-up mt-5 mx-0" style="width: 100%; z-index: 2" app absolute>
                <slot></slot>
            </v-main>
        </template>

        <!-- footer -->
        <template>
            <!-- PC footer -->
            <v-footer v-if="width > this.break" app padless class="footCol hidden-sm-and-down m-auto">
                <v-col class="text-center" cols="12">
                    <p style="margin-bottom: 0.2rem; font-size: 0.8rem">非公式いせぶいDB v.3.4.0</p>
                    <p style="margin-bottom: 0.1rem; font-size: 0.5rem">
                        当サイト内コンテンツの著作権、肖像権は、すべて、個人VTuberグループ「いせぶい」及びその所属メンバーに帰属します。
                    </p>
                    <p style="margin-bottom: 0.5rem; font-size: 0.6rem">管理人：@Mmazoku_media</p>
                </v-col>
            </v-footer>
            <!-- SP footer -->
            <template v-if="width <= this.break">
                <v-card style="z-index: 2" class="hidden-md-and-up">
                    <v-col class="text-center" cols="12">
                        <p style="margin-bottom: 0.2rem; font-size: 0.8rem">非公式いせぶいDB v.3.4.0</p>
                        <p style="margin-bottom: 0.1rem; font-size: 0.5rem">
                            当サイト内コンテンツの著作権、肖像権は、<br />
                            すべて、個人VTuberグループ「いせぶい」及びその所属メンバーに帰属します。
                        </p>
                        <p style="margin-bottom: 0.5rem; font-size: 0.6rem">管理人：@Mmazoku_media</p>
                    </v-col>
                </v-card>

                <v-bottom-navigation app class="hidden-md-and-up m-auto py-1 memberCol" grow>
                    <v-btn fab depressed class="memberCol" @click="sheet = !sheet">
                        <span class="my-1">DB List</span>
                        <v-icon>fas fa-database</v-icon>
                    </v-btn>
                    <template v-if="$page.props.user">
                        <inertia-link as="v-btn" fab depressed class="memberCol" :href="route('user.book')">
                            <span class="my-1">BookMark</span>
                            <v-icon>fas fa-bookmark</v-icon>
                        </inertia-link>
                    </template>
                    <template v-else>
                        <v-tooltip top color="error">
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn fab depressed class="memberCol" dark v-bind="attrs" v-on="on">
                                    <span class="my-1">BookMark</span>
                                    <v-icon>fas fa-bookmark</v-icon>
                                </v-btn>
                            </template>
                            <span>ログインユーザーのみ</span>
                        </v-tooltip>
                    </template>
                    <template v-if="$page.props.user">
                        <inertia-link as="v-btn" fab depressed class="memberCol" :href="route('user.added')">
                            <span class="my-1">YourData</span>
                            <v-icon>fas fa-folder-open</v-icon>
                        </inertia-link>
                    </template>
                    <template v-else>
                        <v-tooltip top color="error">
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn fab depressed class="memberCol" dark v-bind="attrs" v-on="on">
                                    <span class="my-1">YourData</span>
                                    <v-icon>fas fa-folder-open</v-icon>
                                </v-btn>
                            </template>
                            <span>ログインユーザーのみ</span>
                        </v-tooltip>
                    </template>
                    <inertia-link as="v-btn" fab depressed class="memberCol" :href="route('data.add')">
                        <span class="my-1">AddData</span>
                        <v-icon>fas fa-cloud-upload-alt</v-icon>
                    </inertia-link>
                </v-bottom-navigation>
            </template>
        </template>

        <!-- DB list -->
        <v-bottom-sheet v-model="sheet" hide-overlay>
            <v-sheet class="text-center" style="background-color: #eee">
                <v-btn class="mt-3 mb-6 mx-auto" text color="red" @click="sheet = !sheet"> close </v-btn>
                <v-divider class="mb-6"></v-divider>
                <inertia-link as="v-btn" style="color: #111" large class="my-3 mx-auto" href="/">非公式いせぶいDBトップ </inertia-link>
                <div class="py-6 pb-10" style="padding: 0 10vw">
                    <v-row no-gutters>
                        <template v-for="member in $page.props.setting.member">
                            <v-col :key="member.name" cols="6" style="padding: 0 2vw">
                                <inertia-link
                                    as="v-btn"
                                    :color="member.ImageCol"
                                    block
                                    large
                                    class="my-3"
                                    style="color: #111"
                                    :href="'/' + member.name + '/latest'"
                                >
                                    {{ member.display }}
                                </inertia-link>
                            </v-col>
                        </template>
                    </v-row>
                </div>
            </v-sheet>
        </v-bottom-sheet>

        <template><slot name="playerWindow"></slot></template>
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
            break: 900,
            width: window.innerWidth,
        }
    },
    methods: {
        logout() {
            this.$inertia.post(route("logout"))
        },
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
