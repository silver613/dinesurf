<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Accept Invitation
      </h2>
    </template>
    <div class="px-3 mt-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div
          class="
            restaurant-comp
            bg-white
            overflow-hidden
            shadow-xl
            pt-3
            px-4
            pb-12
            sm:rounded-lg
          "
        >
          <div v-if="!invitationStatus">
            <h3 class="text-lg font-medium text-gray-900 mb-10 capitalize">
              <span v-if="invitation.name" class="capitalize"
                >Dear {{ invitation.name }}, <br
              /></span>
              Do you Accept This Reservation Invitation from
              {{ invitation.reservation.user.first_name }}
              {{ invitation.reservation.user.last_name }} to
              <span class="text-blue-400">
                <Link
                  target="_blank"
                  :href="
                    route('restaurants.index', [
                      { id: invitation.reservation.vendor.id },
                    ])
                  "
                >
                  {{ invitation.reservation.vendor.company_name }}
                </Link>
              </span>
              on
              {{ invitation.reservation.formated_date }} at
              {{ invitation.reservation.formated_time }}?
            </h3>
            <div class="flex justify-around">
              <Link
                class="jet-btn active:bg-gray-900 mx-2"
                :href="
                  route('accept-invitation', {
                    reply: 'yes',
                    id: invitation.id,
                  })
                "
              >
                Yes
              </Link>
              <Link
                class="jet-btn active:bg-gray-900 mx-2"
                :href="
                  route('accept-invitation', { reply: 'no', id: invitation.id })
                "
              >
                No
              </Link>
            </div>
          </div>
          <div v-else>
            <h3 class="text-lg font-medium text-gray-900 mb-10 capitalize">
              {{ invitationStatus }}
            </h3>
            <p class="mb-5">
              <button
                class="jet-btn active:bg-gray-900"
                @click="invitationStatus = null"
              >
                Change Invitation Status
              </button>
            </p>
            <div v-if="invitationStatus == 'Invitation Accepted'">
              <p>Reservation is set for:</p>
              <p>
                <span style="font-weight: bold">Date:</span>
                {{ invitation.reservation.formated_date }}
              </p>
              <p>
                <span style="font-weight: bold">Time:</span>
                {{ invitation.reservation.formated_time }}
              </p>
              <p>
                <span style="font-weight: bold">Status:</span>
                {{ invitation.reservation.status }}
              </p>
              <p v-if="invitation.reservation.vendor.cancellation_policy">
                <span style="font-weight: bold"
                  >Restaurant Cancelation Policy:</span
                >
                {{ invitation.reservation.vendor.cancellation_policy }}
              </p>
              <p v-if="invitation.reservation.vendor.dress_code">
                <span style="font-weight: bold">Dress Code:</span>
                {{ invitation.reservation.vendor.dress_code }}
              </p>
              <p>
                <span style="font-weight: bold">Note:</span> On busy nights &
                days, active customers have 2hrs to dine due to other
                reservations
              </p>
              <div class="mt-5">
                <Link
                  :href="
                    route('reservation', { id: invitation.reservation.id })
                  "
                  class="btn-md btn-auth btn-red text-center"
                  >Login to View Reservation</Link
                >
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";

export default {
  props: ["invitation", "status"],
  components: {
    AppLayout,
  },
  data() {
    return {
      invitationStatus: this.status,
    };
  },
};
</script>