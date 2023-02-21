<template>
  <q-layout view="lHh Lpr lFf">
    <q-header>
      <q-toolbar>
        <q-btn
          @click="toggleLeftDrawer"
          icon="menu"
          aria-label="Menu"
          flat
          dense
          round
        />
        <q-toolbar-title>Home Portal</q-toolbar-title>
        <music-player></music-player>
        <div>Quasar v{{ $q.version }}</div>
      </q-toolbar>
      <div class="q-px-md q-pt-sm q-mb-md">
        <div class="text-h3">{{ this.$route.meta.title || 'No Title' }}</div>
      </div>
      <q-img
        src="images/mountains.jpeg"
        class="header-image absolute-top"
      />
    </q-header>

    <q-drawer
      v-model="leftDrawerOpen"
      show-if-above
      :width="250"
      :breakpoint="600"
    >
      <q-scroll-area style="height: calc(100% - 124px); margin-top: 124px; border-right: 1px solid #ddd">
        <q-list padding>
          <template v-for="item in $router.options.routes[0].children.filter(item => item.menu === true)">
            <q-item
              :index="item.path"
              :to="item.path"
              exact
              clickable
              v-ripple
            >
              <q-item-section avatar><q-icon :name="item.meta.icon ?? 'label'" /></q-item-section>
              <q-item-section>{{ item.meta.title }}</q-item-section>
            </q-item>
          </template>
          <hr>
          <template v-for="item in $router.options.routes[0].children.filter(item => item.admin === true)">
            <q-item
              :index="item.path"
              :to="item.path"
              exact
              clickable
              v-ripple
            >
              <q-item-section avatar><q-icon :name="item.meta.icon ?? 'label'" /></q-item-section>
              <q-item-section>{{ item.meta.title }}</q-item-section>
            </q-item>
          </template>
        </q-list>
      </q-scroll-area>

      <q-img class="absolute-top" src="images/mountains.jpeg" style="height: 124px">
        <div class="absolute-bottom bg-transparent">
          <q-btn class="q-mb-sm" :to="'/profile'" round flat>
            <q-avatar size="56px">
              <q-img src="https://cdn.quasar.dev/img/boy-avatar.png" />
            </q-avatar>
          </q-btn>
          <div class="text-weight-bold">User name</div>
          <div>@user-name</div>
        </div>
      </q-img>
    </q-drawer>

    <q-page-container>
      <keep-alive>
        <router-view />
      </keep-alive>
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
<style lang="scss">
  .header-image {
    height: 100%;
    z-index: -1;
    opacity: .2;
    filter: grayscale(100%);
  }
</style>
