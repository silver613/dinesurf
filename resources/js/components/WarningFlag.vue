<template>
     <div 
     :class="this.status === 'success' ? 'text-dinesurf-green-600  border-l-dinesurf-green-500' : 'text-dinesurf-red-600  border-l-dinesurf-red-500'"
     
      class="flex     w-auto border p-2   rounded border-l-4 ">
         <span class="pr-3">
            <i  class="fa fa-info-circle"></i>
         </span>
         <span>{{title}}
            <a   v-if="this.link" :href="this.link"  class="text-blue-400"  >click here</a>
         </span>

     </div>
</template>

<script>
export default {
props: ["title", 'status', 'link'],



mounted(){
   console.log("status:", this.status);
   console.log("title:", this.title)
}

}
</script>


