<template>
  <div
    class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 px-4"
  >
    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden">

      <!-- HEADER -->
      <div class="flex items-center justify-between px-6 py-4 border-b border-b-neutral-200">

        <div class="flex items-center gap-3">
          <i class="w-10 h-10 text-center fa-solid fa-file-invoice-dollar text-(--evogard-orange) bg-(--evogard-orange-rgba) p-2 rounded-full text-xl"></i>

          <h2 class="text-lg font-semibold text-neutral-600">
            Boleto atualizado
          </h2>
        </div>

        <button
          @click="$emit('close')"
          class="text-gray-400 hover:text-gray-600 transition"
        >
          <i class="fa-solid fa-xmark text-lg"></i>
        </button>

      </div>

      <!-- BODY -->
      <div class="p-6 space-y-6">

        <!-- Linha Digitável -->
        <div>

          <label class="text-sm text-gray-500">
            Linha Digitável
          </label>

          <div class="mt-2 bg-gray-100 rounded-xl p-4 flex items-center justify-between gap-3">

            <span class="text-sm text-gray-700 break-all">
              {{ boleto.linha_digitavel }}
            </span>

            <button
              @click="copyLine"
              class="transition text-(--evogard-orange) hover:text-blue-700"
            >

              <!-- Ícone COPY -->
              <i
                v-if="!copied"
                class="fa-regular fa-copy text-[25px]"
              ></i>

              <!-- Ícone CHECK -->
              <i
                v-else
                class="fa-solid fa-check text-(--evogard-orange) text-[25px]"
              ></i>

            </button>

          </div>

        </div>

        <!-- Vencimento -->
        <div class="flex items-center gap-3 text-gray-600">

          <i class="fa-regular fa-calendar text-(--evogard-orange)"></i>

          <span>
            Vencimento: <b>{{ boleto.nova_data_vencimento }}</b>
          </span>

        </div>

        <div class="flex flex-col space-y-2">
          <!-- Botão PDF -->
          <a
            :href="boleto.link_boleto"
            target="_blank"
            class="block w-full text-center bg-(--evogard-orange) hover:bg-(--evogard-blue) text-white font-semibold py-3 rounded-xl transition"
          >
            <i class="fa-solid fa-download mr-2"></i>
            Baixar PDF do Boleto
          </a>

          <a
            href="/"
            class="block w-full text-center bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-3 rounded-xl transition"
          >
            <i class="fa-solid fa-arrow-left mr-2"></i>
            Voltar
          </a>
        </div>

      </div>

    </div>
  </div>
</template>

<script>
export default {
  name: "BoletoUpdatedModal",
  props: {
    boleto: {
      type: Object,
      required: true
    }
  },
  emits: ['close'],
  data() {
    return {
      copied: false
    }
  },
  methods: {
    copyLine() {

      navigator.clipboard.writeText(this.boleto.linha_digitavel)

      this.copied = true

      setTimeout(() => {
        this.copied = false
      }, 3000)

    }
  }
}
</script>