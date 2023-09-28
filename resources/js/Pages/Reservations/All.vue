<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ resource_name }}
      </h2>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" v-if="reservationsData">
        <div
          class="bg-white overflow-hidden shadow-xl py-3 px-4 sm:rounded-lg"
          v-if="reservationsData.data"
        >
          <div class="mb-3 flex items-center">
            <h3 class="text-lg font-medium text-gray-900 capitalize">
              {{ resource_name }} Reservations ({{ size }})
            </h3>
          </div>

          <div class="flex flex-col items-center">
            <template v-if="reservationsData.data.length > 0">
              <div
                class="w-full"
                v-for="(reservation, index) in reservationsData.data"
                :key="index"
              >
                <single-reservation
                  :reservation="reservation"
                ></single-reservation>
              </div>
            </template>
            <div class="w-full" v-else>No {{ resource_name }} found</div>
          </div>

          <pagination
            class="mt-6"
            :links="reservationsData.links"
            :aTag="true"
          />
        </div>
      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import Welcome from "@/Jetstream/Welcome.vue";

export default {
  props: ["resource_name", "reservations", "size"],
  components: {
    AppLayout,
    Welcome,
  },
  data() {
    return {
      reservationsData: this.reservations,
    };
  },
  mounted() {
    document.title = "Reservations - Dinesurf";
  },
};
</script>
