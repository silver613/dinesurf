<template>
    <div class="flex flex-col mb-3">
        <div
            class="favoriteContainer justify-between w-full border-b-2 p-5 pb-3"
        >
            <div class="flex">
                <div
                    :style="
                        'background-image: url(' +
                        reservationData.vendor.profile_photo_url +
                        ')'
                    "
                    class="bg-fav"
                >
                    <!-- <img src="/images/product4.jpg" alt="" width="60" class="img bg-grey-500 img-responsive"> -->
                </div>
                <div class="flex flex-col pl-2">
                    <span class="font-bold text-xs">{{
                        reservationData.vendor.company_name
                    }}</span>
                    <span class="text-xs text-grey-300">{{
                        `${reservationData.formated_date} at  ${reservationData.formated_time}`
                    }}</span>
                    <span class="text-xs text-grey-300">{{
                        ` Table for ${reservationData.party_size}   ${
                            reservationData.party_size > 1 ? "people" : "person"
                        }`
                    }}</span>

                    <span
                        class="font-bold text-xs text-green-700"
                        v-if="reservationData.status == 'approved'"
                        >{{ reservationData.status }}
                    </span>
                    <span
                        class="font-bold text-xs text-yellow-500"
                        v-if="reservationData.status == 'pending'"
                        >{{ reservationData.status }}</span
                    >
                    <span
                        class="font-bold text-xs text-red-700"
                        v-if="
                            reservationData.status == 'failed' ||
                            reservationData.status == 'cancelled'
                        "
                        >{{ reservationData.status }}</span
                    >

                    <!-- <span class="font-bold text-xs text-red-700">Past: {{ reservation.past ? "yes" : "no" }}</span>  -->
                    <span
                        v-if="reservation.seating_preferences"
                        class="font-bold text-xs text-red-700"
                        >Seating Preferences:
                        {{ reservation.seating_preferences }}</span
                    >
                    <span
                        class="font-bold text-xs text-red-700"
                        v-if="reservation.note"
                        >Note: {{ reservation.note }}</span
                    >
                    <p
                        v-if="reservation.transaction"
                        class="text-sm text-blogtext-gray mb-2"
                    >
                        Deposit:
                        {{
                            numberWithCommas(
                                reservation.transaction.amount,
                                reservation.transaction.currency
                            )
                        }}
                    </p>
                    <span v-html="updating"></span>
                </div>
            </div>

            <div class="btnContainerfavorite mt-5">
                <!-- <a   :href="route('restaurants.index', [{ id: vendorData.id }])" class="bg-red-500 text-sm font-bold text-xs text-red-700 p-3 text-white  rounded ">
                                              Reservation Now
                             </a> -->

                <div
                    class="flex flex-wrap mb-3"
                    v-if="reservation.is_invitation"
                >
                    <button
                        class="btn btn-md btn-dark-blue text-xs mr-2 h-8"
                        @click="updateInvitation('accepted')"
                    >
                        Accept
                    </button>
                    <button
                        class="btn btn-md btn-red text-xs mr-2 h-8"
                        @click="updateInvitation('declined')"
                    >
                        Decline
                    </button>
                    <!-- <button class="btn btn-md btn-white-gray h-8" @click="updateInvitation('remove')">Remove</button> -->
                </div>

                <div
                    class="md:flex flex-col flex-wrap text-xs md:flex-row mb-3"
                    v-if="!reservation.past && !reservation.is_invitation"
                >
                    <Link
                        :href="
                            route('reservation.edit', [
                                { id: reservationData.id },
                            ])
                        "
                        class="btn btn-md mr-5 btn-blue text-xs h-8"
                    >
                        Modify</Link
                    >
                    <button
                        v-if="reservationData.status != 'cancelled'"
                        @click="
                            toggleReservation(reservationData.id, 'cancelled')
                        "
                        class="btn btn-md btn-red text-xs mr-5 h-8"
                    >
                        Cancel
                    </button>
                    <button
                        v-if="reservationData.status == 'cancelled'"
                        @click="
                            toggleReservation(reservationData.id, 'pending')
                        "
                        class="btn btn-md btn-dark-blue text-xs mr-5"
                    >
                        Undo
                    </button>
                    <div class="top-25 md:mt-3">
                        <a
                            :href="
                                'https://calendar.google.com/calendar/render?' +
                                googlecalendar
                            "
                            target="_blank"
                            class="btn btn-md btn-white-gray mr-5 text-xs h-8"
                        >
                            <i class="far fa-calendar-alt"></i> Add to Calendar
                        </a>
                    </div>

                    <button
                        @click="exampleModalShowing = true"
                        class="btn btn-md btn-white-gray top-25 md:mt-0 text-xs h-8"
                    >
                        <i class="fas fa-user-friends"></i> Invite guest
                    </button>
                </div>
            </div>
        </div>

        <!-- Invitations -->
        <jet-modal
            :show="exampleModalShowing"
            @close="exampleModalShowing = false"
        >
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 capitalize">
                    Invite Guest To Reservation
                </h3>
                <form @submit.prevent="inviteGuest()" class="my-5">
                    <div class="flex flex-col">
                        <p class="font-bold mb-3">
                            You must invite either an E-Mail or Phone Number
                        </p>
                        <div class="w-full">
                            <span class="font-bold">E-Mail:</span>
                            <!-- <jet-label for="email" value="E-Mail" /> -->
                            <input
                                type="email"
                                class="form-control mb-3 mt-1 w-full"
                                v-model="email"
                            />
                        </div>
                        <div class="w-full">
                            <!-- <jet-label for="phone" value="Phone Number" /> -->
                            <span class="font-bold">Phone Number:</span>
                            <phone-input
                                class="w-full phone-input mt-1"
                                v-model="phone"
                                :modelName="'phone'"
                                @setValue="setPhone"
                                placeholder="*Phone"
                            ></phone-input>
                        </div>
                        <textarea
                            v-model="note"
                            class="form-control mb-3"
                            name="note"
                            id="note"
                            cols="30"
                            rows="2"
                            placeholder="Personal Note (optional)"
                        ></textarea>
                        <div class="flex text-center my-5" v-if="responseText">
                            {{ responseText }}
                        </div>
                        <button
                            type="submit"
                            class="btn btn-md btn-red"
                            :disabled="responseText"
                        >
                            Invite
                        </button>
                    </div>
                </form>
                <div class="flex flex-col w-full">
                    <h3
                        class="text-lg mb-3 font-medium text-gray-900 capitalize"
                    >
                        Invitations
                    </h3>
                    <template v-if="reservationData.invitations">
                        <div v-if="reservationData.invitations.length > 0">
                            <div class="flex mb-3 pl-7">
                                <div class="w-4/6">
                                    <h3
                                        class="text-sm font-medium text-gray-900 capitalize"
                                    >
                                        Email / Phone
                                    </h3>
                                </div>
                                <div class="w-2/6 md:w-1/6 text-center">
                                    <h3
                                        class="text-sm font-medium text-gray-900 capitalize"
                                    >
                                        Status
                                    </h3>
                                </div>
                            </div>
                            <div class="h-40 overflow-y-scroll show-scrollbar">
                                <ol class="dec pl-7">
                                    <li
                                        v-for="(
                                            invitation, index
                                        ) in reservationData.invitations
                                            .slice()
                                            .reverse()"
                                        :key="index"
                                        class="mb-3"
                                    >
                                        <div
                                            class="flex flex-wrap items-center"
                                        >
                                            <div
                                                class="w-3/6 md:w-4/6 overflow-x-scroll pr-3"
                                            >
                                                {{ invitation.email }}
                                                {{ invitation.phone }}
                                            </div>
                                            <div
                                                class="w-2/6 md:w-1/6 text-center"
                                            >
                                                <span
                                                    class="font-bold text-green-700"
                                                    v-if="
                                                        invitation.status ==
                                                        'accepted'
                                                    "
                                                    >{{
                                                        invitation.status
                                                    }}</span
                                                >
                                                <span
                                                    class="font-bold text-yellow-500"
                                                    v-if="
                                                        invitation.status ==
                                                        'pending'
                                                    "
                                                    >{{
                                                        invitation.status
                                                    }}</span
                                                >
                                                <span
                                                    class="font-bold text-red-700"
                                                    v-if="
                                                        invitation.status ==
                                                            'declined' ||
                                                        invitation.status ==
                                                            'cancelled'
                                                    "
                                                    >{{
                                                        invitation.status
                                                    }}</span
                                                >
                                            </div>
                                            <div class="w-1/6 flex">
                                                <i
                                                    class="fas fa-envelope mr-5 cursor-pointer"
                                                    title="Resend Invitation"
                                                    @click="
                                                        resendInvitation(
                                                            invitation.id
                                                        )
                                                    "
                                                ></i>
                                                <i
                                                    class="fas fa-trash text-red-500 cursor-pointer delete-guest"
                                                    title="Delete Invitation"
                                                    @click="
                                                        deleteGuest(
                                                            invitation.id
                                                        )
                                                    "
                                                ></i>
                                            </div>
                                        </div>
                                    </li>
                                </ol>
                            </div>
                        </div>
                        <div class="flex" v-else>No Invitations yet</div>
                    </template>
                    <div class="flex" v-else>No Invitations yet</div>
                </div>
            </div>
        </jet-modal>
        <!-- End Invitations -->
    </div>
</template>

<script>
import tippy from "tippy.js";
import "tippy.js/dist/tippy.css"; // optional for styling
import moment from "moment";

export default {
    props: ["reservation"],
    data() {
        return {
            exampleModalShowing: false,
            reservationData: this.reservation,
            updating: null,
            filter: false,
            responseText: null,
            email: null,
            phone: null,
            note: null,
        };
    },
    computed: {
        googlecalendar() {
            const params = new URLSearchParams({
                action: "TEMPLATE",
                dates:
                    moment(this.reservation.iso_start)
                        .utc()
                        .format("YYYYMMDD[T]HHmmss[Z]") +
                    "/" +
                    moment(this.reservation.iso_end)
                        .utc()
                        .format("YYYYMMDD[T]HHmmss[Z]"),
                details:
                    this.capitalizeWords(this.reservation.vendor.company_name) +
                    " Reservation on Dinesurf\n\n" +
                    route("reservations", { id: this.reservation.id }),
                location: this.reservation.vendor.company_address,
                text:
                    this.capitalizeWords(this.reservation.vendor.company_name) +
                    " Reservation on Dinesurf\n\n",
            });
            return params.toString();
        },
        outLookcalendar() {
            j;
            const params = new URLSearchParams({
                body: "Learn all about the rules of the Motorway and how to access the fast lane.\n\nhttps://en.wikipedia.org/wiki/Gridlock_(Doctor_Who)",
                enddt: "2022-01-12T20:00:00+00:00",
                location: "New Earth",
                path: "/calendar/action/compose",
                rru: "addevent",
                startdt: "2022-01-12T18:00:00+00:00",
                subject: "Welcome to the Motorway",
            });
            return params.toString();
        },
        office365calendar() {
            const params = new URLSearchParams({
                body: "Learn all about the rules of the Motorway and how to access the fast lane.\n\nhttps://en.wikipedia.org/wiki/Gridlock_(Doctor_Who)",
                enddt: "2022-01-12T20:00:00+00:00",
                location: "New Earth",
                path: "/calendar/action/compose",
                rru: "addevent",
                startdt: "2022-01-12T18:00:00+00:00",
                subject: "Welcome to the Motorway",
            });
            return params.toString();
        },
    },
    methods: {
        capitalizeWords(string) {
            return string.replace(/(?:^|\s)\S/g, function (a) {
                return a.toUpperCase();
            });
        },
        toggleReservation(id, status) {
            if (status == "cancelled") {
                var r = confirm(
                    "Are you sure you want to cancel this reservation?"
                );
            } else {
                var r = confirm(
                    "Are you sure you want to undo this reservation status?"
                );
            }

            if (!r) {
                return;
            }

            this.updating = "Updating...";

            fetch(route("cancel-reservation", { id: id, status: status }))
                .then((result) => {
                    return result.json();
                })
                .then((result) => {
                    if (result.success) {
                        this.updating = "Updated";
                        var vm = this;

                        setTimeout(
                            () => {
                                vm.updating = null;
                            },
                            500,
                            vm
                        );
                        this.getReservation();
                    }
                })
                .catch((err) => {
                    this.handleApiError(err);
                });
        },
        getReservation() {
            fetch(route("get-reservation", { id: this.reservationData.id }))
                .then((result) => {
                    return result.json();
                })
                .then((result) => {
                    console.log("result: ", result);
                    if (result.success) {
                        console.log("reservation: ", result.data);
                        this.reservationData = result.data.reservation;
                    }
                })
                .catch((err) => {
                    this.handleApiError(err);
                });
        },
        inviteGuest() {
            this.responseText = "Inviting...";

            axios
                .post(route("invite-guest"), {
                    reservation_id: this.reservationData.id,
                    email: this.email,
                    phone: this.phone,
                    note: this.note,
                })
                .then((result) => {
                    this.responseText = "Invited.";

                    this.email = null;
                    this.note = null;

                    this.getReservation();
                    let vm = this;
                    setTimeout(
                        function () {
                            vm.responseText = null;
                        },
                        500,
                        vm
                    );
                })
                .catch((err) => {
                    this.responseText = null;
                    this.handleApiError(err);
                });
        },
        deleteGuest(id) {
            var r = confirm("Are you sure you want to delete this invitation?");

            if (!r) {
                return;
            }

            this.responseText = "Deleting...";

            axios(route("delete-guest", { id: id }))
                .then((result) => {
                    this.responseText = "Deleted.";
                    this.getReservation();
                    let vm = this;
                    setTimeout(
                        function () {
                            vm.responseText = null;
                        },
                        500,
                        vm
                    );
                })
                .catch((err) => {
                    this.responseText = null;
                    this.handleApiError(err);
                });
        },
        updateInvitation(status) {
            if (status == "accepted") {
                var statusMsg = "accept";
            }

            if (status == "remove") {
                var statusMsg = "remove";
            }

            if (status == "declined") {
                var statusMsg = "decline";
            }

            var r = confirm(
                "Are you sure you want to " + statusMsg + " this invitation?"
            );

            if (!r) {
                return;
            }

            var formData = new FormData();
            formData.append("status", status);
            formData.append(
                "invitation_id",
                this.reservationData.invitation.id
            );

            axios
                .post(route("update-invitation"), formData)
                .then((result) => {
                    this.getReservation();
                })
                .catch((err) => {
                    this.handleApiError(err);
                });
        },
    },
    mounted() {
        tippy(".undone", {
            content: "Coming Soon",
        });

        tippy(".delete-guest", {
            content: "Delete Invitation",
        });
    },
};
</script>
