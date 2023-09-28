<template>
  <div class="flex flex-col w-full">
    <input type="hidden" name="user_id" id="user_id" v-model="form.user_id" />
    <transition
      enter-active-class="transition ease-out duration-200"
      enter-from-class="transform opacity-0 scale-95"
      enter-to-class="transform opacity-100 scale-100"
      leave-active-class="transition ease-in duration-75"
      leave-from-class="transform opacity-100 scale-100"
      leave-to-class="transform opacity-0 scale-95"
    >
      <div class="w-full">
        <jet-label>Select User (must be a registered user)</jet-label>
        <input
          style="margin-bottom: 0 !important"
          @input="search()"
          type="text"
          v-model="form.user_query"
          class="auth-card-input capitalize"
          placeholder="Search Name or Email"
          required
        />
        <div class="relative mb-7 -mt-2">
          <!-- Full Screen Dropdown Overlay -->
          <div
            v-show="showUsers"
            class="fixed inset-0 z-40"
            @click="resetUser()"
          ></div>
          <div
            v-if="showUsers"
            class="absolute z-50 mt-2 rounded-md shadow-lg bg-white w-96 py-1"
          >
            <div class="rounded-md ring-1 ring-black ring-opacity-5">
              <ul>
                <template v-if="users.length > 0">
                  <li
                    v-for="(userItem, index) in users"
                    :key="index"
                    class="
                      block
                      w-full
                      px-4
                      py-2
                      text-sm
                      leading-5
                      text-gray-700 text-left
                      hover:bg-gray-100
                      focus:outline-none focus:bg-gray-100
                      transition
                      cursor-pointer
                      capitalize
                    "
                    @click="selectUser(userItem)"
                  >
                    {{ userItem.first_name }}
                    {{ userItem.last_name }}
                  </li>
                </template>
                <li
                  v-else
                  class="
                    block
                    w-full
                    px-4
                    py-2
                    text-sm
                    leading-5
                    text-gray-700 text-left
                    hover:bg-gray-100
                    focus:outline-none focus:bg-gray-100
                    transition
                    cursor-pointer
                    uppercase
                  "
                >
                  <i class="far fa-clock"></i> No Users Found
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
export default {
  data() {
    return {
      showUsers: false,
      form: this.$inertia.form({
        user_id: null,
        user_query: null,
      }),
      users: [],
    };
  },
  methods: {
    search() {
      this.showUsers = true;
      this.form.user_id = null;
      fetch(route("search-users", { query: this.form.user_query }))
        .then((result) => {
          return result.json();
        })
        .then((result) => {
          this.users = result.data;
        })
        .catch((err) => {
          this.handleApiError(err);
        });
    },
    selectUser(userItem) {
      this.form.user_query = userItem.first_name + " " + userItem.last_name;
      this.form.user_id = userItem.id;
      this.showUsers = false;
      this.$emit('setUser',this.form.user_id)
    },
    resetUser() {
      if (!this.form.user_id) {
        this.form.user_query = null;
      }
      this.showUsers = false;
      return;
    },
  },
};
</script>