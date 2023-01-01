
const routes = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { path: '', component: () => import('pages/IndexPage.vue') }
    ]
  },{
    path: '/profile',
    component: () => import('layouts/MainLayout.vue'),
    meta: {
      title: 'Профиль'
    },
    name: 'profile',
    alias: '/profile',
    menu: 0
  },{
    path: '/login',
    component: () => import('layouts/MainLayout.vue'),
    meta: {
      title: 'Авторизация'
    },
    name: 'login',
    alias: '/login',
    menu: 0
  },{
    path: '/home',
    component: () => import('layouts/MainLayout.vue'),
    meta: {
      title: 'Главная'
    },
    name: 'home',
    alias: '/',
    menu: 1
  },{
    path: '/finances',
    component: () => import('layouts/MainLayout.vue'),
    meta: {
      title: 'Финансы'
    },
    name: 'finances',
    alias: '/finances',
    menu: 1
  },{
    path: '/tasks',
    component: () => import('layouts/MainLayout.vue'),
    meta: {
      title: 'Задачи'
    },
    name: 'tasks',
    alias: '/tasks',
    menu: 1
  },{
    path: '/reminds',
    component: () => import('layouts/MainLayout.vue'),
    meta: {
      title: 'Напоминания'
    },
    name: 'reminds',
    alias: '/reminder',
    menu: 1
  },{
    path: '/restaurants',
    component: () => import('layouts/MainLayout.vue'),
    meta: {
      title: 'Рестораны'
    },
    name: 'restaurants',
    alias: '/restaurants',
    menu: 1
  },{
    path: '/music',
    component: () => import('layouts/MainLayout.vue'),
    meta: {
      title: 'Музыка'
    },
    name: 'music',
    alias: '/music',
    menu: 1,
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
    menu: 1
  },{
    path: '/musicupload',
    component: () => import('layouts/MainLayout.vue'),
    meta: {
      title: 'Управление музыкой'
    },
    name: 'musicupload',
    alias: '/musicupload',
    admin: 1
  },{
    path: '/:catchAll(.*)*',
    component: () => import('pages/ErrorNotFound.vue')
  }
]

export default routes
