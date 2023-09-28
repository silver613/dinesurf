<template>
    <div class="flex mb-3">
        <!-- <button
            @click="toggle()"
            id="share"
            class="
                cursor-pointer
                h-8
                w-8
                flex
                items-center
                justify-center
                focus:outline-none
                
            "
        >
            <i class="fas fa-share-alt"   
             :class="'text-dinesurf-green-500  text-2xl'">
            </i>
        </button>
     -->
    
    
    
        <div :id="'share_icons' + id" class="flex justify-between w-14rem" v-if="show">

            <a
            id="linkedin"
                target="_blank"
                :href="'https://www.linkedin.com/sharing/share-offsite/?url='+ social_link"
                class="
                    flex
                    items-center
                    justify-center
                    h-[3rem]
                    min-w-[3rem]
                    rounded-full
                    bg-dinesurf-green-600
                    text-white
                    cursor-pointer
                    mr-1
                "
                ><i class="fab fa-linkedin-in text-[20px]"></i
            ></a>

            <a
            id="twitter"
                target="_blank"
                :href="'https://twitter.com/intent/tweet?text='+link_text+social_link"
                class="
                    flex
                    items-center
                    justify-center
                    h-[3rem]
                    min-w-[3rem]
                    rounded-full
                    bg-dinesurf-green-600
                    text-white
                    cursor-pointer
                    mr-1
                "
                ><i class="fab fa-twitter text-[20px]"></i
            ></a>
            <a
                 id="facebook"
                target="_blank"
                :href="'http://www.facebook.com/sharer.php?u='+ social_link"
                class="
                    flex
                    items-center
                    justify-center
                    h-[3rem]
                    min-w-[3rem]
                    rounded-full
                    bg-dinesurf-green-600
                    text-white
                    cursor-pointer
                    mr-1
                "
                ><i class="fab fa-facebook text-[20px]"></i
            ></a>

            <a
                id="whatsapp"
                target="_blank"
                :href="'whatsapp://send?text='+link_text+' '+social_link"
                class="
                    flex
                    items-center
                    justify-center
                    h-[3rem]
                    min-w-[3rem]
                    rounded-full
                    bg-dinesurf-green-600
                    text-white
                    cursor-pointer
                    mr-1
                "
                ><i class="fab fa-whatsapp text-[20px]"></i
            ></a>

         
            <button

                type="button"
                id="copyLink"
                class="
                    flex
                    items-center
                    justify-center
                    h-[3rem]
                    min-w-[3rem]
                    rounded-full
                    bg-dinesurf-green-600
                    text-white
                    cursor-pointer
                "
                @click="copyToClipboard('ctn-link')"
            >
                <i class="far fa-copy text-[20px]"></i>
            </button>
            <input
                type="text"
                :value="social_link"
                name="ctn-link"
                placeholder="copy link"
                class="opacity-0 h-1 focus:outline-none"
                id="ctn-link"
            />
        </div>
    </div>
</template>

<script>
import tippy from "tippy.js";
import "tippy.js/dist/tippy.css"; // optional for styling

export default {
    props: ["id", "menu", "copy_link", "social_link", "link_text"],
    data() {
        return {
            show: true,
            isMenu: this.menu
        };
    },
    methods: {
        toggle() {
            this.show = !this.show;
        },
        copyToClipboard(id) {

            var copyText = document.getElementById(id);
            copyText.select();
            document.execCommand("copy");
            toastr.success('Link copied to clipboard');
        },
    },
    mounted() {
        tippy("#twitter", {
            content: "Share on Twitter",
        });

        tippy("#instagram", {
            content: "Share on Instagram",
        });
        tippy("#linkedin", {
            content: "Share on Linkedin",
        });
        tippy("#facebook", {
            content: "Share on Facebook",
        });

        tippy("#whatsapp", {
            content: "Share on Whatsapp",
        });

    
        tippy("#copyLink", {
                content: "Copy To Clipboard",
        });

       
    },

};
</script>
