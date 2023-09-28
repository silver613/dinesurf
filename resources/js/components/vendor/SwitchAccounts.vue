<template>
  <div class="md:py-20 px-4">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="flex flex-col items-center w-full">
        <!-- Switch Accounts -->
        <div class="card w-full md:w-1/2 bg-white">
          <div class="card-body text-left">
            <div class="flex flex-col w-full">
              <div class="flex justify-between mb-3">
                <span
                  class="cursor-pointer mr-2 text-xs"
                  v-if="addVendorForm"
                  @click="addVendorForm = !addVendorForm"
                  ><i class="fa-solid fa-angle-left mr-1"></i>Go Back</span
                >
                <h5 class="text-xs md:text-lg font-bold">
                  <span v-if="addVendorForm"> Add Vendor </span>
                  <span v-else>Choose a Vendor Account</span>
                </h5>
                <JetButton
                  v-if="canCreateVendor"
                  @click="startAddVendor()"
                  class="ml-2"
                  style="font-size: 10px; padding: 2px 9px"
                  >Create Vendor</JetButton
                >
                <!-- <a
                  v-if="!user?.can_create_vendor"
                  href="https://wa.me/17633372013"
                  target="_blank"
                  class="ml-2 jet-btn btn-black"
                  style="font-size: 10px; padding: 2px 9px"
                  >Request Permission To Create Vendor</a
                > -->
              </div>
              <form v-if="addVendorForm" @submit.prevent="addVendor()">
                <div class="text-left">
                  <div class="flex flex-col">
                    <div class="mb-6 w-full">
                      <JetLabel
                        for="company_name"
                        value="Company / Business Name"
                      />
                      <JetInput
                        id="company_name"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.company_name"
                        :required="true"
                        autofocus
                        autocomplete="company_name"
                      />
                    </div>
                    <div class="mb-6 w-full">
                      <JetLabel
                        for="company_address"
                        value="Business Address"
                      />
                      <textarea
                        id="company_address"
                        type="text"
                        class="
                          border-gray-300
                          focus:border-indigo-300
                          focus:ring
                          focus:ring-indigo-200
                          focus:ring-opacity-50
                          rounded-md
                          shadow-sm
                          mt-1
                          block
                          w-full
                        "
                        v-model="form.company_address"
                        autofocus
                        autocomplete="company_address"
                        required
                      />
                    </div>
                  </div>

                  <div class="text-center mb-5">
                    <span>{{ responseMessage }}</span>
                    <i
                      class="fas fa-spinner fa-spin"
                      v-if="form.processing"
                    ></i>
                    <span class="text-green-800 font-bold text-lg">{{
                      responseSuccess
                    }}</span>
                    <span class="text-red-700 font-bold text-lg">{{
                      responseFail
                    }}</span>
                  </div>

                  <div class="flex flex-col md:flex-row justify-center">
                    <JetButton
                      class="w-full py-3 flex justify-center"
                      :class="{ 'opacity-25': form.processing }"
                      :disabled="form.processing"
                      :type="'submit'"
                    >
                      Submit
                    </JetButton>
                  </div>
                </div>
              </form>
              <LoadingCard
                v-else
                :title="'Vendors'"
                :url="route('api.vendor-accounts')"
                @setData="setVendors"
                :trigger="trigger"
              >
                <div v-if="vendors" class="w-full flex flex-col">
                  <div v-if="vendors.length > 0" class="w-full flex flex-col">
                    <Link
                      class="
                        bg-white
                        rounded-lg
                        shadow
                        px-6
                        py-4
                        min-h-[70px]
                        h-full
                        jump
                        mb-5
                        jump
                        cursor-pointer
                      "
                      v-for="(item, index) in vendors"
                      :key="index"
                      :href="
                        route('vendors.switch-vendor-accounts', {
                          vendor_id: item.id,
                        })
                      "
                    >
                      <div class="flex items-center">
                        <img
                          v-if="item.profile_photo_path"
                          class="
                            h-5
                            sm:h-8
                            w-auto
                            rounded-full
                            object-cover
                            mr-2
                          "
                          :src="item.profile_photo_url"
                        />
                        {{ item.company_name }}
                      </div>
                      <div class="flex items-center">
                        {{ item.company_address }}
                      </div>
                    </Link>
                  </div>
                  <p class="text-sm" v-else>No Vendor Accounts Found</p>
                </div>
              </LoadingCard>
            </div>
          </div>
        </div>

        <!-- Invitations -->
        <div class="card w-full md:w-1/2 bg-white">
          <div class="card-body text-left">
            <div class="flex flex-col w-full">
              <h5 class="text-xs md:text-lg font-bold">Invitations</h5>

              <LoadingCard
                :title="'Invitations'"
                :url="route('api.vendor-invitations')"
                @setData="setInvitations"
              >
                <div v-if="invitations" class="w-full flex flex-col">
                  <div v-if="invitations.length > 0">
                    <div
                      class="
                        bg-white
                        rounded-lg
                        shadow
                        px-6
                        py-4
                        min-h-[70px]
                        h-full
                        jump
                        mb-5
                        flex
                        justify-between
                        jump
                      "
                      v-for="(item, index) in invitations"
                      :key="index"
                    >
                      <div>
                        {{ item.team.name }}
                      </div>
                      <div>
                        <JetButton
                          @click="acceptInvitation(item.id)"
                          style="font-size: 10px; padding: 5px 30px"
                          >Accept</JetButton
                        >
                      </div>
                    </div>
                  </div>
                  <p class="text-sm" v-else>No Invitations Found</p>
                </div>
              </LoadingCard>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>



<script>
import JetButton from "@/Jetstream/Button.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetLabel from "@/Jetstream/Label.vue";
import LoadingCard from "@/components/LoadingCard3.vue";
import { Link } from "@inertiajs/inertia-vue3";

export default {
  components: {
    JetButton,
    JetInput,
    JetLabel,
    LoadingCard,
    Link,
  },
  data() {
    return {
      vendors: null,
      invitations: null,
      addVendorForm: false,
      form: {
        processing: false,
        company_name: null,
        company_address: null,
      },
      responseSuccess: null,
      responseFail: null,
      responseMessage: null,
      trigger: 0,
    };
  },
  computed: {
    user() {
      return this.$page.props.user;
    },
    canCreateVendor() {
      if (this.user?.can_create_vendor) {
        return !this.addVendorForm;
      } else {
        return !this.addVendorForm && this.vendors?.length < 1;
      }
    },
  },
  methods: {
    setVendors(data) {
      this.vendors = data;
    },
    setInvitations(data) {
      this.invitations = data;
    },
    addVendor() {
      if (
        !confirm(
          "Are yon sure you want to crate this Vendors?. You can't delete it once created"
        )
      ) {
        return false;
      }
      this.responseSuccess = null;
      this.responseFail = null;
      this.responseMessage = "Creating Vendor...";
      this.form.processing = true;

      axios
        .post(route("api.create-vendor"), this.form)
        .then((result) => {
          toastr.success(result.data.message);
          this.responseSuccess = result.data.message + "!";
          this.responseFail = null;
          this.responseMessage = null;
          this.trigger++;
          this.form.processing = false;
          this.addVendorForm = false;
        })
        .catch((err) => {
          this.form.processing = false;
          this.responseSpin = false;
          this.responseSuccess = null;
          this.responseFail = err;
          this.handleApiError(err);
        });
    },
    acceptInvitation(invitation_id) {
      axios
        .get(
          route("api.accept-invitation", {
            invitation_id,
          })
        )
        .then((result) => {
          var vendor_id = result.data.data;
          this.$inertia.visit(
            route("vendors.switch-vendor-accounts", { vendor_id })
          );
        })
        .catch((err) => {
          console.log(err);
        });
    },
    parseAddressComponents(components) {
      // predefine the final $address Array
      location = {
        address: "",
        city: "",
      };

      components.forEach((item) => {
        name = item.long_name;
        type = item.types[0];

        if (type == "route") {
          location.address = name;
        }

        if (type == "locality") {
          location.city = name;
        }
      });

      return location.address + " " + location.city;
    },
    initialize() {
      setTimeout(() => {
        var input = document.getElementById("company_address");
        var autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.addListener("place_changed", function () {
          autocomplete.getPlace();
        });
      }, 500);
    },
    startAddVendor() {
      this.addVendorForm = !this.addVendorForm;
      this.initialize();
    },
  },
};
</script>
