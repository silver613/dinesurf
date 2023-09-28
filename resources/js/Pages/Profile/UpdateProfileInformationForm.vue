<template>
  <jet-form-section @submitted="updateProfileInformation" >
    
    <template #title> Profile Information </template>

    <template #description>
      Update your account's profile information and email address.
    </template>

    <template #form>
      <!-- Profile Photo -->
      <div class="w-full">
    

      <div class="col-span-6 sm:col-span-4" >
        <!-- Profile Photo File Input -->
        <input
          type="file"
          class="hidden"
          ref="photo"
          @change="updatePhotoPreview"
        />


        <!-- Current Profile Photo -->
        <div class="mt-2  flex justify-center" v-show="!photoPreview">
          <img
            :src="user.profile_photo_url"
            :alt="user.name"
            class="rounded-full h-20 w-20 object-cover cursor-pointer"
            @click.prevent="selectNewPhoto"
          />
        </div>

        <!-- New Profile Photo Preview -->
        <div class="mt-2 w-32 mx-auto relative flex justify-center" @click.prevent="selectNewPhoto" v-show="photoPreview">
          <span
            class="block rounded-full w-20 cursor-pointer h-20"
            
            :style="
              'background-size: cover;  background-color:grey; background-repeat: no-repeat; background-position: center center; background-image: url(\'' +
              photoPreview +
              '\');'
            "
          >
          </span>

           <div class="absolute  w-8 h-8 flex items-center cursor-pointer bottom-0 right-5 bg-white border rounded-full p-2 ">
            <svg width="12" height="16" viewBox="0 0 16 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M14.4181 4.79167C14.7804 4.42942 14.9797 3.94833 14.9797 3.43658C14.9797 2.92483 14.7804 2.44375 14.4181 2.0815L12.8982 0.561583C12.536 0.199333 12.0549 0 11.5431 0C11.0314 0 10.5503 0.199333 10.189 0.560625L0 10.718V14.949H4.22913L14.4181 4.79167ZM11.5431 1.91667L13.064 3.43563L11.5402 4.95363L10.0203 3.43467L11.5431 1.91667ZM1.91667 13.0324V11.5134L8.66333 4.78783L10.1833 6.30775L3.43754 13.0324H1.91667ZM0 16.8657H15.3333V18.7824H0V16.8657Z" fill="#a0d062"/>
               </svg>
           </div>
        </div>
         
        <jet-input-error :message="form.errors.photo" class="mt-2" />
      </div>

    
        
      <!-- input group -->
      <div class="flex lg:md:flex-row flex-col mx-auto  max-w-3xl lg:md:space-x-10 lg:md:space-y-0  space-y-4 mt-5  ">
        <!-- Basic information -->
        <div class="w-full  flex flex-col  space-y-5">

               <!-- username -->

                <div class="col-span-6 sm:col-span-4">
                    <jet-label for="displayname" value="Display Name" />
                    <jet-input
                      id="displayname"
                      type="text"
                      class="mt-1 block w-full"
                      v-model="form.displayname"
                      autocomplete="displayname"
                      placeholder="What would you like your display name be ?"
                    />
                    <jet-input-error :message="form.errors.displayname" class="mt-2" />
                  </div>
                <!-- username -->

                <div class="col-span-6 sm:col-span-4">
                    <jet-label for="username" value="User Name" />
                    <jet-input
                      id="username"
                      type="text"
                      class="mt-1 block w-full"
                      v-model="form.username"
                      autocomplete="username"
                      placeholder="What would you like your username be ?"
                    />
                    <jet-input-error :message="form.errors.username" class="mt-2" />
                  </div>

                   <!-- First Name -->
                  <div class="col-span-6 sm:col-span-4">
                    <jet-label for="first_name" value="First Name" />
                    <jet-input
                      id="first_name"
                      type="text"
                      class="mt-1 block w-full"
                      v-model="form.first_name"
                      autocomplete="first_name"
                    />
                    <jet-input-error :message="form.errors.first_name" class="mt-2" />
                  </div>

                  <!-- Last Name -->
                  <div class="col-span-6 sm:col-span-4">
                    <jet-label for="last_name" value="Last Name" />
                    <jet-input
                      id="last_name"
                      type="text"
                      class="mt-1 block w-full"
                      v-model="form.last_name"
                      autocomplete="last_name"
                    />
                    <jet-input-error :message="form.errors.last_name" class="mt-2" />
                  </div>

                    <!-- Last Name -->
                    <div class="col-span-6 sm:col-span-4">
                    <jet-label for="bio" value="Bio" />
                    <textarea
                      id="bio"
                      type="text"
                      class="mt-1 form-group auth-card-input block w-full h-[127px]"
                      v-model="form.bio"
                      autocomplete="bio"
                      placeholder="Write a bio"
                    > </textarea>
                    <jet-input-error :message="form.errors.bio" class="mt-2" />
                  </div>


                 
        </div>
      
      
          <!-- Social handles -->
          <div class="w-full  flex flex-col  space-y-5">



             

                  <!-- Phone Number --> 
                  <div class="col-span-6 sm:col-span-4">
                      <jet-label for="phone_number" value="Phone Number" />
                      <phone-input
                        class="w-full phone-input"
                        v-model="phone"
                        :modelName="'phone_number'"
                        @setValue="setPhone"
                        required
                      ></phone-input>
                      <jet-input-error :message="form.errors.phone_number" class="mt-2" />
                    </div>


                  <!-- Email -->
                  <div class="col-span-6 sm:col-span-4">
                    <jet-label for="email" value="Email" />
                    <jet-input
                      id="email"
                      type="email"
                      class="mt-1 block w-full"
                      v-model="form.email"
                    />
                    <jet-input-error :message="form.errors.email" class="mt-2" />
                  </div>
                     


                     <!-- birthday -->
                     <div class="col-span-6 sm:col-span-4">
                    <jet-label for="birthday" value="Birthday(optional)" />
                    <jet-input
                      class="mt-1 block w-full"
                      @input="formatDate"
                      id="birthday"
                      type="text"
                      v-model="form.birthday"
                      
                      autocomplete="birthday"
                      placeholder="Birthdate: dd / mm"
                    />
                         <jet-input-error :message="form.errors.birthday" class="mt-2" />
                  </div>

        


                    <div class="col-span-6 sm:col-span-4">
                            <jet-label for="insta_link" value="Instagram Link(optional)" />
                            <jet-input
                              id="insta_link"
                              type="text"
                              class="mt-1 block w-full"
                              v-model="form.insta_link"
                              autocomplete="insta_link"
                              placeholder="Input your instagram  profile link..."
                            />
                            <jet-input-error :message="form.errors.insta_link" class="mt-2" />
                      </div>

                         <div class="col-span-6 sm:col-span-4">
                            <jet-label for="twitter_link" value="Twitter Link(optional)" />
                            <jet-input
                              id="twitter_link"
                              type="text"
                              class="mt-1 block w-full"
                              v-model="form.twitter_link"
                              autocomplete="twitter_link"
                              placeholder="Input your twitter profile link..."
                            />
                            <jet-input-error :message="form.errors.twitter_link" class="mt-2" />
                      </div>


                      <div class="col-span-6 sm:col-span-4">
                            <jet-label for="tiktok_link" value="Tiktok Link(optional)" />
                            <jet-input
                              id="tiktok_link"
                              type="text"
                              class="mt-1 block w-full"
                              v-model="form.tiktok_link"
                              autocomplete="tiktok_link"
                              placeholder="Input your tiktok  profile link..."
                            />
                            <jet-input-error :message="form.errors.tiktok_link" class="mt-2" />
                      </div>
            </div>
      </div>


    </div>
     
    </template>

    <template #actions>
      <jet-action-message :on="form.recentlySuccessful" class="mr-3">
        Saved.
      </jet-action-message>

      <jet-button
        :class="{ 'opacity-25': form.processing }"
        :disabled="form.processing"
      >
        Update Setting
      </jet-button>
    </template>
  </jet-form-section>
</template>

<script>
import JetButton from "@/Jetstream/Button.vue";
import JetFormSection from "@/Jetstream/FormSection.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetInputError from "@/Jetstream/InputError.vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetActionMessage from "@/Jetstream/ActionMessage.vue";
import JetSecondaryButton from "@/Jetstream/SecondaryButton.vue";
export default {
  components: {
    JetActionMessage,
    JetButton,
    JetFormSection,
    JetInput,
    JetInputError,
    JetLabel,
    JetSecondaryButton,
  },
  props: ["user"],
  data() {
    return {
      phone: this.unSetPhone(this.user.phone_number),
      form: this.$inertia.form({
        _method: "PUT",
        first_name: this.user.first_name,
        last_name: this.user.last_name,
        phone_number: null,
        birthday: this.user.birthday,
        email: this.user.email,
        photo: null,
        bio: this.user.bio,
        username: this.user.username,
        insta_link: this.user.instal_link,
        twitter_link: this.user.twitter_link,
        tiktok_link: this.user.tiktok_link,
        displayname: this.user.displayname
      }),
      photoPreview: this.user?.image_url,
    };
  },
 
  methods: {
    updateProfileInformation() {
      if (this.$refs.photo) {
        this.form.photo = this.$refs.photo.files[0];
      }
      this.form.post(route("user-profile-information.update"), {
        errorBag: "updateProfileInformation",
        preserveScroll: true,
      });
    },
    selectNewPhoto() {
      this.$refs.photo.click();
    },
    updatePhotoPreview() {
      const reader = new FileReader();
      reader.onload = (e) => {
        this.photoPreview = e.target.result;
      };
      reader.readAsDataURL(this.$refs.photo.files[0]);
    },
    deletePhoto() {
      this.$inertia.delete(route("current-user-photo.destroy"), {
        preserveScroll: true,
        onSuccess: () => (this.photoPreview = null),
      });
    },
    formatDate(e) {
      //To accomdate for backspacing, we detect which key was pressed - if backspace, do nothing:
      if (e.which !== 8) {
        var numChars = this.form.birthday.length;
        // console.log("len", numChars);
        if (numChars >= 4) {
          this.form.birthday = this.form.birthday.substring(0, 5);
          var last2 = this.form.birthday.slice(-2);
          if (last2 > 12) {
            this.form.birthday = this.form.birthday.substring(0, 3);
          }
          return;
        }
        if (numChars === 2) {
          var last = this.form.birthday.slice(-1);
          if (last != "/") {
            var val = this.form.birthday;
            val += "/";
            this.form.birthday = val;
          }
        }
      }
      return;
    },
  },


  mounted(){

    console.log("this.user:", this.user)
  }
};
</script>