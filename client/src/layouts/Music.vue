<template>
  <div class="music-wrapper">
    <div class="music-left">
      <router-view />
    </div>
    <div class="music-right">

    </div>
  </div>
</template>
<style lang="scss" scoped>
.music-wrapper {
  display: flex;
}
.music-left {
  max-width: 890px;
}
.music-right {
  flex-grow: 1;
}
</style>
