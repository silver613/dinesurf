<template>
  <div class="py-0 px-0 w-full">
    <div class="mb-6 w-full grid grid-cols-3 gap-3 ">
      <div
          class="h-20 md:w-[120px]    w-80  shadow-sm" 
          v-show="images.length >  0"  v-for="(image, index) in images" :key="index">
        <img
        :key="index"
          :src=" isUploading ? image.src : image.image_url"
          class="block  imageCustom  rounded-md "
          draggable="false"
          :id="'image-output' + type + index"
        />
      </div>
      <!---->
    </div>
    <!---->
    <div class="form-file ">
      <form @submit.prevent="submit()">

        <div  class="relative  border border-dashed  border-dinesurf-green-400
         md:w-full   w-80   flex items-center   flex-col justify-center bg-gray-200 ">
         <div class="absolute cursor-pointer w-full text-center flex flex-col items-center">
          <div>Add images</div>
              <svg width="20" height="20" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M27.5 3.74976C27.5 2.75519 27.1049 1.80137 26.4017 1.09811C25.6984 0.394844 24.7446 -0.000244141 23.75 -0.000244141C22.7554 -0.000244141 21.8016 0.394844 21.0984 1.09811C20.3951 1.80137 20 2.75519 20 3.74976V19.9998H3.75C2.75544 19.9998 1.80161 20.3948 1.09835 21.0981C0.395088 21.8014 0 22.7552 0 23.7498C0 24.7443 0.395088 25.6981 1.09835 26.4014C1.80161 27.1047 2.75544 27.4998 3.75 27.4998H20V43.7498C20 44.7443 20.3951 45.6981 21.0984 46.4014C21.8016 47.1047 22.7554 47.4998 23.75 47.4998C24.7446 47.4998 25.6984 47.1047 26.4017 46.4014C27.1049 45.6981 27.5 44.7443 27.5 43.7498V27.4998H43.75C44.7446 27.4998 45.6984 27.1047 46.4017 26.4014C47.1049 25.6981 47.5 24.7443 47.5 23.7498C47.5 22.7552 47.1049 21.8014 46.4017 21.0981C45.6984 20.3948 44.7446 19.9998 43.75 19.9998H27.5V3.74976Z" fill="#9DC64E"/>
              </svg>
           </div>
           <div  class="w-full  flex items-center">
                <input
                  dusk="image"
                  type="file"
                  :id="'file-image' + type"
                  name="image"
                  accept="image/*"
                  class="form-file-input  w-full opacity-0  h-20  select-none"
                  @change="displayFile"
                  multiple
                />
           </div>
             
        </div>
      
        <div class="flex flex-col md:flex-row mt-3"  v-show="images.length > 0">
       <!--     <div>
            <button
              type="submit"
              class="
              w-32
                inline-flex
                items-center
                px-4
                py-2
                bg-dinesurf-green-400
                border border-transparent
                rounded-md
                font-semibold
                text-xs text-white
                uppercase
                tracking-widest
                hover:bg-gray-700
                active:bg-gray-900
                focus:outline-none
                focus:border-gray-900
                focus:ring
                focus:ring-gray-300
                disabled:opacity-25
                transition
              "
              :disabled="
                responseText == 'Saving...' || responseText == 'Saved.'
              "
            >
              Save
            </button>
          </div> -->
          <span class="ml-5" v-html="responseText"> </span>
        </div>
      </form>
    </div>
  </div>
</template>


<script>
export default {
  props: ["vendor", "type", "selectedImages"],
  data() {
    return {
      responseText: "",
      files: [],
      images: this.selectedImages ? this.selectedImages : [],
      isUploading: false,
    };
  },

  methods: {

    handleImages(e){
        this.$emit('handleImages', e);
       },

    displayFile() {
      var imagefiles = document.getElementById("file-image" + this.type).files;
        this.isUploading = true;
      if (this.responseText) {
        this.files = [];
        this.images = [];

        Array.from(imagefiles).forEach((file_element, index) => {
          setTimeout(
            () => {
              document.getElementById("image-output" + this.type + index).src =
                "";
            },
            1000,
            index
          );
        });
      }

      Array.from(imagefiles).forEach((file_element, index) => {
        this.files[index] = file_element;
        this.images[index] = {
          src: null,
        };

        setTimeout(
          () => {
            var output = document.getElementById(
              "image-output" + this.type + index
            );
            output.src = URL.createObjectURL(file_element);
            output.onload = function () {
              URL.revokeObjectURL(output.src); // free memory
            };
          },
          1000,
          file_element,
          index
        );
      });

      this.handleImages(this.files)
    },
    submit() {
      this.responseText = "Saving...";
      var formData = new FormData();

      this.files.forEach((file) => {
        formData.append("images[]", file);
      });
      axios
        .post(route("vendors.upload-images"), formData)
        .then((result) => {
          this.$parent.getImages();
          document.getElementById("file-image" + this.type).value = null;
          this.images = [];
          this.responseText = "Saved.";
          let vm = this;
          setTimeout(
            function () {
              vm.responseText = null;
            },
            500,
            vm
          );
        })
        .catch((err) => {
          console.log(err);
          if (err.response) {
            if (err.response.status == 422) {
              this.responseText =
                '<span class="text-red-700 font-bold block"> Error Uploading Files...one of the Files might be too large..max file size is 2mb</span>';
              toastr.error("one of the files is too large");
              toastr.error("max file size 2mb");
            }
            return;
          }
          this.responseText =
            '<span class="text-red-700 font-bold block"> Error Uploading Files...one of the Files might be too large..max file size is 2mb</span>';
          this.handleApiError(err);
        });
    },
    checkBannerFileSize() {
      var reader = new FileReader();

      //Read the contents of Image File.
      reader.readAsDataURL(this.file);
      reader.onload = function (e) {
        //Initiate the JavaScript Image object.
        var image = new Image();

        //Set the Base64 string return from FileReader as source.
        image.src = e.target.result;

        //Validate the File Height and Width.
        image.onload = function () {
          var height = this.height;
          var width = this.width;
          if (height > 2660 || width > 100) {
            alert("Height and Width must not exceed 100px.");
            return false;
          }
          alert("Uploaded image has valid Height and Width.");
          return true;
        };
      };
    },
    gallery() {
      this.$parent.getImages();
    },
  },

  mounted(){
    
    console.log("images:", this.images);
  }
};
</script>

<style>
  .imageCustom{
    object-fit: cover;
    width: 100% !important;
    height:100% !important;
  }
  .imageContainerCustom{
    width: 220px;
    height: 200px;
  }

  
 /* @media (max-width: 650px) {
   
   .imageContainerCustom{
     display:'none'
   }

 } */



</style>