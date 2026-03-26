<template>

  <div class="min-h-screen flex bg-gray-100">

    <!-- OVERLAY MOBILE -->
    <div v-if="menuOpen" @click="menuOpen = false" class="fixed inset-0 bg-black/40 z-30 md:hidden"></div>

    <!-- SIDEBAR -->
    <aside :class="[
      'fixed md:relative z-40 h-screen w-[85%] max-w-xs md:w-64 bg-(--evogard-blue) text-white flex flex-col transition-transform duration-300',
      menuOpen ? 'translate-x-0' : '-translate-x-full',
      'md:translate-x-0'
    ]">

      <!-- LOGO -->
      <div class="h-20 flex items-center justify-center border-b border-white/10">
        <img src="../../img/EVOGARD-WHITE.png" class="h-10">
      </div>

      <!-- MENU -->
      <nav class="flex-1 p-4 space-y-1">

        <Link href="/dashboard" :class="navClass('/dashboard')" @click="menuOpen = false">
        <i class="fa-solid fa-chart-line w-5"></i>
        <span>Dashboard</span>
        </Link>

        <Link href="/links" :class="navClass('/links')" @click="menuOpen = false">
        <i class="fa-solid fa-link w-5"></i>
        <span>Links</span>
        </Link>

        <Link href="/users" :class="navClass('/users')" @click="menuOpen = false">
        <i class="fa-solid fa-users w-5"></i>
        <span>Usuários</span>
        </Link>

      </nav>

      <!-- FOOTER -->
      <div class="p-4 border-t border-white/10 text-sm text-white/70 flex items-center gap-2">

        <i class="fa-solid fa-user"></i>
        {{ user.name }}

      </div>

    </aside>


    <!-- CONTEÚDO -->
    <div class="flex-1 flex flex-col">

      <!-- HEADER -->
      <header class="h-16 bg-white shadow flex items-center justify-between px-6">

        <!-- BOTÃO MOBILE -->
        <button @click="menuOpen = !menuOpen" class="md:hidden text-gray-600 text-xl">
          <i class="fa-solid fa-bars"></i>
        </button>

        <!-- LOGO MOBILE -->
        <div class="md:hidden font-semibold text-gray-700 flex items-center gap-2">

          <i class="fa-solid fa-shield-halved text-(--evogard-blue)"></i>
          Evogard

        </div>

        <!-- LOGOUT -->
        <button @click="logout" class="text-sm text-gray-600 hover:text-red-500 transition flex items-center gap-2">

          <i class="fa-solid fa-right-from-bracket"></i>
          Sair

        </button>

      </header>


      <!-- PAGE -->
      <main class="flex-1 p-2 text-neutral-800">

        <slot />

      </main>

    </div>

  </div>

</template>

<script>

import { Link, router } from '@inertiajs/vue3'
import axios from 'axios'

export default {

  name: "AuthenticatedLayout",

  components: {
    Link
  },

  data() {
    return {
      menuOpen: false
    }
  },

  computed: {

    user() {
      return this.$page.props.auth?.user ?? {}
    }

  },

  methods: {

    navClass(url) {

      return [
        "flex items-center gap-3 px-4 py-2 rounded-lg transition font-medium",
        this.$page.url.startsWith(url)
          ? "bg-white/20"
          : "hover:bg-white/10"
      ]

    },

    async logout() {

      try {

        await axios.post('/logout')

      } catch (e) {

        alert("Erro ao sair, tente novamente.")
        return

      }

      router.visit('/')

    }

  }

}

</script>