<template>
  <q-card flat>
    <q-card-section>
      <div class="text-h6">{{ widget.name }}</div>
    </q-card-section>
    
    <q-separator />

    <q-card-section>
      <div class="row justify-between">
        <div class="flex column">
          <p class="text-subtitle2 q-ma-none">Всего: {{ widget.data.remindsCount }}</p>
          <p class="text-subtitle2 q-ma-none">Активных: {{ widget.data.activeRemindsCount }}</p>
        </div>
      </div>
    </q-card-section>

    <q-card-section>
      <p class="text-subtitle2 q-ma-none">Ближайшие напоминания (3 дня):</p>
      <q-table
        :rows="widget.data.reminds"
        :columns="columns"
        row-key="title"
        :flat="true"
        :rows-per-page-options="[0]"
        :pagination.sync="{page: 1, rowsPerPage: 0}"
        ref="remindTable"
        dense
      />
    </q-card-section>
  </q-card>
</template>
<script>
export default {
  props: ['widget'],
  setup() {
    const columns = [
      { name: 'title', label: 'Title', field: 'title', align: 'left', sortable: true },
      { name: 'date', label: 'Date', field: 'datetime', align: 'center', sortable: true }
    ]

    return {
      columns
    }
  }
}
</script>
<style lang="scss" scoped>

</style>
