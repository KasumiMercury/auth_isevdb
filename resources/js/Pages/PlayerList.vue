<template>
    <v-app>
        <app-layout class="pb-80">
            <template #header> {{ currentMember.display }} PlayerList </template>

            <v-card elevation="0" outlined>
                <!--tab-->
                <v-tabs show-arrows fixed-tabs v-model="select">
                    <inertia-link as="v-tab" :href="'/' + memberName + '/latest'">Latest</inertia-link>

                    <template v-for="(category, index) in this.$page.props.setting.category">
                        <inertia-link as="v-tab" :key="index" :href="'/' + memberName + '/' + category.id">{{ category.title }}</inertia-link>
                    </template>
                    <v-tab class="d-none">hidden</v-tab>
                </v-tabs>

                <!--table-cards-->
                <v-container fluid>
                    <v-data-iterator
                        :items="players"
                        :items-per-page.sync="itemsPerPage"
                        :page.sync="page"
                        :sort-desc="sortDesc"
                        :search="search"
                        :sort-by="sortBy.toLowerCase()"
                        hide-default-footer
                    >
                        <template v-slot:header>
                            <v-row no-gutters justify="center">
                                <v-col class="px-1" cols="8">
                                    <v-text-field v-model="search" dense clearable hide-details prepend-icon="mdi-magnify" single-line></v-text-field>
                                </v-col>
                            </v-row>
                            <v-row class="px-2" justify="center" align="center">
                                <v-col class="ml-5 mr-auto" cols="12" sm="12" md="auto">
                                    <span class="grey--text">Items per page</span>
                                    <v-menu offset-y>
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-btn dark text color="grey darken-1" class="ml-2" v-bind="attrs" v-on="on">
                                                {{ itemsPerPage }}
                                                <v-icon>mdi-chevron-down</v-icon>
                                            </v-btn>
                                        </template>
                                        <v-list>
                                            <v-list-item
                                                v-for="(number, index) in itemsPerPageArray"
                                                :key="index"
                                                @click="updateItemsPerPage(number)"
                                            >
                                                <v-list-item-title>{{ number }}</v-list-item-title>
                                            </v-list-item>
                                        </v-list>
                                    </v-menu>
                                </v-col>
                                <v-col class="mr-0 ml-auto" sm="4" md="2">
                                    <v-select dense v-model="sortBy" flat hide-details="auto" :items="keys" label="Sort by"></v-select>
                                </v-col>
                                <v-col cols="auto" class="mr-1">
                                    <v-btn-toggle v-model="sortDesc" mandatory dense>
                                        <v-btn outlined :value="false" small>
                                            <v-icon>fas fa-caret-up</v-icon>
                                        </v-btn>
                                        <v-btn outlined :value="true" small>
                                            <v-icon>fas fa-caret-down</v-icon>
                                        </v-btn>
                                    </v-btn-toggle>
                                </v-col>
                            </v-row>
                            <template v-if="cate == 0">
                                <v-alert dense text type="success" class="mt-6 mx-10"> 新着20件のみを表示しています。 </v-alert>
                            </template>
                        </template>
                        <template v-slot:default="props">
                            <v-row dense>
                                <v-col v-for="(item, index) in props.items" :key="item.title" cols="12" sm="12" md="12" lg="12">
                                    <v-card :elevation="index + 1">
                                        <v-card-title>{{ item.title }}</v-card-title>
                                        <template v-if="item.twitter == null">
                                            <v-row>
                                                <v-col cols="auto" class="ml-10 mr-auto">
                                                    <v-card-subtitle>{{ item.date }}</v-card-subtitle>
                                                </v-col>
                                                <template v-if="item.status == 0">
                                                    <v-col cols="auto" class="ml-auto mr-10 mb-5">
                                                        <v-btn
                                                            color="#DA1725"
                                                            :style="'color: #EEE'"
                                                            @click="openPlayer(item.VideoID, item.start, item.end, item.id, item.cate_id)"
                                                        >
                                                            <v-icon>fab fa-youtube</v-icon>　Play
                                                        </v-btn>
                                                    </v-col>
                                                </template>
                                                <template v-if="item.status == 1">
                                                    <v-col cols="auto" class="ml-auto mr-10 mb-5">
                                                        <v-btn
                                                            color="#2BA640"
                                                            :style="'color: #EEE'"
                                                            @click="openPlayer(item.VideoID, item.start, item.end, item.id, item.cate_id)"
                                                        >
                                                            <v-icon>fab fa-youtube</v-icon>　Member Only
                                                        </v-btn>
                                                    </v-col>
                                                </template>
                                                <template v-if="item.status == 2">
                                                    <v-col cols="auto" class="ml-auto mr-10 mb-5">
                                                        <v-btn disabled :style="'color: #000'"> <v-icon>fab fa-youtube</v-icon>　Private </v-btn>
                                                    </v-col>
                                                </template>
                                            </v-row>
                                        </template>
                                        <template v-else>
                                            <v-row>
                                                <v-col cols="auto" class="ml-10 mr-auto">
                                                    <v-card-subtitle>{{ item.date }}（DB追加日）</v-card-subtitle>
                                                </v-col>
                                                <v-col cols="auto" class="ml-auto mr-10 mb-5">
                                                    <template v-if="item.cate == 5">
                                                        <v-btn color="#1DA1F2" :style="'color: #EEE'" @click="openTwitter(item.twitter)">
                                                            <v-icon>fab fa-twitter-square</v-icon>　Image
                                                        </v-btn>
                                                    </template>
                                                    <template v-else>
                                                        <v-btn color="#1DA1F2" :style="'color: #EEE'" @click="openTwitter(item.twitter)">
                                                            <v-icon>fab fa-twitter-square</v-icon>　Play
                                                        </v-btn>
                                                    </template>
                                                </v-col>
                                            </v-row>
                                        </template>
                                    </v-card>
                                </v-col>
                            </v-row>
                        </template>

                        <template v-slot:footer>
                            <div class="text-center py-4">
                                <v-pagination
                                    v-model="page"
                                    :length="pageLength"
                                    circle
                                    prev-icon="mdi-menu-left"
                                    next-icon="mdi-menu-right"
                                ></v-pagination>
                            </div>
                        </template>
                    </v-data-iterator>
                </v-container>
            </v-card>

            <template #playerWindow>
                <template v-if="width >= 900">
                    <v-card
                        :loading="loading"
                        elevation="20"
                        v-if="playerOpen"
                        width="50vw"
                        max-width="600px"
                        style="position: fixed; right: 30px; bottom: 30px; margin: 0; z-index: 10"
                    >
                        <template slot="progress">
                            <v-progress-linear color="pink accent-3" height="5" indeterminate></v-progress-linear>
                        </template>

                        <v-row>
                            <v-col cols="auto" class="p-0 mt-0 ml-1 mr-auto">
                                <inertia-link text as="v-btn" :href="'/' + currentMember.name + '/player/' + playIndex">詳細ページへ </inertia-link>
                            </v-col>
                            <v-col cols="auto" class="ml-auto mr-0">
                                <v-btn v-if="playerOpen" color="red" text @click="closePlayer">close</v-btn>
                            </v-col>
                        </v-row>
                        <v-responsive :aspect-ratio="16 / 9" class="m-1">
                            <iframe
                                v-if="playerOpen"
                                width="100%"
                                height="100%"
                                :src="'https://www.youtube.com/embed/' + playID + '?start=' + playStart + '&end=' + playEnd + '&rel=0&loop=1'"
                                title="YouTube video player"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen
                            ></iframe>
                        </v-responsive>
                        <v-row class="m-0 p-0">
                            <v-col cols="8" class="my-3 mx-auto">
                                <ShareNetwork
                                    v-if="playerOpen"
                                    network="twitter"
                                    :url="Tweet.url"
                                    :title="Tweet.title"
                                    :hashtags="Tweet.hash"
                                    :class="'text-decoration-none'"
                                >
                                    <v-btn v-show="playerOpen" color="#1DA1F2" style="color: #fff" block large
                                        ><v-icon>fas fa-share-square</v-icon>　Tweet</v-btn
                                    >
                                </ShareNetwork>
                            </v-col>
                        </v-row>
                    </v-card>
                </template>
                <template v-if="width < 900">
                    <v-card
                        :loading="loading"
                        elevation="20"
                        v-if="playerOpen"
                        width="100vw"
                        max-width="600px"
                        style="position: fixed; right: 0; bottom: 80px; margin: 0; z-index: 10"
                    >
                        <template slot="progress">
                            <v-progress-linear color="pink accent-3" height="5" indeterminate></v-progress-linear>
                        </template>

                        <v-row>
                            <v-col cols="auto" class="p-0 mt-0 ml-1 mr-auto">
                                <inertia-link text as="v-btn" :href="'/' + currentMember.name + '/player/' + playIndex">詳細ページへ </inertia-link>
                            </v-col>
                            <v-col cols="auto" class="ml-auto mr-0">
                                <v-btn v-if="playerOpen" color="red" text @click="closePlayer">close</v-btn>
                            </v-col>
                        </v-row>
                        <v-responsive :aspect-ratio="16 / 9" class="m-1">
                            <iframe
                                v-if="playerOpen"
                                width="100%"
                                height="100%"
                                :src="'https://www.youtube.com/embed/' + playID + '?start=' + playStart + '&end=' + playEnd + '&rel=0&loop=1'"
                                title="YouTube video player"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen
                            ></iframe>
                        </v-responsive>
                        <v-row class="m-0 p-0">
                            <v-col cols="8" class="my-3 mx-auto">
                                <ShareNetwork
                                    v-if="playerOpen"
                                    network="twitter"
                                    :url="Tweet.url"
                                    :title="Tweet.title"
                                    :hashtags="Tweet.hash"
                                    :class="'text-decoration-none'"
                                >
                                    <v-btn v-show="playerOpen" color="#1DA1F2" style="color: #fff" block large
                                        ><v-icon>fas fa-share-square</v-icon>　Tweet</v-btn
                                    >
                                </ShareNetwork>
                            </v-col>
                        </v-row>
                    </v-card>
                </template>
                <template>
                    <v-card
                        :loading="loading"
                        elevation="20"
                        v-if="twitterOpen"
                        style="position: fixed; right: 30px; bottom: 30px; margin: 0; z-index: 10"
                    >
                        <template slot="progress">
                            <v-progress-linear color="blue accent-2" height="5" indeterminate></v-progress-linear>
                        </template>
                        <template v-if="twitterOpen">
                            <Tweet :id="showTwitter" :style="'max-height: 80vh; max-width: 80vw'"></Tweet>
                            <v-btn text color="red" @click="closeTwitter">close</v-btn>
                        </template>
                    </v-card>
                </template>
            </template>
        </app-layout>
    </v-app>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout"
import { Tweet, Timeline } from "vue-tweet-embed"

export default {
    props: ["memberName", "cate", "players"],
    data() {
        return {
            loading: false,
            width: window.innerWidth,
            break: 900,
            Tweet: {},
            itemsPerPageArray: [5, 10, 15, 20, 30],
            search: "",
            filter: {},
            sortDesc: true,
            page: 1,
            itemsPerPage: 5,
            pageLength: 1,
            sortBy: "id",
            keys: ["id", "title", "date"],
            select: this.cate,
            playerOpen: false,
            twitterOpen: false,
            playID: "",
            playStart: "",
            playEnd: "",
            playIndex: 0,
            showTwitter: "",
            memberArray: [],
            currentMember: [],
        }
    },
    created() {
        this.pageLength = Math.ceil(Object.keys(this.players).length / this.itemsPerPage)
        this.memberArray = this.$page.props.setting.member.filter((value) => {
            if (value.name == this.memberName) {
                return true
            }
        })
        this.currentMember = this.memberArray[0]
    },
    components: {
        AppLayout,
        Tweet,
        Timeline,
    },
    computed: {
        numberOfPages() {
            return Math.ceil(this.items.length / this.itemsPerPage)
        },
        filteredKeys() {
            return this.keys.filter((key) => key !== "Name")
        },
    },
    methods: {
        updateItemsPerPage(number) {
            this.itemsPerPage = number
            this.pageLength = Math.floor(Object.keys(this.players).length / number)
        },
        openPlayer(VideoID, start, end, id, playerCate) {
            this.loading = true
            setTimeout(() => (this.loading = false), 2000)

            this.playerOpen = false
            this.twitterOpen = false
            this.playID = VideoID
            this.playStart = start
            this.playEnd = end
            this.playIndex = id

            if (playerCate == 4) {
                this.Tweet["url"] = "https://youtu.be/" + this.playID
                this.Tweet["title"] = "非公式" + this.currentMember.display + "DB 切り抜き No." + this.playIndex
                this.Tweet["hash"] = this.currentMember.display + "非公式DB," + this.currentMember.display
            } else {
                this.Tweet["url"] = "https://isevdb.sakura.ne.jp/" + this.currentMember.name + "/player/" + this.playIndex
                this.Tweet["title"] = "非公式" + this.currentMember.display + "DB No." + this.playIndex
                this.Tweet["hash"] = this.currentMember.display + "非公式DB," + this.currentMember.display
            }

            this.playerOpen = true
        },
        openTwitter(twitterId) {
            this.loading = true
            setTimeout(() => (this.loading = false), 2000)

            this.playerOpen = false
            this.showTwitter = twitterId
            this.twitterOpen = true
        },
        closePlayer() {
            this.playerOpen = false
        },
        closeTwitter() {
            this.twitterOpen = false
        },
        handleResize: function () {
            this.width = window.innerWidth
        },
    },
    mounted() {
        window.addEventListener("resize", this.handleResize)
    },
    beforeDestroy: function () {
        window.removeEventListener("resize", this.handleResize)
    },
}
</script>
