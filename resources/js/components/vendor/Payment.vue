<template>
  <div class="pt-3">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden p-4 sm:rounded-lg mb-3 pb-8">
        <div class="flex flex-wrap justify-center mt-3">
          <h5 class="text-sm font-light text-gray-400">
            Proceed to make a payment to Dinesurf
          </h5>
        </div>
        <div class="flex flex-wrap justify-center mt-3">
          <div
            class="
              bg-white
              overflow-hidden
              shadow-xl
              p-4
              sm:rounded-lg
              mb-3
              w-full
              lg:w-1/3
            "
          >
            <form
              id="paymentForm"
              @submit.prevent="startTxn()"
              :action="route('make-payment')"
              method="POST"
            >
              <input type="hidden" name="_token" v-model="csrf_token" />

              <input type="hidden" name="reference" v-model="txnRef" />

              <input type="hidden" name="type" v-model="model_type" />

              <div class="flex flex-col w-full mb-5">
                <jet-label
                  >Payment Type<span class="text-red-500">*</span></jet-label
                >
                <select
                  class="form-control"
                  v-model="form.type"
                  name="payment_type"
                  id="payment_type"
                  required
                >
                  <option>subscription</option>
                  <option>new_card</option>
                </select>
              </div>
              <div
                class="flex flex-col w-full mb-5"
                v-if="form.type == 'subscription'"
              >
                <jet-label>Plan<span class="text-red-500">*</span></jet-label>
                <select
                  @change="changePlan()"
                  class="form-control"
                  v-model="form.plan_id"
                  name="plan_id"
                  id="plan_id"
                  required
                  readonly
                >
                  <option
                    v-for="(item, index) in sub_plans"
                    :key="index"
                    :value="item.id"
                    class="capitalize"
                  >
                    {{ item.name }} - {{ plan.frequency.duration_text }}
                  </option>
                </select>
              </div>
              <div class="flex mb-5">
                <div class="flex flex-col w-1/3">
                  <jet-label
                    >Currency<span class="text-red-500">*</span></jet-label
                  >
                  <select
                    class="form-control"
                    v-model="form.currency"
                    name="currency"
                    id="currency"
                    required
                    readonly
                  >
                    <option>NGN</option>
                    <!-- <option>USD</option> -->
                  </select>
                </div>
                <div class="flex flex-col w-2/3">
                  <jet-label
                    >Amount<span class="text-red-500">*</span></jet-label
                  >
                  <input
                    type="number"
                    min="0"
                    class="form-control"
                    v-model="form.amount"
                    name="amount"
                    id="amount"
                    required
                    readonly
                  />
                </div>
              </div>

              <div
                class="flex flex-col w-full mb-5"
                v-if="form.type != 'new_card'"
              >
                <jet-label :value="'Voucher (optional)'"></jet-label>
                <input
                  @mouseleave="checkVoucher()"
                  @blur="checkVoucher()"
                  @input="checkVoucher()"
                  class="form-control"
                  v-model="form.voucher_code"
                  name="voucher_code"
                  id="voucher_code"
                  :class="{ 'pointer-events-none	': responseSpin }"
                />
                <input
                  v-if="voucher"
                  type="hidden"
                  name="voucher_id"
                  id="voucher_id"
                  v-model="voucher.id"
                />
                <small class="text-xs mt-1" v-html="voucherConfirmMsg"></small>
              </div>

              <div class="flex flex-col w-full mb-5" v-if="voucher">
                <ul>
                  <li>
                    You'll get a discount of
                    <span v-if="voucher.type == 'percentage'"
                      >-{{ voucher.discount_amount }}% off
                      {{
                        numberWithCommas(this.form.amount, this.form.currency)
                      }}</span
                    >
                    <span v-if="voucher.type == 'price'">
                      <!-- {{
                                                numberWithCommas(
                                                    this.form.amount,
                                                    this.form.currency
                                                )
                                            }} -->
                      -
                      {{
                        numberWithCommas(
                          voucher.discount_amount,
                          this.form.currency
                        )
                      }}
                    </span>
                    for this transaction.
                  </li>
                </ul>
              </div>

              <div class="text-center">
                <span>{{ responseMessage }}</span>
                <span class="text-green-800 font-bold text-lg">{{
                  responseSuccess
                }}</span>
                <span
                  class="text-red-700 font-bold text-lg"
                  v-html="responseFail"
                ></span>
                <i class="fas fa-spinner fa-spin" v-if="responseSpin"></i>
              </div>
              <div class="flex">
                <button
                  :disabled="responseSpin"
                  class="jet-btn active:bg-gray-900 mx-2 jet-btn-lg"
                  type="submit"
                >
                  Pay
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import JetAuthenticationCard from "@/Jetstream/AuthenticationCard.vue";
import JetAuthenticationCardLogo from "@/Jetstream/AuthenticationCardLogo.vue";
import JetButton from "@/Jetstream/Button.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetCheckbox from "@/Jetstream/Checkbox.vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetValidationErrors from "@/Jetstream/ValidationErrors.vue";

export default {
  props: ["vendor", "type", "plan", "plans", "paystackkey", "stripekey"],
  components: {
    JetAuthenticationCard,
    JetAuthenticationCardLogo,
    JetButton,
    JetInput,
    JetCheckbox,
    JetLabel,
    JetValidationErrors,
  },
  computed: {
    finalAmount() {
      if (this.voucher) {
        if (this.voucher.type == "percentage") {
          let amount_off =
            this.form.amount * (this.voucher.discount_amount / 100);
          return this.form.amount - amount_off;
        }
        if (this.voucher.type == "price") {
          return this.form.amount - this.voucher.discount_amount;
        }
      }
      return this.form.amount;
    },
  },
  data() {
    return {
      form: this.$inertia.form({
        type: this.type,
        plan_id: this.plan ? this.plan.id : null,
        frequency_id: this.plan ? this.plan.frequency.id : null,
        currency: "NGN",
        amount: this.plan ? this.plan.frequency.price : null,
        voucher_id: null,
        voucher_code: null,
      }),
      model_type: "vendor",
      csrf_token: this.$page.props.csrf_token,
      sub_plans: [],
      paymentMethod: "paystack",
      voucherConfirmMsg: null,

      // Paystack
      txnRef: null,
      voucher: null,

      // Stripe
      clientSecret: null,
      stripeOn: false,

      // Payment
      responseSpin: false,
      responseSuccess: null,
      responseFail: null,
      responseMessage: null,
    };
  },
  methods: {
    checkVoucher() {
      if (!this.form.voucher_code) {
        this.voucher = null;
        this.voucherConfirmMsg = null;
      } else {
        this.voucherConfirmMsg =
          'Confirming voucher code...<i class="fas fa-spinner fa-spin"></i>';

        axios
          .post(route("check-voucher"), {
            code: this.form.voucher_code,
            type: "vendor",
          })
          .then((res) => {
            var result = res.data;
            if (result.success) {
              this.voucher = result.data;
              this.voucherConfirmMsg =
                '<span class="text-green-700">Voucher Valid <i class="fas fa-check"></i></span>';
            } else {
              this.voucher = null;
              if (result.errors) {
                let msg = this.returnErrorMsg(result.errors);
                this.voucherConfirmMsg =
                  '<span class="text-red-700">' + msg + "</span>";
                return;
              }
              this.voucherConfirmMsg =
                '<span class="text-red-700">' +
                result.message +
                '<i class="fas fa-exclamation-triangle"></i></span>';
            }
          })
          .catch((err) => {
            this.voucher = null;
            this.voucherConfirmMsg =
              '<span class="text-red-700">' +
              this.handleApiError(err) +
              "</span>";
            return;
          });
      }
    },
    changePlan() {
      var plan = null;
      this.sub_plans.forEach((item, index) => {
        if (item.id == this.form.plan_id) {
          plan = item;
        }
      });
      if (plan) {
        this.form.currency = plan.currency;
        this.form.amount = plan.price;
      }
    },
    startTxn() {
      this.responseSpin = true;
      this.responseMessage = "Starting Transaction..."
      if (this.finalAmount == 0 && this.voucher) {
        return this.startFreeTrial();
      }

      axios
        .post("/start-transaction", {
          amount: this.finalAmount,
          paymentMethod: this.paymentMethod,
          txn_type: this.form.type,
          currency: this.form.currency,
          email: this.$page.props.user.email,
          phone: this.vendor.phone_number,
          name: this.vendor.company_name,
          vendor_id: this.vendor.id,
          plan_id: this.form.plan_id,
          frequency_id: this.form.frequency_id,
        })
        .then((result) => {
          result = result.data
          toastr.success(result.message);
          this.responseSpin = true;
          this.responseSuccess = null;
          this.responseFail = null;
          this.responseMessage = result.message + "!, Confirming Payment...";
          this.txnRef = result.data.reference;
          this.pay();
        })
        .catch((err) => {
          console.log(err);
          this.responseSpin = false;
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
      this.responseSpin = true;
      this.responseSuccess = null;
      this.responseFail = null;
      this.responseMessage = "Running Paystack...";

      let vm = this;

      var config = {
        key: this.paystackkey, // Replace with your public key
        email: this.$page.props.user.email,
        amount: this.finalAmount == 0 ? 100 : this.finalAmount * 100,
        firstname: this.vendor.company_name,
        ref: this.txnRef,
        currency: this.form.currency, //GHS for Ghana Cedis
        // currency: this.currency1, //GHS for Ghana Cedis
        // label: "Optional string that replaces customer email"
        onClose: function () {
          vm.responseSpin = false;
          vm.responseSuccess = null;
          vm.responseFail = null;
          vm.responseMessage = null;
        },
        callback: function (response) {
          var message = "Payment complete! Reference: " + response.reference;
          vm.responseSpin = true;
          vm.responseSuccess = null;
          vm.responseFail = null;
          vm.responseMessage = "Payment complete!, Verifying Transaction...";

          return vm.paystackVerify();
        },
      };

      var handler = PaystackPop.setup(config);
      handler.openIframe();
    },
    paystackVerify() {
      axios
        .get("/paystack-verify/" + this.txnRef)
        .then((result) => {
          result = result.data;
          console.log("result: ", result);
          this.responseSpin = true;
          this.responseSuccess = null;
          this.responseFail = null;
          this.responseMessage =
            result.message + "!, Completing " + this.type + " transaction...";
          document.getElementById("paymentForm").submit();
        })
        .catch((err) => {
          console.log(err);
          this.responseSpin = false;
          this.responseSuccess = null;
          this.responseFail = err;
          this.responseMessage = null;
        });
    },
    lockForm() {
      var selects = document.getElementsByTagName("select");
      for (var i = 0, fLen = selects.length; i < fLen; i++) {
        selects[i].readOnly = true; //As @oldergod noted, the "O" must be upper case
        selects[i].disabled = true; //As @oldergod noted, the "O" must be upper case
        selects[i].setAttribute("pointer-events", "none;");
        selects[i].setAttribute("cursor", "default;");
      }
    },
    startFreeTrial() {
      this.responseSpin = true;
      this.responseSuccess = null;
      this.responseFail = null;
      this.responseMessage = "Starting Fre Trial...";

      axios
        .post(route("vendors.free-trial"), {
          voucher_id: this.voucher.id,
        })
        .then((result) => {
          result = result.data
          toastr.success(result.message);
          this.responseSpin = true;
          this.responseSuccess = result.message + "!,...";
          this.responseFail = null;
          this.responseMessage = null;
          let vm = this;
          setTimeout(
            function () {
              // vm.$inertia.get(route("vendors.dashboard"));
              window.location = route("vendors.dashboard");
            },
            500,
            vm
          );
        })
        .catch((err) => {
          this.responseSpin = false;
          this.responseSuccess = null;
          this.responseMessage = null;
          if (err.errors) {
            this.responseFail = this.returnErrorMsg(err.errors);
            return;
          }
          this.responseFail = err.message;
        });
    },
  },
  mounted() {
    this.sub_plans = this.plans;
    if (this.plan) {
      this.form.plan_id = this.plan.id;
      //   this.form.amount = this.plan.price;
    }
    if (this.type) {
      this.form.type = this.type;
      if (this.type == "new_card") {
        this.form.amount = 50;
        this.form.currency = "NGN";
      }
      if (this.type == "subscription") {
        this.lockForm();
      }
    }
  },
};
</script>
