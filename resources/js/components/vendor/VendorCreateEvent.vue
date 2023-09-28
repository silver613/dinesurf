<template>
         <div  class="  w-full  p-5  md:p-10">
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
                            <div  class="w-full  flex flex-col "   id="paymentInfo">
                                <jet-label>  <p class="mb-2">
                Payment:
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

                                               @change="handlepaymentInfo"
                                              
                                           >
                                               <option  :value="null">-- Select payment --</option>
                                               <option   value="free" >Free</option>
                                               <option   :disabled="!isSubscribed"  value="paid">Pre-Paid</option>
                                               <option  value="post">Post-Paid</option>
                                   </select>

                                   <div  className="mb-4" v-if="isPaymentInfo">
                                        <flash-text
                                        :title="'You need to update your info with bank details, where we can send your revenue shares.'"
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
                                      
                                    <div class="grid md:grid-cols-2 grid-cols-1 md:gap-x-2 mt-4">
                                              <div  class="border shadow-md rouded-lg p-3 mt-2" v-for="(item, rowIndex) in packagesList" :key="rowIndex">
                                                        <div class="flex justify-between">
                                                              <span class="w-[8rem]  capitalize text-md font-bold">{{item.name}}</span>
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

                                    <!-- <div class="mt-3  bg-red-400" v-if="packagesList.length > 0">
                                          <table class="table table-fixed">
                                              <thead>
                                                  <tr>
                                                      <th class=" px-5">Name</th>
                                                      <th class=" px-5">Description</th>
                                                      <th class=" px-5">Price</th>
                                                  </tr>
                                              </thead>

                                              <tbody>
                                                  <tr   v-for="(item, rowIndex) in packagesList"  :key="rowIndex">
                                                      <td class=" px-5">{{item.name}}</td>
                                                      <td class=" px-5 max-w-[2rem]" style="width:120px !important">{{ item.description }}</td>
                                                      <td class=" px-5">{{ item.price }}</td>
                                                      <td>
                                                        <span   @click="handleRemovePackage(rowIndex)" id="deleteRow" class="delete_row cursor-pointer" >
                                                                        <i  class="fa fa-trash    text-dinesurf-red-400"></i>     
                                                                </span>
                                                          </td>
                                                  </tr>
                                              </tbody>
                                          </table> -->
                                    <!-- </div> -->
                                   <!-- <jet-label>Price: </jet-label> -->

                                   <!-- <div  class="flex "> -->
                                       <!-- <div class="w-[40%]">
                                           <select

                                           required
                                                   class="
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
                                                   border-r-0
                                                   "
                                                   v-model="form.slots"
                                                   name="duration"
                                                   id="duration"
                                               >
                                                   <option selected :value="null">--Select--</option>
                                                   <option value="per_person">Per Guest</option>
                                                   <option value="per_couple">Per Couple(2 people)</option>
                                               </select>
                                       </div> -->

                                       <!-- <div  class="w-[70%]">
                                           <input
                                               type="number"
                                               v-model="form.price"
                                               class=" w-full mt-1  border-l-0 rounded-md
                                                   shadow-sm focus:border-[#ccc]  border-3 border-[#ccc] "
                                               placeholder="Enter Price"
                                               :disabled="form.payment === 'free'"
                                               
                                           />
                                       </div> -->
                                   <!-- </div> -->
                              </div>







                              <!-- duration -->

                              <div class="md:mt-10  ">
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




<!--                         
                           <div class="flex items-center  w-full  border border-dashed border-dinesurf-green-400 bg-gray-100 justify-between p-3">
                            

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
                                   <button type="button"  class="bg-dinesurf-green-400  relative w-32 h-10 pt-0 text-white">
                                    
                                       <span v-if="!this.image_value">Choose Image</span>
                                       <span   v-else>Change Image</span>
                                       <input
                                           dusk="image"
                                           type="file"
                                           :id="'file-image'"
                                           name="image"
                                           accept="image/*"
                                           class="form-file-input select-none  opacity-0  absolute top-2 left-4 "
                                           @change="displayFile"
                                       />
                                   </button>
                           </div> -->

                            <EventUploadImage    @handleImages="handleImages" />

              

                       </div>


                       <div class="w-full mt-10">
                             <button 
                             :class="{ 'opacity-25': processing || isPaymentInfo }"
                             :disabled="processing || isPaymentInfo"
                             type="submit" class="btn  text-white font-bold rounded bg-dinesurf-green-600 w-full p-3">
                                      <span v-if="processing">Processing....</span> 
                                      <span v-else>Create Event</span>
                             
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
            selectedTags: [],
            selectedDays:[],
            selectedDay: null,
            processing:false,
            image_value:null,
            eventPackage:{
                name: null,
                description: null,
                price: null
            },
            packagesList:[],
            form: this.$inertia.form({
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
                goal: 20,
                repeat: null,
                payment: this.isSubscribed ? null : "free",
                slots:null,
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
            this.packagesLIst = [];
            this.form = this.$inertia.form({
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
                goal: 0
            })
          },

         submit() {
         

            if(this.form.payment !='free' && this.packagesList.length < 1){
                toastr.error("You need to add some packages to paid events");
                return;
            }

    
            this.processing = true;
            var formData = new FormData();
            this.form.file.forEach((file) => {
                formData.append("images[]", file);
            });
            // formData.append("images", this.form.file);
            formData.append("name", this.form.name);
            formData.append("price", this.form.price);
            formData.append("description", this.form.description);
            formData.append("capacity", this.form.capacity);
            formData.append("goal", this.form.goal);
            formData.append("startDate", this.form.startDate);
            formData.append("startTime", this.form.startTime);
            formData.append("endDate", this.form.duration != 'single' ? this.form.endDate : this.form.startDate);
            formData.append("endTime",  this.form.endTime);
            formData.append("duration", this.form.duration);
            formData.append("slot", this.form.slots);
            formData.append("payment", this.form.payment);
            formData.append("repeat",
             this.form.duration === 'repeat' ? JSON.stringify(this.selectedDays?.map((item) => item.value)) : null)
             formData.append("packages", JSON.stringify(this.packagesList));
       
            axios
          .post(route("vendors.create-event"), formData,
          {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
           }
          )
                .then(({data}) => {

                if (data.success) {
                    toastr.success(data.message);
                    this.reset();
                } else {
                   if (data.message == "CSRF token mismatch.") {
                        window.location.reload();
                    }else{
                     toastr.error(data.message);
                    }      
                }

                })
                .catch((err) => {
                //  this.handleApiError(err);
                toastr.error(err.message);
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
            }
    },


    mounted(){

  tippy("#paymentInfo", {
      content:
        "For free restaurant plans, it is not possible to offer pre-paid events. In order to charge before the event at the time of reservation, you will need to upgrade to a paid plan.",
    });
    
    }

    
}
</script>