<template>
  <teleport to="body">
    <transition leave-active-class="duration-200">
      <div
        v-show="show"
        class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50"
        scroll-region
      >
        <transition
          enter-active-class="ease-out duration-300"
          enter-from-class="opacity-0"
          enter-to-class="opacity-100"
          leave-active-class="ease-in duration-200"
          leave-from-class="opacity-100"
          leave-to-class="opacity-0"
        >
          <div
            v-show="show"
            class="fixed inset-0 transform transition-all"
            @click="close"
          >
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
          </div>
        </transition>

        <transition
          enter-active-class="ease-out duration-300"
          enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
          enter-to-class="opacity-100 translate-y-0 sm:scale-100"
          leave-active-class="ease-in duration-200"
          leave-from-class="opacity-100 translate-y-0 sm:scale-100"
          leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        >
          <div
            v-show="show"
            class="
              mb-6
              bg-white
              rounded-lg
              overflow-hidden
              shadow-xl
              transform
              transition-all
              sm:w-full sm:mx-auto
            "
            :class="maxWidthClass"
          >
            <button
              :id="'closeModal' + id"
              aria-label="close"
              class="absolute top-0 right-0 text-xl text-gray-500 my-2 mx-4"
              @click.prevent="close"
              v-if="closeable"
            >
              <svg width="30" height="30" viewBox="0 0 44 44" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M28.2288 17.2287L23.4575 22L28.2288 26.7712C28.3301 26.8657 28.4113 26.9795 28.4677 27.106C28.5241 27.2325 28.5544 27.3691 28.5568 27.5075C28.5593 27.646 28.5338 27.7835 28.4819 27.9119C28.4301 28.0404 28.3529 28.157 28.2549 28.2549C28.157 28.3529 28.0404 28.4301 27.912 28.4819C27.7835 28.5338 27.646 28.5593 27.5075 28.5568C27.3691 28.5544 27.2325 28.5241 27.106 28.4677C26.9795 28.4113 26.8657 28.3301 26.7713 28.2288L22 23.4575L17.2288 28.2288C17.0333 28.4109 16.7747 28.5101 16.5075 28.5054C16.2404 28.5007 15.9855 28.3924 15.7965 28.2035C15.6076 28.0145 15.4994 27.7596 15.4946 27.4925C15.4899 27.2253 15.5891 26.9667 15.7713 26.7712L20.5425 22L15.7713 17.2287C15.5891 17.0333 15.4899 16.7747 15.4946 16.5075C15.4994 16.2404 15.6076 15.9855 15.7965 15.7965C15.9855 15.6076 16.2404 15.4993 16.5075 15.4946C16.7747 15.4899 17.0333 15.5891 17.2288 15.7713L22 20.5425L26.7713 15.7713C26.9667 15.5891 27.2253 15.4899 27.4925 15.4946C27.7596 15.4993 28.0145 15.6076 28.2035 15.7965C28.3924 15.9855 28.5007 16.2404 28.5054 16.5075C28.5101 16.7747 28.4109 17.0333 28.2288 17.2287ZM39.5313 22C39.5313 25.4674 38.5031 28.8568 36.5767 31.7398C34.6504 34.6228 31.9123 36.8699 28.7089 38.1968C25.5055 39.5237 21.9806 39.8708 18.5798 39.1944C15.1791 38.5179 12.0553 36.8483 9.60354 34.3965C7.15175 31.9447 5.48206 28.8209 4.80562 25.4202C4.12917 22.0194 4.47635 18.4945 5.80324 15.2911C7.13014 12.0877 9.37717 9.34966 12.2602 7.4233C15.1432 5.49694 18.5327 4.46875 22 4.46875C26.6479 4.47421 31.1039 6.323 34.3904 9.60956C37.677 12.8961 39.5258 17.3521 39.5313 22ZM37.4688 22C37.4688 18.9406 36.5615 15.9498 34.8618 13.406C33.1621 10.8622 30.7462 8.87953 27.9196 7.70874C25.0931 6.53795 21.9828 6.23161 18.9822 6.82848C15.9816 7.42534 13.2253 8.8986 11.0619 11.0619C8.89861 13.2253 7.42535 15.9816 6.82849 18.9822C6.23162 21.9828 6.53795 25.0931 7.70875 27.9196C8.87954 30.7462 10.8622 33.1621 13.406 34.8618C15.9499 36.5615 18.9406 37.4688 22 37.4688C26.1012 37.4642 30.0331 35.833 32.933 32.933C35.833 30.0331 37.4642 26.1012 37.4688 22Z" fill="black"/>
              </svg>
            </button>
            <slot v-if="show"></slot>
          </div>
        </transition>
      </div>
    </transition>
  </teleport>
</template>

<script>
import { onMounted, onUnmounted } from "vue";

export default {
  emits: ["close"],

  props: {
    show: {
      default: false,
    },
    maxWidth: {
      default: "2xl",
    },
    closeable: {
      default: true,
    },
    id: {
      required: false,
    },
  },

  watch: {
    show: {
      immediate: true,
      handler: (show) => {
        if (show) {
          document.body.style.overflow = "hidden";
        } else {
          document.body.style.overflow = null;
        }
      },
    },
  },

  setup(props, { emit }) {
    const close = () => {
      if (props.closeable) {
        emit("close");
      }
    };

    const closeOnEscape = (e) => {
      if (e.key === "Escape" && props.show) {
        close();
      }
    };

    onMounted(() => document.addEventListener("keydown", closeOnEscape));
    onUnmounted(() => document.removeEventListener("keydown", closeOnEscape));

    return {
      close,
    };
  },

  computed: {
    maxWidthClass() {
      return {
        sm: "sm:max-w-sm",
        md: "sm:max-w-md",
        lg: "sm:max-w-lg",
        xl: "sm:max-w-xl",
        "2xl": "sm:max-w-2xl",
      }[this.maxWidth];
    },
  },
};
</script>
