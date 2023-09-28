<template>
  <vendor-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight capitalize">
        {{ title }}
      </h2>
    </template>

    <data-table
      :models="models"
      :loading="loading"
      :loaded="loaded"
      :title="'Role'"
      :filters="filters"
      :columns="columns"
      :actions="actions"
      :size="size"
      :rows="rows"
      @runAction="fireAction"
      @loadData="loadData"
      :filterable="false"
      :createLink="''"
      :canCreate="false"
      :indexLink="route('vendors.roles')"
      :viewLink="route('vendors.edit-role-page')"
    >
    </data-table>
  </vendor-layout>
</template>

<script>
import VendorLayout from "@/Layouts/VendorLayout.vue";

export default {
  components: {
    VendorLayout,
  },
  props: {
    title: String,
    filters: Object,
  },
  data() {
    return {
      models: {
        data: [],
        links: [],
        total: 0,
      },
      loading: true,
      loaded: false,
      vendor: this.$page.props.auth.vendor,
      params: {},
      actionData: {},
      columns: [
        {
          name: "id",
          db_name: "id",
          sortable: true,
        },
        {
          name: "name",
          db_name: "name",
          sortable: true,
        },
      ],
      actions: [],
    };
  },
  computed: {
    rows() {
      var modelRows = [];

      this.models.data.forEach((el) => {
        modelRows.push([el.id, el.name]);
      });

      return modelRows;
    },
  },
  methods: {
    fireAction(data) {},
    loadData() {
      const urlSearchParams = new URLSearchParams(window.location.search);
      this.params = Object.fromEntries(urlSearchParams.entries());

      this.loaded = false;
      this.loading = true;
      this.models = {
        data: [],
        links: [],
        total: 0,
      };

      axios
        .get(route("data.vendors.roles", { ...this.params }))
        .then((result) => {
          this.models = result.data.models;
          this.vendor = result.data.vendor;
          this.loading = false;
          this.loaded = true;
        })
        .catch((err) => {
          this.loaded = false;
          this.loading = false;
        });
    },
  },
  mounted() {
    this.loadData();
  },
};
</script>
