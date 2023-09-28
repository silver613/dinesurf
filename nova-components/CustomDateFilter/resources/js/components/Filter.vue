<template>
  <FilterContainer>
    <span>{{ filter.name }}</span>

    <template #filter>
      <!-- <SelectControl
        :dusk="`${filter.name}-select-filter`"
        label="label"
        class="w-full block"
        size="sm"
        v-model:selected="value"
        @change="value = $event"
        :options="filter.options"
        id="calendar"
      >
        <option value="" :selected="value == ''">&mdash;</option>
      </SelectControl> -->

      <input
        :dusk="`${filter.name}-select-filter`"
        label="label"
        class="
          w-full
          block
          form-control form-select form-control-sm form-select-bordered
        "
        size="sm"
        v-model="value"
        @change="value = $event"
        style="margin-bottom: 0"
        id="calendar"
      />
    </template>
  </FilterContainer>
</template>

<script>
import debounce from "lodash/debounce";
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.css";

export default {
  emits: ["change"],

  props: {
    resourceName: {
      type: String,
      required: true,
    },
    filterKey: {
      type: String,
      required: true,
    },
    lens: String,
  },

  data: () => ({
    value: null,
    debouncedHandleChange: null,
  }),

  created() {
    this.debouncedHandleChange = debounce(() => this.handleChange(), 500);

    this.setCurrentFilterValue();
  },

  mounted() {
    Nova.$on("filter-reset", this.setCurrentFilterValue);
    this.startcalendar();
  },

  beforeUnmount() {
    Nova.$off("filter-reset", this.setCurrentFilterValue);
  },

  watch: {
    value() {
      this.debouncedHandleChange();
    },
  },

  methods: {
    setCurrentFilterValue() {
      this.value = this.filter.currentValue;
    },

    handleChange() {
      this.$store.commit(`${this.resourceName}/updateFilterState`, {
        filterClass: this.filterKey,
        value: this.value,
      });

      this.$emit("change");
    },

    startcalendar() {
      var element = document.getElementById("flatpickrCalendar");
      if (element) {
        element.remove();
        console.log("removed calendar");
      }

      if (this.fp) {
        this.fp.destroy();
        console.log("destroyed calendar: ", this.fp);
      }

      const vm = this;

      console.log("starting calender...");

      this.fp = flatpickr("#calendar", {
        dateFormat: "d-m-Y",
        mode: "range",
      });

      setTimeout(() => {
        var elements = document.getElementsByClassName("flatpickr-calendar");
        if (elements) {
          elements[0].setAttribute("id", "flatpickrCalendar");
        }
      }, 1000);
    },
  },

  computed: {
    filter() {
      return this.$store.getters[`${this.resourceName}/getFilter`](
        this.filterKey
      );
    },
  },
};
</script>
