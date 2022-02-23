<template>
    <v-app>
        <div :id="'bg_' + currentMember.name"></div>
        <app-layout :style="styles">
            <template #header> {{ currentMember.display }} PlayerList </template>
            <template v-if="cate == 0">
                <h1 class="text-center" style="font-size: 4rem; font-family: 'Zen Maru Gothic', sans-serif; color: #eee">
                    {{ currentMember.display }}
                </h1>
                <v-row justify="center">
                    <v-col cols="auto">
                        <v-btn ref="button" large :color="currentMember.MainCol" class="my-5" @click="$vuetify.goTo('#main-table')">
                            スキップ▼
                        </v-btn>
                    </v-col>
                </v-row>
                <v-img
                    elevation="20"
                    class="mx-auto my-5 px-5"
                    max-height="500"
                    width="100%"
                    contain
                    :src="'/img/' + currentMember.name + '.jpg'"
                ></v-img>
                <v-row class="my-10" justify="start" align="start" no-gutters>
                    <v-col cols="12" class="px-5 py-10">
                        <p class="p-2 description">
                            <span>いせぶい{{ currentMember.display }}非公式DBは、</span><span>「異世界転生してVになりました。」</span
                            ><span>略して「いせぶい」に所属する</span><span>{{ currentMember.display }}の</span
                            ><span>可愛い声や面白いエピソード、</span><span>ファンが作った切り抜きを</span
                            ><span>共有するデータベース（DB)です。</span>
                        </p>
                        <p class="p-2 description">
                            <span> 本DBは</span><span>YouTube{{ currentMember.display }}チャンネル、</span
                            ><span>またはYouTubeにアップロードされた切り抜きの</span><span>動画リンクを直接共有します。 </span>
                        </p>
                        <p class="p-2 description">
                            <span> このサイトで再生されると</span><span>ダイレクトに本人のチャンネルにて</span><span>再生回数が計上されます。 </span>
                        </p>
                        <p class="p-2 description" style="color: #e73275 !important; font-weight: bold">
                            <span> 動画リンクの登録は</span><span>どなたでも可能です。</span><span>是非、「ここの声マジ最高！」とか</span
                            ><span>「このエピソード好き」や</span><span>作成した切り抜きを</span><span>登録してください。 </span>
                        </p>
                        <p class="p-2 description">
                            <span> 本DBが</span><span>みなさんの推し活や</span><span>彼女の活動の支えに</span><span>なりえることを願います。 </span>
                        </p>
                        <p class="p-2 description"><span> Twitter共有ボタンもあるので</span><span>是非ご利用ください。</span></p>
                        <p class="p-2 description">
                            <span>製作者の時間的都合、</span><span>というより根気的問題で</span><span>開発は超スローペースですが</span
                            ><span>今後機能拡充や</span><span>デザインの改善等</span><span>行います。</span>
                        </p>
                        <p class="p-2 description">
                            <span>不具合や</span><span>こんな機能あると嬉しい等</span><span>ございましたら、</span><span>管理人に</span
                            ><span>Twitterにて</span><span>メンションをいただけると幸いです。</span>
                        </p>
                    </v-col>
                </v-row>
                <v-row no-gutters justify="center" align="center">
                    <v-col cols="auto" class="mx-1 py-5">
                        <v-row no-gutters>
                            <v-col cols="12" class="text-center">
                                <p class="description">
                                    <span>{{ currentMember.display }}Twitter</span>
                                </p>
                            </v-col>
                            <v-col cols="12">
                                <v-card v-if="width >= 960" width="20vw" class="mx-auto my-5">
                                    <timeline
                                        :id="currentMember.TWaccount"
                                        :options="{ height: '600', width: '20vw' }"
                                        sourceType="profile"
                                    ></timeline>
                                </v-card>
                                <v-card v-if="width < 960" class="mx-20 my-5">
                                    <timeline
                                        :id="currentMember.TWaccount"
                                        :options="{ height: '600', width: '100%' }"
                                        sourceType="profile"
                                    ></timeline>
                                </v-card>
                            </v-col>
                        </v-row>
                    </v-col>
                    <v-col class="mb-5">
                        <v-row no-gutters>
                            <v-col cols="12" class="text-center description">
                                <p class="description">
                                    <span>{{ currentMember.display }}YouTubeチャンネル</span>
                                </p>
                                <p class="description"><span>新着動画</span></p>
                            </v-col>
                            <v-col cols="12">
                                <v-card class="p-2 mx-5">
                                    <v-responsive :aspect-ratio="16 / 9">
                                        <iframe
                                            width="100%"
                                            height="100%"
                                            :src="'https://www.youtube.com/embed/?list=' + currentMember.YTaccount"
                                            frameborder="0"
                                            allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                                            allowfullscreen
                                        ></iframe>
                                    </v-responsive>
                                </v-card>
                            </v-col>
                        </v-row>
                    </v-col>
                </v-row>
            </template>

            <v-card class="m-5" id="main-table" style="margin-bottom: 500px" elevation="0" outlined>
                <!--tab-->
                <v-tabs show-arrows fixed-tabs v-model="select">
                    <inertia-link
                        as="v-tab"
                        :href="'/' + memberName + '/latest'"
                        class="text-subtitle-2 fontCol"
                        style="font-family: 'Raleway', sans-serif !important"
                        >Latest</inertia-link
                    >

                    <template v-for="(category, index) in this.$page.props.setting.category">
                        <inertia-link
                            as="v-tab"
                            :key="index"
                            :href="'/' + memberName + '/' + category.id"
                            class="text-subtitle-2 fontCol"
                            style="font-family: 'Raleway', sans-serif !important"
                            >{{ category.title }}</inertia-link
                        >
                    </template>
                    <v-tab class="d-none">hidden</v-tab>
                </v-tabs>
                <!--table-cards-->
                <v-container fluid>
                    <v-row v-if="cate == 5">
                        <v-col v-for="(item, index) in players" :key="item.id" cols="auto">
                            <v-card :elevation="index + 1">
                                <template v-if="likes.includes(item.id) == true">
                                    <v-btn x-small icon color="yellow darken-1" @click="DisLike(item.id)">
                                        <v-icon>fas fa-bookmark</v-icon>
                                    </v-btn>
                                </template>
                                <template v-else>
                                    <v-btn x-small icon @click="addLike(item.id)">
                                        <v-icon>fas fa-bookmark</v-icon>
                                    </v-btn>
                                </template>
                                <Tweet :id="item.twitter"></Tweet>
                            </v-card>
                        </v-col>
                    </v-row>
                    <v-data-iterator
                        v-else
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
                            <template v-if="cate == 0">
                                <v-alert dense text type="success" class="mt-6 px-10"> 新着20件を表示しています。 </v-alert>
                            </template>
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
                                                            font-size: 0.8rem !important;
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
                                                                    item.cate_id,
                                                                    item.CreaterHN
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
                                                                    item.cate_id,
                                                                    item.CreaterHN
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
                                                            font-size: 0.8rem !important;
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
                <template v-if="width >= 960">
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
                <template v-if="width < 960">
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
import AppLayout from "@/Layouts/AppLayout"
import { Tweet, Timeline } from "vue-tweet-embed"
import moment from "moment"

export default {
    filters: {
        moment: function (date) {
            return moment(date).format("YYYY/MM/DD")
        },
    },
    props: ["memberName", "cate", "players", "likesObj"],
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
            select: "",
            playerOpen: false,
            twitterOpen: false,
            playID: "",
            playStart: "",
            playEnd: "",
            playerId: 0,
            playIndex: 0,
            playTitle: "",
            playCreater: "",
            showTwitter: "",
            playCate: 0,
            memberArray: [],
            currentMember: [],
            timer: undefined,
        }
    },
    created() {
        this.select = this.cate
        this.pageLength = Math.ceil(Object.keys(this.players).length / this.itemsPerPage)
        this.memberArray = this.$page.props.setting.member.filter((value) => {
            if (value.name == this.memberName) {
                return true
            }
        })
        this.currentMember = this.memberArray[0]
        if (this.$page.props.user) {
            this.likes = this.likesObj.map((item) => item.player_id)
        }
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
        styles() {
            return {
                "--BtnCol": this.currentMember.BtnCol,
                "--MainCol": this.currentMember.MainCol,
                "--NavCol": this.currentMember.NavCol,
            }
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
        openPlayer(index, title, VideoID, start, end, id, playerCate, playerCreater) {
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
            this.playCreater = playerCreater

            if (playerCate == 4) {
                this.Tweet["url"] = "https://www.youtube.com/watch?v=" + this.playID
                this.Tweet["title"] = "非公式" + this.currentMember.display + "DB 切り抜き No." + this.playerId + " by " + this.playCreater
                this.Tweet["hash"] = this.currentMember.display + "非公式DB," + this.currentMember.display
            } else {
                this.Tweet["url"] =
                    "https://isevdb.sakura.ne.jp/" +
                    this.currentMember.name +
                    "/player/" +
                    this.playerId +
                    "　https://www.youtube.com/watch?v=" +
                    this.playID +
                    "?t=" +
                    this.playStart
                this.Tweet["title"] = "非公式" + this.currentMember.display + "DB No." + this.playerId
                this.Tweet["hash"] = this.currentMember.display + "非公式DB," + this.currentMember.display

                this.TweetRow["url"] = "https://www.youtube.com/watch?v=" + this.playID
                this.TweetRow["hash"] = this.currentMember.display
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
                        self.players[nextId].cate_id,
                        self.players[nextId].CreaterHN
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
                self.players[nextId].cate_id,
                self.players[nextId].CreaterHN
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
                self.players[nextId].cate_id,
                self.players[nextId].CreaterHN
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
    background: var(--MainCol) !important;
    border-color: var(--BtnCol) !important;
    color: var(--BtnCol) !important;
}
.footCol {
    background: var(--NavCol) !important;
}
.v-list-item {
    color: var(--BtnCol) !important;
}
.v-divider {
    border-color: var(--BtnCol) !important;
    opacity: 0.2;
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
