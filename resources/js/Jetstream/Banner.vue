<script setup>
import { computed, ref, watch } from "vue";
import { usePage } from "@inertiajs/inertia-vue3";

const show = ref(true);
const style = computed(
  () => usePage().props.value.jetstream.flash?.bannerStyle || "success"
);
const message = computed(
  () => usePage().props.value.jetstream.flash?.banner || ""
);

const reload = computed(() => usePage().props.value.jetstream.flash?.reload);

const close = () => {
  show.value = false;
  if (reload.value) {
    window.location.reload();
  }
};

watch(message, async () => {
  show.value = true;
});
</script>

<template>
  <div>
    <div
      v-if="show && message"
      :class="{
        'bg-orange-400': style == 'warning',
        'bg-green-700': style == 'success',
        'bg-red-700': style == 'danger',
      }"
    >
      <div class="max-w-screen-xl mx-auto py-2 px-3 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between flex-wrap">
          <div class="w-0 flex-1 flex items-center min-w-0">
            <span
              class="flex p-2 rounded-lg"
              :class="{
                'bg-orange-500': style == 'warning',
                'bg-green-600': style == 'success',
                'bg-red-600': style == 'danger',
              }"
            >
              <svg
                v-if="style == 'success'"
                class="h-5 w-5 text-white"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                />
              </svg>

              <svg
                v-if="style == 'danger' || style == 'warning'"
                class="h-5 w-5 text-white"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                />
              </svg>
            </span>

            <p class="ml-3 font-medium text-sm text-white truncate">
              {{ message }}
            </p>
          </div>

          <div class="shrink-0 sm:ml-3">
            <button
              type="button"
              class="
                -mr-1
                flex
                p-2
                rounded-md
                focus:outline-none
                sm:-mr-2
                transition
              "
              :class="{
                'hover:bg-orange-500 focus:bg-orange-500 bg-orange-500': style == 'warning',
                'hover:bg-green-600 focus:bg-green-600': style == 'success',
                'hover:bg-red-600 focus:bg-red-600': style == 'danger',
              }"
              aria-label="Dismiss"
              @click.prevent="close()"
            >
              <svg
                v-if="!reload"
                class="h-5 w-5 text-white"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M6 18L18 6M6 6l12 12"
                />
              </svg>
              <span v-else class="flex text-white">
                <span>reload</span>
                <svg
                  class="h-5 w-5 text-white ml-1"
                  fill="#fff"
                  stroke="currentColor"
                  xmlns="http://www.w3.org/2000/svg"
                  viewBox="0 0 512 512"
                >
                  <!--! Font Awesome Pro 6.2.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
                  <path
                    d="M463.5 224H472c13.3 0 24-10.7 24-24V72c0-9.7-5.8-18.5-14.8-22.2s-19.3-1.7-26.2 5.2L413.4 96.6c-87.6-86.5-228.7-86.2-315.8 1c-87.5 87.5-87.5 229.3 0 316.8s229.3 87.5 316.8 0c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0c-62.5 62.5-163.8 62.5-226.3 0s-62.5-163.8 0-226.3c62.2-62.2 162.7-62.5 225.3-1L327 183c-6.9 6.9-8.9 17.2-5.2 26.2s12.5 14.8 22.2 14.8H463.5z"
                  />
                </svg>
              </span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
