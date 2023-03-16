<template>
  <q-skeleton :type="'text'" style="width: 250px" />
</template>

<script>
import API from '../utils/api'

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

      const {data} = await API.post('me')
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
