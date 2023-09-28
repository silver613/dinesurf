<template>
  <div class="flex cursor-pointer" >
        <button v-if="modelData?.liked"  @click="toggleLike()">
            <div>         
                   <svg width="24" height="20" viewBox="0 0 24 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M6.5 1C3.4629 1 1 3.23669 1 5.99621C1 8.22381 1.9625 13.5107 11.4368 18.8551C11.6065 18.9499 11.8013 19 12 19C12.1987 19 12.3935 18.9499 12.5632 18.8551C22.0375 13.5107 23 8.22381 23 5.99621C23 3.23669 20.5371 1 17.5 1C14.4629 1 12 4.028 12 4.028C12 4.028 9.5371 1 6.5 1Z" fill="#D2592E" stroke="#D2592E" stroke-width="0.8" stroke-linecap="round" stroke-linejoin="round"/>
                     </svg>
             </div>

              <span  class="font-normal text-[10px]"> {{ modelData.likes.length }}  {{  modelData.likes.length > 1 ? 'likes' : 'like' }} </span>
        </button>
        <button  v-else @click="toggleLike()">
             <div>         
                   <svg width="24" height="20" viewBox="0 0 24 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M6.5 1C3.4629 1 1 3.23669 1 5.99621C1 8.22381 1.9625 13.5107 11.4368 18.8551C11.6065 18.9499 11.8013 19 12 19C12.1987 19 12.3935 18.9499 12.5632 18.8551C22.0375 13.5107 23 8.22381 23 5.99621C23 3.23669 20.5371 1 17.5 1C14.4629 1 12 4.028 12 4.028C12 4.028 9.5371 1 6.5 1Z" fill="gray" stroke="gray" stroke-width="0.8" stroke-linecap="round" stroke-linejoin="round"/>
                     </svg>
             </div>
             <span  class="font-normal text-[10px]"> {{ modelData.likes.length }}  {{  modelData.likes.length > 1 ? 'likes' : 'like' }}</span>
        </button>
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
    getDinelist() {
      fetch(route("single.dinelist", { id: this.model.id }))
        .then((result) => {
          return result.json();
        })
        .then((result) => {
          if (result.success) {
            this.modelData = result.data;
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
        alert("You have to login to add a dinelist to your hit list");
        return;
      }
      this.modelData.liked = !this.modelData.liked;
      fetch(route("like.dinelist", { dinelist_id: this.model.id }))
        .then((result) => {
          return result.json();
        })
        .then((result) => {

          // console.log("result:", result)
          if (result.success) {
            this.getDinelist();
          } else {
            toastr.error("An Error Occured!");
          }
        })
        .catch((err) => {
          this.handleApiError(err);
        });
    },
  },

  // mounted(){
  //   console.log("model:", this.model)
  // }
};
</script>
