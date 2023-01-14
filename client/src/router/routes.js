const routes = [{
  path: '/',
  name: 'main',
  component: () => import('layouts/MainLayout.vue'),
  children: [{
    path: '/profile',
    component: () => import('pages/Profile.vue'),
    meta: {
      title: 'Профиль'
    },
    name: 'profile',
    alias: '/profile',
    menu: false
  }, {
    path: '/home',
    component: () => import('pages/Main.vue'),
    meta: {
      title: 'Главная',
      icon: 'home'
    },
    name: 'home',
    alias: '/',
    menu: true
  }, {
    path: '/finances',
    component: () => import('pages/Finances.vue'),
    meta: {
      title: 'Финансы',
      icon: 'account_balance_wallet'
    },
    name: 'finances',
    alias: '/finances',
    menu: true
  }, {
    path: '/tasks',
    component: () => import('layouts/MainLayout.vue'),
    meta: {
      title: 'Задачи',
      icon: 'list'
    },
    name: 'tasks',
    alias: '/tasks',
    menu: true
  }, {
    path: '/reminds',
    component: () => import('layouts/MainLayout.vue'),
    meta: {
      title: 'Напоминания',
      icon: 'calendar_month'
    },
    name: 'reminds',
    alias: '/reminder',
    menu: true
  }, {
    path: '/restaurants',
    component: () => import('layouts/MainLayout.vue'),
    meta: {
      title: 'Рестораны',
      icon: 'emoji_food_beverage'
    },
    name: 'restaurants',
    alias: '/restaurants',
    menu: true
  }, {
    path: '/music',
    component: () => import('pages/music/Music.vue'),
    name: 'music',
    alias: '/music',
    menu: true,
    meta: {
      title: 'Музыка',
      icon: 'music_note'
    }
  }, {
    path: '/music/tags/:slug',
    component: () => import('pages/music/Tag.vue'),
    name: 'tag',
    props: true,
    meta: {
      title: 'Музыка'
    }
  }, {
    path: '/music/artists/:id',
    component: () => import('pages/music/Artist.vue'),
    name: 'artist',
    props: true,
    meta: {
      title: 'Музыка'
    }
  }, {
    path: '/music/albums/:id',
    component: () => import('pages/music/Album.vue'),
    name: 'album',
    props: true,
    meta: {
      title: 'Музыка'
    }
  }, {
    path: '/video',
    component: () => import('layouts/MainLayout.vue'),
    meta: {
      title: 'Видео',
      icon: 'videocam'
    },
    name: 'video',
    alias: '/video',
    menu: true
  }]
}, {
  path: '/login',
  component: () => import('layouts/Login.vue'),
  meta: {
    title: 'Авторизация'
  },
  name: 'login',
  alias: '/login',
  menu: false
}, {
  path: '/musicupload',
  component: () => import('layouts/MainLayout.vue'),
  meta: {
    title: 'Управление музыкой',
    icon: 'settings'
  },
  name: 'musicupload',
  alias: '/musicupload',
  admin: true
}, {
  path: '/:catchAll(.*)*',
  component: () => import('pages/404.vue')
}
]

export default routes
