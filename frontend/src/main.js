import { createApp } from 'vue'
import App from './App.vue'
import './registerServiceWorker'
import router from './router'
import store from './store'
import Element from 'element-plus'
import 'element-plus/dist/index.css'
import './styles.scss'

createApp(App)
  .use(store)
  .use(router)
  .use(Element)
  .mount('#portal')
