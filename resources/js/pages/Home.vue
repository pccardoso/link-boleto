<template>
  <div class="min-h-screen bg-(--evogard-blue) flex items-center justify-center px-4">

    <div class="bg-white w-full max-w-lg rounded-2xl shadow-xl p-8 my-7">

      <!-- Título -->
      <div class="flex flex-col items-center justify-center gap-3 mb-8">

        <!-- Imagem -->
        <img src="../../img/MARCA-EVOGARD.png" alt="Boletos" class="h-12">

        <!-- Texto -->
        <div class="text-center">
          <h1 class="text-2xl font-bold text-(--evogard-blue)">
            Consulta de Boletos
          </h1>
          <p class="text-gray-500 text-sm mt-1">
            Informe a placa do veículo para consultar boletos em aberto
          </p>
        </div>

      </div>

      <HorizontalSelect v-model="tipoSelecionado" :options="listSelect" class="mb-4" />

      <!-- Campo Placa -->
      <div class="mb-6 space-y-2">
        <label class="block text-sm font-medium text-gray-700 mb-2">
          {{ labelTypeSearch }}
        </label>

        <input v-model="placa" type="text" :placeholder="plaholderTypeSearch"
          class="w-full px-4 py-3 border text-gray-600 border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" />
      </div>

      <VehicleListComponent :veiculos="veiculos" @selectedPlate="actionSelectedPlate" class="mb-4"
        v-if="tipoSelecionado === 'cpf'" />

      <BoletoListComponent v-if="viewBoletos" :boletos="pegarBoletoMaisAntigo" class="mb-4"
        @actionUpdateMaturity="updateBolet" @actionLinkSurvey="openModalLink" />

      <!-- Botões -->
      <div class="flex gap-3">

        <BaseButton :loading="loadingButton" :disabled="validateInput" @click="getPlate">
          Consultar Boletos
        </BaseButton>

        <button @click="clearForm"
          class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-3 rounded-xl transition duration-200">
          Limpar
        </button>

      </div>

    </div>

  </div>

  <BoletoUpdatedModal 
    v-if="showModalUpdate"
    :boleto="boletUpdateCurrent"
    @close="showModalUpdate = false"
  />

  <LoadingBoleto 
    v-model="loadingUpdateBolet"
  />

  <ModalUploadRash 
    v-if="showModalLink"
    :plate="plateHashCurrent"
    :nosso_numero="nossoNumeroCurrent"
    @close="showModalLink = false"
    @updatedBill="updatedBolet"
  />

</template>

<script>

import BaseButton from '@/components/BaseButton.vue';
import VehicleListComponent from '@/components/VehicleListComponent.vue';
import HorizontalSelect from '@/components/HorizontalSelect.vue';
import { getAllVehicle } from '@/api/vehicle';
import { getAllBoletOfPeople, getAllBoletOfPlate, updateBoleto } from '@/api/vehicle';
import BoletoListComponent from '@/components/BoletoListComponent.vue';
import { validateDocument } from '@/helpers/utils';
import BoletoUpdatedModal from '@/components/BoletoUpdatedModal.vue';
import LoadingBoleto from '@/components/LoadingBoleto.vue';
import ModalUploadRash from '@/components/ModalUploadRash.vue';

export default {
  name: "Home",

  data() {
    return {
      placa: "",
      veiculos: [],
      loadingButton: false,
      listSelect: [
        { icon: "fa-solid fa-car", label: "Placa", value: "plate" },
        { icon: "fa-solid fa-user", label: "CPF", value: "cpf" },
      ],
      tipoSelecionado: "plate",
      configPlaceholder: {
        plate: "Selecione a placa",
        cpf: "Digite o CPF/CNPJ"
      },
      configLabel: {
        plate: "Placa do Veículo:",
        cpf: "CPF/CNPJ do Cliente:"
      },
      boletos: [],
      viewBoletos: false,
      boletosCurrent: [],
      errors: {},
      showModalUpdate: false,
      boletUpdateCurrent: {},
      loadingUpdateBolet: false,
      showModalLink: false,
      plateHashCurrent: '',
      nossoNumeroCurrent: 0,
    }
  },

  computed: {

    plaholderTypeSearch() {
      return this.configPlaceholder[this.tipoSelecionado] ?? "Não encontrado"
    },
    labelTypeSearch() {
      return this.configLabel[this.tipoSelecionado] ?? "Não encontrado"
    },
    validateInput() {
      return !validateDocument(this.tipoSelecionado, this.placa);
    },
    pegarBoletoMaisAntigo() {
      if (!Array.isArray(this.boletosCurrent) || this.boletosCurrent.length === 0) {
        return [];
      }

      const boletoMaisAntigo = this.boletosCurrent.reduce((maisAntigo, atual) => {
        if (!maisAntigo) return atual;

        return new Date(atual.data_vencimento) < new Date(maisAntigo.data_vencimento)
          ? atual
          : maisAntigo;
      }, null);

      return [boletoMaisAntigo];
    }

  },

  watch: {

    tipoSelecionado() {
      this.clearForm();
    }

  },

  components: {
    ModalUploadRash,
    BaseButton,
    VehicleListComponent,
    HorizontalSelect,
    BoletoListComponent,
    BoletoUpdatedModal,
    LoadingBoleto
  },

  methods: {

    async updatedBolet(dataUpdated){

      this.boletUpdateCurrent = dataUpdated.data.data.boleto;
      this.showModalLink = false;
      this.showModalUpdate = true;

    },

    async getPlate() {

      if (!validateDocument(this.tipoSelecionado, this.placa)) return;

      this.loadingButton = true;
      this.viewBoletos = false;
      this.boletosCurrent = [];
      this.veiculos = [];

      try {

        if (this.tipoSelecionado === "plate") {

          const responseBoleto = await getAllBoletOfPlate(this.placa);

          this.boletosCurrent = responseBoleto.data.data.filter(bol => bol.situacao_boleto === "ABERTO");

          if (this.boletosCurrent.length) this.viewBoletos = true;

        } else {
          const [responsePlate, responseBoleto] = await Promise.all([getAllVehicle(this.placa), getAllBoletOfPeople(this.placa)]);

          if (responsePlate.status === 200) {
            this.veiculos = responsePlate.data.data;

            if (responseBoleto.status === 200) {

              this.boletos = responseBoleto.data.data;

            }

          }
        }

      } catch (error) {

        alert("Erro");

      } finally {
        this.loadingButton = false;
      }
    },

    async actionSelectedPlate(vehicle) {
      this.boletosCurrent = this.boletos.filter(bol => Number(bol.veiculo[0].codigo_veiculo) === Number(vehicle.codigo_veiculo) && bol.situacao_boleto === "ABERTO");
      if (this.boletosCurrent) {
        this.viewBoletos = true;
      }
    },

    clearForm() {
      this.placa = "";
      this.veiculos = [];
      this.boletos = [];
      this.boletosCurrent = [];
      this.loadingButton = false;
      this.viewBoletos = false;
    },

    async updateBolet(bolet) {

      this.loadingUpdateBolet = true;
      try {

        const responseUpdateBolet = await updateBoleto(Number(bolet.nosso_numero));

        if (responseUpdateBolet.status === 200) {

          this.boletUpdateCurrent = responseUpdateBolet.data.data;
          this.showModalUpdate = true;

        }

        console.log("Response update boleto: ", responseUpdateBolet);

      } catch (error) {
        console.log(error);
      } finally {
        this.loadingUpdateBolet = false;
      }

    },

    openModalLink(bolet) {

      this.plateHashCurrent =
        bolet?.veiculos?.[0]?.placa ||
        bolet?.veiculo?.[0]?.placa ||
        null;
      this.nossoNumeroCurrent = Number(bolet.nosso_numero);
        
      this.showModalLink = true;

    }

  }
}
</script>