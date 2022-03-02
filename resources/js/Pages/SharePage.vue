<template>
    <v-app>
        <div :id="'bg_' + currentMember.name"></div>
        <share-layout :style="styles">
            <template #title> 非公式{{ currentMember.display }}DB </template>
            <template #header> {{ currentMember.display }} SharePage </template>
            <div id="element">
                <v-card class="px-5 py-5">
                    <h2 class="text-center my-2" style="font-size: 1.5rem; font-family: 'Zen Maru Gothic', sans-serif">{{ player.title }}</h2>
                    <v-card v-if="width > this.break" class="mx-auto p-3" width="65vw" min-width="750">
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
                    <v-card v-if="width <= this.break" width="100%" class="mx-auto p-3">
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
                    <div class="px-15 my-5">
                        <ShareNetwork
                            v-if="show"
                            network="twitter"
                            :url="Tweet.url"
                            :title="Tweet.title"
                            :hashtags="Tweet.hash"
                            :class="'text-decoration-none'"
                        >
                            <v-btn v-show="show" color="#1DA1F2" style="color: #fff" block x-large>
                                <v-icon>fas fa-share-square</v-icon>　Tweet
                            </v-btn>
                        </ShareNetwork>
                    </div>
                    <v-row justify="center" class="text-center">
                        <v-col cols="12" class="mt-1">
                            <v-btn large :color="currentMember.MainCol" :href="'/' + currentMember.name + '/latest'">
                                <v-icon>fas fa-database</v-icon>
                                　非公式{{ currentMember.display }}DBへ
                            </v-btn>
                        </v-col>
                    </v-row>
                    <v-row justify="center" class="text-center">
                        <v-col cols="12">
                            <v-btn large color="cyan lighten-3" href="/">
                                <v-icon>fas fa-home</v-icon>
                                　非公式いせぶいDBトップへ
                            </v-btn>
                        </v-col>
                    </v-row>
                </v-card>
            </div>
        </share-layout>
    </v-app>
</template>

<script>
import ShareLayout from "@/Layouts/ShareLayout"
export default {
    props: ["current-member", "player", "id"],
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
        ShareLayout,
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
</style>
