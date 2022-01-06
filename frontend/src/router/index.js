import { createRouter, createWebHistory } from 'vue-router'
import Home from '../views/Home.vue'
import Finances from '../views/Finances.vue'
import Tasks from '../views/Tasks.vue'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {path: '/home', component: Home, title: 'Главная', alias: '/'},
    {path: '/finances', component: Finances, title: 'Финансы', alias: '/finances'},
    {path: '/tasks', component: Tasks, title: 'Задачи', alias: '/tasks'}
  ],
  linkActiveClass: 'is-active',
  linkExactActiveClass: 'is-active'
})

export default router
