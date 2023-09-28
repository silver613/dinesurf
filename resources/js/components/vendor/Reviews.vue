<template>
    <div class="w-full">
        <div v-if="user_review && !edit">
            <h5 class="mb-3">Your Review</h5>
            <div
                class="text-grey-dark leading-normal text-sm flex items-start"
                v-if="user_review.user"
            >
                <div class="pr-2">
                    <img
                        class="h-8 w-auto rounded-full object-cover"
                        :src="user_review.avatar"
                        :alt="user_review.name"
                    />
                </div>
                <div class="flex-grow">
                    <div v-if="user_review.user">
                        <span class="font-bold mr-2">{{
                            user_review.name
                        }}</span>
                        <ratings :stars="user_review.stars"></ratings>
                    </div>

                    <span class="font-normal block">{{
                        user_review.content
                    }}</span>
                    <span class="mx-1 text-xs block mt-2 text-gray-500">{{
                        user_review.date
                    }}</span>
                    <div class="text-blue-500">
                        <a href="javascript:void(0)" @click="startEdit()"
                            >Edit Your Review <i class="fas fa-edit"></i
                        ></a>
                    </div>
                </div>
            </div>
        </div>
        <div v-else>
            <h5 class="mt-5">Rate this Vendor</h5>
            <form
                @submit.prevent="submit()"
                v-if="!regSuccess"
                @click="checkAuth()"
            >
                <div class="mb-3">
                    <div class="rating">
                        <input
                            type="radio"
                            id="star5"
                            name="rating"
                            :value="5"
                            v-model="form.rating"
                        /><label for="star5" title="Rocks!">5 stars</label>
                        <input
                            type="radio"
                            id="star4"
                            name="rating"
                            :value="4"
                            v-model="form.rating"
                        /><label for="star4" title="Pretty good">4 stars</label>
                        <input
                            type="radio"
                            id="star3"
                            name="rating"
                            :value="3"
                            v-model="form.rating"
                        /><label for="star3" title="Meh">3 stars</label>
                        <input
                            type="radio"
                            id="star2"
                            name="rating"
                            value="2"
                            v-model="form.rating"
                        /><label for="star2" title="Kinda bad">2 stars</label>
                        <input
                            type="radio"
                            id="star1"
                            name="rating"
                            :value="1"
                            v-model="form.rating"
                        /><label for="star1" title="Sucks big time"
                            >1 star</label
                        >
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="mb-3 flex flex-col">
                    <label class="block" for="content">Comment</label>
                    <textarea
                        id="content"
                        class="block form-control w-full"
                        v-model="form.content"
                        type="number"
                        min="0"
                    ></textarea>
                </div>
                <div class="mb-3">
                    <div class="text-center mb-5">
                        <span>{{ responseMessage }}</span>
                        <i
                            class="fas fa-spinner fa-spin"
                            v-if="responseSpin"
                        ></i>
                        <span class="text-green-800 font-bold text-lg">{{
                            responseSuccess
                        }}</span>
                        <span class="text-red-700 font-bold text-lg">{{
                            responseFail
                        }}</span>
                    </div>
                </div>
                <div class="mb-3">
                    <button
                        type="submit"
                        class="btn-md btn-auth btn-red"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        Submit
                    </button>
                </div>
            </form>
            <div
                class="flex flex-col justify-center items-center my-10"
                v-if="regSuccess"
            >
                <img src="/images/success-icon.svg" class="h-16 md:h-32" />
                <h4 class="text-center my-3">Your review has been posted</h4>
                <div class="flex justify-around">
                    <button
                        class="mx-2 btn-md btn-auth btn-red"
                        @click="reset()"
                    >
                        Ok, Thanks
                    </button>
                </div>
            </div>
        </div>
        <div class="py-5">
            <div class="mb-5">
                <h5>All Reviews</h5>
                <p class="text-xs text-green-600 font-bold leading-7">
                    The vast majority of our reviews come from verified Diners,
                    reviews may come from notable Restaurant Critics and other
                    network members (as tagged). Select reviewers may receive
                    discounted products or points for an honest, helpful review.
                </p>
            </div>
            <template v-if="reviews.length > 0">
                <div
                    class="text-grey-dark leading-normal text-sm flex items-start mb-5"
                    v-for="(item, index) in reviews"
                    :key="index"
                >
                    <div class="pr-2">
                        <img
                            class="h-8 w-auto rounded-full object-cover"
                            :src="item.avatar"
                            :alt="item.name"
                        />
                    </div>
                    <div class="flex-grow">
                        <div>
                            <span class="font-bold mr-2">{{ item.name }}</span>

                            <ratings :stars="item.stars"></ratings>
                        </div>
                        <span class="font-normal block">{{
                            item.content
                        }}</span>
                        <div class="flex mt-2 items-center">
                            <span class="mx-1 text-xs block text-gray-500">{{
                                item.date
                            }}</span>
                            <div class="ml-3 flex items-center">
                                from
                                <img
                                    src="/images/TripAdvisor_Logo.png"
                                    class="h-4 w-auto mx-1"
                                    v-if="item.trip_advisor_review_id"
                                />
                                <img
                                    src="/images/google_logo.png"
                                    class="h-4 w-auto mx-1"
                                    v-if="item.google_review_id"
                                />
                                <img
                                    src="/images/company_logo.jpg"
                                    class="h-4 w-auto mx-1"
                                    v-if="
                                        !item.trip_advisor_review_id &&
                                        !item.google_review_id
                                    "
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <div v-else>No Reviews Available</div>
        </div>

        <!-- Login -->
        <card-modal
            :showing="exampleModalShowing"
            @close="exampleModalShowing = false"
        >
            <login-card
                @close="exampleModalShowing = false"
                :status="status"
                :canResetPassword="true"
                :intendedUrl="$page.props.page"
            ></login-card>
        </card-modal>
    </div>
</template>

<script>
import Ratings from "./Ratings.vue";
import LoginCard from "@/components/LoginCard.vue";
export default {
    components: { Ratings, LoginCard },
    props: ["vendor", "string"],
    data() {
        return {
            exampleModalShowing: false,
            reviews: [],
            user_review: null,
            edit: false,
            form: this.$inertia.form({
                content: null,
                rating: null,
                type: "vendor",
                vendor_id: null,
            }),
            regSuccess: false,
            // response
            responseSpin: false,
            responseSuccess: null,
            responseFail: null,
            responseMessage: null,
        };
    },
    methods: {
        checkAuth() {
            if (!this.$page.props.user) {
                this.logCurrentUrl(document.location.toString());
                this.exampleModalShowing = true;
                return;
            }
            return;
        },
        startEdit() {
            this.edit = true;
            this.form = this.$inertia.form({
                content: this.user_review.content,
                rating: this.user_review.stars,
                type: "vendor",
                vendor_id: this.user_review.vendor_id,
            });
        },
        submit() {
            this.responseSpin = true;
            this.form.processing = true;
            this.responseSuccess = null;
            this.responseFail = null;
            this.responseMessage = "Posting Review...";

            fetch(route("reviews"), {
                method: "POST",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": this.$page.props.csrf_token,
                },
                body: JSON.stringify(this.form),
            })
                .then((result) => {
                    return result.json();
                })
                .then((result) => {
                    if (result.success) {
                        toastr.success(result.message);
                        this.responseSuccess = null;
                        this.responseFail = null;
                        this.getReviews();
                        var vm = this;
                        setTimeout(
                            function () {
                                vm.form.processing = false;
                                vm.responseMessage = result.message + "!";
                                vm.regSuccess = true;
                            },
                            500,
                            vm,
                            result
                        );
                    } else {
                        toastr.error("An Error Occured!");
                        this.form.processing = false;
                        this.responseSpin = false;
                        this.responseSuccess = null;
                        this.responseMessage = null;

                        if (result.errors) {
                            this.responseFail = this.showErrorMsg(
                                result.errors
                            );
                        } else {
                            this.responseFail = result.message;
                            if (result.message == "CSRF token mismatch.") {
                                window.location.reload();
                            }
                        }
                    }
                })
                .catch((err) => {
                    this.responseSpin = false;
                    this.form.processing = false;
                    this.responseSuccess = null;
                    this.responseFail = err;
                    this.handleApiError(err);
                });
        },
        reset() {
            this.getReviews();
            this.edit = false;
            this.regSuccess = false;
            this.responseSpin = false;
            this.form.processing = false;
            this.responseSuccess = null;
            this.responseFail = null;
            this.responseMessage = null;
        },
        getReviews() {
            fetch(route("get-reviews") + "?vendor_id=" + this.vendor.id)
                .then((result) => {
                    return result.json();
                })
                .then((result) => {
                    if (result.success) {
                        // toastr.success(result.message);
                        this.reviews = result.data.reviews;
                        this.user_review = result.data.user_review;
                    } else {
                        toastr.error("An Error Occured!");
                    }
                })
                .catch((err) => {
                    this.handleApiError(err);
                });
        },
    },
    mounted() {
        this.form.vendor_id = this.vendor.id;
        this.getReviews();
        if (this.vendor) {
            document.title = this.vendor.company_name + " - Reviews";
        }
    },
};
</script>
