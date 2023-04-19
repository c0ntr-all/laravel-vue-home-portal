<template>
  <div class="q-mb-lg">
    <q-skeleton type="QBtn" width="200px" />
  </div>
  <div class="task-lists row items-start q-gutter-md q-mb-lg">
    <q-card
      v-for="q in Math.floor(Math.random() * 10)"
      class="list"
    >
      <q-card-section class="list__header">
        <q-skeleton type="text" style="width: 75px" />
      </q-card-section>
      <q-separator dark />
      <q-card-section class="list__body">
        <q-skeleton
          v-for="q in Math.floor(Math.random() * 7)"
          type="QBtn"
          style="height: 37px; width: 100%; margin-bottom: 8px"
        />
      </q-card-section>
      <q-card-section class="list__footer">
      </q-card-section>
    </q-card>
  </div>
</template>
<script>

</script>
<style lang="scss" scoped>
.list {
  position: relative;
  display: flex;
  flex-direction: column;
  width: 272px;
  max-height: 100%;
  border-radius: 3px;
  white-space: normal;
  background-color: #ebecf0;
  box-sizing: border-box;

  &__header {
    padding: 8px;
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
</style>
