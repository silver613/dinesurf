<template>
  <vendor-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        View Role
      </h2>
    </template>
    <div class="md:py-12 px-4">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-center">
          {{ role.name }}
        </div>
      </div>
    </div>
    <!-- Admins -->
    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div
          v-if="admins"
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
            Admins ({{ admins.length }})
          </h3>
          <!-- <mini-data-table
            v-if="admins.length > 0"
            :models="admins"
            :title="'admins'"
            :filters="[]"
            :columns="columns"
            :actions="actions"
            :size="admins.length"
            :rows="admins_rows"
            @runAction="runAction"
            :viewLink="route('vendors.admin')"
          ></mini-data-table>
          <div class="w-full p-4" v-else>No Admins</div> -->
        </div>
      </div>
    </div>
  </vendor-layout>
</template>

<script>
import VendorLayout from "@/Layouts/VendorLayout.vue";
import EditGuest from "@/components/vendor/VendorEditGuest.vue";
export default {
  props: ["role"],
  components: {
    VendorLayout,
  },
  data() {
    return {
      columns: [
        {
          name: "id",
          db_name: "id",
          sortable: true,
        },
        {
          name: "email",
          db_name: "email",
        },
      ],
      actions: [],
      actionData: {},
    };
  },
  computed: {
    admins() {
      return {
        data: this.role ? this.role.admins : [],
        length: this.role ? this.role.admins.length : 0,
      };
    },
    admins_rows() {
      var modelRows = [];

      if (this.role) {
        this.role.admins.forEach((el) => {
          modelRows.push([el.id, el.email]);
        });
      }

      return modelRows;
    },
  },
  methods: {},
};
</script>
