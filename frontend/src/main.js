import { createApp } from 'vue'
import App from './App.vue'
import './registerServiceWorker'
import router from './router'
import store from './store/index'
import Element from 'element-plus'
import 'element-plus/dist/index.css'
import './styles.scss'

createApp(App)
  .directive("click-outside", {
    beforeMount(el, binding, vnode) {
      el.clickOutsideEvent = function(event) {
        if (!(el === event.target || el.contains(event.target))) {
          binding.value(event, el);
        }
      };
      document.body.addEventListener('click', el.clickOutsideEvent);
    },
    unmounted(el) {
      document.body.removeEventListener('click', el.clickOutsideEvent);
    }
  })
  .use(store)
  .use(router)
  .use(Element)
  .mount('#portal')
