<template>
  <vendor-layout>
    <template #header>
      <header-text :title="'Edit Category'"  />
    </template>
    <div class="md:py-12 px-4">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-center">
           <edit-menu-category
            class="b-g-white"
            :vendor="vendor"
            :menus="menus"
            :selectedMenus="selectedMenus"
            :category="category"
            :is_admin="true"
          ></edit-menu-category>
        </div>
      </div>
    </div>
   


  </vendor-layout>
</template>

<script>
import VendorLayout from "@/Layouts/VendorLayout.vue";
import EditMenuCategory from "@/components/vendor/VendorEditMenuCategory.vue";
export default {
  props: ["vendor", "menus","category", "selectedMenus"],
  components: {
    VendorLayout,
    EditMenuCategory,
  },
  data() {
    return {
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
};
</script>
