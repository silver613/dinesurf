<template>
  <div class="mt-3 sm:px-8">
    <form @submit.prevent="checkVoucher()">
      <div class="bg-white sm:rounded-lg shadow-sm">
        <div class="px-6 py-4">
          <div class="mt-6 flex">
            <label
              for="coupon_for_existing"
              class="w-1/3 mr-10 mt-2 text-gray-800 text-sm font-semibold"
              >Coupon</label
            >
            <input
              v-model="form.code"
              required
              type="text"
              id="coupon_for_existing"
              placeholder="Coupon"
              class="
                w-full
                bg-white
                border border-gray-200
                px-3
                py-2
                rounded
                outline-none
              "
            />
          </div>
        </div>
        <div
          class="
            px-6
            py-4
            mt-5
            bg-gray-100 bg-opacity-50
            border-t border-gray-100
            text-right
          "
        >
          <div class="text-center">
            <span>{{ responseMessage }}</span>
            <i class="fas fa-spinner fa-spin" v-if="responseSpin"></i>
            <span class="text-green-800 font-bold text-lg">{{
              responseSuccess
            }}</span>
            <span class="text-red-700 font-bold text-lg" v-html="responseFail">
            </span>
          </div>
          <button
            type="submit"
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
            :disabled="responseSpin"
          >
            Apply
          </button>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
export default {
  data() {
    return {
      form: this.$inertia.form({
        code: null,
        type: "vendor",
      }),

      voucher: null,

      // responses
      responseSpin: false,
      responseSuccess: null,
      responseFail: null,
      responseMessage: null,
    };
  },
  methods: {
    checkVoucher() {
      this.responseSpin = true;
      this.responseSuccess = null;
      this.responseFail = null;
      this.responseMessage = "Checking Coupon/Voucher...";

      axios
        .post(route("check-voucher"), {
          code: this.form.code,
          type: this.form.type,
        })
        .then((result) => {
          result = result.data;
          if (result.success) {
            this.voucher = result.data;
            this.applyCoupon();
            this.responseSpin = true;
            this.responseSuccess = null;
            this.responseFail = null;
            this.responseMessage =
              result.message + "!, Applying Coupon/Voucher...";
          } else {
            this.responseSpin = false;
            this.responseSuccess = null;
            this.responseMessage = null;
            if (result.errors) {
              this.responseFail = this.returnErrorMsg(result.errors);
              return;
            }
            this.responseFail = result.message;
          }
        })
        .catch((err) => {
          this.responseSpin = false;
          this.responseSuccess = null;
          this.responseMessage = null;
          if (err.errors) {
            this.responseFail = this.returnErrorMsg(err.errors);
            return;
          }
          this.responseFail = err;
        });
    },
    applyCoupon() {
      fetch(route("apply-coupon"), {
        method: "POST",
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": this.$page.props.csrf_token,
        },
        body: JSON.stringify({
          type: this.form.type,
          voucher_id: this.voucher.id,
          usage_type: "subscription",
          use: false,
        }),
      })
        .then((result) => {
          return result.json();
        })
        .then((result) => {
          if (result.success) {
            this.responseSpin = false;
            this.responseSuccess = result.message;
            this.responseFail = null;
            this.responseMessage = null;
          } else {
            this.responseSpin = false;
            this.responseSuccess = null;
            this.responseMessage = null;
            if (result.errors) {
              this.responseFail = this.returnErrorMsg(result.errors);
              return;
            }
            this.responseFail = result.message;
          }
        })
        .catch((err) => {
          this.responseSpin = false;
          this.responseSuccess = null;
          this.responseMessage = null;
          if (err.errors) {
            this.responseFail = this.returnErrorMsg(err.errors);
            return;
          }
          this.responseFail = err;
        });
    },
  },
};
</script>
