<template>
    <div  class="bg-white md:pl-20  pl-5 mb-5  md:p-5  py-4 flex justify-between"  >
        <!-- <Link  :href="route('restaurants.index', [{ id: this.vendor.id }]) +
            '?tab=events'" class="text-dinesurf-green-600">
           <i  class="fa fa-angle-left  mr-2"></i>
             {{this.vendor.company_name}}
        </Link> -->

          <Link  :href="route('restaurants.index', {id: this.vendor.id})" class="text-dinesurf-green-600">
           <i  class="fa fa-angle-left  mr-2"></i>
             {{ this.vendor.company_name }}
        </Link>
    </div>
  

    <div class="md:px-20   px-5">

       <div  >
            <div
                class="restaurant-card-img restaurant-card-img-event   rounded-md"
                :style="'background-image: url(' + this.vendor.banner_url + ')'" >
              <event-banner-info :vendor="vendor"  :event="event"  ></event-banner-info>
            </div>
             <div class="mt-3 ">
                  <!-- <span  class="text-xl   font-bold  pb-5">{{event.name}}</span> -->
                  <p class="text-avenir  w-full  text-justify  mt-3" v-html="event.description"></p>
                  <div class="mt-3">
                        <event-share-button :id="event.id" class="my-1"></event-share-button>
                   </div>
             </div>
       </div>

        <div
          id="vendorTabs"
          class="flex justify-flex-start  event-tab  text-center"
        >
          <div
            class="w-24 text-xs   font-bold"
            :class="tab == 'info' ? 'active' : ''"
            @click="toggleTab('info')"
          >
            Overview
          </div>


          <div
            class="w-24 text-xs font-bold"
            :class="tab == 'gallery' ? 'active' : ''"
            @click="toggleTab('gallery')"
          >
            Photos
          </div>

        </div>


   

     <div class="flex  flex-col  md:flex-row  ">
        
        <div  v-if="!tab || tab == 'info'"  class="md:mr-20  md:border-r  md:w-[40%]">
             <event-info  :event="event"  :vendor="vendor"  />

        </div>

        <div  v-if="tab == 'gallery'"  class="md:mr-20 mt-3  md:border-r  md:w-[40%]">
             <images  :images="event.images"  :vendor="vendor"  />

        </div>
       

        <div  class="md:mt-0   mt-10">
            <event-make-reservation  :vendor="this.vendor" :event="event"  :paystackkey="this.paystackkey"   :reference="this.reference" />
        </div>
       
     </div>
       
   
 </div>

   
        
 <div class="z-[2000] mt-4 leading-normal text-xs text-gray-500 space-y-4 pt-2 bottom-0    border-2 w-full bg-gray-100    ">
            <p class="text-center">
              Powered by
              <a class="link-default" href="https://dinesurf.com">Dinesurf</a>
              · v3.0.0 (Dinner App)
            </p>
            <p class="text-center pb-3">© {{   new Date().getFullYear() }} Dinesurf Ltd.</p>
          </div>

</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import moment from 'moment';
import LoginCard from "@/components/LoginCard.vue";
import tippy from "tippy.js";
import "tippy.js/dist/tippy.css"; // optional for styling
import EventBannerInfo from "@/components/EventBannerInfo.vue";
import EventInfo from "@/components/EventInfo.vue";
import EventMakeReservation from "@/components/EventMakeReservation.vue";
import Images from "@/components/EventGallery.vue";
import EventShareButton from "@/components/EventShareButton.vue";
export default {
    props:['event', 'vendor', 'paystackkey', 'reference'],
    components:{
        AppLayout,
        Datepicker,
        LoginCard,
        EventBannerInfo,
        EventInfo,
        EventMakeReservation,
        Images,
        EventShareButton
    },
    data(){
        return{
          tab:  "info",
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

      load(){
            const urlSearchParams = new URLSearchParams(window.location.search);
            this.params = Object.fromEntries(urlSearchParams.entries());
            this.tab = this.params.tab ?  this.params.tab : "info";

       },

      back() {
      window.history.back();
    },

      toggleTab(tab) {
      //   var url = window.location.pathname + "?tab=" + tab;
      this.$inertia.get(
        route('getEvent', { id: this.event.id }),
        { tab: tab },
        {
          preserveState: true,
          preserveScroll: true,
          onFinish: (visit) => {
            this.tab = tab;
          },
        }
      );
    },
      
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

      console.log("event.images:", this.event.images);

       this.load();
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

    }
    
}
</script>

