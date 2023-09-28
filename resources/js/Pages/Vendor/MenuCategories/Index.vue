<template>

      <data-table
      :deleteAsButton="true"
      :filterable="false"
      :noAction="true"
      :noDateFilter="true"
      :models="models"
      :loading="loading"
      :loaded="loaded"
      :title="'Category'"
      :extendedTitle="'Categories'"
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
      :createLink="route('vendors.create-menu-category-page')"
      :indexLink="route('vendors.edit-menu-page', { id: menu_id })"
      :viewLink="route('vendors.edit-menu-category-page')"       
      
    >
      <template  #notFound>
        <div class="max-w-full">
          <empty-space 
                  :title="'Oops!... Its Empty'"
                  :description="'94% of diners check a restaurant menu online before deciding to visit You can start building your online QR code menu with one click'"
                  :page="'Category'"
                  @handleAsbutton="createButton()"
                  :image="'/images/empty_images/4.png'"
                  :asButton="true"
                    />
          </div>
      </template>
    </data-table>
  

    <jet-modal :show="showCreateCate" @close="showCreateCate = !showCreateCate" >
         <div class="flex  justify-center items-center mb-10">
          <create-menu-category
                        class="b-g-white"
                        :vendor="vendor"
                        :menus="menus"
                        :is_admin="true"
            ></create-menu-category>
         </div>
    </jet-modal>

    <jet-modal :show="showEditCate" @close="showEditCate = !showEditCate" >
         <div class="flex  justify-center items-center mb-10">
          <edit-menu-category
            class="b-g-white"
            :vendor="vendor"
            :menus="menus"
            :selectedMenus="menus"
            :category="current_category_edit"
            :is_admin="true"
          ></edit-menu-category>
         </div>
    </jet-modal>
</template>

<script>
import VendorLayout from "@/Layouts/VendorLayout.vue";
import EditMenuCategory from "@/components/vendor/VendorEditMenuCategory.vue";
import CreateMenuCategory from "@/components/vendor/VendorCreateMenuCategory.vue";
export default {
  components: {
    VendorLayout,
    EditMenuCategory,
    CreateMenuCategory,
  },
  props: {
    title: String,
    filters: Object,
    menus: Object,
  },
  data() {
    return {
      menus: this.menus,
      showCreateCate:false,
      showEditCate:false,
      menuCategoryBoxShow:false,
      models: {
        data: [],
        links: [],
        total: 0,
      },
      loading: false,
      loaded: false,
      vendor: this.$page.props.auth.vendor,
      params: { menu_id: this.filters?.menu_id},
      actionData: {},
      menu_id: this.filters?.menu_id,
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
          name: "description",
          db_name: "description",
          sortable: true,
        },
        {
          name: "Items",
          db_name: "items",
          sortable: true,
        },
     
      ],
       actions: [
        {
         value:"delete",
         name: "Delete"
        }
       ],
      birthday: this.filters?.birthday,
      birthday_date: this.filters?.birthday_date,
      current_category_edit: null,
    };
  },
  computed: {
    rows() {
      var modelRows = [];

      this.models.data.forEach((el) => {
        modelRows.push([
          el.id,
          el.name,
          el.description,
          el.items.length
        ]);
      });

      return modelRows;
    },
  },
  methods: { 
    editButton(data){
      const category =  this.models?.data.find(el => el.id == data.id);
      this.current_category_edit = category;
      this.showEditCate = true;
    },
    createButton(){
         this.showCreateCate = !this.showCreateCate;
    },
    getItemNames(item){
      if(item.length > 2) {
          const names = item.slice(0,1).map((item) =>   (`
          <span class="bg-gray-100  text-black px-3 py-2 rounded-md 
                          whitespace-nowrap"> ${item.name}</span>
         `));
            
            
           return names +  "+" + (item.length - 2);

      }else{

         return item.slice(0,1).map((item) =>   (`
          <span class="bg-gray-100  text-black px-3 py-2 
                           rounded-md  whitespace-nowrap"> ${item.name}</span>
          `
            ));
        
      }
    
     },
    openCategoryBox(){
      
      const menucate = localStorage.getItem('menuCategoryvendorId');
        if(!menucate  && menucate  != this.$page.props.auth.vendor.id){
          this.menuCategoryBoxShow = true;
        }
  },

   fireAction(data) {
      var action = data.action;
      var ids = data.ids;
      var all = data.all;

      if (!action) {
        return;
      }

      if (ids.length <= 0) {
        alert("Please select a category to delete");
        return;
      }
          if(confirm("Are you sure you want to delete ?")){   
         this.$inertia.post(
          route("vendors.menu-action") + "?without_async=true",
          {
            ids: ids,
            action: action,
            action_model: "menuCategory",
          },
          {
            onFinish: (visit) => {
              this.loadData();
            },
          }
        );  
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
        .get(route("data.vendors.menu-categories", { ...this.params, menu_id: this.menu_id }))
        .then((result) => {

          this.models = result.data.models;
          this.vendor = result.data.vendor;
          this.loading = false;
          this.loaded = true;
          this.loadReservationsCount();
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
    this.openCategoryBox();
    // console.log("this.menus:", this.menus);
    // console.log("menus Filter:", this.menus?.filter((item) => item.id == this.filters?.menu_id))
  },
};
</script>
