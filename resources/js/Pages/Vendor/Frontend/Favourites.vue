<template>
      <app-layout>
          <div class="relative  h-full  w-full ">
                <div class="bg-white  flex lg:flex-row flex-col">
                       <div  class="flex flex-col lg:md:shadow-side-menu rounded-md  lg:md:mb-auto lg:md:mt-10 lg:md:ml-10 lg:p-8 p-5">
                            <div class="grid lg:grid-cols-1 grid-cols-2 gap-x-5  items-center">

                                <a class="flex min-w-[17rem] my-10 lg:hidden "  :href="route('explore.dinelist')">
                                    <span class="bg-[#F6F6F6] w-full rounded-l-full p-3 text-[16px] px-4  font-normal font-sans  text-[#9CA3AF]">Explore DineList</span>
                                    <button  class="bg-dinesurf-green-400 rounded-r-full px-4 p-3">
                                            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M15.7044 15.75L12.3498 12.3795L15.7044 15.75ZM14.2088 7.875C14.2088 9.56576 13.5391 11.1873 12.3471 12.3828C11.155 13.5784 9.53826 14.25 7.85245 14.25C6.16664 14.25 4.54988 13.5784 3.35783 12.3828C2.16578 11.1873 1.49609 9.56576 1.49609 7.875C1.49609 6.18424 2.16578 4.56274 3.35783 3.36719C4.54988 2.17165 6.16664 1.5 7.85245 1.5C9.53826 1.5 11.155 2.17165 12.3471 3.36719C13.5391 4.56274 14.2088 6.18424 14.2088 7.875V7.875Z" stroke="#F9FAFB" stroke-width="2" stroke-linecap="round"/>
                                            </svg>
                                    </button>
                                </a>
                                <div class="ml-10  flex items-end justify-end">
                                    <jet-dropdown align="right" width="48">
                                        <!-- $page.props.jetstream.managesProfilePhotos -->
                                        <template #trigger>
                                            <button>
                                                <svg width="23" height="6" viewBox="0 0 23 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <circle cx="3" cy="3" r="3" fill="#9DC64E"/>
                                                                <circle cx="12" cy="3" r="3" fill="#9DC64E"/>
                                                                <circle cx="20" cy="3" r="3" fill="#9DC64E"/>
                                                </svg>
                                                </button>
                                        </template>
                        
                                        <template #content >
                                            <div class="flex flex-col  justify-end items-end p-4 space-y-3">
                                                    <a  :href="route('profile.show')" class="flex  space-x-2 items-center " v-if="this.isSameUser"> 
                                                        <span class="font-medium text-[18px]">Edit profile</span>

                                                                <svg width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M15.9853 4.08184C16.3869 3.77325 16.6079 3.36344 16.6079 2.9275C16.6079 2.49156 16.3869 2.08174 15.9853 1.77315L14.3002 0.478392C13.8986 0.169805 13.3652 0 12.7978 0C12.2304 0 11.6971 0.169805 11.2965 0.477575L0 9.13026V12.7345H4.68881L15.9853 4.08184ZM12.7978 1.63274L14.484 2.92668L12.7946 4.21981L11.1095 2.92586L12.7978 1.63274ZM2.125 11.1018V9.80785L9.605 4.07858L11.2901 5.37334L3.81119 11.1018H2.125ZM0 14.3673H17V16H0V14.3673Z" fill="#383838"/>
                                                                </svg>
                                                    </a>

                                                    <button  @click="showShareCard = true" class="flex space-x-2 items-center"> 
                                                        <span class="font-medium text-[18px]">Share Profile</span>

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
                                <a class="lg:flex w-full my-10 hidden "  :href="route('explore.dinelist')">
                                    <span class="bg-[#F6F6F6] w-full rounded-l-full p-3 text-[16px] px-4  font-normal font-sans  text-[#9CA3AF]">Explore DineList</span>
                                    <button  class="bg-dinesurf-green-400 rounded-r-full px-4 p-3">
                                            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M15.7044 15.75L12.3498 12.3795L15.7044 15.75ZM14.2088 7.875C14.2088 9.56576 13.5391 11.1873 12.3471 12.3828C11.155 13.5784 9.53826 14.25 7.85245 14.25C6.16664 14.25 4.54988 13.5784 3.35783 12.3828C2.16578 11.1873 1.49609 9.56576 1.49609 7.875C1.49609 6.18424 2.16578 4.56274 3.35783 3.36719C4.54988 2.17165 6.16664 1.5 7.85245 1.5C9.53826 1.5 11.155 2.17165 12.3471 3.36719C13.5391 4.56274 14.2088 6.18424 14.2088 7.875V7.875Z" stroke="#F9FAFB" stroke-width="2" stroke-linecap="round"/>
                                            </svg>
                                    </button>
                                </a>

                                           
                                <div class="flex items-center  justify-center">
                                    <img
                                        :src="current_user.image_url"
                                        :alt="current_user.name"
                                        class="rounded-full h-20 w-20 bg-gray-300 border-2 border-transparent object-cover cursor-pointer"
                                    />
                                </div>
                                <div class="flex flex-col w-80 mx-auto items-center">
                                        <h1 class="font-thin text-[28px] capitalize   text-dinesurf-green-400">{{ current_user.displayname ? current_user.displayname : current_user.name ===  null ? fullName : current_user.name }}</h1>
                                        <h2 class="font-normal text-[14px] text-center capitalize   text-[#00000070]  ">{{ current_user.bio }}</h2>
                                </div>
                                <div class="flex space-x-2 mt-4 mx-auto"  >
                                        <a  :href="current_user.instal_link" v-if="current_user.instal_link" class="bg-[#000000] rounded-full p-2">
                                            <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M5.33696 8.54249C5.33696 7.02241 6.53121 5.78982 8.0048 5.78982C9.47839 5.78982 10.6733 7.02241 10.6733 8.54249C10.6733 10.0626 9.47839 11.2952 8.0048 11.2952C6.53121 11.2952 5.33696 10.0626 5.33696 8.54249ZM3.89443 8.54249C3.89443 10.8843 5.73464 12.7825 8.0048 12.7825C10.275 12.7825 12.1152 10.8843 12.1152 8.54249C12.1152 6.20071 10.275 4.30245 8.0048 4.30245C5.73464 4.30245 3.89443 6.20071 3.89443 8.54249ZM11.3173 4.13433C11.3172 4.33031 11.3735 4.52191 11.479 4.6849C11.5845 4.84789 11.7344 4.97496 11.9099 5.05003C12.0854 5.1251 12.2786 5.1448 12.4649 5.10664C12.6513 5.06849 12.8225 4.97418 12.9568 4.83566C13.0912 4.69714 13.1828 4.52062 13.2199 4.32842C13.2571 4.13623 13.2381 3.93699 13.1655 3.7559C13.0929 3.57481 12.9698 3.42 12.8119 3.31106C12.654 3.20211 12.4683 3.14392 12.2783 3.14385H12.2779C12.0232 3.14397 11.779 3.24835 11.5989 3.43406C11.4188 3.61978 11.3175 3.87164 11.3173 4.13433ZM4.77086 15.2638C3.99042 15.2271 3.56623 15.093 3.28434 14.9797C2.91061 14.8296 2.64395 14.6509 2.36359 14.3621C2.08323 14.0733 1.90969 13.7984 1.76483 13.4129C1.65494 13.1223 1.52495 12.6846 1.48947 11.8795C1.45066 11.0091 1.44291 10.7477 1.44291 8.54256C1.44291 6.33745 1.4513 6.07672 1.48947 5.2056C1.52501 4.40055 1.65597 3.9637 1.76483 3.67218C1.91033 3.28666 2.08362 3.0116 2.36359 2.72239C2.64357 2.43319 2.90997 2.25417 3.28434 2.10474C3.56611 1.99139 3.99042 1.85729 4.77086 1.82069C5.61463 1.78066 5.86809 1.77267 8.0048 1.77267C10.1415 1.77267 10.3952 1.78132 11.2397 1.82069C12.0201 1.85735 12.4436 1.99244 12.7262 2.10474C13.1 2.25417 13.3666 2.43358 13.647 2.72239C13.9273 3.0112 14.1002 3.28666 14.2457 3.67218C14.3556 3.96284 14.4856 4.40055 14.5211 5.2056C14.5599 6.07672 14.5676 6.33745 14.5676 8.54256C14.5676 10.7477 14.5599 11.0084 14.5211 11.8795C14.4856 12.6846 14.3549 13.1221 14.2457 13.4129C14.1002 13.7984 13.9269 14.0735 13.647 14.3621C13.367 14.6506 13.1 14.8296 12.7262 14.9797C12.4445 15.0931 12.0201 15.2272 11.2397 15.2638C10.3959 15.3038 10.1425 15.3118 8.0048 15.3118C5.86713 15.3118 5.61437 15.3038 4.77086 15.2638ZM4.70458 0.335163C3.85242 0.375194 3.27012 0.514578 2.76159 0.718699C2.23494 0.929492 1.7891 1.21229 1.34359 1.67113C0.898075 2.12998 0.624631 2.5906 0.420284 3.13387C0.222405 3.65877 0.0872843 4.25911 0.0484771 5.13816C0.00902942 6.01859 0 6.30006 0 8.54249C0 10.7849 0.00902942 11.0664 0.0484771 11.9468C0.0872843 12.8259 0.222405 13.4262 0.420284 13.9511C0.624631 14.494 0.898139 14.9552 1.34359 15.4138C1.78904 15.8725 2.23494 16.1549 2.76159 16.3663C3.27108 16.5704 3.85242 16.7098 4.70458 16.7498C5.55853 16.7898 5.83095 16.7998 8.0048 16.7998C10.1786 16.7998 10.4515 16.7905 11.305 16.7498C12.1572 16.7098 12.7392 16.5704 13.248 16.3663C13.7743 16.1549 14.2205 15.8727 14.666 15.4138C15.1115 14.955 15.3844 14.494 15.5893 13.9511C15.7872 13.4262 15.923 12.8259 15.9611 11.9468C15.9999 11.0657 16.009 10.7849 16.009 8.54249C16.009 6.30006 15.9999 6.01859 15.9611 5.13816C15.9223 4.25905 15.7872 3.65844 15.5893 3.13387C15.3844 2.59093 15.1108 2.1307 14.666 1.67113C14.2212 1.21156 13.7743 0.929492 13.2486 0.718699C12.7392 0.514578 12.1572 0.374534 11.3057 0.335163C10.4522 0.295131 10.1793 0.285156 8.00544 0.285156C5.83159 0.285156 5.55853 0.294471 4.70458 0.335163Z" fill="white"/>
                                            </svg>
                                        </a>

                                        <a  :href="current_user.twitter_link" v-if="current_user.twitter_link" class="bg-[#000000] rounded-full p-2">
                                            <svg width="17" height="15" viewBox="0 0 17 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M16.1937 2.27546C15.6818 2.51017 15.1437 2.6736 14.5926 2.76173C14.8503 2.71467 15.2294 2.22056 15.3803 2.02056C15.6095 1.71889 15.7843 1.3745 15.8956 1.00489C15.8956 0.977442 15.9213 0.938227 15.8956 0.918619C15.8826 0.911068 15.868 0.907111 15.8532 0.907111C15.8384 0.907111 15.8239 0.911068 15.8109 0.918619C15.2125 1.26388 14.5757 1.52739 13.9154 1.70292C13.8924 1.71041 13.8679 1.71108 13.8446 1.70486C13.8212 1.69865 13.7999 1.68577 13.7829 1.66763C13.7315 1.60242 13.6762 1.54086 13.6173 1.48332C13.348 1.22624 13.0425 1.01584 12.7119 0.859797C12.2656 0.664732 11.7836 0.580254 11.3022 0.612742C10.8351 0.644172 10.3791 0.777656 9.96249 1.00489C9.55224 1.24447 9.19168 1.56993 8.90248 1.96174C8.59828 2.36501 8.37866 2.83301 8.25839 3.33427C8.15921 3.81106 8.14796 4.30385 8.22526 4.78522C8.22526 4.86758 8.22526 4.87934 8.15901 4.86758C5.53477 4.45582 3.38163 3.46368 1.62232 1.3343C1.54503 1.24018 1.50455 1.24018 1.44198 1.3343C0.676418 2.5735 1.04816 4.53425 2.0051 5.50286C2.13392 5.63227 2.26642 5.75776 2.40628 5.8754C1.96753 5.84221 1.53949 5.71553 1.14753 5.50286C1.07392 5.45188 1.03343 5.47933 1.02975 5.57345C1.01932 5.70393 1.01932 5.83512 1.02975 5.9656C1.10655 6.59092 1.33784 7.18329 1.69998 7.68213C2.06212 8.18098 2.54211 8.56839 3.09087 8.80477C3.22464 8.86582 3.36403 8.91182 3.50677 8.94202C3.1006 9.02722 2.68401 9.04047 2.27378 8.98124C2.18545 8.96163 2.15233 9.01261 2.18545 9.1028C2.72649 10.6714 3.90059 11.1498 4.76185 11.4165C4.87963 11.4361 4.9974 11.4361 5.1299 11.4675C5.1299 11.4675 5.1299 11.4675 5.10782 11.491C4.85386 11.9851 3.82698 12.3184 3.35587 12.491C2.49597 12.8201 1.57915 12.9459 0.669057 12.8596C0.525515 12.8361 0.49239 12.84 0.455584 12.8596C0.418778 12.8792 0.455584 12.9223 0.49607 12.9616C0.680099 13.091 0.864127 13.2047 1.05552 13.3145C1.62528 13.6456 2.22764 13.9086 2.85163 14.0988C6.08317 15.0478 9.71957 14.3498 12.1451 11.7812C14.0516 9.76554 14.7215 6.98519 14.7215 4.20092C14.7215 4.09504 14.8429 4.03229 14.9129 3.97739C15.3952 3.57697 15.8204 3.10407 16.1753 2.57349C16.2367 2.49441 16.2682 2.39371 16.2636 2.29115C16.2636 2.23232 16.2636 2.24409 16.1937 2.27546Z" fill="white"/>
                                            </svg>
                                        </a>

                                        <a :href="current_user.tiktok_link" v-if="current_user.tiktok_link" class="bg-[#000000] rounded-full p-2">
                                            <svg width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M11.4556 6.25678C12.5274 7.04319 13.8108 7.46336 15.1258 7.45826V4.76837C14.8673 4.76911 14.6095 4.74055 14.357 4.68319V6.82614C13.0391 6.82855 11.7537 6.40356 10.6825 5.6112V11.1434C10.6792 12.0512 10.438 12.9412 9.98448 13.7189C9.53092 14.4967 8.88189 15.1333 8.10622 15.5611C7.33055 15.989 6.45715 16.1923 5.57863 16.1493C4.70011 16.1064 3.84923 15.8189 3.11621 15.3172C3.79424 16.0253 4.66059 16.5098 5.60556 16.7093C6.55053 16.9089 7.53162 16.8145 8.42459 16.4381C9.31756 16.0618 10.0822 15.4204 10.6218 14.5952C11.1614 13.7699 11.4516 12.798 11.4556 11.8024V6.25678ZM12.4068 3.51309C11.8618 2.90334 11.5274 2.12471 11.4556 1.29842V0.94873H10.7259C10.8161 1.47397 11.0124 1.97373 11.302 2.41553C11.5916 2.85734 11.968 3.23129 12.4068 3.51309ZM4.80581 13.1877C4.5529 12.8464 4.3979 12.4386 4.35848 12.0108C4.31905 11.583 4.39676 11.1523 4.58277 10.7679C4.76879 10.3834 5.05563 10.0606 5.41065 9.83608C5.76568 9.6116 6.17463 9.49449 6.59096 9.49809C6.82082 9.49802 7.04933 9.53431 7.26853 9.60569V6.82614C7.01229 6.79111 6.75384 6.77612 6.4954 6.78131V8.94218C5.96165 8.76803 5.38394 8.80764 4.87718 9.05311C4.37042 9.29859 3.97175 9.73195 3.76046 10.267C3.54917 10.8021 3.54073 11.3996 3.73684 11.9408C3.93295 12.482 4.31922 12.9271 4.81884 13.1877H4.80581Z" fill="#EE1D52"/>
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M10.6827 5.59327C11.754 6.38563 13.0393 6.81062 14.3573 6.8082V4.66526C13.6064 4.50139 12.9242 4.09839 12.4071 3.51309C11.9683 3.23129 11.5918 2.85734 11.3022 2.41553C11.0126 1.97373 10.8164 1.47397 10.7261 0.94873H8.80635V11.8024C8.80456 12.2829 8.65706 12.7506 8.38455 13.1401C8.11204 13.5296 7.7282 13.8212 7.28693 13.974C6.84565 14.1268 6.36908 14.1332 5.92414 13.9921C5.47919 13.8511 5.0882 13.5698 4.80605 13.1877C4.35891 12.9549 4.00081 12.5733 3.78955 12.1046C3.57828 11.6359 3.52616 11.1074 3.6416 10.6043C3.75704 10.1013 4.0333 9.65309 4.42582 9.33201C4.81833 9.01092 5.3042 8.83569 5.80504 8.83459C6.03483 8.83534 6.26322 8.87161 6.48261 8.94218V6.78131C5.5347 6.80479 4.61405 7.11345 3.83369 7.66938C3.05333 8.22531 2.44721 9.00432 2.08974 9.9108C1.73227 10.8173 1.63901 11.8118 1.8214 12.7722C2.00378 13.7326 2.45389 14.6171 3.11645 15.3172C3.84963 15.8225 4.70185 16.113 5.58234 16.1578C6.46283 16.2026 7.33861 16.0001 8.11639 15.5718C8.89418 15.1435 9.54484 14.5054 9.99906 13.7256C10.4533 12.9457 10.6941 12.0533 10.6957 11.1434L10.6827 5.59327Z" fill="white"/>
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M14.3571 4.66519V4.08687C13.6675 4.08989 12.9912 3.89091 12.4069 3.51302C12.9227 4.10001 13.6054 4.50334 14.3571 4.66519ZM10.7259 0.948662C10.7259 0.84555 10.6955 0.737954 10.6825 0.634842V0.285156H8.03302V11.1433C8.03072 11.7513 7.79481 12.3335 7.37707 12.7622C6.95933 13.1909 6.39389 13.4309 5.80484 13.4297C5.458 13.4315 5.11567 13.3486 4.80585 13.1877C5.088 13.5698 5.47899 13.8511 5.92394 13.9921C6.36889 14.1331 6.84545 14.1268 7.28673 13.9739C7.72801 13.8211 8.11184 13.5295 8.38435 13.14C8.65686 12.7506 8.80437 12.2828 8.80615 11.8024V0.948662H10.7259ZM6.48242 6.77675V6.16256C5.39217 6.00969 4.28389 6.24247 3.33822 6.82296C2.39255 7.40345 1.66525 8.29742 1.27484 9.35918C0.884425 10.4209 0.853931 11.5879 1.18832 12.6698C1.52271 13.7518 2.20228 14.6849 3.11625 15.3172C2.4587 14.6154 2.01339 13.7312 1.83473 12.7726C1.65607 11.8139 1.75178 10.8223 2.11019 9.91885C2.46859 9.01542 3.07416 8.23929 3.85293 7.68527C4.63169 7.13126 5.54992 6.82335 6.49545 6.79917L6.48242 6.77675Z" fill="#69C9D0"/>
                                                </svg>
                                        </a>
                                </div>

                            
                                            
                            </div>




                              
                              <div class="mt-10  w-full lg:px-0  px-5 ">
                                        <div class="flex justify-center">
                                            <div class="flex bg-[#E3E3E34F]  text-center space-x-5 lg:w-80  w-full justify-between  rounded-full ">
                                                    <button @click="tab = 'dinelist'"  class="p-2   font-bold text-[14px]   "  :class="  tab === 'dinelist' ?  'bg-black  text-white rounded-full  w-full' : 'text-dinesurf-green-400 w-full'">
                                                        DineList
                                                    </button>
                                                    <button @click="tab = 'favorite'" class="p-2 font-bold text-[14px]"   :class="  tab === 'favorite' ?  'bg-black  text-white rounded-full  w-full' : 'text-dinesurf-green-400  w-full'">
                                                        Favorites
                                                    </button>
                                            </div>
                                        </div>
                                      

                                       <div v-if="tab === 'dinelist'" class="w-full  lg:px-10  pt-[20px] md:px-5 px-0">

                                                <div class="flex justify-start"  v-if="this.isSameUser && list.length > 0">
                                                     <button  @click="showCreateDineList = true" class="p-3  bg-[#FFF4F0]  text-[#D2592E]  rounded-full">
                                                        + Add New List
                                                     </button>
                                                </div>

                                                <div class="grid grid-cols-1  min-h-[25rem] mb-10  sm:grid-cols-2 gap-4 pt-[20px]   px-5 " v-if="list.length > 0">
                                                    <!-- overflow-x-scroll  h-[27rem] -->
                                                      <div v-for="(item, index) in list">
                                                               <dinelist-card  :isSameUser="this.isSameUser" :list="item" :user="current_user"/>
                                                        </div>
                                                </div>
                                                <div class="w-full h-[25rem] text-center flex items-center  justify-center" v-else>
                                                        <div v-if="this.isSameUser">
                                                            <empty-space 
                                                                            :title="'Oops!... Its Empty'"
                                                                            :description="'Start by creating dinelist.'"   
                                                                            :image="'/images/empty_images/4.png'"
                                                                            :page="'list'"
                                                                            @handleAsbutton="showCreateDineList = !showCreateDineList"
                                                                            :asButton="true"
                                                                    />
                                                                 <!-- <span>    <span  @click="showCreateDineList = true" class="text-dinesurf-green-400 cursor-pointer ml-3">here</span>  </span>   -->
                                                        </div>
                                                        <div  class="flex justify-center items-center" v-else>
                                                                        <empty-space 
                                                                            :title="'Oops!... Its Empty'"
                                                                            :description="'This profile doesn\'t have any dinelist yet!'"   
                                                                            :image="'/images/empty_images/4.png'"
                                                                            :noButton="true"
                                                                    />
                                                                        <!-- <span>This profile doesn't have any dinelist yet!</span> -->
                                                        </div>
                                                 </div>
                                                       
                                       </div>


                                      
                                       <div  v-if="tab === 'favorite'" class="w-full  lg:px-10  pt-10 md:px-5 px-0">
                                        <!-- overflow-scroll  h-[27rem] -->
                                             <div class="flex justify-between">
                                                   <span class="text-[23px]  text-dinesurf-red-400">Favorite DineLists</span>
                                                    <!-- <a href="#" class="font-bold text-[16px] text-dinesurf-green-400">See All</a> -->
                                             </div>

                                             <div class="grid    sm:grid-cols-2 pt-[20px] gap-4 mb-5  min-h-[10rem] grid-cols-1" v-if="fdinelist?.length > 0">
                                                       <div v-for="(item, index) in fdinelist">
                                                               <dinelist-card  :isSameUser="this.isSameUser" :list="item" :user="current_user"/>
                                                        </div>
                                                      
                                                </div>
                                              <div class="w-full h-[10rem] text-center flex items-center justify-center" v-else>
                                                    No Favorite Founds
                                             </div>

                                              <div class="flex justify-between">
                                                   <span class="text-[23px]  text-dinesurf-red-400">Liked Restaurants</span>
                                                    <!-- <a href="#" class="font-bold text-[16px] text-dinesurf-green-400">See All</a> -->
                                             </div>
                                          
                                          
                                             <template v-if="vendors?.data?.length > 0" class="bg-green-500 min-h-[10rem]" >
                                                <div   class=" grid    sm:grid-cols-3 pt-[20px] gap-4 mb-5  grid-cols-1">
                                                  <div
                                                        v-for="(item, index) in vendors.data"
                                                        :key="index" 
                                                    >
                                                        <favourite-card  :vendor="item" :index=" index + 1" />
                                                        </div>
                                                </div>
                                               
                                            </template>

                                            <div class="w-full h-[10rem] text-center flex items-center justify-center" v-else>
                                                    No Favorite Founds
                                             </div>
                                       </div>


                              </div>
                 </div>

              <div class=" z-10   w-full">
                    <Footer /> 
                 </div>

         </div>
 </app-layout>
              



      <card-modal
        :showing="showCreateDineList"
        @close="showCreateDineList = false"
        :key="'cardModal1'"
      >

         <create-dine-list  />

      </card-modal>

      <card-modal
        :showing="showShareCard"
        @close="showShareCard = false"
        :key="'cardModal1'"
      >
      <div class="my-5">
          <span class="font-bold text-2xl">Share Profile:</span>
      </div>

          <custom-share-button  :link_text="'See the curated list of restaurants to dine at by '+ current_user.name" :social_link="route('dinelist.profile', { slug : current_user.slug })" /> 

      </card-modal>

      

     
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import Welcome from "@/Jetstream/Welcome.vue";
import Footer from "@/components/Footer.vue";
import JetDropdownLink from "@/Jetstream/DropdownLink.vue";
import Favorite from "@/components/favourite.vue"
import DinelistCard from "@/components/DinelistCard.vue";
import FavouriteCard from "@/components/FavouriteCard.vue";
import JetDropdown from "@/Jetstream/Dropdown.vue";
import CustomShareButton from "@/components/CustomShareButton.vue"
import CreateDineList from "@/components/CreateDineList.vue"
export default {
       props: {
        vendors: {
            type: Array,
            defualt: [],
        },
        favourite_dinelist: {
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
        current_user: {
            type: Object,
        },
        isLogged: {
            type: Boolean
        },
        isSameUser: {
            type: Boolean
        }
    },
    data() {
    return {
     showShareCard: false,
      tab: 'dinelist',
      showingProfileSettings: false,
      showCreateDineList:false,
      user: this.current_user,
      fdinelist: this.favourite_dinelist,
      fullName: this.current_user?.first_name + ' ' + this.current_user?.last_name
    };
  },
    components: {
        CreateDineList,
        CustomShareButton,
        JetDropdown,
        FavouriteCard,
        AppLayout,
        Welcome,
        Footer,
        JetDropdownLink,
        DinelistCard,
        Favorite
    },

    methods: {

        OnClosingModal(){
             this.showCreateDineList = false;
             window.location.reload();
      },
          handleWebshare(){

                        // Check if the WebShare API is supported by the browser
                if (navigator.share) {
                // Create a share button or trigger the sharing functionality in response to a user action
                const shareButton = document.getElementById('shareButton');
                
                shareButton.addEventListener('click', () => {
                    // Invoke the share dialog
                    navigator.share({
                    title: 'WebShare API Demo',
                    text: 'Check out this awesome website!',
                    url: 'https://www.example.com'
                    })
                    .then(() => console.log('Successfully shared'))
                    .catch((error) => console.log('Error sharing:', error));
                });
                } else {
                // WebShare API is not supported, handle the fallback or show an alternative UI
                console.log('WebShare API is not supported');
                }

       }
    },
    mounted() {

        console.log("this.isSameUser:", this.list);

    },
};
</script>
