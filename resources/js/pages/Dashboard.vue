<template>
  <div class="p-6">

    <h1 class="text-2xl font-bold mb-4 text-neutral-600">Dashboard</h1>

    <div class="bg-white p-4 rounded-lg">

      <Line v-if="chartData" :data="chartData" :options="chartOptions" />

    </div>

  </div>
</template>

<script>
import axios from 'axios'
import { Line } from 'vue-chartjs'

import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  LineElement,
  CategoryScale,
  LinearScale,
  PointElement
} from 'chart.js'

ChartJS.register(
  Title,
  Tooltip,
  Legend,
  LineElement,
  CategoryScale,
  LinearScale,
  PointElement
)

export default {

  name: "Dashboard",

  components: {
    Line
  },

  data() {
    return {
      chartData: null,

      chartOptions: {
        responsive: true,
        maintainAspectRatio: false
      }
    }
  },

  async mounted() {

    const response = await axios.get('/dashboard/hashes-mes')

    this.chartData = {
      labels: response.data.labels,
      datasets: [
        {
          label: 'Hashs geradas',
          data: response.data.values,
          borderColor: '#3b82f6',
          backgroundColor: 'rgba(59,130,246,0.2)',
          tension: 0.4
        }
      ]
    }

  }

}
</script>

<style>
canvas {
  height: 300px !important;
}
</style>