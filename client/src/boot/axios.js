import { boot } from 'quasar/wrappers'
import axios from 'axios'
import { Notify } from 'quasar'

// Be careful when using SSR for cross-request state pollution
// due to creating a Singleton instance here;
// If any client changes this (global) instance, it might be a
// good idea to move this instance creation inside of the
// "export default () => {}" function below (which runs individually
// for each client)

const api = axios.create({ baseURL: `${process.env.host}/api/` })

export default boot(({ app, router }) => {
  api.interceptors.request.use(config => {
    config.headers.accept = 'application/json'
    if(localStorage.access_token) {
      config.headers.authorization = `Bearer ${localStorage.access_token}`
    }

    return config
  },error => {
    return Promise.reject(error);
  })

  api.interceptors.response.use(
    response => response,
    error => {
      if (error.response) {
        // Ошибки авторизации
        if (error.response.status === 401) {
          localStorage.removeItem('access_token')
          router.push('/login')
        }
      } else if (error.request) {
        // Ошибки, не получившие ответа от сервера (включая ошибки CORS)
        if (error.message.includes('Network Error')) {
          Notify.create({
            type: 'negative',
            message: 'Произошла ошибка сети. Пожалуйста, проверьте ваше соединение или настройки CORS.'
          });
        }
      } else {
        // Другие ошибки
        Notify.create({
          type: 'negative',
          message: `Произошла ошибка: ${error.message}`
        });
      }

      return Promise.reject(error);
    }
  );

  // for use inside Vue files (Options API) through this.$axios and this.$api
  app.config.globalProperties.$axios = axios
  // ^ ^ ^ this will allow you to use this.$axios (for Vue Options API form)
  //       so you won't necessarily have to import axios in each vue file

  app.config.globalProperties.$api = api
  // ^ ^ ^ this will allow you to use this.$api (for Vue Options API form)
  //       so you can easily perform requests against your app's API
})

export { api }
