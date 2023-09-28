<template>
  <div>
    <jet-modal
      :show="showEmailModal"
      @close="showEmailModal = false"
      :id="'SendEmail'"
    >
      <div class="p-6">
        <h3 class="text-lg font-medium text-gray-900">Email User(s)</h3>
        <form v-if="!sendSuccess" @submit.prevent="submit()" class="my-5">
          <div class="flex flex-col">
            <div class="mb-3 flex flex-col">
              <label for="email_users_subject"
                >Subject <span class="text-red-500">*</span></label
              >
              <input
                name="subject"
                type="text"
                id="email_users_subject w-full block"
                class="form-control mb-3"
                placeholder="Subject"
                v-model="backendActionData.subject"
                required
              />
            </div>
            <div class="mb-3 flex flex-col">
              <label for="email_users_message">Mail Banner Image </label>
             <!--  <ol class="text-xs pad disc">
                <li>
                  For best results, image should be at least 2660x1140px. (a
                  Rectangle).
                </li>
                <li>File format: jpeg, gif, or png.</li>
                <li>Max File Size: 2mb.</li>
              </ol> -->

              <div class="bg-white mt-2 overflow-hidden md:w-full border border-red p-4 border-l-4 border-l-dinesurf-green-400 sm:rounded-lg mb-6">
                <ul>
                  <li>For best results, image should be at least 2660x1140px. (a
                  Rectangle).</li>
                  <li class="text-dinesurf-green-400   text-sm">File format: JPEG, GIF, or PNG.</li>
                  <li class="text-gray-400 text-xs">Max File Size: 2mb.</li>
                </ul>
              </div>

              <display-image
                :vendor="vendor"
                :type="'emailPhoto'"
                @input="setMessagePhoto"
              ></display-image>
            </div>
            <div class="mb-10 mt-6 flex flex-col">
              <label for="email_users_message"
                >Message <span class="text-red-500">*</span></label
              >
              <text-editor
                class="mb-3 w-full"
                v-model="backendActionData.message"
                @input="setMessage"
              ></text-editor>
            </div>
            <div class="text-center mb-5">
              <span>{{ responseMessage }}</span>
              <i class="fas fa-spinner fa-spin" v-if="responseSpin"></i>
              <span class="text-green-800 font-bold text-lg">{{
                responseSuccess
              }}</span>
              <span class="text-red-700 font-bold text-lg">{{
                responseFail
              }}</span>
            </div>
            <button
              style="padding: 15px 40px"
              type="submit"
              class="jet-btn active:bg-gray-900"
              :class="{ 'opacity-25': form.processing }"
              :disabled="form.processing"
            >
              Send
            </button>
          </div>
        </form>
        <div
          class="flex flex-col justify-center items-center my-10"
          v-if="sendSuccess"
        >
          <img src="/images/success-icon.svg" class="h-16 md:h-32" />
          <h4 class="text-center my-3">Email Sending successful</h4>
          <div class="flex justify-around">
            <button
              style="padding: 15px 40px"
              class="jet-btn active:bg-gray-900 mx-2"
              @click="close()"
            >
              Close
            </button>
            <button
              style="padding: 15px 40px"
              class="jet-btn active:bg-gray-900 mx-2"
              @click="reset()"
            >
              Send Another Email
            </button>
          </div>
        </div>
      </div>
    </jet-modal>
  </div>
</template>

<script>
export default {
  props: {
    showEmailModal: {
      type: Boolean,
    },
    backendActionData: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      sendSuccess: false,
      responseSpin: false,
      responseSuccess: null,
      responseFail: null,
      responseMessage: null,
      form: this.$inertia.form({}),
    };
  },
  methods: {
    setMessage(data) {
      this.backendActionData.message = data;
    },
    setMessagePhoto(data) {
      this.backendActionData.banner = data;
    },
    submit() {
      this.responseMessage = "Sending Email...";

      this.runAction();
    },
    reset() {
      this.sendSuccess = false;
      this.responseSpin = false;
      this.responseSuccess = null;
      this.responseFail = null;
      this.responseMessage = null;
    },
    close() {
      this.reset();
      var closebtn = document.getElementById("closeModalSendEmail");
      if (closebtn) {
        closebtn.click();
      }
    },
  },
};
</script>