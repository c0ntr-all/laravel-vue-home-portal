const routes = [{
  path: '/',
  name: 'main',
  component: () => import('layouts/MainLayout.vue'),
  children: [{
    path: '/profile',
    component: () => import('pages/client/profile/Profile.vue'),
    meta: {
      title: 'Профиль'
    },
    name: 'profile',
    alias: '/profile',
    menu: false
  }, {
    path: '/settings',
    component: () => import('pages/client/profile/Settings.vue'),
    meta: {
      title: 'Настройки'
    },
    name: 'settings',
    alias: '/settings',
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
    component: () => import('pages/client/Tasks.vue'),
    meta: {
      title: 'Задачи',
      icon: 'list'
    },
    name: 'tasks',
    alias: '/tasks',
    menu: true
  }, {
    path: '/reminds',
    component: () => import('pages/client/Reminds.vue'),
    meta: {
      title: 'Напоминания',
      icon: 'calendar_month'
    },
    name: 'reminds',
    alias: '/reminder',
    menu: true
  }, {
    path: '/music',
    component: () => import('layouts/Music.vue'),
    menu: true,
    meta: {
      title: 'Музыка',
      icon: 'music_note'
    },
    children: [{
      path: '/music',
      component: () => import('pages/client/music/Music.vue'),
      name: 'music',
      props: true,
    }, {
      path: '/music/tags/:slug',
      component: () => import('pages/client/music/Tag.vue'),
      name: 'tag',
      props: true,
      meta: {
        title: 'Музыка'
      }
    }, {
      path: '/music/artists/:id/show',
      component: () => import('pages/client/music/Artist.vue'),
      name: 'artist',
      props: true,
      meta: {
        title: 'Музыка'
      },
      children: [{
        path: '/music/artists/:id/tracks',
        component: () => import('pages/client/music/Artist.vue'),
        name: 'artist-tracks',
        props: true,
      }, {
        path: '/music/artists/:id/albums',
        component: () => import('pages/client/music/Artist.vue'),
        name: 'artist-albums',
        props: true,
      }, {
        path: '/music/artists/:id/similar',
        component: () => import('pages/client/music/Artist.vue'),
        name: 'artist-similar',
        props: true,
      }]
    }, {
      path: '/music/albums/:id/show',
      component: () => import('pages/client/music/Album.vue'),
      name: 'album',
      props: true,
      meta: {
        title: 'Музыка'
      }
    }, {
      path: '/music/playlists/:id/show',
      component: () => import('pages/client/music/Playlist.vue'),
      name: 'playlist',
      props: true,
      meta: {
        title: 'Playlists'
      }
    }]
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
  }, {
    path: '/admin/music',
    component: () => import('pages/admin/music/Music.vue'),
    meta: {
      title: 'Управление музыкой',
      icon: 'settings'
    },
    name: 'musicmanage',
    alias: '/admin/music',
    admin: true
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
  path: '/:catchAll(.*)*',
  component: () => import('pages/404.vue')
}]

export default routes
