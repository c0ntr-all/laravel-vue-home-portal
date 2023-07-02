import axios from "axios"
import route from "../router/index"

const api = axios.create()

api.interceptors.request.use(config => {
  if(localStorage.access_token) {
    config.headers.authorization = `Bearer ${localStorage.access_token}`
  }
  config.baseURL = 'http://localhost/api/auth/'

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
    //todo: redirect to login page if 401 code
  }

  return Promise.reject(error);
})

export default api
