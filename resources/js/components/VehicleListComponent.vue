<template>
  <div>

    <!-- Search -->
    <div class="mb-4">
      <input
        v-model="search"
        type="text"
        placeholder="Buscar por placa ou modelo..."
        class="w-full px-4 py-2 border text-gray-600 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm"
      />
    </div>

    <!-- Lista -->
    <div v-if="filteredVehicles.length" class="space-y-2 mb-4">

      <div
        v-for="(veiculo, index) in filteredVehicles"
        :key="index"
        tabindex="0"
        role="button"
        aria-label="Informações do veículo"
        @click="selectVehicle(veiculo, index)"
        @keydown.enter="selectVehicle(veiculo, index)"
        :class="[
          'border border-gray-200 rounded-lg px-4 py-3 transition duration-200 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer',
          selectedIndex === index
            ? 'bg-(--evogard-orange-rgba)'
            : 'bg-white hover:bg-blue-100 focus:bg-blue-300'
        ]"
      >
        <div class="flex items-center justify-between">

          <!-- Esquerda -->
          <div>
            <h2 class="text-base font-semibold text-gray-800 tracking-wider leading-tight">
              {{ veiculo.plate || "SEM PLACA" }}
            </h2>
            <p class="text-sm text-gray-500 leading-tight">
              {{ veiculo.model }}
            </p>
          </div>

          <!-- Direita -->
          <div class="flex items-center gap-3">

            <!-- Status -->
            <span
              :class="[
                'px-2 py-0.5 text-xs font-semibold rounded-full',
                statusColor(veiculo.status)
              ]"
            >
              {{ veiculo.status }}
            </span>

            <!-- Seta -->
            <span class="text-gray-300 text-[22px] transition-transform duration-200 group-hover:translate-x-1">
              <i class="fa-solid fa-angle-right"></i>
            </span>

          </div>
        </div>
      </div>

    </div>

    <!-- Estado vazio -->
    <div v-else class="text-center text-gray-400 py-10">
      <i class="fa-solid fa-car text-[30px]"></i> <br>
      <label>Nenhum veículo encontrado.</label>
    </div>

  </div>
</template>

<script>
export default {
  name: "VehicleListComponent",

  props: {
    veiculos: {
      type: Array,
      required: true,
      default: () => []
    }
  },

  emits: ["selectedPlate"],

  data() {
    return {
      search: "",
      selectedIndex: null
    }
  },

  computed: {
    filteredVehicles() {
      if (!this.search) return this.veiculos

      const term = this.search.toLowerCase()

      return this.veiculos.filter(v => {
        const plate = (v.plate || "").toLowerCase()
        const model = (v.model || "").toLowerCase()

        return plate.includes(term) || model.includes(term)
      })
    }
  },

  methods: {

    selectVehicle(veiculo, index) {
      this.selectedIndex = index
      this.$emit("selectedPlate", veiculo)
    },

    statusColor(status) {
      if (status === "CANCELADO") return "bg-red-100 text-red-600"
      if (status === "ATIVO") return "bg-green-100 text-green-600"
      return "bg-yellow-100 text-yellow-600"
    }
  }
}
</script>