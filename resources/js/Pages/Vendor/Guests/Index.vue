<template>
  <vendor-layout>
    <template #header>
      <header-text   :title="title" />
    </template>

    <data-table
      :UseGuestAction="true"
      :models="models"
      :loading="loading"
      :loaded="loaded"
      :title="'guest'"
      :filters="filters"
      :columns="columns"
      :actions="actions"
      :size="size"
      :rows="rows"
      @runAction="fireAction"
      @loadData="loadData"
      :createLink="route('vendors.create-guest-page')"
      :indexLink="route('vendors.guests')"
      :viewLink="route('vendors.edit-guest-page')"
      :externalFilters="{
        approved: approved,
        birthday: birthday,
        birthday_date: birthday_date,
      }"
    >
      <template #outerFilters>
        <!-- <div class="flex flex-wrap"> -->
          <div class="w-[48%] mr-2 ">
            <label for="status" class="block text-sm font-light text-gray-400">Approval Status:</label>

            <select
              id="approved"
              name="approved"
              v-model="approved"
              class="block form-control w-full "
            >
              <option :value="null"></option>
              <option :value="true">Un-Blocked</option>
              <option :value="false">Blocked</option>
            </select>
          </div>
          <div class="w-[48%]  mr-2">
            <label for="status" class="block text-sm font-light text-gray-400">Birthdays:</label>

            <select
              id="birthday"
              name="birthday"
              v-model="birthday"
              class="block form-control w-full "
            >
              <option :value="null"></option>
              <option :value="'today'">Today</option>
              <option :value="'month'">This Month</option>
              <option v-for="(item, index) in months" :key="index">
                {{ item }}
              </option>
              <option :value="'date'">On Specific Date</option>
            </select>
          </div>
          <div class="w-[48%] mr-3" 
          v-if="birthday_date"
          >
            <label for="status" class="block text-sm font-light text-gray-400">Specific Birthday Date:</label>
            <input
              class="block form-control w-full "
              id="birthday"
              type="text"
              v-model="birthday_date"
              required
              autocomplete="birthday"
              placeholder="dd / mm"
            />
          </div>
        <!-- </div> -->
      </template>

      <template  #notFound>
        <div class="max-w-full">
                  <empty-space 
                  :title="'Enhance Guest Experience'"
                  :description="'We help you send confirmation text and email to reduce No-shows. Guests on Dinesurf can make a reservation and appear here, you can also add in reservation records from other sources like phone calls.'"
                  :page="'guest'"
                  :link="route('vendors.create-guest-page')"
                  :image="'/images/empty_images/2.png'"
                    />
          </div>
      </template>
    </data-table>

    <!-- Send Emails -->
    <send-email
      :showEmailModal="showEmailModal"
      :backendActionData="actionData"
    ></send-email>
    <!-- End Send Emails -->

    <!-- Send Texts -->
    <send-text
      :showTextModal="showTextModal"
      :backendActionData="actionData"
    ></send-text>
    <!-- End Send Texts -->
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
      months: [
        "January",
        "February",
        "March",
        "April",
        "May",
        "June",
        "July",
        "August",
        "September",
        "October",
        "November",
        "December",
      ],
      loading: true,
      loaded: false,
      vendor: this.$page.props.auth.vendor,
      params: {},
      actionData: {},
      approved: this.filters.approved,
      showEmailModal: false,
      showTextModal: false,
      columns: [
        // {
        //   name: "created_at",
        //   db_name: "created_at",
        //   sortable: true,
        // },
        // {
        //   name: "id",
        //   db_name: "id",
        //   sortable: true,
        // },
        {
          name: "first_name",
          db_name: "first_name",
          sortable: true,
        },
        {
          name: "last_name",
          db_name: "last_name",
          sortable: true,
        },
        {
          name: "email",
          db_name: "email",
        },
        {
          name: "phone",
          db_name: "phone",
        },
        {
          name: "birthday",
          db_name: "birthday",
        },
        {
          name: "reservations",
        },
        // {
        //   name: "approved",
        //   db_name: "approved",
        // },
      ],
      actions: [
        {
          value: "email_users",
          name: "Send E-Mail",
        },
        {
          value: "text_users",
          name: "Send Text",
        },
        {
          value: "un-block",
          name: "Un-Block",
        },
        {
          value: "block",
          name: "Block",
        },
      ],
      birthday: this.filters.birthday,
      birthday_date: this.filters.birthday_date,
    };
  },
  computed: {
    rows() {
      var modelRows = [];

      this.models.data.forEach((el) => {
        modelRows.push([
          el.first_name,
          el.last_name,
          el.email,
          el.phone ? el.phone : "--",
          el.birthday ? el.birthday : "--",
          el.reservations_count,
          // this.blocked(el.email, this.vendor.block_lists) != true
          //   ? '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" aria-labelledby="check-circle" role="presentation" class="fill-current text-green-600"><path d="M12 22a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm-2.3-8.7l1.3 1.29 3.3-3.3a1 1 0 0 1 1.4 1.42l-4 4a1 1 0 0 1-1.4 0l-2-2a1 1 0 0 1 1.4-1.42z"></path></svg>'
          //   : '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" aria-labelledby="x-circle" role="presentation" class="fill-current text-red-600"><path d="M4.93 19.07A10 10 0 1 1 19.07 4.93 10 10 0 0 1 4.93 19.07zm1.41-1.41A8 8 0 1 0 17.66 6.34 8 8 0 0 0 6.34 17.66zM13.41 12l1.42 1.41a1 1 0 1 1-1.42 1.42L12 13.4l-1.41 1.42a1 1 0 1 1-1.42-1.42L10.6 12l-1.42-1.41a1 1 0 1 1 1.42-1.42L12 10.6l1.41-1.42a1 1 0 1 1 1.42 1.42L13.4 12z"></path></svg>',
       
          ]);
      });

      return modelRows;
    },
  },
  methods: {
    fireAction(data) {
      var action = data.action;
      var ids = data.ids;
      var all = data.all;
      // alert("run action " + action + " for models " + [...ids]);

      if (!action) {
        return;
      }

      if (ids.length <= 0) {

        if (action == "email_users") {
          alert("Please select a Guest or some guests you want send an E-Mail");
        }
       
        if (action == "text_users") {
          alert("Please select a Guest or some guests you want to Text");
        }
        return;
      }

      if (action == "email_users") {
        this.actionData = {
          ids: ids,
          action: action,
          allMatching: all,
          filters: this.filters,
          subject: null,
          message: null,
          banner: null,
          action_model: "guest",
        };
        this.showEmailModal = true;
      }

      if (action == "text_users") {
        this.actionData = {
          ids: ids,
          action: action,
          allMatching: all,
          filters: this.filters,
          message: null,
          action_model: "guest",
        };
        this.showTextModal = true;
      }

      if (action == "block" || action == "un-block") {
        var r = confirm("Are you sure you want to " + action + " this Guest?");
        if (!r) {
          return;
        }

        this.$inertia.post(
          route("vendors.run-action") + "?without_async=true",
          {
            ids: ids,
            action: action,
            allMatching: all,
            filters: this.filters,
            action_model: "guest",
          },
          {
            onFinish: (visit) => {
              this.loadData();
            },
          }
        );
      }

      if (!r) {
        return;
      }
    },
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
        .get(route("data.vendors.guests", { ...this.params }))
        .then((result) => {
          this.models = result.data.models;
          this.vendor = result.data.vendor;
          this.loading = false;
          this.loaded = true;
          this.loadReservationsCount();
        })
        .catch((err) => {
          this.loaded = false;
          this.loading = false;
        });
    },
    loadReservationsCount() {
      this.models.data.forEach((el) => {
        axios
          .get(
            route("data.vendors.count-guest-reservations", {
              guest_id: el.id,
            })
          )
          .then((result) => {
            el.reservations_count = result.data.count;
          })
          .catch((err) => {
            console.log(err);
          });
      });
    },
  },
  mounted() {
    this.loadData();
  },
};
</script>
