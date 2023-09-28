<template>

<div>
  <div class="flex justify-between ">
      <span class="font-bold text-[18px]">{{ dinelist?.name}}</span>
      <span>{{ dinelist?.likes?.length }}  {{ dinelist?.likes?.length  > 1 ? 'likes' : 'like'}} </span>
   </div>
            
  <div  class=" 
      relative
      rounded-full  
    ">  
    <div class="bg-[#00000080] z-0 rounded-[14px] opacity-2 w-full h-full absolute top-0 bottom-0">

    </div>
  
 
   <div
                class="restaurant-card-img bg-grey   rounded-[14px] flex   p-3"
                :style="'background-image: url(' + image + ')'"

              
                >
                <a :href="route('view.dinelist', { slug: dinelist?.slug})" class="z-10 flex flex-col items-center justify-center w-full"> 
                     <span class="text-white  font-bold text-[24px] ">{{ dinelist?.name}}</span>
                     <!-- <span  class="text-white font-medium  text-[18px]" v-if="user.username">By {{  user.username }}</span> -->
                </a >

                <div class="z-10 flex items-center justify-center ">
                  
                    <jet-dropdown align="right" width="48">
                   <!-- $page.props.jetstream.managesProfilePhotos -->
                  <template #trigger>
                        <button>
                         <side-dots />
                        </button>
                  </template>

                  <template #content >
                       <div class="flex flex-col  justify-start items-start p-4 space-y-3">
                            <button @click="deleteDinelist" class="flex  space-x-2 items-center " v-if="this.isSameUser"> 
                              <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                 <path d="M7.2 3.6H10.8C10.8 3.12261 10.6104 2.66477 10.2728 2.32721C9.93523 1.98964 9.47739 1.8 9 1.8C8.52261 1.8 8.06477 1.98964 7.72721 2.32721C7.38964 2.66477 7.2 3.12261 7.2 3.6ZM5.4 3.6C5.4 2.64522 5.77928 1.72955 6.45442 1.05442C7.12955 0.379285 8.04522 0 9 0C9.95478 0 10.8705 0.379285 11.5456 1.05442C12.2207 1.72955 12.6 2.64522 12.6 3.6H17.1C17.3387 3.6 17.5676 3.69482 17.7364 3.8636C17.9052 4.03239 18 4.2613 18 4.5C18 4.73869 17.9052 4.96761 17.7364 5.1364C17.5676 5.30518 17.3387 5.4 17.1 5.4H16.3062L15.5088 14.706C15.4321 15.6046 15.021 16.4417 14.3567 17.0517C13.6924 17.6617 12.8233 18.0001 11.9214 18H6.0786C5.17672 18.0001 4.30765 17.6617 3.64333 17.0517C2.97902 16.4417 2.56786 15.6046 2.4912 14.706L1.6938 5.4H0.9C0.661305 5.4 0.432387 5.30518 0.263604 5.1364C0.0948211 4.96761 0 4.73869 0 4.5C0 4.2613 0.0948211 4.03239 0.263604 3.8636C0.432387 3.69482 0.661305 3.6 0.9 3.6H5.4ZM11.7 9C11.7 8.7613 11.6052 8.53239 11.4364 8.3636C11.2676 8.19482 11.0387 8.1 10.8 8.1C10.5613 8.1 10.3324 8.19482 10.1636 8.3636C9.99482 8.53239 9.9 8.7613 9.9 9V12.6C9.9 12.8387 9.99482 13.0676 10.1636 13.2364C10.3324 13.4052 10.5613 13.5 10.8 13.5C11.0387 13.5 11.2676 13.4052 11.4364 13.2364C11.6052 13.0676 11.7 12.8387 11.7 12.6V9ZM7.2 8.1C7.43869 8.1 7.66761 8.19482 7.8364 8.3636C8.00518 8.53239 8.1 8.7613 8.1 9V12.6C8.1 12.8387 8.00518 13.0676 7.8364 13.2364C7.66761 13.4052 7.43869 13.5 7.2 13.5C6.96131 13.5 6.73239 13.4052 6.5636 13.2364C6.39482 13.0676 6.3 12.8387 6.3 12.6V9C6.3 8.7613 6.39482 8.53239 6.5636 8.3636C6.73239 8.19482 6.96131 8.1 7.2 8.1ZM4.284 14.553C4.32234 15.0025 4.52805 15.4211 4.8604 15.7262C5.19275 16.0312 5.6275 16.2003 6.0786 16.2H11.9214C12.3722 16.1998 12.8065 16.0305 13.1385 15.7255C13.4704 15.4206 13.6759 15.0022 13.7142 14.553L14.499 5.4H3.501L4.2858 14.553H4.284Z" fill="#383838"/>
                              </svg>
                              <span class="font-medium text-[18px]">Remove&nbsp;DineList</span>
                            </button>

                            <button  @click="showEditDineList = true" class="flex  space-x-3 items-center " v-if="this.isSameUser"> 
                                   <svg width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M15.9853 4.08184C16.3869 3.77325 16.6079 3.36344 16.6079 2.9275C16.6079 2.49156 16.3869 2.08174 15.9853 1.77315L14.3002 0.478392C13.8986 0.169805 13.3652 0 12.7978 0C12.2304 0 11.6971 0.169805 11.2965 0.477575L0 9.13026V12.7345H4.68881L15.9853 4.08184ZM12.7978 1.63274L14.484 2.92668L12.7946 4.21981L11.1095 2.92586L12.7978 1.63274ZM2.125 11.1018V9.80785L9.605 4.07858L11.2901 5.37334L3.81119 11.1018H2.125ZM0 14.3673H17V16H0V14.3673Z" fill="#383838"/>
                                       </svg>

                                       <span class="font-medium text-[18px]">Edit DineList</span>

                                     
                            </button>

                            <button   class="flex space-x-2 items-center" @click="showShareCard = true"> 
  
                                      <svg width="17" height="16" viewBox="0 0 18 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                          <path d="M14.5161 10.625C16.4439 10.625 18 12.0487 18 13.8125C18 15.5763 16.4439 17 14.5161 17C12.5884 17 11.0323 15.5763 11.0323 13.8125C11.0323 13.5575 11.0671 13.3238 11.1368 13.0794L6.05032 10.6356C5.41161 11.2838 4.50581 11.6875 3.48387 11.6875C1.55613 11.6875 0 10.2638 0 8.5C0 6.73625 1.55613 5.3125 3.48387 5.3125C4.50581 5.3125 5.41161 5.72687 6.05032 6.36437L11.1368 3.92062C11.0671 3.67625 11.0323 3.4425 11.0323 3.1875C11.0323 1.42375 12.5884 0 14.5161 0C16.4439 0 18 1.42375 18 3.1875C18 4.95125 16.4439 6.375 14.5161 6.375C13.4942 6.375 12.5884 5.96063 11.9497 5.32313L6.86323 7.76688C7.00388 8.24715 7.00388 8.75285 6.86323 9.23312L11.9497 11.6769C12.5884 11.0287 13.4942 10.625 14.5161 10.625Z" fill="#383838"/>
                                        </svg>
                                        <span class="font-medium text-[18px]">Share DineList</span>

                            </button>
                       </div>
                  </template>
                  
                </jet-dropdown>
                    
                </div>

            </div>


            <div v-if="isDeleting" class="absolute top-0 flex flex-col space-y-2 items-center justify-center z-20  bg-[#00000080] h-full w-full">
              <span class="fa fa-spinner  text-4xl  text-white fa-spin"> </span>
              <span class="text-white font-bold">Deleting... {{  dinelist.name }}</span>

           </div>
        </div>
      </div>


   <card-modal
        :showing="showEditDineList"
        @close="showEditDineList = false"
        :key="'cardModal1'"
      >
    <EditDineList  :list="dinelist" />
</card-modal>






   <card-modal
        :showing="showShareCard"
        @close="showShareCard = false"
        :key="'cardModal1'"
      >
      <div class="my-5">
          <span class="font-bold text-2xl">Share {{ dinelist.name }}:</span>
      </div>

          <custom-share-button  :link_text="'See  this  dinelist  with amazing restaurants to dine at'" :social_link="route('view.dinelist', {id : dinelist.id })" /> 

   </card-modal>




</template>



<script>

import SideDots from '../Jetstream/Vendor_icons/SideDots.vue';
import JetDropdown from "@/Jetstream/Dropdown.vue";
import EditDineList from './EditDineList.vue';
import CustomShareButton from './CustomShareButton.vue';
import axios from 'axios';
export default {
   props: ['list', 'user', 'isSameUser'],
   components:{
    SideDots,
    JetDropdown,
    EditDineList,
    CustomShareButton
   },
    data() {
    return {
      showShareCard:false,
      showEditDineList: false,
      dinelist: this.list,
      image: this.list?.vendors?.length > 0 ? this.list?.vendors[0]?.banner_url : null ,
      isDeleting:false
    };
  },

  methods:{

      OnClosingModal(){
             this.showEditDineList = false;
             window.location.reload();
      },
       deleteDinelist(){
        const id = this.list.id;
        this.isDeleting = true;
        axios.delete(route('delete.dinelist', {id }))
        .then((result) => {
          toastr.success(result.data.message);
          setTimeout(
                function () {
                  window.location.reload();
                },
                500,
              );
        })
        .catch((error)=>{
          console.log("error:", error)
        }).finally(() =>  this.isDeleting = false  )

       }
  },
  
    mounted() {
      // console.log("List:", this.list);
    },
};


</script>