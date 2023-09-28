<template>
  <div class=" auth-card2 reservation-auth-card bg-white">
    <div class="auth-card-body text-left">
      <form
        @submit.prevent="submit"
        class="flex flex-col w-full"
        v-if="!regSuccess"
      >
        <h3 class="mb-3">Menu Information</h3>

        <!-- <div class="flex flex-col w-full mb-3">
          <jet-label class="font-bold">ID: {{ form.id }}</jet-label>
        </div> -->

        

        <div class="flex flex-col w-full">
          <jet-label>Name: </jet-label>
          <input
            type="text"
            v-model="form.name"
            class="auth-card-input"
            placeholder="Enter Last Name"
          />
        </div>

        <div class="flex flex-col w-full">
          <jet-label>Description(optional): </jet-label>
          <div class="flex flex-row justify-between">
               <input
                  type="text"
                  v-model="form.description"
                  class="auth-card-input"
                  placeholder="Enter Menu Description(optional)"
                  
                />
          </div>
    </div>

              
    <div class="flex items-center  w-full  border border-dashed border-dinesurf-green-400 bg-gray-100 justify-between p-3">
                            

                            <div
                                class="

                                  w-40
                                  h-20
                                "  

                                v-if="image_value"
                                >
                                <img
                                    :src="image_value"
                                    class="imageN"
                                    draggable="false"
                                    :id="'image-output'"
                                />
                                </div>
                               <div class="flex flex-col"  v-if="!this.image_value">
                                        <span class="text-dinesurf-green-400 cursor-pointer">
                                            Add Image/ Banner
                                        </span>
                                        <span class="text-xs">Max 2MB,PNG,JPEG</span>
                                        <span v-if="this.value" class="ml-1 capitalize">
                                            <span class="mr-2 text-green-600">{{responseText}}</span>
                                        </span>
                                        <span class="text-xs"  v-else>
                                        No file uploaded
                                        </span>
                                </div>
                                <button type="button"  class="bg-dinesurf-green-400  relative w-32 h-10 pt-0 text-white">
                                 
                                    <span v-if="!this.image_value">Choose Image</span>
                                    <span   v-else>Change Image</span>
                                    <input
                                        dusk="image"
                                        type="file"
                                        :id="'file-image'"
                                        name="image"
                                        accept="image/*"
                                        class="form-file-input select-none  opacity-0  absolute top-2 left-4 "
                                        @change="displayFile"
                                    />
                                </button>
                        </div>

      

        <div class="text-center mb-5">
          <span>{{ responseMessage }}</span>
          <i class="fas fa-spinner fa-spin" v-if="responseSpin"></i>
          <span class="text-green-800 font-bold text-lg">{{
            responseSuccess
          }}</span>
          <span class="text-red-700 font-bold text-lg">{{ responseFail }}</span>
        </div>
        <button
          type="submit"
          style="padding: 15px 0"
          class="jet-btn active:bg-gray-900"
          :class="{ 'opacity-25': form.processing }"
          :disabled="form.processing"
        >
          Update Menu
        </button>
      </form>
      <div
        class="flex flex-col justify-center items-center my-10"
        v-if="regSuccess"
      >
      <img src="/images/success-svg2.svg" class="h-16 md:h-32" />
        <h4 class="text-center my-3  font-inter text-xs text-[#000000] font-normal">Menu Updated Successfully</h4>
        <div class="flex flex-col justify-around  space-y-3 w-full">
          <div class=" flex flex-wrap justify-center  space-x-4 items-center w-full">
            <Link 
              class="bg-[#201F1F] text-white p-2 px-4 rounded-md active:bg-gray-900"
              :href="route('vendors.edit-menu-page', {id: this.menu?.id }) + '?tab=categories'"
            >
              View Categories
            </Link>
            <Link  
             
              class="bg-[#201F1F] text-white p-2 px-4 rounded-md active:bg-gray-900 "
              :href="route('vendors.edit-menu-page', {id: this.menu?.id }) + '?tab=categories'"
            >
              Create Category
            </Link  >
           
          </div>
          <div  class="w-full flex justify-center items-center">
            <button
             
             class=" active:bg-gray-900   rounded-md  border  border-[#201F1F] p-2 px-4"
             @click="reset()"
           >
            Back to  editing.
           </button>
          </div>
        </div>
      </div>
    </div>

   

  <category-popup  :show="this.menuCategoryBoxShow"   @close="this.menuCategoryBoxShow = !this.menuCategoryBoxShow"  />
 
  </div>
</template>

<script>
import moment from "moment";
import JetAuthenticationCard from "@/Jetstream/AuthenticationCard.vue";
import JetAuthenticationCardLogo from "@/Jetstream/AuthenticationCardLogo.vue";
import JetButton from "@/Jetstream/Button.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetCheckbox from "@/Jetstream/Checkbox.vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetValidationErrors from "@/Jetstream/ValidationErrors.vue";

export default {
  components: {
    JetAuthenticationCard,
    JetAuthenticationCardLogo,
    JetButton,
    JetInput,
    JetCheckbox,
    JetLabel,
    JetValidationErrors,
  },

  props: {
    vendor: {
      type: Object,
      required: true,
    },
    menu: {
      type: Object,
      required: true,
    },
   
    status: String,
  },
  data() {
    return {
      menuCategoryBoxShow:false,
      showUsers: false,
      exampleModalShowing: false,
      today: new Date(),
      form: this.$inertia.form({
        id: this.menu.id,
        name: this.menu.name,
        description: this.menu.description,
         image: this.menu.image
      }),
      image_value: this.menu.image,
      regSuccess: false,
      // response
      responseSpin: false,
      responseSuccess: null,
      responseFail: null,
      responseMessage: null,
      users: [],
    };
  },
  computed: {
    formatedBirthday() {
      if (this.form.birthday) {
        return moment(this.form.birthday).format("dddd, MMM Do YYYY");
      }
      return;
    },
  },
  methods: {


    displayFile() {
        var files = document.getElementById("file-image").files;
        var output = document.getElementById("image-output");
        this.form.file = files[0];
        this.image_value = URL.createObjectURL(files[0]);
            output.src = URL.createObjectURL(files[0]);
        output.onload = function () {
        URL.revokeObjectURL(output.src); // free memory
        };
    },
    
    openCategoryBox(){
      
      const menuvendorId = localStorage.getItem('menuCategoryvendorId');
      if(menuvendorId && menuvendorId == this.$page.props.auth.vendor.id){
          this.$inertia.get( route("vendors.menu-categories"));
        }else{
           this.menuCategoryBoxShow = true;
        }
      },
    reset() {
      this.regSuccess = false;
      this.responseSpin = false;
      this.responseSuccess = null;
      this.responseFail = null;
      this.responseMessage = null;
    },
    
    submit() {
      
      this.responseSpin = true;
      this.form.processing = true;
      this.responseSuccess = null;
      this.responseFail = null;
      this.responseMessage = "Updating Menu...";

      // fetch(route("vendors.edit-menu", { id: this.form.id }), {
      //   method: "POST",
      //   headers: {
      //     Accept: "application/json",
      //     "Content-Type": "application/json",
      //     "X-CSRF-TOKEN": this.$page.props.csrf_token,
      //   },
      //   body: JSON.stringify(this.form),
      // })
      //   .then((result) => {
      //     return result.json();
      //   })
      //   .then((result) => {

        
      var formData = new FormData();
          
          formData.append("image", this.form.file);
          formData.append("name", this.form.name);
          formData.append("description", this.form.description);
        
          axios
        .post(route("vendors.edit-menu", { id: this.form.id}), formData,
        {
              headers: {
                  'Content-Type': 'multipart/form-data'
              }
         }
        ).then(({ data }) => {
        const result = data ;
          if (result.success) {
            toastr.success(result.message);
            this.responseSuccess = null;
            this.responseFail = null;
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
              this.responseFail = this.showErrorMsg(result.errors);
            } else {
              if (result.message == "CSRF token mismatch.") {
                window.location.reload();
              }
              this.responseFail = result.message;
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
    search() {
      this.showUsers = true;
      this.form.user_id = null;
      fetch(route("search-users", { query: this.form.user_query }))
        .then((result) => {
          return result.json();
        })
        .then((result) => {
          this.users = result.data;
        })
        .catch((err) => {
          this.handleApiError(err);
        });
    },
    selectUser(userItem) {
      this.form.user_query = userItem.first_name + " " + userItem.last_name;
      this.form.last_name = userItem.last_name;
      this.form.first_name = userItem.first_name;
      this.form.email = userItem.email;
      this.form.user_id = userItem.id;
      this.showUsers = false;
    },
    resetUser() {
      console.log("user_id: ", this.form.user_id);
      if (!this.form.user_id) {
        this.form.user_query = null;
      }
      this.showUsers = false;
      return;
    },
  },
  mounted() {
    // console.log("today: ", this.today);
    // var today = new Date().toISOString().split("T")[0];
    // document.getElementById("birthday").setAttribute("min", today);
  },
};
</script>
