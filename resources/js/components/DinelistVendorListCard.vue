<template>
 <a :href="route('view.dinelist', { slug : dinelist.slug})"  class="flex  space-x-[10px] ">  
        
    <div   class=" w-20 h-20 bg-grey bg-gray-200 cursor-pointer   bg-center bg-cover rounded-[5px] flex   p-3" :style="'background-image: url(' + image + ')'" ></div> 
        <div class="flex justify-between w-full">
            <div class="flex flex-col cursor-pointer"  >
                <span class="text-dinesurf-green-600 font-medium text-[18px]">{{ dinelist.name }}</span>
                <span class="font-normal text-[14px]  text-dinesurf-black-800 ">By {{ dinelist.user?.displayname ?  dinelist.user?.displayname : dinelist.user.name }}</span>
                <span class="font-normal text-[10px]  text-dinesurf-black-800 "> {{ dinelist.likes?.length }} likes</span>
                <span class="font-normal text-[10px]  text-dinesurf-black-800">Last Updated {{ getTimeAgo(dinelist.updated_at)  }}</span>
            </div>
        </div>
    
    </a>
</template>


<script>
export default{
    props: ['list'],
    components: {},
    data() {
        return {
          image:this.list.vendors[0]?.banner_url,
          dinelist: this.list

        };
    },

    mounted(){
    }

}

</script>