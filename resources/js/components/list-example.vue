<template>

    <h1 class="mb-24">Vue Horizontal List</h1>

    <div class="p-24 bg-steam border-3">
      <h5>Just an empty DIV, for comparision.</h5>
    </div>

    <section>
      <vue-horizontal-list :items="items" :options="options">
        <template v-slot:nav-prev>
          <div>👈</div>
        </template>

        <template v-slot:nav-next>
          <div>👉</div>
        </template>

        <template v-slot:start>
          <div>First Item</div>
        </template>

        <template v-slot:end>
          <div>Last Item</div>
        </template>

        <template v-slot:default="{ item }">
          <div class="item">
            <h5>{{ item.title }}</h5>
            <p>{{ item.content }}</p>
          </div>
        </template>
      </vue-horizontal-list>
    </section>


   

</template>

<script>
  import VueHorizontalList from "vue-horizontal-list";
  export default {
    name: "ExampleHorizontalList",
    components: {VueHorizontalList},
    data() {
      return {
        items: [
          {
            title: 'Sed non ante non cras amet',
            content: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas non sagittis leo. Vestibulum sit amet metus nec neque dignissim dapibus.',
            image: 'https://picsum.photos/id/1015/600/600'
          },
          {
            title: 'Curabitur sit amet nunc',
            content: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec id mollis erat. Aliquam erat volutpat. Nunc erat lacus, rhoncus nec.',
            image: 'https://picsum.photos/id/1019/600/600'
          },
          {
            title: 'Proin pharetra, ante metus',
            content: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi ac diam ac ex efficitur posuere. Pellentesque cursus pellentesque risus, non.',
            image: 'https://picsum.photos/id/1039/600/600'
          },
          {
            title: 'Cras pharetra non enim a',
            content: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi malesuada varius nibh, a malesuada nisi feugiat eget. Aenean convallis semper.',
            image: 'https://picsum.photos/id/1042/600/600'
          },
          {
            title: 'Proin vulputate, augue eu accumsan',
            content: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas fringilla tempor libero sit amet mollis. Praesent quis leo erat. Integer.',
            image: 'https://picsum.photos/id/1044/600/600'
          },
          {
            title: 'Maecenas feugiat magna sapien in',
            content: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam sit amet fringilla ante. Quisque at ipsum non lacus consequat dictum.',
            image: 'https://picsum.photos/id/1057/600/600'
          },
          {
            title: 'Donec commodo sed enim at',
            content: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc eu condimentum risus. Praesent dignissim, neque nec pharetra vestibulum, libero odio.',
            image: 'https://picsum.photos/id/1063/600/600'
          },
          {
            title: 'In bibendum urna et turpis',
            content: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse vitae ante volutpat leo vulputate volutpat et sed ex. Vivamus eu.',
            image: 'https://picsum.photos/id/1076/600/600'
          },
          {
            title: 'Phasellus iaculis dignissim erat at',
            content: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla mattis quam scelerisque, eleifend purus gravida, scelerisque orci. Ut et turpis.',
            image: 'https://picsum.photos/id/1083/600/600'
          },
        ],
            options: {
                 
                 navigation: {
                    // when to show navigation
                    start:250,
                    color: "red",
                },
                 responsive: [
                       {end: 576, size: 1},
                        {start: 576, end: 768, size: 2},
                        {start: 768, end: 992, size: 3},
                        {start: 992, end: 1200, size: 4},
                        {start: 1200, size: 5}
                 ],
                  list: {
                    // 1200 because @media (min-width: 1200px) and therefore I want to switch to windowed mode
                    windowed: 1200,
                    
                    // Because: #app {padding: 80px 24px;}
                    padding: 24
        },
         autoplay: {
                    // enable/disable playing slideshow
                    play: true,
                    // the delay duration between slides in milliseconds
                    speed: 1800,
                    // if setup, the slideshow will be in the loop.
                    repeat: true,
                },
                                
            },
      }
    }
  }
</script>

<style scoped lang="less">
  section > div {
    margin-top: 24px;
    margin-bottom: 24px;
  }
  .Banner {
    background-image: url("https://picsum.photos/id/1011/2000/500");
  }
  .background-image {
    background-repeat: no-repeat;
    background-position: center;
    background-size: cover;
  }
  img {
    object-fit: cover;
    width: 100%;
    height: 100%;
  }
  .ImageBoxed {
    flex: 0 0 25%;
    max-width: 25%;
  }
</style>