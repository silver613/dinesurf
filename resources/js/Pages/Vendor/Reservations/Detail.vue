<template>
  <vendor-layout>
    <template #header>
      <header-text   :title="title" />
    </template>

    <div class="md:py-12 px-4">
      <div   v-if="!reservation.created_by_vendor" class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div
          class="
            restaurant-comp
            bg-white
            overflow-hidden
            shadow-xl
            py-3
            px-4
            sm:rounded-lg
          "
        >
          <div class="w-full">
            <admin-single-reservation
             
              :reservation="reservation"
            ></admin-single-reservation>
          </div>
        </div>
      </div>
      <div class="flex justify-center">
        <vendor-edit-reservation
          v-if="reservation.created_by_vendor"
          :reservation="reservation"
          :vendor="vendor"
        >
        </vendor-edit-reservation>
      </div>
    </div>
  </vendor-layout>
</template>

<script>
import VendorLayout from "@/Layouts/VendorLayout.vue";
import VendorEditReservation from "@/components/vendor/VendorEditReservation.vue";

export default {
  props: ["vendor", "reservation", "title"],
  components: {
    VendorLayout,
    VendorEditReservation,
  },
};
</script>
