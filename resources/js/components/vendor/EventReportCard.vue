<template>
      <div class="flex flex-col border md:w-[24%] w-full md:mt-0 mt-5 items-center justify-center p-5">
            <span class="font-bold mb-1  capitalize ">{{title}}</span>
            <span>{{value}}</span>

            <span class="text-[10px] mt-5  text-dinesurf-green-400">{{ cap }}</span>
      </div>
</template>

<script>
export default {
    props: ["title", "value", "cap"],
    


    mounted(){
        console.log("title:", this.title)
    }
}
</script>