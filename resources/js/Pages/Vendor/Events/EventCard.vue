<template >
  <div
   
    class="
     
    single-restaurant-card
      bg-white
      md:m-3
      mt-3
      mr-3
      rounded-lg
      relative
      shadow-md border
      w-64
    "
    
  >
    <!-- <div class="flex flex-col  rounded-lg  w-64 "  > -->
      <!-- <div
        class="h-full bg-gray-300 "
        style="height: 130px; background-color: gray"
      >
        <img :src="this.eventData.image_url" class="imageN" draggable="true" />
      </div> -->

      
      <Link 
      
      :href="
      this.type === 'diner'
        ? route('getEvent', { id: eventData.id })
        : route('vendors.edit-event-page') + '/' + eventData.id
    ">

      <div
          class="restaurant-card-img bg-grey  w-64 rounded-t-md"
          :style="'background-image: url(' + image + ')'"
        ></div>


      <div class="p-3">
        <h4
          class="
            text-medium text-blogtext-dark
            font-bold
            clamp-text
           
            font-sans
            capitalize
          "
        >
          {{
           eventData.name.length > 25
              ? eventData.name.substring(0, 25) + "..."
              :eventData.name
          }}
        </h4>

          <div class="flex mt-1  mb-2">
            <svg
              width="15"
              height="15"
              viewBox="0 0 20 20"
              fill="none"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                d="M10 0C4.486 0 0 4.486 0 10C0 15.514 4.486 20 10 20C15.514 20 20 15.514 20 10C20 4.486 15.514 0 10 0ZM10 18C5.589 18 2 14.411 2 10C2 5.589 5.589 2 10 2C14.411 2 18 5.589 18 10C18 14.411 14.411 18 10 18Z"
                fill="black"
                fill-opacity="0.66"
              />
              <path
                d="M11 5H9V11H15V9H11V5Z"
                fill="black"
                fill-opacity="0.66"
              />
            </svg>
            <span class="ml-1 text-xs text-gray-600">
              {{ this.getFormatted(eventData.start_date) }}
              {{
                eventData.duration != "single"
                  ? " - " + this.getFormatted(eventData.end_date)
                  : null
              }}
            </span>
          </div>

          <div class="flex justify-between mt-1">
            <span class="text-xs text-gray-600">
              {{
                new Date("1970-01-01T" + eventData.start_time).toLocaleString(
                  "en-US",
                  { hour: "numeric", minute: "numeric", hour12: true }
                )
              }}
              {{
                "- " +
                new Date("1970-01-01T" + eventData.end_time).toLocaleString(
                  "en-US",
                  { hour: "numeric", minute: "numeric", hour12: true }
                )
              }}
            </span>
            <div class="flex">
              <div class="mt-[2px] mr-1">
                <svg
                  width="11"
                  height="11"
                  viewBox="0 0 11 11"
                  fill="none"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <g clip-path="url(#clip0_3230_15033)">
                    <path
                      d="M9.84611 3.97831L9.00157 3.13377C8.93343 3.06655 8.84258 3.0273 8.74693 3.02375C8.65128 3.02021 8.55778 3.05263 8.48485 3.11463C8.37227 3.21046 8.22779 3.26049 8.08006 3.25482C7.93232 3.24915 7.79211 3.18819 7.6872 3.08401C7.58308 2.97911 7.52214 2.83894 7.51647 2.69124C7.5108 2.54355 7.56081 2.39912 7.65658 2.28655C7.71857 2.21362 7.751 2.12012 7.74746 2.02447C7.74391 1.92882 7.70466 1.83797 7.63744 1.76983L6.79213 0.924331C6.7207 0.853032 6.62389 0.812988 6.52296 0.812988C6.42204 0.812988 6.32523 0.853032 6.25379 0.924331L4.90746 2.27047C4.82434 2.35389 4.76166 2.45542 4.72432 2.5671C4.71705 2.58858 4.70493 2.60808 4.68889 2.62411C4.67286 2.64014 4.65336 2.65227 4.63188 2.65954C4.52015 2.69688 4.41861 2.75963 4.33525 2.84288L0.882827 6.2953C0.811528 6.36673 0.771484 6.46354 0.771484 6.56447C0.771484 6.6654 0.811528 6.7622 0.882827 6.83364L1.72833 7.67818C1.79646 7.7454 1.88731 7.78465 1.98296 7.78819C2.07861 7.79173 2.17212 7.75931 2.24504 7.69732C2.35736 7.60071 2.50203 7.55012 2.65007 7.55568C2.79811 7.56124 2.93859 7.62254 3.04334 7.72729C3.1481 7.83205 3.20939 7.97252 3.21495 8.12056C3.22051 8.2686 3.16992 8.41328 3.07332 8.52559C3.01132 8.59852 2.9789 8.69202 2.98244 8.78767C2.98598 8.88332 3.02523 8.97417 3.09245 9.04231L3.937 9.88685C4.00843 9.95815 4.10524 9.99819 4.20617 9.99819C4.3071 9.99819 4.4039 9.95815 4.47534 9.88685L7.92776 6.43443C8.01095 6.3511 8.07371 6.24964 8.1111 6.13799C8.11834 6.11644 8.13047 6.09687 8.14654 6.0808C8.16261 6.06473 8.18218 6.0526 8.20372 6.04536C8.31535 6.00801 8.41681 5.94533 8.50016 5.86221L9.8463 4.51588C9.91734 4.44445 9.9572 4.3478 9.95717 4.24706C9.95713 4.14631 9.9172 4.04969 9.84611 3.97831ZM5.47566 3.41088C5.44722 3.43932 5.41347 3.46188 5.37631 3.47727C5.33916 3.49267 5.29933 3.50059 5.25912 3.50059C5.2189 3.50059 5.17908 3.49267 5.14192 3.47727C5.10477 3.46188 5.07101 3.43932 5.04257 3.41088L4.8223 3.1906C4.76628 3.13289 4.73521 3.05545 4.73581 2.97502C4.73642 2.89459 4.76865 2.81762 4.82554 2.76076C4.88243 2.7039 4.9594 2.6717 5.03984 2.67113C5.12027 2.67056 5.1977 2.70166 5.25539 2.75771L5.47566 2.97779C5.5041 3.00623 5.52666 3.03999 5.54205 3.07714C5.55745 3.1143 5.56537 3.15412 5.56537 3.19434C5.56537 3.23455 5.55745 3.27438 5.54205 3.31153C5.52666 3.34869 5.5041 3.38244 5.47566 3.41088ZM6.31771 4.25293C6.26029 4.31031 6.18244 4.34255 6.10127 4.34255C6.02009 4.34255 5.94224 4.31031 5.88482 4.25293L5.67431 4.04242C5.64587 4.01398 5.62331 3.98022 5.60792 3.94307C5.59253 3.90591 5.58461 3.86609 5.58461 3.82588C5.58461 3.78566 5.59253 3.74584 5.60792 3.70869C5.62331 3.67153 5.64587 3.63777 5.67431 3.60934C5.73174 3.5519 5.80963 3.51964 5.89085 3.51964C5.93107 3.51964 5.97089 3.52756 6.00804 3.54295C6.0452 3.55834 6.07895 3.5809 6.10739 3.60934L6.3179 3.81985C6.34644 3.84828 6.36909 3.88207 6.38455 3.91927C6.40001 3.95647 6.40798 3.99635 6.408 4.03664C6.40801 4.07692 6.40008 4.11682 6.38466 4.15403C6.36923 4.19124 6.34661 4.22505 6.3181 4.25351L6.31771 4.25293ZM7.15977 5.09499C7.13133 5.12343 7.09757 5.14599 7.06042 5.16138C7.02327 5.17677 6.98344 5.1847 6.94323 5.1847C6.90301 5.1847 6.86319 5.17677 6.82603 5.16138C6.78888 5.14599 6.75512 5.12343 6.72668 5.09499L6.51617 4.88447C6.46014 4.82676 6.42908 4.74932 6.42968 4.66889C6.43029 4.58845 6.46252 4.51149 6.51941 4.45463C6.57629 4.39776 6.65327 4.36557 6.73371 4.365C6.81414 4.36443 6.89157 4.39553 6.94925 4.45158L7.15977 4.66209C7.18839 4.69048 7.21112 4.72424 7.22666 4.76143C7.2422 4.79862 7.25025 4.83851 7.25034 4.87882C7.25043 4.91913 7.24256 4.95906 7.22718 4.99632C7.2118 5.03358 7.18922 5.06743 7.16072 5.09594L7.15977 5.09499ZM8.01005 5.94738C7.98162 5.97582 7.94786 5.99838 7.9107 6.01377C7.87355 6.02916 7.83373 6.03708 7.79351 6.03708C7.75329 6.03708 7.71347 6.02916 7.67631 6.01377C7.63916 5.99838 7.6054 5.97582 7.57697 5.94738L7.35803 5.72729C7.329 5.69894 7.30588 5.6651 7.29004 5.62774C7.2742 5.59038 7.26594 5.55024 7.26574 5.50966C7.26555 5.46908 7.27342 5.42886 7.28891 5.39135C7.3044 5.35384 7.32718 5.31978 7.35595 5.29115C7.38472 5.26253 7.41889 5.23991 7.45647 5.2246C7.49406 5.2093 7.53431 5.20162 7.57489 5.20201C7.61547 5.2024 7.65557 5.21086 7.69285 5.22688C7.73014 5.24291 7.76387 5.26618 7.79207 5.29536L8.0112 5.51525C8.03963 5.54369 8.06217 5.57746 8.07755 5.61462C8.09293 5.65179 8.10083 5.69161 8.10081 5.73183C8.10079 5.77205 8.09285 5.81187 8.07745 5.84901C8.06204 5.88616 8.03946 5.91991 8.01101 5.94833L8.01005 5.94738Z"
                      fill="#9DC64E"
                    />
                  </g>
                  <defs>
                    <clipPath id="clip0_3230_15033">
                      <rect
                        width="9.79845"
                        height="9.79845"
                        fill="white"
                        transform="translate(0.465332 0.506836)"
                      />
                    </clipPath>
                  </defs>
                </svg>
              </div>
              <span
                class="text-[10px] text-black"
                v-if="eventData.payment != 'free'"
              >
                {{ 
                // numberWithCommas(eventData.price, "NGN")
                'package'
                 }}
                <!-- {{
                  eventData.slot === "per_person"
                    ? "Per Guest"
                    : eventData.slot === "per_couple"
                    ? "Per Couple"
                    : null
                }} -->
                </span
              >

              <span v-else class="text-[11px] text-black"> Free </span>
            </div>
          </div>

          </div>
        </Link>






          <div  
            class="flex items-center justify-between  px-2 mb-3"
            v-if="this.type === 'diner'"
          >
            <div class="mt-2 "  v-if="isHome">
              <a :href="route('restaurants.index', 
                        {
                            id: eventData.vendor.id,
                        })" 
                        
                        class="text-[12px] capitalize font-bold text-dinesurf-green-400"  target="_blank">
                {{
                 eventData.vendor.company_name.length > 15
                    ?eventData.vendor.company_name.substring(0, 15) + "..."
                    :eventData.vendor.company_name
                }}
              </a>
            </div>

            <div>
              <a
                :href="route('getEvent', { id: eventData.id })"
                class="
                   btn
                  w-full
                  text-center
                  inline-flex
                  flex
                  items-center
                  px-2
                  py-1
                  bg-dinesurf-green-600
                  border border-transparent
                  rounded-lg
                  text-white
                  tracking-widest
                  hover:bg-red-700
                  active:bg-red-900
                  focus:outline-none
                  focus:border-red-900
                  focus:ring
                  focus:ring-red-300
                  disabled:opacity-25
                  transition
                 
                  text-[10px]
                "
                >      
                 Buy Ticket
           
              </a>
            </div>


          </div>
    
          
          <div  v-else  class="flex justify-end px-2 mb-3">
            <div class="mt-1">
              <button
                class="
                   btn
                  w-full
                  text-center
                  inline-flex
                  flex
                  items-center
                  px-2
                  py-1
                  bg-dinesurf-red-600
                  border border-transparent
                  rounded-lg
                  text-white
                  tracking-widest
                  hover:bg-red-700
                  active:bg-red-900
                  focus:outline-none
                  focus:border-red-900
                  focus:ring
                  focus:ring-red-300
                  disabled:opacity-25
                  transition
                 
                  text-[10px]
                "
                @click="runAction()"
                >      
                  Delete Event
           
                
            </button>
            </div>
        
          </div>
       
        
      <!-- </div> -->

      <div
        :class="[
          eventData.event_type === 'past'
            ? 'bg-dinesurf-red-400'
            : eventData.event_type === 'live'
            ? 'bg-dinesurf-green-400'
            : eventData.event_type === 'upcoming'
            ? 'bg-yellow-300'
            : null,
            isHome ? 'right-0   top-0' : 'right-0 top-0',
        ]"
        class="px-5 py-1 absolute  rounded-tr"
      >
        <span class="text-white capitalize">{{ eventData.event_type }} </span>
      </div>
    <!-- </div> -->
  </div>
</template>
  <script>
import moment from "moment";
export default {
  props: ["event", "onDelete", "type", "isHome"],
  data() {
    return {
      eventData: this.event,
      showVideoModal: false,
      image: this.event?.images?.length > 0 ? this.event.images[0]?.image_url :  this.event?.image_url
    };
  },

  methods: {
    getFormatted(date) {
      var m = moment(date, "YYYY-MM-DD"); // explicit input format
      return m.format("ddd MMM DD YYYY");
    },
    getRandomImage() {
      // var num = Math.floor(Math.random() * 6);
      var num = Math.floor(Math.random() * this.images.length);
      var img = this.images[num];
      return img;
    },

    runAction() {
      this.$emit("onDelete", {
        action: "delete",
        ids: [this.eventData?.id],
      });
    },
  },

  mounted() {
      //  console.log("eventevent:", this.event)
  },
};
</script>
  <style>
.imageN {
  object-fit: cover;
  width: 100% !important;
  height: 100% !important;
}
.imageContainer {
  /* width: 260px; */
  width: 260px;
  height: 130px;
  background-color: gray;
}
</style>