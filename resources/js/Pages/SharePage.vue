<template>
    <v-app>
        <div :id="'bg_' + currentMember.name"></div>
        <app-layout class="pb-5" :style="styles">
            <template #title> 非公式{{ currentMember.display }}DB </template>
            <template #header> {{ currentMember.display }} PlayerList </template>
            <v-card class="px-5 py-10">
                <v-card v-if="width > this.break" style="max-width: 70vw" class="mx-auto p-3">
                    <v-responsive :aspect-ratio="16 / 9">
                        <youtube
                            class="mx-auto"
                            width="100%"
                            height="100%"
                            :video-id="player.VideoID"
                            :playerVars="playerVars"
                            ref="youtube"
                            @playing="playing"
                            @paused="paused"
                            @ended="ended"
                        ></youtube>
                        <v-overlay class="text-center" :absolute="absolute" :value="overlay">
                            <p class="text-h5">次の動画まで{{ this.countDisplay }}秒</p>
                            <v-row no-gutters justify="center" align="center">
                                <v-col cols="auto" class="mx-2">
                                    <v-btn @click="redoPlayer" color="red" rounded><v-icon>fas fa-redo</v-icon>　REPEAT</v-btn>
                                </v-col>
                                <v-col cols="auto" class="mx-2">
                                    <v-btn @click="continuePlayer" color="red" rounded>CONTINUE　<v-icon>fas fa-play</v-icon></v-btn>
                                </v-col>
                                <v-col cols="auto" class="mx-2">
                                    <v-btn @click="nextPlayer" color="red" rounded>NEXT　<v-icon>fas fa-angle-double-right</v-icon></v-btn>
                                </v-col>
                            </v-row>
                        </v-overlay>
                    </v-responsive>
                </v-card>
                <v-card v-if="width <= this.break" style="max-width: 95vw" class="mx-auto p-3">
                    <v-responsive :aspect-ratio="16 / 9">
                        <youtube
                            class="mx-auto"
                            width="100%"
                            height="100%"
                            :video-id="player.VideoID"
                            :playerVars="playerVars"
                            ref="youtube"
                            @playing="playing"
                            @paused="paused"
                            @ended="ended"
                        ></youtube>
                        <v-overlay class="text-center" :absolute="absolute" :value="overlay">
                            <p class="text-h5">次の動画まで{{ this.countDisplay }}秒</p>
                            <v-row no-gutters justify="center" align="center">
                                <v-col cols="auto" class="mx-2">
                                    <v-btn @click="redoPlayer" color="red" rounded><v-icon>fas fa-redo</v-icon>　REPEAT</v-btn>
                                </v-col>
                                <v-col cols="auto" class="mx-2">
                                    <v-btn @click="continuePlayer" color="red" rounded>CONTINUE　<v-icon>fas fa-play</v-icon></v-btn>
                                </v-col>
                                <v-col cols="auto" class="mx-2">
                                    <v-btn @click="nextPlayer" color="red" rounded>NEXT　<v-icon>fas fa-angle-double-right</v-icon></v-btn>
                                </v-col>
                            </v-row>
                        </v-overlay>
                    </v-responsive>
                </v-card>
                <v-row class="mt-1 mb-0" justify="center" align="center">
                    <v-col cols="auto">
                        <h2 class="text-center my-3" style="font-size: 1.5rem; font-family: 'Zen Maru Gothic', sans-serif">"{{ player.title }}"</h2>
                    </v-col>
                </v-row>
                <v-divider></v-divider>
                <v-row no-gutters justify="center" align="center" class="my-3">
                    <v-col cols="auto" class="mx-3">
                        <v-btn @click="redoPlayer" icon color="red">
                            <v-icon>fas fa-redo</v-icon>
                        </v-btn>
                    </v-col>
                    <v-col cols="auto" class="mx-3">
                        <v-btn @click="pauseVideo" v-if="playStatus" icon color="red">
                            <v-icon>fas fa-pause fa-fw</v-icon>
                        </v-btn>
                        <v-btn @click="playVideo" v-else icon color="red">
                            <v-icon>fas fa-play fa-fw</v-icon>
                        </v-btn>
                    </v-col>
                    <v-col cols="auto" class="mx-3">
                        <v-btn @click="nextPlayer" icon color="red">
                            <v-icon>fas fa-angle-double-right</v-icon>
                        </v-btn>
                    </v-col>
                </v-row>
                <v-alert v-if="this.player.end == 0" border="left" type="warning" outlined prominent class="my-3">
                    <p>このデータは終了時刻が設定されていません。</p>
                    <p>
                        次の動画に進むには
                        <v-btn @click="nextPlayer" icon color="warning" outlined>
                            <v-icon>fas fa-angle-double-right</v-icon>
                        </v-btn>
                        をクリック/タップしてください。
                    </p>
                </v-alert>
                <v-divider class="mb-3"></v-divider>

                <template>
                    <div class="text-center">
                        <v-dialog v-model="TWdialog" persistent max-width="500">
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn color="#1DA1F2" style="color: #fff" block x-large v-bind="attrs" v-on="on">
                                    <v-icon>fas fa-share-square</v-icon>　Tweet
                                </v-btn>
                            </template>

                            <v-card>
                                <v-card-title style="color: #1da1f2"> Tweet Setting </v-card-title>

                                <v-switch class="ml-20" inset v-model="TWtitle" @change="setTWtitle" label="データタイトル"></v-switch>
                                <v-switch class="ml-20" inset v-model="TWurl" @change="setTWurl" label="非公式DBのURL"></v-switch>
                                <v-switch class="ml-20" inset v-model="TWyt" @change="setTWurl" label="元動画のURL"></v-switch>

                                <v-divider></v-divider>
                                <v-card-actions>
                                    <v-btn color="#1DA1F2" text @click="TWdialog = false"> Back </v-btn>
                                    <v-spacer></v-spacer>
                                    <ShareNetwork
                                        network="twitter"
                                        :url="Tweet.url"
                                        :title="Tweet.title"
                                        :hashtags="Tweet.hash"
                                        :class="'text-decoration-none'"
                                        @click="TWdialog = false"
                                    >
                                        <v-btn color="#1DA1F2" style="color: #fff"> Tweet </v-btn>
                                    </ShareNetwork>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                    </div>
                </template>

                <v-row justify="center" class="my-5 text-center">
                    <v-col cols="12">
                        <v-btn :color="currentMember.MainCol" :href="'/' + currentMember.name + '/latest'">
                            非公式{{ currentMember.display }}DBへ
                        </v-btn>
                    </v-col>
                    <v-col cols="12" class="mt-5">
                        <v-btn large as="v-btn" color="cyan lighten-3" href="/"> 非公式いせぶいDBトップへ </v-btn>
                    </v-col>
                </v-row>

                <template>
                    <v-divider></v-divider>
                    <v-card-actions>
                        <v-btn block x-large text @click="showRelated = !showRelated">
                            関連動画 <v-icon>{{ showRelated ? "mdi-chevron-up" : "mdi-chevron-down" }}</v-icon>
                        </v-btn>
                    </v-card-actions>

                    <v-expand-transition>
                        <div v-show="showRelated">
                            <v-data-iterator :items="related" hide-default-footer>
                                <template v-slot:default="props">
                                    <v-row dense>
                                        <v-col v-for="(item, index) in props.items" :key="'related_item' + index" cols="12">
                                            <v-card :elevation="index + 1">
                                                <v-row class="mt-1 mb-0" justify="start" align="center">
                                                    <v-col cols="auto" class="ml-5">
                                                        <h2
                                                            class="text-subtitle-2 text-md-h6"
                                                            style="
                                                                font-family: 'Raleway', 'Zen Maru Gothic', sans-serif !important;
                                                                color: #555 !important;
                                                            "
                                                        >
                                                            {{ item.title }}
                                                        </h2>
                                                    </v-col>
                                                </v-row>
                                                <template>
                                                    <v-row>
                                                        <v-col cols="auto" class="ml-10 mr-auto">
                                                            <p>{{ item.date | moment }}</p>
                                                        </v-col>
                                                        <template v-if="item.status == 0">
                                                            <v-col cols="auto" class="ml-auto mr-10 mb-5">
                                                                <v-btn
                                                                    color="#DA1725"
                                                                    style="font-family: 'Raleway', sans-serif !important; color: #eee !important"
                                                                    :href="'/' + currentMember.name + '/player/' + item.id"
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
                                                                    :href="'/' + currentMember.name + '/player/' + item.id"
                                                                >
                                                                    <v-icon>fab fa-youtube</v-icon>　Member Only
                                                                </v-btn>
                                                            </v-col>
                                                        </template>
                                                    </v-row>
                                                </template>
                                            </v-card>
                                        </v-col>
                                    </v-row>
                                </template>
                            </v-data-iterator>
                        </div>
                    </v-expand-transition>
                </template>
            </v-card>
        </app-layout>
    </v-app>
</template>

<script>
import AppLayout from "@/Layouts/ShareLayout"
import moment from "moment"

export default {
    filters: {
        moment: function (date) {
            return moment(date).format("YYYY/MM/DD")
        },
    },
    props: ["currentMember", "player", "id", "related"],
    data() {
        return {
            TWtitle: true,
            TWurl: true,
            TWyt: false,
            TWdialog: false,
            playerVars: {
                autoplay: 1,
                controls: 1,
                start: this.player.start,
                iv_load_policy: 3,
            },
            width: window.innerWidth,
            break: 960,
            Tweet: {},
            showRelated: true,
            playStatus: false,
            timer: undefined,
            count: undefined,
            NowTime: undefined,
            countDisplay: 10,
            nextIndex: 0,
            listLength: 0,
            absolute: true,
            overlay: false,
            seek: undefined,
        }
    },
    created() {
        this.Tweet["url"] = "https://isevdb.sakura.ne.jp/" + this.currentMember.name + "/share/" + this.player.id
        this.Tweet["title"] = "非公式" + this.currentMember.display + "DB No." + this.player.id + "　”" + this.player.title + "”"
        this.Tweet["hash"] = this.currentMember.display + "非公式DB," + this.currentMember.display
    },
    components: {
        AppLayout,
    },
    methods: {
        handleResize: function () {
            this.width = window.innerWidth
        },
        playVideo() {
            this.YTplayer.playVideo()
            this.playStatus = true
        },
        pauseVideo() {
            this.YTplayer.pauseVideo()
            this.playStatus = false
        },
        playing() {
            clearTimeout(this.count)
            clearTimeout(this.timer)
            this.playStatus = true
            this.getTime()
        },
        paused() {
            this.playStatus = false
            clearTimeout(this.timer)
        },
        ended() {
            this.playStatus = false
            clearTimeout(this.timer)
            this.countDown()
        },
        redoPlayer() {
            clearTimeout(this.count)
            this.count = undefined
            clearTimeout(this.timer)
            this.timer = undefined
            this.overlay = false
            this.countDisplay = 10
            this.YTplayer.seekTo(this.player.start, true)
            this.NowTime = this.player.start
        },
        continuePlayer() {
            this.overlay = false
            clearTimeout(this.count)
        },
        getTime() {
            if (this.player.end != 0) {
                this.timer = setTimeout(this.getTime, 1000)
                if (Number(this.NowTime) > Number(this.player.end)) {
                    clearTimeout(this.timer)
                    this.overlay = true
                    this.countDown()
                }
                this.YTplayer.getCurrentTime().then((time) => {
                    this.NowTime = time
                })
            }
        },
        countDown() {
            this.count = setTimeout(this.countDown, 1000)
            this.countDisplay--
            if (this.countDisplay == 0) {
                clearTimeout(this.count)
                this.nextPlayer()
            }
        },
        nextPlayer() {
            if (this.list_type == null) {
                location.href = "/" + this.currentMember.name + "/player/" + this.related[0].id
            } else {
                this.findNext()
                if (Number(this.nextIndex) + Number(this.list_id) + 1 > this.listLength) {
                    let selectIndex = Number(this.nextIndex) + Number(this.list_id) + 1 - this.listLength
                    location.href =
                        "/" +
                        this.currentMember.name +
                        "/player/" +
                        this.list[this.nextIndex].id +
                        "?list=" +
                        this.list_type +
                        "&index=" +
                        selectIndex
                } else {
                    let selectIndex = Number(this.nextIndex) + Number(this.list_id) + 1
                    location.href =
                        "/" +
                        this.currentMember.name +
                        "/player/" +
                        this.list[this.nextIndex].id +
                        "?list=" +
                        this.list_type +
                        "&index=" +
                        selectIndex
                }
            }
        },
        findNext() {
            while (this.list[this.nextIndex].twitter != null || this.list[this.nextIndex].status == 2 || this.list[this.nextIndex].status == 3) {
                this.nextIndex++
                if (Number(this.nextIndex) > Number(this.listLength)) {
                    this.nextIndex = Number(this.nextIndex) - Number(this.listLength)
                }
            }
        },
        selectList(index) {
            if (Number(index) + Number(this.list_id) + 1 > this.listLength) {
                let selectIndex = Number(index) + Number(this.list_id) + 1 - this.listLength
                location.href = "/list/player/" + this.list[index].id + "?list=" + this.list_type + "&index=" + selectIndex
            } else {
                let selectIndex = Number(index) + Number(this.list_id) + 1
                location.href = "/list/player/" + this.list[index].id + "?list=" + this.list_type + "&index=" + selectIndex
            }
        },
        setTWtitle() {
            if (this.TWtitle == false) {
                this.Tweet["title"] = ""
            } else {
                this.Tweet["title"] = "非公式" + this.currentMember.display + "DB No." + this.player.id + "　”" + this.player.title + "”"
            }
        },
        setTWurl() {
            if (this.TWurl == false) {
                if (this.TWyt == false) {
                    this.Tweet["url"] = ""
                } else {
                    this.Tweet["url"] = "https://youtu.be/" + this.player.VideoID
                }
            } else {
                if (this.TWyt == false) {
                    this.Tweet["url"] = "https://isevdb.sakura.ne.jp/" + this.currentMember.name + "/share/" + this.player.id
                } else {
                    this.Tweet["url"] =
                        "https://isevdb.sakura.ne.jp/" +
                        this.currentMember.name +
                        "/share/" +
                        this.player.id +
                        "　https://youtu.be/" +
                        this.player.VideoID
                }
            }
        },
    },
    computed: {
        styles() {
            return {
                "--BtnCol": this.currentMember.BtnCol,
                "--MainCol": this.currentMember.MainCol,
                "--NavCol": this.currentMember.NavCol,
            }
        },
        YTplayer() {
            return this.$refs.youtube.player
        },
    },
    mounted: function () {
        window.addEventListener("resize", this.handleResize)
    },
    beforeDestroy: function () {
        window.removeEventListener("resize", this.handleResize)
        clearTimeout(this.timer)
        clearTimeout(this.count)
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
