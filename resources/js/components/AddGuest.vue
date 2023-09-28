<template>
  <div>
    <h5>Guest</h5>
    <div class="flex flex-col w-full">
      <jet-label
        >Guest First Name: <span class="font-bold text-red-400 text-lg">*</span>
      </jet-label>
      <input
        @input="setGuest()"
        type="text"
        v-model="first_name"
        class="auth-card-input"
        placeholder="Enter First Name"
        required
      />
    </div>

    <div class="flex flex-col w-full">
      <jet-label>Guest Last Name: </jet-label>
      <input
        @input="setGuest()"
        type="text"
        v-model="last_name"
        class="auth-card-input"
        placeholder="Enter Last Name"
      />
    </div>

    <!-- Phone Number -->
    <div class="flex flex-col w-full">
      <jet-label>Guest Phone: </jet-label>
      <phone-input
        class="w-full phone-input"
        v-model="form.phone"
        :modelName="'phone'"
        @setValue="setUserPhone"
        ref="guestPhone"
      ></phone-input>
    </div>

    <div class="flex flex-col w-full">
      <jet-label>Guest E-Mail: </jet-label>
      <input
        @input="setGuest()"
        type="email"
        v-model="email"
        class="auth-card-input"
        placeholder="Enter E-Mail"
      />
    </div>

    <hr class="h-1 w-full bg-black mb-8" />
  </div>
</template>

<script>
import { throttle } from "lodash";

export default {
  porps: ["phone"],
  data() {
    return {
      first_name: null,
      last_name: null,
      email: null,
      form: {
        phone: this.phone,
      },
    };
  },
  methods: {
    setGuest() {
      this.$emit("setGuest", {
        first_name: this.first_name,
        last_name: this.last_name,
        email: this.email,
        phone: this.form.phone,
      });
    },
    setUserPhone(data) {
      if (data) {
        this.form.phone = data.val;
        this.setPhone(data);
        this.setGuest();
      }
    },
  },
  mounted() {
    this.form.phone = this.phone;
  },
};
</script>