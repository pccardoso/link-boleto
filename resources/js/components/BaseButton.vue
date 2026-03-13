<template>
  <button
    :type="type"
    :disabled="isDisabled"
    @click="$emit('click')"
    :class="[
      'flex-1 font-semibold py-3 rounded-xl transition duration-200 shadow-md flex items-center justify-center gap-2 text-white',
      isDisabled
        ? 'bg-gray-300 cursor-not-allowed'
        : 'bg-(--evogard-blue) hover:bg-(--evogard-orange)'
    ]"
  >
    <!-- Spinner -->
    <svg
      v-if="loading"
      class="animate-spin h-5 w-5 text-white"
      xmlns="http://www.w3.org/2000/svg"
      fill="none"
      viewBox="0 0 24 24"
    >
      <circle
        class="opacity-25"
        cx="12"
        cy="12"
        r="10"
        stroke="currentColor"
        stroke-width="4"
      />

      <path
        class="opacity-75"
        fill="currentColor"
        d="M4 12a8 8 0 018-8v8H4z"
      />
    </svg>

    <!-- Texto -->
    <span>
      <slot />
    </span>
  </button>
</template>

<script>
export default {
  name: "BaseButton",

  emits: ["click"],

  props: {
    loading: {
      type: Boolean,
      default: false,
    },

    disabled: {
      type: Boolean,
      default: false,
    },

    type: {
      type: String,
      default: "button",
    },
  },

  computed: {
    isDisabled() {
      return this.loading || this.disabled
    },
  },
}
</script>