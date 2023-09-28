<template>
    <div  class="w-full  p-5  md:p-10">
          <form  @submit.prevent="submit()"  enctype="multipart/form-data">
              <div  class="flex md:flex-row   flex-col justify-between">
                      <div  class="w-full  flex flex-col md:px-20">
                       <jet-label>Name: </jet-label>
                            <input
                                type="text"
                                v-model="form.name"
                                class="auth-card-input mt-1"
                                placeholder="Enter  Name"
                                required
                                
                            />
                       </div>

                       <div  class="w-full  flex flex-col md:px-20">

                         <div>

                               <div  class="w-full  flex flex-col md:px-20">
                               <jet-label>Payment: </jet-label>
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
                                               <option selected :value="null">-- Select payment --</option>
                                               <option value="free">Free</option>
                                               <option value="paid">Paid</option>
                                           </select>
                                     </div>
                               
                                     <div>
                                   <jet-label>Price: </jet-label>

                                   <div  class="flex ">
                                       <div class="w-[40%]">
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
                                                   <option selected :value="null">--Select--</option>
                                                   <option value="per_person">Per Guest</option>
                                                   <option value="per_couple">Per Couple(2 people)</option>
                                               </select>
                                       </div>

                                       <div  class="w-[70%]">
                                           <input
                                               type="number"
                                               v-model="form.price"
                                               class="auth-card-input mt-1"
                                               placeholder="Enter Price"
                                               :disabled="form.payment === 'free'"
                                               
                                           />
                                       </div>
                                   </div>
                              </div>
                           </div>
                     
                          
                       </div>
                </div>


                


                <div  class="flex md:flex-row   flex-col justify-between">
                      <div  class="w-full  flex flex-col md:px-20">
                               <div class="w-full  flex flex-col mb-5">
                                   <jet-label>Description: </jet-label>
                                       <textarea
                                               id="description"
                                               type="text"
                                               class="form-control"
                                               v-model="form.description"
                                               style="height:150px"
                                               placeholder="Enter Description"
                                           ></textarea>
                               </div>

                               <div  class="w-full  flex flex-col ">
                                   <jet-label>Capacity(number of seats): </jet-label>
                                   <input
                                           type="text"
                                           v-model="form.capacity"
                                           class="auth-card-input mt-1"
                                           placeholder="Enter capacity"
                                           required
                                       />
                               </div> 
                             
                       </div>

                       <div  class="w-full md:mt-0 mt-4  flex flex-col md:px-20">


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
                               <div class="flex ">
                                      
                                   <div class="pr-5">
                                   <jet-label>Start Dates: </jet-label>
                                       <input
                                           type="date"
                                           v-model="form.startDate"
                                           class="auth-card-input mt-1"
                                           placeholder="Enter Date"
                                           required
                                       />
                                   </div>

                                   <div class="md:pl-5">
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
                                      
                                      <div class="pl-5 ">
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
                </div>

                <div  class="flex md:flex-row   flex-col justify-between">
                      <!-- <div  class="w-full  flex flex-col md:px-20">
                       <jet-label>Payment: </jet-label>
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
                                       <option selected :value="null">-- Select payment --</option>
                                       <option value="free">Free</option>
                                       <option value="paid">Paid</option>
                                   </select>
                       </div> -->

                       <div  class="w-full  flex flex-col md:px-20">
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
                                           <span class="text-dinesurf-green-400 cursor-pointer">
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
                                   <button type="button"  class="bg-dinesurf-green-400 w-32 h-10 pt-2 text-white">
                                    
                                       <span v-if="!this.image_value">Choose Image</span>
                                       <span   v-else>Change Image</span>
                                       <input
                                           dusk="image"
                                           type="file"
                                           :id="'file-image'"
                                           name="image"
                                           accept="image/*"
                                           class="form-file-input select-none  opacity-0 -mt-8"
                                           @change="displayFile"
                                       />
                                   </button>
                           </div>
                       </div>
                </div>


                 

               <!--  <div  class="flex md:flex-row  md:mt-0 mt-5 flex-col justify-between">
                      <div  class="w-full  flex flex-col md:px-20">
                       <jet-label>Occupancy Goal: </jet-label>
                          
                             <VueSimpleRangeSlider
                               style="width: 100%;  height: 20px"
                               :min="10"
                               :max="100"
                               exponential
                               v-model="form.goal"
                               >
                               <template #prefix="{ value }">%</template>
                               </VueSimpleRangeSlider>
                       </div> -->

                       <!-- <div  class="w-full  flex flex-col  md:mt-0 mt-9 md:px-20"> -->

                           <!-- <div class="flex flex-wrap mt-3">
                               <jet-label>Add Tags: </jet-label>
                               <select
                               @change="(e) => updateTag(e.target.value)"
                               name="tags"
                               id="tags"
                               class="mt-1 block w-full form-control"
                               >
                               <option :value="null">-- Add Tags --</option>
                               <option
                                   v-for="(item, index) in tagList"
                                   :key="index"
                                   :value="item.id"
                               >
                                   {{ item.name }}
                               </option>
                               </select>
                           </div> -->

                           <!-- <div class="flex mt-2 flex-wrap">
                               <div
                               class="border p-2 mr-3 mt-2"
                               v-for="(item, index) in selectedTags"
                               :key="index"
                               >
                               <span>{{ item.name }}</span>
                               <span class="ml-2 ">
                                   <i
                                   @click="removeTag(item.id)"
                                   class="fa fa-remove cursor-pointer"
                                   ></i
                               ></span>
                               </div>
                           </div>
                                   
                       </div> 


                      
                </div>-->

                <div  class="flex md:flex-row  md:mt-5 mt-5  justify-end w-full md:px-20">
                      <div class="md:w-[41%]  w-full">
                             <button 
                             :class="{ 'opacity-25': processing }"
                             :disabled="processing"
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
import MuiltipleSelector from "../MultipleSelector.vue"
export default {

components: {
VueSimpleRangeSlider,
MuiltipleSelector
},

data(){
   return{
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
           payment:null
       }),
   }
},

methods: {

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

       
   console.log("startTime:", this.form)
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
    
       this.processing = true;
       var formData = new FormData();
       formData.append("image", this.form.file);
       formData.append("name", this.form.name);
       formData.append("price", this.form.price);
       formData.append("description", this.form.description);
       formData.append("capacity", this.form.capacity);
       formData.append("goal", this.form.goal);
       formData.append("startDate", this.form.startDate);
       formData.append("startTime", this.form.startTime);
       formData.append("endDate", this.form.endDate);
       formData.append("endTime", this.form.endTime);
       formData.append("duration", this.form.duration);
       formData.append("repeat",
        this.form.duration === 'repeat' ? JSON.stringify(this.selectedDays?.map((item) => item.value)) : null)
  
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
           //   console.log("image:", e.target.result);
           };
           reader.readAsDataURL(file);
       }
},


}
</script>