<template>
  <v-dropdown placement="left">
    <!-- Button content -->
    <template v-slot:button>
      <span class="px-2 py-2 border rounded inline-flex items-center text-sm">
        <span class="mr-2">Actions</span>

        <svg
          class="w-4 h-4"
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 20 20"
        >
          <path
            d="M4.516 7.548c.436-.446 1.043-.481 1.576 0L10 11.295l3.908-3.747c.533-.481 1.141-.446 1.574 0 .436.445.408 1.197 0 1.615-.406.418-4.695 4.502-4.695 4.502a1.095 1.095 0 0 1-1.576 0S4.924 9.581 4.516 9.163c-.409-.418-.436-1.17 0-1.615z"
          />
        </svg>
      </span>
    </template>

    <!-- Opened dropdown content -->
    <template v-slot:content>
      <a
        class="
          flex
          w-full
          justify-between
          items-center
          rounded
          px-2
          py-1
          my-1
          hover:bg-indigo-600 hover:text-white
        "
        href="#"
        >Profile</a
      >
      <a
        class="
          flex
          w-full
          justify-between
          items-center
          rounded
          px-2
          py-1
          my-1
          hover:bg-indigo-600 hover:text-white
        "
        href="#"
        >Settings</a
      >
      <a
        class="
          flex
          w-full
          justify-between
          items-center
          rounded
          px-2
          py-1
          my-1
          hover:bg-indigo-600 hover:text-white
        "
        href="#"
        >Help</a
      >
      <a
        class="
          flex
          w-full
          justify-between
          items-center
          rounded
          px-2
          py-1
          my-1
          hover:bg-indigo-600 hover:text-white
        "
        href="#"
        >Orders</a
      >
      <a
        class="
          flex
          w-full
          justify-between
          items-center
          rounded
          px-2
          py-1
          my-1
          hover:bg-indigo-600 hover:text-white
        "
        href="#"
        >Support</a
      >
    </template>
  </v-dropdown>
</template>

<script>
import VDropdown from "@/components/v-dorpdown.vue";
export default {
  data() {
    return {
      action: null,
    };
  },
  components: {
    VDropdown,
  },
  props: {
    actions: {
      type: Array,
      default: [],
    },
    py: {
      type: String,
    },
  },
};
</script>
