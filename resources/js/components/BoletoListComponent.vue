<template>
  <div v-if="boletos.length" class="space-y-3">

    <div v-for="(boleto, index) in boletos" :key="index"
      class="bg- border border-gray-200 rounded-xl p-4 shadow-sm hover:shadow-md transition duration-200">
      <div class="flex items-center justify-between">
      
        <!-- ESQUERDA -->
        <div>
          <h2 class="text-sm text-gray-500 tracking-wide">
            Boleto #{{ boleto.nosso_numero }}
          </h2>

          <p class="text-2xl font-bold text-gray-800 mt-1">
            {{ formatCurrency(boleto.valor_boleto) }}
          </p>

          <p class="text-sm text-gray-500 mt-1">
            Vencimento: {{ formatDate(boleto.data_vencimento_original) }}
          </p>
        </div>

        <!-- DIREITA -->
        <div class="flex flex-col items-end gap-3">

          <!-- Status -->
          <span :class="[
            'px-3 py-1 text-xs font-semibold rounded-full',
            isExpired(boleto.data_vencimento_original)
              ? 'bg-red-100 text-red-600'
              : 'bg-green-100 text-green-600'
          ]">
            {{ isExpired(boleto.data_vencimento_original) ? 'Vencido' : 'A vencer' }}
          </span>

          <!-- Botões -->
          <div class="flex gap-2">

            <!-- Ver boleto -->
            <a :href="boleto.link_boleto" target="_blank"
              class="bg-(--evogard-blue) hover:bg-(--evogard-orange) text-white text-sm px-4 py-2 rounded-lg transition duration-200">
              <i class="fa-solid fa-barcode"></i> Ver boleto
            </a>

            <!-- Copiar Pix -->
            <button v-if="boleto?.pix?.copia_cola" @click="copyPix(boleto?.pix?.copia_cola, index)"
              class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm px-4 py-2 rounded-lg transition duration-200">
              <i class="fa-brands fa-pix"></i> {{ copiedIndex === index ? 'Copiado!' : 'Copiar Pix' }}
            </button>

          </div>

        </div>
      </div>

      <div v-if="isExpired(boleto.data_vencimento_original)"
  class="mt-3 rounded-xl border border-red-300 bg-red-50 p-4 shadow-sm transition-all">

  <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">

    <!-- Lado esquerdo -->
    <div class="flex items-start gap-3">
      <div class="bg-red-200 p-2 rounded-full w-10 h-10 flex justify-center items-center">
        <i class="fa-solid fa-triangle-exclamation text-red-500 text-[22px]"></i>
      </div>

      <div>
        <p class="text-red-700 font-semibold text-sm">
          Boleto expirado
        </p>

        <p class="text-gray-600 text-sm mt-1">
          Está vencido há
          <span class="font-bold text-red-600">
            {{ daysLate(boleto.data_vencimento_original) }} dias
          </span>
        </p>
      </div>
    </div>

    <!-- Ações -->
    <div class="flex w-full md:w-auto">

      <!-- Mais de 5 dias -->
      <button
        v-if="daysLate(boleto.data_vencimento_original) > 5"
        @click="$emit('actionLinkSurvey', boleto)"
        class="w-full md:w-auto bg-red-600 hover:bg-red-700 text-white text-sm px-4 py-3 md:py-2 rounded-lg transition-all shadow-md">
        <i class="fa-solid fa-camera mr-1"></i>
        Fazer Vistoria
      </button>

      <!-- Até 5 dias -->
      <button
        v-else-if="daysLate(boleto.data_vencimento_original) <= 5"
        @click="atualizarBoleto(boleto)"
        class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-3 md:py-2 rounded-lg transition-all shadow-md">
        Atualizar boleto
      </button>

    </div>

  </div>
</div>
    </div>

  </div>

  <!-- Estado vazio -->
  <div v-else class="text-center text-gray-500 py-10">
    <i class="fa-solid fa-barcode text-[30px]"></i> <br>
    <label for="emptyBolet">Nenhum boleto encontrado.</label>
  </div>
</template>

<script>
export default {
  name: "BoletoListComponent",
  props: {
    boletos: {
      type: Array,
      required: true,
      default: () => []
    }
  },
  emits: ['actionUpdateMaturity', 'actionLinkSurvey'],
  data() {
    return {
      copiedIndex: null
    }
  },
  methods: {
    formatCurrency(value) {
      return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL'
      }).format(parseFloat(value))
    },

    formatDate(date) {
      return new Date(date).toLocaleDateString('pt-BR')
    },

    isExpired(date) {
      return new Date(date) < new Date()
    },

    daysLate(date) {
      const today = new Date()
      const targetDate = new Date(date)

      // Zera horas para evitar erro por diferença de horário
      today.setHours(0, 0, 0, 0)
      targetDate.setHours(0, 0, 0, 0)

      const diffInMs = today - targetDate
      const diffInDays = diffInMs / (1000 * 60 * 60 * 24)

      return diffInDays > 0 ? diffInDays : 0
    },

    async copyPix(value, index) {
      try {
        await navigator.clipboard.writeText(value)

        this.copiedIndex = index

        setTimeout(() => {
          this.copiedIndex = null
        }, 2000)

      } catch (error) {
        console.error('Erro ao copiar:', error)
        alert('Não foi possível copiar o Pix.')
      }
    },
    atualizarBoleto(bolet) {

      this.$emit('actionUpdateMaturity', bolet);

    }
  }
}
</script>