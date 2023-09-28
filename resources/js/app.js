import "../css/app.css";
import "../css/main.css";
import "../css/rating.css";
import "./bootstrap";

// Import modules...

import Actions from "@/components/Actions.vue";
import ANavLink from "@/components/ANavLink.vue";
import BackButton from "@/components/BackButton.vue";
import CardModal from "@/components/CardModal.vue";
import DataCalendar from "@/components/DataCalendar.vue";
import DataTable from "@/components/DataTable.vue";
import DateInput from "@/components/DateInput.vue";
import HeaderText from "@/components/Header.vue";
import Like from "@/components/Like.vue";
import LoadingCard from "@/components/LoadingCard.vue";
import Location from "@/components/Location.vue";
import globalLocationSearch from "@/components/Location3.vue";
import MiniDataTable from "@/components/MiniDataTable.vue";
import Modal from "@/components/Modal.vue";
import PhoneInput from "@/components/PhoneInput.vue";
import SingleReservation from "@/components/Reservation.vue";
import SingleRestaurant from "@/components/Restaurant.vue";
import FlashText from "@/components/WarningFlag.vue";


// new
import EmptySpace from "@/components/EmptySpace.vue";
import CategoryPopup from "@/components/menuPopup/category.vue";
import ItemPopup from "@/components/menuPopup/item.vue";
import MenuPopup from "@/components/menuPopup/menu.vue";
import globalSearchNew from "@/components/Search.vue";
import SearchUsers from "@/components/SearchUsers.vue";
import ShareButton from "@/components/ShareButton.vue";
import TextEditor from "@/components/TextEditor.vue";
import DisplayImage from "@/components/vendor/DisplayImage.vue";
import Metric from "@/components/vendor/Metric.vue";
import Ratings from "@/components/vendor/Ratings.vue";
import AdminSingleReservation from "@/components/vendor/Reservation.vue";
import SendEmail from "@/components/vendor/SendEmail.vue";
import SendText from "@/components/vendor/SendText.vue";
import UploadImage from "@/components/vendor/UploadImage.vue";
import JetConfirmationModal from "@/Jetstream/ConfirmationModal.vue";
import JetDialogModal from "@/Jetstream/DialogModal.vue";
import JetModal from "@/Jetstream/Modal.vue";
import Pagination from "@/Jetstream/Pagination.vue";
import LoadingIcon from "@/Jetstream/Vendor_icons/LoadingIcon.vue";
import store from "@/store/index.js";
import CreateIcon from "@/Jetstream/Vendor_icons/CreateIcon.vue";



import {
    createInertiaApp,
    Link,
    plugin as InertiaPlugin
} from "@inertiajs/inertia-vue3";
import { InertiaProgress } from "@inertiajs/progress";
import { createApp, h } from "vue";
import { Tab, Tabs } from "vue3-tabs-component";
import { ZiggyVue } from "../../vendor/tightenco/ziggy/dist/vue.m";
import AppGlobalMethods from "./Plugins/AppGlobalMethods.js";

import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";

const appName =
    window.document.getElementsByTagName("title")[0]?.innerText || "Laravel";

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue")
        ),
    setup({ el, app, props, plugin }) {
        return (
            createApp({ render: () => h(app, props) })
                .component("AdminSingleReservation", AdminSingleReservation)
                .component("SingleRestaurant", SingleRestaurant)
                .component("SingleReservation", SingleReservation)
                .component("Modal", Modal)
                .component("LoadingCard", LoadingCard)
                .component("TextEditor", TextEditor)
                .component("SendEmail", SendEmail)
                .component("SendText", SendText)
                .component("JetModal", JetModal)
                .component("PhoneInput", PhoneInput)
                .component("DateInput", DateInput)
                .component("JetConfirmationModal", JetConfirmationModal)
                .component("JetDialogModal", JetDialogModal)
                .component("SearchUsers", SearchUsers)
                .component("ANavLink", ANavLink)
                .component("Like", Like)
                .component("Link", Link)
                .component("UploadImage", UploadImage)
                .component("DisplayImage", DisplayImage)
                .component("CardModal", CardModal)
                .component("Location", Location)
                .component("Pagination", Pagination)
                .component("Metric", Metric)
                .component("DataCalendar", DataCalendar)
                .component("Actions", Actions)
                .component("ShareButton", ShareButton)
                .component("Ratings", Ratings)
                .component("DataTable", DataTable)
                .component("MiniDataTable", MiniDataTable)
                .component("HeaderText", HeaderText)
                .component("FlashText", FlashText)
                .component("BackButton", BackButton)
                // new
                .component("globalSearchNew", globalSearchNew)
                .component("globalLocationSearch", globalLocationSearch)
                .component("tabs", Tabs)
                .component("tab", Tab)
                .component("MenuPopup", MenuPopup)
                .component("CategoryPopup", CategoryPopup)
                .component("ItemPopup", ItemPopup)
                .component("LoadingIcon", LoadingIcon)
                .component("EmptySpace", EmptySpace)
                .component("CreateIcon", CreateIcon)
                .mixin({
                    methods: {
                        route,
                    },
                })
                .use(InertiaPlugin)
                .use(AppGlobalMethods)
                .use(ZiggyVue, Ziggy)
                .use(store)
                .mount(el)
        );
    },
});

InertiaProgress.init({
    color: "#8cc63e",
    includeCSS: true,
    showSpinner: true,
});
