<template>
   <button  @click="back" class="text-dinesurf-green-600">
           <i  class="fa fa-angle-left  mr-2"></i>
           Back
    </button>

</template>

<script>
export default {
    methods: {
    back() {
      window.history.back();
    },
  }

}
</script>


