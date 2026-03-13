<template>
  <div class="flex gap-3">

    <div v-for="(option, index) in options" :key="index" @click="select(option.value)"
      class="flex-1 flex items-center justify-center gap-2 px-4 py-2 rounded-lg border cursor-pointer transition duration-200 select-none h-[50px]"
      :class="modelValue === option.value
        ? 'bg-(--evogard-orange) text-white border-(--evogard-orange)'
        : 'bg-white text-gray-700 border-gray-300 hover:bg-blue-50'">
      <!-- Ícone -->
      <i :class="`${option.icon} text-[26px]`" ></i>

      <!-- Label -->
      <span class="text-sm font-medium">
        {{ option.label }}
      </span>

    </div>

  </div>
</template>

<script>
export default {
  name: "HorizontalSelect",

  props: {
    modelValue: {
      required: true
    },
    options: {
      type: Array,
      required: true
    }
  },

  emits: ["update:modelValue"],

  methods: {
    select(value) {
      this.$emit("update:modelValue", value)
    }
  }
}
</script>