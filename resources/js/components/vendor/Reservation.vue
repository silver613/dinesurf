<template>
    <div
        class="flex flex-col cursor-pointer justify-start flex-shrink-0 mb-6 space-x-4 border-gray-300 border-t-2 p-5 hover:bg-gray-100"
    >
        <div v-if="reservation && userData" class="flex items-start">
            <div class="w-16">
                <img
                    :src="userData.profile_photo_url"
                    alt="loc hair style"
                    class="h-12 w-auto"
                />
            </div>
            <div class="">
                <h4 class="text-lg text-blogtext-dark font-bold mb-2">
                    {{ userData.first_name }}
                    {{ userData.last_name }}
                </h4>
                <p
                    v-if="userData.email"
                    class="text-sm text-blogtext-gray mb-2"
                >
                    Email: {{ userData.email }}
                </p>
                <p
                    v-if="userData.phone_number"
                    class="text-sm text-blogtext-gray mb-2"
                >
                    Phone: {{ userData.phone_number }}
                </p>
                <p class="text-sm text-blogtext-gray mb-2">
                    {{ reservationData.formated_date }}
                </p>
                <p class="text-sm text-blogtext-gray mb-2">
                    <!-- From {{ reservationData.formated_start_time }} to
          {{ reservationData.formated_end_time }} -->

                    Time: {{ reservationData.formated_time }}
                </p>
                <p class="text-sm text-blogtext-gray mb-2">
                    Party Size: {{ reservationData.party_size }}
                </p>
                <div class="mb-3 font-bold">
                    <span class="mr-2">Status: </span>
                    <span
                        class="font-bold text-green-700"
                        v-if="reservationData.status == 'approved'"
                        >{{ reservationData.status }}</span
                    >
                    <span
                        class="font-bold text-yellow-500"
                        v-if="reservationData.status == 'pending'"
                        >{{ reservationData.status }}</span
                    >
                    <span
                        class="font-bold text-red-700"
                        v-if="
                            reservationData.status == 'failed' ||
                            reservationData.status == 'cancelled'
                        "
                        >{{ reservationData.status }}</span
                    >
                </div>
                <p class="text-sm text-blogtext-gray mb-2">
                    Seating Preferances: [{{ reservation.seating_preferences }}]
                </p>
                <p class="text-sm text-blogtext-gray mb-2">
                    Past: {{ reservation.past ? "yes" : "no" }}
                </p>
                <p class="text-sm text-blogtext-gray mb-2">
                    Notes: {{ reservation.note }}
                </p>
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
                <p>
                    <span v-html="updating"></span>
                </p>
                <p class="text-sm text-blogtext-gray mb-2"></p>
            </div>
            <div v-if="!reservation.past" class="md:flex hidden pl-5">
                <select
                    v-model="option"
                    @change="toggleReservation(reservationData.id, option)"
                >
                    <option value="null">Actions</option>
                    <option value="approved">Approve</option>
                    <option value="cancelled">Cancel</option>
                    <option value="un-approve">Make Pending</option>
                    <!-- <option value="un-cancel">Un Cancel</option> -->
                </select>
            </div>
        </div>
        <div v-if="!reservation.past" class="md:hidden flex">
            <select
                v-model="option"
                @change="toggleReservation(reservationData.id, option)"
            >
                <option value="null">Actions</option>
                <option value="approved">Approve</option>
                <option value="cancelled">Cancel</option>
                <option value="un-approve">Make Pending</option>
                <!-- <option value="un-cancel">Un Cancel</option> -->
            </select>
        </div>
    </div>
</template>

<script>
export default {
    props: ["reservation"],
    data() {
        return {
            option: null,
            reservationData: this.reservation,
            userData: this.reservation.user
                ? this.reservation.user
                : this.reservation.guest,
            updating: null,
        };
    },
    methods: {
        toggleReservation(id, status) {
            if (!status) {
                return;
            }

            var action = status;
            var ids = [];
            ids.push(id);

            if (!action) {
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

            if (!r) {
                return;
            }

            this.$inertia
                .post(
                    route("vendors.toggle-reservation", {
                        ids: ids,
                        status: action,
                    }),
                    {},
                    {
                        preserveScroll: true,
                    }
                )
                .then((result) => {
                    this.getReservation();
                })
                .catch((err) => {});
        },
        getReservation() {
            fetch(route("get-reservation", { id: this.reservationData.id }))
                .then((result) => {
                    return result.json();
                })
                .then((result) => {
                    console.log("result: ", result);
                    if (result.success) {
                        this.reservationData = result.data.reservation;
                        this.userData = result.data.user;
                    }
                })
                .catch((err) => {
                    this.handleApiError(err);
                });
        },
    },
};
</script>
