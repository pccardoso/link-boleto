<template>

  <div class="min-h-screen flex bg-gray-100">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-(--evogard-blue) text-white flex flex-col">

      <!-- LOGO -->
      <div class="h-20 flex items-center justify-center border-b border-white/10">
        <img src="../../img/EVOGARD-WHITE.png" class="h-10">
      </div>

      <!-- MENU -->
      <nav class="flex-1 p-4 space-y-2">

        <Link href="/dashboard" :class="navClass('/dashboard')">
        Dashboard
        </Link>

        <Link href="/tickets" :class="navClass('/tickets')">
        Tickets
        </Link>

        <Link href="/users" :class="navClass('/users')">
        Usuários
        </Link>

      </nav>

      <!-- FOOTER -->
      <div class="p-4 border-t border-white/10 text-sm text-white/60">
        {{ user.name }}
      </div>

    </aside>


    <!-- CONTEÚDO -->
    <div class="flex-1 flex flex-col">

      <!-- TOPO -->
      <header class="h-16 bg-white shadow flex items-center justify-end px-6">

        <button @click="logout" class="text-sm text-gray-600 hover:text-red-500 transition">
          Sair
        </button>

      </header>


      <!-- PÁGINA -->
      <main class="flex-1 p-8 text-neutral-800">

        <slot />

      </main>

    </div>

  </div>

</template>

<script>

import { Link, router } from '@inertiajs/vue3'
import axios from 'axios';

export default {

  name: "AuthenticatedLayout",

  components: {
    Link
  },

  computed: {
    user() {
      return this.$page.props.auth?.user ?? {}
    }
  },

  methods: {

    navClass(url) {
      return [
        "block px-4 py-2 rounded-lg transition",
        this.$page.url.startsWith(url)
          ? "bg-white/20"
          : "hover:bg-white/10"
      ]
    },

    logout() {

      try{

        const response = axios.post('/logout');

        
      }catch(e) {
        alert("Erro ao sair, tente novamente.")
        return
      }

      router.post('/logout')
    }

  }

}

</script>