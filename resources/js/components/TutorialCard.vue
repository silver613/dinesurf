<template v-if="tutorialData">
    <div
      class="
       bg-white
        m-3
       h-[19rem]
       rounded-lg
      shadow-md
      border
      "
    >

          <div class="flex flex-col justify-center  w-auto p-4">
            <button  @click="message()" >
              <iframe
                
                :src="tutorialData.video_url"
                title="YouTube video player"
                frameborder="0"
               
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen
              ></iframe>
          </button>

              <h4
                class="
                  text-medium text-blogtext-dark
                  font-bold
                  clamp-text
                  max-w-90
                  mt-4
                  font-sans
                "
              >
                {{ tutorialData.title }}
              </h4>

              <p  class="text-sm w-64 mt-2 " v-html="tutorialData.description">
            
              </p>
            </div>

             

           
          <!-- </div> -->
  
           
  
       
   
        <jet-modal
          :show="showVideoModal"
          @close="showVideoModal = false"
          :key="tutorialData.id"
        >
          <div class="p-6 w-full">
            <h3 class="text-lg mb-2 font-medium text-gray-900 capitalize">
              {{ tutorialData.title }} Tour Video
            </h3>
            <div class="flex flex-col justify-center items-center w-full">
              <iframe
                class="youtube_video"
                :src="tutorialData.video_link"
                title="YouTube video player"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen
              ></iframe>
            </div>
          </div>
        </jet-modal>
      </div>
 
  </template>
  <script>
  export default {
    props: ["tutorial"],
    data() {
      return {
        tutorialData: this.tutorial,
        showVideoModal: false,
      };
    },

    methods:{
      message(){
        alert("Hey there");
      }
    },
  
    mounted() {
    },
  };
  </script>
  