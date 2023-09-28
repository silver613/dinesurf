<template>
    <div
      class="auth-card reservation-auth-card"
      style=" margin-top: 0 !important; border-top: 0 !important;
        padding-top: 10px !important;
      "
    >
    


      <div class="flex justify-center items-center  flex-col h-[180px] w-full"  v-if="this.hideForm">
        
          <div  class=" flex items-center justify-center mb-5">
                   <span class="text-dinesurf-green-400  capitalize  text-center  text-sm  font-bold">You have  reserved dates for this event</span>
         </div>
            <div  class="flex justify-between md:px-0 ">
                      <button class="btn jet-btn mr-3"  @click="this.hideForm = !this.hideForm">
                            Reserve Again
                      </button>

                      <Link :href="route('event-reservations')" class="btn bg-dinesurf-green-400  jet-btn">
                           All Reservation
                      </Link>
          </div>
    </div>



    <div class="auth-card-header flex flex-col items-center"   v-if="!this.hideForm">
       
       <span  class="text-xl font-bold"> Reserve Event</span>
  </div>
    
      <div
        class="auth-card-body text-left"
        v-if="!this.hideForm"
      >
        <form
          @click="checkAuth()"
          @submit.prevent="submit"
          class="flex flex-col w-full"
 
        >
        <div class="flex flex-col w-full" v-if="!user">
          <jet-label>Full Name</jet-label>
          <input
            id="name"
            name="name"
            v-model="form.name"
            type="text"
            class="form-control auth-card-input"
            placeholder="Enter Full Name"
            required
          />
        </div>
        <div class="flex flex-col w-full" v-if="!user">
          <jet-label>Email</jet-label>
          <input
            id="email"
            name="email"
            v-model="form.email"
            type="email"
            class="form-control h-10 auth-card-input"
            placeholder="Enter Email"
            required
          />
        </div>

        <div  v-if="this.payment !='free' && packages.length > 0" class="mt-1">
          <jet-label
           >Select Package: </jet-label
         >
          <select
                   :id="'general_actions'"
                     class="form-control text-xs mt-1  w-full lg:text-sm h-10 "
                   @change="handlePackage()"
                   v-model="selectedPackageIndex"
                >
                  <option
                    v-for="(item, index) in packages"
                    :key="index"
                    :value="index"
                    class="capitalize"
                  >
                     {{ item.name }} 
                  </option>
                </select>

                <div  v-if="selectedPackage != null">

                  <div  class="border shadow-md rouded-lg p-3 mt-2">
                                              <div class="flex justify-between">
                                                              <span class=" capitalize text-md font-bold">{{selectedPackage?.name}}</span>
                                                              
                                                        </div>

                                                        <div class="flex    flex-wrap mt-2">
                                                             <span>{{numberWithCommas(selectedPackage?.price, "NGN")  }}</span>
                                                        </div>
                                                        <div class="flex  flex-wrap mt-2">
                                                             <span class="  whitespace-wrap text-justify">{{ selectedPackage?.description }}</span>
                                                        </div>
                                              </div>
                </div>
        </div>

        <div class="flex flex-col w-full  mt-3">
         
         <jet-label
           >Reserve Seat: </jet-label
         >
         <input
                type="number"
                min="1"
                v-model="form.slot"
                class="auth-card-input h-10  mt-1 "
                placeholder="Enter seat number"
                @change="handleSeatChange"
                
             />
             
             <!-- :disabled="this.event.slot !=='per_person'" -->
       </div>
    <div class="flex flex-col w-full">
            <jet-label :value="'Date'"></jet-label>

            <Datepicker 
                          v-model="form.date"
                           format="dd/MM/yyyy"
                           value-format="dd/MM/yyyy"
                           :min-date="this.currentDate"
                           :max-date="this.end_date"
                          class="mt-1 "
                        />
          </div>


  
          <div class="flex flex-col w-full  mt-4">
         
            <jet-label
              >Time: </jet-label
            >
            <input
                                   type="time"
                                     v-model="form.time"
                                     class="auth-card-input mt-1  h-10"
                                     placeholder="Enter Date"
                                     required
                                     readonly
                              />
          </div>

          <div class="flex flex-col w-full  " v-if="event.payment !='free' && Number(this.form.total_price)">
         
         <jet-label
           >Total Price: </jet-label
         >
         <input  

                 readonly
                  type="number"
                  v-model="form.total_price"
                  class="auth-card-input  mt-1 "
     />
       </div>




       <div  class="  items-center flex flex-col w-full   mb-5"  v-if="event.payment !='free' && Number(this.form.total_price) ">
            
            <div class="flex flex-col  shadow-md  w-full border-2 bg-[#f4f4f4]    ">
              <h5  class="w-full border-b p-3 pb-2 text-center">One-Time {{ event.payment === 'paid' ?  'Pre-Paid' : 'Post-Paid'  }}  Event</h5>
              <div  class="p-5 pb-5 pt-5 flex flex-col">
               
                  <div  class="flex  justify-between mb-2">
                      <span class="text-[15px]  font-bold  capitalize">Amount 
                        <!-- {{(this.event.slot === 'per_couple ' ?  'per couple': this.event.slot === 'per_person' ? 'per person' : null)}}:  --></span>
                      <span>{{ numberWithCommas(this.selectedPackage?.price, "NGN") }}</span>
                   </div>
                   
                   <div  class="flex  justify-between mb-2">
                      <span class="text-[15px]  font-bold">{{ event.payment === 'paid' ? 'Sub ' : null }} Total : <span
                     id="subTotalInfo"
        class="
          ml-1
          mb-3
          pb-1
          pt-1
          bg-gray-200
          rounded-full
          p-2
          h-[0.5rem]
          w-[0.5rem]
          cursor-pointer
          text-xs
        "
      >
        <i class="fa fa-info"></i
      ></span> </span>
                      <span>{{ numberWithCommas(form.total_price, "NGN") }}</span>
                   </div>
                   <div  class="flex  justify-between mb-2"  v-if="event.payment === 'paid'">
                       <span class="text-[15px]  font-bold">Transaction Fee:
                        <span
        id="TransactionFee"
        class="
        ml-1
          mb-3
          pb-1
          pt-1
          bg-gray-200
          rounded-full
          p-2
          h-[0.5rem]
          w-[0.5rem]
          cursor-pointer
          text-xs
        "
      >
        <i class="fa fa-info"></i>
        </span>
                       </span>
                    
                       <span>{{ numberWithCommas(transactionFee(), "NGN") }}</span>
                   </div>

                   <div  class="flex  justify-between mb-2"  v-if="event.payment === 'paid'">
                       <span class="text-[15px]  font-bold">General Total:</span>
                       <span class="border-t text-center">{{ numberWithCommas(generalTotal(), "NGN") }}</span>
                   </div>
              </div>

              <div  class="p-4"  v-if="event.payment === 'post'">
                  <p>This is a post paid event, you will need to pay the total amount at the event venue.</p>
              </div>

           </div>
     </div>
  
       
      
  
       
  
          <button
            type="submit"
            class="btn-md btn-auth btn-red"
            :class="{ 'opacity-25': processing }"
            :disabled="processing"
          >
          {{processing  ? this.responseMessage : 'Confirm Reservation'}} 
            
          </button>
        </form>
     
      </div>
  
      <!-- Login -->
      <card-modal :showing="openLoginModal" @close="openLoginModal = false">
        <login-card
          @close="openLoginModal = false"
          :status="status"
          :canResetPassword="true"
          :intendedUrl="$page.props.page"
        ></login-card>
      </card-modal>
    </div>
  

   




    


    <!-- reservation success modal -->

    <jet-modal :show="this.successModal" @close="this.successModal = !this.successModal">
    <!-- <template #content> -->

    <div class="flex flex-col items-center" v-if="cancellation">
    

      <button
        class="
          bg-dinesurf-red-100
          text-dinesurf-red-500
          items-center
          p-4
          text-center
        "
        @click="succesModal = false"
      >
        Close
      </button>
    </div>
    <div class="flex flex-col items-center" v-else>
      <a
        v-if="!vendor.profile_photo_path"
        :href="route('restaurants.index', [{ id: vendor.id }])"
      >
        <img
          class="fill-current h-[8rem] w-auto object-contain mb-1"
          :src="vendor.profile_photo_url"
        />
      </a>
      <a v-else :href="route('index')">
        <img
          class="fill-current block bg-red h-10 w-auto object-contain mb-5 mt-5"
          src="/images/company_logo.png"
        />
      </a>

      <div class="flex flex-row items-center content-center">
        <img src="/images/success-icon.svg" class="h-6 mr-3" />
        <h4 class="text-center my-3 text-2xl">Reservation successful</h4>
      </div>

      <div class="bg-gray-100 w-80 md:w-[30rem] px-8 py-4">
        <h4 class="text-center mb-5">Reservation details</h4>
        <div class="flex md:flex-row flex-col justify-between">
          <div class="flex flex-col mr-3">
            <span v-if="user">
              <span class="text-[#94a3b8] mr-2">Name:</span>
              {{ `${user?.first_name}, ${user?.last_name}` }}
            </span>

            <span v-if="!user">
              <span class="text-[#94a3b8] mr-2">Name:</span>
              {{ form.name }}
            </span>

            <!-- v-if="!user" -->
            <span>
              <span class="text-[#94a3b8] mr-2 mt-3">Reserved Date:</span>
              {{ form.date }}
            </span>

          </div>
          <div class="flex flex-col mr-3">
            <span>
              <span class="text-[#94a3b8] mr-2">Seats:</span>
              {{ form.slot}} {{  form.slot > 1  ? 'Guests' : 'Guest'}}</span
            >
            <span>
              <span class="text-[#94a3b8] mr-2 mt-3">Payment:</span>
              {{   this.event.payment !='free' ? numberWithCommas(form.total_price, "NGN")  :' Free' }}
            </span>

          </div>
        </div>
      </div>
    </div>

    <div class="flex justify-around p-5 md:px-15 flex-wrap">
      <Link
        target="_blank"
        :href="route('restaurants.index', [{ id: this.vendor.id }]) +
            '?tab=events'"
        class="w-64 m-2 btn btn-md bg-dinesurf-green-500 text-white text-center"
      >
      <i   class="fa fa-arrow-back"></i>
       All Events
      </Link>
      <a
        target="_blank"
        :href="'https://calendar.google.com/calendar/render?'+ googlecalendar "
        class="w-64 m-2 btn btn-md btn-white-gray text-center"
      >
        Add to calendar
      </a>
      <button
        class="w-64 m-2 btn btn-md h-8 btn-white-gray"
        @click="closeReload()"
      >
      Reserve Again
      </button>
      <Link
        class="w-64  text-center m-2 btn btn-md bg-dinesurf-red-300 text-[#656565]"
       :href="route('event-reservations')"
       v-if="user"
      >
        All Reservations
    </Link>
    </div>

    <!-- </template> -->
  </jet-modal>

  
  </template>
  
  <script>
  import moment from "moment";
  import JetAuthenticationCard from "@/Jetstream/AuthenticationCard.vue";
  import LoginCard from "@/components/LoginCard.vue";
  import JetAuthenticationCardLogo from "@/Jetstream/AuthenticationCardLogo.vue";
  import JetButton from "@/Jetstream/Button.vue";
  import JetInput from "@/Jetstream/Input.vue";
  import JetCheckbox from "@/Jetstream/Checkbox.vue";
  import JetLabel from "@/Jetstream/Label.vue";
  import JetValidationErrors from "@/Jetstream/ValidationErrors.vue";
  import JetInputError from "@/Jetstream/InputError.vue";
  import Datepicker from '@vuepic/vue-datepicker';
  import '@vuepic/vue-datepicker/dist/main.css';
  import tippy from "tippy.js";
  import "tippy.js/dist/tippy.css"; // optional for styling

  export default {
    components: {
      JetAuthenticationCard,
      JetAuthenticationCardLogo,
      JetButton,
      JetInput,
      JetCheckbox,
      JetLabel,
      JetValidationErrors,
      LoginCard,
      JetInputError,
      Datepicker
    },
  
    props: {
      vendor: {
        type: Object,
        required: true,
      },
      event: {
        type: Object,
        required: true,
      },
      status: String,
      paystackkey : String,
      reference: String
    },
    data() {
      return {
        currentDate: this.event.duration != 'single' ? moment().add(1, 'days').format("YYYY-MM-DD") : moment(this.event.start_date),
        successModal:false,
        vendor: this.vendor,
         end_date: moment(this.event.end_date),
         event: this.event,
         processing: false,
         openLoginModal:false,
         responseMessage: null,
         user:this.$page.props.auth.user,
         hideForm: this.$page.props.auth.user && this.event.reservations?.length > 0 ,
         packages: this.event.payment !='free' ? JSON.parse(this.event.packages) || [] : [],
         price:this.event.price,
         form: {
            time : this.event.start_time,
            date: this.event.duration != 'single' ? moment().add(1, 'days').format("YYYY-MM-DD") : moment(this.event.start_date),
            slot: this.event.slot === 'per_person' ? 1 :  this.event.slot === 'per_couple' ? 2 : 1,
            total_price:   this.event.price,
            payment_status: false,
            payment_ref: null,
            transaction_ref: null,
            email:this.$page.props?.auth?.user?.email,
            name:this.$page.props?.auth?.user?.name
          },
          txnRef:this.reference,
          currency: "NGN",
          selectedPackageIndex: null,
          selectedPackage:null
      };
    },
    computed: {

      user() {
      return this.$page.props.user;
      },
      outLookcalendar() {
        j;
        const params = new URLSearchParams({
          body: "Learn all about the rules of the Motorway and how to access the fast lane.\n\nhttps://en.wikipedia.org/wiki/Gridlock_(Doctor_Who)",
          enddt: "2022-01-12T20:00:00+00:00",
          location: "New Earth",
          path: "/calendar/action/compose",
          rru: "addevent",
          startdt: "2022-01-12T18:00:00+00:00",
          subject: "Welcome to the Motorway",
        });
        return params.toString();
      },
      office365calendar() {
        const params = new URLSearchParams({
          body: "Learn all about the rules of the Motorway and how to access the fast lane.\n\nhttps://en.wikipedia.org/wiki/Gridlock_(Doctor_Who)",
          enddt: "2022-01-12T20:00:00+00:00",
          location: "New Earth",
          path: "/calendar/action/compose",
          rru: "addevent",
          startdt: "2022-01-12T18:00:00+00:00",
          subject: "Welcome to the Motorway",
        });
        return params.toString();
      },
  
      googlecalendar() {
        const params = new URLSearchParams({
          action: "TEMPLATE",
          dates:
            moment(this.event.start_date)
              .utc()
              .format("YYYYMMDD[T]HHmmss[Z]") +
            "/" +
            moment(this.event.end_date)
              .utc()
              .format("YYYYMMDD[T]HHmmss[Z]"),
          details:
            // this.capitalizeWords(this.reservationData?.vendor.company_name) +
            this.capitalizeWords(this?.event?.name)+ " by " + this.capitalizeWords(this?.vendor?.company_name) +
            " on Dinesurf\n\n" +
            route("getEvent", { id: this.event?.id }),
          location: this.vendor?.company_address,
          text:
          this.capitalizeWords(this?.event?.name)+ " by " + this.capitalizeWords(this?.vendor?.company_name) +
            " on Dinesurf\n\n",
        });
        return params.toString();
      },
      formatedTime() {
        if (this.form.time) {
          return moment(this.form.time, "HH:mm:ss").format("hh:mm A");
        }
        return;
      },
      formatedDate() {
        if (this.form.date) {
          return moment(this.form.date).format("dddd, MMM Do YYYY");
        }
        return;
      },
      enabledDays() {
        var days = [];
  
        if (this.vendor) {
          this.vendor.days?.forEach((el) => {
            var dayIndex = el.id == 7 ? 0 : el.id;
            if (!days.includes(dayIndex)) {
              days.push(dayIndex);
            }
          });
        }
  
        return days.length > 0 ? days : null;
      },
    },
  
    methods: {
      handlePackage(){
          const getPackage = this.packages.find((item, index) => this.selectedPackageIndex === index );
          this.form.total_price = getPackage.price;
          this.price = getPackage.price;
          this.selectedPackage = getPackage;
          this.handleSeatChange()
      },

     generalTotal(){
         return  Number(this.transactionFee()) + Number(this.form?.total_price)
      },


      closeReload(){
        this.succesModal = false;
        window.location.reload();
      },

      

     paystackVerify() {
        this.responseMessage = "Verifying payment..";
      fetch("/paystack-verify-event/" + this.txnRef)
        .then((result) => {
          return result.json();
        })
        .then((result) => {
          if (result.success && result.data?.status) { 
            this.form.payment_status = result.data?.data?.status;
            this.handleReservation()
          } else {
            toastr.error(result.message);
            this.processing = false;
          }
        })
        .catch((err) => {
          console.log("er wahtr:", err);
          toastr.error("An Error Occured!");
        });
    },

        payWithPaystack() {

            this.responseMessage = "Initiating payment..";
   
            let vm = this;
            var config = {
                key: this.paystackkey, // Replace with your public key
                email:  this.user ?  this.$page.props?.auth?.user?.email : this.form.email,
                amount: this.generalTotal() == 0 ? 100 : this.generalTotal() * 100,
                firstname:  this.user ?  this.$page.props?.auth?.user?.first_name : this.form.name,
                ref: this.txnRef,
                currency: this.currency, 
                subaccount: this.vendor.paystack_code,
                transaction_charge: this.dinesurfTransactionCharge(),
                
                onClose: function () {
                vm.processing = false;
                },
                callback: function (response) {
                    vm.form.payment_ref = response.reference;
                    vm.form.transaction_ref = response.transaction;
                   return vm.paystackVerify();
                },
      };

      var handler = PaystackPop.setup(config);
      handler.openIframe();
    },



        submit() {
         
         this.processing = true;
         this.responseMessage = "Loading..";

          if(this.event.payment === 'paid'){
           this.payWithPaystack()
          }else{
            this.handleReservation()
          }
         

         },


         handleReservation(){

          let vm = this;
          var formData = new FormData();
         formData.append("event_date", this.form.date);
         formData.append("event_time", this.form.time);
         formData.append("total_price", this.form.total_price);
         formData.append("vendor_id", this.vendor.id);
         formData.append("event_id", this.event.id);
         formData.append("reserve_seat", this.form.slot);
         formData.append("isPaid",  this.event.payment === 'paid' ? 1 : 0);
         formData.append("payment_ref", this.form.payment_ref);
         formData.append("payment_status", this.form.payment_status);
        formData.append("transaction_ref", this.form.transaction_ref);
        formData.append("email", this.form.email);
        formData.append("name", this.form.name);
        formData.append("package",  JSON.stringify(this.selectedPackage));

        this.responseMessage = "Making Reservation..";
         axios
       .post(route("create-event-reservation"), formData,
       {
             headers: {
                 'Content-Type': 'multipart/form-data'
             }
        }
       )
             .then(({data}) => {

             if (data.success) {
                 toastr.success(data.message);
                 vm.successModal = true;
               
             } else {
                if (data.message == "CSRF token mismatch.") {
                     window.location.reload();
                 }else{
                  toastr.error(data.message);
                 }
                 
             }
             })
             .catch((err) => {
            console.log("error:L:", err)
            toastr.error(err.message);
             }).finally(() => this.processing = false);

         },

        transactionFee(){
     
            const perc = 0.03;
            const percentageTotal =  perc * this.form?.total_price + 100;
            const transactionFee =  percentageTotal > 2000 ? 2000 : percentageTotal;
            return  transactionFee;        
        },

        dinesurfTransactionCharge(){
          const processFee = 0.02 *  this.form?.total_price;
          const totalprocessFee = processFee > 2000 ? 2000 : processFee;

          return totalprocessFee;
        },

        handleSeatChange(){
            const reserve_seat  = this.form.slot;
            this.form.total_price = reserve_seat *  this.price;
        },
      capitalizeWords(string) {
        return string?.replace(/(?:^|\s)\S/g, function (a) {
          return a.toUpperCase();
        });
      },
     
      checkAuth() {
        // if (!this.$page.props.user) {
        //   this.logCurrentUrl(document.location.toString());
        //   this.openLoginModal = true;
        //   return;
        // }
        // return;
      },
  
   
    },
    mounted() {
      
   tippy("#subTotalInfo", {
      content:
        "The event amount multiplies by the number of seats",
    });

      tippy("#TransactionFee", {
        content:
          "3% + ₦100 charges fee",
      });


       
    },
  };
  </script>
  