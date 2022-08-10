import { createRouter, createWebHistory } from 'vue-router'
import store from '../store/index'


const router = createRouter({
  history: createWebHistory(),
  routes: [{
    path: '/profile',
    component: () => import('../views/Profile'),
    meta: {
      title: 'Профиль'
    },
    name: 'profile',
    alias: '/profile',
    menu: 0
  },{
    path: '/login',
    component: () => import('../views/auth/Login'),
    meta: {
      title: 'Авторизация'
    },
    name: 'login',
    alias: '/login',
    menu: 0
  },{
    path: '/home',
    component: () => import('../views/Home'),
    meta: {
      title: 'Главная'
    },
    name: 'home',
    alias: '/',
    menu: 1
  },{
    path: '/finances',
    component: () => import('../views/Finances'),
    meta: {
      title: 'Финансы'
    },
    name: 'finances',
    alias: '/finances',
    menu: 1
  },{
    path: '/tasks',
    component: () => import('../views/Tasks'),
    meta: {
      title: 'Задачи'
    },
    name: 'tasks',
    alias: '/tasks',
    menu: 1
  },{
    path: '/reminds',
    component: () => import('../views/Reminds'),
    meta: {
      title: 'Напоминания'
    },
    name: 'reminds',
    alias: '/reminder',
    menu: 1
  },{
    path: '/restaurants',
    component: () => import('../views/Restaurants'),
    meta: {
      title: 'Рестораны'
    },
    name: 'restaurants',
    alias: '/restaurants',
    menu: 1
  },{
    path: '/music',
    component: () => import('../views/music/Music'),
    meta: {
      title: 'Музыка'
    },
    name: 'music',
    alias: '/music',
    menu: 1,
    children: [{
        path: '/music',
        component: () => import('../views/music/Main')
      },{
        path: '/music/artists/:artistId',
        component: () => import('../views/music/Artist'),
        props: true
      },{
        path: '/music/albums/:albumId',
        component: () => import('../views/music/Album'),
        props: true
      }
    ]
  },{
    path: '/musicupload',
    component: () => import('../views/music/MusicUpload'),
    meta: {
      title: 'Загрузка музыки'
    },
    name: 'musicupload',
    alias: '/musicupload',
    admin: 1
  },{
    path: '/:catchAll(.*)',
    component: () => import('../views/Home'),
    meta: {
      title: '404'
    },
    name: '404'
  }],
  linkActiveClass: 'is-active',
  linkExactActiveClass: 'is-active'
})

router.beforeEach((to, from, next ) => {
  document.title = `${to.meta.title}`
  if(to.name !== 'login') {
    if(!store.getters.isLoggedIn) {
      return next({
        name: 'login'
      })
    }
  }
  if(to.name === 'login' && store.getters.isLoggedIn) {
    return next({
      name: 'home'
    })
  }

  next()
})

export default router
