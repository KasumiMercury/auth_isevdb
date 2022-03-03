import "./bootstrap"
import Vue from "vue/dist/vue.esm.js"
import vuetify from "./plugins/vuetify"
import VueSocialSharing from "vue-social-sharing"
import VueYoutube from "vue-youtube"
import SharePage from "./Pages/SharePage"

Vue.use(VueSocialSharing)
Vue.use(VueYoutube)

const app = document.getElementById("app")
new Vue({
    vuetify,
    components: {
        SharePage,
    },
}).$mount(app)
