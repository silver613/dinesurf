<template>
    <vendor-layout>
        <template #header>
            <header-text :title="title" />
        </template>

        <div
            class="bg-white md:pl-8 pl-4 flex flex-col justify-between md:flex-row"
        >
            <ul
                class="text-base font-bold text-center text-gray-500 rounded-lg flex spacing-2 dark:divide-gray-700 dark:text-gray-400 md:w-auto w-full mt-5 md:mt-0"
            >
                <li class="w-20 mr-4">
                    <a
                        href="javascript:void(0)"
                        @click="goToView('table')"
                        class="inline-block w-full focus:outline-none rounded-none text-xs md:text-sm pb-2"
                        :class="
                            view == 'table'
                                ? 'text-dinesurf-green-400 active border-b font-light  border-b-dinesurf-green-400'
                                : 'text-gray-400 font-light'
                        "
                        aria-current="page"
                        >Table
                    </a>
                </li>
                <li class="w-20">
                    <a
                        href="javascript:void(0)"
                        @click="goToView('calendar')"
                        class="inline-block w-full focus:outline-none rounded-none text-xs md:text-sm pb-2"
                        :class="
                            view == 'calendar'
                                ? 'text-dinesurf-green-400 active border-b  font-light border-b-dinesurf-green-400'
                                : 'text-gray-400 font-light'
                        "
                        >Calendar</a
                    >
                </li>
            </ul>
            <div
                class="flex flex-col md:mt-0 mt-8 md:pr-0 pr-4"
                id="reserveFormInfo"
            >
                <div class="bg-[#f4f4f4] flex justify-between">
                    <span class="text-xs p-2 border">View your reservation link page</span>
                    <div class="flex">
                        <a
                            :href="reservationFormLink"
                            target="_blank"
                            class="bg-dinesurf-green-400 p-2"
                        >
                            <i class="fa fa-link text-white"></i
                        ></a>
                        <button
                            @click="copyToClipboard('ctn-link')"
                            id="copyLink"
                            class="bg-white border border-dinesurf-red-400 p-2"
                        >
                            <i class="fa fa-copy text-dinesurf-red-400"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="flex flex-col">
                <div
                    class="md:pr-8 xs:w-full mt-5 md:mt-0"
                    v-if="type != 'upcoming'"
                >
                    <flash-text
                        :title="'You cannot change the status of past reservations'"
                    />
                </div>
            </div>
        </div>

        <input
            type="text"
            :value="this.reservationFormLink"
            name="ctn-link"
            placeholder="copy link"
            class="opacity-0 h-1 focus:outline-none"
            id="ctn-link"
        />

        <!-- Data Table -->
        <data-table
            v-if="view == 'table'"
            :isDropDown="true"
            :models="models"
            :loading="loading"
            :loaded="loaded"
            :title="'reservation'"
            :extendedTitle="title"
            :filters="filters"
            :columns="columns"
            :actions="actions"
            :rows="rows"
            @runAction="runAction"
            @loadData="loadData"
            :createLink="route('vendors.create-reservation')"
            :indexLink="route('vendors.reservations')"
            :viewLink="route('vendors.reservation')"
            :externalFilters="{
                type: type,
                view: view,
                status: status,
                type: type,
                reservation_start_date: reservation_start_date,
                reservation_end_date: reservation_end_date,
            }"
        >
            <template #outerFilters>
                <!-- <div class="flex flex-wrap bg-white"> -->
                <div class="flex flex-col w-[48%] mr-3 mb-3">
                    <label
                        for="status"
                        class="block text-sm font-light text-gray-400"
                        >Status:</label
                    >

                    <select
                        id="status"
                        name="status"
                        v-model="status"
                        class="block form-control"
                    >
                        <option value=""></option>
                        <option>approved</option>
                        <option>pending</option>
                        <option>cancelled</option>
                    </select>
                </div>
                <div class="flex flex-col w-[48%] mb-3">
                    <label
                        for="status"
                        class="block text-sm font-light text-gray-400"
                        >Type:</label
                    >

                    <select
                        id="type"
                        name="type"
                        v-model="type"
                        class="block form-control"
                    >
                        <option value=""></option>
                        <option>upcoming</option>
                        <option>past</option>
                    </select>
                </div>
                <div class="mb-3 w-[48%] mr-3">
                    <label class="block text-sm font-light text-gray-400"
                        >Reservation Start Date:</label
                    >
                    <input
                        v-model="reservation_start_date"
                        name="reservation_start_date"
                        id="reservation_start_date"
                        class="block form-control w-full"
                        type="date"
                    />
                </div>
                <div class="mb-3 w-[48%]">
                    <label class="block block text-sm font-light text-gray-400"
                        >Reservation End Date:</label
                    >
                    <input
                        v-model="reservation_end_date"
                        name="reservation_end_date"
                        id="reservation_end_date"
                        class="block form-control w-full"
                        type="date"
                    />
                </div>
                <!-- </div> -->
            </template>

            <template #notFound>
                <div class="max-w-full">
                    <empty-space
                        :title="'Better Than Google Sheets'"
                        :description="'We help you send confirmation text and email to reduce No-shows. Guests on Dinesurf can make a reservation and appear here, you can also add in reservation records from other sources like phone calls.'"
                        :page="'reservation'"
                        :link="route('vendors.create-reservation')"
                        :image="'/images/empty_images/1.png'"
                    />
                </div>
            </template>
        </data-table>
        <!-- End Send Emails -->

        <!-- Data calendar -->
        <data-calendar
            v-if="view == 'calendar'"
            :models="models.data"
            :initialDate="filters.date"
            :initialDates="dates_with_reservations"
        ></data-calendar>
        <!-- End Data calendar -->

        <!-- Send Emails -->
        <send-email
            :showEmailModal="showEmailModal"
            :backendActionData="actionData"
        ></send-email>
        <!-- End Send Emails -->

        <!-- Send Texts -->
        <send-text
            :showTextModal="showTextModal"
            :backendActionData="actionData"
        ></send-text>
        <!-- End Send Texts -->
    </vendor-layout>
</template>

<script>
import VendorLayout from "@/Layouts/VendorLayout.vue";
import tippy from "tippy.js";
import "tippy.js/dist/tippy.css"; // optional for styling
export default {
    components: {
        VendorLayout,
    },
    props: {
        title: String,
        filters: Object,
    },
    data() {
        return {
            models: {
                data: [],
                links: [],
                total: 0,
            },
            reservationFormLink: route("reserve", {
                slug: this.$page.props.auth.vendor?.slug
                    ? this.$page.props.auth.vendor?.slug
                    : this.$page.props.auth.vendor?.company_name,
            }),
            loading: true,
            loaded: false,
            vendor: this.$page.props.auth.vendor,
            dates_with_reservations: [],
            view: "table",
            params: {},
            actionData: {},
            showEmailModal: false,
            showTextModal: false,
            status: this.filters.status,
            type: this.filters.type,
            reservation_start_date: this.filters.reservation_start_date,
            reservation_end_date: this.filters.reservation_end_date,
            columns: [
                {
                    name: "created_at",
                    db_name: "created_at",
                    sortable: true,
                },
                {
                    name: "user",
                },
                {
                    name: "phone",
                    db_name: "phone",
                },
                {
                    name: "date",
                    db_name: "date",
                    sortable: true,
                },
                {
                    name: "time",
                    db_name: "time",
                    sortable: true,
                },
                {
                    name: "party size",
                    db_name: "party_size",
                    sortable: true,
                },
                {
                    name: "status",
                },
                {
                    name: "deposit",
                },
                {
                    name: "sp",
                    db_name: "seating_preferences",
                },
            ],
            actions: [
                {
                    value: "approved",
                    name: "Approve",
                },
                {
                    value: "cancelled",
                    name: "Cancel",
                },
                {
                    value: "un-approve",
                    name: "Make Pending",
                },
                {
                    value: "email_users",
                    name: "Send E-Mail",
                },
                {
                    value: "text_users",
                    name: "Send Text",
                },
            ],
        };
    },
    computed: {
        rows() {
            var modelRows = [];
            this.models.data.forEach((el) => {
                var statusColor = this.getModelStatusColor(el);
                modelRows.push([
                    this.formatDateTime(el.created_at),
                    this.getUser(el),
                    el.phone,
                    el.formated_date,
                    el.formated_time,
                    el.party_size,
                    statusColor,
                    this.numberWithCommas(
                        el.transaction?.amount,
                        el.transaction?.currency
                    ),
                    el.seating_preferences,
                ]);
            });
            return modelRows;
        },
    },
    methods: {
        copyToClipboard(id) {
            var copyText = document.getElementById(id);
            copyText.select();
            document.execCommand("copy");
            toastr.success("Link copied to clipboard");
        },
        getModelStatusColor(reservation) {
            // tippy(`#${reservation.user}`, {
            //     content: "Add Spots in your restaurant that Users can choose when making a Reservation"
            // });
            if (reservation.status == "approved") {
                return (
                    `<span  title="${
                        reservation.past
                            ? "past reservation"
                            : "incoming reservation"
                    }"  id="${
                        reservation.user
                    }" class="font-light capitalize bg-green-100  text-green-600 p-2 rounded "  >` +
                    reservation.status +
                    "</span>"
                );
            }
            if (reservation.status == "pending") {
                return (
                    `<span  title="${
                        reservation.past
                            ? "past reservation"
                            : "incoming reservation"
                    }"  id="${
                        reservation.user
                    }" class="font-light capitalize bg-amber-100  text-amber-600 p-2 rounded "  >` +
                    reservation.status +
                    "</span>"
                );
            }
            if (
                reservation.status == "failed" ||
                reservation.status == "cancelled"
            ) {
                return (
                    `<span  title="${
                        reservation.past
                            ? "past reservation"
                            : "incoming reservation"
                    }"  id="${
                        reservation.user
                    }" class="font-light capitalize bg-red-100  text-red-600 p-2 rounded "  >` +
                    reservation.status +
                    "</span>"
                );
            }
            return;
        },
        runAction(data) {
            var action = data.action;
            var ids = data.ids;
            var all = data.all;
            // alert("run action " + action + " for models " + [...ids]);
            if (!action) {
                return;
            }
            if (ids.length <= 0) {
                alert(
                    "Please select a Reservation or some reservations you want send an E-Mail"
                );
                return;
            }
            if (action == "un-approve" || action == "un-cancel") {
                var r = confirm(
                    "Are you sure you want to set this Reservation to pending?"
                );
                action = "pending";
            }
            if (action == "cancelled") {
                var r = confirm(
                    "Are you sure you want to cancel this reservation?"
                );
            }
            if (action == "approved") {
                var r = confirm(
                    "Are you sure you want to approve this reservation?"
                );
            }
            if (action == "email_users") {
                this.actionData = {
                    ids: ids,
                    action: action,
                    allMatching: all,
                    filters: this.filters,
                    subject: null,
                    message: null,
                    banner: null,
                    action_model: "reservation",
                };
                this.showEmailModal = true;
                this.showTextModal = false;
            }
            if (action == "text_users") {
                this.actionData = {
                    ids: ids,
                    action: action,
                    allMatching: all,
                    filters: this.filters,
                    type: this.type,
                    message: null,
                    action_model: "reservation",
                };
                this.showEmailModal = false;
                this.showTextModal = true;
            }
            if (!r) {
                return;
            }
            if (
                action == "un-approve" ||
                action == "un-cancel" ||
                action == "cancelled" ||
                action == "approved" ||
                action == "pending"
            ) {
                this.$inertia.post(
                    route("vendors.toggle-reservation"),
                    {
                        ids: ids,
                        action: action,
                        allMatching: all,
                        filters: this.filters,
                        type: this.type,
                    },
                    {
                        onFinish: (visit) => {
                            this.loadData();
                        },
                    }
                );
            }
        },
        goToView(view) {
            this.$inertia.get(
                route("vendors.reservations"),
                {
                    view: view,
                },
                {
                    replace: true,
                }
            );
        },
        loadData() {
            const urlSearchParams = new URLSearchParams(window.location.search);
            this.params = Object.fromEntries(urlSearchParams.entries());
            this.view = this.params.view ? this.params.view : "table";
            this.loaded = false;
            this.loading = true;
            this.models = {
                data: [],
                links: [],
                total: 0,
            };
            this.dates_with_reservations = [];
            axios
                .get(route("data.vendors.reservations", { ...this.params }))
                .then((result) => {
                    this.models = result.data.models;
                    this.dates_with_reservations =
                        result.data.dates_with_reservations;
                    this.vendor = result.data.vendor;
                    this.loading = false;
                    this.loaded = true;
                })
                .catch((err) => {
                    this.loaded = false;
                    this.loading = false;
                    this.dates_with_reservations = [];
                });
        },
    },
    mounted() {
        console.log(
            "this.$page.props.auth.vendor.slug:",
            this.$page.props.auth.vendor
        );

        tippy("#reserveFormInfo", {
            content:
                "Preview to see your personal  reservation form , you can also share this link or add to your socials bio",
        });
        this.loadData();
    },
};
</script>
