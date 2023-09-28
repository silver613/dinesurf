<template>
    <jet-form-section @submitted="updateProfileInformation">

        <template #form>
            <button
                @click="submitForm()"
                class="float px-2 w-auto h-12 rounded-full btn-red active:shadow-lg mouse shadow transition ease-in duration-200 focus:outline-none font-bold z-1024"
            >
                Save
            </button>
            <div class="px-4">
                <jet-validation-errors class="mb-4" />
                <div
                    v-if="status"
                    class="mb-4 font-medium text-lg text-green-600"
                >
                    <span v-html="status"></span>
                </div>
            </div>
               


            <div class="flex flex-col  md:max-w-md " v-if="this.view == 'general_information'">
                <div class=" md:p-5  w-full">
                    <div class="sm:col-span-4 w-full">
                        <h3 class="text-lg font-medium text-gray-900">
                            Restaurant Information
                        </h3>
                    </div>

                    <!-- Business Name -->
                    <div class="mt-3 sm:col-span-4 w-full">
                        <jet-label for="company_name" value="Restaurant Name" />
                        <jet-input
                            id="company_name"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.company_name"
                            autocomplete="company_name"
                        />
                        <jet-input-error
                            :message="form.errors.company_name"
                            class="mt-2"
                        />
                    </div>

                    <!-- Country -->
                    <div class="col-span-6 mt-3 sm:col-span-4">
                        <jet-label for="country" value="Country" />
                        <select
                            v-model="form.country_id"
                            name="country"
                            id="country"
                            class="block mt-1 w-full form-control"
                            @change="getStates(form.country_id)"
                        >
                            <option :value="null">--Select Country--</option>
                            <option
                                v-for="(item, index) in countries"
                                :key="index"
                                :value="item.id"
                            >
                                {{ item.name }}
                            </option>
                        </select>
                        <jet-input-error
                            :message="form.errors.country_id"
                            class="mt-2"
                        />
                    </div>

                    <!-- state -->
                    <div class="col-span-6 sm:col-span-4 mt-3">
                        <jet-label for="state" value="State" />
                        <select
                            v-model="form.state_id"
                            name="state"
                            id="state"
                            class="mt-1 block w-full form-control"
                            @change="getCities(form.state_id)"
                        >
                            <option :value="null">--Select state--</option>
                            <option
                                v-for="(item, index) in states"
                                :key="index"
                                :value="item.id"
                            >
                                {{ item.name }}
                            </option>
                        </select>
                        <jet-input-error
                            :message="form.errors.state_id"
                            class="mt-2"
                        />
                    </div>

                    <!-- city -->
                    <!-- <div class="col-span-6 mt-3 sm:col-span-4">
            <jet-label for="city" value="City" />
            <select
              v-model="form.city_id"
              name="city"
              id="city"
              class="mt-1 block w-full form-control"
            >
              <option :value="null">--Select city--</option>
              <option
                v-for="(item, index) in cities"
                :key="index"
                :value="item.id"
              >
                {{ item.name }}
              </option>
            </select>
            <jet-input-error :message="form.errors.city_id" class="mt-2" />
          </div> -->

                    <!-- Company Address -->
                    <div class="col-span-6 mt-3 sm:col-span-4">
                        <jet-label
                            for="company_address"
                            value="Company address"
                        />
                        <jet-input
                            id="company_address"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.company_address"
                            autocomplete="company_address"
                        />
                        <input type="hidden" id="city2" name="city2" />
                        <input type="hidden" id="cityLat" name="cityLat" />
                        <input type="hidden" id="cityLng" name="cityLng" />
                        <jet-input-error
                            :message="form.errors.company_address"
                            class="mt-2"
                        />
                    </div>
                </div>

               
               
               
                <div class="md:col-span-6 md:mt-0 mt-5 md:p-5  w-full">
                    <div class="col-span-6 sm:col-span-4 w-full">
                        <h3 class="text-lg font-medium text-gray-900">
                            Admin Information
                        </h3>
                    </div>
                    <!-- First Name -->
                    <div class="col-span-6 mt-3 sm:col-span-4">
                        <jet-label for="first_name" value="First Name" />
                        <jet-input
                            id="first_name"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.first_name"
                            autocomplete="first_name"
                        />
                        <jet-input-error
                            :message="form.errors.first_name"
                            class="mt-2"
                        />
                    </div>

                    <!-- Last Name -->
                    <div class="col-span-6 mt-3 sm:col-span-4">
                        <jet-label for="last_name" value="Last Name" />
                        <jet-input
                            id="last_name"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.last_name"
                            autocomplete="last_name"
                        />
                        <jet-input-error
                            :message="form.errors.last_name"
                            class="mt-2"
                        />
                    </div>

                    <!-- Phone Number -->
                    <div class="col-span-6 mt-3 sm:col-span-4">
                        <jet-label for="phone_number" value="Phone Number" />
                        <phone-input
                            class="w-full mt-1 phone-input"
                            v-model="phone"
                            :modelName="'phone_number'"
                            @setValue="setPhone"
                            required
                        ></phone-input>
                        <jet-input-error
                            :message="form.errors.phone_number"
                            class="mt-2"
                        />
                    </div>

                    <!-- Email -->
                    <!-- <div class="col-span-6 sm:col-span-4">
                  <jet-label for="email" value="Email" />
                  <jet-input
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                  />
                  <jet-input-error :message="form.errors.email" class="mt-2" />
                </div> -->
                </div>
            </div>


        

            <div class="flex md:max-w-md flex-col w-full"  v-if="this.view == 'contact_information'">
                <div class="col-span-4 md:p-5 md:pr-10 w-full">
                    <div class="col-span-6 mb-1 sm:col-span-4 w-full">
                        <h3 class="text-lg font-medium text-gray-900">
                            Contact Information
                        </h3>
                    </div>
                    <!-- Company Email -->
                    <div class="col-span-6 mt-4 sm:col-span-4">
                        <jet-label for="company_email" value="Company Email" />
                        <jet-input
                            id="company_email"
                            type="email"
                            class="mt-1 block w-full"
                            v-model="form.company_email"
                            autocomplete="company_email"
                        />
                        <jet-input-error
                            :message="form.errors.company_email"
                            class="mt-2"
                        />
                    </div>

                    <!-- Company Phone -->
                    <div class="col-span-6 mt-4 sm:col-span-4">
                        <jet-label for="company_phone" value="Company Phone" />
                        <phone-input
                            class="w-full mt-1 phone-input"
                            v-model="form.company_phone"
                            :modelName="'company_phone'"
                            @setValue="setPhone"
                            required
                        ></phone-input>
                        <jet-input-error
                            :message="form.errors.company_phone"
                            class="mt-2"
                        />
                    </div>

                    <!-- Whatsapp Link -->
                    <div class="col-span-6 mt-4 sm:col-span-4">
                        <jet-label for="whatsapp_number">
                            <p class="mb-2">
                                Whatsapp Number:
                                <span
                                    id="whatsappInfo"
                                    class=" mb-3 pb-1 pt-1 ml-1 font-bold rounded-full cursor-pointer"
                                >
                                    <i class="fa fa-info"></i
                                ></span>
                            </p>
                        </jet-label>

                        <!-- <jet-input
                            id="whatsapp_number"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.whatsapp_number"
                            autocomplete="whatsapp_number"
                        /> -->

                        <phone-input
                            class="w-full mt-1 phone-input"
                            v-model="form.whatsapp_number"
                            :modelName="'whatsapp_number'"
                            @setValue="setPhone"
                            required
                        ></phone-input>
                        <jet-input-error
                            :message="form.errors.whatsapp_number"
                            class="mt-2"
                        />
                    </div>
                </div>
            </div>






            <div class="col-span-4 md:p-5  md:max-w-md" v-if="this.view == 'financials'">
                    <div class="col-span-6 mb-3 sm:col-span-4 w-full">
                        <h3 class="text-lg font-medium text-gray-900">
                            Financials
                        </h3>
                    </div>

                     <!-- Accept Reservation Deposits -->
                    <div
                        class="col-span-6 flex items-center sm:col-span-4 cursor-pointer"
                    >
                        <jet-label
                            for="accept_reservation_deposit"
                            value="Want to Accept Reservation Deposits? : "
                        />
                    </div>

                    <div class="col-span-6 sm:col-span-4 mb-6">
                        <div class="flex mt-1">
                            <div class="flex mr-4 items-center">
                                <input
                                    class="form-control mr-1 checkbox-hover"
                                    id="accept_reservation_deposit1"
                                    name="accept_reservation_deposit"
                                    type="radio"
                                    v-model="form.accept_reservation_deposit"
                                    :value="true"
                                />
                                <label for="accept_reservation_deposit1"
                                    >Yes</label
                                >
                            </div>
                            <div class="flex mr-4 items-center">
                                <input
                                    class="form-control mr-1 checkbox-hover"
                                    id="accept_reservation_deposit2"
                                    name="accept_reservation_deposit"
                                    type="radio"
                                    v-model="form.accept_reservation_deposit"
                                    :value="false"
                                />
                                <label for="accept_reservation_deposit2"
                                    >No
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Deposits Required for Reservations -->
                    <div
                        class="col-span-6 flex items-center sm:col-span-4 cursor-pointer"
                    >
                        <jet-label
                            for="reservation_deposit_required"
                            value="Are Deposits Required for Reservations? : "
                        />
                    </div>

                    <div class="col-span-6 sm:col-span-4 mb-6">
                        <div class="flex mt-1">
                            <div class="flex mr-4 items-center">
                                <input
                                    class="form-control mr-1 checkbox-hover"
                                    id="reservation_deposit_required1"
                                    name="reservation_deposit_required"
                                    type="radio"
                                    v-model="form.reservation_deposit_required"
                                    :value="true"
                                />
                                <label for="reservation_deposit_required1"
                                    >Yes</label
                                >
                            </div>
                            <div class="flex mr-4 items-center">
                                <input
                                    class="form-control mr-1 checkbox-hover"
                                    id="reservation_deposit_required2"
                                    name="reservation_deposit_required"
                                    type="radio"
                                    v-model="form.reservation_deposit_required"
                                    :value="false"
                                />
                                <label for="reservation_deposit_required2"
                                    >No
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Minimum Reservvation Amount -->
                    <div class="form-group col-span-2 md:col-span-1">
                        <JetLabel
                            for="min_reservation_deposit"
                            value="Reservation Deposit Per Person"
                        />
                        <div class="relative w-full flex">
                            <div class="w-2/3">
                                <div
                                    class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"
                                >
                                    {{ currencySymbol }}
                                </div>
                                <JetInput
                                    class="!px-7 w-full"
                                    id="min_reservation_deposit"
                                    v-model="form.min_reservation_deposit"
                                    type="number"
                                    min="1000"
                                    value="1000"
                                    @input="validateAmount()"
                                />
                            </div>

                            <select
                                name="currency"
                                id="currency"
                                class="!w-auto form-control"
                                v-model="form.reservation_currency"
                            >
                                <option
                                    v-for="(item, index) in currencies"
                                    :key="index"
                                >
                                    {{ item.currency }}
                                </option>
                            </select>
                        </div>
                        <small class="text-ncdmb-black-200">{{
                            numberWithCommas(
                                form.min_reservation_deposit,
                                form.reservation_currency
                            )
                        }}</small>
                        <JetInputError
                            :message="form.errors['min_reservation_deposit']"
                            class="mt-2"
                        />
                        <JetInputError
                            :message="form.errors['reservation_currency']"
                            class="mt-2"
                        />
                    </div>

                    <!-- Account  Number -->
                    <div class="mt-3 sm:col-span-4 w-full">
                        <jet-label
                            for="account_number"
                            value="Account Number"
                        />
                        <jet-input
                            id="account_number"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.account_number"
                            autocomplete="account_number"
                        />
                        <jet-input-error
                            :message="form.errors.account_number"
                            class="mt-2"
                        />
                    </div>

                    <!-- Bank Code -->
                    <div class="col-span-6 mt-3 sm:col-span-4">
                        <jet-label for="bank" value="Bank Name" />
                        <select
                            v-model="form.bank_code"
                            name="bank"
                            id="bank"
                            class="block mt-1 w-full form-control"
                            @change="verifyAccountNumber()"
                        >
                            <option :value="null">--Select Bank--</option>
                            <option
                                v-for="(item, index) in banks"
                                :key="index"
                                :value="item.sort_code"
                            >
                                {{ item.name }}
                            </option>
                        </select>
                        <jet-input-error
                            :message="form.errors.bank_code"
                            class="mt-2"
                        />
                    </div>

                    <!-- Account  Name -->
                    <div class="mt-3 sm:col-span-4 w-full">
                        <jet-label for="account_name" value="Account Name" />
                        <jet-input
                            readonly
                            disabled
                            id="account_name"
                            type="text"
                            class="mt-1 block w-full capitalize"
                            v-model="form.account_name"
                            autocomplete="account_name"
                        />

                        <span v-if="verifying"
                            >Verifying... <i class="fa fa-spinner spin"></i
                        ></span>
                        <jet-input-error
                            :message="form.errors.account_name"
                            class="mt-2"
                        />
                    </div>
                </div>
            





                <div class="flex md:mt-0 mt-6 flex-col md:max-w-md" v-if="this.view == 'restaurant_profile_info'">
                <div class="col-span-4 md:p-5 md:pr-10 w-full">
                    <div class="col-span-6 mb-1 sm:col-span-4 w-full">
                        <h3 class="text-lg font-medium text-gray-900">
                            Restaurant Profile Information
                        </h3>
                    </div>
                    <!-- Description -->
                    <div class="col-span-6 mt-4 sm:col-span-4">
                        <jet-label for="description" value="Description" />
                        <textarea
                            id="description"
                            class="mt-1 block w-full rounded-md border border-gray-300  shadow-sm"
                            v-model="form.description"
                            autocomplete="description"
                        ></textarea>
                        <jet-input-error
                            :message="form.errors.description"
                            class="mt-2"
                        />
                    </div>
                    <!-- Party Size -->
                    <div class="col-span-6 mt-4 sm:col-span-4">
                        <jet-label for="party_size" value="Max Party Size" />
                        <jet-input
                            id="party_size"
                            type="number"
                            min="0"
                            class="mt-1 block w-full"
                            v-model="form.party_size"
                            autocomplete="party_size"
                        />
                        <jet-input-error
                            :message="form.errors.party_size"
                            class="mt-2"
                        />
                    </div>

                    <!-- Seating preferences -->
                    <div class="col-span-6 mt-4 sm:col-span-4">
                        <jet-label for="seating_preference"
                            >Seating preferences:
                            <!-- <span
                                id="priceSymbolInfo"
                                class="ml-1 -mt-1 mb-2 pb-1 pt-1  rounded-full  cursor-pointer"
                            >
                                <i class="fa fa-info"></i
                            </span> -->
                        </jet-label>
                        <div>
                            <div
                                class="flex items-center mt-1 relative rounded-md shadow-sm"
                                id="priceSymbolInfo"
                            >
                                <input
                                    v-model="seating_preference.name"
                                    type="text"
                                    name="seating_preference"
                                    id="seating_preference"
                                    class="block w-full form-control"
                                    placeholder="Enter Preference"
                                />
                                <div
                                    class="flex w-[12rem] flex-col items-center mx-3 pt-1"
                                >
                                    <input
                                        class="form-control mr-1"
                                        id="sp_requried"
                                        name="sp_requried"
                                        type="checkbox"
                                        v-model="seating_preference.required"
                                    />
                                    <label for="day2" class="capitalize"
                                        >Make Required</label
                                    >
                                </div>
                                <jet-button
                                    :type="'button'"
                                    @click="addPreference"
                                >
                                    Add
                                </jet-button>
                            </div>
                        </div>
                        <jet-input-error
                            :message="form.errors.seating_preferences"
                            class="mt-3"
                        />
                        <div class="my-3">
                            <ol
                                class="dec pad1"
                                v-if="form.seating_preferences.length > 0"
                            >
                                <li
                                    class="mb-3"
                                    v-for="(
                                        item, index
                                    ) in form.seating_preferences"
                                    :key="index"
                                >
                                    <div class="flex">
                                        <div>
                                            {{ item.name }}
                                        </div>
                                        <div class="ml-2">
                                            <i
                                                @click="removePreference(index)"
                                                class="fas fa-trash cursor-pointer text-red-500"
                                            ></i>
                                        </div>
                                    </div>
                                </li>
                            </ol>
                            <div v-else>No Seating preferences Set</div>
                        </div>
                    </div>
                </div>
                <div class="col-span-4 md:px-5   w-full">
                    <!-- Cancellation Policy -->
                    <div class="col-span-6 mt-4 sm:col-span-4">
                        <jet-label
                            for="cancellation_policy"
                            value="Cancellation Policy"
                        />
                        <textarea
                            id="cancellation_policy"
                            class="mt-1 block w-full rounded-md border border-gray-300  shadow-sm"
                            v-model="form.cancellation_policy"
                            autocomplete="cancellation_policy"
                        ></textarea>
                        <jet-input-error
                            :message="form.errors.cancellation_policy"
                            class="mt-2"
                        />
                    </div>

                    <!-- Dress Code -->
                    <div class="col-span-6 mt-4 sm:col-span-4">
                        <jet-label for="dress_code" value="Dress Code" />
                        <textarea
                            id="dress_code"
                            class="mt-1 block w-full rounded-md border border-gray-300  shadow-sm"
                            v-model="form.dress_code"
                            autocomplete="dress_code"
                        ></textarea>
                        <jet-input-error
                            :message="form.errors.dress_code"
                            class="mt-2"
                        />
                    </div>
                </div>




                <div class="flex md:mt-0 mt-6  flex-col w-full" >
                <div class="col-span-4 md:p-5 w-full">
                    <div class="col-span-6 mb-1 sm:col-span-4 w-full">
                        <h3 class="text-lg font-medium text-gray-900">
                            Social Media
                        </h3>
                    </div>
                    <!-- Facebook Link -->
                    <div class="col-span-6 sm:col-span-4">
                        <jet-label for="facebook_link" value="Facebook Link" />
                        <jet-input
                            id="facebook_link"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.facebook_link"
                            autocomplete="facebook_link"
                        />
                        <jet-input-error
                            :message="form.errors.facebook_link"
                            class="mt-2"
                        />
                    </div>

                    <!-- Instagram Link -->
                    <div class="col-span-6 mt-3 sm:col-span-4">
                        <jet-label
                            for="instagram_link"
                            value="Instagram Link"
                        />
                        <jet-input
                            id="instagram_link"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.instagram_link"
                            autocomplete="instagram_link"
                        />
                        <jet-input-error
                            :message="form.errors.instagram_link"
                            class="mt-2"
                        />
                    </div>

                    <!-- twitter Link -->
                    <div class="col-span-6 mt-3 sm:col-span-4">
                        <jet-label for="twitter_link" value="twitter Link" />
                        <jet-input
                            id="twitter_link"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.twitter_link"
                            autocomplete="twitter_link"
                        />
                        <jet-input-error
                            :message="form.errors.twitter_link"
                            class="mt-2"
                        />
                    </div>
                </div>
                <div class="col-span-4 md:px-5  w-full">
                    <!-- Youtube Link -->
                    <div class="col-span-6 sm:col-span-4">
                        <jet-label for="youtube_link" value="Youtube Link" />
                        <jet-input
                            id="youtube_link"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.youtube_link"
                            autocomplete="youtube_link"
                        />
                        <jet-input-error
                            :message="form.errors.youtube_link"
                            class="mt-2"
                        />
                    </div>

                    <!-- Linkedin Link -->
                    <div class="col-span-6 mt-3 sm:col-span-4">
                        <jet-label for="linkedin_link" value="Linkedin Link" />
                        <jet-input
                            id="linkedin_link"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.linkedin_link"
                            autocomplete="linkedin_link"
                        />
                        <jet-input-error
                            :message="form.errors.linkedin_link"
                            class="mt-2"
                        />
                    </div>
                </div>
            </div>
             



            <div class="flex md:mt-0 mt-6 flex-col w-full">
                <div class="col-span-4 md:p-5 w-full">
                    <div class="col-span-6 mb-1 sm:col-span-4 w-full">
                        <h3 class="text-lg font-medium text-gray-900">
                            Cuisine
                        </h3>
                    </div>

                    <!-- Cuisines -->
                    <div class="col-span-6 sm:col-span-4">
                        <div class="flex flex-wrap mt-3">
                            <select
                                @change="
                                    (e) => updateVendorCuisine(e.target.value)
                                "
                                name="cuisine"
                                id="cuisine"
                                class="mt-1 block w-full form-control"
                            >
                                <option :value="null">--Add cuisine--</option>
                                <option
                                    v-for="(item, index) in cuisines"
                                    :key="index"
                                    :value="item.id"
                                >
                                    {{ item.name }}
                                </option>
                            </select>
                        </div>
                        <div class="flex mt-4 flex-wrap">
                            <div
                                class="border p-2 mr-3"
                                v-for="(item, index) in selectedCuisines"
                                :key="index"
                            >
                                <span>{{ item.name }}</span>
                                <span class="ml-2">
                                    <i
                                        @click="removeCusine(item.id)"
                                        class="fa fa-remove cursor-pointer"
                                    ></i
                                ></span>
                            </div>
                        </div>
                        <jet-input-error
                            :message="form.errors.cuisines"
                            class="mt-2"
                        />
                    </div>
                </div>

                <div class="col-span-4 md:mt-0 mt-6 md:p-5 w-full">
                    <div class="col-span-6 mb-1 sm:col-span-4 w-full">
                        <h3 class="text-lg font-medium text-gray-900">
                            Feature
                        </h3>
                    </div>

                    <div class="flex flex-wrap mt-3">
                        <select
                            @change="(e) => updateVendorFeature(e.target.value)"
                            name="feature"
                            id="feature"
                            class="mt-1 block w-full form-control"
                        >
                            <option :value="null">--Add Feature--</option>
                            <option
                                v-for="(item, index) in features"
                                :key="index"
                                :value="item.id"
                            >
                                {{ item.name }}
                            </option>
                        </select>
                    </div>

                    <div class="flex mt-4 flex-wrap">
                        <div
                            class="border p-2 mr-3"
                            v-for="(item, index) in selectedFeatures"
                            :key="index"
                        >
                            <span>{{ item.name }}</span>
                            <span class="ml-2">
                                <i
                                    @click="removeFeature(item.id)"
                                    class="fa fa-remove cursor-pointer"
                                ></i
                            ></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        

            <div class="flex md:mt-0 mt-6 md:flex-row flex-col w-full" v-if="this.view == 'open_time_availability'">
                <div class="col-span-4 md:p-5 md:pr-10 w-full">
                    <div class="col-span-6 mb-2 sm:col-span-4 w-full">
                        <h3 class="text-lg font-medium text-gray-900">
                            Open Times & Availabilty
                        </h3>
                    </div>

                    <!-- Open -->
                    <div
                        class="col-span-6 flex items-center sm:col-span-4 cursor-pointer"
                    >
                        <jet-label
                            for="open"
                            class="mr-1"
                            value="Are you currently opened? : "
                        />
                        <input
                            class="form-control mr-1 checkbox-hover"
                            id="open"
                            type="checkbox"
                            v-model="form.open"
                        />
                        <jet-input-error
                            :message="form.errors.open"
                            class="mt-2"
                        />
                    </div>

                    <!-- Time and date -->
                    <div
                        class="col-span-6 sm:col-span-4 mb-3"
                        :class="{
                            'pointer-events-none opacity-50': !form.open,
                        }"
                    >
                        <div class="flex mt-3">
                            <div class="flex mr-4 items-center">
                                <input
                                    class="form-control mr-1 checkbox-hover"
                                    id="time_and_date1"
                                    name="time_and_date"
                                    type="radio"
                                    v-model="form.is_same_time"
                                    :value="true"
                                />
                                <label for="time_and_date1"
                                    >Same Time Everyday</label
                                >
                            </div>
                            <div class="flex mr-4 items-center">
                                <input
                                    class="form-control mr-1 checkbox-hover"
                                    id="time_and_date2"
                                    name="time_and_date"
                                    type="radio"
                                    v-model="form.is_same_time"
                                    :value="false"
                                />
                                <label for="time_and_date2">Custom time </label>
                            </div>
                        </div>
                    </div>

                    <!-- Days -->
                    <div
                        class="col-span-6 sm:col-span-4 mt-4"
                        :class="{
                            'pointer-events-none opacity-50': !form.open,
                        }"
                    >
                        <template v-if="!form.is_same_time">
                            <div
                                class="flex w-full justify-between mb-3 items-end"
                                v-for="(item, index) in days"
                                :key="index"
                            >
                                <div class="w-full md:mr-2">
                                    <div class="flex mr-4 items-start">
                                        <jet-label
                                            for="open_time"
                                            class="mr-2 capitalize"
                                        >
                                            {{ item.name }}
                                            <span
                                                class="text-xs font-bold text-gray-400 block"
                                                >open</span
                                            >
                                        </jet-label>
                                        <input
                                            class="form-control mr-1 mt-1 checkbox-hover"
                                            id="time_and_date"
                                            type="checkbox"
                                            v-model="
                                                days[index].vendor_attached
                                            "
                                            :value="true"
                                        />
                                        <!-- <label for="time_and_date">Open </label> -->
                                    </div>
                                    <jet-input
                                        id="open_time"
                                        type="time"
                                        class="mt-1 block w-full"
                                        v-model="days[index].open_time"
                                        autocomplete="open_time"
                                    />
                                    <jet-input-error
                                        :message="form.errors.days"
                                        class="mt-2"
                                    />
                                </div>

                                <div class="w-full md:ml-2">
                                    <jet-label
                                        for="open_time"
                                        class="mr-2 capitalize"
                                    >
                                        <span
                                            class="text-xs font-bold text-gray-400 block"
                                            >close</span
                                        >
                                    </jet-label>
                                    <jet-input
                                        id="close_time"
                                        type="time"
                                        class="mt-1 block w-full"
                                        v-model="days[index].close_time"
                                        autocomplete="close_time"
                                    />
                                    <jet-input-error
                                        :message="form.errors.days"
                                        class="mt-2"
                                    />
                                </div>
                            </div>
                        </template>
                        <div
                            v-if="form.is_same_time"
                            class="flex w-full justify-between mb-3 items-end"
                        >
                            <div class="w-full md:mr-2">
                                <div class="flex mr-4 items-center">
                                    <jet-label for="open_time" class="mr-2">
                                        <span class="capitalize">
                                            Everyday
                                        </span>
                                    </jet-label>
                                </div>
                                <jet-input
                                    id="open_time"
                                    type="time"
                                    class="mt-1 block w-full"
                                    v-model="form.open_time"
                                    autocomplete="open_time"
                                />
                                <jet-input-error
                                    :message="form.errors.open_time"
                                    class="mt-2"
                                />
                            </div>

                            <div class="w-full md:ml-2">
                                <jet-input
                                    id="close_time"
                                    type="time"
                                    class="mt-1 block w-full"
                                    v-model="form.close_time"
                                    autocomplete="close_time"
                                />
                                <jet-input-error
                                    :message="form.errors.close_time"
                                    class="mt-2"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            



            <div class="col-span-4 md:mt-0 mt-6 md:p-5 md:pl-10 md:max-w-md"  v-if="this.view == 'menu_highlight'">
                    <div class="col-span-6 mb-1 sm:col-span-4 w-full">
                        <h3 class="text-lg font-medium text-gray-900">
                            Menu Highlights
                        </h3>
                    </div>

                    <!-- Average Menu Price -->
                    <div
                        class="col-span-6 sm:col-span-4 flex flex-wrap items-center"
                        id="average_menu_price"
                    >
                        <div class="w-full flex">
                            <jet-label
                                for="average_menu_price"
                                value="Average Menu Price"
                            />
                            <!-- <span
                                id="priceSymbolInfo"
                                class="ml-3 -mt-1 pb-1 pt-1 bg-gray-200 rounded-full p-2 h-[2rem] w-[2rem] cursor-pointer text-center"
                            >
                                <i class="fa fa-info"></i
                            ></span> -->
                        </div>
                        <div class="w-1/5 -mr-2"  id="priceSymbolInfo">
                            <select
                                v-model="form.average_menu_price_currency"
                                name="average_menu_price_currency"
                                id="average_menu_price_currency"
                                class="mt-1 block w-full form-control"
                            >
                                <option selected>
                                    {{ form.average_menu_price_currency }}
                                </option>
                                <option value="NGN">NGN</option>
                                <option value="USD">USD</option>
                                <option value="GHS">GHS</option>
                            </select>
                            <jet-input-error
                                :message="
                                    form.errors.average_menu_price_currency
                                "
                                class="mt-2"
                            />
                        </div>
                        <div class="w-4/5">
                            <jet-input
                                id="average_menu_price"
                                type="number"
                                class="mt-1 block w-full"
                                v-model="form.average_menu_price"
                                autocomplete="average_menu_price"
                            />
                            <jet-input-error
                                :message="form.errors.average_menu_price"
                                class="mt-2"
                            />
                        </div>
                        <div class="w-full mt-1 text-base font-bold">
                            ~
                            {{
                                numberWithCommas(
                                    form.average_menu_price,
                                    form.average_menu_price_currency
                                )
                            }}
                        </div>
                    </div>

                    <!-- Menu Link -->
                    <div class="col-span-6 mt-3 sm:col-span-4">
                        <jet-label for="menu_link" value="Menu Link" />
                        <jet-input
                            id="menu_link"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.menu_link"
                            autocomplete="menu_link"
                        />
                        <jet-input-error
                            :message="form.errors.menu_link"
                            class="mt-2"
                        />
                    </div>

                       <!-- Use menu link as an external page ?  -->
                       <div
                        class="col-span-6 flex items-center sm:col-span-4 mt-4 cursor-pointer"
                    >
                        <jet-label
                            for="accept_reservation_deposit"
                            value="Want to use this menu link as page?(this is where we would redirect user to when scaned your Qrcode) : "
                        />
                    </div>

                    <div class="col-span-6 sm:col-span-4 mb-6">
                        <div class="flex mt-1">
                            <div class="flex mr-4 items-center">
                                <input
                                    class="form-control mr-1 checkbox-hover"
                                    id="accept_reservation_deposit1"
                                    name="accept_reservation_deposit"
                                    type="radio"
                                    @change="handleExternalMenuSwitch"
                                    v-model="form.use_external_menu_page"
                                    :value="true"
                                />
                                <label for="accept_reservation_deposit1"
                                    >Yes</label
                                >
                            </div>
                            <div class="flex mr-4 items-center">
                                <input
                                    class="form-control mr-1 checkbox-hover"
                                    id="accept_reservation_deposit2"
                                    name="accept_reservation_deposit"
                                    type="radio"
                                    @change="handleExternalMenuSwitch"
                                    v-model="form.use_external_menu_page"
                                    :value="false"
                                />
                                <label for="accept_reservation_deposit2"
                                    >No
                                </label>
                            </div>
                        </div>
                    </div>


                    <!-- Attach Additional Doc -->
                    <div class="col-span-6 sm:col-span-4 mt-4">
                        <jet-label
                            for="attach_menu_pdf"
                            value="Attach Additional Doc to Reservation E-Mails"
                        />
                        <div class="flex justify-start">
                            <div class="form-check form-check-inline mr-5">
                                <input
                                    v-model="form.attach_menu_pdf"
                                    class="form-check-input form-check-input appearance-none rounded-full h-4 w-4 border border-gray-300 bg-white checked:bg-dinesurf-green-600 checked:border-dinesurf-green-600 focus:outline-none transition duration-200 mt-1 align-top bg-no-repeat bg-center bg-contain float-left mr-2 cursor-pointer"
                                    type="radio"
                                    name="inlineRadioOptions"
                                    id="inlineRadio1"
                                    :value="true"
                                />
                                <label
                                    class="form-check-label inline-block text-gray-800"
                                    for="inlineRadio10"
                                    >Yes</label
                                >
                            </div>
                            <div class="form-check form-check-inline">
                                <input
                                    v-model="form.attach_menu_pdf"
                                    class="form-check-input appearance-none rounded-full h-4 w-4 border border-gray-300 bg-white checked:bg-dinesurf-green-600 checked:border-dinesurf-green-600 focus:outline-none transition duration-200 mt-1 align-top bg-no-repeat bg-center bg-contain float-left mr-2 cursor-pointer"
                                    type="radio"
                                    name="inlineRadioOptions"
                                    id="inlineRadio2"
                                    :value="false"
                                />
                                <label
                                    class="form-check-label inline-block text-gray-800"
                                    for="inlineRadio20"
                                    >No</label
                                >
                            </div>
                            <jet-input-error
                                :message="form.errors.attach_menu_pdf"
                                class="mt-2"
                            />
                        </div>
                    </div>

                    <!-- Additional Doc -->
                    <div
                        class="col-span-6 mt-4 sm:col-span-4"
                        v-if="form.attach_menu_pdf"
                    >
                        <!-- <jet-label for="menuPdf">
                        <span>Additional Doc</span>
                        
                      </jet-label> -->
                        <div
                            class="flex items-center border border-dashed border-dinesurf-green-400 bg-gray-100 justify-between p-3"
                        >
                            <div class="flex flex-col">
                                <span class="text-dinesurf-green-400">
                                    Upload Additional Doc
                                </span>
                                <span class="text-xs">Max 5MB,PDF</span>
                                <span
                                    v-if="vendor.menu_pdf"
                                    class="ml-1 capitalize"
                                >
                                    <span class="mr-2 text-green-600"
                                        >Uploaded</span
                                    >
                                    <a
                                        :href="vendor.menu_pdf_url"
                                        target="_blank"
                                        class="text-blue-400"
                                        >View >>></a
                                    >
                                </span>
                                <span class="text-xs" v-else>
                                    No file uploaded
                                </span>
                            </div>
                            <button
                                type="button"
                                class="bg-dinesurf-green-400 w-32 h-10 pt-2 text-white"
                            >
                                <span>Choose a file</span>
                                <jet-input
                                    :id="'menuPdf'"
                                    :name="'menuPdf'"
                                    type="file"
                                    @input="
                                        form.menu_pdf = $event.target.files[0]
                                    "
                                    class="-mt-4 block w-full opacity-0"
                                    accept=".pdf"
                                />
                            </button>
                        </div>

                        <jet-input-error
                            :message="form.errors.menu_pdf"
                            class="mt-2"
                        />
                    </div>
                </div>
           


              <div class="flex md:mt-0 mt-6 flex-col w-full" v-if="this.view == 'view_restaurant_profile'">
                <div class="col-span-4 md:p-5 w-full">
                    <div class="col-span-6 mb-1 flex  justify-between w-full">
                        <h3 class="text-lg font-light text-gray-900">
                            How a listing appears
                        </h3>

                        <flash-text
                        :title="'To view your listing'"
                        :link="route('restaurants.index', {id: this.vendor.id})"
                        :status="'success'"
                        />
                    </div>

                </div>

                <div class="border-4  rounded bg-gray-100  h-[60rem]  p-10 ">
                    <iframe class="w-full h-full" style="width: 100%; height: 100%" :src="route('restaurants.index', {id: this.vendor.id})"></iframe>
                </div>
            </div>


    </template>
     

    <!-- <div v-if="this.view != 'view_restaurant_profile'">

    </div> -->
        <template   #actions>

            <div v-if="this.view != 'view_restaurant_profile'">
                <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                    Saved.
                </jet-action-message>

                <jet-button
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    :id="'saveBtn'"
                    class="bg-dinesurf-green-600"
                >
                    Save Changes
                </jet-button>
           </div>
        </template>
    </jet-form-section>
</template>

<script>
import moment from "moment";
import JetButton from "@/Jetstream/Button.vue";
import JetFormSection from "@/Jetstream/FormSection2.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetInputError from "@/Jetstream/InputError.vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetActionMessage from "@/Jetstream/ActionMessage.vue";
import JetSecondaryButton from "@/Jetstream/SecondaryButton.vue";
import JetValidationErrors from "@/Jetstream/ValidationErrors.vue";
import axios from "axios";
import tippy from "tippy.js";
import "tippy.js/dist/tippy.css"; // optional for styling
export default {
    components: {
        JetActionMessage,
        JetButton,
        JetFormSection,
        JetInput,
        JetInputError,
        JetLabel,
        JetSecondaryButton,
        JetValidationErrors,
    },
    props: ["vendor", "status", 'view'],
    computed: {
        currencySymbol() {
            var symbol = null;
            this.currencies.forEach((el) => {
                if (el.currency == this.form.reservation_currency) {
                    symbol = el.symbol;
                }
            });
            return symbol;
        },
    },
    data() {
        return {
            currencies: [
                {
                    symbol: "₦‎",
                    currency: "NGN",
                },
                {
                    symbol: "$",
                    currency: "USD",
                },
                {
                    symbol: "¢",
                    currency: "GHS",
                },
            ],
            banks: [],
            city2: null,
            cityLat: null,
            cityLng: null,
            selectedCuisines: [],
            selectedFeatures: [],
            cuisines: [],
            days: [],
            features: [],
            time_and_date: this.vendor.open_time ? false : true,
            vendor_cuisines: [],
            vendor_days: [],
            vendor_features: [],
            countries: [],
            states: [],
            cities: [],
            seating_preference: {
                name: null,
                required: false,
            },
            phone: this.unSetPhone(this.vendor.phone_number),
            company_phone: this.unSetPhone(this.vendor.company_phone),
            form: this.$inertia.form({
                _method: "POST",
                id: this.vendor.id,
                first_name: this.vendor.first_name,
                last_name: this.vendor.last_name,
                phone_number: this.vendor.phone_number,
                lat: this.vendor.latitude,
                lng: this.vendor.longitude,
                // email: this.vendor.email,
                cuisines: [],
                days: [],
                features: [],
                photo: null,
                lat: this.vendor.latitude,
                lng: this.vendor.longitude,
                company_name: this.vendor.company_name,
                company_address: this.vendor.company_address,
                country_id: this.vendor.country_id??128,
                state_id: this.vendor.state_id,
                city_id: this.vendor.city_id,
                party_size: this.vendor.party_size,
                open: this.vendor.open,
                open_time: this.vendor.open_time,
                close_time: this.vendor.close_time,
                is_same_time: this.vendor.is_same_time === 0 ? false : true,
                description: this.vendor.description,
                company_email: this.vendor.company_email,
                company_phone: this.vendor.company_phone,
                cancellation_policy: this.vendor.cancellation_policy,
                menu_link: this.vendor.menu_link,
                whatsapp_number: this.vendor.whatsapp_number,
                facebook_link: this.vendor.facebook_link,
                instagram_link: this.vendor.instagram_link,
                twitter_link: this.vendor.twitter_link,
                youtube_link: this.vendor.youtube_link,
                linkedin_link: this.vendor.linkedin_link,
                seating_preferences: [],
                menu_highlights: this.vendor.menu_highlights,
                average_menu_price_currency:
                    this.vendor.average_menu_price_currency,
                average_menu_price: this.vendor.average_menu_price,
                dress_code: this.vendor.dress_code,
                menu_pdf: this.vendor.menu_pdf,
                attach_menu_pdf: this.vendor.attach_menu_pdf,
                bank_code: this.vendor.bank_code,
                account_number: this.vendor.account_number,
                account_name: this.vendor.account_name,
                reservation_deposit_required:
                    this.vendor.reservation_deposit_required,
                min_reservation_deposit: this.vendor.min_reservation_deposit,
                reservation_currency: this.vendor.reservation_currency,
                accept_reservation_deposit: this.vendor.accept_reservation_deposit,
                use_external_menu_page: this.vendor.use_external_menu_page
            }),
            photoPreview: null,
            verifying: false,
        };
    },
    methods: {
        validateAmount() {
            if (this.form.min_reservation_deposit < 0) {
                this.form.min_reservation_deposit =
                    this.form.min_reservation_deposit * -1;
            }
        },
        handleTimeChange(value) {},
        updateVendorCuisine(selectedCuisineId) {
            if (!this.vendor_cuisines.includes(selectedCuisineId)) {
                this.vendor_cuisines.push(Number(selectedCuisineId));
                this.setVendorCuisines();
            }
        },
        removeCusine(cuisineId) {
            const vendor_cuisines = this.vendor_cuisines.filter((item) => {
                return item !== cuisineId;
            });
            this.vendor_cuisines = vendor_cuisines;
            this.setVendorCuisines();
        },
        removeFeature(featureId) {
            const vendor_features = this.vendor_features.filter((item) => {
                return item !== featureId;
            });
            this.vendor_features = vendor_features;
            this.setVendorFeatures();
        },
        updateVendorFeature(selectedFeatureId) {
            if (!this.vendor_features.includes(selectedFeatureId)) {
                this.vendor_features.push(Number(selectedFeatureId));
                this.setVendorFeatures();
            }
        },
        submitForm() {
            document.getElementById("saveBtn").click();
        },
        displayResult() {
            let selectElement = document.getElementById("mySelect");
            let optionNames = [...selectElement.options].map((o) => o.text);
            console.log(optionNames);
        },
        formatedTime(time) {
            if (time) {
                return moment(time, "HH:mm:ss").format("hh:mm A");
            }
            return;
        },
        showMe() {
            // console.log("cuisines: ", this.cuisines);
        },
        updateProfileInformation() {
            this.form.cuisines = this.cuisines;
            this.form.features = this.features;
            if (this.cityLat) {
                this.form.lat = this.cityLat;
            }
            if (this.cityLng) {
                this.form.lng = this.cityLng;
            }

            if (!this.form.is_same_time) {
                this.form.days = this.days;
            }

            var text = this.form.description;
            if (text) {
                this.form.description = text.replace(/\n\r?/g, "<br />");
            }

            this.form.post(route("vendors.profile.update"), {
                forceFormData: true,
                onSuccess: () => {
                    toastr.success("Vendor Account Updated");
                    this.reset();
                },
            });
        },
        reset() {
            var text = this.vendor.description;
            if (text) {
                this.form.description = text.replace(/<br\s*[\/]?>/gi, "\n");
            }
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
        setVendorCuisines() {
            this.cuisines.forEach((el, index) => {
                if (this.vendor_cuisines.includes(el.id)) {
                    this.cuisines[index].vendor_attached = true;
                } else {
                    this.cuisines[index].vendor_attached = false;
                }
            });

            this.getSelectedCuisine();
        },
        setVendorDays() {
            this.days.forEach((el, index) => {
                this.vendor_days.forEach((vday) => {
                    if (vday.day_id == el.id) {
                        this.days[index].vendor_attached = true;
                        this.days[index].open_time = vday.open_time;
                        this.days[index].close_time = vday.close_time;
                    }
                });
            });
        },
        setVendorFeatures() {
            this.features.forEach((el, index) => {
                if (this.vendor_features.includes(el.id)) {
                    this.features[index].vendor_attached = true;
                } else {
                    this.features[index].vendor_attached = false;
                }
            });
            this.getSelectedFeatures();
        },
        getCuisines() {
            axios(route("cuisines"))
                .then((result) => {
                    this.cuisines = result.data.data.cuisines;
                    this.getVendorCuisines();
                    this.getSelectedCuisine();
                })
                .catch((err) => {
                    this.handleApiError(err);
                });
        },
        getDays() {
            axios(route("days"))
                .then((result) => {
                    this.days = result.data.data.days;
                    this.getVendorDays();
                })
                .catch((err) => {
                    this.handleApiError(err);
                });
        },
        getFeatures() {
            axios(route("features"))
                .then((result) => {
                    this.features = result.data.data.features;
                    this.getVendorFeatures();
                })
                .catch((err) => {
                    this.handleApiError(err);
                });
        },
        getVendorCuisines() {
            if (this.vendor) {
                axios(
                    route("cuisines", {
                        vendor_id: this.vendor.id,
                        only_ids: true,
                    })
                )
                    .then((result) => {
                        this.vendor_cuisines = result.data.data.cuisines;
                        this.setVendorCuisines();
                    })
                    .catch((err) => {
                        this.handleApiError(err);
                    });
            }
        },
        getVendorDays() {
            if (this.vendor) {
                axios(route("days", { vendor_id: this.vendor.id }))
                    .then((result) => {
                        this.vendor_days = result.data.data.days;
                        this.setVendorDays();
                    })
                    .catch((err) => {
                        this.handleApiError(err);
                    });
            }
        },
        getVendorFeatures() {
            if (this.vendor) {
                axios(
                    route("features", {
                        vendor_id: this.vendor.id,
                        only_ids: true,
                    })
                )
                    .then((result) => {
                        this.vendor_features = result.data.data.features;
                        this.setVendorFeatures();
                    })
                    .catch((err) => {
                        this.handleApiError(err);
                    });
            }
        },
        getSeatingPreferences() {
            axios(route("vendors.seating-preferences"))
                .then((result) => {
                    this.form.seating_preferences =
                        result.data.data.seating_preferences;
                })
                .catch((err) => {
                    this.handleApiError(err);
                });
        },
        initialize() {
            var input = document.getElementById("company_address");
            var autocomplete = new google.maps.places.Autocomplete(input);
            this.complete = autocomplete;
            var text = this.vendor.description;
            if (text) {
                this.form.description = text.replace(/<br\s*[\/]?>/gi, "\n");
            }
            var vm = this;
            google.maps.event.addListener(
                autocomplete,
                "place_changed",
                function () {
                    var place = autocomplete.getPlace();
                    vm.city2 = place.name;
                    console.log("city2: ", vm.city2);
                    document.getElementById("city2").value = place.name;
                    vm.cityLat = place.geometry.location.lat();
                    console.log("cityLat: ", vm.cityLat);
                    document.getElementById("cityLat").value =
                        place.geometry.location.lat();
                    vm.cityLng = place.geometry.location.lng();
                    console.log("cityLng: ", vm.cityLng);
                    document.getElementById("cityLng").value =
                        place.geometry.location.lng();
                    vm.check();
                }
            );
        },
        check() {
            this.form.company_address =
                document.getElementById("company_address").value;
            // console.log(document.getElementById("company_address").value);
            // console.log(this.city2, this.cityLat, this.cityLng);
        },
        addPreference() {
            var error = false;
            if (!this.seating_preference.name) {
                error = true;
                alert("Please enter a Seating Preference");
                return false;
            }
            this.form.seating_preferences.forEach((el) => {
                if (el.name == this.seating_preference.name) {
                    error = true;
                    alert("you've already entered this seating prefrence");
                    return false;
                }
            });
            if (error) {
                return;
            }
            this.form.seating_preferences.push({
                name: this.seating_preference.name,
                required: this.seating_preference.required,
            });
            this.seating_preference = {
                name: null,
                required: false,
            };
        },
        removePreference(key) {
            if (
                !confirm("Are you sure you want to remove this share holder?")
            ) {
                return;
            }
            this.form.seating_preferences.splice(key, 1);
        },
        getLocations() {
            axios(route("countries"))
                .then((result) => {
                    this.countries = result.data.data;
                })
                .catch((err) => {
                    this.handleApiError(err);
                });
            if (this.form.country_id) {
                this.getStates(this.form.country_id);
            }
            if (this.form.state_id) {
                this.getCities(this.form.state_id);
            }
        },
        getStates(country_id) {
            axios(
                route("states", {
                    country_id: country_id,
                })
            )
                .then((result) => {
                    this.states = result.data.data;
                })
                .catch((err) => {
                    this.handleApiError(err);
                });
        },
        getSelectedCuisine() {
            const cuisines = this.cuisines.filter((item) => {
                return item.vendor_attached == true;
            });
            this.selectedCuisines = cuisines;
        },
        getSelectedFeatures() {
            const features = this.features.filter((item) => {
                return item.vendor_attached == true;
            });
            this.selectedFeatures = features;
        },
        getCities(state_id) {
            axios(
                route("cities", {
                    state_id: state_id,
                })
            )
                .then((result) => {
                    this.cities = result.data.data;
                })
                .catch((err) => {
                    this.handleApiError(err);
                });
        },

        getBanks() {
            axios(route("banks"))
                .then((result) => {
                    this.banks = result.data.data;
                })
                .catch((err) => {
                    this.handleApiError(err);
                });
        },

        verifyAccountNumber() {
            this.verifying = true;
            if (this.form.account_number === null) {
                this.handleApiError("Account number can't be empty");
                this.verifying = false;
                return;
            } else {
                const account_number = this.form.account_number;
                const bank_code = this.form.bank_code;

                axios(
                    route("verify-account", {
                        account_number,
                        bank_code,
                    })
                )
                    .then((result) => {
                        toastr.success(result.data.data.message);
                        this.form.account_name =
                            result.data.data.data.account_name;
                    })
                    .catch((err) => {
                        this.handleApiError(err);
                    })
                    .finally(() => (this.verifying = false));
            }
        },


     isValidUrl(urlString){
	  	var urlPattern = new RegExp('^(https?:\\/\\/)?'+ // validate protocol
	    '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // validate domain name
	    '((\\d{1,3}\\.){3}\\d{1,3}))'+ // validate OR ip (v4) address
	    '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // validate port and path
	    '(\\?[;&a-z\\d%_.~+=-]*)?'+ // validate query string
	    '(\\#[-a-z\\d_]*)?$','i'); // validate fragment locator
	      return !!urlPattern.test(urlString);
	},


        handleExternalMenuSwitch(){
            if(this.form.use_external_menu_page &&  this.form.menu_link == null){   
                toastr.error("Please provide a valid url as menu link to redirect to");
                this.form.use_external_menu_page = false;
                return
            }
           
            if(this.form.use_external_menu_page &&  !this.isValidUrl(this.form.menu_link)){
                toastr.error("Menu link must be a valid url");
                this.form.use_external_menu_page = false;
                return
            }
        }
    },
    mounted() {

        // console.log("vendor:", this.vendor)
        // console.log("this.form.use_external_menu_page:", this.form.use_external_menu_page)
        this.getBanks();
        this.getCuisines();
        this.getDays();
        this.getFeatures();
        this.getSeatingPreferences();
        this.getLocations();
        // google.maps.event.addDomListener(window, "load", this.initialize);
        tippy("#priceSymbolInfo", {
            content:
                "Whatever currency you selected, keep it in mind to input value respectively for it while setting price for your menu items",
                arrow: true,
               distance: 3,
            });
        tippy("#whatsappInfo", {
            content:
                "Please, whatsapp number should be in international format with no  special characters and don't prefix a (+) sign.  For Example: 2348187000344 or  16000333444",
                arrow: true,
                distance: 3,
            });
        tippy("#preferenceInfo", {
            content:
                "Add Spots in your restaurant that Users can choose when making a Reservation",
                arrow: true,
               distance: 3,
        });
    },
};
</script>
