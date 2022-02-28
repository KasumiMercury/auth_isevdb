<template>
    <v-app>
        <div :id="'bg_' + currentMember.name"></div>
        <app-layout class="pb-5" :style="styles">
            <template #title> 非公式{{ currentMember.display }}DB </template>
            <template #header> {{ currentMember.display }} PlayerList </template>
            <v-card class="px-5 py-10">
                <h2 class="text-center my-3" style="font-size: 1.5rem; font-family: 'Zen Maru Gothic', sans-serif">{{ player.title }}</h2>
                <v-card v-if="width > this.break" style="max-width: 80vw" class="mx-auto p-3">
                    <v-responsive v-show="show" :aspect-ratio="16 / 9">
                        <iframe
                            width="100%"
                            height="100%"
                            :src="'https://www.youtube.com/embed/' + playID + '?start=' + playStart + '&end=' + playEnd + '&rel=0&loop=1'"
                            title="YouTube video player"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen
                        ></iframe>
                    </v-responsive>
                </v-card>
                <v-card v-if="width <= this.break" style="max-width: 95vw" class="mx-auto p-3">
                    <v-responsive v-show="show" :aspect-ratio="16 / 9">
                        <iframe
                            width="100%"
                            height="100%"
                            :src="'https://www.youtube.com/embed/' + playID + '?start=' + playStart + '&end=' + playEnd + '&rel=0&loop=1'"
                            title="YouTube video player"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen
                        ></iframe>
                    </v-responsive>
                </v-card>
                <ShareNetwork
                    v-if="show"
                    network="twitter"
                    :url="Tweet.url"
                    :title="Tweet.title"
                    :hashtags="Tweet.hash"
                    :class="'text-decoration-none'"
                >
                    <v-btn v-show="show" class="my-10" color="#1DA1F2" style="color: #fff" block x-large>
                        <v-icon>fas fa-share-square</v-icon>　Tweet
                    </v-btn>
                </ShareNetwork>
                <v-row justify="center" class="text-center">
                    <v-col cols="12">
                        <inertia-link large as="v-btn" :color="currentMember.MainCol" :href="'/' + currentMember.name + '/latest'">
                            非公式{{ currentMember.display }}DBへ
                        </inertia-link>
                    </v-col>
                    <v-col cols="12" class="mt-5">
                        <inertia-link large as="v-btn" color="cyan lighten-3" href="/"> 非公式いせぶいDBトップへ </inertia-link>
                    </v-col>
                </v-row>
            </v-card>
        </app-layout>
    </v-app>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout"

export default {
    props: ["currentMember", "player", "id"],
    data() {
        return {
            width: window.innerWidth,
            break: 960,
            Tweet: {},
            playID: "",
            playStart: "",
            playEnd: "",
            playIndex: 0,
            memberArray: [],
            show: false,
        }
    },
    created() {
        this.playID = this.player.VideoID
        this.playStart = this.player.start
        this.playEnd = this.player.end
        this.playIndex = this.id
        this.Tweet["url"] = "https://isevdb.sakura.ne.jp/" + this.currentMember.name + "/share/" + this.playIndex
        this.Tweet["title"] = "非公式" + this.currentMember.display + "DB No." + this.playIndex
        this.Tweet["hash"] = this.currentMember.display + "非公式DB," + this.currentMember.display
        this.show = true
    },
    components: {
        AppLayout,
    },
    methods: {
        handleResize: function () {
            this.width = window.innerWidth
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
    },
    mounted: function () {
        window.addEventListener("resize", this.handleResize)
    },
    beforeDestroy: function () {
        window.removeEventListener("resize", this.handleResize)
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
