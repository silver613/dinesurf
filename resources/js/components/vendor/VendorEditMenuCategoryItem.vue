<template>
  <div class=" auth-card2 reservation-auth-card bg-white">
    <div class="auth-card-body text-left">
      <form
        @submit.prevent="submit"
        class="flex flex-col w-full"
        v-if="!regSuccess"
      >
        <h3 class="mb-3">Item Information</h3>
     
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
          <jet-label>Price: </jet-label>
          <input
            type="number"
            v-model="form.price"
            class="auth-card-input"
            placeholder="Enter Price"
            
            
          />
          <div class="w-full  flex justify-between text-base  -mt-4 mb-4 "  v-if="form.price">
            <span class="font-bold">  ~
              {{
                numberWithCommas(
                  form.price,
                  this.vendor.average_menu_price_currency
                )
              }}
              </span>
              <!-- <span> -->
                 <a   id="priceSymbolInfo" :href="route('vendors.profile.show')" target="_blank" class="text-xs  underlined text-blue-600" >
                  want to change currency? 
              </a>

              <!-- <span
               
                class="
                  ml-3
                  -mt-1
                  pb-1
                  pt-1
                  bg-gray-200
                  rounded-full
                  p-3
                  h-[2rem]
                  w-[2rem]
                  cursor-pointer
                  text-center
                "
              >
                <i class="fa fa-info"></i
              ></span>
              </span> -->
             
            </div>
        </div>

        <div class="flex flex-col w-full">
                <jet-label>Description(optional): </jet-label>
                <div class="flex flex-row justify-between">
                    <input
                        type="text"
                        v-model="form.description"
                        class="auth-card-input"
                        placeholder="Enter Item Description(optional)"
                        
                      />
                </div>
          </div>

        <div class="flex flex-col w-full" 
            v-if="categories">

            <muiltiple-selector 
                                    :label="'Select Category:'" 
                                    :list="categoriesList" 
                                    @updateSelected="updateSelected"
                                    :selected="selected"
                                    @removeSelected="removeSelectedDay"/>
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
          Update Item
        </button>
      </form>
      <div
        class="flex flex-col justify-center items-center my-10"
        v-if="regSuccess"
      >
      <img src="/images/success-svg2.svg" class="h-16 md:h-32" />
        <h4 class="text-center my-3  font-inter text-xs text-[#000000] font-normal">Item Updated Successfully</h4>
        <div class="flex justify-around">
          <div class=" flex flex-wrap justify-center  space-x-4 items-center w-full">
            <Link
              style="padding: 15px 40px"
              class="bg-[#201F1F] text-white p-2 px-4 rounded-md active:bg-gray-900"
              :href="route('vendors.edit-menu-page', {id: this.menus[0]?.id }) + '?tab=items'"
            >
              View Item
            </Link>
            <button
              style="padding: 15px 40px"
              class=" active:bg-gray-900   rounded-md  border  border-[#201F1F] p-2 px-4"
              @click="reset()"
            >
             Create  Item
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
import tippy from "tippy.js";
import "tippy.js/dist/tippy.css"; // optional for styling
import MuiltipleSelector from "../MultipleSelector.vue";

export default {
  components: {
    JetAuthenticationCard,
    JetAuthenticationCardLogo,
    JetButton,
    JetInput,
    JetCheckbox,
    JetLabel,
    JetValidationErrors,
    MuiltipleSelector
  },

  props: {
    vendor: {
      type: Object,
      required: true,
    },
    categories: {
      type: Object,
      required: true,
    },

     item: {
      type: Object,
      required: true,
    },
    selectedCategories: {
        type:Object,
        required:true
    },
    menus: Object,
    status: String,
  },
  data() {
    return {
      showUsers: false,
      exampleModalShowing: false,
      today: new Date(),
      form: this.$inertia.form({
        id: this.item.id,
        name: this.item.name,
        price:this.item.price,
        category_ids:this.selectedCategories.map((item) => item.id),
        selectedCategories:this.selectedCategories.map((item) => item.id),
        description: this.item.description,
        file: this.item.image
      }),
      image_value: this.item.image,
      regSuccess: false,
      // response
      responseSpin: false,
      responseSuccess: null,
      responseFail: null,
      responseMessage: null,
      users: [],
      selected:this.selectedCategories,
      categoriesList: this.categories.map((item) => { return { id: item.id, value: item.id, name: item.name}}),
      
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

    updateSelected(e){
            const val = Number(e.target.value);
            const list = this.categoriesList.find((item) => item.id === val);
          if (!this.selected.includes(list) ) {
                this.selected.push(list);
            }
       },

        removeSelectedDay(value) {
            const list = this.selected.filter((item) => {
              return item.value !== value;
           });
            this.selected = list;
        },

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

    reset() {
      this.regSuccess = false;
      this.responseSpin = false;
      this.responseSuccess = null;
      this.responseFail = null;
      this.responseMessage = null;
    },
    submit() {
    
      if(!Number(this.form.price) && !confirm("menu item without a price can't be ordered online")) return;
     
      this.responseSpin = true;
      this.form.processing = true;
      this.responseSuccess = null;
      this.responseFail = null;
      this.responseMessage = "Updating Item...";

    

      const ids = this.selected.map((item) => item.id )
      var formData = new FormData(); 
          ids.forEach((id) => {
                formData.append("category_ids[]", id);
           });
         
           this.form.selectedCategories.forEach((sel) => {
            formData.append("selectedCategories[]", sel);
           });
  
          formData.append("image", this.form.file);
          formData.append("name", this.form.name);
          formData.append("price", this.form.price);
          formData.append("description", this.form.description);
       
          axios
        .post(route("vendors.edit-menu-category-item", { id: this.form.id }), formData,
        {
              headers: {
                  'Content-Type': 'multipart/form-data'
              }
         }
        )

        .then(({ data }) => {
          const result = data ;

      // fetch(route("vendors.edit-menu-category-item", { id: this.form.id }), {
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
      tippy("#priceSymbolInfo", {
      content:"You can change the currency in settings, under  menu highlight",
    });
  },

};
</script>
