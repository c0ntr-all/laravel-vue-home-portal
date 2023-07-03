import { boot } from 'quasar/wrappers'
import axios from 'axios'

// Be careful when using SSR for cross-request state pollution
// due to creating a Singleton instance here;
// If any client changes this (global) instance, it might be a
// good idea to move this instance creation inside of the
// "export default () => {}" function below (which runs individually
// for each client)
const api = axios.create({ baseURL: 'http://localhost/api/auth/' })

export default boot(({ app, router }) => {
  api.interceptors.request.use(config => {
    if(localStorage.access_token) {
      config.headers.authorization = `Bearer ${localStorage.access_token}`
    }

    return config
  },error => {
    return Promise.reject(error);
  })

  api.interceptors.response.use(response => {
    return response
  }, error => {
    if(error.response.data.message === 'Token has expired') {
      axios.post('http://localhost/api/auth/refresh', {}, {
        headers: {
          'authorization': `Bearer ${localStorage.access_token}`
        }
      }).then(response => {
        localStorage.access_token = response.data.access_token

        error.config.headers.authorization = `Bearer ${response.data.access_token}`

        return api.request(error.config)
      })
    }

    if(error.response.status === 401) {
      router.push('/login')
    }

    return Promise.reject(error);
  })

  // for use inside Vue files (Options API) through this.$axios and this.$api
  app.config.globalProperties.$axios = axios
  // ^ ^ ^ this will allow you to use this.$axios (for Vue Options API form)
  //       so you won't necessarily have to import axios in each vue file

  app.config.globalProperties.$api = api
  // ^ ^ ^ this will allow you to use this.$api (for Vue Options API form)
  //       so you can easily perform requests against your app's API
})

export { api }
