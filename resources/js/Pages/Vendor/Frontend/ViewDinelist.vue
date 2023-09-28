<template>
    <app-layout>
        <div class=" h-full w-full ">
            <div class="bg-white w-full  flex lg:flex-row flex-col h-full">
                     <div  class="flex flex-col lg:md:shadow-side-menu rounded-md w-auto lg:md:mb-auto lg:md:mt-10 lg:md:ml-10 lg:p-8 p-5 pb-0">
                          <a  :href="route('dinelist.profile', { slug: user.slug})">
                                         <svg width="193" height="36" viewBox="0 0 193 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <circle cx="18" cy="18" r="18" fill="#9DC64E"/>
                                            <path d="M20.7066 11.636C20.894 11.8236 20.9993 12.0779 20.9993 12.343C20.9993 12.6082 20.894 12.8625 20.7066 13.05L15.7566 18L20.7066 22.95C20.8887 23.1387 20.9895 23.3913 20.9872 23.6535C20.985 23.9156 20.8798 24.1665 20.6944 24.3519C20.509 24.5373 20.2582 24.6424 19.996 24.6447C19.7338 24.647 19.4812 24.5462 19.2926 24.364L13.6356 18.707C13.4481 18.5195 13.3428 18.2652 13.3428 18C13.3428 17.7349 13.4481 17.4806 13.6356 17.293L19.2926 11.636C19.4801 11.4486 19.7344 11.3433 19.9996 11.3433C20.2647 11.3433 20.519 11.4486 20.7066 11.636Z" fill="white"/>
                                            <path d="M59.548 26V14.12H64.75C66.19 14.12 67.252 14.402 67.936 14.966C68.62 15.53 68.962 16.328 68.962 17.36C68.962 17.996 68.77 18.56 68.386 19.052C68.002 19.544 67.438 19.856 66.694 19.988V20.006C67.522 20.126 68.158 20.438 68.602 20.942C69.046 21.446 69.268 22.046 69.268 22.742C69.268 23.762 68.896 24.56 68.152 25.136C67.42 25.712 66.346 26 64.93 26H59.548ZM61.96 24.128H64.714C65.386 24.128 65.884 23.99 66.208 23.714C66.532 23.438 66.694 23.054 66.694 22.562C66.694 22.058 66.532 21.674 66.208 21.41C65.884 21.146 65.386 21.014 64.714 21.014H61.96V24.128ZM61.96 19.106H64.426C65.734 19.106 66.388 18.596 66.388 17.576C66.388 16.556 65.734 16.046 64.426 16.046H61.96V19.106ZM76.5198 20.258C76.5198 19.742 76.3698 19.34 76.0698 19.052C75.7818 18.764 75.3978 18.62 74.9178 18.62C74.4738 18.62 74.0838 18.734 73.7478 18.962C73.4118 19.19 73.1658 19.568 73.0098 20.096L70.9038 19.448C71.1078 18.656 71.5518 18.02 72.2358 17.54C72.9318 17.06 73.8618 16.82 75.0258 16.82C75.8178 16.82 76.5078 16.946 77.0958 17.198C77.6838 17.438 78.1398 17.804 78.4638 18.296C78.7998 18.788 78.9678 19.406 78.9678 20.15V23.804C78.9678 24.26 79.1838 24.488 79.6158 24.488C79.8198 24.488 80.0118 24.458 80.1918 24.398L80.0478 25.928C79.7358 26.084 79.3458 26.162 78.8778 26.162C78.4578 26.162 78.0798 26.09 77.7438 25.946C77.4078 25.802 77.1438 25.586 76.9518 25.298C76.7598 24.998 76.6638 24.626 76.6638 24.182V24.11L77.0958 24.038C77.0238 24.494 76.8258 24.884 76.5018 25.208C76.1778 25.52 75.7818 25.76 75.3138 25.928C74.8578 26.096 74.3838 26.18 73.8918 26.18C73.2798 26.18 72.7518 26.09 72.3078 25.91C71.8638 25.718 71.5218 25.442 71.2817 25.082C71.0418 24.722 70.9218 24.278 70.9218 23.75C70.9218 23.078 71.1378 22.532 71.5698 22.112C72.0018 21.68 72.6198 21.386 73.4238 21.23L76.8078 20.546L76.7898 22.076L74.6298 22.508C74.2218 22.592 73.9098 22.706 73.6938 22.85C73.4898 22.982 73.3878 23.204 73.3878 23.516C73.3878 23.816 73.4958 24.044 73.7118 24.2C73.9398 24.356 74.2098 24.434 74.5218 24.434C74.7738 24.434 75.0198 24.404 75.2598 24.344C75.4998 24.284 75.7158 24.188 75.9078 24.056C76.0998 23.924 76.2498 23.744 76.3578 23.516C76.4658 23.288 76.5198 23.012 76.5198 22.688V20.258ZM85.7765 16.82C86.5805 16.82 87.2585 16.94 87.8105 17.18C88.3745 17.408 88.8245 17.726 89.1605 18.134C89.5085 18.53 89.7545 18.974 89.8985 19.466L87.5945 20.294C87.4745 19.754 87.2705 19.346 86.9825 19.07C86.6945 18.794 86.3045 18.656 85.8125 18.656C85.3565 18.656 84.9665 18.764 84.6425 18.98C84.3185 19.184 84.0725 19.502 83.9045 19.934C83.7365 20.354 83.6525 20.882 83.6525 21.518C83.6525 22.154 83.7365 22.682 83.9045 23.102C84.0845 23.522 84.3365 23.834 84.6605 24.038C84.9965 24.242 85.3865 24.344 85.8305 24.344C86.1905 24.344 86.4965 24.284 86.7485 24.164C87.0005 24.032 87.2045 23.846 87.3605 23.606C87.5285 23.366 87.6425 23.078 87.7025 22.742L89.9345 23.462C89.8025 24.002 89.5565 24.476 89.1965 24.884C88.8365 25.292 88.3745 25.61 87.8105 25.838C87.2465 26.066 86.5925 26.18 85.8485 26.18C84.9245 26.18 84.1085 25.994 83.4005 25.622C82.6925 25.25 82.1465 24.716 81.7625 24.02C81.3785 23.324 81.1865 22.484 81.1865 21.5C81.1865 20.516 81.3785 19.676 81.7625 18.98C82.1465 18.284 82.6865 17.75 83.3825 17.378C84.0785 17.006 84.8765 16.82 85.7765 16.82ZM101.013 17L96.8547 21.338L96.3687 21.572L93.6687 24.47L93.6327 21.842L98.2047 17H101.013ZM94.3167 13.058V26H91.8507V13.058H94.3167ZM97.1967 19.862L101.229 26H98.4387L95.5047 21.302L97.1967 19.862ZM117.143 14.12V16.118H113.165V26H110.681V16.118H106.685V14.12H117.143ZM121.25 16.82C122.162 16.82 122.96 17.006 123.644 17.378C124.34 17.75 124.88 18.284 125.264 18.98C125.66 19.676 125.858 20.516 125.858 21.5C125.858 22.472 125.66 23.312 125.264 24.02C124.88 24.716 124.34 25.25 123.644 25.622C122.96 25.994 122.162 26.18 121.25 26.18C120.35 26.18 119.552 25.994 118.856 25.622C118.16 25.25 117.614 24.716 117.218 24.02C116.834 23.312 116.642 22.472 116.642 21.5C116.642 20.516 116.834 19.676 117.218 18.98C117.614 18.284 118.16 17.75 118.856 17.378C119.552 17.006 120.35 16.82 121.25 16.82ZM121.25 18.656C120.794 18.656 120.404 18.764 120.08 18.98C119.768 19.184 119.528 19.496 119.36 19.916C119.192 20.336 119.108 20.864 119.108 21.5C119.108 22.136 119.192 22.664 119.36 23.084C119.528 23.504 119.768 23.822 120.08 24.038C120.404 24.242 120.794 24.344 121.25 24.344C121.694 24.344 122.072 24.242 122.384 24.038C122.708 23.822 122.954 23.504 123.122 23.084C123.29 22.664 123.374 22.136 123.374 21.5C123.374 20.864 123.29 20.336 123.122 19.916C122.954 19.496 122.708 19.184 122.384 18.98C122.072 18.764 121.694 18.656 121.25 18.656ZM138.661 14.12C139.573 14.12 140.353 14.276 141.001 14.588C141.649 14.888 142.147 15.326 142.495 15.902C142.855 16.466 143.035 17.15 143.035 17.954C143.035 18.758 142.855 19.448 142.495 20.024C142.147 20.588 141.649 21.026 141.001 21.338C140.353 21.638 139.573 21.788 138.661 21.788H136.159V26H133.675V14.12H138.661ZM138.283 19.826C139.027 19.826 139.585 19.67 139.957 19.358C140.329 19.034 140.515 18.566 140.515 17.954C140.515 17.33 140.329 16.862 139.957 16.55C139.585 16.238 139.027 16.082 138.283 16.082H136.159V19.826H138.283ZM145.06 26V17H147.202L147.364 18.764C147.604 18.14 147.952 17.66 148.408 17.324C148.876 16.988 149.458 16.82 150.154 16.82C150.358 16.82 150.544 16.838 150.712 16.874C150.88 16.91 151.018 16.958 151.126 17.018L150.838 19.088C150.718 19.04 150.568 19.004 150.388 18.98C150.22 18.956 149.998 18.944 149.722 18.944C149.362 18.944 149.014 19.034 148.678 19.214C148.342 19.382 148.066 19.646 147.85 20.006C147.634 20.354 147.526 20.798 147.526 21.338V26H145.06ZM156.582 16.82C157.494 16.82 158.292 17.006 158.976 17.378C159.672 17.75 160.212 18.284 160.596 18.98C160.992 19.676 161.19 20.516 161.19 21.5C161.19 22.472 160.992 23.312 160.596 24.02C160.212 24.716 159.672 25.25 158.976 25.622C158.292 25.994 157.494 26.18 156.582 26.18C155.682 26.18 154.884 25.994 154.188 25.622C153.492 25.25 152.946 24.716 152.55 24.02C152.166 23.312 151.974 22.472 151.974 21.5C151.974 20.516 152.166 19.676 152.55 18.98C152.946 18.284 153.492 17.75 154.188 17.378C154.884 17.006 155.682 16.82 156.582 16.82ZM156.582 18.656C156.126 18.656 155.736 18.764 155.412 18.98C155.1 19.184 154.86 19.496 154.692 19.916C154.524 20.336 154.44 20.864 154.44 21.5C154.44 22.136 154.524 22.664 154.692 23.084C154.86 23.504 155.1 23.822 155.412 24.038C155.736 24.242 156.126 24.344 156.582 24.344C157.026 24.344 157.404 24.242 157.716 24.038C158.04 23.822 158.286 23.504 158.454 23.084C158.622 22.664 158.706 22.136 158.706 21.5C158.706 20.864 158.622 20.336 158.454 19.916C158.286 19.496 158.04 19.184 157.716 18.98C157.404 18.764 157.026 18.656 156.582 18.656ZM167.179 12.878C167.599 12.878 167.977 12.926 168.313 13.022C168.661 13.106 168.919 13.214 169.087 13.346L168.745 14.984C168.613 14.9 168.463 14.834 168.295 14.786C168.127 14.738 167.929 14.714 167.701 14.714C167.233 14.714 166.861 14.822 166.585 15.038C166.309 15.242 166.171 15.596 166.171 16.1V17.342L166.279 17.828V26H163.813V15.92C163.813 15.416 163.897 14.972 164.065 14.588C164.233 14.204 164.467 13.886 164.767 13.634C165.067 13.382 165.421 13.196 165.829 13.076C166.249 12.944 166.699 12.878 167.179 12.878ZM169.015 17V18.836H162.247V17H169.015ZM171.733 15.704C171.217 15.704 170.833 15.596 170.581 15.38C170.329 15.152 170.203 14.822 170.203 14.39C170.203 13.946 170.329 13.616 170.581 13.4C170.833 13.172 171.217 13.058 171.733 13.058C172.249 13.058 172.633 13.172 172.885 13.4C173.137 13.616 173.263 13.946 173.263 14.39C173.263 14.822 173.137 15.152 172.885 15.38C172.633 15.596 172.249 15.704 171.733 15.704ZM172.957 17V26H170.491V17H172.957ZM178.005 13.058V23.228C178.005 23.648 178.077 23.942 178.221 24.11C178.365 24.278 178.605 24.362 178.941 24.362C179.145 24.362 179.313 24.35 179.445 24.326C179.577 24.29 179.745 24.242 179.949 24.182L179.733 25.802C179.529 25.922 179.271 26.012 178.959 26.072C178.659 26.144 178.359 26.18 178.059 26.18C177.183 26.18 176.541 25.97 176.133 25.55C175.737 25.118 175.539 24.434 175.539 23.498V13.058H178.005ZM185.499 26.18C184.551 26.18 183.723 25.994 183.015 25.622C182.319 25.25 181.779 24.716 181.395 24.02C181.011 23.324 180.819 22.484 180.819 21.5C180.819 20.516 181.011 19.676 181.395 18.98C181.779 18.284 182.313 17.75 182.997 17.378C183.693 17.006 184.485 16.82 185.373 16.82C186.285 16.82 187.059 17.006 187.695 17.378C188.331 17.738 188.817 18.236 189.153 18.872C189.489 19.496 189.657 20.21 189.657 21.014C189.657 21.254 189.651 21.482 189.639 21.698C189.627 21.914 189.609 22.106 189.585 22.274H182.403V20.564H188.505L187.317 20.996C187.317 20.24 187.143 19.658 186.795 19.25C186.459 18.842 185.979 18.638 185.355 18.638C184.899 18.638 184.503 18.746 184.167 18.962C183.843 19.178 183.597 19.502 183.429 19.934C183.261 20.354 183.177 20.888 183.177 21.536C183.177 22.172 183.267 22.7 183.447 23.12C183.639 23.528 183.903 23.834 184.239 24.038C184.587 24.242 185.001 24.344 185.481 24.344C186.009 24.344 186.435 24.242 186.759 24.038C187.083 23.834 187.335 23.552 187.515 23.192L189.477 23.948C189.285 24.416 188.991 24.818 188.595 25.154C188.211 25.49 187.749 25.748 187.209 25.928C186.681 26.096 186.111 26.18 185.499 26.18Z" fill="#77943F"/>
                                         </svg>

                          </a>

                          <div class="lg:md:w-80 mt-10 mb-10">

                             <div  class="flex justify-between ">
                               <div class="flex flex-col ">
                                  <div class="flex items-center space-x-2">
                                     <h1 class="text-dinesurf-red-400 font-light text-[23px]">{{ dinelist?.name }} </h1>
                                     <span v-if="dinelist.isPrivate" class="fa fa-lock text-gray-800" id="privateInfo" ></span>
                                  </div>
                                  
                                   <span class="font-bold"  v-if="user?.username">By  <span class="font-bold text-dinesurf-green-400">@{{ user.username }}</span> </span>
                                   <span>Last updated {{  getTimeAgo(dinelist.updated_at) }}</span>
                               </div>
                              <div v-if="this.isSameUser">
                                         <jet-dropdown align="right" width="48">
                                            <!-- $page.props.jetstream.managesProfilePhotos -->
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
                                                <div class="flex flex-col  justify-end items-end p-4 space-y-3">
                                                        <button @click="showEditDineList = true" class="flex  space-x-2 items-center "> 
                                                            <span class="font-medium text-[18px]">Edit Dinelist</span>

                                                                    <svg width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M15.9853 4.08184C16.3869 3.77325 16.6079 3.36344 16.6079 2.9275C16.6079 2.49156 16.3869 2.08174 15.9853 1.77315L14.3002 0.478392C13.8986 0.169805 13.3652 0 12.7978 0C12.2304 0 11.6971 0.169805 11.2965 0.477575L0 9.13026V12.7345H4.68881L15.9853 4.08184ZM12.7978 1.63274L14.484 2.92668L12.7946 4.21981L11.1095 2.92586L12.7978 1.63274ZM2.125 11.1018V9.80785L9.605 4.07858L11.2901 5.37334L3.81119 11.1018H2.125ZM0 14.3673H17V16H0V14.3673Z" fill="#383838"/>
                                                                    </svg>
                                                        </button>

                                                        <button  @click="showShareCard = true" class="flex space-x-2 items-center"> 
                                                            <span class="font-medium text-[18px]">Share Dinelist</span>

                                                                <svg width="17" height="16" viewBox="0 0 18 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M14.5161 10.625C16.4439 10.625 18 12.0487 18 13.8125C18 15.5763 16.4439 17 14.5161 17C12.5884 17 11.0323 15.5763 11.0323 13.8125C11.0323 13.5575 11.0671 13.3238 11.1368 13.0794L6.05032 10.6356C5.41161 11.2838 4.50581 11.6875 3.48387 11.6875C1.55613 11.6875 0 10.2638 0 8.5C0 6.73625 1.55613 5.3125 3.48387 5.3125C4.50581 5.3125 5.41161 5.72687 6.05032 6.36437L11.1368 3.92062C11.0671 3.67625 11.0323 3.4425 11.0323 3.1875C11.0323 1.42375 12.5884 0 14.5161 0C16.4439 0 18 1.42375 18 3.1875C18 4.95125 16.4439 6.375 14.5161 6.375C13.4942 6.375 12.5884 5.96063 11.9497 5.32313L6.86323 7.76688C7.00388 8.24715 7.00388 8.75285 6.86323 9.23312L11.9497 11.6769C12.5884 11.0287 13.4942 10.625 14.5161 10.625Z" fill="#383838"/>
                                                                 </svg>

                                                                    <!-- <custom-share-button /> -->

                                                        </button>
                                                </div>
                                            </template>
                                         </jet-dropdown>
                               </div>
                            </div>

                            <div  class="flex space-x-4  mt-8 " >                
                                <!-- share -->
                                <div class="flex flex-col space-y-1 items-center cursor-pointer" @click="showShareCard = true">
                                       <div>
                                            <svg width="23" height="24" viewBox="0 0 23 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                               <path d="M16.6748 14.0161C18.5838 14.0161 20.1248 15.5813 20.1248 17.5203C20.1248 19.4593 18.5838 21.0245 16.6748 21.0245C14.7658 21.0245 13.2248 19.4593 13.2248 17.5203C13.2248 17.2399 13.2593 16.983 13.3283 16.7143L8.29131 14.0278C7.65881 14.7403 6.76181 15.1841 5.7498 15.1841C3.8408 15.1841 2.2998 13.6189 2.2998 11.68C2.2998 9.74097 3.8408 8.17576 5.7498 8.17576C6.76181 8.17576 7.65881 8.63131 8.29131 9.33215L13.3283 6.6456C13.2593 6.37695 13.2248 6.11997 13.2248 5.83964C13.2248 3.90065 14.7658 2.33545 16.6748 2.33545C18.5838 2.33545 20.1248 3.90065 20.1248 5.83964C20.1248 7.77862 18.5838 9.34383 16.6748 9.34383C15.6628 9.34383 14.7658 8.88828 14.1333 8.18744L9.0963 10.874C9.23559 11.402 9.23559 11.9579 9.0963 12.4859L14.1333 15.1725C14.7658 14.4599 15.6628 14.0161 16.6748 14.0161Z" fill="#383838"/>
                                            </svg>
                                       </div>
                                       <span class="font-normal text-[10px]">Share List</span>
                                </div>

                                  <!-- Invite  Collaborators -->
                                  <div  v-if="this.isSameUser">
                                     


                                       <jet-dropdown align="right" width="48">
                                            <!-- $page.props.jetstream.managesProfilePhotos -->
                                            <template #trigger>
                                                <button class="flex flex-col space-y-1  items-center cursor-pointer" >
                                                    <svg width="23" height="23" viewBox="0 0 23 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <circle cx="11.5" cy="11.5" r="11.5" fill="#9DC64E"/>
                                                                        <g clip-path="url(#clip0_5278_24056)">
                                                                        <path d="M11.4592 11.4806C12.4367 11.4806 13.2187 10.7474 13.2187 9.85128C13.2187 8.95515 12.4367 8.22196 11.4592 8.22196C10.4817 8.22196 9.69968 8.95515 9.69968 9.85128C9.69968 10.7474 10.4817 11.4806 11.4592 11.4806ZM11.4592 12.5668C9.84918 12.5668 8.54968 11.3503 8.54968 9.85128C8.54968 8.3523 9.84918 7.13574 11.4592 7.13574C13.0692 7.13574 14.3687 8.3523 14.3687 9.85128C14.3687 11.3503 13.0692 12.5668 11.4592 12.5668ZM7.43418 16.9117H15.4842V16.1893C15.4842 15.2389 14.1559 14.2559 11.4592 14.2559C8.76243 14.2559 7.43418 15.2389 7.43418 16.1893V16.9117ZM11.4592 13.1697C15.2887 13.1697 16.6342 14.9782 16.6342 16.1893V17.9979H6.28418V16.1893C6.28418 14.9782 7.62968 13.1697 11.4592 13.1697Z" fill="white"/>
                                                                        </g>
                                                                        <path d="M5.49 6.28V11.34H4.56V6.28H5.49ZM7.48 8.36V9.25H2.56V8.36H7.48Z" fill="white"/>
                                                                        <defs>
                                                                        <clipPath id="clip0_5278_24056">
                                                                        <rect x="5.70898" y="7.13574" width="11.5" height="10.8622" rx="5.43108" fill="white"/>
                                                                        </clipPath>
                                                                        </defs>
                                                        </svg>
                                                        <span class="font-normal text-[10px]">Invite  Collaborators</span>

                                                 </button>
                                            </template>

                                            <template #content >
                                                <div class="flex flex-col  justify-center items-center p-4 space-y-3">
                                                       <span>Coming soon!</span>
                                                </div>
                                            </template>
                                         </jet-dropdown>
                                </div>

                                      <!-- Likes -->
                                 <div class="flex flex-col space-y-1  items-center cursor-pointer">
                                      <like-dinelist   :model="dinelist" />
                                  </div>

                                  <a v-if="dinelist?.likes?.length > 0" class="flex flex-col space-y-1 items-center cursor-pointer" :href="route('likedby.dinelist', {slug: dinelist.slug })">
                                       <div>
                                        <i class="fa fa-bar-chart"></i>
                                       </div>
                                       <span class="font-normal text-[10px]">Insights</span>
                                    </a>
                                  <!-- <i class="fas fa-analytics"></i> -->
                            </div>
                              
                          </div>
                    </div>

                    <div class="lg:mt-10  w-full lg:px-10 min-h-[30rem]  flex flex-col   px-5 ">
                        <div class="lg:mt-5 flex flex-col space-y-5  mb-5"  v-if="list.vendors?.length > 0">

                            <div class="my-3  flex items-center justify-center" v-if="this.isSameUser">
                                <a :href="route('add.to.dinelist', { id:dinelist.id})"  class="bg-dinesurf-green-400 rounded-full p-4 px-8 font-bold text-[16px] text-white" >Add to DineList</a>
                            </div>

                            <span class="text-dinesurf-green-400 font-semibold text-[24px]"> Restaurants</span>
                         
                                <template   v-for="(vendor, index) in list.vendors" :key="index" >
                                    <dinelist-vendor-card :isSameUser="this.isSameUser" :vendor="vendor" :dinelist="dinelist" :isDinelistvendor="true" />
                                </template>      
                              </div>
                              
                          <div v-else>
                                    <div class="flex  flex-col justify-center items-center  space-y-4" v-if="this.isSameUser">
                                        <span class="font-semibold  text-[32px]">Let’s start building your DineList </span>
                                        <a :href="route('add.to.dinelist', { id:dinelist.id})"  class="bg-dinesurf-green-400 rounded-full p-4 px-8 font-bold text-[16px] text-white" >Add to DineList</a>
                                    </div>
                                    <div class="flex justify-center items-center " v-else>
                                        <!-- <span class="font-semibold  text-[32px]">No restaurant found! </span> -->
                                        <empty-space 
                                        :title="'Oops!... Its Empty'"
                                        :description="'The owner has not added any restaurants to this list'"   
                                        :image="'/images/empty_images/4.png'"
                                        :noButton="true"
                                            />
                       
                                    </div>
                          <!-- isSameUser -->
                          </div>

                     

                          <!-- recommeended restaurants -->
                          <div class="mt-10"  v-if="this.isSameUser">
                                 <span class="text-dinesurf-green-400 font-semibold text-[24px]">Recommended Restaurants</span>
                         
                              <div class="mt-5 flex flex-col space-y-5  mb-5"  v-if="this.vendors?.length > 0">
                                <template   v-for="(item, index) in this.vendors" :key="index" >
                                   <dinelist-vendor-card :vendor="item" :dinelist="dinelist" :isSameUser="this.isSameUser"  />
                                </template>      
                              </div>
                               <div class="w-full h-[10rem] text-center flex items-center justify-center mt-10" v-else>
                               <empty-space 
                                        :title="'Oops!... Its Empty'"
                                        :description="'we are unable to load any restaurant at the moment'"   
                                        :image="'/images/empty_images/4.png'"
                                        :noButton="true"
                                            />
                       
                              </div>
                          </div>

                    </div>
            </div>


          
            <div class="w-full">
                 <Footer /> 
            </div>
        
        </div>
    </app-layout>





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

          <custom-share-button  :link_text="'See  this  dinelist  with amazing restaurants to dine at'" :social_link="route('view.dinelist', {slug : dinelist.slug })" /> 

   </card-modal>

</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import Footer from "@/components/Footer.vue";
import JetDropdownLink from "@/Jetstream/DropdownLink.vue";
import Favorite from "@/components/favourite.vue"
import DinelistCard from "@/components/DinelistCard.vue";
import JetDropdown from "@/Jetstream/Dropdown.vue";
import CustomShareButton from "@/components/CustomShareButton.vue"
import CreateDineList from "@/components/CreateDineList.vue"
import EditDineList from '@/components/EditDineList.vue';
import DinelistVendorCard from "@/components/DinelistVendorCard.vue";
import LikeDinelist from "@/components/LikeDinelist.vue";
import EmptySpace from "@/components/EmptySpace.vue";
import tippy from "tippy.js";
import "tippy.js/dist/tippy.css"; // optional for styling

export default {
 props: {
  vendors: {
      type: Array,
      defualt: [],
  },
  list: {
      type: Array,
      defualt: [],
  },
  resource_name: {
      type: String,
  },
  dinelist_user: {
      type: Object,
  },
  isLogged: {
      type: Boolean
  },
  title: {
    type: String
  },
  isSameUser: {
    type: Boolean
  }
},
data() {
   return {
        showShareCard:false,
        tab: 'dinelist',
        showingProfileSettings: false,
        showEditDineList:false,
        user: this.dinelist_user,
        dinelist: this.list
   };
},
components: {
  DinelistVendorCard,
  CreateDineList,
  CustomShareButton,
  JetDropdown,
  AppLayout,
  Footer,
  JetDropdownLink,
  DinelistCard,
  EditDineList,
  LikeDinelist,
  EmptySpace
},

methods: {

    back() {
      window.history.back();
    },

  OnClosingModal(){
       this.showCreateDineList = false;
       window.location.reload();
},
 
},
mounted() {

  // document.title = this.title ? this.title  :  "DineList & Favourite Restaurants - Dinesurf";
  
  tippy("#privateInfo", {
                content: "This is a private list",
  });
},
};
</script>
