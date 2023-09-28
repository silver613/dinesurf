<template>


      <data-table
      :deleteAsButton="true"
      :isDraggable="true"
      :noDateFilter="true"
      :noAction="true"
      :models="models"
      :loading="loading"
      :loaded="loaded"
      :title="'Item'"
      :filters="filters"
      :columns="columns"
      :actions="actions"
      :size="size"
      :rows="rows"
      @runAction="fireAction"
      @loadData="loadData"
      :createAsButton="true"
      @createButton="createButton()"
      :editAsButton="true"
      @editButton="editButton"
      :indexLink="route('vendors.edit-menu-page', { id: menu_id }) + '?tab=items'"
      :externalFilters="{
        category_id:category_id
      }"
    >
    
  

    <template  #notFound>
        <div class="max-w-full">
          <empty-space 
                  :title="'Oops!... Its Empty'"
                  :description="'94% of diners check a restaurant menu online before deciding to visit You can start building your online QR code menu with one click'"
                  :page="'Item'"
                  @handleAsbutton="createButton()"
                  :image="'/images/empty_images/4.png'"
                  :asButton="true"
                    />
          </div>
      </template>

    <template #outerFilters>
        <!-- <div class="flex flex-wrap"> -->
          <div class="w-[48%] mr-2 ">
            <label for="status" class="block text-sm font-light text-gray-400">By Category:</label>

            <select
              id="category_id"
              name="category_id"
              v-model="category_id"
              class="block form-control w-full "
            >
              <option :value="null">Select Category</option>
              <option
                    v-for="(category, categoryIndex) in this.categories"
                    :key="categoryIndex"
                    :value="category.id"
                  >
                    {{ category.name }}
                  </option>
            </select>
          </div>
       
        <!-- </div> -->
      </template>
   
    </data-table>

 <!-- <item-popup  :show="this.menuItemBoxShow"   @close="this.menuItemBoxShow = !this.menuItemBoxShow"  /> -->
    


 <jet-modal :show="showCreateItem" @close="showCreateItem = !showCreateItem" >
         <div class="flex  justify-center items-center mb-10">
          <create-menu-category-item
                        class="b-g-white"
                        :vendor="vendor"
                        :categories="this.categories"
                        :is_admin="true"
                        :menus="this.menus"
             ></create-menu-category-item>
         </div>
    </jet-modal>

    <jet-modal :show="showEditItem" @close="showEditItem = !showEditItem" >
         <div class="flex  justify-center items-center mb-10">
              <edit-menu-category
                class="b-g-white"
                :vendor="this.vendor"
                :categories="this.categories"
                :selectedCategories="current_item_edit?.categories"
                :item="current_item_edit"
                :is_admin="true"
                :menus="this.menus"
              ></edit-menu-category>
         </div>
    </jet-modal>
  
</template>

<script>
import VendorLayout from "@/Layouts/VendorLayout.vue";
import CreateMenuCategoryItem from "@/components/vendor/VendorCreateMenuCategoryItem.vue";
import EditMenuCategory from "@/components/vendor/VendorEditMenuCategoryItem.vue";
export default {
  components: {
    VendorLayout,
    CreateMenuCategoryItem,
    EditMenuCategory
  },
  props: {
    title: String,
    filters: Object,
    categories: Object,
    menus: Object
  },
  data() {
    return {
      menuItemBoxShow:false,
      category_id:null,
      models: {
        data: [],
        links: [],
        total: 0,
      },
      loading: false,
      loaded: false,
      vendor: this.$page.props.auth.vendor,
      params: {},
      actionData: {},
      approved: this.filters?.approved,
      showEmailModal: false,
      showTextModal: false,
      columns: [
        {
          name: "id",
          db_name: "id",
          sortable: true,
        },
        {
          name: "name",
          db_name: "name",
          sortable: true,
        },
        {
          name: "price",
          db_name: "price",
          sortable: true,
        },
        {
          name: "Category",
          db_name: "category",
          sortable: false,
        },
        {
          name: "description",
          db_name: "description",
          sortable: true,
        },
     

        
      ],
       actions: [
        {
         value:"delete",
         name: "Delete"
        },
        {
         value:"toggle",
         name: "Toggle"
        }
       ],
      birthday: this.filters?.birthday,
      birthday_date: this.filters?.birthday_date,
      menu_id: this.filters?.menu_id,
      current_item_edit: null,
      showCreateItem:false,
      showEditItem: false
    };
  },
  computed: {
    rows() {
      var modelRows = [];

      this.models.data.forEach((el) => {
        modelRows.push([
          el.id,
          el.name,
          this.numberWithCommas(
          el.price,
          this.vendor.average_menu_price_currency),
          this.getCategoryNames(el.categories),
          el.description
        ]);
      });

      return modelRows;
    },
  },
  methods: {

    editButton(data){
      const item =  this.models?.data.find(el => el.id == data.id);
      this.current_item_edit = item;
      this.showEditItem = true;
    },
    createButton(){
         this.showCreateItem = !this.showCreateItem;
    },

    getDescription(desc){
        return `
        <div class="bg-red-400 w-[5rem]  whitespace-wrap">
          ${desc}
        </div>
        `
    },

     getCategoryNames(item){
      if(item.length > 1) {
          const names = item.slice(0,1).map((item) =>   (`
          <span class="bg-gray-100  text-black px-3 py-2 rounded-md 
                          whitespace-nowrap"> ${item.name}</span>
          `
            ));
            
           return names +  "+" + (item.length - 1);

      }else{

         return item.slice(0,1).map((item) =>   (`
          <span class="bg-gray-100  text-black px-3 py-2 
                           rounded-md  whitespace-nowrap"> ${item.name}</span>
          `
            ));
        
      }
    
     },
    openItemBox(){
      
      const menuItem = localStorage.getItem('menuItemvendorId');
        if(!menuItem && menuItem != this.$page.props.auth.vendor.id){
          this.menuItemBoxShow = true;
        }
      },
   
    fireAction(data) {
      var action = data.action;
      var ids = data.ids;
      var all = data.all;

     if(action === 'dragged'){
     
      this.models.data.map((item, index) => {  item.index = index + 1 });
      axios.put(route("vendors.sorting-menu-items"), {
         menus: this.models.data
        }).then((result) => {
          toastr.success(result.data.message);
        });

     }

     if(action === 'toggle'){

       axios.put(route("vendors.update-menu-item-status"), {
         id: ids[0]
        }).then((result) => {
          toastr.success(result.data.message);
        });

     }

     if(action === 'delete'){
     
  
      if (!action) {
        return;
      }

      if (ids.length <= 0) {
        alert("Please select an item to delete");
        return;
      }
    if(confirm("Are you sure you want to delete ?")){   
         this.$inertia.post(
          route("vendors.menu-action") + "?without_async=true",
          {
            ids: ids,
            action: action,
            action_model: "menuCategoryItem",
          },
          {
            onFinish: (visit) => {
              this.loadData();
            },
          }
        );   
    }

     }
   },
   
    loadData() {
      const urlSearchParams = new URLSearchParams(window.location.search);
      this.params = Object.fromEntries(urlSearchParams.entries());
    
      this.loaded = false;
      this.loading = true;
      this.models = {
        data: [],
        links: [],
        total: 0,
      };

      axios
        .get(route("data.vendors.menu-category-items", { ...this.params, menu_id: this.menu_id }))
        .then((result) => {
          this.models = result.data.models;
          this.vendor = result.data.vendor;
          this.loading = false;
          this.loaded = true;
          // this.loadReservationsCount();
          
        })
        .catch((err) => {
          this.loaded = false;
          this.loading = false;
        });
    },
    loadReservationsCount() {
      this.models.data.forEach((el) => {
        axios
          .get(
            route("data.vendors.count-guest-reservations", {
              guest_id: el.id,
            })
          )
          .then((result) => {
            el.reservations_count = result.data.count;
          })
          .catch((err) => {
            console.log(err);
          });
      });
    },
  },
  mounted() {
    this.loadData();
    this.openItemBox();

  },
};
</script>
