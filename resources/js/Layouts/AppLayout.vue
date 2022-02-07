<template>
    <div id="app">
        <v-app>
            <!-- PC nav -->
            <v-navigation-drawer v-if="width > this.break" class="hidden-sm-and-down" permanent floating app clipped>
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
                    <inertia-link as="v-list-item" link :href="route('profile.show')">
                        <v-list-item-icon>
                            <v-icon>fas fa-bookmark</v-icon>
                        </v-list-item-icon>
                        <v-list-item-title>BookMark</v-list-item-title>
                    </inertia-link>
                    <v-divider inset></v-divider>
                    <inertia-link as="v-list-item" link :href="route('profile.show')">
                        <v-list-item-icon>
                            <v-icon>fas fa-folder-open</v-icon>
                        </v-list-item-icon>
                        <v-list-item-title>YourData</v-list-item-title>
                    </inertia-link>
                    <v-divider inset></v-divider>
                    <inertia-link as="v-list-item" link :href="route('profile.show')">
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
                <v-app-bar
                    elevation="3"
                    v-if="width > this.break"
                    style="padding: 5px 10vw"
                    color="white"
                    class="hidden-sm-and-down"
                    app
                    clipped-left
                >
                    <v-toolbar-title class="text-h4"> 非公式いせぶいDB </v-toolbar-title>

                    <v-spacer></v-spacer>
                    <v-menu offset-y v-if="$page.props.user">
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn color="primary" small depressed v-bind="attrs" v-on="on" outlined>
                                <v-icon>mdi-account</v-icon>
                                <span>account</span>
                            </v-btn>
                        </template>
                        <v-list>
                            <inertia-link as="v-list-item" :href="route('profile.show')">
                                <v-list-item-title>Profile</v-list-item-title>
                            </inertia-link>
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
                    style="padding: 5px 2vw"
                    absolute
                    color="white"
                    class="hidden-md-and-up"
                    app
                    clipped-left
                >
                    <v-toolbar-title class="text-h5"> 非公式いせぶいDB </v-toolbar-title>

                    <v-spacer></v-spacer>
                    <v-menu offset-y v-if="$page.props.user">
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn color="primary" small depressed v-bind="attrs" v-on="on" outlined>
                                <v-icon>mdi-account</v-icon>
                            </v-btn>
                        </template>
                        <v-list>
                            <inertia-link as="v-list-item" :href="route('profile.show')">
                                <v-list-item-title>Profile</v-list-item-title>
                            </inertia-link>
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
                <v-main v-if="width > this.break" class="hidden-sm-and-down m-auto" app absolute>
                    <slot></slot>
                </v-main>
                <!-- SP container -->
                <v-main v-if="width <= this.break" class="hidden-md-and-up m-auto" style="width: 100vw" app clipped-left> <slot></slot> </v-main
            ></template>

            <!-- footer -->
            <template>
                <!-- PC footer -->
                <v-footer v-if="width > this.break" app padless fixed class="hidden-sm-and-down m-auto">
                    <v-col class="text-center" cols="12">
                        <p style="margin-bottom: 0.1rem; font-size: 0.5rem">
                            当サイト内コンテンツの著作権、肖像権は、<br />
                            すべて、個人VTuberグループ「いせぶい」及びその所属メンバーに帰属します。
                        </p>
                        <p style="margin-bottom: 0.5rem; font-size: 0.6rem">管理人：@Mmazoku_media</p>
                    </v-col>
                </v-footer>
                <!-- SP footer -->
                <template v-if="width <= this.break">
                    <v-container style="margin: 58px auto" class="hidden-md-and-up">
                        <v-col class="text-center" cols="12">
                            <p style="margin-bottom: 0.1rem; font-size: 0.5rem">
                                当サイト内コンテンツの著作権、肖像権は、<br />
                                すべて、個人VTuberグループ「いせぶい」及びその所属メンバーに帰属します。
                            </p>
                            <p style="margin-bottom: 0.5rem; font-size: 0.6rem">管理人：@Mmazoku_media</p>
                        </v-col>
                    </v-container>

                    <v-bottom-navigation app class="hidden-md-and-up m-auto py-1" grow>
                        <v-btn fab depressed color="white" @click="sheet = !sheet">
                            <span class="my-1">DB List</span>
                            <v-icon>fas fa-database</v-icon>
                        </v-btn>
                        <inertia-link as="v-btn" fab depressed color="white" :href="route('profile.show')">
                            <span class="my-1">BookMark</span>
                            <v-icon>fas fa-bookmark</v-icon>
                        </inertia-link>
                        <inertia-link as="v-btn" fab depressed color="white" :href="route('profile.show')">
                            <span class="my-1">YourData</span>
                            <v-icon>fas fa-folder-open</v-icon>
                        </inertia-link>
                        <inertia-link as="v-btn" fab depressed color="white" :href="route('profile.show')">
                            <span class="my-1">AddData</span>
                            <v-icon>fas fa-cloud-upload-alt</v-icon>
                        </inertia-link>
                    </v-bottom-navigation>
                </template>
            </template>

            <!-- DB list -->
            <v-bottom-sheet v-model="sheet" hide-overlay>
                <v-sheet class="text-center">
                    <v-btn class="mt-6" text color="red" @click="sheet = !sheet"> close </v-btn>
                    <div class="py-6"></div>
                </v-sheet>
            </v-bottom-sheet>
        </v-app>
    </div>
</template>

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
        setWindowWidth: _.debounce(function () {
            this.width = window.innerWidth
        }, 300),
    },
    created() {
        window.addEventListener("resize", this.setWindowWidth, false)
    },
    beforeDestroy: function () {
        window.removeEventListener("resize", this.setWindowWidth, false)
    },
}
</script>
