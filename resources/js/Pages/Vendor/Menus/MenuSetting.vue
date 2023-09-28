<template>
    <div class="mt-5 w-full px-5" v-if="!regSuccess">
        <div class="flex flex-col items-center justify-center pb-4">
            <span class="font-bold text-2xl text-center">Menu Settings</span>
            <span class="text-dinesurf-"
                >Select The menu Experience for your restaurant</span
            >
        </div>

        <div class="grid grid-cols-3 gap-3 w-full">
            <div
                @click="page = 'dinesurf-menu'"
                class="text-center py-3 cursor-pointer flex space-x-3 items-center justify-center"
                :class="
                    page == 'dinesurf-menu'
                        ? 'border-b-2 border-b-dinesurf-green-400 '
                        : ''
                "
            >
                <input
                    class="checked:bg-dinesurf-green-600 checked:border-dinesurf-green-600"
                    v-model="page"
                    type="radio"
                    value="dinesurf-menu"
                    name="page"
                    id="dinesurf-menu"
                />
                <span>Dinesurf Menu</span>
            </div>

            <div
                @click="page = 'pdf-menu'"
                class="text-center py-3 cursor-pointer flex space-x-3 items-center justify-center"
                :class="
                    page == 'pdf-menu'
                        ? 'border-b-2 border-b-dinesurf-green-400 '
                        : ''
                "
            >
                <input
                    class="checked:bg-dinesurf-green-600 checked:border-dinesurf-green-600"
                    v-model="page"
                    type="radio"
                    value="pdf-menu"
                    name="page"
                    id="pdf-menu"
                />
                <span>Pdf Menu</span>
            </div>

            <div
                @click="page = 'mira-menu'"
                class="text-center py-3 cursor-pointer flex space-x-3 items-center justify-center"
                :class="
                    page == 'mira-menu'
                        ? 'border-b-2 border-b-dinesurf-green-400 '
                        : ''
                "
            >
                <input
                    class="checked:bg-dinesurf-green-600 checked:border-dinesurf-green-600"
                    v-model="page"
                    type="radio"
                    value="mira-menu"
                    name="page"
                    id="mira-menu"
                />
                <span>Mira Menu</span>
            </div>
        </div>

        <div
            class="flex h-full items-center justify-center lg:px-20 py-8 flex-col space-y-5 border-t"
            v-if="page == 'mira-menu'"
        >
            <div class="flex flex-col space-y-3 text-center">
                <span>
                    Improve your customers’ experience with a digital menu that
                    allows them to order and pay at their table.
                </span>
                <p v-if="mira_id" class="text-center">
                    Mira Enabled
                    <i class="fa-regular fa-circle-check text-green-500"></i>
                </p>
                <p v-if="mira_processing" class="text-center">
                    Setting Up Mira...<i
                        class="fa-solid fa-spinner fa-spin"
                    ></i>
                </p>
                <button
                    v-if="!mira_id"
                    @click="enableMira"
                    :class="{ 'opacity-25': mira_processing }"
                    type="button"
                    class="btn text-white font-normal px-5 rounded bg-dinesurf-green-600 p-3"
                >
                    <span v-if="mira_processing">Processing....</span>
                    <span v-else>Enable Mira</span>
                </button>
                <div class="mt-10 px-10 flex justify-center" v-else>
                    <button
                        @click="submit"
                        :class="{ 'opacity-25': processing }"
                        type="button"
                        class="btn text-white font-normal px-5 rounded bg-dinesurf-green-600 p-3"
                    >
                        <span v-if="processing">Processing....</span>
                        <span v-else>Set Mira as Menu manager</span>
                    </button>
                </div>
            </div>
        </div>

        <div
            class="flex h-full items-center justify-center lg:px-20 pt-10 flex-col space-y-5 border-t"
            v-if="page == 'pdf-menu'"
        >
            <div
                class="flex items-center w-full border border-dashed border-dinesurf-green-400 bg-gray-100 justify-between p-3"
            >
                <div class="w-40 h-20" v-if="pdf_value">
                    <embed
                        :src="pdf_value"
                        type="application/pdf"
                        class="imageN"
                        :id="'image-output'"
                    />
                </div>
                <div class="flex flex-col" v-if="!this.pdf_value">
                    <span class="text-dinesurf-green-400 cursor-pointer">
                        Add PDF
                    </span>
                    <span class="text-xs">Max 3MB,PDF</span>

                    <span class="text-xs"> No file uploaded </span>
                </div>
                <button
                    type="button"
                    class="bg-dinesurf-green-400 relative w-32 h-10 pt-0 text-white"
                >
                    <span v-if="!this.pdf_value">Choose PDF</span>
                    <span v-else>Change PDF</span>
                    <input
                        dusk="image"
                        type="file"
                        :id="'file-image'"
                        name="image"
                        accept="application/pdf"
                        class="form-file-input select-none opacity-0 absolute top-2 left-4"
                        @change="displayFile"
                    />
                </button>
            </div>

            <div class="mt-10 px-10 flex justify-center" v-if="form.bg != ''">
                <button
                    @click="submit"
                    :class="{ 'opacity-25': processing }"
                    type="button"
                    class="btn text-white font-normal px-5 rounded bg-dinesurf-green-600 p-3"
                >
                    <span v-if="processing">Processing....</span>
                    <span v-else>Save setting</span>
                </button>
            </div>
        </div>

        <div
            class="flex h-full md:flex-row flex-col space-x-4 border-t"
            v-if="page == 'dinesurf-menu'"
        >
            <div class="flex justify-center md:border-r pr-4">
                <ul class="flex flex-col space-y-4 md:w-auto mt-10 w-full">
                    <li class="relative">
                        <input
                            class="sr-only peer"
                            v-model="form.bg_type"
                            @change="handleBgType"
                            type="radio"
                            value="color"
                            name="bg_type"
                            id="color"
                        />
                        <label
                            class="flex p-2 bg-white border border-gray-300 rounded-lg cursor-pointer focus:outline-none hover:bg-gray-50 peer-checked:ring-dinesurf-green-500 peer-checked:ring-2 peer-checked:border-transparent"
                            for="color"
                            >Use background color</label
                        >
                    </li>
                    <li class="relative">
                        <input
                            class="sr-only peer"
                            v-model="form.bg_type"
                            type="radio"
                            value="image"
                            name="bg_type"
                            id="image"
                        />
                        <label
                            class="flex p-2 bg-white border border-gray-300 rounded-lg cursor-pointer focus:outline-none hover:bg-gray-50 peer-checked:ring-dinesurf-green-500 peer-checked:ring-2 peer-checked:border-transparent"
                            for="image"
                            >Use&nbsp;background&nbsp;image</label
                        >
                    </li>

                    <li class="relative">
                        <input
                            class="sr-only peer"
                            v-model="form.bg_type"
                            type="radio"
                            value="theme"
                            name="bg_type"
                            id="theme"
                        />
                        <label
                            class="flex p-2 bg-white border border-gray-300 rounded-lg cursor-pointer focus:outline-none hover:bg-gray-50 peer-checked:ring-dinesurf-green-500 peer-checked:ring-2 peer-checked:border-transparent"
                            for="theme"
                            >Use&nbsp;background&nbsp;theme</label
                        >
                    </li>
                </ul>
            </div>

            <div class="flex justify-center flex-col w-full mt-10">
                <div
                    class="px-10 flex justify-center"
                    id="colorInf"
                    v-if="form.bg_type === 'color'"
                >
                    <div class="relative flex flex-col space-y-3">
                        <label class="">Pick a color</label>
                        <input
                            type="color"
                            v-model="form.bg"
                            @change="handColor"
                            style="border-radius: 30px"
                            class="cursor-pointer w-60 rounded-md h-32"
                        />
                        <span v-if="colorValue"
                            >Selected: {{ colorValue }}</span
                        >

                        <!-- <div  class="absolute  top-[40%]  left-[40%]">
                                                <svg width="38" height="38" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M27.5 3.74976C27.5 2.75519 27.1049 1.80137 26.4017 1.09811C25.6984 0.394844 24.7446 -0.000244141 23.75 -0.000244141C22.7554 -0.000244141 21.8016 0.394844 21.0984 1.09811C20.3951 1.80137 20 2.75519 20 3.74976V19.9998H3.75C2.75544 19.9998 1.80161 20.3948 1.09835 21.0981C0.395088 21.8014 0 22.7552 0 23.7498C0 24.7443 0.395088 25.6981 1.09835 26.4014C1.80161 27.1047 2.75544 27.4998 3.75 27.4998H20V43.7498C20 44.7443 20.3951 45.6981 21.0984 46.4014C21.8016 47.1047 22.7554 47.4998 23.75 47.4998C24.7446 47.4998 25.6984 47.1047 26.4017 46.4014C27.1049 45.6981 27.5 44.7443 27.5 43.7498V27.4998H43.75C44.7446 27.4998 45.6984 27.1047 46.4017 26.4014C47.1049 25.6981 47.5 24.7443 47.5 23.7498C47.5 22.7552 47.1049 21.8014 46.4017 21.0981C45.6984 20.3948 44.7446 19.9998 43.75 19.9998H27.5V3.74976Z" fill="gray"/>
                                                </svg>
                                           </div> -->
                    </div>
                </div>

                <div class="px-10" v-if="form.bg_type === 'image'">
                    <div
                        class="flex items-center w-full border border-dashed border-dinesurf-green-400 bg-gray-100 justify-between p-3"
                    >
                        <div class="w-40 h-20" v-if="image_value">
                            <img
                                :src="image_value"
                                class="imageN"
                                draggable="false"
                                :id="'image-output'"
                            />
                        </div>
                        <div class="flex flex-col" v-if="!this.image_value">
                            <span
                                class="text-dinesurf-green-400 cursor-pointer"
                            >
                                Add Image/ Banner
                            </span>
                            <span class="text-xs">Max 2MB,PNG,JPEG</span>

                            <span class="text-xs"> No file uploaded </span>
                        </div>
                        <button
                            type="button"
                            class="bg-dinesurf-green-400 relative w-32 h-10 pt-0 text-white"
                        >
                            <span v-if="!this.image_value">Choose Image</span>
                            <span v-else>Change Image</span>
                            <input
                                dusk="image"
                                type="file"
                                :id="'file-image'"
                                name="image"
                                accept="image/*"
                                class="form-file-input select-none opacity-0 absolute top-2 left-4"
                                @change="displayFile"
                            />
                        </button>
                    </div>
                </div>

                <div v-if="form.bg_type === 'theme'">
                    <ul class="grid grid-cols-3 gap-5 px-10">
                        <li
                            class="relative w-20 h-20"
                            v-for="(theme, index) in themes"
                            :key="index"
                        >
                            <input
                                class="sr-only peer"
                                v-model="form.theme_val"
                                @change="handleTheme"
                                type="radio"
                                :value="'theme-' + (index + 1)"
                                name="bg_theme"
                                :id="'theme-' + (index + 1)"
                            />
                            <label
                                :style="'background-image: url(' + theme + ')'"
                                :class="
                                    form.theme_val === 'theme-' + (index + 1)
                                        ? 'peer-checked:ring-dinesurf-green-500 peer-checked:ring-2 peer-checked:border-transparent'
                                        : ''
                                "
                                class="flex theme-bg p-5 w-full bg-white border border-gray-300 rounded-lg cursor-pointer focus:outline-none hover:bg-gray-50 peer-checked:ring-dinesurf-green-500 peer-checked:ring-2 peer-checked:border-transparent"
                                :for="'theme-' + (index + 1)"
                            >
                            </label>
                        </li>
                    </ul>
                </div>

                <div class="mt-10 px-10 flex justify-center" v-if="form.bg">
                    <button
                        @click="submit"
                        :class="{ 'opacity-25': processing }"
                        type="button"
                        class="btn text-white font-normal rounded bg-dinesurf-green-600 px-5 p-3"
                    >
                        <span v-if="processing">Processing....</span>
                        <span v-else>Save setting </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div
        class="flex flex-col justify-center items-center my-5"
        v-if="regSuccess"
    >
        <img src="/images/success-svg2.svg" class="h-16 md:h-32" />
        <h4
            class="text-center my-3 font-inter text-xs text-[#000000] font-normal"
        >
            {{ respondText }}
        </h4>
        <div class="flex flex-col justify-around space-y-3 w-full">
            <div
                class="flex flex-wrap justify-center space-x-4 items-center w-full"
            >
                <a
                    class="bg-[#201F1F] text-white p-2 px-4 rounded-md active:bg-gray-900"
                    target="_blank"
                    :href="route('menu', { id: vendor?.slug })"
                >
                    View menu page
                </a>
                <button
                    class="active:bg-gray-900 rounded-md border border-[#201F1F] p-2 px-4"
                    @click="reset()"
                >
                    Update setting
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import tippy from "tippy.js";
import "tippy.js/dist/tippy.css"; // optional for styling
import axios from "axios";

export default {
    components: {},
    props: {
        setting: Object,
    },
    data() {
        return {
            page: this.setting?.page ? this.setting?.page : "dinesurf-menu",
            regSuccess: false,
            respondText: "",
            form: {
                bg_type: this.setting?.bg_type,
                bg: this.setting?.bg,
                theme_val:
                    this.setting?.bg_type == "theme" ? this.setting?.bg : null,
                pdf: this.setting?.pdf,
            },
            image_value: this.setting?.image_url,
            pdf_value: this.setting?.pdf_url,
            themes: [
                "/images/themes/theme-1.png",
                "/images/themes/theme-2.png",
                "/images/themes/theme-3.png",
                "/images/themes/theme-4.png",
                "/images/themes/theme-5.png",
                "/images/themes/theme-6.png",
            ],
            processing: false,
            vendor: this.$page.props.auth.vendor,
            colorValue: null,

            // mira
            mira_id: this.$page.props.auth.vendor.mira_id,
            mira_processing: false,
        };
    },

    methods: {
        enableMira() {
            this.mira_processing = true;

            axios
                .post(
                    route("vendors.enable-service"),
                    { service: "mira" },
                    {
                        headers: {
                            "Content-Type": "application/json",
                            Accept: "application/json",
                        },
                    }
                )
                .then(({ data }) => {
                    this.mira_id = data.mira_id;
                })
                .catch((err) => {
                    this.handleApiError(err);
                })
                .finally(() => (this.mira_processing = false));
        },
        handColor(e) {
            this.colorValue = e.target.value;
        },
        reset() {
            this.regSuccess = false;
            this.respondText = "";
        },
        displayFile() {
            var files = document.getElementById("file-image").files;
            var output = document.getElementById("image-output");

            if (this.page == "dinesurf-menu") {
                this.form.bg = files[0];
                this.image_value = URL.createObjectURL(files[0]);
            } else {
                this.form.pdf = files[0];
                this.pdf_value = URL.createObjectURL(files[0]);
            }
            output.src = URL.createObjectURL(files[0]);
            output.onload = function () {
                URL.revokeObjectURL(output.src); // free memory
            };
        },
        handleTheme() {
            console.log("this.form.theme_val:", this.form.theme_val);
            this.form.bg = this.form.theme_val;
        },

        submit() {
            this.processing = true;
            var formData = new FormData();
            formData.append("bg_type", this.form.bg_type);
            formData.append("bg", this.form.bg);
            formData.append("page", this.page);
            formData.append("pdf", this.form.pdf);

            axios
                .post(route("vendors.menu.settings"), formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                })
                .then(({ data }) => {
                    console.log("data.message:", data.message);
                    if (data?.success) {
                        this.regSuccess = true;
                        this.respondText = data.message;
                    } else {
                        alert(data.message);
                        //  toastr.error(data.message);
                    }
                })
                .catch((err) => {
                    toastr.error(err.message);
                })
                .finally(() => (this.processing = false));
        },
    },
    mounted() {
        console.log("mounted::");

        tippy("#colorInf", {
            content: "Click the color box to pick a color",
            arrow: true,
            arrowType: "round",
            distance: 3,
        });
    },
};
</script>
