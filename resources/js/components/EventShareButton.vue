<template>
    <div class="flex mb-3">
        <button
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
            <i class="fas fa-share-alt  text-black"    >
            </i>
        </button>
    
    
    
    
        <div :id="'share_icons' + id" class="flex justify-between w-14rem" v-if="show">
            <a
                target="_blank"
                :href="
                'https://twitter.com/intent/tweet?text=check%20out%20this%20beautiful%20event%20and%20make%20a%20reservation%20for%20it%20on%20dinesurf%20'+route('getEvent', { id: id })
                "
                class="
                    flex
                    items-center
                    justify-center
                    h-8
                    min-w-14
                    rounded-full
                    bg-blue-500
                    text-white
                    cursor-pointer
                    mr-1
                "
                ><i class="fab fa-twitter"></i
            ></a>
            <a
                target="_blank"
                :href="
                  'https://www.facebook.com/sharer/sharer.php?u='+ route('getEvent', { id: id })
                "
                class="
                    flex
                    items-center
                    justify-center
                    h-8
                    min-w-14
                    rounded-full
                    bg-blue-500
                    text-white
                    cursor-pointer
                    mr-1
                "
                ><i class="fab fa-facebook-f"></i
            ></a>
            <a
                target="_blank"
                :href="
                  'https://www.linkedin.com/sharing/share-offsite/?url='+ route('getEvent', { id: id })
                "
                class="
                    flex
                    items-center
                    justify-center
                    h-8
                    min-w-14
                    rounded-full
                    bg-blue-500
                    text-white
                    cursor-pointer
                    mr-1
                "
                ><i class="fab fa-linkedin-in"></i
            ></a>

            <button

            v-if="!isMenu"
                type="button"
                id="copyLink"
                class="
                    flex
                    items-center
                    justify-center
                    h-8
                    min-w-14
                    rounded-full
                    bg-blue-500
                    text-white
                    cursor-pointer
                "
                @click="copyToClipboard('ctn-link')"
            >
                <i class="far fa-copy"></i>
            </button>
            <input
                type="text"
                :value="route('getEvent', { id: id })"
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
    props: ["id", "menu"],
    data() {
        return {
            show: false,
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
            tippy("#copyLink", {
                content: "Value Copied",
            });
        },
    },
    mounted() {
        tippy("#share", {
            content: "Share on social media",
        });

        tippy("#copyLink", {
                content: "Copy To Clipboard",
        });

       
    },

};
</script>
