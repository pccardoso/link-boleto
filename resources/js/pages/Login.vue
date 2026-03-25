<template>

  <div>

    <div class="text-center mb-8">
      <h1 class="text-2xl font-bold text-(--evogard-blue)">
        Acessar sistema (Teste)
      </h1>

      <p class="text-gray-500 text-sm mt-2">
        Informe seu e-mail e senha para entrar
      </p>
    </div>

    <!-- EMAIL -->
    <div class="mb-5">
      <label class="block text-sm font-medium text-gray-700 mb-2">
        E-mail
      </label>

      <input
        v-model="email"
        type="email"
        placeholder="seu@email.com"
        class="w-full px-4 py-3 border border-gray-300 rounded-xl
               focus:outline-none focus:ring-2 focus:ring-blue-500
               transition text-gray-600"
      >
    </div>

    <!-- SENHA -->
    <div class="mb-6">
      <label class="block text-sm font-medium text-gray-700 mb-2">
        Senha
      </label>

      <input
        v-model="password"
        type="password"
        placeholder="Digite sua senha"
        class="w-full px-4 py-3 border border-gray-300 rounded-xl
               focus:outline-none focus:ring-2 focus:ring-blue-500
               transition text-gray-600"
      >
    </div>

    <!-- BOTÃO -->
    <button
      @click="login"
      :disabled="loading"
      class="w-full bg-(--evogard-blue) hover:opacity-90
             text-white font-semibold py-3 rounded-xl
             transition flex items-center justify-center gap-2"
    >
      <span v-if="!loading">Entrar</span>
      <span v-else>Entrando...</span>
    </button>

  </div>

</template>

<script>

import { router } from '@inertiajs/vue3'

export default {

  name: "Login",

  data() {
    return {
      email: "",
      password: "",
      loading: false
    }
  },

  methods: {

    login() {

      this.loading = true

      router.post('/login', {
        email: this.email,
        password: this.password
      }, {
        onFinish: () => this.loading = false
      })

    }

  }

}

</script>