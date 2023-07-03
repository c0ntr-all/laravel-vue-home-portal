<template>
  <q-skeleton :type="'text'" style="width: 250px" />
</template>

<script>
import { api } from "src/boot/axios"

export default {
  data() {
    return {
      email: '',
      loading: false
    }
  },
  methods: {
    async getProfile() {
      this.loading = true

      const {data} = await api.get('user')
      if(!data) {
        throw new Error('Нет данных!')
      }
      this.email = data.email
      this.loading = false
    }
  },
  mounted() {
    this.getProfile()
  }
}
</script>
