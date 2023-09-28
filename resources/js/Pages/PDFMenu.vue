<template>
 
      <div  class="relative h-screen ">
          <div id="pdf-wrapper">
            <iframe id="pdf-viewer" frameborder="0" scrolling="no"></iframe>
         </div>
   </div> 
 </template>
 
 <script>
 import AppLayout from "@/Layouts/AppLayout.vue";
 import JetAuthenticationCardLogo from "@/Jetstream/AuthenticationCardLogo2.vue";
 
 export default {
   props: [ "vendor",  "setting"],
   components: {
     AppLayout,
     JetAuthenticationCardLogo
   },
   data() {
     return {
        pdf_link: this.setting?.pdf_url,
        pdfURL : "https://storage.googleapis.com/ds_menu/Taj-Official-Menu.pdf"
        //  'https://dinesurf.s3.eu-central-1.amazonaws.com/menu_setting_pdfs/6ZWny3AQiUJb2fBpupeqDXIKFqzX00jLlrHEdiyU.pdf'
    };
   },


   methods:{
             isIOS() {
                return /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
            },
               getPDFViewerURL(pdfURL) {
                if (this.isIOS()) {
                    return `https://drive.google.com/viewerng/viewer?embedded=true&url=${pdfURL}`;
                } else {
                    return `https://docs.google.com/gview?embedded=true&url=${pdfURL}`;
                }
            }
   },

   mounted() {
  
    if (this.vendor) {
       document.title = this.vendor.company_name + " - menus";
       document.querySelector('link[rel=icon]').href = this.vendor.profile_photo_url;
     }
     const pdfViewer = document.getElementById("pdf-viewer");
      pdfViewer.src = this.getPDFViewerURL(this.pdf_link);
 
   },
 
  
 };
 </script>


<style>
#pdf-wrapper {
    width: 100%;
    height: 100vh;
    overflow: auto;
}
#pdf-viewer {
    width: 100%;
    height: 100%;
    border: none;
}
</style>