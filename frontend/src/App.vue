<template>
  <el-config-provider :locale="locale">
    <div class="area">
      <div class="main">
        <header class="app-header" v-if="$store.getters.isLoggedIn">
          <div class="app-header__sidebar">Unreal Portal</div>
          <div class="app-header__content">
            <the-avatar></the-avatar>
          </div>
        </header>
        <el-container>
          <the-sidebar v-if="$store.getters.isLoggedIn"></the-sidebar>
          <el-container class="content">
            <router-view/>
            <footer class="app-footer" v-if="$store.getters.isLoggedIn">Home Portal v.0.0.4</footer>
          </el-container>
        </el-container>
      </div>
    </div>
    <music-player v-if="$store.getters.isLoggedIn"></music-player>
  </el-config-provider>
</template>

<script>
  import { ElConfigProvider } from 'element-plus'
  import ru from 'element-plus/lib/locale/lang/ru'

  import TheSidebar from './components/TheSidebar'
  import TheAvatar from './components/TheAvatar'
  import MusicPlayer from './components/music/playing/MusicPlayer'

  export default {
    setup() {
      return {
        locale: ru,
      }
    },
    methods: {
      logout() {
        this.$store.dispatch('logout').then(() => {
          this.$router.push({name: 'login'})
        })
      }
    },
    provide() {
      return {
        logout: this.logout
      }
    },
    components: {
      ElConfigProvider,
      TheSidebar,
      TheAvatar,
      MusicPlayer
    }
  }
</script>

<style lang="scss">
  #portal {
    display: flex;
    flex-direction: column;
    height: 100%;
    overflow: hidden;
  }
  .area {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100%;
  }
  .main {
    flex: auto 1 1;
    display: flex;
    flex-direction: column;
    height: 100%;
    overflow: hidden;

    .app-header {
      position: sticky;
      display: flex;
      height: 56px;
      background: #fff;
      box-shadow: 0 2px 4px rgba(0, 0, 0, .08);

      &__sidebar {
        display: flex;
        align-items: center;
        width: 220px;
        height: 56px;
        padding: 0 20px;
        background: #374f65;
        color: #fff;
      }
      &__content {
        display: flex;
        justify-content: flex-end;
        flex: 1;
      }
    }
  }
  .el-container {
    height: 100%;

    .content {
      flex-direction: column;
    }
  }
  .el-menu-item {
    --el-menu-item-height: 44px;
    --el-menu-item-font-size: 16px;
  }
  .el-main {
    background-color: #f1f1f1;
  }
  .app-footer {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 40px;
    background-color: #fff;
    color: var(--el-text-color-primary);
  }
</style>
