<template>
           <div class="mb-0" >
                   <jet-label>{{this.label}}</jet-label>
                                <select
                                 class="auth-card-input
                                  border-gray-300
                                  focus:border-indigo-300
                                  focus:ring
                                  focus:ring-indigo-200
                                  focus:ring-opacity-50
                                  rounded-md
                                  shadow-sm
                                  mt-1
                                  block
                                  w-full "
                                  @change="(e) => handleSelected(e)"
                                  name="duration"
                                  :id="this.id" >
                                    <option selected :value="null">-- {{this.label}} --</option>
                                    <option  v-for="(item , index) in this.list"  :disabled="checkIfSelected(item.value)" :value="item.value" :key="index">{{item.name}}</option>
                                 </select>
            </div>

                                  <div class="flex  -mt-5 mb-3 flex-wrap">
                                    <div
                                    class="border p-2 mr-3 mt-2 "
                                    v-for="(item, index) in selected"
                                    :key="index"
                                    >
                                    <span>{{ item.name }}</span>
                                    <span class="ml-2 ">
                                        <i
                                        @click="handleRemoveSelected(item.value)"
                                        class="fa fa-remove cursor-pointer"
                                        ></i
                                    ></span>
                                    </div>
                                </div>
</template>

<script>
export default {
   props:['label', 'selected', 'list', 'id'],
   data(){
     return{
        repeat: null
     }
   },
   methods:{
       handleSelected(e){
        this.$emit('updateSelected', e);
       },

       handleRemoveSelected(value){
        this.$emit('removeSelected', value);
       },

       checkIfSelected(value){
         const find = this.selected?.find((item)=> item.value === value);

         if(find){
            return true;
         }else{
            return false;
         }
       }
   }
    
}
</script>