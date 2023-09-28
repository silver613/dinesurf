<template>
    <vendor-layout>
        <template #header>
            <header-text :title="'Transactions'" />
        </template>

  
        <!-- Data Table -->
 <div class="md:w-[65rem]">
        <data-table
      :models="models"
      :loading="loading"
      :loaded="loaded"
      :title="'Transactions'"
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
      :canView="false"
      :indexLink="route('vendors.transactions')"
    >
    </data-table>
</div>
        <!-- End Send Emails -->

       
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
            showEmailModal: false,
            showTextModal: false,
            columns: [
                {
                    name: "Name",
                    db_name: "name",
                },

                {
                    name: "Email",
                    db_name: "email",
                },
                {
                    name: "phone",
                    db_name: "phone",
                },
                {
                    name: "type",
                    db_name: "type"
                },
                {
                    name: "amount",
                    db_name: "amount",
                },

                {
                    name: "status",
                    db_name: "status",
                },

                {
                    name: "date",
                    db_name: "date",
                },
                {
                    name: 'reference',
                    db_name: 'reference',
                    sortable: true
                }
            ],
            actions: [],
        };
    },
    computed: {
        rows() {
            var modelRows = [];
            this.models.data.forEach((el) => {
                modelRows.push([
                    el.name,
                    el.email,
                    el.phone,
                    el.type,
                    this.numberWithCommas(el.amount, el.currency),
                    el.status,
                    el.created_at,
                    el.reference,
                 
                ]);
            });
            return modelRows;
        },
    },
    methods: {
      
    
       
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
            this.dates_with_reservations = [];

            axios
                .get(route("data.vendors.transactions", { ...this.params }))
                .then((result) => {
                    this.models = result.data.models;
                    this.vendor = result.data.vendor;
                    this.loading = false;
                    this.loaded = true;
                })
                .catch((err) => {
                    this.loaded = false;
                    this.loading = false;
                    this.dates_with_reservations = [];
                });
        },
    },
    mounted() {
        this.loadData();
    },
};
</script>
