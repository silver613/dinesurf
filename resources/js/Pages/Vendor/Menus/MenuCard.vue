<template >
    <div
      class="
       bg-white
        md:m-3
        mt-3
       rounded-lg
      shadow-md
      border
      md:w-auto
      w-full
      cursor-move
      ">

     <div class="flex flex-col justify-center  w-auto ">
              <!-- <div
                class="
                       
                    md:w-[260px]   
                    w-full
                    bg-gray-300
                "
                style=" height: 110px;
    background-color:gray;"
                >
                <img
                :src="this.getRandomImage()"
                    class="imageN"
                    draggable="true"
                />
                </div> -->

                <div
          class="restaurant-card-img restaurant-card-img-menu bg-grey   rounded-t-md"
          :style="'background-image: url(' + this.getRandomImage() + ')'"
        ></div>

        <div class="p-3"   >

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
                {{ this.menuData.name }}
              </h4>
                   
              <div   v-if="this.menuData.description !== null">
                   <p  class="text-sm md:w-64 mt-2 " v-html="this.menuData.description"> </p>
              </div>
              <!-- <span>{{getRandomImage}}</span> -->
           
           
                    <div class="flex justify-between   mt-3">
                         <div class="flex ">
                            <svg width="15" height="15" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 0C4.486 0 0 4.486 0 10C0 15.514 4.486 20 10 20C15.514 20 20 15.514 20 10C20 4.486 15.514 0 10 0ZM10 18C5.589 18 2 14.411 2 10C2 5.589 5.589 2 10 2C14.411 2 18 5.589 18 10C18 14.411 14.411 18 10 18Z" fill="black" fill-opacity="0.66"/>
                            <path d="M11 5H9V11H15V9H11V5Z" fill="black" fill-opacity="0.66"/>
                            </svg>
                            <span class="ml-1 text-xs text-gray-600">
                                Last updated {{new Date(this.menuData.updated_at).toDateString()}}
                            </span>

                         </div>

                         <div class="flex">
                               <a  :href="route('vendors.edit-menu-page') + '/' + this.menuData.id" class="mr-3 bg-white shadow-md  p-1">
                                <svg width="14" height="14" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                   <path d="M3.28486 13.4219C3.32588 13.4219 3.36689 13.4178 3.40791 13.4116L6.85732 12.8066C6.89834 12.7984 6.9373 12.78 6.96602 12.7492L15.6593 4.05596C15.6783 4.03698 15.6934 4.01445 15.7037 3.98964C15.714 3.96483 15.7193 3.93824 15.7193 3.91138C15.7193 3.88452 15.714 3.85792 15.7037 3.83311C15.6934 3.80831 15.6783 3.78577 15.6593 3.7668L12.2509 0.356348C12.2119 0.317383 12.1606 0.296875 12.1053 0.296875C12.0499 0.296875 11.9986 0.317383 11.9597 0.356348L3.26641 9.04961C3.23564 9.08037 3.21719 9.11728 3.20898 9.1583L2.604 12.6077C2.58405 12.7176 2.59118 12.8306 2.62477 12.9371C2.65836 13.0436 2.7174 13.1403 2.79678 13.2188C2.93213 13.3501 3.10234 13.4219 3.28486 13.4219ZM4.66709 9.84531L12.1053 2.40918L13.6085 3.9124L6.17031 11.3485L4.34717 11.6705L4.66709 9.84531ZM16.0469 15.1445H0.953125C0.590137 15.1445 0.296875 15.4378 0.296875 15.8008V16.5391C0.296875 16.6293 0.370703 16.7031 0.460938 16.7031H16.5391C16.6293 16.7031 16.7031 16.6293 16.7031 16.5391V15.8008C16.7031 15.4378 16.4099 15.1445 16.0469 15.1445Z" fill="black"/>
                                </svg>

                               </a>

                               <button class="shadow-md  bg-white  p-1"  @click="runAction()">
                                    <svg width="16" height="16" viewBox="0 0 20 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M8 4.21631H12C12 3.68588 11.7893 3.17717 11.4142 2.80209C11.0391 2.42702 10.5304 2.21631 10 2.21631C9.46957 2.21631 8.96086 2.42702 8.58579 2.80209C8.21071 3.17717 8 3.68588 8 4.21631ZM6.5 4.21631C6.5 3.75668 6.59053 3.30156 6.76642 2.87692C6.94231 2.45228 7.20012 2.06644 7.52513 1.74143C7.85013 1.41643 8.23597 1.15862 8.66061 0.98273C9.08525 0.806839 9.54037 0.716309 10 0.716309C10.4596 0.716309 10.9148 0.806839 11.3394 0.98273C11.764 1.15862 12.1499 1.41643 12.4749 1.74143C12.7999 2.06644 13.0577 2.45228 13.2336 2.87692C13.4095 3.30156 13.5 3.75668 13.5 4.21631H19.25C19.4489 4.21631 19.6397 4.29533 19.7803 4.43598C19.921 4.57663 20 4.7674 20 4.96631C20 5.16522 19.921 5.35599 19.7803 5.49664C19.6397 5.63729 19.4489 5.71631 19.25 5.71631H17.93L16.76 17.8273C16.6702 18.7553 16.238 19.6166 15.5477 20.2431C14.8573 20.8697 13.9583 21.2167 13.026 21.2163H6.974C6.04186 21.2164 5.1431 20.8694 4.45295 20.2428C3.7628 19.6162 3.33073 18.7551 3.241 17.8273L2.07 5.71631H0.75C0.551088 5.71631 0.360322 5.63729 0.21967 5.49664C0.0790175 5.35599 0 5.16522 0 4.96631C0 4.7674 0.0790175 4.57663 0.21967 4.43598C0.360322 4.29533 0.551088 4.21631 0.75 4.21631H6.5ZM8.5 8.96631C8.5 8.7674 8.42098 8.57663 8.28033 8.43598C8.13968 8.29533 7.94891 8.21631 7.75 8.21631C7.55109 8.21631 7.36032 8.29533 7.21967 8.43598C7.07902 8.57663 7 8.7674 7 8.96631V16.4663C7 16.6652 7.07902 16.856 7.21967 16.9966C7.36032 17.1373 7.55109 17.2163 7.75 17.2163C7.94891 17.2163 8.13968 17.1373 8.28033 16.9966C8.42098 16.856 8.5 16.6652 8.5 16.4663V8.96631ZM12.25 8.21631C12.0511 8.21631 11.8603 8.29533 11.7197 8.43598C11.579 8.57663 11.5 8.7674 11.5 8.96631V16.4663C11.5 16.6652 11.579 16.856 11.7197 16.9966C11.8603 17.1373 12.0511 17.2163 12.25 17.2163C12.4489 17.2163 12.6397 17.1373 12.7803 16.9966C12.921 16.856 13 16.6652 13 16.4663V8.96631C13 8.7674 12.921 8.57663 12.7803 8.43598C12.6397 8.29533 12.4489 8.21631 12.25 8.21631Z" fill="#B01313"/>
                                     </svg>

                               </button>
                         </div>
        
                    </div>
                </div>





            </div>
      </div>
 
  </template>
  <script>
  export default {
    props: ["menu", "onDelete"],
    data() {
      return {
        menuData: this.menu,
        showVideoModal: false,
        images:['/images/menu_images/1.jpg','/images/menu_images/2.jpg','/images/menu_images/3.jpg','/images/menu_images/4.jpg', '/images/menu_images/5.jpg', '/images/menu_images/6.jpg']
      };
    },

    methods:{
    getRandomImage() {
      if(this.menu.image  != null){
        return this.menu.image_url;
      }
          var num = Math.floor(Math.random() * 6);
          var num = Math.floor( Math.random() * this.images.length);
          var img = this.images[num];
          return img;
      },

     runAction(){
      this.$emit("onDelete", {
        action: 'delete',
        ids: [this.menuData.id]
      });
     }
    },
  
    mounted() {

    },
  };
  </script>
  <style>
  .imageN{
    object-fit: cover;
    width: 100% !important;
    height:100% !important;
  }
  .imageContainer{
    /* width: 260px; */
    width: 260px;
    height: 130px;
    background-color:gray;
  }
</style>