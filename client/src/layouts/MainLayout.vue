<template>
  <q-layout view="lHh Lpr lFf">
    <q-header>
      <q-toolbar>
        <q-btn
          flat
          dense
          round
          icon="menu"
          aria-label="Menu"
          @click="toggleLeftDrawer"
        />
        <q-toolbar-title>Home Portal</q-toolbar-title>
        <div>Quasar v{{ $q.version }}</div>
      </q-toolbar>
      <div class="q-px-lg q-pt-xl q-mb-md">
        <div class="text-h3">Pagetitle</div>
        <div class="text-subtitle">{{ todaysDate }}</div>
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
      <q-scroll-area style="height: calc(100% - 185px); margin-top: 185px; border-right: 1px solid #ddd">
        <q-list padding>
          <q-item
            to="/"
            exact
            clickable
            v-ripple
          >
            <q-item-section avatar><q-icon name="list" /></q-item-section>
            <q-item-section>Todo</q-item-section>
          </q-item>
          <q-item
            to="/help"
            exact
            clickable
            v-ripple
          >
            <q-item-section avatar><q-icon name="help" /></q-item-section>
            <q-item-section>Help</q-item-section>
          </q-item>
        </q-list>
      </q-scroll-area>

      <q-img class="absolute-top" src="images/mountains.jpeg" style="height: 185px">
        <div class="absolute-bottom bg-transparent">
          <q-avatar size="56px" class="q-mb-sm">
            <img src="https://cdn.quasar.dev/img/boy-avatar.png">
          </q-avatar>
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
import { date } from 'quasar'

import EssentialLink from 'components/EssentialLink.vue'

export default defineComponent({
  name: 'MainLayout',

  components: {
    EssentialLink
  },

  setup () {
    const leftDrawerOpen = ref(false)

    return {
      leftDrawerOpen,
      toggleLeftDrawer () {
        leftDrawerOpen.value = !leftDrawerOpen.value
      }
    }
  },
  computed: {
    todaysDate() {
      let timestamp = Date.now()
      return date.formatDate(timestamp, 'YYYY-MM-DD HH:mm')
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
