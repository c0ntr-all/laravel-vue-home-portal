import { createRouter, createWebHistory } from 'vue-router'
import store from '../store/index'


const router = createRouter({
  history: createWebHistory(),
  routes: [{
      path: '/profile',
      component: () => import('../views/Profile'),
      title: 'Профиль',
      name: 'profile',
      alias: '/profile',
      menu: 0
    },{
      path: '/login',
      component: () => import('../views/auth/Login'),
      title: 'Авторизация',
      name: 'login',
      alias: '/login',
      menu: 0
    },{
      path: '/home',
      component: () => import('../views/Home'),
      title: 'Главная',
      name: 'home',
      alias: '/',
      menu: 1
    },{
      path: '/finances',
      component: () => import('../views/Finances'),
      title: 'Финансы',
      name: 'finances',
      alias: '/finances',
      menu: 1
    },{
      path: '/tasks',
      component: () => import('../views/Tasks'),
      title: 'Задачи',
      name: 'tasks',
      alias: '/tasks',
      menu: 1
    },{
      path: '/reminds',
      component: () => import('../views/Reminds'),
      title: 'Напоминания',
      name: 'reminds',
      alias: '/reminder',
      menu: 1
    },{
      path: '/restaurants',
      component: () => import('../views/Restaurants'),
      title: 'Рестораны',
      name: 'restaurants',
      alias: '/restaurants',
      menu: 1
    },{
      path: '/music',
      component: () => import('../views/music/Music'),
      title: 'Музыка',
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
      title: 'Загрузка музыки',
      name: 'musicupload',
      alias: '/musicupload'
    },{
      path: '/:catchAll(.*)',
      component: () => import('../views/Home'),
      name: '404'
    }
  ],
  linkActiveClass: 'is-active',
  linkExactActiveClass: 'is-active'
})

router.beforeEach((to, from, next ) => {
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
