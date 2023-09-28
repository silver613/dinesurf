<template>
  <div class="flex cursor-pointer" v-if="modelData">
    <button
      v-if="modelData.liked"
      class="bg-red-600 p-2 rounded flex items-center"
      @click="toggleLike()"
    >
      <i
        class="far  fa-heart text-2xl cursor-pointer text-white mr-2"
        :class="this.class"
      >
      </i>
      <span class="text-white">Restaurant Added</span>
    </button>

    <button v-else @click="toggleLike()" class="md:bg-white p-2 rounded md:w-[139px]  flex items-center text-center">
      <i
        class="far  fa-heart  md:text-xl  text-2xl cursor-pointer md:text-black  text-white   mr-2"
        :class="this.class"
      ></i>
      <span class="hidden md:block"  style="font-size:12px; ">Add to Favorites</span>
    </button>

    <!-- <span
                    >{{ modelData.likes.length }} like<span
                        v-if="!modelData.likes.length == 1"
                        >s</span
                    ></span
                > -->
  </div>
</template>

<script>
export default {
  props: ["model", "class"],
  data() {
    return {
      modelData: this.model,
    };
  },
  methods: {
    getRestaurant() {
      fetch(route("restaurants-data.index", { id: this.model.id }))
        .then((result) => {
          return result.json();
        })
        .then((result) => {
          if (result.success) {
            // toastr.success(result.message);
            this.restaurantData = result.data;
          } else {
            toastr.error("An Error Occured!");
          }
        })
        .catch((err) => {
          this.handleApiError(err);
        });
    },
    toggleLike() {
      if (!this.$page.props.user) {
        alert("You have to login to add a restaurant to your hit list");
        return;
      }
      this.modelData.liked = !this.modelData.liked;
      fetch(route("toggle-like", { vendor_id: this.model.id }))
        .then((result) => {
          return result.json();
        })
        .then((result) => {
          if (result.success) {
            this.getRestaurant();
          } else {
            toastr.error("An Error Occured!");
          }
        })
        .catch((err) => {
          this.handleApiError(err);
        });
    },
  },
};
</script>
