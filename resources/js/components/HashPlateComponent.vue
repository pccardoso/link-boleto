<template>

<div class="bg-white rounded-xl shadow p-4">

  <!-- SEARCH + PAGE SIZE -->
  <div class="flex justify-between items-center mb-4">

    <input
      v-model="search"
      type="text"
      placeholder="Buscar..."
      class="border rounded px-3 py-2 w-64"
    >

    <select v-model="perPage" class="border rounded px-2 py-2">
      <option :value="10">10</option>
      <option :value="20">20</option>
      <option :value="30">30</option>
      <option :value="50">50</option>
    </select>

  </div>

  <!-- TABLE -->
  <div class="overflow-x-auto">

    <table class="min-w-full text-sm text-left">

      <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
        <tr>
          <th class="px-6 py-3">ID</th>
          <th class="px-6 py-3">Placa</th>
          <th class="px-6 py-3">Hash</th>
          <th class="px-6 py-3">Arquivo</th>
          <th class="px-6 py-3">Criado em</th>
          <th class="px-6 py-3">Ações</th>
        </tr>
      </thead>

      <tbody>

        <tr
          v-for="item in paginatedData"
          :key="item.id"
          class="border-b hover:bg-gray-50"
        >

          <td class="px-6 py-4">{{ item.id }}</td>

          <td class="px-6 py-4 font-semibold">
            {{ item.plate }}
          </td>

          <td class="px-6 py-4">
            {{ item.hash }}
          </td>

          <td class="px-6 py-4">

            <span v-if="item.upload && item.upload.length">
              {{ item.upload.length }} arquivo(s)
            </span>

            <span v-else class="text-red-500">
              Sem upload
            </span>

          </td>

          <td class="px-6 py-4">
            {{ formatDate(item.created_at) }}
          </td>

          <td class="px-6 py-4">

            <button
              @click="viewItem(item)"
              class="text-blue-500 hover:text-blue-700"
            >
              Anexos
            </button>

          </td>

        </tr>

      </tbody>

    </table>

  </div>

  <!-- PAGINATION -->
  <div class="flex justify-between items-center mt-4">

    <span class="text-sm text-gray-600">
      Mostrando {{ startItem }} - {{ endItem }} de {{ filteredData.length }}
    </span>

    <div class="flex items-center gap-1">

      <!-- anterior -->
      <button
        class="px-3 py-1 border rounded"
        :disabled="page === 1"
        @click="page--"
      >
        <
      </button>

      <!-- primeira -->
      <button
        v-if="visiblePages[0] > 1"
        @click="page = 1"
        class="px-3 py-1 border rounded"
      >
        1
      </button>

      <span v-if="visiblePages[0] > 2">...</span>

      <!-- paginas -->
      <button
        v-for="p in visiblePages"
        :key="p"
        @click="page = p"
        class="px-3 py-1 border rounded"
        :class="p === page ? 'bg-blue-500 text-white' : ''"
      >
        {{ p }}
      </button>

      <span v-if="visiblePages[visiblePages.length - 1] < totalPages - 1">...</span>

      <!-- ultima -->
      <button
        v-if="visiblePages[visiblePages.length - 1] < totalPages"
        @click="page = totalPages"
        class="px-3 py-1 border rounded"
      >
        {{ totalPages }}
      </button>

      <!-- proximo -->
      <button
        class="px-3 py-1 border rounded"
        :disabled="page === totalPages"
        @click="page++"
      >
        >
      </button>

    </div>

  </div>

</div>

</template>

<script>
export default {

  name: "HashPlateComponent",

  props: {
    data: {
      type: Array,
      required: true
    }
  },

  emits: ['view-item'],

  data(){
    return{
      search: '',
      page: 1,
      perPage: 10
    }
  },

  computed:{

    filteredData(){

      if(!this.search){
        return this.data
      }

      const term = this.search.toLowerCase()

      return this.data.filter(item => {

        const uploadPath = item.upload?.[0]?.path || ''

        return (
          String(item.id).includes(term) ||
          item.plate.toLowerCase().includes(term) ||
          item.hash.toLowerCase().includes(term) ||
          uploadPath.toLowerCase().includes(term)
        )

      })

    },

    totalPages(){
      return Math.ceil(this.filteredData.length / this.perPage)
    },

    paginatedData(){

      const start = (this.page - 1) * this.perPage
      const end = start + this.perPage

      return this.filteredData.slice(start, end)

    },

    visiblePages(){

      const pages = []
      const range = 2

      let start = this.page - range
      let end = this.page + range

      if(start < 1) start = 1
      if(end > this.totalPages) end = this.totalPages

      for(let i = start; i <= end; i++){
        pages.push(i)
      }

      return pages

    },

    startItem(){
      return (this.page - 1) * this.perPage + 1
    },

    endItem(){

      const end = this.page * this.perPage

      return end > this.filteredData.length
        ? this.filteredData.length
        : end

    }

  },

  watch:{
    search(){
      this.page = 1
    },
    perPage(){
      this.page = 1
    }
  },

  methods:{

    formatDate(date){
      if(!date) return "-"
      return new Date(date).toLocaleString('pt-BR')
    },

    viewItem(item){
      this.$emit('view-item', item)
    }

  }

}
</script>