<template>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

        <!-- HEADER -->
        <div class="flex items-center justify-between mb-6">

            <h2 class="text-lg font-semibold text-gray-700 flex items-center gap-2">
                <i class="fa-solid fa-file-invoice-dollar text-(--evogard-orange)"></i>
                Boletos
            </h2>

            <div class="flex items-center gap-3">

                <!-- SEARCH -->
                <div class="relative">

                    <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>

                    <input v-model="search" type="text" placeholder="Buscar boleto..."
                        class="border border-gray-200 rounded-lg pl-9 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">

                </div>

                <!-- PER PAGE -->
                <select v-model="perPage" class="border border-gray-200 rounded-lg px-2 py-2 text-sm">
                    <option :value="10">10</option>
                    <option :value="20">20</option>
                    <option :value="30">30</option>
                    <option :value="50">50</option>
                </select>

            </div>

        </div>

        <!-- TABLE -->
        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="text-gray-500 text-xs uppercase border-b border-b-neutral-400">
                    <tr>
                        <th class="text-left py-3">ID</th>
                        <th class="text-left py-3">Associado</th>
                        <th class="text-left py-3">CPF/CNPJ</th>
                        <th class="text-left py-3">Nosso Número</th>
                        <th class="text-left py-3">Linha Digitável</th>
                        <th class="text-left py-3">Vencimento</th>
                        <th class="text-left py-3">Criado</th>
                        <th class="text-right py-3">Ação</th>
                    </tr>
                </thead>

                <tbody>

                    <tr v-for="item in paginatedData" :key="item.id"
                        class="border-b border-b-neutral-200 last:border-none hover:bg-gray-50 transition">

                        <td class="py-4 text-gray-500">
                            #{{ item.id }}
                        </td>

                        <td class="py-4 font-semibold text-gray-700">
                            {{ item.associado }}
                        </td>

                        <td class="py-4 text-gray-500">
                            {{ item.cpf_cnpj }}
                        </td>

                        <td class="py-4 text-gray-500">
                            {{ item.nosso_numero }}
                        </td>

                        <td class="py-4 text-xs text-gray-500" :title="item.linha_digitavel">
                            {{ item.linha_digitavel.substring(0, 20) }}...
                        </td>

                        <td class="py-4">

                            <span v-if="item.nova_data_vencimento"
                                class="px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-700">
                                {{ formatDate(item.nova_data_vencimento) }}
                            </span>

                            <span v-else class="px-2 py-1 text-xs rounded bg-gray-100 text-gray-600">
                                -
                            </span>

                        </td>

                        <td class="py-4 text-gray-500">
                            {{ formatDate(item.created_at) }}
                        </td>

                        <td class="py-4 text-right flex justify-end gap-3">

                            <!-- VER BOLETO -->
                            <a :href="item.link_boleto" target="_blank"
                                class="text-gray-400 hover:text-green-600 transition">
                                <i class="fa-solid fa-file-pdf"></i>
                            </a>

                            <!-- EMIT -->
                            <button @click="$emit('view-boleto', item)"
                                class="text-gray-400 hover:text-blue-600 transition">
                                <i class="fa-solid fa-eye"></i>
                            </button>

                        </td>

                    </tr>

                    <tr v-if="paginatedData.length === 0">
                        <td colspan="8" class="text-center py-8 text-gray-400">
                            Nenhum boleto encontrado
                        </td>
                    </tr>

                </tbody>

            </table>

        </div>

        <!-- PAGINATION -->
        <div class="flex items-center justify-between mt-6 text-sm text-gray-500">

            <span>
                {{ startItem }} - {{ endItem }} de {{ filteredData.length }}
            </span>

            <div class="flex items-center gap-1">

                <button class="px-3 py-1 rounded hover:bg-gray-100" :disabled="page === 1" @click="page--">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>

                <button v-for="p in visiblePages" :key="p" @click="p !== '...' && (page = p)" class="px-3 py-1 rounded"
                    :class="[
                        p === page ? 'bg-(--evogard-orange) text-white' : 'hover:bg-gray-100',
                        p === '...' ? 'cursor-default text-gray-400' : ''
                    ]">
                    {{ p }}
                </button>

                <button class="px-3 py-1 rounded hover:bg-gray-100" :disabled="page === totalPages" @click="page++">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>

            </div>

        </div>

    </div>

</template>

<script>
export default {

    name: "BoletosTableComponent",

    props: {
        data: {
            type: Array,
            required: true
        }
    },

    emits: ['view-boleto'],

    data() {
        return {
            search: '',
            page: 1,
            perPage: 10
        }
    },

    computed: {

        filteredData() {

            if (!this.search) return this.data

            const term = this.search.toLowerCase()

            return this.data.filter(item => {

                return (
                    String(item.id ?? '').includes(term) ||
                    String(item.associado ?? '').toLowerCase().includes(term) ||
                    String(item.cpf_cnpj ?? '').includes(term) ||
                    String(item.nosso_numero ?? '').includes(term) ||
                    String(item.linha_digitavel ?? '').includes(term)
                )

            })

        },

        totalPages() {
            return Math.ceil(this.filteredData.length / this.perPage)
        },

        paginatedData() {

            const start = (this.page - 1) * this.perPage
            const end = start + this.perPage

            if (start >= this.filteredData.length) {
                return []
            }

            return this.filteredData.slice(start, end)

        },

        startItem() {
            return (this.page - 1) * this.perPage + 1
        },

        endItem() {

            const end = this.page * this.perPage

            return end > this.filteredData.length
                ? this.filteredData.length
                : end

        },

        visiblePages() {

            const pages = []
            const total = this.totalPages
            const current = this.page

            if (total <= 7) {
                for (let i = 1; i <= total; i++) pages.push(i)
                return pages
            }

            pages.push(1)

            if (current > 4) pages.push("...")

            const start = Math.max(2, current - 1)
            const end = Math.min(total - 1, current + 1)

            for (let i = start; i <= end; i++) {
                pages.push(i)
            }

            if (current < total - 3) pages.push("...")

            pages.push(total)

            return pages
        }

    },

    watch: {
        search() {
            this.page = 1
        },
        perPage() {
            this.page = 1
        }
    },

    methods: {

        formatDate(date) {

            if (!date) return "-"

            return new Date(date).toLocaleString('pt-BR')

        },

    }

}
</script>