import { createRouter, createWebHistory } from 'vue-router'
import Home from '../views/Home.vue'
import Finances from '../views/Finances.vue'
import Tasks from '../views/Tasks.vue'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {path: '/home', component: Home, alias: '/'},
    {path: '/finances', component: Finances, alias: '/finances'},
    {path: '/tasks', component: Tasks, alias: '/tasks'}
  ],
  linkActiveClass: 'active',
  linkExactActiveClass: 'active'
})
