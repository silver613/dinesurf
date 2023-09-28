<template>
  <a :href="href" :class="classes">
    <slot></slot>
  </a>
</template>

<script>
export default {
  props: ["href", "active", "vendorLink"],

  computed: {
    classes() {
      if (this.vendorLink) {
           return this.active
          ? "inline-flex items-center px-1 pt-1 border-b-2 border-indigo-400 text-sm lg:text-lg font-bold leading-5 text-white-900 focus:outline-none focus:border-indigo-700 transition"
          : "inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-xs lg:text-sm font-medium leading-5 text-white-500 hover:text-white-700 hover:border-white-300 focus:outline-none focus:text-white-700 focus:border-white-300 transition";
      } else {
        return this.active
          ? "inline-flex items-center px-1 pt-1 border-b-2 border-indigo-400 text-sm lg:text-lg font-medium leading-5 text-gray-900 focus:outline-none focus:border-indigo-700 transition"
          : "inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-xs lg:text-sm  font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition";
      }
    },
  },
};
</script>
