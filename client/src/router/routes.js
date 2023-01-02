
const routes = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { path: '', component: () => import('pages/IndexPage.vue') }
    ],
    menu: false
  },{
    path: '/profile',
    component: () => import('layouts/MainLayout.vue'),
    meta: {
      title: 'Профиль'
    },
    name: 'profile',
    alias: '/profile',
    menu: false
  },{
    path: '/login',
    component: () => import('layouts/MainLayout.vue'),
    meta: {
      title: 'Авторизация'
    },
    name: 'login',
    alias: '/login',
    menu: false
  },{
    path: '/home',
    component: () => import('layouts/MainLayout.vue'),
    meta: {
      title: 'Главная'
    },
    name: 'home',
    alias: '/',
    menu: true
  },{
    path: '/finances',
    component: () => import('layouts/MainLayout.vue'),
    meta: {
      title: 'Финансы'
    },
    name: 'finances',
    alias: '/finances',
    menu: true
  },{
    path: '/tasks',
    component: () => import('layouts/MainLayout.vue'),
    meta: {
      title: 'Задачи'
    },
    name: 'tasks',
    alias: '/tasks',
    menu: true
  },{
    path: '/reminds',
    component: () => import('layouts/MainLayout.vue'),
    meta: {
      title: 'Напоминания'
    },
    name: 'reminds',
    alias: '/reminder',
    menu: true
  },{
    path: '/restaurants',
    component: () => import('layouts/MainLayout.vue'),
    meta: {
      title: 'Рестораны'
    },
    name: 'restaurants',
    alias: '/restaurants',
    menu: true
  },{
    path: '/music',
    component: () => import('layouts/MainLayout.vue'),
    meta: {
      title: 'Музыка'
    },
    name: 'music',
    alias: '/music',
    menu: true,
    // children: [{
    //   path: '/music',
    //   component: () => import('../views/music/Main')
    // },{
    //   path: '/music/tags/:slug',
    //   component: () => import('../views/music/Tag'),
    //   props: true
    // },{
    //   path: '/music/artists/:artistId',
    //   component: () => import('../views/music/Artist'),
    //   props: true
    // },{
    //   path: '/music/albums/:albumId',
    //   component: () => import('../views/music/Album'),
    //   props: true
    // }]
  },{
    path: '/video',
    component: () => import('layouts/MainLayout.vue'),
    meta: {
      title: 'Видео'
    },
    name: 'video',
    alias: '/video',
    menu: true
  },{
    path: '/musicupload',
    component: () => import('layouts/MainLayout.vue'),
    meta: {
      title: 'Управление музыкой'
    },
    name: 'musicupload',
    alias: '/musicupload',
    admin: true
  },{
    path: '/:catchAll(.*)*',
    component: () => import('pages/ErrorNotFound.vue')
  }
]

export default routes
