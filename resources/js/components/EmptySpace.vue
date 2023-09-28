<template>

    <div  class=" flex justify-center flex-col items-center  ">
          <div  class="w-[120px]  h-[120px] "  v-if="image">
             <img
              class="fill-current object-contain"
              :src="image"
            />
          </div>

          <div  class="w-80">
                 <div class="flex flex-col  items-center">
                                    <span v-if="this.title" class="font-bold mb-2 text-sm  text-[#22320C]">
                                     {{ this.title }}
                                    </span>

                                    <span  v-if="this.description">
                                     {{ this.description }}
                                    </span>
                     </div>
          </div>

              <div class="mt-5"  v-if="this.link">
                                    <Link  :href="this.link" :id="this.title"  target="_blank"
                                    class="jet-btn w-full active:bg-gray-900 text-xs h-10"
                                     > 
                                     <div  class="mr-1">
                                         <create-icon />
                                     </div>
                                     Create {{ this.page }}</Link>
               </div>

               <div class="mt-5"  v-else-if="!this.noButton">
                                    <button @click="handle()"  
                                    class="jet-btn w-full active:bg-gray-900 text-xs h-10"
                                     >

                                     <div  class="mr-1">
                                         <create-icon />
                                     </div>
                                     
                                     Create {{ this.page }}</button>
               </div>


    </div>
       
</template>

<script>

export default {
  props:['image', 'title', 'description', 'link', "page", "noButton"],
    
   data(){
    return{
           image: this.image
        }
   },
    

   methods:{
      handle(){
              this.$emit("handleAsbutton");
      }
   },

    mounted(){
        // console.log("files mounted:", this.image);
    }
}
</script>