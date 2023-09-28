<template>
        <div class="flex items-center justify-center  w-full">
            <div class="shadow-md  ">
                <div class="mb-5 border-b p-4">
                   <span  class="font-bold  text-xl">Customize Menu page For {{this.vendor.company_name}} </span>
                </div>

                <div class="p-4">
                      <div class="flex ">
                           <span class="text-small w-40 mb-5">
                              Pick  Background Color :
                           </span>
                           <input type="color" v-model="bgColor"  class=""  @change="handleChangBg"/>
                      </div>

                      <div class="flex ">
                           <span class="text-small w-40 mb-5">
                              Pick  Text Color :
                           </span>
                           <input type="color" v-model="color" @change="handleChangColor"/>
                      </div>
                </div>
            </div>
              
        </div>
</template>

<script>
export default {
 props: ["vendor"],


 data(){
    return{
      bgColor:  localStorage.getItem('vendor_with_customize_color') ? localStorage.getItem('vendor_with_customize_color') :'#000000',
      color: localStorage.getItem('vendor_with_customize_bg')? localStorage.getItem('vendor_with_customize_bg') : '#000000'
    }
 },

 methods:{
    handleChangBg(e){
        localStorage.setItem("vendor_with_customize",  this.vendor.id);
        localStorage.setItem("vendor_with_customize_bg",  e.target.value);
    },
    handleChangColor(e){
        localStorage.setItem("vendor_with_customize",  this.vendor.id);
        localStorage.setItem("vendor_with_customize_color",  e.target.value);
    }
 },

 mounted(){
    console.log("Vendor:", this.vendor.id);
 }
}
</script>

