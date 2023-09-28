<template>
  <div>
    <jet-modal :show="showTextModal" @close="showTextModal = false" :id="'SendText'">
      <div class="p-6">
        <h3 class="text-lg font-medium text-gray-900">Text User(s)</h3>
        <form v-if="!sendSuccess" @submit.prevent="submit()" class="my-5">
          <div class="flex flex-col">
            <div class="flex flex-col">
              <label for="email_users_message"
                >Message <span class="text-red-500">*</span></label
              >
              <textarea
                id="message"
                class="mb-3 w-full"
                v-on:keyup="validates"
                v-model="this.backendActionData.message"
                autocomplete="message"
                maxlength="160"
              ></textarea>
              <div  class="flex justify-between mt-2">
                 <span></span>
                 <div>
                  <span   id="textPage" class="bg-gray-400 mr-3 p-3 rounded-full pt-1 pb-1  cursor-pointer"><i  class="fa   fa-info"></i> </span>
                   <span>  {{this.countWords}}/160</span>
                 </div>
                
              </div>
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
          <h4 class="text-center my-3">Text Sending successful</h4>
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
              Send Another Text
            </button>
          </div>
        </div>
      </div>
    </jet-modal>
  </div>
</template>

<script>
import tippy from "tippy.js";
import "tippy.js/dist/tippy.css"; // optional for styling

export default {
  props: {
    showTextModal: {
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
      countWords: 0,
      form: this.$inertia.form({}),
      flagWordLengthError: false
    };
  },
  methods: {

    validates(event){
      const wordLength = event.target.value.length;

      if(wordLength > 160){
        this.flagWordLengthError = true;
      }else{
        this.backendActionData.message = event.target.value;
        this.countWords = wordLength
      }
     

      // console.log("Wordlength:",countWords)
    },
    submit() {
      this.responseMessage = "Sending Text...";

      console.log("textM:",  this.backendActionData.message);
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
      var closebtn = document.getElementById("closeModalSendText");
      if (closebtn) {
        closebtn.click();
      }
    },
  },

  mounted(){
   
   tippy('#textPage', {
      content: "SMS can only take 160 characters per page"
   });
   
  }
};
</script>