import axios from "axios";
import router from "../router/index"

const api = axios.create()

api.interceptors.request.use(config => {
  if(localStorage.access_token) {
    config.headers.authorization = `Bearer ${localStorage.access_token}`
  }
  config.baseURL = 'http://localhost/api/auth/'

  return config
},error => {
  //Этот блок кода срабатывает только тогда, когда ошибка отправки запроса с фронта
  console.log(error)
})

api.interceptors.response.use({}, error => {
  //Этот блок кода срабатывает когда прилетает ошибка с бэка
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

export default api
