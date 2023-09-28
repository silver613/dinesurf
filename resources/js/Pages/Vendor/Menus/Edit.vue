<template>
  <vendor-layout>
    <template #header>
      <div  class="flex items-center space-x-2">
          <header-text   :title="  menu.name" />
          <button  @click="showEditMenu = !showEditMenu"  id="editMenu">
               <svg width="16" height="19" viewBox="0 0 16 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M14.4181 4.79167C14.7804 4.42942 14.9797 3.94833 14.9797 3.43658C14.9797 2.92483 14.7804 2.44375 14.4181 2.0815L12.8982 0.561583C12.536 0.199333 12.0549 0 11.5431 0C11.0314 0 10.5503 0.199333 10.189 0.560625L0 10.718V14.949H4.22913L14.4181 4.79167ZM11.5431 1.91667L13.064 3.43563L11.5402 4.95363L10.0203 3.43467L11.5431 1.91667ZM1.91667 13.0324V11.5134L8.66333 4.78783L10.1833 6.30775L3.43754 13.0324H1.91667ZM0 16.8657H15.3333V18.7824H0V16.8657Z" fill="#3B82F6"/>
               </svg>
          </button>
        
       </div>
    </template>
  

    <div>
      <div
          id="vendorTabs"
          class="flex justify-flex-start  event-tab px-10 text-center"
        >
          <div
            class="w-24 text-xs   font-bold"
            :class="tab == 'categories' ? 'active' : ''"
            @click="toggleTab('categories')"
          >
            Category
          </div>


          <div
            class="w-24 text-xs font-bold"
            :class="tab == 'items' ? 'active' : ''"
            @click="toggleTab('items')"
            id="itemTab"
          >
            Item
          </div>

        </div>

    </div>

        <div  v-if="tab == 'categories'">

                  <Categories :vendor="this.vendor" :filters="filters"  :menus="[this.menu]"/>
        </div>

        <div  v-if="tab == 'items'">

                    <items  :vendor="this.vendor" :filters="filters" :categories="categories" :menus="[this.menu]"/>
          </div>




   



    <jet-modal :show="showEditMenu" @close="showEditMenu = !showEditMenu" >
         <div class="flex  justify-center items-center mb-10">
           <edit-menu
            class="b-g-white"
            :vendor="vendor"
            :menu="menu"
            :is_admin="true"
          ></edit-menu>
         </div>
    </jet-modal>
  </vendor-layout>
</template>

<script>
import VendorLayout from "@/Layouts/VendorLayout.vue";
import EditMenu from "@/components/vendor/VendorEditMenu.vue";
import Categories from "../MenuCategories/Index.vue";
import Items from "../MenuCategoryItems/Index.vue";
import tippy from "tippy.js";
import "tippy.js/dist/tippy.css"; // optional for styling


export default {
  props: ["vendor", "menu", 'categories' ],
  components: {
    VendorLayout,
    EditMenu,
    Categories,
    Items
  },
  data() {
    return {
      tab: '',
      showEditMenu: false,
      filters: { menu_id: this.menu.id},
      columns: [
        {
          name: "created_at",
          db_name: "created_at",
          sortable: true,
        },
        {
          name: "id",
          db_name: "id",
          sortable: true,
        },
        {
          name: "user",
        },
        {
          name: "phone",
          db_name: "phone",
        },

        {
          name: "date",
          db_name: "date",
          sortable: true,
        },
        {
          name: "time",
        },
        {
          name: "party_size",
          db_name: "party_size",
          sortable: true,
        },
        {
          name: "status",
        },
        {
          name: "past",
        },
        // {
        //   name: "seating_preferences",
        // },
      ],
      actions: [ ],
      actionData: {},
    };
  },
  computed: {
    reservations_rows() {
      var modelRows = [];

      this.reservations.forEach((el) => {
        var statusColor = this.getModelStatusColor(el);
        modelRows.push([
          el.formatted_created_at,
          el.id,
          this.getUser(el),
          el.phone,
          el.formated_date,
          el.formated_time,
          el.party_size,
          '<span class="font-bold ' +
            statusColor +
            '">' +
            el.status +
            "</span>",
          el.past ? "yes" : "no",
          // el.seating_preferences,
        ]);
      });

      return modelRows;
    },
  },
  methods: {

    load(){
            const urlSearchParams = new URLSearchParams(window.location.search);
            const params = Object.fromEntries(urlSearchParams.entries());
            this.tab = params.tab ?  params.tab : 'categories';

       },


    toggleTab(tab) {
 
      this.$inertia.get(
        route('vendors.edit-menu-page', { id: this.menu.id }),
        { tab: tab },
        {
          preserveState: true,
          preserveScroll: true,
          onFinish: (visit) => {
            this.tab = tab;
          },
        }
      );
    },
    getModelStatusColor(reservation) {
      if (reservation.status == "approved") {
        return "text-green-700";
      }

      if (reservation.status == "pending") {
        return "text-yellow-500";
      }

      if (reservation.status == "failed" || reservation.status == "cancelled") {
        return "text-red-700";
      }

      return;
    },
    runAction(data) {
      var action = data.action;
      var ids = data.ids;
      var all = data.all;
      // alert("run action " + action + " for models " + [...ids]);

      if (!action) {
        return;
      }

      if (ids.length <= 0) {
        alert(
          "Please select a Reservation or some reservations you want send an E-Mail"
        );
        return;
      }

      if (action == "un-approve" || action == "un-cancel") {
        var r = confirm(
          "Are you sure you want to set this Reservation to pending?"
        );
        action = "pending";
      }

      if (action == "cancelled") {
        var r = confirm("Are you sure you want to cancel this reservation?");
      }

      if (action == "approved") {
        var r = confirm("Are you sure you want to approve this reservation?");
      }

      if (action == "email_users") {
        this.actionData = {
          ids: ids,
          action: action,
          allMatching: all,
          filters: this.filters,
          type: this.type,
          subject: null,
          message: null,
          banner: null,
          action_model: "reservation",
        };
        this.showEmailModal = true;
      }

      if (action == "text_users") {
        this.actionData = {
          ids: ids,
          action: action,
          allMatching: all,
          filters: this.filters,
          type: this.type,
          message: null,
          action_model: "reservation",
        };
        this.showTextModal = true;
      }

      if (!r) {
        return;
      }

      if (
        action == "un-approve" ||
        action == "un-cancel" ||
        action == "cancelled" ||
        action == "approved" ||
        action == "pending"
      ) {
        this.$inertia.post(route("vendors.toggle-reservation"), {
          ids: ids,
          action: action,
          allMatching: all,
          filters: this.filters,
          type: this.type,
        });
      }
    },
  },

  mounted(){


    this.load();

    tippy("#editMenu", {
      content:"Edit Menu",
      arrow: true,
      distance: 3,
    });

    tippy("#itemTab", {
      content:"Create Items",
      arrow: true,
      distance: 3,
      sticky : true,
      showOnLoad : true,
    });
  }
};
</script>
