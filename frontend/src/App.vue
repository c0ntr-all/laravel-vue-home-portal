<template>
  <div class="area">
    <div class="panes">
      <the-sidebar></the-sidebar>
      <div class="content"></div>
    </div>
  </div>
</template>

<script>
import TheSidebar from './components/TheSidebar.vue'

export default {
  components: {TheSidebar}
}
</script>

<style lang="scss">
  #portal {
    display: flex;
    flex-direction: column;
    height: 100%;
  }
  .area {
    flex: auto 1 1;
    height: 100%;
    overflow: hidden;
  }
  .panes {
    flex: auto 1 1;
    height: 100%;
    display: flex;
    overflow: hidden;
  }
  .content {
    flex: auto 1 1;
    width: 0;
    overflow-x: hidden;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
  }
</style>
