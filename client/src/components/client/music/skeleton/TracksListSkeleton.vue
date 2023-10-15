<template>
  <div class="bg-white q-pa-lg">
    <div class="tracks-list q-gutter-xs q-pr-lg">
      <div
        v-for="q in Math.floor(Math.random() * 10)"
        class="music-track flex no-wrap self-start items-center col-grow q-pr-sm rounded-borders"
      >
        <div class="music-track__left">
          <div class="music-track-cover q-mr-md">
            <q-skeleton size="40px" />
          </div>
          <div class="music-track__title">
            <div class="music-track__name">
              <q-skeleton type="text" width="300px" />
            </div>
            <div class="music-track__artist">
              <q-skeleton type="text" width="200px" />
            </div>
          </div>
        </div>
        <div class="music-track__right">
          <div class="music-track__rate flex">
            <q-skeleton v-for="q in 4" class="q-ml-xs" type="circle" size="20px" />
          </div>
          <div class="music-track__end-column">
            <div class="music-track__time">
              <q-skeleton type="text" width="30px" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
}
</script>
<style lang="scss" scoped>
.tracks-list {
  max-width: 700px;
  border-right: 1px solid #ccc;
}
.music-track {
  position: relative;

  &__left {
    display: flex;
    align-items: center;
    flex-grow: 1;
    flex-shrink: 1;
    overflow: hidden;
  }

  &__right {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-shrink: 0;
  }

  &-cover {
    display: flex;
    position: relative;
    justify-content: center;
    align-items: center;
    flex-shrink: 0;
    width: 40px;
    height: 40px;
    margin: 4px;
    border-radius: 8px;

    &__image {
      border-radius: 8px;
    }
  }
  &__artist {
    font-size: 12.5px;
    line-height: 16px;
    font-weight: bold;
  }
  &__title {
    white-space: nowrap;
    overflow: hidden;
    margin-left: 4px;
  }
  &__name {
    font-size: 12.5px;
    line-height: 16px;
    text-overflow: ellipsis;
    overflow: hidden;
  }
  &__rate {
    margin-right: 1rem;
  }
  &__end-column {
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 48px;
  }
  &__time {
    flex-shrink: 0;
    color: #818c99;
    cursor: pointer;
    font-size: 12px;
    min-width: 3em;
    text-align: right;
  }
}
</style>
