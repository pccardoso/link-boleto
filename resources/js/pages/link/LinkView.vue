<template>

    <div class="p-6">

      <h1 class="text-2xl font-bold mb-4">Links</h1>

      <p>Bem-vindo ao links! Aqui você pode acessar as funcionalidades do sistema.</p>

      <HashPlateComponent :data="hashPlates" />

    </div>


</template>

<script>

import axios from 'axios';
import HashPlateComponent from '@/components/HashPlateComponent.vue';

export default {

  name: "LinkView",
  components: {
    HashPlateComponent
  },
  data(){
    return {
      hashPlates: []
    }       
  },
  async mounted() {
    
    const response = await axios.get('/links/all');

    this.hashPlates = response.data.data;

  },

}

</script>