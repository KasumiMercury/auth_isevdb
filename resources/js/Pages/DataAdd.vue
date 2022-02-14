<template>
    <v-app>
        <app-layout>
            <template #header> Register Data </template>
            <v-stepper v-model="e1">
                <v-stepper-header>
                    <template v-for="n in steps">
                        <v-stepper-step :key="`${n}-step`" :complete="e1 > n" :step="n"> Step {{ n }} </v-stepper-step>

                        <v-divider v-if="n !== steps" :key="n"></v-divider>
                    </template>
                </v-stepper-header>

                <v-stepper-items>
                    <v-stepper-content :step="1">
                        <v-card class="text-center">
                            <v-alert v-if="$page.props.user == null" border="left" type="warning" outlined prominent class="my-3">
                                アカウントをお持ちの方はログインをしてから登録してください。<br />
                                途中でログインすると入力中のデータは初期化されます。
                            </v-alert>
                            <v-btn color="#DA1725" :style="'color: #EEE'" class="mx-auto my-10" @click="setType(1, 'youtube')"
                                ><v-icon>fab fa-youtube</v-icon>　You Tube</v-btn
                            >
                            <v-divider></v-divider>
                            <v-btn color="#DA1725" :style="'color: #EEE'" class="mx-auto my-10" @click="setType(1, 'clip')"
                                ><v-icon>fab fa-youtube</v-icon>　You Tube（切り抜き）</v-btn
                            >
                            <v-divider></v-divider>
                            <p class="mt-10">※実験的機能としての実装です。</p>
                            <v-btn class="mx-auto mb-10" color="#1DA1F2" :style="'color: #EEE'" @click="setType(1, 'twitter')"
                                ><v-icon>fab fa-twitter-square</v-icon>　Twitter</v-btn
                            >
                        </v-card>
                    </v-stepper-content>

                    <v-stepper-content :step="2">
                        <template v-if="this.contentType == 'youtube'">
                            <v-card class="mb-12">
                                <div class="my-5">
                                    <v-alert type="warning" prominent border="left" outlined>
                                        YouTubeのURLを入力し、ADDボタンで追加してください。<br />
                                        タイトルはYouTubeの動画タイトルが適用されます。
                                    </v-alert>
                                    <v-text-field :rules="[rules.required]" v-model="urlInput" label="YouTube url" outlined clearable></v-text-field>
                                    <v-row>
                                        <v-col cols="auto" class="ml-auto mr-3">
                                            <v-btn color="#DA1725" :style="'color: #EEE'" large @click="addYoutube"
                                                ><v-icon>fab fa-youtube</v-icon>　Add</v-btn
                                            >
                                        </v-col>
                                    </v-row>
                                    <v-alert class="my-2" v-model="alert" border="left" type="error" dismissible>
                                        動画情報を取得できませんでした。入力URLが正しいか確認してください。
                                    </v-alert>
                                </div>
                            </v-card>
                        </template>

                        <template v-if="this.contentType == 'clip'">
                            <v-card class="mb-12">
                                <div class="my-5">
                                    <v-alert type="warning" prominent border="left" outlined>
                                        YouTubeのURLを入力し、ADDボタンで追加してください。<br />
                                        タイトルはYouTubeの動画タイトルが適用されます。
                                    </v-alert>
                                    <v-alert type="info" prominent border="left" outlined>
                                        複数メンバーを含む切り抜きは、メンバーごとにメンバー分登録してください。<br />
                                        本データベースはメンバーごとに独立したものとして扱っています。
                                    </v-alert>
                                    <v-text-field v-model="urlInput" label="YouTube url" outlined clearable></v-text-field>
                                    <v-row>
                                        <v-col cols="auto" class="ml-auto mr-3">
                                            <v-btn color="#DA1725" :style="'color: #EEE'" large @click="addClip"
                                                ><v-icon>fab fa-youtube</v-icon>　Add</v-btn
                                            >
                                        </v-col>
                                    </v-row>
                                    <v-alert class="my-2" v-model="alert" border="left" type="error" dismissible>
                                        動画情報を取得できませんでした。入力URLが正しいか確認してください。
                                    </v-alert>
                                </div>
                                <draggable v-model="clipArray" @start="drag = true" @update="dragClip" @end="drag = false">
                                    <v-card v-for="(clip, index) in clipArray" :key="'clipForm' + index" class="my-2" outlined>
                                        <v-row class="my-1">
                                            <v-col class="ml-1 mt-3" cols="auto">
                                                <v-img class="ml-3 mr-auto" :src="clip.thumbnail" width="100px"></v-img>
                                            </v-col>
                                            <v-col class="ml-0 mt-3">
                                                <p>{{ clip.title }}</p>
                                            </v-col>
                                        </v-row>
                                        <v-divider class="my-1"></v-divider>
                                        <p class="ml-3 mr-auto">{{ clip.date }} by {{ clip.channel }}</p>
                                        <v-divider class="my-1"></v-divider>
                                        <p class="ml-3 mr-auto">https://youtu.be/{{ clip.VideoID }}</p>
                                        <v-divider class="my-1"></v-divider>
                                        <v-row align="center" justify="center">
                                            <v-col cols="6" class="mr-auto my-3 ml-1">
                                                <v-select
                                                    prepend-icon="fas fa-user-tag"
                                                    v-model="clipMember[index]"
                                                    @change="changeClipMember(index, $event)"
                                                    :items="$page.props.setting.member"
                                                    item-text="name"
                                                    item-value="id"
                                                    solo
                                                ></v-select>
                                            </v-col>
                                            <v-col cols="auto" class="mr-2 my-3 ml-0">
                                                <v-btn v-on:click="clipDelete(index)" color="red" text>削除</v-btn>
                                            </v-col>
                                        </v-row>
                                    </v-card>
                                </draggable>
                                <v-row justify="end">
                                    <v-col cols="auto" class="my-2 mr-5">
                                        <v-btn @click="nextStep(2)">一括登録</v-btn>
                                    </v-col>
                                </v-row>
                            </v-card>
                        </template>

                        <template v-if="this.contentType == 'twitter'">
                            <v-card class="mb-12">
                                <div class="my-5">
                                    <v-alert outlined type="warning" prominent border="left">
                                        ツイート右下の共有ボタンから、URLをコピーしてください。<br />
                                        現在の仕様ではその他の方法で取得されたURLは正常に情報を取得できない可能性があります。
                                    </v-alert>
                                    <v-text-field v-model="urlInput" label="Twitter url" outlined clearable></v-text-field>
                                    <v-row>
                                        <v-col cols="auto" class="ml-auto mr-3">
                                            <v-btn color="#1DA1F2" :style="'color: #EEE'" large @click="addTweet"
                                                ><v-icon>fab fa-twitter-square</v-icon>　Add</v-btn
                                            >
                                        </v-col>
                                    </v-row>
                                    <v-alert class="my-2" v-model="alert" border="left" type="error" dismissible>
                                        ツイート情報を取得できませんでした。入力URLが正しいか確認してください。
                                    </v-alert>
                                </div>
                                <v-card class="my-4 py-3 px-6" v-for="(tweet, index) in tweetArray" :key="'tweet' + index">
                                    <p>No.{{ index + 1 }}</p>
                                    <v-expansion-panels accordion class="my-3">
                                        <v-expansion-panel>
                                            <v-expansion-panel-header class="text-center" color="#1DA1F2" :style="'color: #EEE'">
                                                クリックでツイートを表示
                                            </v-expansion-panel-header>
                                            <v-expansion-panel-content>
                                                <Tweet class="p-0 m-0" :id="tweet.twitterId"></Tweet>
                                            </v-expansion-panel-content>
                                        </v-expansion-panel>
                                    </v-expansion-panels>
                                    <v-text-field
                                        class="my-3"
                                        outlined
                                        label="title"
                                        :rules="[rules.required]"
                                        v-model="tweetArray[index].title"
                                    ></v-text-field>

                                    <v-select
                                        prepend-icon="fas fa-user-tag"
                                        v-model="tweetArray[index].member_id"
                                        :items="$page.props.setting.member"
                                        item-text="name"
                                        item-value="id"
                                        solo
                                    >
                                    </v-select>
                                    <v-select
                                        prepend-icon="fas fa-folder"
                                        v-model="tweetArray[index].cate_id"
                                        :items="$page.props.setting.category"
                                        item-text="title"
                                        item-value="id"
                                        solo
                                    ></v-select>
                                    <v-btn v-on:click="tweetDelete(index)" class="ml-auto mr-0" color="red" text>削除</v-btn>
                                </v-card>

                                <v-row justify="end">
                                    <v-col cols="auto" class="my-2 mr-5">
                                        <v-btn @click="nextStep(2)">一括登録</v-btn>
                                    </v-col>
                                </v-row>
                            </v-card>
                        </template>

                        <v-btn color="primary" class="ml-0 mr-auto" @click="backStep(2)"> Back </v-btn>
                    </v-stepper-content>

                    <v-stepper-content :step="3">
                        <template v-if="this.contentType == 'youtube'">
                            <v-card class="mb-12 text-center">
                                <youtube class="mx-auto" :video-id="youtubeInfo.VideoID" ref="youtube"></youtube>
                                <v-divider></v-divider>
                                <v-row no-gutters justify="center" align="center" class="my-1">
                                    <v-col cols="auto">
                                        <v-btn large @click="SSMinus" icon color="red">
                                            <v-icon>fas fa-angle-double-left</v-icon>
                                        </v-btn>
                                    </v-col>
                                    <v-col cols="auto">
                                        <v-btn large @click="SMinus" icon color="red">
                                            <v-icon>fas fa-angle-left</v-icon>
                                        </v-btn>
                                    </v-col>
                                    <v-col cols="auto">
                                        <v-btn x-large @click="pauseVideo" v-if="playing" icon color="red">
                                            <v-icon>fas fa-pause fa-fw</v-icon>
                                        </v-btn>
                                        <v-btn x-large @click="playVideo" v-else icon color="red">
                                            <v-icon>fas fa-play fa-fw</v-icon>
                                        </v-btn>
                                    </v-col>
                                    <v-col cols="auto">
                                        <v-btn large @click="SPlus" icon color="red">
                                            <v-icon>fas fa-angle-right</v-icon>
                                        </v-btn>
                                    </v-col>
                                    <v-col cols="auto">
                                        <v-btn large @click="SSPlus" icon color="red">
                                            <v-icon>fas fa-angle-double-right</v-icon>
                                        </v-btn>
                                    </v-col>
                                </v-row>
                                <v-divider></v-divider>
                                <v-row no-gutters justify="center" align="start" class="mt-10">
                                    <v-col cols="auto">
                                        <v-btn large @click="InputMinus" icon color="red">
                                            <v-icon>fas fa-caret-left</v-icon>
                                        </v-btn>
                                    </v-col>
                                    <v-col cols="8">
                                        <v-slider
                                            v-model="slider"
                                            class="align-center"
                                            :max="10000"
                                            :min="0"
                                            thumb-label="always"
                                            v-model.number="PMInput"
                                            step="5"
                                        ></v-slider>
                                    </v-col>
                                    <v-col cols="auto">
                                        <v-btn large @click="InputPlus" icon color="red">
                                            <v-icon>fas fa-caret-right</v-icon>
                                        </v-btn>
                                    </v-col>
                                </v-row>
                                <v-row no-gutters justify="center" align="center" class="my-1">
                                    <v-col cols="6">
                                        <v-text-field
                                            v-model="SeekInput"
                                            label="Seek To"
                                            hint="入力された時間へ進みます。半角数字、半角「:」で区切り"
                                            persistent-hint
                                        ></v-text-field>
                                    </v-col>
                                    <v-col cols="auto">
                                        <v-btn large @click="InputSeek" icon color="red">
                                            <v-icon>fas fa-forward</v-icon>
                                        </v-btn>
                                    </v-col>
                                </v-row>
                                <v-row justify="center" class="my-1">
                                    <v-col cols="auto" class="mx-auto">
                                        <v-switch v-model="isPaste" inset label="貼り付けで取得"></v-switch>
                                    </v-col>
                                </v-row>
                                <v-divider></v-divider>
                                <v-row justify="center" class="my-1">
                                    <v-col cols="auto" class="mx-auto">
                                        <template v-if="isPaste == false">
                                            <v-btn class="my-2" @click="getTime"><v-icon>fas fa-search-plus</v-icon> 現在時刻を取得</v-btn>
                                        </template>
                                        <template v-else>
                                            <v-row justify="center" align="end">
                                                <v-col cols="auto">
                                                    <v-textarea readonly filled :value="example" label="Example" rows="15"></v-textarea>
                                                </v-col>
                                                <v-col cols="auto">
                                                    <v-textarea
                                                        v-model="PastedTime"
                                                        clearable
                                                        clear-icon="mdi-close-circle"
                                                        label="Paste Time"
                                                        rows="15"
                                                        solo
                                                        hint="２桁１桁問わず、半角の「:」で区切り"
                                                    ></v-textarea>
                                                </v-col>
                                                <v-col cols="auto">
                                                    <v-btn class="m-3" @click="PasteToTime">変換</v-btn>
                                                </v-col>
                                            </v-row>
                                        </template>
                                    </v-col>
                                </v-row>

                                <v-row>
                                    <v-col cols="12" v-for="(youtube, index) in youtubeArray" :key="'youtube' + index">
                                        <v-card class="my-3" :elevation="index + 1">
                                            <v-row no-gutters class="my-0">
                                                <v-col class="ml-5 mr-5">
                                                    <v-text-field
                                                        label="time"
                                                        @change="changeTime(index)"
                                                        v-model="youtubeArray[index].time"
                                                    ></v-text-field>
                                                </v-col>
                                                <v-col cols="auto" class="mr-5 ml-auto">
                                                    <v-btn large @click="PlayAt(youtubeArray[index].time)" icon color="red">
                                                        <v-icon>fas fa-forward</v-icon>
                                                    </v-btn>
                                                </v-col>
                                                <v-col v-if="index != 0 && youtubeIsStart[index - 1] == true" cols="auto" class="mr-5 ml-auto">
                                                    <v-switch
                                                        v-model="youtubeIsStart[index]"
                                                        inset
                                                        @change="editIsStart(index, $event)"
                                                        label="スタートタイム"
                                                    ></v-switch>
                                                </v-col>
                                                <v-col v-else cols="auto" class="mr-5 ml-auto">
                                                    <v-switch
                                                        v-model="youtubeIsStart[index]"
                                                        input-value="true"
                                                        disabled
                                                        inset
                                                        label="編集不可"
                                                    ></v-switch>
                                                </v-col>
                                            </v-row>
                                            <v-row class="my-0">
                                                <v-col class="mx-10">
                                                    <v-text-field
                                                        v-if="youtubeIsStart[index] == true"
                                                        label="title"
                                                        v-model="youtube.title"
                                                    ></v-text-field>
                                                    <v-text-field
                                                        v-else
                                                        label="title"
                                                        v-model="youtubeArray[index - 1].title"
                                                        disabled
                                                    ></v-text-field>
                                                </v-col>
                                            </v-row>
                                            <v-row justify="center" class="my-0">
                                                <template v-if="youtubeIsStart[index] == false || youtubeSwitch[index] == false">
                                                    <v-col md="4" cols="10">
                                                        <v-select
                                                            prepend-icon="fas fa-folder"
                                                            readonly
                                                            :items="$page.props.setting.category"
                                                            item-text="title"
                                                            item-value="id"
                                                            solo
                                                        ></v-select>
                                                    </v-col>
                                                    <v-col md="4" cols="10">
                                                        <v-select
                                                            prepend-icon="fas fa-user-tag"
                                                            readonly
                                                            :items="$page.props.setting.member"
                                                            item-text="name"
                                                            item-value="id"
                                                            solo
                                                        ></v-select>
                                                    </v-col>
                                                </template>
                                                <template v-else>
                                                    <v-col md="4" cols="10">
                                                        <v-select
                                                            prepend-icon="fas fa-folder"
                                                            @change="editCate(index, $event)"
                                                            v-model="youtubeCate[index]"
                                                            :items="$page.props.setting.category"
                                                            item-text="title"
                                                            item-value="id"
                                                            solo
                                                        ></v-select>
                                                    </v-col>
                                                    <v-col md="4" cols="10">
                                                        <v-select
                                                            prepend-icon="fas fa-user-tag"
                                                            @change="editMember(index, $event)"
                                                            v-model="youtubeMember[index]"
                                                            :items="$page.props.setting.member"
                                                            item-text="name"
                                                            item-value="id"
                                                            solo
                                                        ></v-select>
                                                    </v-col>
                                                </template>
                                            </v-row>
                                            <v-row justify="end" align="end" class="my-0">
                                                <v-col cols="auto" class="mr-20">
                                                    <v-switch
                                                        v-model="youtubeSwitch[index]"
                                                        @change="editSwitch(index, $event)"
                                                        inset
                                                        label="DBに追加"
                                                    ></v-switch>
                                                </v-col>
                                                <v-col cols="auto" class="mr-10 mb-0">
                                                    <v-btn v-on:click="youtubeDelete(index)" color="red" text>削除</v-btn>
                                                </v-col>
                                            </v-row>
                                        </v-card>
                                    </v-col>
                                </v-row>

                                <v-row justify="center">
                                    <v-col cols="auto" class="my-2 mr-5">
                                        <v-btn @click="AddStep(3)">一括登録</v-btn>
                                    </v-col>
                                </v-row>
                                <v-row justify="center">
                                    <v-col cols="auto" class="my-2 mr-5">
                                        <v-btn @click="StampStep(3)">タイムスタンプを出力</v-btn>
                                    </v-col>
                                </v-row>
                            </v-card>
                        </template>

                        <template v-if="this.contentType == 'clip'">
                            <v-card class="text-center mb-10 py-10 px-5" outlined>
                                <template v-if="$page.props.user">
                                    <p>確認のためボタンをクリックしてください</p>
                                    <v-btn class="my-3" @click="postClip">登録を確定する</v-btn>
                                </template>
                                <template v-else>
                                    <p>ハンドルネームを設定してください。</p>
                                    <p>削除申請の際、照合に使用します。</p>
                                    <v-row justify="center">
                                        <v-col cols="6">
                                            <v-text-field label="HN" v-model="createrHN"></v-text-field>
                                        </v-col>
                                    </v-row>
                                    <v-btn class="my-3" @click="postClip">登録を確定する</v-btn>
                                </template>
                            </v-card>
                            <v-alert :value="complete" type="success" border="left" transition="scale-transition">
                                完了しました！ご協力ありがとうございます！
                            </v-alert>
                        </template>

                        <template v-if="this.contentType == 'twitter'">
                            <v-card class="text-center mb-10 py-10 px-5" outlined>
                                <template v-if="$page.props.user">
                                    <p>確認のためボタンをクリックしてください</p>
                                    <v-btn @click="postTweet" class="my-3">登録を確定する</v-btn>
                                </template>
                                <template v-else>
                                    <p>ハンドルネームを設定してください。</p>
                                    <p>削除申請の際、照合に使用します。</p>
                                    <v-row justify="center">
                                        <v-col cols="6">
                                            <v-text-field label="HN" v-model="createrHN"></v-text-field>
                                        </v-col>
                                    </v-row>
                                    <v-btn class="my-3" @click="postTweet">登録を確定する</v-btn>
                                </template>
                            </v-card>
                            <v-alert :value="complete" type="success" border="left" transition="scale-transition">
                                完了しました！ご協力ありがとうございます！
                            </v-alert>
                        </template>

                        <v-btn color="primary" class="ml-0 mr-auto" @click="backStep(3)"> Back </v-btn>
                    </v-stepper-content>

                    <v-stepper-content :step="4">
                        <template v-if="isAdd == true">
                            <v-card class="mb-12 text-center">
                                <template v-if="$page.props.user">
                                    <p>確認のためボタンをクリックしてください</p>
                                    <v-btn @click="postYoutube" class="my-3">登録を確定する</v-btn>
                                </template>
                                <template v-else>
                                    <p>ハンドルネームを設定してください。</p>
                                    <p>削除申請の際、照合に使用します。</p>
                                    <v-row justify="center">
                                        <v-col cols="6">
                                            <v-text-field label="HN" v-model="createrHN"></v-text-field>
                                        </v-col>
                                    </v-row>
                                    <v-btn class="my-3" @click="postYoutube">登録を確定する</v-btn>
                                </template>

                                <v-alert :value="complete" type="success" border="left" transition="scale-transition">
                                    完了しました！ご協力ありがとうございます！
                                </v-alert>
                            </v-card>
                        </template>
                        <template v-else>
                            <v-card class="mb-12 text-center">
                                <v-btn @click="CopyStamp" class="my-3"><v-icon>fas fa-clipboard</v-icon> タイムスタンプをコピー</v-btn>
                                <div class="text-center" id="target">
                                    <ul style="list-style-type: none" class="text-center" v-for="(time, index) in youtubeArray" :key="'time' + index">
                                        <li v-if="youtubeArray[index].isStart == true">
                                            {{ youtubeArray[index].time }}　{{ youtubeArray[index].title }} <span style="display: none">\n</span>
                                        </li>
                                    </ul>
                                </div>
                            </v-card>

                            <v-alert :value="complete" type="success" border="left" transition="scale-transition">
                                完了しました！ご利用ありがとうございます！
                            </v-alert>
                        </template>

                        <v-btn color="primary" class="mx-auto" @click="backStep(4)"> Back </v-btn>
                    </v-stepper-content>
                </v-stepper-items>
            </v-stepper>
        </app-layout>
    </v-app>
</template>

<script>
import AppLayout from "@/Layouts/TopLayout"
import draggable from "vuedraggable"
import { Tweet } from "vue-tweet-embed"

export default {
    data() {
        return {
            example: "4:31:16\n32:00\n6:4:07\n6:09:08",
            isAdd: false,
            SeekInput: "5:04",
            PMInput: 15,
            playing: false,
            rules: {
                required: (value) => !!value || "Required.",
            },
            createrHN: "",
            isPaste: false,
            members: [],
            alert: false,
            n: 0,
            e1: 1,
            steps: 3,
            width: window.innerWidth,
            break: 900,
            contentType: "",
            urlInput: "",
            YTurl: "",
            youtubeInfo: [],
            youtubeArray: new Array(),
            youtubeForm: new Object(),
            youtubeIsStart: new Array(),
            youtubeCate: new Array(),
            youtubeMember: new Array(),
            youtubeSwitch: new Array(),
            clipArray: [],
            clipMember: [],
            createID: "",
            Path: false,
            results: null,
            params: {
                id: "",
                key: "AIzaSyCv9gZE-50Vj3fVj1dgk0IPv0O1MoDsiHI",
                part: "snippet",
            },
            tweetArray: [],
            tweetForm: [],
            createTweet: "",
            PastedTime: "",
            sendArray: [],
            complete: false,
        }
    },
    components: {
        AppLayout,
        Tweet,
        draggable,
    },
    methods: {
        handleResize: function () {
            this.width = window.innerWidth
        },
        setType(n, type) {
            if (type == "youtube") {
                this.steps = 4
                this.e1 = n + 1
                this.contentType = type
            } else {
                this.e1 = n + 1
                this.contentType = type
            }
        },
        backStep(n) {
            if (n === 2) {
                this.e1 = n - 1
                this.contentType = ""
                this.steps = 3
            } else {
                this.e1 = n - 1
                this.youtubeInfo = []
            }
        },
        nextStep(n) {
            this.e1 = n + 1
        },
        AddStep(n) {
            this.isAdd = true
            this.e1 = n + 1
        },
        StampStep(n) {
            this.isAdd = false
            this.e1 = n + 1
        },
        addYoutube() {
            this.YTurl = this.urlInput
            this.alert = false
            this.clipForm = []
            let isShort = this.YTurl.indexOf("watch")
            if (isShort != -1) {
                let WhereS = this.YTurl.indexOf("=") + 1
                let WhereTime = this.YTurl.indexOf("&t")
                if (WhereTime != -1) {
                    this.createID = this.YTurl.slice(WhereS, WhereTime)
                } else {
                    this.createID = this.YTurl.slice(WhereS)
                }
            } else {
                let WhereDomain = this.YTurl.indexOf("youtu.be/") + 9
                let WhereTime = this.YTurl.indexOf("?t=")
                if (WhereTime != -1) {
                    this.createID = this.YTurl.slice(WhereDomain, WhereTime)
                } else {
                    this.createID = this.YTurl.slice(WhereDomain)
                }
            }
            if (this.createID == "") {
                this.alert = true
            } else {
                this.params.id = this.createID
                let self = this
                axios
                    .get("https://www.googleapis.com/youtube/v3/videos", {
                        params: this.params,
                    })
                    .then(function (res) {
                        self.results = res.data.items[0].snippet

                        self.youtubeInfo.VideoID = self.createID
                        self.youtubeInfo.title = self.results.title
                        self.youtubeInfo.date = self.results.publishedAt
                        self.youtubeInfo.channnel = self.results.channelId
                        self.youtubeInfo.thumbnail = self.results.thumbnails.default.url
                        self.youtubeInfo.member_id = 1
                        self.e1 = 3
                    })
                    .catch((error) => {
                        console.log(error)
                    })
            }
        },
        getTime() {
            this.youtubeForm = []
            this.player.getCurrentTime().then((time) => {
                let INTtime = Math.floor(time)
                let hour = Math.floor(INTtime / 3600)
                let min = Math.floor((INTtime % 3600) / 60)
                let rem = ("00" + (INTtime % 60)).slice(-2)
                let HMStime = hour + ":" + min + ":" + rem

                this.youtubeForm.rowTime = Math.floor(time)
                this.youtubeForm.time = HMStime
                this.youtubeForm.title = ""
                this.youtubeForm.isStart = true
                this.youtubeForm.member_id = 1
                this.youtubeForm.cate_id = 1
                this.youtubeForm.switch = true

                this.youtubeArray.push(this.youtubeForm)
                this.youtubeArray.sort((a, b) => a.rowTime - b.rowTime)

                this.youtubeIsStart = this.youtubeArray.map((item) => item["isStart"])
                this.youtubeCate = this.youtubeArray.map((item) => item["cate_id"])
                this.youtubeMember = this.youtubeArray.map((item) => item["member_id"])
                this.youtubeSwitch = this.youtubeArray.map((item) => item["switch"])
            })
        },
        changeTime(index) {
            let changeRowTIme = this.youtubeArray[index].time
            let timeArray = changeRowTIme.split(":")
            if (timeArray.length == 2) {
                let TimeM = timeArray[0]
                let TimeS = timeArray[1]
                let timeSecond = Number(TimeM) * 60 + Number(TimeS)
                this.youtubeArray[index].rowTime = timeSecond
            } else {
                let TimeH = timeArray[0]
                let TimeM = timeArray[1]
                let TimeS = timeArray[2]
                let timeSecond = Number(TimeH) * 3600 + Number(TimeM) * 60 + Number(TimeS)
                this.youtubeArray[index].rowTime = timeSecond
            }

            this.youtubeArray.sort((a, b) => a.rowTime - b.rowTime)

            this.youtubeIsStart = this.youtubeArray.map((item) => item["isStart"])
            this.youtubeCate = this.youtubeArray.map((item) => item["cate_id"])
            this.youtubeMember = this.youtubeArray.map((item) => item["member_id"])
            this.youtubeSwitch = this.youtubeArray.map((item) => item["switch"])
        },
        editIsStart(index, event) {
            this.youtubeArray[index]["isStart"] = event
        },
        editCate(index, event) {
            this.youtubeArray[index]["cate_id"] = event
        },
        editMember(index, event) {
            this.youtubeArray[index]["member_id"] = event
        },
        editSwitch(index, event) {
            this.youtubeArray[index]["switch"] = event
        },
        youtubeDelete(index) {
            this.youtubeArray.splice(index, 1)
            this.youtubeIsStart.splice(index, 1)
            this.youtubeCate.splice(index, 1)
            this.youtubeMember.splice(index, 1)
            this.youtubeSwitch.splice(index, 1)
        },
        PasteToTime() {
            let PastedArrayRaw = this.PastedTime.split("\n")
            let InputTime = PastedArrayRaw.filter((v) => v)
            let numTemp = InputTime.length

            for (let i = 0; i < numTemp; i++) {
                let timeArray = InputTime[i].split(":")
                if (timeArray.length == 2) {
                    this.youtubeForm = []
                    let TimeM = timeArray[0]
                    let TimeS = timeArray[1]
                    let timeSecond = Number(TimeM) * 60 + Number(TimeS)

                    this.youtubeForm.rowTime = timeSecond
                    this.youtubeForm.time = InputTime[i]
                    this.youtubeForm.title = ""
                    this.youtubeForm.isStart = true
                    this.youtubeForm.member_id = 1
                    this.youtubeForm.cate_id = 1
                    this.youtubeForm.switch = true

                    this.youtubeArray.push(this.youtubeForm)
                } else {
                    this.youtubeForm = []
                    let TimeH = timeArray[0]
                    let TimeM = timeArray[1]
                    let TimeS = timeArray[2]
                    let timeSecond = Number(TimeH) * 3600 + Number(TimeM) * 60 + Number(TimeS)

                    this.youtubeForm.rowTime = timeSecond
                    this.youtubeForm.time = InputTime[i]
                    this.youtubeForm.title = ""
                    this.youtubeForm.isStart = true
                    this.youtubeForm.member_id = 1
                    this.youtubeForm.cate_id = 1
                    this.youtubeForm.switch = true

                    this.youtubeArray.push(this.youtubeForm)
                }

                this.youtubeArray.sort((a, b) => a.rowTime - b.rowTime)

                this.youtubeIsStart = this.youtubeArray.map((item) => item["isStart"])
                this.youtubeCate = this.youtubeArray.map((item) => item["cate_id"])
                this.youtubeMember = this.youtubeArray.map((item) => item["member_id"])
                this.youtubeSwitch = this.youtubeArray.map((item) => item["switch"])
            }
        },
        addClip() {
            this.YTurl = this.urlInput
            this.alert = false
            let isShort = this.YTurl.indexOf("watch")
            if (isShort != -1) {
                let WhereS = this.YTurl.indexOf("=") + 1
                let WhereTime = this.YTurl.indexOf("&t")
                if (WhereTime != -1) {
                    this.createID = this.YTurl.slice(WhereS, WhereTime)
                } else {
                    this.createID = this.YTurl.slice(WhereS)
                }
            } else {
                let WhereDomain = this.YTurl.indexOf("youtu.be/") + 9
                let WhereTime = this.YTurl.indexOf("?t=")
                if (WhereTime != -1) {
                    this.createID = this.YTurl.slice(WhereDomain, WhereTime)
                } else {
                    this.createID = this.YTurl.slice(WhereDomain)
                }
            }
            if (this.createID == "") {
                this.alert = true
            } else {
                this.params.id = this.createID
                let self = this
                axios
                    .get("https://www.googleapis.com/youtube/v3/videos", {
                        params: this.params,
                    })
                    .then(function (res) {
                        self.results = res.data.items[0].snippet
                        let clipForm = []

                        clipForm.VideoID = self.createID
                        clipForm.title = self.results.title
                        clipForm.date = self.results.publishedAt
                        clipForm.channel = self.results.channelTitle
                        clipForm.thumbnail = self.results.thumbnails.default.url
                        clipForm.member_id = 1
                        self.clipMember.push(1)
                        self.clipArray.push(clipForm)
                    })
                    .catch((error) => {
                        console.log(error)
                    })
            }
        },
        changeClipMember(index, event) {
            this.clipArray[index]["member_id"] = event
        },
        clipDelete(index) {
            this.clipArray.splice(index, 1)
            this.clipMember.splice(index, 1)
        },
        dragClip() {
            this.clipMember = this.clipArray.map((item) => item["member_id"])
        },
        addTweet() {
            this.Tweeturl = this.urlInput
            this.alert = false
            let whereStatus = this.Tweeturl.indexOf("/status/") + 8
            let whereEnd = this.Tweeturl.indexOf("?s=")
            //this.createTweet = this.Tweeturl.slice(whereStatus, 16)
            this.createTweet = this.Tweeturl.slice(whereStatus, whereEnd)

            this.tweetForm = []

            this.tweetForm.twitterId = this.createTweet
            this.tweetForm.cate_id = 5
            this.tweetForm.member_id = 1
            this.tweetArray.push(this.tweetForm)
        },
        tweetDelete(index) {
            this.tweetArray.splice(index, 1)
        },
        postTweet() {
            this.complete = false
            if (this.$page.props.user == null) {
                this.sendArray = []
                for (let i = 0; i < Object.keys(this.tweetArray).length; i++) {
                    this.sendArray.push({
                        title: this.tweetArray[i].title,
                        date: new Date(),
                        status: 0,
                        cate_id: this.tweetArray[i].cate_id,
                        member_id: this.tweetArray[i].member_id,
                        twitter: this.tweetArray[i].twitterId,
                        created_at: new Date(),
                        updated_at: new Date(),
                        createrHN: this.createrHN,
                    })
                }
            } else {
                this.sendArray = []
                for (let i = 0; i < Object.keys(this.tweetArray).length; i++) {
                    this.sendArray.push({
                        title: this.tweetArray[i].title,
                        date: new Date(),
                        status: 0,
                        cate_id: this.tweetArray[i].cate_id,
                        member_id: this.tweetArray[i].member_id,
                        twitter: this.tweetArray[i].twitterId,
                        created_at: new Date(),
                        updated_at: new Date(),
                        createrHN: this.$page.props.user.name,
                    })
                }
            }
            let self = this
            axios({
                method: "post",
                url: "/api/add/twitter",
                dataType: "json",
                data: this.sendArray,
            })
                .then((response) => {
                    console.log(response)
                    self.complete = true
                })
                .catch((error) => {
                    console.log(error)
                })
        },
        postClip() {
            this.complete = false
            if (this.$page.props.user == null) {
                this.sendArray = []
                for (let i = 0; i < Object.keys(this.clipArray).length; i++) {
                    this.sendArray.push({
                        title: this.clipArray[i].title,
                        date: this.clipArray[i].date,
                        VideoID: this.clipArray[i].VideoID,
                        start: 0,
                        end: 0,
                        status: 0,
                        cate_id: 4,
                        member_id: this.clipArray[i].member_id,
                        created_at: new Date(),
                        updated_at: new Date(),
                        createrHN: this.createrHN,
                    })
                }
            } else {
                this.sendArray = []
                for (let i = 0; i < Object.keys(this.clipArray).length; i++) {
                    this.sendArray.push({
                        title: this.clipArray[i].title,
                        date: this.clipArray[i].date,
                        VideoID: this.clipArray[i].VideoID,
                        start: 0,
                        end: 0,
                        status: 0,
                        cate_id: 4,
                        member_id: this.clipArray[i].member_id,
                        created_at: new Date(),
                        updated_at: new Date(),
                        createrHN: this.$page.props.user.name,
                    })
                }
            }
            let self = this
            axios({
                method: "post",
                url: "/api/add/clip",
                dataType: "json",
                data: this.sendArray,
            })
                .then((response) => {
                    console.log(response)
                    self.complete = true
                })
                .catch((error) => {
                    console.log(error)
                })
        },
        postYoutube() {
            this.complete = false
            if (this.$page.props.user == null) {
                this.sendArray = []
                let SendLength = 0
                for (let i = 0; i < Object.keys(this.youtubeArray).length; i++) {
                    if (this.youtubeArray[i].isStart == true) {
                        if (this.youtubeArray[i].switch == true) {
                            this.sendArray.push({
                                title: this.youtubeArray[i].title,
                                date: this.youtubeInfo.date,
                                VideoID: this.youtubeInfo.VideoID,
                                start: this.youtubeArray[i].rowTime,
                                end: 0,
                                status: 0,
                                cate_id: this.youtubeArray[i].cate_id,
                                member_id: this.youtubeArray[i].member_id,
                                created_at: new Date(),
                                updated_at: new Date(),
                                createrHN: this.createrHN,
                            })
                            SendLength = SendLength++
                        }
                    } else {
                        this.sendArray[SendLength].end = this.youtubeArray[i].rowTime
                    }
                }
            } else {
                this.sendArray = []
                let SendLength = 0
                for (let i = 0; i < Object.keys(this.youtubeArray).length; i++) {
                    if (this.youtubeArray[i].isStart == true) {
                        if (this.youtubeArray[i].switch == true) {
                            this.sendArray.push({
                                title: this.youtubeArray[i].title,
                                date: this.youtubeInfo.date,
                                VideoID: this.youtubeInfo.VideoID,
                                start: this.youtubeArray[i].rowTime,
                                end: 0,
                                status: 0,
                                cate_id: this.youtubeArray[i].cate_id,
                                member_id: this.youtubeArray[i].member_id,
                                created_at: new Date(),
                                updated_at: new Date(),
                                createrHN: this.$page.props.user.name,
                            })
                            SendLength = SendLength++
                        }
                    } else {
                        this.sendArray[SendLength].end = this.youtubeArray[i].rowTime
                    }
                }
            }
            let self = this
            axios({
                method: "post",
                url: "/api/add/youtube",
                dataType: "json",
                data: this.sendArray,
            })
                .then((response) => {
                    console.log(response)
                    self.complete = true
                })
                .catch((error) => {
                    console.log(error)
                })
        },
        playVideo() {
            this.player.playVideo()
            this.playing = true
        },
        pauseVideo() {
            this.player.pauseVideo()
            this.playing = false
        },
        SSPlus() {
            this.player.getCurrentTime().then((time) => {
                this.player.seekTo(time + 10, true)
            })
        },
        SSMinus() {
            this.player.getCurrentTime().then((time) => {
                this.player.seekTo(time - 10, true)
            })
        },
        SPlus() {
            this.player.getCurrentTime().then((time) => {
                this.player.seekTo(time + 1, true)
            })
        },
        SMinus() {
            this.player.getCurrentTime().then((time) => {
                this.player.seekTo(time - 1, true)
            })
        },
        InputMinus() {
            this.player.getCurrentTime().then((time) => {
                this.player.seekTo(time - this.PMInput, true)
            })
        },
        InputPlus() {
            this.player.getCurrentTime().then((time) => {
                this.player.seekTo(time + this.PMInput, true)
            })
        },
        InputSeek() {
            let InputTimeTemp = this.SeekInput
            let SetHMS = InputTimeTemp.split(":")
            if (SetHMS.length == 2) {
                let TimeM = SetHMS[0]
                let TimeS = SetHMS[1]
                let SeekTime = Number(TimeM) * 60 + Number(TimeS)
                this.player.seekTo(SeekTime, true)
            } else {
                let TimeH = SetHMS[0]
                let TimeM = SetHMS[1]
                let TimeS = SetHMS[2]
                let SeekTime = Number(TimeH) * 3600 + Number(TimeM) * 60 + Number(TimeS)
                this.player.seekTo(SeekTime, true)
            }
        },
        PlayAt(input) {
            let InputTimeTemp = input
            let SetHMS = InputTimeTemp.split(":")
            console.log(SetHMS)
            if (SetHMS.length == 2) {
                let TimeM = SetHMS[0]
                let TimeS = SetHMS[1]
                let SeekTime = Number(TimeM) * 60 + Number(TimeS)
                this.player.seekTo(SeekTime, true)
            } else {
                let TimeH = SetHMS[0]
                let TimeM = SetHMS[1]
                let TimeS = SetHMS[2]
                let SeekTime = Number(TimeH) * 3600 + Number(TimeM) * 60 + Number(TimeS)
                this.player.seekTo(SeekTime, true)
            }
        },
        CopyStamp() {
            this.complete = false
            let self = this
            const copyText = this.$el.querySelector("#target").innerText
            navigator.clipboard
                .writeText(copyText)
                .then(() => {
                    self.complete = true
                })
                .catch((e) => {
                    alert(e)
                })
        },
    },
    mounted: function () {
        window.addEventListener("resize", this.handleResize)
    },
    beforeDestroy: function () {
        window.removeEventListener("resize", this.handleResize)
    },
    watch: {
        steps(val) {
            if (this.e1 > val) {
                this.e1 = val
            }
        },
    },
    computed: {
        player() {
            return this.$refs.youtube.player
        },
    },
}
</script>
