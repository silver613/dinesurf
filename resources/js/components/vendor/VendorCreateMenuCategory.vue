<template>
  <div class="reservation-auth-card bg-white">
   
    <div class="auth-card-body text-left">
      <form
        @submit.prevent="submit"
        class="flex flex-col w-full"
        v-if="!regSuccess"
      >
        <h3 class="mb-3">Create a Category</h3>
       

        <div class="w-full">
          <jet-label>Selected Menu: </jet-label>
          <input
            type="text"
            v-model="menuName"
            class="auth-card-input mt-1  cursor-not-allowed"
            placeholder="menu "
            multiple="true"
            disabled

          />
        </div>

        <div class="flex relative flex-col w-full">
          <jet-label>Name: </jet-label>
          <textarea
             id="addcategories"
            type="text"
            v-model="form.name"
            class="auth-card-input"
            placeholder="Enter all categories"
            multiple="true"
            @keyup=" handleCategory()"
            
          ></textarea>

          <!-- <button type="button"  title="add to group" @click="handleCategory()"  class="absolute bg-[#f4f4f4] py-2 px-3 pt-[0.6rem] right-[2px] bottom-8 rounded-rt-md">
                 <svg width="20" height="20" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M27.5 3.74976C27.5 2.75519 27.1049 1.80137 26.4017 1.09811C25.6984 0.394844 24.7446 -0.000244141 23.75 -0.000244141C22.7554 -0.000244141 21.8016 0.394844 21.0984 1.09811C20.3951 1.80137 20 2.75519 20 3.74976V19.9998H3.75C2.75544 19.9998 1.80161 20.3948 1.09835 21.0981C0.395088 21.8014 0 22.7552 0 23.7498C0 24.7443 0.395088 25.6981 1.09835 26.4014C1.80161 27.1047 2.75544 27.4998 3.75 27.4998H20V43.7498C20 44.7443 20.3951 45.6981 21.0984 46.4014C21.8016 47.1047 22.7554 47.4998 23.75 47.4998C24.7446 47.4998 25.6984 47.1047 26.4017 46.4014C27.1049 45.6981 27.5 44.7443 27.5 43.7498V27.4998H43.75C44.7446 27.4998 45.6984 27.1047 46.4017 26.4014C47.1049 25.6981 47.5 24.7443 47.5 23.7498C47.5 22.7552 47.1049 21.8014 46.4017 21.0981C45.6984 20.3948 44.7446 19.9998 43.75 19.9998H27.5V3.74976Z" fill="#1F2937"/>
                </svg>
          </button> -->
     </div>

     <div class="flex  -mt-5 mb-3 flex-wrap"  id="categoriesInput">
                                    <div
                                    class="border p-2 mr-3 mt-2 "
                                    v-for="(item, index) in categories"
                                    :key="index"
                                    >
                                    <span>{{ item  }}</span>
                                    <span class="ml-2 ">
                                        <i
                                        @click="handleCategorySelected(item)"
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
                  placeholder="Enter Category Description(optional)"
                  
                />
          </div>
    </div> -->

        <!-- <div class="flex flex-col w-full" 
            v-if="menus">

            
            <muiltiple-selector 
                                    :label="'Selected Menu:'" 
                                    :list="menusList" 
                                    @updateSelected="updateSelected"
                                    :selected="selected"
                                    @removeSelected="removeSelected"
                  /> -->

          <!-- <jet-label>Select Menu:</jet-label>
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
            v-model="form.menu_ids"
            name="source"
            id="source"
            multiple

          >
           <option selected disabled >Select Menu</option>
            <option   v-for="menu in menus" :key="menu.id"  :value="menu.id">{{menu.name}}</option>
          </select> -->
        <!-- </div> -->


      
       

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
          Create Category
        </button>
      </form>
      <div
        class="flex flex-col justify-center items-center my-5"
        v-if="regSuccess"
      >
      <img src="/images/success-svg2.svg" class="h-16 md:h-32" />
        <h4 class="text-center my-3  font-inter text-xs text-[#000000] font-normal">Category Created Successfully</h4>
        <div class="flex flex-col justify-around  space-y-3 w-full">
          <div class=" flex flex-wrap justify-center  space-x-4 items-center w-full">
            <Link
              style="padding: 15px 40px"
              class="bg-[#201F1F] text-white p-2 px-4 rounded-md active:bg-gray-900"
              :href="route('vendors.edit-menu-page', {id: menus[0]?.id }) + '?tab=categories'"
            >
              View Categories
            </Link>
            <div   id="addItems">
                <Link
                
                :href="route('vendors.edit-menu-page', {id: menus[0]?.id }) + '?tab=items'"
                  style="padding: 15px 40px"
                  class="bg-[#201F1F] text-white p-2 px-4 rounded-md active:bg-gray-900  "
                >
                  Add Item
              </Link>
          </div>

        </div>
          <div  class="w-full flex justify-center items-center">
            
            <button
              style="padding: 15px 40px"
              class=" active:bg-gray-900   rounded-md  border  border-[#201F1F] p-2 px-4"
              @click="reset()"
            >
             Create  Category
            </button>
            
          </div>
        </div>
      </div>
    </div>
  </div>


  <item-popup  :show="this.menuItemBoxShow"   @close="this.menuItemBoxShow = !this.menuItemBoxShow"  />
  
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
import MuiltipleSelector from "../MultipleSelector.vue";
import tippy from "tippy.js";
import "tippy.js/dist/tippy.css"; // optional for styling

export default {
  components: {
    MuiltipleSelector,
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
    menus: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      menuName: this.menus[0]?.name,
      menusList: this.menus?.map((item) => { return { id: item.id, value: item.id, name: item.name}}),
      menuItemBoxShow:false,
      showUsers: false,
      exampleModalShowing: false,
      today: new Date(),
      phone: null,
      form: this.$inertia.form({
        name: null,
        menu_ids:[],
        description:null
      }),
      regSuccess: false,
      // response
      responseSpin: false,
      responseSuccess: null,
      responseFail: null,
      responseMessage: null,
      users:[],
      options: ['list', 'of', 'options'],
      selected:[],
      categories:[]
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

    handleCategorySelected(value){
         const list = this.categories.filter((item) => {
              return item !== value;
           });
            this.categories = list;
    },
     
    handleCategory(){
      const data = this.form.name.split(",");
      this.categories =  data.filter((item) => item !== ' ');
    },
    updateSelected(e){
            const val = Number(e.target.value);
            const list = this.menusList.find((item) => item.id === val);
          if (!this.selected.includes(list) ) {
                this.selected.push(list);
            }     
     },

    removeSelected(value) {
            const list = this.selected.filter((item) => {
              return item.value !== value;
           });
            this.selected = list;
        },

    openItemBox(){
      
      const menuvendorId = localStorage.getItem('menuItemvendorId');
      if(menuvendorId && menuvendorId == this.$page.props.auth.vendor.id){
          this.$inertia.get( route("vendors.menu-category-items"));
        }else{
           this.menuItemBoxShow = true;
        }
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
      
      this.form.menu_ids = this.selected.map((item) => {
        return  item.id;
      })
      this.form.name = this.categories;

      if(this.categories.length < 1){
        toastr.error("Add atleast one category to proceed!");
        return;
      }

      
      this.responseSpin = true;
      this.form.processing = true;
      this.responseSuccess = null;
      this.responseFail = null;
      this.responseMessage = "Creating Category...";

      fetch(route("vendors.create-menu-category"), {
        method: "POST",
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": this.$page.props.csrf_token,
        },
        body: JSON.stringify(this.form),
      })
        .then((result) => {
          return result.json();
        })
        .then((result) => {

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
      // console.log("user_id: ", this.form.user_id);
      if (!this.form.user_id) {
        this.form.user_query = null;
      }
      this.showUsers = false;
      return;
    },
    setSelected(){
       this.selected = this.menusList;
    }
  },
  mounted() {
    this.setSelected();

    tippy("#addcategories", {
      content:"Enter all categories with comma (,) seperated",
      arrow: true,
      distance: 3,
    });

    tippy("#categoriesInput", {
      content:"The categories created would be visible under the menu selected",
      arrow: true,
      distance: 3,
    });


    tippy("#addItems", {
      content:"Click to add items to your menu.",
      arrow: true,
      distance: 3,
    });
    

  },
};
</script>
