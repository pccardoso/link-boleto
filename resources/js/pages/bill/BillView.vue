<template>

  <div class="p-6">

    <h1 class="text-2xl font-bold mb-6">
      Boletos
    </h1>

    <BoletosTableComponent
      v-if="boletos.length"
      :data="boletos"
      @view-boleto="verBoleto"
    />

  </div>

</template>

<script>

import axios from 'axios'
import BoletosTableComponent from '@/components/BoletosTableComponent.vue';

export default {

  name: "BillView",

  components: {
    BoletosTableComponent
  },

  data() {
    return {
      boletos: []
    }
  },

  async mounted() {

    const response = await axios.get('/bills/all')

    this.boletos = response.data.data

  },

  methods: {

    verBoleto(boleto) {

      console.log("Boleto clicado:", boleto)

      // exemplo abrir pdf
      window.open(boleto.link_boleto, '_blank')

    }

  }

}

</script>