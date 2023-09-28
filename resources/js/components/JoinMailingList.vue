<template>
  <form @submit.prevent="addToMailingList()" class="w-full">
    <div class="searchInputContainer w-full">
      <input
        type="email"
        name="email"
        id="email"
        placeholder="Enter Your Email"
        class="
          bg-white
          py-4
          md:px-5 md:w-full
          focus:outline-none
          border-0
          w-full
          md:pr-10
          pr-[4rem]
          text-base
          rounded-t-md rounded-b-md
        "
        required
        v-model="form.email"
      />
      <button
        type="submit"
        class="
          newsletter-btn
          absolute
          top-0
          right-0
          p-2.5
          text-sm
          font-medium
          text-white
          searchBtnBg
          border
          focus:ring-4 focus:outline-none
          rounded-tr-md rounded-br-md
        "
        :class="{ 'opacity-25': processing, 'bg-secondary': form.email }"
        :disabled="form.processing"
      >
        Subscribe
      </button>
    </div>
    <div class="w-full text-center h-5">
      <span class="font-bold text-sm">{{ responseMessage }}</span>
      <span class="text-green-700 font-bold text-sm">{{
        responseSuccess
      }}</span>
      <span class="text-red-500 font-bold text-sm" v-html="responseFail"></span>
      <i class="fas fa-spinner fa-spin" v-if="form.processing"></i>
    </div>
  </form>
</template>

<script>
import { useForm } from "@inertiajs/inertia-vue3";

export default {
  data() {
    return {
      form: useForm({
        email: null,
      }),
      responseSuccess: null,
      responseFail: null,
      responseMessage: null,
    };
  },
  methods: {
    addToMailingList() {
      this.form.processing = true;
      this.responseSuccess = null;
      this.responseFail = null;
      this.responseMessage = "Adding Email...";

      axios
        .post("/api/add-mail", {
          email: this.form.email,
        })
        .then((result) => {
          var result = result.data;
          toastr.success(result.message);
          this.responseSuccess = result.message;
          this.responseFail = null;
          this.responseMessage = null;
          this.form.processing = false;
          var vm = this;
          setTimeout(
            () => {
              vm.responseSuccess = null;
            },
            500,
            vm,
            result
          );
        })
        .catch((err) => {
          console.log(err);
          // toastr.error(err, "An Error Occured!");
          this.form.processing = false;
          this.responseSuccess = null;
          this.responseFail = this.handleApiError(err);
          this.responseMessage = null;
        });
    },
  },
};
</script>