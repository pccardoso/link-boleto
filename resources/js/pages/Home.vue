<template>

  <div class="min-h-screen flex flex-col bg-textura">

    <!-- HERO / CONTEÚDO PRINCIPAL -->
    <section class="flex flex-1 items-center justify-center px-8 py-16">

      <div class="max-w-7xl w-full grid md:grid-cols-2 gap-10 items-center">

        <!-- TEXTO ESQUERDA -->
        <div>

          <!-- IMAGENS -->
          <div class="flex flex-col items-center gap-3 mb-6 sm:flex-row sm:justify-start sm:gap-4">

            <img src="../../img/EVOGARD-WHITE.png" class="h-14 sm:h-20 object-contain">

            <img src="../../img/COBERTURA-WHITE.png" class="h-12 sm:h-16 object-contain">

          </div>

          <!-- TÍTULO -->
          <h1 class="text-5xl font-bold text-center sm:text-left text-white leading-tight">
            MANTENHA SEU VEÍCULO <br />
            100% PROTEGIDO.
          </h1>

          <!-- SUBTEXTO -->
          <p class="text-orange-500 text-center sm:text-left text-[25px] font-semibold mt-6">
            Emita agora a segunda via do seu boleto de forma rápida e segura.
          </p>

        </div>

        <!-- BOX DIREITA (SEU TEMPLATE ATUAL) -->
        <div class="rounded-3xl">

          <div class="bg-white w-full max-w-lg rounded-2xl shadow-xl p-8 my-4 mx-auto">

            <!-- Título -->
            <div class="flex flex-col items-center justify-center gap-3 mb-8">

              <img src="../../img/LOGO-EVOGARD.png" alt="Boletos" class="h-12">

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

            <div class="flex gap-3">

              <BaseButton :loading="loadingButton" :disabled="validateInput" @click="getPlate">
                Consultar
              </BaseButton>

              <button @click="clearForm"
                class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-3 rounded-xl transition duration-200">
                Limpar
              </button>

            </div>

          </div>

        </div>

      </div>

    </section>

    <!-- CARDS -->
    <section class="py-16 bg-gray-100">
      <div class="max-w-6xl mx-auto grid md:grid-cols-4 gap-8 px-6">

        <!-- CARD -->
        <div
          class="bg-gradient-to-br from-orange-400 to-orange-500 text-white rounded-xl p-6 shadow-lg flex flex-col items-center text-center gap-4 transform transition duration-300 hover:scale-105 hover:shadow-2xl">
          <div class="bg-white/20 p-4 rounded-full flex items-center justify-center">
            <img src="../../img/COBERTURA_COMPLETA.png" class="h-16 w-16 object-contain">
          </div>
          <h3 class="font-bold text-lg">Cobertura completa</h3>
          <p class="text-sm text-white/90">Proteção total para o seu veículo em qualquer situação.</p>
        </div>

        <!-- CARD -->
        <div
          class="bg-gradient-to-br from-orange-400 to-orange-500 text-white rounded-xl p-6 shadow-lg flex flex-col items-center text-center gap-4 transform transition duration-300 hover:scale-105 hover:shadow-2xl">
          <div class="bg-white/20 p-4 rounded-full flex items-center justify-center">
            <img src="../../img/ASSISTENCIA.png" class="h-16 w-16 object-contain">
          </div>
          <h3 class="font-bold text-lg">Assistência 24h</h3>
          <p class="text-sm text-white/90">Atendimento rápido e confiável a qualquer hora do dia.</p>
        </div>

        <!-- CARD -->
        <div
          class="bg-gradient-to-br from-orange-400 to-orange-500 text-white rounded-xl p-6 shadow-lg flex flex-col items-center text-center gap-4 transform transition duration-300 hover:scale-105 hover:shadow-2xl">
          <div class="bg-white/20 p-4 rounded-full flex items-center justify-center">
            <img src="../../img/RASTREADOR.png" class="h-16 w-16 object-contain">
          </div>
          <h3 class="font-bold text-lg">Rastreador</h3>
          <p class="text-sm text-white/90">Localize seu veículo em tempo real, de forma segura.</p>
        </div>

        <!-- CARD -->
        <div
          class="bg-gradient-to-br from-orange-400 to-orange-500 text-white rounded-xl p-6 shadow-lg flex flex-col items-center text-center gap-4 transform transition duration-300 hover:scale-105 hover:shadow-2xl">
          <div class="bg-white/20 p-4 rounded-full flex items-center justify-center">
            <img src="../../img/PROTECAO.png" class="h-16 w-16 object-contain">
          </div>
          <h3 class="font-bold text-lg">Proteção para diversas situações</h3>
          <p class="text-sm text-white/90">Segurança completa para você e sua família.</p>
        </div>

      </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-(--evogard-blue) text-white pt-10 pb-6 bg-textura">

      <div class="max-w-7xl mx-auto px-6">

        <div class="grid md:grid-cols-2 gap-10 items-center text-center md:text-left">

          <!-- LOGO -->
          <div class="flex flex-col items-center md:items-start gap-3">
            <img src="../../img/EVOGARD-WHITE.png" class="h-10">
          </div>



          <!-- CONTATO -->
          <div class="flex flex-col items-center md:items-end gap-3">

            <span class="font-semibold tracking-wider text-sm text-gray-200">
              DÚVIDAS? FALE CONOSCO
            </span>

            <div class="flex items-center gap-2 text-lg font-semibold">
              <i class="fas fa-phone"></i>
              0800 000 1992
            </div>

          </div>

        </div>

      </div>

    </footer>

  </div>

  <BoletoUpdatedModal v-if="showModalUpdate" :boleto="boletUpdateCurrent" @close="showModalUpdate = false" />

  <LoadingBoleto v-model="loadingUpdateBolet" />

  <ModalUploadRash v-if="showModalLink" :plate="plateHashCurrent" :nosso_numero="nossoNumeroCurrent"
    @close="showModalLink = false" @updatedBill="updatedBolet" />

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
import Swal from 'sweetalert2';

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

    async updatedBolet(dataUpdated) {

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

          if (!this.boletosCurrent.length) {
            Swal.fire({
              icon: 'info',
              title: 'Nenhum boleto encontrado',
              text: "Não foram encontrados boletos.",
            });
          }

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

        if (error.status === 404) {
          Swal.fire({
            icon: 'error',
            title: 'Ops...',
            text: "Nenhum resultado encontrado para a consulta.",
          });
        }

        console.log(error);

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