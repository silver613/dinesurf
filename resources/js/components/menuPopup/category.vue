<template>
    <jet-modal :show="this.show" @close="this.close" >
   <div class="h-auto ">
       <h2 class="font-semibold text-xl text-gray-800 text-center leading-tight mt-3 mb-3">
         Menu Notes 
         </h2>
         <div  class="flex flex-col  justify-between   ">
           
               <div class="flex-column items-center md:px-20  px-10">
                <p  class="mt-4"> Create category for each menu, you can create more than two(2) categories for a menu,  Example of categories you can create are   <span class="font-bold">starters, alcoholic drinks, sides, milkshakes </span> etc.</p>
               
               </div>

               <div class="md:px-20  px-10 my-5">
                  <label>
                    <input type="checkbox"   class="
                         checked:bg-dinesurf-green-600 checked:border-dinesurf-green-600
                         focus:border-dinesurf-green-600 focus:bg-dinesurf-green-600"  @change="handleCheckbox"  />
                    Do not show me this again
                  </label>
               </div>

               <div class="flex flex-row  justify-between md:px-20 px-10 mb-10">
                 <button  @click="this.close" class="btn rounded p-2 w-20 h-10   text-white font-bold bg-dinesurf-red-400 ">Cancel</button>
                 <button   @click="redirectPage()"  class="btn jet-btn">Continue</button>
               </div>
 
         </div>
   </div>
 </jet-modal>
</template>

<script>
export default {
   emits: ['close'],
   props: {
        show: {
          default: false
        },
          
       },

 data() {
   return {
       menuBoxShow: this.show,
       isShowingAgain:false
   };
 },
 methods: {

   close() {
        this.$emit('close')
   },
  
   handleCheckbox(event){
     this.isShowingAgain = !this.isShowingAgain;
   },

   redirectPage(){

    
    if(this.isShowingAgain) {
            localStorage.setItem("menuCategoryvendorId", this.$page.props.auth.vendor.id);
            // this.$inertia.get(
            //   route("vendors.menu-categories")
            // );
            this.close();
          }else{
            // this.$inertia.get(
            //   route("vendors.menu-categories")
            // );
            this.close();
       }
  }

  
 },
 mounted() {

//    console.log("show:", this.show)
 },
}
</script>

