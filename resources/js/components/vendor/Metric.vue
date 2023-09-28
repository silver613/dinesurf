<template>
  <div class="px-3 mb-6 w-1/4">
    <a :href="link ? link : 'javascript:void(0)'" v-if="aLink">
      <div class="block metric-card card relative px-6 py-4 card-panel jump">
        <!---->
        <div class="flex mb-4">
          <h3 class="mr-3 text-xs lg:text-base font-bold">{{ title }}</h3>
          <!---->
        </div>
        <p class="flex items-center text-xl lg:text-4xl mb-4">
          <span v-if="currency">{{ numberWithCommas(value, currency) }}</span>
          <span v-else>{{ value }}</span>
          <!---->
        </p>
        <div>
          <!-- <p
            style="font-size: 10px"
            class="flex items-center font-light lg:text-xs"
            v-html="hint"
          ></p> -->
        </div>
      </div>
    </a>
    <Link :href="link ? link : 'javascript:void(0)'" v-if="!aLink">
      <div class="block metric-card card relative px-6 py-4 card-panel jump">
        <!---->
        <div class="flex mb-4">
          <h3 class="mr-3 text-xs lg:text-base font-bold">{{ title }}</h3>
          <!---->
        </div>
        <p class="flex items-center text-xl lg:text-4xl mb-4">
          <i class="fas fa-chart-area mr-2"></i>
          <span v-if="currency">{{ numberWithCommas(value, currency) }}</span>
          <span v-else>{{ value }}</span>
          <!---->
        </p>
        <div>
          <!-- <p
            style="font-size: 10px"
            class="flex items-center font-light lg:text-xs"
            v-html="hint"
          ></p> -->
        </div>
      </div>
    </Link>
  </div>
</template>

<script>
export default {
  props: ["value", "title", "currency", "hint", "link", "link_text", "aLink"],
};
</script>