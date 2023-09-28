<template>
  <div class="auth-card2 reservation-auth-card bg-white">
   
    <div class="auth-card-body text-left">
      <form
        @submit.prevent="submit"
        class="flex flex-col w-full"
        v-if="!regSuccess"
      >
        <h3 class="mb-3">Create an Item</h3>
       
    <div class="flex w-full space-x-4  items-center  " id="itemsInput">
      <div class="flex flex-col w-full">
          <jet-label>Name: </jet-label>
          <input
            type="text"
            v-model="form.name"
            class="auth-card-input  mt-1"
            placeholder="Enter Name"
            
          />
        </div>
        
        <div class="flex flex-col w-full">
          <jet-label>Price: </jet-label>
          <input
            type="number"
            v-model="form.price"
            class="auth-card-input  mt-1"
            placeholder="Enter Price"
            
            
          />
        </div>

        <div class=" pb-3 h-space-10 ">
          <button type="button"  title="add to group" @click="handleItems()"  class=" bg-[#f4f4f4]  py-3 px-5  rounded-md">
            <svg width="20" height="20" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M27.5 3.74976C27.5 2.75519 27.1049 1.80137 26.4017 1.09811C25.6984 0.394844 24.7446 -0.000244141 23.75 -0.000244141C22.7554 -0.000244141 21.8016 0.394844 21.0984 1.09811C20.3951 1.80137 20 2.75519 20 3.74976V19.9998H3.75C2.75544 19.9998 1.80161 20.3948 1.09835 21.0981C0.395088 21.8014 0 22.7552 0 23.7498C0 24.7443 0.395088 25.6981 1.09835 26.4014C1.80161 27.1047 2.75544 27.4998 3.75 27.4998H20V43.7498C20 44.7443 20.3951 45.6981 21.0984 46.4014C21.8016 47.1047 22.7554 47.4998 23.75 47.4998C24.7446 47.4998 25.6984 47.1047 26.4017 46.4014C27.1049 45.6981 27.5 44.7443 27.5 43.7498V27.4998H43.75C44.7446 27.4998 45.6984 27.1047 46.4017 26.4014C47.1049 25.6981 47.5 24.7443 47.5 23.7498C47.5 22.7552 47.1049 21.8014 46.4017 21.0981C45.6984 20.3948 44.7446 19.9998 43.75 19.9998H27.5V3.74976Z" fill="#1F2937"/>
                </svg>
          </button>
        </div>
      </div> 

            <div class="flex  -mt-5 mb-3 flex-wrap">
                                    <div
                                    class="border p-2 mr-3 mt-2 "
                                    v-for="(item, index) in items"
                                    :key="index"
                                    >
                                    <span v-if=" item.price">{{ item.name }}, {{   numberWithCommas(
                                                                          item.price,
                                                                          this.vendor.average_menu_price_currency
                                                                        ) }}</span>

                                          <span v-else>{{ item.name }}</span>


                                    <span class="ml-2 ">
                                        <i
                                        @click="handleItemSelected(item)"
                                        class="fa fa-remove cursor-pointer"
                                        ></i
                                    ></span>
                                    </div>
               </div>
            
          <!-- <div class="flex flex-col w-full">
                <jet-label>Description(optional): </jet-label>
                <div class="flex flex-row justify-between">
                    <input
                        type="text"
                        v-model="form.description"
                        class="auth-card-input"
                        placeholder="Enter Item Description(optional)"
                      />
                </div>
          </div> -->

        <div  class="flex flex-col w-full my-2" 
            v-if="categories.length > 0">
            <jet-label>Select Category: </jet-label>
            <select
            class="
              auth-card-input
              border-gray-300
              focus:border-indigo-300
              focus:ring
              focus:ring-indigo-200
              focus:ring-opacity-50
              rounded-md
              shadow-sm
              mt-1
              block
              w-full
            "
            v-model="form.category_ids"
            name="category"
            id="category"

          >
           <option selected disabled >Select Category</option>
            <option   v-for="category in categories" :key="category.id"  :value="category.id">{{category.name}}</option>
          </select>

                <!--     <muiltiple-selector 
                                   :id="'categoryInput'"
                                    :label="'Select Category:'" 
                                    :list="categoriesList" 
                                    @updateSelected="updateSelected"
                                    :selected="selected"
                                    @removeSelected="removeSelectedDay"
                  /> -->
        </div>

        <div class="text-center mb-2">
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
          Create Item
        </button>
      </form>
      <div
        class="flex flex-col justify-center items-center my-5"
        v-if="regSuccess"
      >
      <img src="/images/success-svg2.svg" class="h-16 md:h-32" />
        <h4 class="text-center my-3  font-inter text-xs text-[#000000] font-normal">Item Created Successfully</h4>
        
         <div class="flex justify-around">
          <div class=" flex flex-wrap justify-center  space-x-4 items-center w-full">
            <Link
              style="padding: 15px 40px"
              class="bg-[#201F1F] text-white p-2 px-4 rounded-md active:bg-gray-900"
              :href="route('vendors.edit-menu-page', {id: this.menus[0]?.id }) + '?tab=items'"
            >
              View Items
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
    status: String,
    categories: {
      type: Object,
      required: true,
    },
    menus: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      categoriesList: this.categories?.map((item) => { return { id: item.id, value: item.id, name: item.name}}),
      showUsers: false,
      exampleModalShowing: false,
      today: new Date(),
      phone: null,
      form: this.$inertia.form({
        name: null,
        category_ids:[],
        price:null,
        description: null,
        file: null
      }),
      image_value: null,
      regSuccess: false,
      // response
      responseSpin: false,
      responseSuccess: null,
      responseFail: null,
      responseMessage: null,
      users: [],
    options: ['list', 'of', 'options'],
    selected: [],
    items: []
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
    
    handleItems(){

      if(!Number(this.form.price) && !confirm("menu item without a price can't be ordered online")) return;
      if(this.form.name === null)return;
         const findD = this.items.find((item) => item.name === this.form.name);
      if(findD) return;
         const data = {
          price: this.form.price,
          name: this.form.name
         };
          this.items.push(data);
          this.form.name = null;
          this.form.price = null;
    },
    handleItemSelected(item){
      const list = this.items.filter((el) => {
              return el.name !== item.name && el.price !== item.price;
           });
            this.items = list;
    },
    updateSelected(e){
            const val = Number(e.target.value);
            const list = this.categoriesList.find((item) => item.id === val);
          if (!this.selected.includes(list)  && this.selected.length < 2 && this.selected.length !== 2 ) {
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
      this.form = this.$inertia.form({
       name:null,
       menu_id:null
        
      });
      this.responseSpin = false;
      this.responseSuccess = null;
      this.responseFail = null;
      this.responseMessage = null;
    },
    submit() {
     

      if(this.items.length < 1){
        toastr.error("add atleast one item.");
        return;
      }

      if(this.form.category_ids === null ){
        toastr.error("Select atleast one category.");
        return;
      }

      this.responseSpin = true;
      this.form.processing = true;
      this.responseSuccess = null;
      this.responseFail = null;
      this.responseMessage = "Creating Item...";

      // const ids = this.selected.map((item) => {
      //   return  item.id;
      // })
    
       var formData = new FormData();
        //  ids.forEach((id) => {
        //         formData.append("category_ids[]", id);
        //    });
         formData.append("category_ids[]", this.form.category_ids); 
          formData.append("image", this.form.file);
         
          this.items.forEach((el) => {
            formData.append("name[]", el.name);
            formData.append("price[]", el.price);
        });

          formData.append("description", this.form.description);
       
          axios
        .post(route("vendors.create-menu-category-item"), formData,
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
            this.form.processing = false;
            this.responseMessage = result.message + "!";
            this.regSuccess = true;
        
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
      if (!this.form.user_id) {
        this.form.user_query = null;
      }
      this.showUsers = false;
      return;
    },
  },
  mounted() {

    // console.log("menus:", this.menus)

    tippy("#priceSymbolInfo", {
      content:"You can change the currency in settings, under menu highlights",
      arrow: true
    });


    tippy("#categoryInput", {
      content:"The items created would be visible under the category selected",
      arrow: true,
      distance: 3,
    });

    tippy("#itemsInput", {
      content:"Create multiple items for each category all at once, input the name, price, and click add to put it in the group",
      arrow: true,
      arrowType: 'round',
      distance: 3,
    });

  },
};
</script>
