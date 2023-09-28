<template>
    <div class="flex cursor-pointer" v-if="modelData">
        <button   v-if="modelData.liked" class="bg-red"
         >
             <i
            class="far fa-heart text-2xl cursor-pointer fa fa-heart text-xs cursor-pointer text-red-500"
         
            @click="toggleLike()"
        ></i>
        </button>
       
        <i
            class="far fa-heart text-2xl cursor-pointer text-white"
            v-else
            @click="toggleLike()"
        ></i>
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
    props: ["model"],
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
