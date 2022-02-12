require("./bootstrap")

// Import modules...
import Vue from "vue"
import { App as InertiaApp, plugin as InertiaPlugin } from "@inertiajs/inertia-vue"
import PortalVue from "portal-vue"
import vuetify from "./plugins/vuetify"
import VueSocialSharing from "vue-social-sharing"
import draggable from "vuedraggable"
import VueYoutube from "vue-youtube"

Vue.mixin({ methods: { route } })
Vue.use(InertiaPlugin)
Vue.use(PortalVue)
Vue.use(VueSocialSharing)
Vue.use(VueYoutube)

const app = document.getElementById("app")

new Vue({
    draggable,
    vuetify,
    render: (h) =>
        h(InertiaApp, {
            props: {
                initialPage: JSON.parse(app.dataset.page),
                resolveComponent: (name) => require(`./Pages/${name}`).default,
            },
        }),
}).$mount(app)
