<template>
    <div id="app">
        <v-app>
            <app-layout class="pb-80">
                <v-card v-if="width > this.break" style="max-width: 80vw" class="mx-auto">
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
                <v-card v-if="width <= this.break" style="max-width: 95vw" class="mx-auto">
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
                    <v-btn v-show="show" class="my-10" color="#1DA1F2" style="color: #fff" block large>
                        <v-icon>fas fa-share-square</v-icon>　Tweet
                    </v-btn>
                </ShareNetwork>
                <v-row justify="center">
                    <v-col cols="auto">
                        <inertia-link large as="v-btn" :href="'/' + currentMember.name + '/latest'">
                            非公式{{ currentMember.display }}DBへ
                        </inertia-link>
                    </v-col>
                    <v-col cols="auto">
                        <inertia-link large as="v-btn" href="/"> 非公式いせぶいDBトップへ </inertia-link>
                    </v-col>
                </v-row>
            </app-layout>
        </v-app>
    </div>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout"

export default {
    props: ["memberName", "player", "id"],
    data() {
        return {
            width: window.innerWidth,
            break: 900,
            Tweet: {},
            playID: "",
            playStart: "",
            playEnd: "",
            playIndex: 0,
            memberArray: [],
            currentMember: [],
            show: false,
        }
    },
    created() {
        this.memberArray = this.$page.props.setting.member.filter((value) => {
            if (value.name == this.memberName) {
                return true
            }
        })
        this.currentMember = this.memberArray[0]

        this.playID = this.player.VideoID
        this.playStart = this.player.start
        this.playEnd = this.player.end
        this.playIndex = this.id
        this.Tweet["url"] = "https://isevdb.sakura.ne.jp/" + this.currentMember.name + "/player/" + this.playIndex
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
    mounted: function () {
        window.addEventListener("resize", this.handleResize)
    },
    beforeDestroy: function () {
        window.removeEventListener("resize", this.handleResize)
    },
}
</script>
