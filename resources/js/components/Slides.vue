<template>
  <div class="slider-box">

    <!-- <div  class="absolute flex w-full   justify-between  md:px-5  hidden  px-0">
              <button  @click="controlClick('rotateBack')" v-if="this.currentSlide != 0">
                  <i class="fa fa-angle-left   text-white  text-2xl"></i>
              </button>
              <button  @click="controlClick('rotateFront')"  v-if="this.currentSlide != this.sliderItems?.length">
                  <i class="fa fa-angle-right  text-white  text-2xl"></i>
              </button>
     </div> -->
    <div v-for="(item, index) in sliderItems" :key="item">
      <transition name="fade">
        <div
          class="slides w-full md:px-20  px-5  relative pt-0"
          v-if="currentSlide == index"
          @onmouseenter="mouseMove(index)"
        >
          <div class="text-white text-left">
            <span class="descriptText">{{ item.description }}</span>
          </div>
          <div class="mt-5 text-white">
            <span class="text-lg font-bold"> {{ item.title }}</span>
          </div>

       
        </div>
      </transition>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    sliderItems: {
      type: Array,
      required: true,
    },
  },
  data() {
    return {
      title: "Slider",
      currentSlide: 0,
      Interval: null,
      isTitleShowing: true,
    };
  },

  methods: {
    controlClick(value) {
      console.log("______THE VALUE:", value);
      if (value === "rotateBack" && this.currentSlide != 0) {
        this.currentSlide -= this.currentSlide;
      }

      if (
        value === "rotateFront" &&
        this.currentSlide != this.sliderItems?.length
      ) {
        this.currentSlide += this.currentSlide;
      }
    },
    mouseMove(value) {
      alert(value);
    },
  },
  mounted() {
    var vm = this;
    this.Interval = setInterval(
      () => {
        console.log("vm.sliderItems.length", vm.sliderItems.length);
        if (vm.currentSlide == vm.sliderItems.length) {
          vm.currentSlide = 0;
        } else {
          vm.currentSlide++;
        }
      },
      10000,
      vm
    );
  },
  beforeUnmount() {
    clearInterval(this.Interval);
  },
};
</script>

<style scoped>
#pp {
  padding: 0px !important;
  margin: 0px !important;
}
.slider-box {
  display: flex;
  width: 100%;
  position: relative;
}
/* .slider-box .carousel{
    padding-top:380px !important;
    
 } */
.flex-column {
  width: 100%;
  display: flex;
  flex-direction: column;
  text-align: center;
}
.slides {
  width: 100%;
  height: 300px;
  position: absolute;
  /* padding:50px; */
  /* background-color: azure; */
}
.slide-1 {
  background-color: blue;
}
.slide-2 {
  background-color: rgb(29, 228, 135);
}
.slide-3 {
  background-color: rgb(230, 35, 762);
}
.fade-enter-active,
.fade-leave-active {
  transition: all 1s ease;
  /* transition:opacity 0.5s ease; */
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
  transform: translateX(100%);
}
/* .fade-enter-from {
   opacity:0;
   transform:translateX(-100%);
  }
  
   .fade-leave-to{
   opacity:0;
   transform:translateX(100%);
 } */
.parot {
  display: flex;
  position: absolute;
  bottom: 0px;
  top: 520px;
  justify-content: center;
  text-align: center;
  width: 100%;
}
.parot-box {
  text-align: center;
  padding: 20px;
  background-color: white;
  margin-left: 10px;
  cursor: pointer;
}

.descriptText {
  font-size: 15px;
  font-weight: lighter;
  font-family: Avenir;
}
</style>