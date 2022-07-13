<template>
  <div class="track-card">
    <div class="track-card__number">{{ track.number }}</div>
    <div class="track-card__name">{{ track.name }}</div>
    <div class="track-card__duration">{{ track.duration }}</div>
  </div>
</template>
<script>
  export default {
    props: {
      track: Object
    }
  }
</script>
<style lang="scss" scoped>
  .track-card {
    display: flex;
    padding: .75rem 0;
    border-bottom: 1px solid #d7d7d7;

    &:hover {
      cursor: pointer;
      background: rgba(0, 0, 0, .1);
    }

    &__number {
      flex: 0 0 40px;
      color: #777;
    }
    &__name {
      flex: 1 1 100%;
    }
    &__duration {
      color: #777;
    }
  }
</style>
