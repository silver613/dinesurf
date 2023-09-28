<template>
    <jet-modal :show="this.show" @close="this.close" >
   <div class="h-auto ">
       <h2 class="font-semibold text-xl text-gray-800 text-center leading-tight mt-3 mb-3">
         Menu Notes 
         </h2>
         <div  class="flex flex-col  justify-between   ">
           
               <div class="flex-column items-center  px-10 md:px-20">
                <p  class="mt-4"> Create items,  create item for each category, Items on your menu are the individual name of food items with their prices,    Examples of items are <span class="font-bold">seafood fried rice 4000, spaghetti bolognese 8000, chocolate milkshake 500, chicken and fish 5000 </span> etc.</p>
                 
               </div>

               <div class="md:px-20  px-10 my-5">
                  <label>
                    <input type="checkbox"   class="
                         checked:bg-dinesurf-green-600 checked:border-dinesurf-green-600
                         focus:border-dinesurf-green-600 focus:bg-dinesurf-green-600"  @change="handleCheckbox"  />
                    Do not show me this again
                  </label>
               </div>

               <div class="flex flex-row  justify-between md:px-20   px-10  mb-10">
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
            localStorage.setItem("menuItemvendorId", this.$page.props.auth.vendor.id);
            // this.$inertia.get(
            //   route("vendors.menu-category-items")
            // );
            this.close();
          }else{
            // this.$inertia.get(
            //   route("vendors.menu-category-items")
            // );
            this.close();
          }
  }

  
 },
 mounted() {

   console.log("show:", this.show)
 },
}
</script>

