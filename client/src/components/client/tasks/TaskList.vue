<template>
  <div class="list">
    <div class="list__header">
      <div class="list__header--cover js-header-cover"></div>
      <textarea class="list__header-name"
                spellcheck="false"
                dir="auto"
                maxlength="512"
                data-autosize="true"
                style="overflow: hidden; overflow-wrap: break-word; height: 28px;"
      >{{ list.title }}</textarea>
    </div>
    <div class="list__body">
    </div>
    <div class="list__footer">
    </div>
  </div>
</template>

<script>
export default {
  props: ['list'],
  setup() {
    return {

    }
  }
}
</script>

<style lang="scss" scoped>
.list {
  background-color: #ebecf0;
  border-radius: 3px;
  box-sizing: border-box;
  display: flex;
  flex-direction: column;
  max-height: 100%;
  position: relative;
  white-space: normal;
  width: 272px;

  &__header {
    position: relative;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 8px;

    &-name {
      background: #0000;
      border-radius: 3px;
      box-shadow: none;
      font-weight: 600;
      height: 28px;
      margin: -4px 0;
      max-height: 256px;
      min-height: 20px;
      padding: 4px 8px;
      border: none;
      resize: none;
      overflow: hidden;
      overflow-wrap: break-word;

      &.is-active {
        background-color: #fff;
        box-shadow: inset 0 0 0 2px #0079bf;
      }
    }
    &--cover {
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      top: 0;
      cursor: pointer;
    }
  }
  &__body {
    flex: 1 1 auto;
    margin: 0 4px;
    min-height: 0;
    overflow-x: hidden;
    overflow-y: auto;
    padding: 0 4px;
  }
  &__footer {
    padding: 10px 8px;
  }
}
.is-hidden {
  display: none;
}
</style>
