<template>

  <div class="p-6 w-full max-w-full overflow-hidden">

    <h1 class="text-2xl font-bold mb-6">
      Boletos
    </h1>

    <LoadingComponent v-if="loading" />

    <BoletosTableComponent
      v-else-if="boletos.length && !loading"
      :data="boletos"
      @view-boleto="verBoleto"
    />

  </div>

</template>

<script>

import axios from 'axios'
import BoletosTableComponent from '@/components/BoletosTableComponent.vue';
import LoadingComponent from '@/components/LoadingComponent.vue';

export default {

  name: "BillView",

  components: {
    BoletosTableComponent,
    LoadingComponent
  },

  data() {
    return {
      boletos: [],
      loading: true,
    }
  },

  async mounted() {

    const response = await axios.get('/bills/all')
    this.boletos = response.data.data
    this.loading = false

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