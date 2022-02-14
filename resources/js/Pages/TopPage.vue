<template>
    <v-app>
        <app-layout>
            <template #header> iseVDB - Top </template>
            <v-card class="mx-5 mt-5" style="margin-bottom: 500px" elevation="0" outlined>
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
                                <v-col class="pl-5 ml-0 mr-auto" cols="12" sm="12" md="auto">
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
                            <v-alert dense text type="success" class="mt-6 px-10"> 新着20件を表示しています。 </v-alert>
                        </template>
                        <template v-slot:default="props">
                            <v-row dense>
                                <v-col v-for="(item, index) in props.items" :key="item.id" cols="12">
                                    <v-card :elevation="index + 1">
                                        <v-row class="mt-1 mb-0 px-3" justify="start" align="center">
                                            <v-col cols="auto" class="m-0 p-0">
                                                <template v-if="$page.props.user">
                                                    <template v-if="likes.includes(item.id) == true">
                                                        <v-btn class="m-0 p-0" x-small icon color="yellow darken-1" @click="DisLike(item.id)">
                                                            <v-icon>fas fa-bookmark</v-icon>
                                                        </v-btn>
                                                    </template>
                                                    <template v-else>
                                                        <v-btn class="m-0 p-0" x-small icon @click="addLike(item.id)">
                                                            <v-icon>fas fa-bookmark</v-icon>
                                                        </v-btn>
                                                    </template>
                                                </template>
                                                <template v-else>
                                                    <v-tooltip top color="error">
                                                        <template v-slot:activator="{ on, attrs }">
                                                            <v-list-item link v-bind="attrs" v-on="on">
                                                                <v-btn x-small icon>
                                                                    <v-icon>fas fa-bookmark</v-icon>
                                                                </v-btn>
                                                            </v-list-item>
                                                        </template>
                                                        <span>ログインユーザーのみ</span>
                                                    </v-tooltip>
                                                </template>
                                            </v-col>
                                            <v-col>
                                                <h2
                                                    class="text-subtitle-2 text-md-h6"
                                                    style="font-family: 'Raleway', 'Zen Maru Gothic', sans-serif !important; color: #555 !important"
                                                >
                                                    {{ item.title }}
                                                </h2>
                                            </v-col>
                                        </v-row>
                                        <template v-if="item.twitter == null">
                                            <v-row>
                                                <v-col cols="auto" class="ml-10 mr-auto">
                                                    <p
                                                        style="
                                                            font-family: 'Raleway', 'Zen Maru Gothic', sans-serif !important;
                                                            color: #555 !important;
                                                        "
                                                    >
                                                        {{ item.date | moment }}
                                                    </p>
                                                </v-col>
                                                <template v-if="item.status == 0">
                                                    <v-col cols="auto" class="ml-auto mr-10 mb-5">
                                                        <v-btn
                                                            color="#DA1725"
                                                            style="font-family: 'Raleway', sans-serif !important; color: #eee !important"
                                                            @click="
                                                                openPlayer(
                                                                    index,
                                                                    item.title,
                                                                    item.VideoID,
                                                                    item.start,
                                                                    item.end,
                                                                    item.id,
                                                                    item.cate_id
                                                                )
                                                            "
                                                        >
                                                            <v-icon>fab fa-youtube</v-icon>　Play
                                                        </v-btn>
                                                    </v-col>
                                                </template>
                                                <template v-if="item.status == 1">
                                                    <v-col cols="auto" class="ml-auto mr-10 mb-5">
                                                        <v-btn
                                                            color="#2BA640"
                                                            style="font-family: 'Raleway', sans-serif !important; color: #eee !important"
                                                            @click="
                                                                openPlayer(
                                                                    index,
                                                                    item.title,
                                                                    item.VideoID,
                                                                    item.start,
                                                                    item.end,
                                                                    item.id,
                                                                    item.cate_id
                                                                )
                                                            "
                                                        >
                                                            <v-icon>fab fa-youtube</v-icon>　Member Only
                                                        </v-btn>
                                                    </v-col>
                                                </template>
                                                <template v-if="item.status == 2">
                                                    <v-col cols="auto" class="ml-auto mr-10 mb-5">
                                                        <v-btn disabled style="font-family: 'Raleway', sans-serif !important; color: #000 !important">
                                                            <v-icon>fab fa-youtube</v-icon>　Private
                                                        </v-btn>
                                                    </v-col>
                                                </template>
                                            </v-row>
                                        </template>
                                        <template v-else>
                                            <v-row>
                                                <v-col cols="auto" class="ml-10 mr-auto">
                                                    <p
                                                        style="
                                                            font-family: 'Raleway', 'Zen Maru Gothic', sans-serif !important;
                                                            color: #555 !important;
                                                        "
                                                    >
                                                        {{ item.date | moment }}（DB追加日）
                                                    </p>
                                                </v-col>
                                                <v-col cols="auto" class="ml-auto mr-10 mb-5">
                                                    <template v-if="item.cate_id == 5">
                                                        <v-btn
                                                            color="#1DA1F2"
                                                            style="font-family: 'Raleway', sans-serif !important; color: #eee !important"
                                                            @click="openTwitter(item.twitter)"
                                                        >
                                                            <v-icon>fab fa-twitter-square</v-icon>　Image
                                                        </v-btn>
                                                    </template>
                                                    <template v-else>
                                                        <v-btn
                                                            color="#1DA1F2"
                                                            style="font-family: 'Raleway', sans-serif !important; color: #eee !important"
                                                            @click="openTwitter(item.twitter)"
                                                        >
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
                        class="pb-3"
                        style="position: fixed; right: 30px; bottom: 30px; margin: 0; z-index: 10"
                    >
                        <template slot="progress">
                            <v-progress-linear color="pink accent-3" height="5" indeterminate></v-progress-linear>
                        </template>

                        <v-row>
                            <v-col cols="auto" class="ml-1 mr-auto">
                                <inertia-link text as="v-btn" :href="'/' + currentMember.name + '/player/' + playerId">詳細ページへ </inertia-link>
                            </v-col>
                            <v-col cols="auto" class="ml-auto mr-0">
                                <v-btn v-if="playerOpen" color="red" text @click="closePlayer">close</v-btn>
                            </v-col>
                        </v-row>
                        <v-divider class="py-1"></v-divider>
                        <p class="text-center px-5">“{{ playTitle }}”</p>
                        <v-responsive v-if="playerOpen" :aspect-ratio="16 / 9" class="m-1">
                            <youtube
                                v-if="playerOpen"
                                :fitParent="true"
                                class="mx-auto"
                                :video-id="playID"
                                :playerVars="playerVars"
                                ref="youtube"
                                @playing="playing"
                                @ended="ended"
                            ></youtube>
                        </v-responsive>
                        <v-row class="m-0 p-0" justify="center" v-if="playEnd == 0">
                            <v-col cols="auto" class="mt-1 mx-auto">
                                <v-btn v-show="playerOpen" color="#DA1725" style="color: #fff" @click="RandomNext()"
                                    ><v-icon>fab fa-youtube</v-icon>　次の動画へ（ランダム）</v-btn
                                >
                            </v-col>
                        </v-row>
                        <v-row class="m-0 p-0">
                            <v-col cols="8" class="mt-1 mx-auto">
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
                        <v-row class="m-0 p-0" v-if="playCate != 4">
                            <v-col cols="8" class="mx-auto">
                                <ShareNetwork
                                    v-if="playerOpen"
                                    network="twitter"
                                    :url="TweetRow.url"
                                    title=""
                                    :hashtags="TweetRow.hash"
                                    :class="'text-decoration-none'"
                                >
                                    <v-btn v-show="playerOpen" color="#1DA1F2" style="color: #fff" block
                                        ><v-icon>fas fa-share-square</v-icon>　元動画をツイート</v-btn
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
                        class="pb-3"
                        style="position: fixed; right: 0; bottom: 80px; margin: 0; z-index: 10"
                    >
                        <template slot="progress">
                            <v-progress-linear color="pink accent-3" height="5" indeterminate></v-progress-linear>
                        </template>

                        <v-row>
                            <v-col cols="auto" class="p-0 mt-0 ml-1 mr-auto">
                                <inertia-link text as="v-btn" :href="'/' + currentMember.name + '/player/' + playerId">詳細ページへ </inertia-link>
                            </v-col>
                            <v-col cols="auto" class="ml-auto mr-0">
                                <v-btn v-if="playerOpen" color="red" text @click="closePlayer">close</v-btn>
                            </v-col>
                        </v-row>
                        <v-divider class="py-1"></v-divider>
                        <p class="text-center px-5">“{{ playTitle }}”</p>
                        <v-responsive v-if="playerOpen" :aspect-ratio="16 / 9" class="m-1">
                            <youtube
                                v-if="playerOpen"
                                :fitParent="true"
                                class="mx-auto"
                                :video-id="playID"
                                :playerVars="playerVars"
                                ref="youtube"
                                @playing="playing"
                                @ended="ended"
                            ></youtube>
                        </v-responsive>
                        <v-row class="m-0 p-0" justify="center">
                            <v-col cols="auto" class="mt-1 mx-auto">
                                <v-btn v-show="playerOpen" color="#DA1725" style="color: #fff" @click="RandomNext()"
                                    ><v-icon>fab fa-youtube</v-icon>　次の動画へ（ランダム）</v-btn
                                >
                            </v-col>
                        </v-row>
                        <v-row class="m-0 p-0">
                            <v-col cols="8" class="mt-1 mx-auto">
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
                        <v-row class="m-0 p-0" v-if="playCate != 4">
                            <v-col cols="8" class="mx-auto">
                                <ShareNetwork
                                    v-if="playerOpen"
                                    network="twitter"
                                    :url="TweetRow.url"
                                    title=""
                                    :hashtags="TweetRow.hash"
                                    :class="'text-decoration-none'"
                                >
                                    <v-btn v-show="playerOpen" color="#1DA1F2" style="color: #fff" block
                                        ><v-icon>fas fa-share-square</v-icon>　元動画をツイート</v-btn
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
import AppLayout from "@/Layouts/TopLayout"
import { Tweet } from "vue-tweet-embed"
import moment from "moment"

export default {
    filters: {
        moment: function (date) {
            return moment(date).format("YYYY/MM/DD")
        },
    },
    props: ["players", "likesObj"],
    data() {
        return {
            playerVars: {
                start: 0,
                autoplay: 1,
                controls: 1,
                iv_load_policy: 3,
            },
            likes: [],
            loading: false,
            width: window.innerWidth,
            break: 900,
            Tweet: {},
            TweetRow: {},
            itemsPerPageArray: [5, 10, 15, 20, 30],
            search: "",
            filter: {},
            sortDesc: true,
            page: 1,
            itemsPerPage: 5,
            pageLength: 1,
            sortBy: "id",
            keys: ["id", "title", "date"],
            playerOpen: false,
            twitterOpen: false,
            playID: "",
            playStart: "",
            playEnd: "",
            playIndex: 0,
            playTitle: "",
            showTwitter: "",
            memberArray: [],
            currentMember: [],
            timer: undefined,
        }
    },
    created() {
        this.pageLength = Math.ceil(Object.keys(this.players).length / this.itemsPerPage)
        if (this.likesObj != null) {
            this.likes = this.likesObj.map((item) => item.player_id)
        }
    },
    components: {
        AppLayout,
        Tweet,
    },
    computed: {
        numberOfPages() {
            return Math.ceil(this.items.length / this.itemsPerPage)
        },
        filteredKeys() {
            return this.keys.filter((key) => key !== "Name")
        },
        player() {
            return this.$refs.youtube.player
        },
    },
    methods: {
        updateItemsPerPage(number) {
            this.itemsPerPage = number
            this.pageLength = Math.floor(Object.keys(this.players).length / number)
        },
        openPlayer(index, title, VideoID, start, end, id, playerCate) {
            clearTimeout(this.timer)
            this.loading = true
            setTimeout(() => (this.loading = false), 2000)

            this.playerOpen = false
            this.twitterOpen = false
            this.playID = VideoID
            this.playStart = start
            this.playEnd = end
            this.playerId = id
            this.playIndex = index
            this.playTitle = title
            this.playCate = playerCate
            this.playerVars.start = start

            if (playerCate == 4) {
                this.Tweet["url"] = "https://www.youtube.com/watch?v=" + this.playID
                this.Tweet["title"] = "非公式" + this.currentMember.display + "DB 切り抜き No." + this.playerId
                this.Tweet["hash"] = ""
            } else {
                this.Tweet["url"] = "https://isevdb.sakura.ne.jp/top/player/" + this.playerId
                this.Tweet["title"] = "非公式" + this.currentMember.display + "DB No." + this.playerId
                this.Tweet["hash"] = ""

                this.TweetRow["url"] = "https://www.youtube.com/watch?v=" + this.playID
                this.TweetRow["hash"] = ""
            }

            this.$nextTick(() => (this.playerOpen = true))
        },
        openTwitter(twitterId) {
            this.loading = true
            setTimeout(() => (this.loading = false), 2000)

            this.playerOpen = false
            this.showTwitter = twitterId
            this.twitterOpen = true
        },
        closePlayer() {
            clearTimeout(this.timer)
            this.playerOpen = false
        },
        closeTwitter() {
            this.twitterOpen = false
        },
        handleResize: function () {
            this.width = window.innerWidth
        },
        addLike(id) {
            let self = this
            axios({
                method: "post",
                url: "/api/add/" + id + "/like",
                dataType: "json",
                data: {
                    player_id: id,
                    user_id: this.$page.props.user.id,
                    created_at: new Date(),
                    updated_at: new Date(),
                },
            })
                .then((response) => {
                    console.log(response)
                    self.likes.push(id)
                })
                .catch((error) => {
                    console.log(error)
                })
        },
        DisLike(id) {
            let self = this
            axios({
                method: "post",
                url: "/api/delete/" + id + "/dislike",
                dataType: "json",
                data: {
                    player_id: id,
                    user_id: this.$page.props.user.id,
                },
            })
                .then((response) => {
                    console.log(response)
                    self.likes.splice(self.likes.indexOf(id), 1)
                })
                .catch((error) => {
                    console.log(error)
                })
        },
        playing() {
            if (this.playEnd > 0) {
                let self = this
                let playLength = Number(this.playEnd) - Number(this.playStart)
                let nowId = Number(this.playIndex)
                let nextId = 0
                let whileTime = 0
                let nextPlayer = function () {
                    do {
                        nextId = Math.floor(Math.random() * Number(Object.keys(self.players).length))
                        whileTime++
                    } while (nextId == nowId && self.players[nextId].twitter != null && whileTime < 10)
                    self.openPlayer(
                        nextId,
                        self.players[nextId].title,
                        self.players[nextId].VideoID,
                        self.players[nextId].start,
                        self.players[nextId].end,
                        self.players[nextId].id,
                        self.players[nextId].cate_id
                    )
                }
                this.timer = setTimeout(nextPlayer, Number(playLength * 1000))
            }
        },
        ended() {
            let self = this
            let nowId = Number(this.playIndex)
            let nextId = 0
            let whileTime = 0
            console.log("videoEnded")
            do {
                nextId = Math.floor(Math.random() * Number(Object.keys(self.players).length))
                whileTime++
            } while (nextId == nowId && self.players[nextId].twitter != null && whileTime < 10)
            self.openPlayer(
                nextId,
                self.players[nextId].title,
                self.players[nextId].VideoID,
                self.players[nextId].start,
                self.players[nextId].end,
                self.players[nextId].id,
                self.players[nextId].cate_id
            )
        },
        RandomNext() {
            let self = this
            let nowId = Number(this.playIndex)
            let nextId = 0
            let whileTime = 0
            do {
                nextId = Math.floor(Math.random() * Number(Object.keys(self.players).length))
                whileTime++
            } while (nextId == nowId && self.players[nextId].twitter != null && whileTime < 10)
            self.openPlayer(
                nextId,
                self.players[nextId].title,
                self.players[nextId].VideoID,
                self.players[nextId].start,
                self.players[nextId].end,
                self.players[nextId].id,
                self.players[nextId].cate_id
            )
        },
    },
    mounted() {
        window.addEventListener("resize", this.handleResize)
    },
    beforeDestroy: function () {
        window.removeEventListener("resize", this.handleResize)
        clearTimeout(this.timer)
    },
}
</script>

<style lang="scss">
@import "/css/bgset.css";
.memberCol {
    background: #80deea !important;
}
.description {
    font-size: 1rem;
    font-family: "Zen Maru Gothic", sans-serif;
    color: #111;
    line-height: 1.4em;
}
.description > span {
    position: relative;
    display: inline-block;
}
.description > span::before {
    content: "";
    position: absolute;
    bottom: -5px;
    left: -5px;
    top: -5px;
    right: -5px;
    background-color: #eee;
    z-index: -1;
}
</style>
