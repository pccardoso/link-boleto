<template>

  <div class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50">

    <div class="bg-white w-[900px] h-[80vh] rounded-2xl shadow-2xl flex flex-col overflow-hidden">

      <!-- HEADER -->
      <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-gray-50">

        <div class="flex items-center gap-2">

          <i class="fa-solid fa-paperclip text-blue-600"></i>

          <h2 class="text-lg font-semibold text-gray-700">
            Anexos
          </h2>

          <span class="text-xs bg-blue-100 text-blue-600 px-2 py-1 rounded-full">
            {{ anexos.length }}
          </span>

        </div>

        <button @click="closeModal" class="text-gray-400 hover:text-black transition">
          <i class="fa-solid fa-xmark"></i>
        </button>

      </div>


      <!-- CONTEÚDO -->
      <div class="flex flex-1 overflow-hidden">

        <!-- LISTA -->
        <div class="w-[35%] border-r border-gray-200 overflow-y-auto">

          <!-- SEARCH -->
          <div class="p-3 border-b border-gray-200">
            <input
              v-model="search"
              type="text"
              placeholder="Buscar anexo..."
              class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
          </div>

          <div v-for="item in filteredAnexos" :key="item.id"
            class="flex items-center justify-between px-4 py-3 cursor-pointer transition group"
            :class="selectedItem && selectedItem.id === item.id ? 'bg-blue-50 border-l-4 border-blue-500' : 'hover:bg-gray-100'"
            @click="openPreview(item)">

            <div class="flex items-center gap-3">

              <div class="w-9 h-9 flex items-center justify-center rounded-lg bg-gray-100 group-hover:bg-blue-100">

                <i class="fa-solid fa-video text-gray-500 group-hover:text-blue-600"></i>

              </div>

              <div class="flex flex-col">

                <span class="text-sm text-gray-700 font-medium">
                  {{ item.path.split('/')[1] }}
                </span>

                <span class="text-xs text-gray-400">
                  vídeo
                </span>

              </div>

            </div>

            <i class="fa-solid fa-eye text-gray-400 group-hover:text-blue-600"></i>

          </div>

        </div>


        <!-- PREVIEW -->
        <div class="flex-1 flex flex-col bg-neutral-100">

          <!-- QUANDO TEM VIDEO -->
          <div v-if="selectedItem" class="flex flex-col h-full p-5 overflow-hidden">

            <!-- NOME -->
            <div class="text-sm text-gray-500 mb-3 flex items-center gap-2">

              <i class="fa-solid fa-video"></i>

              {{ selectedItem.path.split('/')[1] }}

            </div>

            <!-- PLAYER -->
            <div class="flex-1 flex items-center justify-center p-6 overflow-hidden">

              <video controls class="max-h-full max-w-full object-contain rounded-lg" :src="videoUrl"></video>

            </div>

          </div>


          <!-- PLACEHOLDER -->
          <div v-else class="flex-1 flex flex-col items-center justify-center text-center text-gray-400 mb-4">

            <i class="fa-solid fa-film text-4xl mb-4"></i>

            <h3 class="text-sm font-medium text-gray-600">
              Nenhum vídeo selecionado
            </h3>

            <p class="text-xs mt-1 max-w-[240px]">
              Escolha um anexo na lista para visualizar o vídeo aqui
            </p>

          </div>

        </div>

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
      selectedItem: null,
      search: ""
    }
  },

  computed: {

    videoUrl() {

      if (!this.selectedItem) return null

      return import.meta.env.VITE_URL_AWS + '/' + this.selectedItem.path

    },

    filteredAnexos() {

      if (!this.search) return this.anexos

      return this.anexos.filter(item =>
        item.path.split('/')[1]
          .toLowerCase()
          .includes(this.search.toLowerCase())
      )

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