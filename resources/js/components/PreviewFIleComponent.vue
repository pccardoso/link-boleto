<template>

  <div class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50">

    <div class="bg-white w-[800px] max-h-[90vh] rounded-2xl shadow-2xl flex flex-col overflow-hidden">

      <!-- HEADER -->
      <div class="flex items-center justify-between px-6 py-4 border-b border-gray-300 bg-gray-50">

        <div class="flex items-center gap-2">

          <!-- ÍCONE -->
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">

            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14m-6 4h6a2 2 0 002-2V8a2 2 0 00-2-2H9a2 2 0 00-2 2v8a2 2 0 002 2z" />
          </svg>

          <h2 class="text-lg font-semibold text-gray-700">
            Anexos
          </h2>

          <span class="text-xs bg-blue-100 text-blue-600 px-2 py-1 rounded-full">
            {{ anexos.length }}
          </span>

        </div>

        <button @click="closeModal" class="text-gray-400 hover:text-black transition">
          ✕
        </button>

      </div>

      <!-- LISTA -->
      <div class="px-6 py-4 max-h-56 overflow-y-auto">

        <div v-for="item in anexos" :key="item.id"
          class="flex items-center justify-between px-3 py-3 rounded-lg hover:bg-gray-100/70 transition-all duration-150 cursor-pointer group"
          @click="openPreview(item)">

          <div class="flex items-center gap-3">

            <!-- ÍCONE -->
            <div
              class="flex items-center justify-center w-9 h-9 bg-gray-100 rounded-lg group-hover:bg-blue-100 transition">

              <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-500 group-hover:text-blue-600"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">

                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M4 6h8a2 2 0 012 2v8a2 2 0 01-2 2H4z" />

              </svg>

            </div>

            <!-- NOME -->
            <div class="flex flex-col">

              <span class="text-sm text-gray-700 font-medium">

                {{ item.path.split('/')[1] }}

              </span>

              <span class="text-xs text-gray-400">
                vídeo
              </span>

            </div>

          </div>

          <!-- AÇÃO -->
          <div
            class="flex items-center justify-center w-8 h-8 rounded-md text-gray-400 group-hover:text-blue-600 group-hover:bg-blue-50 transition">

            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
              stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5
         c4.477 0 8.268 2.943 9.542 7
         -1.274 4.057-5.065 7-9.542 7
         -4.477 0-8.268-2.943-9.542-7z" />
            </svg>

          </div>

        </div>

      </div>

      <!-- PREVIEW -->
      <div v-if="selectedItem" class="flex-1 p-4 m-4 bg-neutral-300 rounded-3xl">

        <!-- NOME -->
        <div class="text-sm text-gray-500 mb-3 flex items-center gap-2">
          {{ selectedItem.path.split('/')[1] }}
        </div>

        <!-- PLAYER -->
        <div class="bg-black rounded-xl flex justify-center items-center p-3">

          <video controls class="max-h-[60vh] max-w-full object-contain rounded" :src="videoUrl"></video>

        </div>

      </div>

      <!-- PLACEHOLDER -->
      <div v-else class="flex-1 flex flex-col items-center justify-center text-center px-6 my-4">

        <!-- ÍCONE -->
        <div class="w-16 h-16 flex items-center justify-center rounded-xl bg-gray-100 mb-4">

          <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">

            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
              d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M4 6h8a2 2 0 012 2v8a2 2 0 01-2 2H4z" />

          </svg>

        </div>

        <!-- TEXTO -->
        <h3 class="text-sm font-medium text-gray-700">
          Nenhum vídeo selecionado
        </h3>

        <p class="text-xs text-gray-400 mt-1 max-w-[260px]">
          Escolha um anexo na lista acima para visualizar o vídeo aqui.
        </p>

      </div>

    </div>

  </div>

</template>

<script>

export default {

  name: "PreviewFileComponent",

  props: {
    anexos: {
      type: Array,
      default: () => []
    }
  },

  emits: ['close-modal'],

  data() {
    return {
      selectedItem: null
    }
  },

  computed: {

    videoUrl() {

      if (!this.selectedItem) return null

      return import.meta.env.VITE_URL_AWS + '/' + this.selectedItem.path

    }

  },

  methods: {

    closeModal() {
      this.selectedItem = null
      this.$emit('close-modal')
    },

    openPreview(item) {
      this.selectedItem = item
    }

  }

}

</script>