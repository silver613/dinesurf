<template>
  <!-- <app-layout :page="$page.props.page"> -->

    <div  class="bg-white md:pl-20  pl-5 mb-5  md:p-5  py-4 flex justify-between"  >
        <Link  :href="route('restaurants.index', [{ id: this.vendor.id }]) +
            '?tab=events'" class="text-dinesurf-green-600">
           <i  class="fa fa-angle-left  mr-2"></i>
             {{this.vendor.company_name}}
        </Link>
    </div>
    <div class="md:px-10  px-4    md:pl-20">
        <div class="flex  md:flex-row   flex-col  justify-between">
            <div  class="pt-2">
                     <span  class="text-3xl    pb-5">{{event.name}}</span>
                     <div class="flex  md:mt-5  mt-5">
                          <div class=" w-[90px]   h-[70px]  ">
                               <img
                                    :src=" this.vendor.banner_url "
                                    class="imageN"
                                    draggable="true"
                                />
                          </div>
                          <div class="pl-3">
                            <!-- vendor name -->
                               <span class="pb-2">{{vendor.company_name}}</span>   
                               <!-- vendor review -->
                               <div>
                                   <ratings class="text-sm" :stars="vendor.average_rating"></ratings>
                                   <span class="pl-2">{{vendor.number_of_reviews}} reviews</span>
                               </div>
                               
                               <!-- vendor cruisine -->
                               <div  class="flex ">
                                        <div v-for="(item, index) in vendor.cuisines" :key="index" class="pr-2">
                                            <span class="text-xs mt-0">• {{ item.name }}</span>
                                        </div>
                               </div>
                              
                          </div>
                     </div>

                     <div class="mt-4  flex">
                              <img src="/images/Icon material-location-on.svg" />
                              <span class="text-sm  ml-2">{{vendor.company_address}}</span>
                    </div>

                     <div class="mt-2  flex">
                       
                        <i  class="far fa-calendar  text-center mt-1"></i>
                              <span class="text-sm  ml-2">

                                {{this.getFormatted(event.start_date)}}  
                                {{ event.duration != 'single' ? " - "+ this.getFormatted(event.end_date) : null }}
                             
                              </span>
                    </div>

                    <!-- <div class="mt-2  flex" v-if="event.duration === 'repeat'">
                        <svg width="15" height="15" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 0C4.486 0 0 4.486 0 10C0 15.514 4.486 20 10 20C15.514 20 20 15.514 20 10C20 4.486 15.514 0 10 0ZM10 18C5.589 18 2 14.411 2 10C2 5.589 5.589 2 10 2C14.411 2 18 5.589 18 10C18 14.411 14.411 18 10 18Z" fill="black" fill-opacity="0.66"/>
                            <path d="M11 5H9V11H15V9H11V5Z" fill="black" fill-opacity="0.66"/>
                        </svg>
                              <span class="text-sm  ml-2">

                                This Event happen every day till 

                                 <div v-for="(item, index) in  JSON.parse(event?.repeat)" :key="index" class="pr-2">
                                            <span class="text-xs mt-0  capitalize">• {{ item }}</span>
                                     </div>

                            
                              </span>
                    </div> -->

                    <div class="mt-2  flex">
                        <svg width="15" height="15" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 0C4.486 0 0 4.486 0 10C0 15.514 4.486 20 10 20C15.514 20 20 15.514 20 10C20 4.486 15.514 0 10 0ZM10 18C5.589 18 2 14.411 2 10C2 5.589 5.589 2 10 2C14.411 2 18 5.589 18 10C18 14.411 14.411 18 10 18Z" fill="black" fill-opacity="0.66"/>
                            <path d="M11 5H9V11H15V9H11V5Z" fill="black" fill-opacity="0.66"/>
                        </svg>
                              <span class="text-sm  ml-2  capitalize">

                                {{new Date("1970-01-01T" + event.start_time).toLocaleString('en-US', { hour: 'numeric',minute: 'numeric', hour12: true })}}
                               {{ '- ' + new Date("1970-01-01T" + event.end_time).toLocaleString('en-US', { hour: 'numeric',minute: 'numeric', hour12: true })}}
                               {{ event.duration === 'repeat' ?  JSON.parse(this.event.repeat)?.length > 6 ? ', Every Day(Mon - Sun)' :', Every '+ JSON.parse(this.event.repeat)?.join(', ') + ' Within The date' : null
                              }}

                          
                              </span>
                    </div>

                    <div class="mt-1  flex  items-center">
                                <div class="w-5 mr-2">
                                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" focusable="false" class="__web-inspector-hide-shortcut__"><g fill="none" fill-rule="evenodd"><path d="M20,15 L20,9 L18.5,9 C18.2238576,9 18,8.77614237 18,8.5 L18,7 L6,7 L6,8.5 C6,8.77614237 5.77614237,9 5.5,9 L4,9 L4,15 L5.5,15 C5.77614237,15 6,15.2238576 6,15.5 L6,17 L18,17 L18,15.5 C18,15.2238576 18.2238576,15 18.5,15 L20,15 Z M4,5 L20,5 C21.1045695,5 22,5.8954305 22,7 L22,17 C22,18.1045695 21.1045695,19 20,19 L4,19 C2.8954305,19 2,18.1045695 2,17 L2,7 C2,5.8954305 2.8954305,5 4,5 Z M12,10 C13.1045695,10 14,10.8954305 14,12 C14,13.1045695 13.1045695,14 12,14 C10.8954305,14 10,13.1045695 10,12 C10,10.8954305 10.8954305,10 12,10 Z" fill="#2D333F"></path></g></svg>
                                </div>
                              <span class="text-sm  capitalize"   v-if="event.payment != 'free'">{{numberWithCommas(event.price, "NGN")}}  {{ event.slot === 'per_couple' ?  'per couple': event.slot === 'per_person' ? 'per guest' : null}}</span>
                              <span   v-else>Free</span>
                     </div>
            </div>

            <!-- event image  -->
            <div  class="mt-3   w-[347px]   h-[220px] bg-gray-400 ">
                <img :src="event.image_url "
                     class="imageN   "  draggable="true" />
            </div>
         </div>

<!-- 
         <div  class=" mt-5  flex  justify-center items-center">
                  <div class="">
                      <button class="bg-dinesurf-green-400 p-3 md:w-32 shadow-2xl font-bold  w-full rounded-full  text-dinesurf-red-400  cursor-pointer">Reserve Event</button>
                  </div>
         </div>
      -->

         <!-- description -->
         <div class=" my-2 mt-10">

            <span class="text-xl    pb-5">Read More About Event:</span>
               <p class="text-avenir   text-justify  mt-3" v-html="event.description"></p>
         </div>



       <div class="my-10"  v-if="!this.hideForm">

            <div class="my-3  mt-10">  
                <span class="text-xl     pb-5">Reserve Event:</span>
                <!-- <p  class="text-sm">Please select the appropriate date you would like to attend the event, the initial start date is 
                    <span  class="text-dinesurf-green-400 ">{{new Date(event.start_date.toLocateDate())}}</span> 
                </p> -->
            </div>

               <div  class="flex  md:flex-row  flex-col  justify-center  mt-5 md:mt-10 ">
                    <div class="md:w-[20%]">
                        <jet-label> Date  : </jet-label>
                        <Datepicker 
                          v-model="form.date"
                           format="dd/MM/yyyy"
                           value-format="dd/MM/yyyy"
                           :min-date="this.event.start_date"
                           :max-date="this.end_date"
                          class="mt-1 "
                        />
                    </div>
                    <div class="md:pl-5    mt-5  md:mt-0  md:w-[20%]">
                        <jet-label> Time : </jet-label>
                            <input
                                    type="time"
                                     v-model="form.time"
                                     class="auth-card-input mt-1  h-10"
                                     placeholder="Enter Date"
                                     required
                                     readonly
                              />
                              
                    </div>

                    <div class="md:pl-5  md:w-[20%]">
                                   <jet-label>Reserve Seat: </jet-label>
                                   <div  class="flex ">
                                       <div class="md:w-[70%]    w-[100%] ">


                                        <!-- <select
                                       class="
                                       auth-card-input
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
                                       h-10
                                       "
                                       v-model="form.slot"
                                       name="slot"
                                       id="slot"
                                       :disabled="form.slot !== 'per_couple'"

                                    @change="handleChangeSlot"
                                   >
                                       <option selected :value="null">-- Select duration --</option>
                                       <option :selected="form.slot !== 'per_couple'" :disabled="form.slot !== 'per_couple'" value="1">1</option>
                                       <option :selected="form.slot === 'per_couple'" value="2">2</option>
                                       <option value="4">4</option>
                                       <option value="6">6</option>
                                       <option value="8">8</option>
                                       <option value="10">10</option>
                                       <option value="12">12</option>
                                       <option value="14">14</option>
                                   </select> -->

                                           <input
                                               type="number"
                                               min="1"
                                               v-model="form.slot"
                                               class="auth-card-input   mt-1  form-input border-l-0 h-10 "
                                               placeholder="Enter seat number"
                                               :disabled="this.event.slot !=='per_person'"
                                               @change="handleSeatChange"
                                               
                                            />

                               </div>

                                       <div  class="md:w-[30%] ">
                                         <button class="bg-gray-300 h-10 px-5  mt-1  rounded-r  -ml-3">
                                            {{form.slot > 1  ? 'Guests' : 'Guest' }}  
                                         </button>
                                       </div>
                                   </div>
                       </div>

                       <div class="md:pl-5   md:w-[20%]" v-if="event.payment != 'free'">
                        <jet-label> Total Price : </jet-label>
                             <input  

                             readonly
                                               type="number"
                                               v-model="form.total_price"
                                               class="auth-card-input   mt-1  form-input border-l-0 h-10 "
                                  />
                              
                      </div>
               </div>








               <div  class="  items-center flex flex-col w-full   mb-10"  v-if="event.payment !='free'">
            
                      <div class="flex flex-col  shadow-md  border-2 bg-dinesurf-green-400    ">
                        <h3  class="w-full border-b p-3 pb-2 text-center">One-Time {{ event.payment === 'paid' ?  'Pre-Paid' : 'Post-Paid'  }}  Event</h3>
                        <div  class="p-10 pt-5 flex flex-col">
                         
                            <div  class="flex  justify-between mb-2">
                                <span class="text-[15px]  font-bold  capitalize">Amount 
                                  {{(this.event.slot === 'per_couple ' ?  'per couple': this.event.slot === 'per_person' ? 'per person' : null)}}: </span>
                                <span>{{ numberWithCommas(event.price, "NGN") }}</span>
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
                    p-3
                    h-[2rem]
                    w-[2rem]
                    cursor-pointer
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
                    p-3
                    h-[1rem]
                    w-[1rem]
                    cursor-pointer
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
                            <p>This is a post paid event, you will need to pay the total amount at the event venue</p>
                        </div>

                     </div>
               </div>





               <div class="flex w-full justify-center">
                   <button 
                   :class="{ 'opacity-25': processing }"
                   :disabled="processing"
                   @click="checkAuth" class="btn bg-dinesurf-red-400 md:w-[30%] p-3 rounded text-white cursor-pointer">
                        {{processing  ? this.responseMessage : 'Make a reservation'}} 
                   </button>
               </div>

           </div>

    </div>




    <!-- reservation card -->
     

    <div class="flex justify-center items-center"  v-if="this.hideForm">
         <div  class="md:w-[60%]     shadow-md border  border-['#f4f4f4']  p-10">
          <div  class="w-full flex items-center justify-center mb-5">
                   <span class="text-dinesurf-green-400  capitalize  text-center  text-2xl">You have  reserved dates for this event</span>
         </div>
            <div  class="flex justify-between md:px-40 ">
                      <button class="btn jet-btn"  @click="this.hideForm = !this.hideForm">
                            Reserve Again
                      </button>

                      <Link :href="route('event-reservations')" class="btn bg-dinesurf-green-400  jet-btn">
                           All Reservation
                      </Link>
              </div>
         </div>
    </div>


    
     
    <div class="z-[2000] mt-2 leading-normal text-xs text-gray-500 space-y-4 pt-2 bottom-0    border-2 w-full bg-gray-100    ">
            <p class="text-center">
              Powered by
              <a class="link-default" href="https://dinesurf.com">Dinesurf</a>
              · v3.0.0 (Dinner App)
            </p>
            <p class="text-center pb-3">© {{   new Date().getFullYear() }} Dinesurf Ltd.</p>
          </div>



           <!-- Login -->
    <card-modal :showing="openLoginModal" @close="openLoginModal = false">
      <login-card
        @close="openLoginModal = false"
        :status="''"
        :canResetPassword="true"
        :intendedUrl="$page.props.page"
      ></login-card>
    </card-modal>







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
            <span>
              <span class="text-[#94a3b8] mr-2">Name:</span>
              {{ `${user?.first_name}, ${user?.last_name}` }}</span
            >
            <span>
              <span class="text-[#94a3b8] mr-2 mt-3">Reserved Date:</span>
              {{ new Date().toLocaleDateString() }}
            </span>

          </div>
          <div class="flex flex-col mr-3">
            <span>
              <span class="text-[#94a3b8] mr-2">Seats:</span>
              {{ form.slot}} {{  form.slot > 1  ? 'Guests' : 'Guest'}}</span
            >
            <span>
              <span class="text-[#94a3b8] mr-2 mt-3">Payment:</span>
              {{   this.event.payment ==='paid' ? form.total_price :' Free' }}
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
        class="w-64 m-2 btn btn-md bg-dinesurf-red-300 text-[#656565]"
       :href="route('event-reservations')"
      >
        All Reservations
    </Link>
    </div>

    <!-- </template> -->
  </jet-modal>

    
  <!-- </app-layout>    -->
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import moment from 'moment';
import LoginCard from "@/components/LoginCard.vue";
import tippy from "tippy.js";
import "tippy.js/dist/tippy.css"; // optional for styling


export default {
    props:['event', 'vendor', 'paystackkey', 'reference'],
    components:{
        AppLayout,
        Datepicker,
        LoginCard
    },
    data(){
        return{
        
        successModal:false,
        vendor: this.vendor,
         end_date: moment(this.event.end_date).add(1, 'days'),
         event: this.event,
         processing: false,
         openLoginModal:false,
         responseMessage: null,
         user:this.$page.props.auth.user,
         hideForm: this.$page.props.auth.user && this.event.reservations?.length > 0 ,
  
         form: {
            time : this.event.start_time,
            date: this.event.start_date,
            slot: this.event.slot === 'per_person' ? 1 :  this.event.slot === 'per_couple' ? 2 : 1,
            total_price: this.event.price,
            payment_status: false,
            payment_ref: null,
            transaction_ref: null
          },
          txnRef:this.reference,
          currency: "NGN",
        }
    },

    computed:{

      googlecalendar() {
          
         const params = new URLSearchParams({
              action: "TEMPLATE",
              dates: moment(this.event.iso_start).utc().format("YYYYMMDD[T]HHmmss[Z]") + "/" + moment(this.event.iso_end).utc().format("YYYYMMDD[T]HHmmss[Z]"),
              details: this.capitalizeWords(this.vendor.company_name) + "Event  Reservation on Dinesurf\n\n" + route('getEvent', { id: this.event.id }),   
              location: this.vendor.company_address,
              text: this.capitalizeWords(this.vendor.company_name) + "Event Reservation on Dinesurf\n\n",
          });
          return params.toString();
      },
      
    },

    methods:{
      
    capitalizeWords(string) {
      return string.replace(/(?:^|\s)\S/g, function (a) {
        return a.toUpperCase();
      });
     },
      generalTotal(){
         return  Number(this.transactionFee()) + Number(this.form?.total_price)
      },

      transactionFee(){
     
        const perc = 0.03;
        const percentageTotal =  perc * this.form?.total_price + 100;
        const transactionFee =  percentageTotal > 2000 ? 2000 : percentageTotal;
        return  transactionFee;        
      },


      closeReload(){
        this.succesModal = false;
        window.location.reload();
      },

        handleSeatChange(){
            const reserve_seat  = this.form.slot;
            this.form.total_price = reserve_seat * this.event.price;
        },

     paystackVerify() {
        this.responseMessage = "Verifying payment..";
      fetch("/paystack-verify-event/" + this.txnRef)
        .then((result) => {
          return result.json();
        })
        .then((result) => {
          if (result.success && result.data?.status) {  
            console.log("result:",result)
            this.form.payment_status = result.data?.data?.status;
            this.handleReservation()
          } else {
            toastr.error(result.message);
            this.processing = false;
          }
        })
        .catch((err) => {
          // console.log(err);
          this.$toast.error("An Error Occured!");
        });
    },

        payWithPaystack() {

            this.responseMessage = "Initiating payment..";
   
            let vm = this;
            var config = {
                key: this.paystackkey, // Replace with your public key
                email: this.$page.props.auth.user.email,
                amount: this.generalTotal() == 0 ? 100 : this.generalTotal() * 100,
                firstname: this.$page.props.auth.user.first_name,
                ref: this.txnRef,
                currency: this.currency, 
                
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

             getDifferenceInDays() {
                const date1 = new Date(this.event.start_date);
                const date2 = new Date(this.event.end_date);
                const diffInMs = Math.abs(date2 - date1);
                return diffInMs / (1000 * 60 * 60 * 24);
                },
                getFormatted(date){
                    var m  = moment(date, "YYYY-MM-DD");  // explicit input format
                    return m.format("ddd MMM DD YYYY");
                },

                handleChangeSlot(e){
                    console.log("event target:", e.target.value)
                },

                checkAuth() {
                    if (!this.$page.props.user) {

                        console.log("document.location.toString():", document.location.toString())
                        this.logCurrentUrl(document.location.toString());
                        this.openLoginModal = true;
                        return;
                    }else{
                        this.submit();
                    }
                  }
             },
    mounted(){
     

        if (this.vendor) {
           document.title = "Attend " +  this.vendor.company_name+"'s Event"+ " - " + this.event?.name;
        }

   tippy("#subTotalInfo", {
      content:
        "The event amount multiplies by the number of seats",
    });

      tippy("#TransactionFee", {
        content:
          "3% + ₦100 charges fee",
      });

      console.log("this.event.slot:", this.event.slot)
    }
    
}
</script>

