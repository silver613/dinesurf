<template>
    <div  class="w-full  p-5  md:p-10">
        <form  @submit.prevent="submit()"  class="flex  md:flex-row flex-col  justify-around "  enctype="multipart/form-data">
                       <div class=" md:w-[40%] md:mr-3">
                           
                           <div  class="w-full  flex flex-col  w-full">
                                <jet-label>Name: </jet-label>
                                    <input
                                        type="text"
                                        v-model="form.name"
                                        class="auth-card-input mt-1"
                                        placeholder="Enter  Name"
                                        required
                                            
                                    />
                            </div>
                            <div  class="w-full  flex flex-col ">
                                <jet-label>  <p class="mb-2">
                Payment:
                <span
                  id="paymentInfo"
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
                ></span>
              </p> </jet-label>
                                  <select
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
                                               "
                                               v-model="form.payment"
                                               name="duration"
                                               id="duration"
                                           >
                                              <option  :value="null">-- Select payment --</option>
                                               <option   value="free" >Free</option>
                                               <option   :disabled="!isSubscribed"  value="paid">Pre-Paid</option>
                                               <option  value="post">Post-Paid</option>
                                   </select>

                                   <div  className="mb-4" v-if="isPaymentInfo">
                                        <flash-text
                                        :title="'You need to update your info with bank details, where we can send your revenue share.'"
                                        :link="route('vendors.profile.show')"
                                   
                                        />
                                   </div>
                            </div>

                            <div v-if="form.payment !== 'free'">

                                    <h5 class="text-sm mb-3">Add Packages</h5>

                                    <div class="  grid grid-cols-2  gap-x-3">

                                            <div  class="">
                                                <jet-label>Package name: </jet-label> 
                                            <input
                                                type="text"
                                                v-model="eventPackage.name"
                                                class=" w-full mt-1  border-l-0 rounded-md
                                                    shadow-sm focus:border-[#ccc]  border-3 border-[#ccc] "
                                                placeholder="Enter package Name"
                                                
                                            />
                                        </div>

                                        
                                        <div  class="">
                                            <jet-label>Package price: </jet-label> 
                                            <input
                                                type="number"
                                                v-model="eventPackage.price"
                                                class=" w-full mt-1  border-l-0 rounded-md
                                                    shadow-sm focus:border-[#ccc]  border-3 border-[#ccc] "
                                                placeholder="Enter Price"
                                                
                                            />
                                        </div>

                                    </div>

                        <div class="  grid grid-cols-2  gap-x-3 mt-3">

                                <div  class="">
                                    <jet-label>Package Description: </jet-label> 
                                        <input
                                            type="text"
                                            v-model="eventPackage.description"
                                            class=" w-full mt-1  border-l-0 rounded-md
                                                shadow-sm focus:border-[#ccc]  border-3 border-[#ccc] "
                                            placeholder="Enter package Description"
                                            
                                        />
                                </div>


                                    <div  class="">
                                        <button 
                                                type="button"  @click="handlePackage()" class="btn  mt-6 text-white font-bold rounded bg-dinesurf-green-600 w-full p-3">
                                        
                                                        <span >Save</span>
                                                
                                                </button>
                                    </div>

                                </div>
                        
                        <div class="grid md:grid-cols-2 grid-cols-1 md:gap-x-2 mt-4 mb-3">
                                <div  class="border shadow-md rouded-lg p-3 mt-2" v-for="(item, rowIndex) in packagesList" :key="rowIndex">
                                            <div class="flex justify-between">
                                                <span class="w-[8rem]  capitalize  text-md font-bold">{{item.name}}</span>
                                                <span   @click="handleRemovePackage(rowIndex)" id="deleteRow" class="delete_row cursor-pointer" >
                                                            <i  class="fa fa-trash    text-dinesurf-red-400"></i>     
                                                </span>
                                            </div>

                                            <div class="flex    flex-wrap mt-2">
                                                <span>{{numberWithCommas(item.price, "NGN")  }}</span>
                                            </div>
                                            <div class="flex  flex-wrap mt-2">
                                                <span class="  whitespace-wrap text-justify">{{ item.description }}</span>
                                            </div>
                                </div>
                        </div>

                    </div>



                              <!-- duration -->

                              <div>
                                   <jet-label>Duration: </jet-label>

                                   <select
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
                                       "
                                       v-model="form.duration"
                                       name="duration"
                                       id="duration"
                                   >
                                       <option selected :value="null">-- Select duration --</option>
                                       <option value="single">Single Day Event</option>
                                       <option value="double">Two or more Days</option>
                                       <option value="repeat">Repeat</option>
                                   </select>
                                   
                               </div>

                               <!-- date -->

                               <div class="flex ">
                                      
                                      <div  :class="form.duration != 'single' ? 'pr-5': 'w-full'" >
                                      <jet-label>Start Dates: </jet-label>
                                          <input
                                              type="date"
                                              v-model="form.startDate"
                                              class="auth-card-input mt-1"
                                              placeholder="Enter Date"
                                              required
                                          />
                                      </div>
   
                                      <div class="md:pl-5" v-if="form.duration != 'single' ">
                                         <jet-label>End Dates: </jet-label>
                                             <input
                                                 type="date"
                                                 v-model="form.endDate"
                                                 class="auth-card-input mt-1"
                                                 placeholder="Enter Date"
                                                 required
                                             />
                                        </div>
                                      
                                  </div>

                                  <div class="flex " >
                                      

                                      <div class="pr-5 md:w-[230px]">
                                      <jet-label> Start Time {{form.duration != 'single' ? '(For every day)': '(For the day)'}}: </jet-label>
                                          <input
                                              type="time"
                                              v-model="form.startTime"
                                              class="auth-card-input mt-1"
                                              placeholder="Enter Time"
                                              required
                                          />
                                      </div>
                                         
                                         <div class="pl-5   ">
                                         <jet-label> End Time : </jet-label>
                                             <input
                                                 type="time"
                                                 v-model="form.endTime"
                                                 class="auth-card-input mt-1"
                                                 placeholder="Enter Date"
                                                 required
                                             />
                                         </div>
                                     </div>

                             <div v-if="form.duration === 'repeat'">
                                   <muiltiple-selector 
                                    :label="'Repeat For:'" 
                                    :list="durationList" 
                                    @updateSelected="updateSelectedDay"
                                    :selected="selectedDays"
                                    @removeSelected="removeSelectedDay"
                                    />
                                   
                               </div>


                       </div>








                       <div  class="  md:w-[40%] ">
                        

                        <div class="w-full  flex flex-col mb-5">
                                   <jet-label>Description: </jet-label>
                                       <!-- <textarea
                                               id="description"
                                               type="text"
                                               class="form-control"
                                               v-model="form.description"
                                               style="height:250px"
                                               placeholder="Enter Description"
                                           ></textarea> -->
                                 <vue-editor v-model="form.description"></vue-editor>
                        </div>

                        <div  class="w-full  flex flex-col ">
                                   <jet-label>Capacity(number of seats): </jet-label>
                                   <input
                                           type="number"
                                           v-model="form.capacity"
                                           class="auth-card-input mt-1"
                                           placeholder="Enter capacity"
                                           required
                                       />
                       </div> 


                      <div  class="w-full  flex flex-col ">
                        <!--     <div class="flex items-center  w-full  border border-dashed border-dinesurf-green-400 bg-gray-100 justify-between p-3">
                            

                               <div
                                   class="

                                     w-40
                                     h-20
                                   "  

                                   v-if="image_value"
                                   >
                                   <img
                                       :src="image_value"
                                       class="imageN"
                                       draggable="false"
                                       :id="'image-output'"
                                   />
                                   </div>
                                  <div class="flex flex-col"  v-if="!this.image_value">
                                           <span class="text-dinesurf-green-400">
                                               Add Image/ Banner
                                           </span>
                                           <span class="text-xs">Max 2MB,PNG,JPEG</span>
                                           <span v-if="this.value" class="ml-1 capitalize">
                                               <span class="mr-2 text-green-600">{{responseText}}</span>
                                           </span>
                                           <span class="text-xs"  v-else>
                                           No file uploaded
                                           </span>
                                   </div>
                                   <button type="button"  class="bg-dinesurf-green-400   relative w-32 h-10 pt-0 text-white">
                                    
                                       <span v-if="!this.image_value">Choose Image</span>
                                       <span   v-else>Change Image</span>
                                       <input
                                           dusk="image"
                                           type="file"
                                           :id="'file-image'"
                                           name="image"
                                           accept="image/*"
                                           class="form-file-input select-none
                                           absolute top-2 left-4  opacity-0 "
                                           @change="displayFile"
                                       />
                                   </button>
                           </div>-->
                           <EventUploadImage    @handleImages="handleImages"     :selectedImages="this.event.images" />

                       </div> 


                       <div class="w-full mt-10">
                             <button 
                             :class="{ 'opacity-25': processing || isPaymentInfo }"
                             :disabled="processing || isPaymentInfo"
                             type="submit" class="btn  text-white font-bold rounded bg-dinesurf-green-600 w-full p-3">
                                      <span v-if="processing">Processing....</span> 
                                      <span v-else>Update Event</span>
                             
                             </button>
                       </div>
              



                 </div>
            </form>
    </div>
</template>

<script>

import VueSimpleRangeSlider from "vue-simple-range-slider";
import "vue-simple-range-slider/css";
import MuiltipleSelector from "../MultipleSelector.vue";
import { VueEditor } from "vue3-editor";
import tippy from "tippy.js";
import "tippy.js/dist/tippy.css"; // optional for styling
import EventUploadImage from '@/components/vendor/EventUploadImage.vue'

export default {
props:['event', 'vendor'],
components: {
VueSimpleRangeSlider,
MuiltipleSelector,
VueEditor,
EventUploadImage
},

data(){
   return{
      isSubscribed: this.$page.props.auth.vendor.subscribed,
       durationList: [
           {
               name:"Everyday", value: 'everyday'
           },
           {
               name:"Monday", value: 'monday'
           },
           {
               name:"Tuesday", value: 'tuesday'
           },
           {
               name:"Wednesday", value: 'wednesday'
           },
           {
               name:"Thursday", value: 'thursday'
           },
           {
               name:"Friday", value: 'friday'
           },
           {
               name:"Saturday", value: 'saturday'
           },
           {
               name:"Sunday", value: 'sunday'
           },
       ],
       tagList:[
           {name:"Commercial", id: 1},
           {name:"Business", id: 2},
           {name:"Sport", id: 3},
           {name:"Building", id: 4},
           {name:"Economy", id: 5},
           {name:"Marketing", id: 6},
       ],
       eventPackage:{
                name: null,
                description: null,
                price: null
            },
       packagesList: this.event.payment != 'free' ? JSON.parse(this.event.packages) || [] : [],
       selectedTags: [],
       selectedDays:[],
       selectedDay: null,
       processing:false,
       image_value:this.event.image_url,
       form: this.$inertia.form({
           name:this.event?.name,
           price:this.event?.price,
           description:this.event?.description,
           duration:this.event?.duration,
           capacity: this.event?.capacity,
           startDate: this.event?.start_date,
           startTime: this.event?.start_time,
           endDate: this.event?.end_date,
           endTime: this.event?.end_time,
           file: null,
           goal: this.event?.occupancy_goal,
           slots: this.event?.slot,
           payment: this.event?.payment,
       }),
       isPaymentInfo: false
   }
},

methods: {

    handlepaymentInfo(){
            const vendor = this.$page.props.auth.vendor;
          
            if(this.form.payment === 'paid' && vendor.account_number === null && vendor.bank_code === null){
                this.isPaymentInfo = true;
            }else{
                this.isPaymentInfo = false;
            }
        },
    handleImages(images){
                 this.form.file = images;
        },

    handleRemovePackage(rowIndex){
        const filt = this.packagesList?.filter((item, index) => index != rowIndex);
        this.packagesList = filt;
        },
        handlePackage(){
           
            const checkifExist  = this.packagesList?.find((item) => item.name === this.eventPackage.name);
            if(!checkifExist){
               this.packagesList.push(this.eventPackage);

               this.eventPackage = {
                    name: null,
                    price: null,
                    description: null
                };
            }

          
        },
   updateSelectedDay(e){
       const selectedDay = e.target.value;
       const daysList = this.durationList.find((item) => item.value === selectedDay);
       if (!this.selectedDays.includes(daysList) && selectedDay !== 'everyday' ) {
           this.selectedDays.push(daysList);
       }

       if(selectedDay === 'everyday'){
           const removeEveryday = this.durationList.filter((item) => item.value != selectedDay);
           this.selectedDays =  removeEveryday;
       }

       
   },

   removeSelectedDay(value) {
       const days = this.selectedDays.filter((item) => {
         return item.value !== value;
      });
       this.selectedDays = days;
   },

   removeTag(tagId) {
   const tags = this.selectedTags.filter((item) => {
       return item.id !== tagId;
   });
   this.selectedTags = tags;
   },


   updateTag(selectedTagId) {
       const taglist = this.tagList.find((item) => item.id === Number(selectedTagId));
       if (!this.selectedTags.includes(taglist)) {
           this.selectedTags.push(taglist);
       }
    },


    reset() {

       this.processing = false;
       this.image_value = null;
       this.selectedDays = [];
       this.packagesList = [];
       this.form = this.$inertia.form({
           id: this.event?.id,
           name:null,
           price:null,
           description:null,
           duration:null,
           capacity: null,
           starteDate: null,
           startTime: null,
           endDate: null,
           endTime: null,
           file: null,
           goal: 0,
           slots: null,
           payment: null
       })
     },

    submit() {

        
        if(this.form.payment !='free' && this.packagesList.length < 1){
                toastr.error("You need to add some packages to paid events");
                return;
            }
    
       this.processing = true;
       var formData = new FormData();

    //    if(this.form?.file?.length > 0){
           this.form?.file?.forEach((file) => {
                formData.append("images[]", file);
            });
    //    }
       
       formData.append("id", this.event.id);
    //    formData.append("image", this.form.file);
       formData.append("name", this.form.name);
       formData.append("price", this.form.price);
       formData.append("description", this.form.description);
       formData.append("capacity", this.form.capacity);
       formData.append("goal", this.form.goal);
       formData.append("startDate", this.form.startDate);
       formData.append("startTime", this.form.startTime);
       formData.append("endDate", this.form.duration != 'single' ? this.form.endDate : this.form.startDate);
       formData.append("endTime", this.form.endTime);
       formData.append("duration", this.form.duration);
       formData.append("slot", this.form.slots);
            formData.append("payment", this.form.payment);
       formData.append("repeat",
        this.form.duration === 'repeat' ? JSON.stringify(this.selectedDays?.map((item) => item.value)) : null)
        formData.append("packages", JSON.stringify(this.packagesList));
       

       axios
     .post(route("vendors.update-event", { id: this.event?.id}), formData,
     {
           headers: {
               'Content-Type': 'multipart/form-data'
           }
      }
     )
           .then(({data}) => {

           if (data.success) {
               toastr.success(data.message);
    
           } else {
              if (data.message == "CSRF token mismatch.") {
                   window.location.reload();
               }else{
                toastr.error(data.message);
               }
               
           }
           })
           .catch((err) => {
            this.handleApiError(err);
           }).finally(() => this.processing = false);
       },


        displayFile() {
        var files = document.getElementById("file-image").files;
        var output = document.getElementById("image-output");
        this.form.file = files[0];
        this.image_value = URL.createObjectURL(files[0]);
            output.src = URL.createObjectURL(files[0]);
        output.onload = function () {
        URL.revokeObjectURL(output.src); // free memory
        };
        },

        createImage(file) {
                let reader = new FileReader();
                let vm = this;
                reader.onload = (e) => {
                    vm.form.file = e.target.result;
                };
                reader.readAsDataURL(file);
            },

            setRepeat(){
            if(this.event.duration === 'repeat'){
                const parseRepeats = JSON.parse(this.event?.repeat).map((item) => {
                        return{
                            name: item.charAt(0).toUpperCase() + item.slice(1),
                            value: item
                        }
                    })

                    
                this.selectedDays = parseRepeats;
                }

        },

        },

       mounted(){
            this.setRepeat();
            tippy("#paymentInfo", {
      content:
      "For free restaurant plans, it is not possible to offer pre-paid events. In order to charge before the event at the time of reservation, you will need to upgrade to a paid plan.",
   });
            //   console.log("this.event:", this.event);
        }


}
</script>