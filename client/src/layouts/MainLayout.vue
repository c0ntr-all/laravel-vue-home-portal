<template>
  <q-layout view="lHh Lpr lFf">
    <q-header class="header q-py-sm flex items-center">
      <q-toolbar>
        <q-btn
          @click="toggleLeftDrawer"
          icon="menu"
          aria-label="Menu"
          color="primary"
          flat
          dense
          round
        />

        <music-player />

        <q-space />

        <q-btn class="q-ml-md" icon="dark_mode" color="primary" flat dense />

        <q-btn class="q-ml-md" icon="notifications" color="primary" flat dense />

        <q-btn class="q-ml-md" round flat>
          <q-avatar size="35px">
            <q-img src="https://cdn.quasar.dev/img/boy-avatar.png" />
          </q-avatar>

          <q-menu class="user-menu" style="width: 190px">
            <div class="row no-wrap">
              <q-list style="width: 100%">
                <q-item class="column items-center">
                  <q-item-section class="user-menu__name">Sergey Taturin</q-item-section>
                  <q-item-section class="user-menu__role" style="margin: 0">
                    <small class="text-grey-6">Admin</small>
                  </q-item-section>
                </q-item>

                <q-separator />

                <q-item :to="'/profile'" clickable>
                  <q-item-section side><q-icon name="person" /></q-item-section>
                  <q-item-section>Profile</q-item-section>
                </q-item>
                <q-item :to="'/settings'" clickable>
                  <q-item-section side><q-icon name="settings" /></q-item-section>
                  <q-item-section>Settings</q-item-section>
                </q-item>

                <q-separator />

                <q-item clickable>
                  <q-item-section side><q-icon name="logout" /></q-item-section>
                  <q-item-section>Logout</q-item-section>
                </q-item>
              </q-list>
            </div>
          </q-menu>
        </q-btn>
      </q-toolbar>
    </q-header>

    <q-drawer v-model="leftDrawerOpen" :width="250" :breakpoint="600" show-if-above>
      <q-scroll-area style="height: calc(100% - 75px); margin-top: 75px; border-right: 1px solid #ddd">
        <q-list padding>
          <template v-for="item in $router.options.routes[0].children.filter(item => item.menu === true)">
            <q-item
              :index="item.path"
              :to="item.path"
              class="sidebar-menu__item"
              exact
              clickable
              v-ripple
            >
              <q-item-section side>
                <q-icon :name="item.meta.icon ?? 'label'" color="primary" />
              </q-item-section>
              <q-item-section>{{ item.meta.title }}</q-item-section>
            </q-item>
          </template>

          <q-separator color="grey-3" />

          <template v-for="item in $router.options.routes[0].children.filter(item => item.admin === true)">
            <q-item
              :index="item.path"
              :to="item.path"
              class="sidebar-menu__item"
              exact
              clickable
              v-ripple
            >
              <q-item-section side>
                <q-icon :name="item.meta.icon ?? 'label'" color="primary" />
              </q-item-section>
              <q-item-section>{{ item.meta.title }}</q-item-section>
            </q-item>
          </template>
        </q-list>
      </q-scroll-area>

      <div class="logo-wrap q-px-md absolute-top">
        <q-img class="logo" src="logo/logo-1.svg" fit="contain" />
      </div>
    </q-drawer>

    <q-page-container>
      <q-page class="q-pa-lg">
        <div class="text-h4 q-mb-lg">{{ this.$route.meta.title || 'No Title' }}</div>
        <router-view />
      </q-page>
    </q-page-container>
  </q-layout>
</template>

<script>
import { defineComponent, ref } from 'vue'
import MusicPlayer from 'src/components/default/MusicPlayer.vue'

export default defineComponent({
  name: 'MainLayout',

  components: {MusicPlayer},

  setup () {
    const leftDrawerOpen = ref(false)

    return {
      leftDrawerOpen,
      toggleLeftDrawer () {
        leftDrawerOpen.value = !leftDrawerOpen.value
      }
    }
  }
})
</script>
<style lang="scss" scoped>
  .q-page {
    background-color: #f0f0f5;
  }
  .header {
    height: 75px;
    background-color: #ffffff;
    border-block-end: 1px solid #e9edf4;

    &-image {
      height: 100%;
      z-index: -1;
      opacity: .2;
      filter: grayscale(100%);
    }
  }
  .user-menu {
    &__name {

    }
    &__role {

    }
  }
  .logo-wrap {
    height: 75px;
    border-block-end: 1px solid #e9edf4;
    border-inline-end: 1px solid #e9edf4;
  }
  .logo {
    height: 75px;
  }
  .sidebar-menu {
    &__item {
      color: #282f53;
      font-size: 16px;
    }
  }
</style>
