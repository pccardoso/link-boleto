<template>
  <div class="w-full flex items-center justify-center py-10">

    <div class="flex flex-col items-center gap-3">

      <!-- Spinner -->
      <div class="w-10 h-10 border-4 border-gray-200 border-t-(--evogard-orange) rounded-full animate-spin"></div>

      <!-- Texto -->
      <span class="text-sm text-gray-500">
        {{ text }}
      </span>

    </div>

  </div>
</template>

<script>
export default {
  name: "LoadingComponent",

  props: {
    text: {
      type: String,
      default: "Carregando..."
    }
  }
}
</script>