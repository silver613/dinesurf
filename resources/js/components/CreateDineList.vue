<template>
    <div class="max-w-xl shadow-0 bg-white">
     
      <div class="auth-card-body text-left">
        <form
          @submit.prevent="submit"
          class="flex flex-col w-full"
          v-if="!regSuccess"
        >
          <h3 class="mb-3">Create DineList</h3>
         
  
          <div class="flex flex-col w-full">
            <jet-label>Give your DineList a name: </jet-label>
            <div class="flex flex-row justify-between mt-1">
                 <input
                    type="text"
                    v-model="form.name"
                    class="auth-card-input"
                    placeholder="eg. Date Night"
                    required
                  />
            </div>
      </div>
  
      <div class="flex  w-full">
             <div class="flex flex-col">
                    <span class="font-bold text-[14px]  text-[#000000]">Privacy</span>
                    <span class="text-[#000000]  font-normal text-[14px] ">Make this list private for only you <br/> and your collaborators</span>
             </div>
             <div>
                 
                <label  class="relative inline-flex items-center cursor-pointer mx-5 mb-5" >
                          <input  type="checkbox"
                                  v-model="form.isPrivate"
                                  class="sr-only peer"  />
                          <div  class="w-11 h-6 mt-5 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-dinesurf-green-300 dark:peer-focus:ring-dinesurf-green-800 dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[1.4rem] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-dinesurf-green-600" ></div>
               </label>
            </div>
      </div>
  
         
  
  
        
         
  
          <div class="text-center  flex flex-col space-y-2 mt-2">
           
           <div class="flex items-center justify-center">
              <span>{{ responseMessage }}</span>
              <i class="fas fa-spinner fa-spin" v-if="responseSpin"></i>
            </div>
            <span class="text-green-800 font-bold text-lg">{{
              responseSuccess
            }}</span>

            <span class="text-red-700 font-bold text-lg">{{ responseFail }}</span>
          </div>


          <button
            type="submit"
            style="padding: 15px 0"
            class="secondary-btn-rounded active:bg-gray-900"
            :class="{ 'opacity-25': form.processing }"
            :disabled="form.processing"
          >
            Create 
          </button>
        </form>
        <div
          class="flex flex-col justify-center items-center my-5"
          v-if="regSuccess"
        >
          <img src="/images/success-svg2.svg" class="h-16 md:h-32" />
          <h4 class="text-center my-3  font-inter text-xs text-[#000000] font-normal">List Created Successfully</h4>
           <div class="flex flex-col justify-around  space-y-3 w-full">
            <!-- <div class=" flex flex-wrap justify-center  space-x-4 items-center w-full">
              <Link 
                class="bg-[#201F1F] text-white p-2 px-4 rounded-md active:bg-gray-900"
                :href="route('vendors.edit-menu-page', {id: menu?.id }) + '?tab=categories'"
              >
                View Categories
              </Link>
              <Link  
               
                class="bg-[#201F1F] text-white p-2 px-4 rounded-md active:bg-gray-900 "
                :href="route('vendors.edit-menu-page', {id: menu?.id }) + '?tab=categories'"
              >
                Create Category
              </Link  >
             
            </div> -->
            <div  class="w-full flex justify-center items-center">
              <button
               
               class=" active:bg-gray-900   rounded-md  border  border-[#201F1F] p-2 px-4"
               @click="reset()"
             >
              Create  List
             </button>
            </div>
          </div>
        </div>
  
      </div>
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
      status: String,
    },
    data() {
      return {
        form: this.$inertia.form({
          name: null,
          isPrivate: null
        }),
        regSuccess: false,
        menu: null,
        // response
        responseSpin: false,
        responseSuccess: null,
        responseFail: null,
        responseMessage: null,
     
      };
    },
    
    methods: {
    
      reset() {
        this.regSuccess = false;
        this.form = this.$inertia.form({
         name:null,
         isPrivate: null
        });
        this.responseSpin = false;
        this.responseSuccess = null;
        this.responseFail = null;
        this.responseMessage = null;
      },
      submit() {

        this.responseSpin = true;
        this.responseSuccess = null;
        this.responseFail = null;
        this.responseMessage = "Creating list...";
  
        var formData = new FormData();
            
              formData.append("name", this.form.name);
              formData.append("isPrivate", this.form.isPrivate);
            
              axios
            .post(route("create.dinelist"), formData,
            {
                  headers: {
                      'Content-Type': 'multipart/form-data'
                  }
             }
            )
    
          .then(({ data }) => {
            const result = data ;
            if (result.success) {
              toastr.success(result.message);
              this.responseSuccess = null;
              this.responseFail = null;
              this.$inertia.visit(route('view.dinelist', { slug: result.data?.slug }));
            
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
            this.responseMessage = "";
            this.handleApiError(err);

            // toast.success(error)
          });
      },
 
   
    },
    // mounted() {
    // },
  };
  </script>
  