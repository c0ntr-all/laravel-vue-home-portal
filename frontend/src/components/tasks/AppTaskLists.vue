<template>
  <el-space alignment="flex-start" wrap>
    <app-task-list
      v-for="list in lists"
      :listId="list.id"
      :listTitle="list.title"
      :items="list.items"
    ></app-task-list>
    <app-list-create-button
      @listCreated="addList"
    ></app-list-create-button>
  </el-space>
</template>

<script>
  import AppTaskList from "./AppTaskList";
  import AppListCreateButton from "./AppListCreateButton";

  export default {
    props: ['lists'],
    data() {
      return {
        tasks: []
      }
    },
    methods: {
      addList(data) {
        this.lists.push(data)
      }
    },
    components: {AppTaskList,AppListCreateButton}
  }
</script>

<style lang="scss" scoped>

</style>
