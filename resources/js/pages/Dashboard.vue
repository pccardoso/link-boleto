<template>
  <div class="p-6">

    <h1 class="text-2xl font-bold mb-6 text-neutral-600">
      Dashboard
    </h1>

    <div class="grid grid-cols-1 lg:grid-cols-1 gap-6">

      <!-- GRÁFICO BOLETOS -->
      <div class="bg-white p-4 rounded-lg shadow">

        <h2 class="text-sm font-semibold text-gray-500 mb-3">
          Boletos gerados por mês (ano atual)
        </h2>

        <Bar v-if="chartDataBills" :data="chartDataBills" :options="chartOptionsBar" />

      </div>

      <!-- GRÁFICO LINHA -->
      <div class="bg-white p-4 rounded-lg shadow">

        <h2 class="text-sm font-semibold text-gray-500 mb-3">
          Códigos gerados por mês (ano atual)
        </h2>

        <Line v-if="chartDataLine" :data="chartDataLine" :options="chartOptionsLine" />

      </div>


      <!-- GRÁFICO BARRAS -->
      <div class="bg-white p-4 rounded-lg shadow">

        <h2 class="text-sm font-semibold text-gray-500 mb-3">
          Códigos vs Uploads por mês (ano atual)
        </h2>

        <Bar v-if="chartDataBar" :data="chartDataBar" :options="chartOptionsBar" />

      </div>


      

    </div>

  </div>
</template>

<script>
import axios from "axios"

import { Line, Bar } from "vue-chartjs"
import ChartDataLabels from "chartjs-plugin-datalabels"

import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  LineElement,
  BarElement,
  CategoryScale,
  LinearScale,
  PointElement
} from "chart.js"

ChartJS.register(
  Title,
  Tooltip,
  Legend,
  LineElement,
  BarElement,
  CategoryScale,
  LinearScale,
  PointElement,
  ChartDataLabels
)

export default {

  name: "Dashboard",

  components: {
    Line,
    Bar
  },

  data() {
    return {

      chartDataLine: null,
      chartDataBar: null,
      chartDataBills: null,

      chartOptionsLine: {
        responsive: true,
        maintainAspectRatio: false,

        plugins: {
          datalabels: {
            color: "#374151",
            anchor: "end",
            align: "top",
            font: {
              weight: "bold"
            },
            formatter: function (value) {
              return value
            }
          },

          legend: {
            display: false
          }
        }
      },

      chartOptionsBar: {
        responsive: true,
        maintainAspectRatio: false,

        plugins: {
          legend: {
            position: "top"
          },

          datalabels: {
            color: "#111",
            anchor: "end",
            align: "top",
            font: {
              weight: "bold"
            }
          }
        }
      }

    }
  },

  async mounted() {

    await Promise.all([
      this.loadHashesMes(),
      this.loadHashesUploadMes(),
      this.loadBillsMes()
    ])

  },

  methods: {

    async loadHashesMes() {

      const response = await axios.get("/dashboard/hashes-mes")

      this.chartDataLine = {
        labels: response.data.labels,

        datasets: [
          {
            label: "Códigos geradas",
            data: response.data.values,
            borderColor: "#3b82f6",
            backgroundColor: "rgba(59,130,246,0.2)",
            tension: 0.4
          }
        ]
      }

    },


    async loadHashesUploadMes() {

      const response = await axios.get("/dashboard/hashes-mes-upload")

      this.chartDataBar = {
        labels: response.data.labels,

        datasets: [
          {
            label: response.data.series[0].name,
            data: response.data.series[0].data,
            backgroundColor: "#ED6B1E"
          },
          {
            label: response.data.series[1].name,
            data: response.data.series[1].data,
            backgroundColor: "#F4945C"
          }
        ]
      }

    },


    async loadBillsMes() {

      const response = await axios.get("/dashboard/bills-mes")

      const labels = Object.keys(response.data)
      const values = Object.values(response.data)

      this.chartDataBills = {
        labels: labels,

        datasets: [
          {
            label: "Boletos gerados",
            data: values,
            backgroundColor: "#ED6B1E"
          }
        ]
      }

    }

  }

}
</script>

<style>
canvas {
  height: 300px !important;
}
</style>