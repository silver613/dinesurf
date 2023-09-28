import LoadingCard from "@/components/LoadingCard.vue";
import store from "@/store/index.js";
import { config, mount } from "@vue/test-utils";
import { pickBy, throttle } from "lodash";
import axios from "./__mocks__/axios";

window.axios = axios;
window.route = (url = null) => {
    if (url) {
        return Ziggy.routes[url] ? Ziggy.routes[url].uri : url;
    } else {
        return {
            current: (url) => {
                return false;
            },
        };
    }
};

import { Ziggy } from "../ziggy";

const loadingComponent = (configs) =>
    mount(LoadingCard, {
        ...configs,
        mocks: {
            axios: axios,
        },
    });

config.global.provide = {
    store,
};

config.global.mocks = {
    numberWithCommas(x, code = null) {
        return code + " " + x;
    },
    getModelStatusColor(reservation) {
        if (reservation.status == "approved") {
            return "text-green-700";
        }

        if (reservation.status == "pending") {
            return "text-yellow-500";
        }

        if (
            reservation.status == "failed" ||
            reservation.status == "cancelled"
        ) {
            return "text-red-700";
        }

        return;
    },
    $t: () => "",
    route: (url = null) => {
        if (url) {
            return Ziggy.routes[url] ? Ziggy.routes[url].uri : url;
        } else {
            return {
                current: (url) => {
                    return false;
                },
            };
        }
    },
    pickBy: (obj) => pickBy(obj),
    throttle: (func, time) => throttle(func, time),
    usePage: {
        props: {
            value: {
                jetstream: {},
                user: {},
                vendor: {},
            },
        },
    },
    store,
    $inertia: {
        form: (data = null) => {
            return data;
        },
    },
    $page: {
        props: {
            jetstream: {},
            user: {},
        },
    },
};

config.global.stubs = {
    Link: "a",
    InertiaLink: "a",
};

jest.mock("@inertiajs/inertia-vue3", () => ({
    __esModule: true,
    ...jest.requireActual("@inertiajs/inertia-vue3"), // Keep the rest of Inertia untouched!
    useForm: () => ({
        /** Return what you need **/
        /** Don't forget to mock post, put, ... methods **/
    }),
    usePage: () => ({
        props: {
            value: {
                someSharedData: "something",
            },
        },
    }),
    form: () => {},
}));
