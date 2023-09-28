<template>
    <div
        class="auth-card reservation-auth-card mb-24 bg-white"
        style="
            margin-top: 0 !important;
            border-top: 0 !important;
            padding-top: 10px !important;
        "
    >
        <!-- <a :href="route('index')" v-if="extraTitle" target="_blank" class="branding-link">
      <div data-id="branding" class="branding">
        <div class="branding-head">powered by</div>
        <div class="branding-tail">Dinesurf</div>
      </div>
    </a> -->
        <div class="auth-card-header flex flex-col items-center">
            <h3 class="text-center">
                <span class="block text-base text-gray-500">
                    Make a Reservation with</span
                >
                <span v-if="extraTitle && vendor" class="text-xl">
                    {{ vendor.company_name }}</span
                >
            </h3>

            <a
                v-if="vendor.profile_photo_path"
                :href="route('restaurants.index', [{ id: vendor.id }])"
            >
                <img
                    class="fill-current h-36 w-auto object-contain mb-1"
                    :src="vendor.profile_photo_url"
                />
            </a>
            <a v-else :href="route('index')">
                <img
                    class="fill-current block h-7 w-auto object-contain mb-5"
                    src="/images/company_logo.png"
                />
            </a>
        </div>
        <div v-if="vendor.latest_deal" class="mb-3">
            <div class="reservation-deal">
                {{ vendor.latest_deal.title }}
            </div>
            <div
                class="reservation-deal text-left"
                v-if="vendor.latest_deal.description"
                v-html="vendor.latest_deal.description"
            ></div>
        </div>
        <div v-if="!vendor.open" class="text-red-600 text-lg">
            <p>Online Reservation is currently not available.</p>
            <p>Please contact the restaurant directly.</p>
        </div>
        <div
            class="auth-card-body text-left"
            :class="{
                'pointer-events-none opacity-50': !vendor.open,
            }"
        >
            <div class="text-center mb-5">
                <span>{{ responseMessage }}</span>
                <span class="text-green-800 font-bold text-lg">{{
                    responseSuccess
                }}</span>
                <span
                    class="text-red-700 font-bold text-lg"
                    v-html="responseFail"
                ></span>
                <i class="fas fa-spinner fa-spin" v-if="form.processing"></i>
            </div>
            <form
                @submit.prevent="submit"
                class="flex flex-col w-full"
                v-if="!regSuccess"
            >
                <div
                    class="w-full"
                    :class="{
                        'opacity-25 pointer-events-none': reservationData.id,
                    }"
                >
                    <jet-label
                        >Pick a Date:
                        <span class="ml-1">{{ formatedDate }}</span></jet-label
                    >
                    <div class="w-full flex justify-center">
                        <div class="flex flex-col">
                            <DateInput
                                v-model="form.date"
                                class="auth-card-input"
                                placeholder="Enter Date"
                                :enabledDays="enabledDays"
                                :minDate="'today'"
                                :inline="true"
                                :hideinput="true"
                                required
                            >
                            </DateInput>
                            <div class="flex justify-center">
                                <input
                                    type="date"
                                    v-model="form.date"
                                    class="opacity-0 h-0 -mt-12 mb-6 w-1"
                                    required
                                />
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col w-full mt-10" v-if="!user">
                        <jet-label>Your Name:</jet-label>
                        <input
                            id="name"
                            name="name"
                            v-model="form.name"
                            type="text"
                            class="form-control auth-card-input"
                            placeholder="Enter Full Name"
                            required
                        />
                    </div>
                    <div class="flex flex-col w-full" v-if="!user">
                        <jet-label>Email:</jet-label>
                        <input
                            id="email"
                            name="email"
                            v-model="form.email"
                            type="email"
                            class="form-control auth-card-input"
                            placeholder="Enter Email"
                            required
                        />
                    </div>

                    <div
                        class="flex flex-col w-full"
                        :class="{ 'mt-10': user }"
                    >
                        <p
                            v-if="vendor.open_time && vendor.close_time"
                            class="mb-1"
                        >
                            Open: {{ vendor.hours }}
                        </p>
                        <p v-if="vendor.open_time && vendor.close_time">
                            You can make reservation only during open hours
                        </p>
                        <jet-label
                            >Time:
                            <span class="ml-1">{{
                                formatedTime
                            }}</span></jet-label
                        >
                        <input
                            @input="checkVendorTime"
                            type="time"
                            v-model="form.time"
                            class="auth-card-input"
                            placeholder="Enter Time"
                            required
                            id="timeInput"
                        />
                    </div>

                    <!-- Phone Number -->
                    <div class="col-span-6 sm:col-span-4">
                        <jet-label for="phone" value="Phone Number" />
                        <phone-input
                            class="w-full phone-input"
                            v-model="phone"
                            :modelName="'phone'"
                            @setValue="setPhone"
                            required
                        ></phone-input>
                        <jet-input-error
                            :message="form.errors.phone"
                            class="mt-2"
                        />
                    </div>

                    <div
                        class="flex flex-col w-full mt-3"
                        v-if="vendor.seating_preferences.length > 0"
                    >
                        <jet-label>Seating preferences</jet-label>
                        <select
                            class="auth-card-input border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full"
                            v-model="form.seating_preferences"
                            name="party_size"
                            id="party_size"
                            required
                        >
                            <option
                                v-for="(
                                    item, index
                                ) in vendor.seating_preferences"
                                :key="index"
                            >
                                {{ item.name }}
                            </option>
                        </select>
                        <!-- <div class="flex flex-wrap">
                        <div class="flex items-center mx-3 pt-1 mb-3" v-for="(item, index) in vendor.seating_preferences" :key="index">
                            <input
                                class="form-control mr-1"
                                :id="'sp'+item.name"
                                :name="'sp'+item.name"
                                type="checkbox"
                                :value="item.name"
                                v-model="form.seating_preferences"
                            />
                            <label for="day2" class="capitalize"
                                >{{ item.name }}</label
                            >
                        </div>
                    </div> -->
                    </div>

                    <!-- Notes -->
                    <div class="flex flex-col w-full mb-5">
                        <jet-label for="note" value="Additional Information" />
                        <textarea
                            id="note"
                            class="auth-card-input auth-card-last-input"
                            v-model="form.note"
                            autocomplete="note"
                        ></textarea>
                        <jet-input-error
                            :message="form.errors.note"
                            class="mt-2"
                        />
                    </div>

                    <div class="flex flex-col w-full">
                        <jet-label :value="'Party Size:'"></jet-label>
                        <select
                            v-if="vendor.party_size"
                            class="auth-card-input border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full"
                            v-model="form.party_size"
                            name="party_size"
                            id="party_size"
                            required
                            @change="
                                form.deposit_amount =
                                    form.party_size *
                                    vendor.min_reservation_deposit
                            "
                        >
                            <option
                                v-for="(i, index) in vendor.party_size"
                                :key="index"
                            >
                                {{ i }}
                            </option>
                        </select>
                        <input
                            v-else
                            type="number"
                            v-model="form.party_size"
                            class="auth-card-input"
                            placeholder="Enter Party Size"
                            min="0"
                            required
                            @input="
                                form.deposit_amount =
                                    form.party_size *
                                    vendor.min_reservation_deposit
                            "
                        />
                    </div>

                    <!-- Reservation Deposit -->
                    <div
                        v-if="
                            vendor.accept_reservation_deposit &&
                            !vendor.reservation_deposit_required
                        "
                        class="col-span-6 flex items-center sm:col-span-4 cursor-pointer"
                    >
                        <jet-label
                            for="deposit"
                            value="Want to make a Deposit?"
                        />
                    </div>

                    <div
                        v-if="
                            vendor.accept_reservation_deposit &&
                            !vendor.reservation_deposit_required
                        "
                        class="col-span-6 sm:col-span-4 mb-6"
                    >
                        <div class="flex mt-1">
                            <div class="flex mr-4 items-center">
                                <input
                                    class="form-control mr-1 checkbox-hover"
                                    id="deposit1"
                                    name="deposit"
                                    type="radio"
                                    v-model="form.deposit"
                                    :value="true"
                                />
                                <label for="deposit1">Yes</label>
                            </div>
                            <div class="flex mr-4 items-center">
                                <input
                                    class="form-control mr-1 checkbox-hover"
                                    id="deposit2"
                                    name="deposit"
                                    type="radio"
                                    v-model="form.deposit"
                                    :value="false"
                                />
                                <label for="deposit2">No </label>
                            </div>
                        </div>
                    </div>

                    <!-- Deposit Amount -->
                    <template v-if="vendor.paystack_code">
                        <div
                            v-if="
                                vendor.reservation_deposit_required ||
                                form.deposit
                            "
                            class="form-group col-span-2 md:col-span-1"
                        >
                            <JetLabel
                                for="deposit_amount"
                                value="Deposit Amount"
                            />
                            <p>
                                = Party Size of:
                                {{ form.party_size ? form.party_size : 1 }} +
                                Transaction Charge of
                                {{
                                    numberWithCommas(
                                        finalFee,
                                        vendor.reservation_currency
                                    )
                                }}
                            </p>
                            <div class="relative w-full flex">
                                <div class="w-full">
                                    <div
                                        class="bg-gray-100 absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"
                                    >
                                        {{ currencySymbol }}
                                    </div>
                                    <JetInput
                                        class="bg-gray-100 !pl-7 w-full"
                                        id="deposit_amount"
                                        type="number"
                                        :min="vendor.min_reservation_deposit"
                                        required
                                        readonly
                                        :value="finalAmount"
                                    />
                                </div>

                                <!-- <select
                            name="currency"
                            id="currency"
                            class="!w-auto form-control"
                            v-model="form.deposit_currency"
                            disabled
                        >
                            <option
                                v-for="(item, index) in currencies"
                                :key="index"
                            >
                                {{ item.currency }}
                            </option>
                        </select> -->
                            </div>
                            <small class="text-ncdmb-black-200">{{
                                numberWithCommas(
                                    finalAmount,
                                    vendor.reservation_currency
                                )
                            }}</small>
                            <JetInputError
                                :message="form.errors['deposit_amount']"
                                class="mt-2"
                            />
                        </div>
                    </template>
                </div>

                <div class="text-center mb-5">
                    <span>{{ responseMessage }}</span>
                    <span class="text-green-800 font-bold text-lg">{{
                        responseSuccess
                    }}</span>
                    <span
                        class="text-red-700 font-bold text-lg"
                        v-html="responseFail"
                    ></span>
                    <i
                        class="fas fa-spinner fa-spin"
                        v-if="form.processing"
                    ></i>
                </div>
                <div class="w-full mt-5">
                    <button
                        type="submit"
                        class="btn-md btn-auth btn-red"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        Confirm Reservation
                    </button>
                </div>
            </form>
            <a
                :href="route('index')"
                v-if="extraTitle"
                target="_blank"
                class="w-full text-black flex flex-col items-center justify-center capitalize font-extrabold mt-10"
            >
                <div>powered by</div>
                <div class="flex">
                    <img src="/images/company_logo.jpg" class="h-8" />
                </div>
            </a>
        </div>

        <!-- Login -->
        <CardModal :showing="openLoginModal" @close="openLoginModal = false">
            <LoginCard
                @close="openLoginModal = false"
                :status="status"
                :canResetPassword="true"
                :intendedUrl="$page.props.page"
            ></LoginCard>
        </CardModal>
    </div>

    <JetModal :show="regSuccess" @close="close()">
        <!-- <template #content> -->

        <div class="flex flex-col items-center" v-if="cancellation">
            <h3>Cancellation Policy</h3>
            <div class="overflow-y-scroll h-64">
                <p class="p-5">
                    {{ vendor.cancellation_policy }}
                </p>
            </div>

            <button
                class="bg-dinesurf-red-100 text-dinesurf-red-500 items-center p-4 text-center"
                @click="cancellation = false"
            >
                Close
            </button>
        </div>
        <div class="flex flex-col items-center" v-else>
            <a
                v-if="!vendor.profile_photo_path"
                :href="route('restaurants.index', [{ id: vendor.id }])"
            >
                <img
                    class="fill-current h-[8rem] w-auto object-contain mb-1"
                    :src="vendor.profile_photo_url"
                />
            </a>
            <a v-else :href="route('index')">
                <img
                    class="fill-current block bg-red h-10 w-auto object-contain mb-5 mt-5"
                    src="/images/company_logo.png"
                />
            </a>

            <div class="flex flex-row items-center content-center">
                <img src="/images/success-icon.svg" class="h-6 mr-3" />
                <h4 class="text-center my-3 text-2xl">
                    Reservation Successful
                </h4>
            </div>

            <div class="bg-gray-100 w-80 md:w-[30rem] px-8 py-4">
                <h4 class="text-center mb-5">Reservation details</h4>
                <div class="flex md:flex-row flex-col justify-between">
                    <div class="flex flex-col mr-3">
                        <span>
                            <span class="text-[#94a3b8] mr-2">Email:</span>
                            <span v-if="user">{{ user.email }}</span>
                            <span v-else>{{ form.email }}</span>
                        </span>
                        <span>
                            <span class="text-[#94a3b8] mr-2">Name:</span>
                            <span v-if="user">{{
                                `${user?.first_name}, ${user?.last_name}`
                            }}</span>
                            <span v-else>{{ form.name }}</span>
                        </span>
                        <span>
                            <span class="text-[#94a3b8] mr-2 mt-3">Date:</span>
                            {{ formatedDate }}
                        </span>

                        <div class="mt-4 md:block hidden">
                            <a
                                :href="vendor.menu_link"
                                class="text-blue-500 cursor-pointer"
                            >
                                View Menu...
                            </a>
                        </div>
                    </div>

                    <div class="flex flex-col">
                        <!-- <span
              ><span class="text-[#94a3b8] mr-2">ID:</span
              >{{ reservationData.id }}
            </span> -->
                        <span
                            ><span class="text-[#94a3b8] mr-2">Time:</span
                            >{{ formatedTime }}
                        </span>
                        <span
                            ><span class="text-[#94a3b8] mr-2 mt-3"
                                >Party Size:</span
                            >
                            {{ form.party_size }}
                        </span>

                        <div class="mt-4">
                            <button
                                @click="handlePolicy()"
                                class="text-blue-500"
                            >
                                View Cancellation policy...
                            </button>
                            <div class="mt-4 md:hidden block">
                                <a
                                    :href="vendor.menu_link"
                                    class="text-blue-500 cursor-pointer"
                                >
                                    View Menu...
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div
            class="flex justify-around p-5 md:px-15 flex-wrap"
            v-if="reservationData"
        >
            <Link
                v-if="user"
                target="_blank"
                :href="route('reservation.edit', [{ id: reservationData.id }])"
                class="w-64 m-2 btn btn-md bg-dinesurf-green-500 text-white text-center"
            >
                Modify reservation
            </Link>
            <a
                target="_blank"
                :href="
                    'https://calendar.google.com/calendar/render?' +
                    googlecalendar
                "
                class="w-64 m-2 btn btn-md btn-white-gray text-center"
            >
                Add to calendar
            </a>
            <button
                v-if="user"
                class="w-64 m-2 btn btn-md h-8 btn-white-gray"
                @click="isInviteGuess = true"
            >
                Invite Guest
            </button>
            <button
                v-if="user"
                class="w-64 m-2 btn btn-md bg-dinesurf-red-300 text-[#656565]"
                @click="toggleReservation(reservationData?.id, 'cancelled')"
            >
                Cancel Reservation
            </button>
            <p class="font-bold mt-5" v-if="!user">
                To Modify, Cancel or Invite Guest to this Reservation, please
                login with your email:
                {{ form.email }}
                <a
                    v-if="reservationData"
                    :href="
                        route('login', {
                            intendedUrl: route('reservation', [
                                { id: reservationData.id },
                            ]),
                        })
                    "
                    class="text-blue-500 cursor-pointer"
                >
                    here
                </a>
            </p>
        </div>

        <!-- </template> -->
    </JetModal>

    <!-- Invitations -->
    <JetModal :show="isInviteGuess" @close="isInviteGuess = false">
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
                <h3 class="text-lg mb-3 font-medium text-gray-900 capitalize">
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
                                    <div class="flex flex-wrap items-center">
                                        <div
                                            class="w-3/6 md:w-4/6 overflow-x-scroll pr-3"
                                        >
                                            {{ invitation.email }}
                                            {{ invitation.phone }}
                                        </div>
                                        <div class="w-2/6 md:w-1/6 text-center">
                                            <span
                                                class="font-bold text-green-700"
                                                v-if="
                                                    invitation.status ==
                                                    'accepted'
                                                "
                                                >{{ invitation.status }}</span
                                            >
                                            <span
                                                class="font-bold text-yellow-500"
                                                v-if="
                                                    invitation.status ==
                                                    'pending'
                                                "
                                                >{{ invitation.status }}</span
                                            >
                                            <span
                                                class="font-bold text-red-700"
                                                v-if="
                                                    invitation.status ==
                                                        'declined' ||
                                                    invitation.status ==
                                                        'cancelled'
                                                "
                                                >{{ invitation.status }}</span
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
                                                    deleteGuest(invitation.id)
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
    </JetModal>
    <!-- End Invitations -->
</template>

<script>
import moment from "moment";
import JetAuthenticationCard from "@/Jetstream/AuthenticationCard.vue";
import LoginCard from "@/components/LoginCard.vue";
import JetAuthenticationCardLogo from "@/Jetstream/AuthenticationCardLogo.vue";
import JetButton from "@/Jetstream/Button.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetCheckbox from "@/Jetstream/Checkbox.vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetValidationErrors from "@/Jetstream/ValidationErrors.vue";
import JetInputError from "@/Jetstream/InputError.vue";
import axios from "axios";

export default {
    components: {
        JetAuthenticationCard,
        JetAuthenticationCardLogo,
        JetButton,
        JetInput,
        JetCheckbox,
        JetLabel,
        JetValidationErrors,
        LoginCard,
        JetInputError,
    },

    props: {
        vendor: {
            type: Object,
            required: true,
        },
        status: String,
        extraTitle: {
            type: Boolean,
            default: false,
        },
    },
    data() {
        return {
            currencies: [
                {
                    symbol: "₦‎",
                    currency: "NGN",
                },
                {
                    symbol: "$",
                    currency: "USD",
                },
            ],
            isInviteGuess: false,
            openLoginModal: false,
            reservationData: [],
            today: new Date(),
            cancellation: false,
            user: this.$page.props.user,
            email: null,
            phone: null,
            note: null,
            responseText: null,
            form: this.$inertia.form({
                name: null,
                email: null,
                party_size: 1,
                date: "",
                // start_time: "",
                // end_time: "",
                time: "",
                vendor_id: this.vendor.id,
                seating_preferences: [],
                phone: null,
                note: null,
                deposit: false,
                deposit_amount: this.vendor.min_reservation_deposit,
                deposit_currency: this.vendor.reservation_currency,
            }),
            regSuccess: false,
            // response
            responseSuccess: null,
            responseFail: null,
            responseMessage: null,
            phone: null,
            paymentMethod: "paystack",
            txnRef: null,
            txnSuccess: false,
        };
    },
    computed: {
        paystackkey() {
            return this.$page.props.paystackkey;
        },
        settings() {
            return this.$page.props.settings;
        },
        paystackFee() {
            let fee = 0;
            if (this.settings) {
                var percentOff =
                    this.form.deposit_amount *
                    this.settings.paystack_percentage_fee;
                var flat =
                    this.form.deposit_amount > this.settings.paystack_wave_under
                        ? this.settings.paystack_flat_fee
                        : 0;
                fee = flat + percentOff;
                if (fee > this.settings.paystack_cap_fee) {
                    fee = this.settings.paystack_cap_fee;
                }
            }
            return fee;
        },
        finalFee() {
            if (this.paymentMethod == "paystack") {
                return this.paystackFee;
            }
        },
        finalAmount() {
            return this.form.deposit_amount + this.finalFee;
        },
        transactionCharge() {
            var charge = 0;
            if (this.settings) {
                charge =
                    this.form.deposit_amount *
                    this.settings.reservations_percentage_fee;
                if (charge > this.settings.reservations_cap_fee) {
                    charge = this.settings.reservations_cap_fee;
                }
            }
            return charge;
        },
        currencySymbol() {
            var symbol = null;
            this.currencies.forEach((el) => {
                if (el.currency == this.vendor.reservation_currency) {
                    symbol = el.symbol;
                }
            });
            return symbol;
        },
        user() {
            return this.$page.props.user;
        },
        outLookcalendar() {
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

        googlecalendar() {
            const params = new URLSearchParams({
                action: "TEMPLATE",
                dates:
                    moment(this.reservationData?.iso_start)
                        .utc()
                        .format("YYYYMMDD[T]HHmmss[Z]") +
                    "/" +
                    moment(this.reservationData?.iso_end)
                        .utc()
                        .format("YYYYMMDD[T]HHmmss[Z]"),
                details:
                    // this.capitalizeWords(this.reservationData?.vendor.company_name) +
                    " Reservation on Dinesurf\n\n" +
                    route("reservations", { id: this.reservationData?.id }),
                location: this.reservationData?.vendor?.company_address,
                text:
                    this.capitalizeWords(
                        this.reservationData?.vendor?.company_name
                    ) + " Reservation on Dinesurf\n\n",
            });
            return params.toString();
        },
        formatedTime() {
            if (this.form.time) {
                return moment(this.form.time, "HH:mm:ss").format("hh:mm A");
            }
            return;
        },
        formatedDate() {
            if (this.form.date) {
                return moment(this.form.date).format("dddd, MMM Do YYYY");
            }
            return;
        },
        enabledDays() {
            var days = [];

            if (this.vendor) {
                this.vendor.days.forEach((el) => {
                    var dayIndex = el.id == 7 ? 0 : el.id;
                    if (!days.includes(dayIndex)) {
                        days.push(dayIndex);
                    }
                });
            }

            return days.length > 0 ? days : null;
        },
    },

    methods: {
        validateAmount() {
            if (this.form.deposit_amount < 0) {
                this.form.deposit_amount = this.form.deposit_amount * -1;
            }
        },
        submit() {
            if (
                this.vendor.paystack_code &&
                this.form.deposit &&
                !this.txnSuccess
            ) {
                return this.startTxn();
            }
            this.form.processing = true;
            this.responseSuccess = null;
            this.responseFail = null;
            this.responseMessage = "Making Reservation...";
            this.form.source = this.$page.props.tracking.source;
            this.form.campaign_id = this.$page.props.tracking.campaign_id;
            this.form.txnRef = this.txnRef;

            axios
                .post(route("api.make-reservation"), this.form)
                .then((result) => {
                    result = result.data;
                    this.reservationData = result.data;
                    toastr.success(result.message);
                    this.responseMessage = null;
                    this.responseSuccess = result.message + "!";
                    this.responseFail = null;
                    var vm = this;
                    if (gtag) {
                        gtag("event", "conversion", {
                            send_to: "AW-398677036/JmKrCI_S64cYEKyojb4B",
                            event_callback: () => {
                                console.log("sent reservation event to Google");
                            },
                        });
                    }

                    setTimeout(
                        function () {
                            vm.form.processing = false;
                            vm.regSuccess = true;
                        },
                        500,
                        vm,
                        result
                    );
                })
                .catch((err) => {
                    this.form.processing = false;
                    this.form.processing = false;
                    this.responseSuccess = null;
                    this.responseMessage = null;
                    this.responseFail = this.handleApiError(err);
                });
        },
        capitalizeWords(string) {
            return string?.replace(/(?:^|\s)\S/g, function (a) {
                return a.toUpperCase();
            });
        },
        handlePolicy() {
            this.cancellation = !this.cancellation;
        },

        inviteGuest() {
            this.responseText = "Inviting...";

            axios
                .post(route("invite-guest"), {
                    reservation_id: this.reservationData?.id,
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
                this.reservationData?.invitation.id
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

        reset() {
            this.isInviteGuess = false;
            this.openLoginModal = false;
            this.reservationData = [];
            this.today = new Date();
            this.cancellation = false;
            this.user = this.$page.props.user;
            this.email = null;
            this.phone = null;
            this.note = null;
            this.responseText = null;
            (this.form = this.$inertia.form({
                name: null,
                email: null,
                party_size: 1,
                date: "",
                // start_time: "",
                // end_time: "",
                time: "",
                vendor_id: this.vendor.id,
                seating_preferences: [],
                phone: null,
                note: null,
                deposit: false,
                deposit_amount: this.vendor.min_reservation_deposit,
                deposit_currency: this.vendor.reservation_currency,
            })),
                (this.regSuccess = false);
            // response
            this.responseSuccess = null;
            this.responseFail = null;
            this.responseMessage = null;
            this.phone = null;
            (this.paymentMethod = "paystack"), (this.txnRef = null);
            this.txnSuccess = false;

            if (this.$page.props.user) {
                this.form.phone = this.$page.props.user.phone_number;
                this.phone = this.unSetPhone(
                    this.$page.props.user.phone_number
                );
                this.form.user_id = this.$page.props.user.id;
                this.form.name =
                    this.$page.props.user.first_name +
                    " " +
                    this.$page.props.user.last_name;
                this.form.email = this.$page.props.user.email;
                this.email = this.$page.props.user.email;
            }

            if (this.vendor?.reservation_deposit_required) {
                this.form.deposit = true;
            }
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
                        this.responseSuccess = true;
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

        checkAuth() {
            if (!this.$page.props.user) {
                this.logCurrentUrl(document.location.toString());
                this.openLoginModal = true;
                return;
            }
            return;
        },

        close() {
            this.$inertia.reload();
            this.form.processing = false;
            this.responseMessage = null;
            this.regSuccess = false;
            this.reset();
            let vm = this;
            setTimeout(
                () => {
                    vm.responseSuccess = null;
                },
                3000,
                vm
            );
        },

        startTxn() {
            this.form.processing = true;
            this.responseMessage = "Starting Transaction...";

            axios
                .post("/start-transaction", {
                    paymentMethod: this.paymentMethod,
                    txn_type: "reservation",
                    amount: this.form.deposit_amount,
                    currency: this.form.deposit_currency,
                    email: this.form.email,
                    phone: this.form.phone,
                    name: this.form.name,
                    vendor_id: this.vendor.id,
                    user_id: this.form.user_id,
                    reservation_id: this.reservationData.id,
                })
                .then((result) => {
                    result = result.data;
                    toastr.success(result.message);
                    this.form.processing = true;
                    this.responseSuccess = null;
                    this.responseFail = null;
                    this.responseMessage =
                        result.message + "!, Confirming Payment...";
                    this.txnRef = result.data.reference;
                    this.form.email = result.data.email;
                    this.form.name = result.data.name;
                    this.pay();
                })
                .catch((err) => {
                    console.log(err);
                    this.form.processing = false;
                    this.responseSuccess = null;
                    this.responseMessage = null;
                    if (err.errors) {
                        this.responseFail = this.returnErrorMsg(err.errors);
                        return;
                    }
                    this.responseFail = err.message;
                });
        },
        pay() {
            if (this.paymentMethod == "paystack") {
                return this.payWithPaystack();
            }
            if (this.paymentMethod == "rave") {
                // return this.payWithRave();
            }
            if (this.paymentMethod == "paypal") {
                // console.log("paypal");
            }
            if (this.paymentMethod == "stripe") {
                // return this.payWithStripe();
            }
        },
        payWithPaystack() {
            this.form.processing = true;
            this.responseSuccess = null;
            this.responseFail = null;
            this.responseMessage = "Running Paystack...";

            let vm = this;

            var config = {
                key: this.paystackkey, // Replace with your public key
                email: this.form.email,
                amount: this.finalAmount * 100,
                firstname: this.form.name,
                ref: this.txnRef,
                currency: this.form.deposit_currency,
                subaccount: this.vendor.paystack_code,
                transaction_charge: this.transactionCharge,
                onClose: function () {
                    vm.form.processing = false;
                    vm.responseSuccess = null;
                    vm.responseFail = null;
                    vm.responseMessage = null;
                },
                callback: function (response) {
                    vm.responseSuccess = null;
                    vm.responseFail = null;
                    vm.responseMessage = "Payment complete!";
                    vm.txnSuccess = true;
                    vm.submit();

                    return;
                },
            };

            var handler = PaystackPop.setup(config);
            handler.openIframe();
        },
    },

    mounted() {
        var timeEl = document.getElementById("timeInput");

        if (timeEl && this.vdendor) {
            if (this.vendor.open_time) {
                timeEl.min = this.vendor.open_time;
            }

            if (this.vendor.closetime) {
                timeEl.max = this.vendor.closetime;
            }
        }

        this.reset();
    },
};
</script>

<style scoped>
.flatpickr-calendar {
    width: 100% !important;
}
</style>
