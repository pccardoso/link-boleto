<template>
  <div
    v-if="modelValue"
    class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 px-4"
  >
    <div class="bg-white w-full max-w-sm rounded-2xl shadow-2xl p-8 text-center">

      <!-- Spinner -->
      <div class="flex justify-center items-center mb-5">
        <div class="w-16 h-16 border-6 border-(--evogard-orange-rgba) border-t-(--evogard-orange) rounded-full animate-spin"></div>
      </div>

      <!-- Texto -->
      <h2 class="text-lg font-semibold text-gray-700">
        {{ messages.title }}
      </h2>

      <p class="text-sm text-gray-500 mt-2">
        {{ messages.description }}
      </p>

    </div>
  </div>
</template>

<script>
export default {
  name: "LoadingBoleto",

  props: {
    modelValue: {
      type: Boolean,
      default: false
    },

    type: {
      type: Number,
      default: 1
    }
  },

  computed: {
    messages() {

      const list = {
        1: {
          title: "Enviando vídeo de vistoria e gerando seu novo boleto...",
          description: "Isso pode levar alguns minutos, favor não feche a tela!"
        },

        2: {
          title: "Gerando boleto atualizado...",
          description: "Aguarde alguns segundos!"
        }
      };

      return list[this.type] || list[1];
    }
  }
}
</script>