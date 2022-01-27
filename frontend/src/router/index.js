import { createRouter, createWebHistory } from 'vue-router'
import Home from '../views/Home.vue'
import Finances from '../views/Finances.vue'
import Tasks from '../views/Tasks.vue'
import Profile from '../views/Profile.vue'
import Login from '../views/auth/Login.vue'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {path: '/profile', component: Profile, title: 'Профиль', alias: '/profile', menu: 0},
    {path: '/login', component: Login, title: 'Авторизация', alias: '/login', menu: 0},
    {path: '/home', component: Home, title: 'Главная', alias: '/', menu: 1},
    {path: '/finances', component: Finances, title: 'Финансы', alias: '/finances', menu: 1},
    {path: '/tasks', component: Tasks, title: 'Задачи', alias: '/tasks', menu: 1}
  ],
  linkActiveClass: 'is-active',
  linkExactActiveClass: 'is-active'
})

export default router
