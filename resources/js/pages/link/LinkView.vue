<template>

    <div class="p-6">

      <h1 class="text-2xl font-bold mb-4">Links</h1>

      <p>Bem-vindo ao links! Aqui você pode acessar as funcionalidades do sistema.</p>

    </div>


</template>

<script>

import axios from 'axios';

export default {

  name: "LinkView",
  components: {
    
  },
  async mounted() {
    
    const response = await axios.get('/links/all');

  },

}

</script>