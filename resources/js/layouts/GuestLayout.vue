<template>

  <div class="min-h-screen flex items-center justify-center bg-textura px-6">

    <div class="w-full max-w-md">

      <!-- LOGO -->
      <div class="flex justify-center mb-8">
        <img src="../../img/EVOGARD-WHITE.png" class="h-14" />
      </div>

      <!-- CARD -->
      <div class="bg-white rounded-2xl shadow-xl p-8">

        <slot />

      </div>

      <!-- FOOTER -->
      <div class="text-center text-gray-400 text-sm mt-6">
        © {{ year }} Evogard
      </div>

    </div>

  </div>

</template>

<script>

export default {

  name: "GuestLayout",

  computed: {

    year() {
      return new Date().getFullYear()
    }

  }

}

</script>