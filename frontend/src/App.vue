<template>
  <el-config-provider :locale="locale">
    <div class="area">
      <div class="main">
        <el-container>
          <the-sidebar v-if="$store.getters.isLoggedIn"></the-sidebar>
          <el-container class="content">
            <router-view/>
          </el-container>
        </el-container>
        <el-footer v-if="$store.getters.isLoggedIn">Home Portal v.0.0.4</el-footer>
      </div>
    </div>
  </el-config-provider>
</template>

<script>
  import { ElConfigProvider } from 'element-plus'
  import ru from 'element-plus/lib/locale/lang/ru'

  import TheSidebar from './components/TheSidebar.vue'

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
    components: {ElConfigProvider,TheSidebar}
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
  }
  .el-container {
    height: 100%;

    .content {
      flex-direction: column;
    }
  }
  .el-header {
    display: flex;
    align-items: center;

    .page-header {
      font-size: 28px;
      font-weight: 300;
    }
  }
  .el-menu-item {
    --el-menu-item-height: 44px;
    --el-menu-item-font-size: 16px;
  }
  .el-footer {
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #b3c0d1;
    color: var(--el-text-color-primary);
  }
</style>
