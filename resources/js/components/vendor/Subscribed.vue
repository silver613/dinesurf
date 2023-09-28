<template>
  <div class="flex-1">
    <div class="pb-6 pt-6 lg:pt-24 lg:pb-24 lg:max-w-4xl lg:mx-auto">
      <!---->
      <div>
        <!---->
        <div>
          <h1 class="text-2xl font-semibold text-gray-700 px-4 sm:px-8">
            Current Subscription Plan
          </h1>
          <div class="mt-6 sm:px-8">
            <!---->
            <div class="bg-white sm:rounded-lg shadow-sm">
              <div>
                <div class="flex justify-between">
                  <h2 class="px-6 pt-4 text-xl font-semibold text-gray-700">
                    {{ subscription.plan.name }}
                  </h2>
                  <!---->
                </div>
                <div class="px-6 pb-4">
                  <div class="mt-2 text-md font-semibold text-gray-700">
                    <span>
                      {{
                        numberWithCommas(
                          subscription.plan_frequency.price,
                          subscription.plan_frequency.currency
                        )
                      }}</span
                    >
                    <!---->
                    / {{ subscription.plan_frequency.duration_text }}
                    <!-- <span class="text-gray-400"
                                            >(5 day trial)</span
                                        > -->
                  </div>

                  <!-- <div
                                        class="
                                            mt-3
                                            max-w-xl
                                            text-sm text-gray-600
                                        "
                                    >
                                        The growth plan allows you to manage
                                        unlimited servers, sites, and
                                        deployments.
                                    </div> -->
                  <div class="mt-3 space-y-2">
                    <div
                      class="flex items-center"
                      v-for="(item, index) in subscription.plan.plan_features"
                      :key="index"
                    >
                      <svg
                        viewBox="0 0 20 20"
                        fill="currentColor"
                        class="text-green-400 opacity-75 w-5 h-5"
                      >
                        <path
                          fill-rule="evenodd"
                          d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                          clip-rule="evenodd"
                        ></path>
                      </svg>
                      <div class="ml-2 text-sm text-gray-600">
                        {{ item.name }}
                      </div>
                    </div>
                  </div>
                  <div class="text-md font-semibold text-gray-700 mt-5">
                    <div class="mb-3">
                      <span class="mr-3">Starts:</span>
                      <span>{{ subscription.start }}</span>
                    </div>
                    <div class="mb-3">
                      <span class="mr-3">Ends:</span>
                      <span>{{ subscription.end }}</span>
                    </div>
                    <div class="mb-3">
                      <span class="mr-3">Renew Subscription:</span>
                      <span
                        class="text-green-500 font-bold"
                        v-if="vendor.renew_subscription"
                        >Yes</span
                      >
                      <span class="text-red-500 font-bold" v-else>No</span>
                    </div>
                    <div class="mb-3" v-if="vendor.renew_subscription">
                      <span class="mr-3">Next Billing Date:</span>
                      <span>{{ subscription.end }}</span>
                    </div>
                    <div
                      class="mb-3"
                      v-if="vendor.plan && vendor.plan_frequency"
                    >
                      <span class="mr-3">Next Subscription Plan:</span>
                      <span
                        >{{ vendor.plan.name }},
                        {{ vendor.plan_frequency.duration_text }} -
                        {{ vendor.plan_frequency.currency }}
                        {{ vendor.plan_frequency.price }}</span
                      >
                    </div>
                  </div>
                </div>
              </div>
              <div class="px-6 pb-4">
                <button
                  @click="changePlan = !changePlan"
                  class="
                    w-auto
                    jump
                    inline-flex
                    items-center
                    px-4
                    py-2
                    bg-green-700
                    rounded-md
                    text-xs text-gray-100
                    uppercase
                    tracking-widest
                    shadow-sm
                    hover:text-white
                    focus:outline-none focus:ring
                    active:text-white
                    transition
                    ease-in-out
                    duration-150
                    font-bold
                  "
                >
                  Change Subscription Plan
                </button>
              </div>
              <div
                v-if="changePlan"
                class="
                  px-6
                  py-4
                  mt-5
                  bg-gray-100 bg-opacity-50
                  border-t border-gray-100
                  text-right
                  flex flex-col
                  justify-center
                  items-center
                "
              >
                <p>Choose a plan below</p>
                <p class="text-sm font-bold text-gray-600 mb-3 capitalize">
                  New plan will take effect at the end of current billing cycle.
                </p>
                <subscription-plans
                  :plans="plans"
                  :url="route('vendors.change-subscription-plan')"
                >
                </subscription-plans>
              </div>
            </div>
          </div>
          <!---->
          <div class="mt-10" v-if="subscription.card">
            <h1 class="text-2xl font-semibold text-gray-700 px-4 sm:px-8">
              Payment Information
            </h1>
            <div class="mt-3 sm:px-8">
              <div class="bg-white sm:rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4">
                  <p class="max-w-2xl text-sm text-gray-600 mb-3">
                    Your current payment method is a
                    <span class="font-semibold uppercase">{{
                      subscription.card.card_type
                    }}</span>
                    card ending in
                    <span class="font-semibold">{{
                      subscription.card.last_digits
                    }}</span>
                    that expires on
                    <span class="font-semibold"
                      >{{ subscription.card.exp_month }}/{{
                        subscription.card.exp_year
                      }}</span
                    >.
                  </p>
                  <button
                    @click="updatePayment = !updatePayment"
                    class="
                      w-auto
                      jump
                      inline-flex
                      items-center
                      px-4
                      py-2
                      bg-green-700
                      rounded-md
                      text-xs text-gray-100
                      uppercase
                      tracking-widest
                      shadow-sm
                      hover:text-white
                      focus:outline-none focus:ring
                      active:text-white
                      transition
                      ease-in-out
                      duration-150
                      font-bold
                    "
                  >
                    Update Payment Information
                  </button>

                  <!---->
                </div>
                <div
                  v-if="updatePayment"
                  class="
                    px-6
                    py-4
                    mt-5
                    bg-gray-100 bg-opacity-50
                    border-t border-gray-100
                    text-right
                    flex flex-col
                    justify-center
                    items-center
                  "
                >
                  <p class="text-sm text-gray-600 mb-3">
                    We'll charge you a test payment of
                    {{ numberWithCommas(50, "NGN") }} to confirm that your new
                    card works, then refund the money immediately.
                  </p>
                  <a
                    :href="
                      route('vendors.pay', {
                        type: 'new_card',
                      })
                    "
                    class="
                      w-auto
                      jump
                      inline-flex
                      items-center
                      px-4
                      py-2
                      bg-green-700
                      rounded-md
                      text-xs text-gray-100
                      uppercase
                      tracking-widest
                      shadow-sm
                      hover:text-white
                      focus:outline-none focus:ring
                      active:text-white
                      transition
                      ease-in-out
                      duration-150
                      font-bold
                    "
                  >
                    Proceed
                  </a>
                </div>
              </div>
            </div>
          </div>

          <h1 class="text-2xl font-semibold text-gray-700 mt-10 px-4 sm:px-8">
            Apply Coupon
          </h1>

          <apply-coupon></apply-coupon>
          <a
            :href="route('vendors.toggle-sub', { action: action })"
            class="hidden"
            id="toggleSub"
          ></a>
          <h1
            class="
              text-2xl
              font-semibold
              text-gray-700
              mt-10
              px-4
              sm:px-8
              capitalize
            "
          >
            {{ subscriptionToggleState }} Subscription
          </h1>
          <div class="mt-3 sm:px-8">
            <div class="px-6 py-4 bg-white sm:rounded-lg shadow-sm">
              <div class="max-w-2xl text-sm text-gray-600">
                <span v-if="vendor.renew_subscription"
                  >You may cancel your subscription at any time. Once your
                  subscription has been cancelled, you will have the option to
                  resume the subscription until the end of your current billing
                  cycle.</span
                >
                <span v-else
                  >You may renew your subscription at any time. Once your
                  subscription has been renewed, at the end of your current
                  billing cycle, A new billing cycle Automatically starts</span
                >
              </div>
              <div class="mt-4">
                <button
                  @click="toggleSub(subscriptionToggleState)"
                  class="
                    inline-flex
                    items-center
                    px-4
                    py-2
                    bg-white
                    border border-gray-300
                    rounded-md
                    font-semibold
                    text-xs text-gray-700
                    uppercase
                    tracking-widest
                    shadow-sm
                    hover:text-gray-500
                    focus:outline-none focus:ring
                    active:text-gray-800 active:bg-gray-50
                    transition
                    ease-in-out
                    duration-150
                  "
                >
                  {{ subscriptionToggleState }} Subscription
                </button>
              </div>
            </div>
          </div>
        </div>
        <!---->

        <!---->
      </div>
    </div>
  </div>
</template>

<script>
import ApplyCoupon from "./ApplyCoupon.vue";
import SubscriptionPlans from "./SubscriptionPlans.vue";
export default {
  props: ["plans", "subscription", "vendor"],

  components: {
    ApplyCoupon,
    SubscriptionPlans,
  },
  data() {
    return {
      changePlan: false,
      updatePayment: false,
      action: null,
      subscriptionToggleState: this.vendor.renew_subscription
        ? "cancel"
        : "renew",
    };
  },
  methods: {
    toggleSub(action) {
      var r = confirm(
        "Are you sure you want to " + action + " your Current Subscription?"
      );

      if (!r) {
        return;
      }

      this.action = action;

      setTimeout(function () {
        document.getElementById("toggleSub").click();
      }, 1000); //wait

      return;
    },
  },
};
</script>
