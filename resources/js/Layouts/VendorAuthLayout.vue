<template>
  <section class="w-full flex">
    <div class="hidden md:block md:w-1/2 vendor-index-bg">
      <div
        class="vendor-index-overlay vendor-index-body flex flex-col justify-between"
      >
        <div>
          <a :href="route('index')">
            <img
              class="fill-current object-contain"
              src="/images/ds_logo.svg"
            />
          </a>
        </div>
        <div>
          <h1>
            Dining Reservations Made Easy<span class="dinesurf-green">.</span>
          </h1>
          <p>Give your restaurant the right exposure.</p>
        </div>
      </div>
    </div>
    <div class="w-full md:w-1/2 m-auto flex justify-center">
      <main>
        <slot></slot>
      </main>
    </div>
  </section>
</template>

<script>
export default {
  props: { appName: String },
  components: {},
};
</script>