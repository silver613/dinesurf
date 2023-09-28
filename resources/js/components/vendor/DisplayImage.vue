<template>
  <div >
    



    <div class="flex items-center  w-full  border border-dashed border-dinesurf-green-400 bg-gray-100 justify-between p-3">
              <div
              class="max-w-80 min-w-80 relative border  border-lg border-50  bg-white overflow-hidden"
              style="width: fit-content"
              :class=" this.file ? '' : 'px-10'"
            >
              <img
                :src="value"
                class="block w-auto h-24  bg-white"
                draggable="false"
                :id="'image-output' + type"
              />
              <!-- <div v-if="this.file" class="absolute -bottom-2  right-0 bg-white w-10 h-10 flex items-center justify-center p-1 rounded-full ">
                <i class="fa fa-trash  text-sm  cursor-pointer " @click="remove()"></i>
               </div> -->
            </div>
              <button type="button"  class="bg-dinesurf-green-400 w-32 relative h-10 pt-0 text-white">
              <span v-if="this.file">Change file</span>
              <span v-else>Choose a file</span>
                   <input
                    dusk="image"
                    type="file"
                    :id="'file-image' + type"
                    name="image"
                    accept="image/*"
                    class="form-file-input select-none  opacity-0  absolute top-2 left-4 "
                    @change="displayFile"
                  />
            </button>
  </div>





  </div>
  <!---->
</template>

<script>
export default {
  props: ["vendor", "type"],
  data() {
    return {
      responseText: "",
      file: null,
      value: null,
    };
  },

  methods: {

    remove(){
       this.file = null;
       this.value = null;
    },
    displayFile() {
      var files = document.getElementById("file-image" + this.type).files;

      var output = document.getElementById("image-output" + this.type);

      this.file = files[0];

      output.src = URL.createObjectURL(files[0]);

      output.onload = function () {
        URL.revokeObjectURL(output.src); // free memory
      };

      this.$emit("input", this.file);
    },
  },
};
</script>