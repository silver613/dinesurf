<template>
  <vendor-layout>
    <template #header>
    <header-text   :title="'Edit Guest'" />
      
    </template>
    <div class="md:py-1 px-4">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-center">
          <edit-guest
            class="b-g-white"
            :vendor="vendor"
            :guest="guest"
            :reservations="reservations"
            :is_admin="true"
          ></edit-guest>
        </div>
      </div>
    </div>
    <!-- Reservations -->
    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div
          v-if="reservations"
          class="
            restaurant-comp
            bg-white
            overflow-hidden
            shadow-xl
            pt-3
            sm:rounded-xl
          "
        >
          <h3 class="text-lg font-medium text-gray-900 mb-10 capitalize px-8">
            Reservations ({{ reservations.length }})
          </h3>
          <mini-data-table
            v-if="reservations.length > 0"
            :models="reservations"
            :title="'reservations'"
            :filters="[]"
            :columns="columns"
            :actions="actions"
            :size="reservations.length"
            :rows="reservations_rows"
            @runAction="runAction"
            :viewLink="route('vendors.reservation')"
          ></mini-data-table>
          <div class="w-full p-4" v-else>No Reservations</div>
        </div>
      </div>
    </div>
  </vendor-layout>
</template>

<script>
import VendorLayout from "@/Layouts/VendorLayout.vue";
import EditGuest from "@/components/vendor/VendorEditGuest.vue";
export default {
  props: ["vendor", "guest", "reservations"],
  components: {
    VendorLayout,
    EditGuest,
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
      actions: [
        {
          value: "approved",
          name: "Approve",
        },
        {
          value: "cancelled",
          name: "Cancel",
        },
        {
          value: "un-approve",
          name: "Make Pending",
        },
        {
          value: "email_users",
          name: "Send E-Mail",
        },
        {
          value: "text_users",
          name: "Send Text",
        },
      ],
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
