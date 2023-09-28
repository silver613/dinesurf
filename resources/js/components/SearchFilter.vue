<template>
  <div class="containerForm p-3 search-filter dineOverflowHidden ">
    <div class="mb-5 pt-0 mt-0">
      <h4 class="search-filter__header">Prices</h4>

      <div class="content mt-2">
        <template v-for="item in priceRange" :key="item.to">
          <div class="flex justify-between mb-2">
            <label :for="'priceInput' + item.id" class="cursorPointer text-xs">
              <input
                v-on:change="submitSearch('price', item.id)"
                type="radio"
                name="price"
                class="
                  form-radio
                  cursor-pointer
                  text-[#DC2626]
                  border-rose-600 border-2
                  mr-1
                "
                :id="'priceInput' + item.id"
              />
              {{ numberWithCommas(item.from, "NGN") }} -
              {{ numberWithCommas(item.to, "NGN") }}</label
            >
          </div>
        </template>

        <button class="px-6 py-1 text-xs" @click="submitSearch('price', null)">
          Clear
        </button>
      </div>
    </div>

    <!-- Ratings -->
    <div class="mb-5 pt-0 mt-0">
      <h4 class="search-filter__header">Ratings</h4>

      <div class="content mt-1">
        <div
          class="flex justify-between mb-2"
          v-for="ratingValue in this.maxRating"
          :key="'rating' + ratingValue"
        >
          <label
            :for="'ratingInput' + ratingValue"
            class="cursorPointer text-xs"
          >
            <input
              name="ratings"
              v-on:change="submitSearch('rating', ratingValue)"
              type="radio"
              class="
                form-radio
                cursor-pointer
                text-[#DC2626]
                border-rose-600 border-2
                mr-1
              "
              :id="'ratingInput' + ratingValue"
            />

            <span
              class="text-thin fa fa-star text-red"
              style="color: #fde047"
              v-for="item in ratingValue"
              :key="'star' + item"
            ></span>
          </label>
        </div>

        <button class="px-6 py-1 text-xs" @click="submitSearch('rating', null)">
          Clear
        </button>
      </div>
    </div>

    <!-- category -->
    <div class="mb-5 pt-0 mt-0">
      <h4 class="search-filter__header">Categories</h4>

      <div class="content mt-2">
        <div
          class="flex justify-between mb-2"
          v-for="(categoryValue, categoryindex) in categories"
          :key="categoryindex"
        >
          <label
            :for="'categoryInput' + categoryValue"
            class="cursorPointer text-xs capitalize"
          >
            <input
              v-on:change="submitSearch('category', categoryValue)"
              type="radio"
              name="category"
              class="
                form-radio
                cursor-pointer
                text-[#DC2626]
                border-rose-600 border-2
                mr-1
              "
              :id="'categoryInput' + categoryValue"
            />
            {{ categoryValue }}
          </label>
        </div>

        <button
          class="px-6 py-1 text-xs"
          @click="submitSearch('category', null)"
        >
          Clear
        </button>
      </div>
    </div>

    <!-- Cuisines -->
    <div class="mb-5 pt-0 mt-2">
      <h4 class="search-filter__header">Cuisine</h4>

      <div class="content mt-2" style="height:40px">
        <template v-for="item in cuisines" :key="item.id">
          <div class="flex justify-between mb-2">
            <label
              :for="'cuisineInput' + item.id"
              class="cursorPointer text-xs"
            >
              <input
                v-on:change="submitSearch('cuisine', item.id)"
                type="radio"
                name="cuisine"
                class="
                  form-radio
                  cursor-pointer
                  text-[#DC2626]
                  border-rose-600 border-2
                  mr-1
                "
                :id="'cuisineInput' + item.id"
              />
              {{ item.name }}
            </label>
          </div>
        </template>

        <button
          class="px-6 py-1 text-xs"
          @click="submitSearch('cuisine', null)"
        >
          Clear
        </button>
      </div>
    </div>
  </div>
</template>


<script>
export default {
  props: ["price", "rating", "category", "cuisine"],
  data() {
    return {
      form: {
        price: null,
        rating: null,
        category: null,
        cuisine: null,
      },
      price: this.price,
      maxRating: 5,
      categories: ["popular", "featured", "nearme"],
      cuisines: {},
    };
  },

  methods: {
    getCuisines() {
      axios(route("cuisines"))
        .then((result) => {
          this.cuisines = result.data.data.cuisines;
        })
        .catch((err) => {
          this.handleApiError(err);
        });
    },
    submitSearch(category, value) {
      if (category == "price") {
        this.form.price = value;
      }

      if (category == "cuisine") {
        this.form.cuisine = value;
      }

      if (category == "rating") {
        this.form.rating = value;
      }

      if (category == "category") {
        this.form.category = value;
      }

      this.$emit("submitSearch", {
        category: category,
        value: value,
      });

      this.setInputs(
        this.form.rating,
        this.form.category,
        this.form.price,
        this.form.cuisine
      );
    },
    setInputs(rating, category, price, cuisine) {
      var vm = this;
      setTimeout(
        () => {
          if (rating) {
            var el = document.getElementById("ratingInput" + rating);
            if (el !== null) {
              document.getElementById("ratingInput" + rating).checked = true;
            }
          } else {
            for (let index = 1; index <= vm.maxRating; index++) {
              var el = document.getElementById("ratingInput" + index);
              if (el !== null) {
                try {
                  el.checked = false;
                } catch (error) {
                  console.log(error);
                }
              }
            }
          }

          if (price) {
            var el = document.getElementById("priceInput" + price);
            if (el !== null) {
              document.getElementById("priceInput" + price).checked = true;
            }
          } else {
            this.priceRange.forEach((el) => {
              var el = document.getElementById("priceInput" + el.id);
              if (el !== null) {
                try {
                  el.checked = false;
                } catch (error) {
                  console.log(error);
                }
              }
            });
          }

          if (cuisine) {
            var el = document.getElementById("cuisineInput" + cuisine);
            if (el !== null) {
              document.getElementById("cuisineInput" + cuisine).checked = true;
            }
          } else {
            this.cuisines.forEach((el) => {
              var el = document.getElementById("cuisineInput" + el.id);
              if (el !== null) {
                try {
                  el.checked = false;
                } catch (error) {
                  console.log(error);
                }
              }
            });
          }

          if (category) {
            var el = document.getElementById("categoryInput" + category);
            if (el !== null) {
              document.getElementById(
                "categoryInput" + category
              ).checked = true;
            }
          } else {
            this.categories.forEach((el) => {
              var el = document.getElementById("categoryInput" + el);
              if (el !== null) {
                try {
                  el.checked = false;
                } catch (error) {
                  console.log(error);
                }
              }
            });
          }
        },
        1000,
        vm,
        rating,
        category,
        price,
        cuisine
      );
    },
  },

  mounted() {
    this.getCuisines();
    this.setInputs(this.rating, this.category, this.price, this.cuisine);
  },
};
</script>