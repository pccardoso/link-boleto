<template>

    <div class="p-6">

      <h1 class="text-2xl font-bold mb-4 text-neutral-600">Links</h1>

      <LoadingComponent v-if="loading" />

      <HashPlateComponent v-else :data="hashPlates" @view-item="handleViewItem" />

    </div>

    <!-- MODAL DE PREVIEW -->
    <PreviewFileComponent v-if="modal" :anexos="anexosPreview" @close-modal="modal = false" />

</template>

<script>

import axios from 'axios';
import HashPlateComponent from '@/components/HashPlateComponent.vue';
import PreviewFileComponent from '@/components/PreviewFIleComponent.vue';
import LoadingComponent from '@/components/LoadingComponent.vue';

export default {

  name: "LinkView",
  components: {
    HashPlateComponent,
    PreviewFileComponent,
    LoadingComponent
  },
  data(){
    return {
      hashPlates: [],
      modal: false,
      selectedItem: null,
      anexosPreview: [],
      loading: true
    }       
  },
  methods: {

    async handleViewItem(item){
      this.selectedItem = item;
      const response = await axios.get(`/links/get-upload/${item.id}`);
      this.anexosPreview = response.data.data;
      this.modal = true; 
    },
  },
  async mounted() {
    const response = await axios.get('/links/all');
    this.hashPlates = response.data.data;
    this.loading = false;

  },

}

</script>