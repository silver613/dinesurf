<template>
  <div
    class="
      dropdown-trigger
      flex
      items-center
      cursor-pointer
      select-none
      px-3
      border-2 border-30
      rounded-lg
      bg-dinesurf-green-500
      text-xs
      text-white
      md:text-base
      w-full
      mt-3
      h-11
    "
  >
    <span class="mr-3 text-sm"
      >
      <!-- <span v-if="showFilter">Hide</span> <span v-else>Show</span> -->
      {{  showFilter ? 'Hide&nbsp;'  + text : 'Show&nbsp;' + text }}
      </span
    >
    <svg
      xmlns="http://www.w3.org/2000/svg"
      width="15"
      height="15"
      viewBox="0 0 20 20"
      aria-labelledby="filter"
      role="presentation"
      class="fill-current text-80"
    >
      <path
        fill-rule="nonzero"
        d="M.293 5.707A1 1 0 0 1 0 4.999V1A1 1 0 0 1 1 0h18a1 1 0 0 1 1 1v4a1 1 0 0 1-.293.707L13 12.413v2.585a1 1 0 0 1-.293.708l-4 4c-.63.629-1.707.183-1.707-.708v-6.585L.293 5.707zM2 2v2.585l6.707 6.707a1 1 0 0 1 .293.707v4.585l2-2V12a1 1 0 0 1 .293-.707L18 4.585V2H2z"
      ></path>
    </svg>
    <!---->
 
  </div>
</template>

<script>
export default {
  props: {
    showFilter: {
      type: Boolean,
    },
    text: {
      type: String,
      required: true,
    },
  },
};
</script>