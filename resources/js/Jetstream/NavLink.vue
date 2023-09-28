<template>
    <Link :href="href" :class="classes" :id="id" style="margin:5px !important; margin-top: 12px !important; margin-bottom: 10px !important;">
      <slot></slot>
    </Link>
  </template>
  
  <script>
  export default {
    props: ["href", "active", "vendorLink", "id"],
  
    computed: {
      classes() {
        if (this.vendorLink) {
             return this.active
            ? "bg-white p-1 text-dinesurf-red-0 pl-7  flex rounded  focus:outline-none focus:border-indigo-700 transition"
            : "flex flex-row pl-7  text-center items-center";
        } else {
          return this.active
            ? "inline-flex items-center bg-blue px-1 pt-1 border-b-2 border-indigo-400 text-sm lg:text-lg font-medium leading-5 text-gray-900 focus:outline-none focus:border-indigo-700 transition"
            : "vendor-nav__link--active   bg-blue";
        }
      },
    },
  };
  </script>
  