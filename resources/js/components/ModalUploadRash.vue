<template>

  <LoadingBoleto v-model="loading" />

  <div v-if="!loading" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 px-4">

    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden max-h-[90vh] flex flex-col">

      <!-- HEADER -->
      <div class="flex items-center justify-between px-6 py-4 border-b border-neutral-200">

        <div class="flex items-center gap-3">

          <div class="w-10 h-10 flex justify-center items-center bg-(--evogard-orange-rgba) rounded-lg text-xl">
            <i class="fa-solid fa-file-invoice-dollar text-(--evogard-orange)"></i>

          </div>

          <h2 class="text-lg font-semibold text-neutral-600">
            Boleto atualizado
          </h2>
        </div>

        <button @click="$emit('close')" class="text-gray-400 hover:text-gray-600 transition">
          <i class="fa-solid fa-xmark text-lg"></i>
        </button>

      </div>

      <!-- BODY -->
      <div class="p-6 space-y-6 overflow-y-auto flex-1">

        <div
          class="w-full rounded-lg p-5 flex flex-col items-center gap-3 bg-(--evogard-orange-rgba) text-(--evogard-orange)">

          <p class="text-sm text-neutral-600">
            Use o código abaixo no seu vídeo de vistoria:
          </p>

          <div class="text-[26px] font-bold tracking-widest bg-white px-4 py-3 rounded-lg">
            {{ hash }}
          </div>

          <p class="text-xs text-neutral-600 text-center">
            Esse código precisa aparecer no vídeo. Se tiver dúvidas, veja um exemplo de como gravar.
          </p>

        </div>
        <div class="space-y-4 flex justify-center">

          <!-- Botão -->
          <button v-if="!showVideo" @click="showVideo = true"
            class="px-4 py-2 bg-(--evogard-orange) text-white rounded-lg hover:bg-(--evogard-blue) transition">
            <i class="fa-solid fa-circle-play"></i>
            Ver modelo de vídeo
          </button>

          <!-- Vídeo -->
          <div v-if="showVideo" class="rounded-xl overflow-hidden shadow-lg border border-gray-200">
            <video controls class="w-full">
              <source src="../../video/EXEMPLE_VISTORIA.mp4" type="video/mp4">
              Seu navegador não suporta vídeo.
            </video>
          </div>

        </div>

        <!-- UPLOAD -->
        <div>

          <label class="block text-sm font-medium text-gray-700 mb-2">
            Enviar vídeo de vistoria
          </label>

          <div
            class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center cursor-pointer hover:border-(--evogard-orange) transition relative"
            @click="openFile">

            <input ref="file" type="file" class="hidden" accept="video/*" @change="handleFile" />

            <!-- SEM ARQUIVO -->
            <div v-if="!file" class="flex flex-col items-center gap-3 text-gray-500">

              <i class="fa-solid fa-cloud-arrow-up text-3xl"></i>

              <p class="text-sm">
                Clique para enviar um vídeo
              </p>

              <span class="text-xs text-gray-400">
                Formatos suportados: MP4, MOV, AVI
              </span>

            </div>

            <!-- COM ARQUIVO -->
            <div v-else class="flex items-center justify-between gap-3 text-left">

              <div class="flex items-center gap-3">

                <i class="fa-solid fa-video text-(--evogard-orange) text-xl"></i>

                <div>
                  <p class="text-sm font-medium text-green-600">
                    {{ file.name }}
                  </p>

                  <p class="text-xs text-gray-400">
                    {{ formatSize(file.size) }}
                  </p>
                </div>

              </div>

              <button @click.stop="removeFile" class="text-red-400 hover:text-red-600 transition">
                <i class="fa-solid fa-trash"></i>
              </button>

            </div>

          </div>

        </div>

      </div>

      <!-- FOOTER -->
      <div class="flex gap-3 px-6 pb-6">

        <button
          class="flex-1 bg-(--evogard-orange) hover:bg-(--evogard-blue) text-white font-semibold py-3 rounded-xl transition disabled:opacity-50"
          :disabled="!file" @click="uploadFileMoovie">
          Enviar
        </button>

        <button class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-3 rounded-xl transition"
          @click="$emit('close')">
          Cancelar
        </button>

      </div>

    </div>

  </div>
</template>

<script>
import { validateHashPlate, uploadMoovie } from "@/api/hash-plate"
import LoadingBoleto from "./LoadingBoleto.vue";

export default {

  name: "ModalUploadRash",

  emits: ["close", "updatedBill"],

  props: {
    plate: {
      type: String,
      required: true
    },
    nosso_numero: {
      type: Number,
      required: true
    }
  },
  components: {
    LoadingBoleto
  },
  data() {
    return {
      hash: "",
      file: null,
      showVideo: false,
      loading: false
    }
  },
  methods: {

    openFile() {
      this.$refs.file.click()
    },

    handleFile(event) {

      const file = event.target.files[0]

      if (!file) return

      if (!file.type.startsWith("video/")) {
        alert("Apenas vídeos são permitidos")
        return
      }

      this.file = file
    },

    removeFile() {
      this.file = null
      this.$refs.file.value = null
    },

    formatSize(size) {

      const mb = size / (1024 * 1024)

      if (mb < 1) {
        return (size / 1024).toFixed(2) + " KB"
      }

      return mb.toFixed(2) + " MB";
    },

    async generateHash() {

      try {

        const response = await validateHashPlate({
          plate: this.plate
        })

        if (response.status === 200) {
          this.hash = response.data.data.hash
        }

      } catch (error) {
        console.error(error)
      }

    },

    async uploadFileMoovie() {

      this.loading = true;

      try {

        const formData = new FormData()

        formData.append("moovie", this.file)
        formData.append("plate", this.plate)
        formData.append("hash", this.hash)
        formData.append("nosso_numero", this.nosso_numero);

        const response = await uploadMoovie(formData)

        if (response.status === 200) {
          this.loading = false;
          this.$emit('updatedBill', response);
        }

        console.log(response)

      } catch (error) {

        console.error(error)

      }

    }

  },

  beforeMount() {
    this.generateHash()
  }

}
</script>