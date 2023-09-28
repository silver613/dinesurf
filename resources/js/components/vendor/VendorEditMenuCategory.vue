<template>
  <div class=" auth-card2 reservation-auth-card bg-white">
    <div class="auth-card-body text-left">
      <form
        @submit.prevent="submit"
        class="flex flex-col w-full"
        v-if="!regSuccess"
      >
        <h3 class="mb-3">Category Information</h3>

        <!-- <div class="flex flex-col w-full mb-3">
          <jet-label class="font-bold">ID: {{ form.id }}</jet-label>
        </div> -->
     
     <!-- <div class="mb-2">
  
          <jet-label class="font-bold">Menus Selected:</jet-label>

         <ul v-if="selectedMenus" style="list-style:circle" class="ml-5">
             <li v-for="menu in selectedMenus" :key="menu.id">{{menu.name}}</li>
        </ul>
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
                      placeholder="Enter Category Description(optional)"
                      
                    />
              </div>
        </div>

        <div class="flex flex-col w-full my-5" 
            v-if="menus">
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
           <!-- <Multiselect v-model="form.menu_ids" :options="options"></Multiselect> -->

           <muiltiple-selector 
                                    :label="'Select Menu:'" 
                                    :list="menusList" 
                                    @updateSelected="updateSelected"
                                    :selected="selected"
                                    @removeSelected="removeSelected"
                  />
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
          Update Category
        </button>
      </form>
      <div
        class="flex flex-col justify-center items-center my-10"
        v-if="regSuccess"
      >
      <img src="/images/success-svg2.svg" class="h-16 md:h-32" />
        <h4 class="text-center my-3  font-inter text-xs text-[#000000] font-normal">Category Updated Successfully</h4>

        <div class="flex flex-col justify-around  space-y-3 w-full">
          <div class=" flex flex-wrap justify-center  space-x-4 items-center w-full">
            <Link
              style="padding: 15px 40px"
              class="bg-[#201F1F] text-white p-2 px-4 rounded-md active:bg-gray-900"
              :href="route('vendors.edit-menu-page', {id: menus[0]?.id }) + '?tab=categories'"
            >
              View Categories
            </Link>
            <Link
            :href="route('vendors.edit-menu-page', {id: menus[0]?.id }) + '?tab=items'"
              style="padding: 15px 40px"
              class="bg-[#201F1F] text-white p-2 px-4 rounded-md active:bg-gray-900  "
            >
              Add Item
          </Link>

        </div>
          <div  class="w-full flex justify-center items-center">
            
            <button
              style="padding: 15px 40px"
              class=" active:bg-gray-900   rounded-md  border  border-[#201F1F] p-2 px-4"
              @click="reset()"
            >
            Back To Editing
            </button>
            
          </div>
        </div>
        <!-- <div class="flex justify-around">
          <div>
            <Link
              style="padding: 15px 40px"
              class="jet-btn active:bg-gray-900 mx-2"
              :href="route('vendors.menu-categories')"
            >
              View Categories
            </Link>
            <Link
            :href="route('vendors.create-menu-category-item-page')"
              style="padding: 15px 40px"
              class="jet-btn active:bg-gray-900 mx-2  "
            >
              Add Item
          </Link>
            <button
              style="padding: 15px 40px"
              class="jet-btn active:bg-gray-900 mx-2 md:mt-2"
              @click="reset()"
            >
              Back To Editing
            </button>
          </div>
        </div> -->
      </div>
    </div>



    <item-popup  :show="this.menuItemBoxShow"   @close="this.menuItemBoxShow = !this.menuItemBoxShow"  />
  

   
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
import MuiltipleSelector from "../MultipleSelector.vue";

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
    menus: {
      type: Object,
      required: true,
    },

     category: {
      type: Object,
      required: true,
    },
    selectedMenus: {
        type:Object,
        required:true
    },
   
    status: String,
  },
  data() {
    return {
      menusList: this.menus?.map((item) => { return { id: item.id, value: item.id, name: item.name}}),
      showUsers: false,
      menuItemBoxShow:false,
      exampleModalShowing: false,
      today: new Date(),
      form: this.$inertia.form({
        id: this.category?.id,
        name: this.category?.name,
        menu_ids:this.selectedMenus?.map((item) => item.id),
        selectedMenus:this.selectedMenus?.map((item) => item.id),
        description:this.category?.description
      }),
      regSuccess: false,
      // response
      responseSpin: false,
      responseSuccess: null,
      responseFail: null,
      responseMessage: null,
      users: [],
      selected: this.selectedMenus
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
      this.responseSpin = false;
      this.responseSuccess = null;
      this.responseFail = null;
      this.responseMessage = null;
    },
    submit() {
      // alert("martins i'm working on it");
      // return;
      this.responseSpin = true;
      this.form.processing = true;
      this.responseSuccess = null;
      this.responseFail = null;
      this.responseMessage = "Updating Category...";
      this.form.menu_ids = this.selected.map((item) => {
        return  item.id;
      })

      console.log("edit menu category file:", this.form)

      fetch(route("vendors.edit-menu-category", { id: this.form.id }), {
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
      console.log("user_id: ", this.form.user_id);
      if (!this.form.user_id) {
        this.form.user_query = null;
      }
      this.showUsers = false;
      return;
    },
  },
 
};
</script>
