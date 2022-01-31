import { createRouter, createWebHistory } from 'vue-router'
import Home from '../views/Home.vue'
import Finances from '../views/Finances.vue'
import Tasks from '../views/Tasks.vue'
import Profile from '../views/Profile.vue'
import Login from '../views/auth/Login.vue'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: '/profile',
      component: Profile,
      title: 'Профиль',
      name: 'profile',
      alias: '/profile',
      menu: 0
    },
    {
      path: '/login',
      component: Login,
      title: 'Авторизация',
      name: 'login',
      alias: '/login',
      menu: 0
    },
    {
      path: '/home',
      component: Home,
      title: 'Главная',
      name: 'home',
      alias: '/',
      menu: 1
    },
    {
      path: '/finances',
      component: Finances,
      title: 'Финансы',
      name: 'finances',
      alias: '/finances',
      menu: 1
    },
    {
      path: '/tasks',
      component: Tasks,
      title: 'Задачи',
      name: 'tasks',
      alias: '/tasks',
      menu: 1
    }
  ],
  linkActiveClass: 'is-active',
  linkExactActiveClass: 'is-active'
})

router.beforeEach((to, from, next ) => {
  const accessToken = localStorage.access_token

  if(to.name !== 'login') {
    if(!accessToken) {
      return next({
        name: 'login'
      })
    }
  }
  if(to.name === 'login' && accessToken) {
    return next({
      name: 'home'
    })
  }

  next()
})

export default router
