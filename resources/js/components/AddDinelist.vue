<template>
    <template v-if="this.isDinelistvendor">
        <jet-dropdown align="right" width="48">
                                          
            <template #trigger>
                <button>
                        <svg width="7" height="24" viewBox="0 0 7 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="3.0781" cy="3.01165" r="3" transform="rotate(90.2247 3.0781 3.01165)" fill="#9DC64E"/>
                            <circle cx="3.04294" cy="12.0116" r="3" transform="rotate(90.2247 3.04294 12.0116)" fill="#9DC64E"/>
                            <circle cx="3.01169" cy="20.0116" r="3" transform="rotate(90.2247 3.01169 20.0116)" fill="#9DC64E"/>
                        </svg>

                    </button>
            </template>

            <template #content >
                <div class="flex flex-col  justify-start items-start p-4 space-y-3">
                    <button @click="handleRemoveFromDinelist" class="flex  space-x-2 items-center "> 
                              <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                 <path d="M7.2 3.6H10.8C10.8 3.12261 10.6104 2.66477 10.2728 2.32721C9.93523 1.98964 9.47739 1.8 9 1.8C8.52261 1.8 8.06477 1.98964 7.72721 2.32721C7.38964 2.66477 7.2 3.12261 7.2 3.6ZM5.4 3.6C5.4 2.64522 5.77928 1.72955 6.45442 1.05442C7.12955 0.379285 8.04522 0 9 0C9.95478 0 10.8705 0.379285 11.5456 1.05442C12.2207 1.72955 12.6 2.64522 12.6 3.6H17.1C17.3387 3.6 17.5676 3.69482 17.7364 3.8636C17.9052 4.03239 18 4.2613 18 4.5C18 4.73869 17.9052 4.96761 17.7364 5.1364C17.5676 5.30518 17.3387 5.4 17.1 5.4H16.3062L15.5088 14.706C15.4321 15.6046 15.021 16.4417 14.3567 17.0517C13.6924 17.6617 12.8233 18.0001 11.9214 18H6.0786C5.17672 18.0001 4.30765 17.6617 3.64333 17.0517C2.97902 16.4417 2.56786 15.6046 2.4912 14.706L1.6938 5.4H0.9C0.661305 5.4 0.432387 5.30518 0.263604 5.1364C0.0948211 4.96761 0 4.73869 0 4.5C0 4.2613 0.0948211 4.03239 0.263604 3.8636C0.432387 3.69482 0.661305 3.6 0.9 3.6H5.4ZM11.7 9C11.7 8.7613 11.6052 8.53239 11.4364 8.3636C11.2676 8.19482 11.0387 8.1 10.8 8.1C10.5613 8.1 10.3324 8.19482 10.1636 8.3636C9.99482 8.53239 9.9 8.7613 9.9 9V12.6C9.9 12.8387 9.99482 13.0676 10.1636 13.2364C10.3324 13.4052 10.5613 13.5 10.8 13.5C11.0387 13.5 11.2676 13.4052 11.4364 13.2364C11.6052 13.0676 11.7 12.8387 11.7 12.6V9ZM7.2 8.1C7.43869 8.1 7.66761 8.19482 7.8364 8.3636C8.00518 8.53239 8.1 8.7613 8.1 9V12.6C8.1 12.8387 8.00518 13.0676 7.8364 13.2364C7.66761 13.4052 7.43869 13.5 7.2 13.5C6.96131 13.5 6.73239 13.4052 6.5636 13.2364C6.39482 13.0676 6.3 12.8387 6.3 12.6V9C6.3 8.7613 6.39482 8.53239 6.5636 8.3636C6.73239 8.19482 6.96131 8.1 7.2 8.1ZM4.284 14.553C4.32234 15.0025 4.52805 15.4211 4.8604 15.7262C5.19275 16.0312 5.6275 16.2003 6.0786 16.2H11.9214C12.3722 16.1998 12.8065 16.0305 13.1385 15.7255C13.4704 15.4206 13.6759 15.0022 13.7142 14.553L14.499 5.4H3.501L4.2858 14.553H4.284Z" fill="#383838"/>
                              </svg>
                              <span class="font-medium text-[18px]">Remove&nbsp;</span>
                            </button>
                </div>
            </template>
            </jet-dropdown>
    </template>

    <template v-else>

         <button  @click="handleAddToDinelist">
              <template v-if="isvendorExist">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12" cy="12" r="11" stroke="#9DC64E" stroke-width="2"/>
                            <path d="M7.12 11.72H16.96V13.5H7.12V11.72Z" fill="#9DC64E"/>
                        </svg>
              </template>

              <template v-else>
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12" cy="12" r="11" stroke="#9DC64E" stroke-width="2"/>
                            <path d="M12.98 7.56V17.68H11.12V7.56H12.98ZM16.96 11.72V13.5H7.12V11.72H16.96Z" fill="#9DC64E"/>
                        </svg>
              </template>
               
           </button>
     </template>
</template>


<script>


import JetDropdown from "@/Jetstream/Dropdown.vue";

export default {
  props:['vendor', 'dinelist','isDinelistvendor'],
    components: {
        JetDropdown
    },
   data(){
    return{
           vendorData: this.vendor,
           list: this.dinelist,
           isvendorExist: false
        }
   },
    

   methods:{

    handleRemoveFromDinelist(){
        const vendor_id  = this.vendorData.id;
        const dinelist_id = this.list.id;
        const isVendor = true;
        const data = {  vendor_id, dinelist_id , isVendor };
        this.$inertia.post(route('add.vendor.dinelist'), data);
        this.isvendorExist = !this.isvendorExist;
        this.$inertia.router.reload();
    },
    handleAddToDinelist(){
        const vendor_id  = this.vendorData.id;
        const dinelist_id = this.list.id;
        const isVendor = this.isvendorExist;
        const data = {  vendor_id, dinelist_id , isVendor };
        this.$inertia.post(route('add.vendor.dinelist'), data);
        this.isvendorExist = !this.isvendorExist;
        this.$inertia.router.reload();
    },

    checkVendor(){
        const check = this.dinelist?.vendors?.find((vendor) =>  vendor.id === this.vendor?.id );
        if(check){
           return true;
        }
       return false
    }
   },

    mounted(){
      this.isvendorExist =  this.checkVendor()

      
    }
}


</script>