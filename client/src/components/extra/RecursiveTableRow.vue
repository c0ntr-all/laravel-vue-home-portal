<template>
  <q-tr :props="props">
    <q-td v-if="props.row.children" auto-width>
      <q-btn @click="props.expand = !props.expand" :icon="props.expand ? 'expand_more' : 'chevron_right'" size="xs" round dense />
    </q-td>
    <q-td v-else auto-width />

    <q-td v-for="col in props.cols" :key="col.name" :props="props">{{ props }}</q-td>
  </q-tr>
  <template v-if="props.row.children">
    <recursive-table-row
      v-for="child in props.row.children"
      :key="child.id"
      :props="prepareProps(props, child)"
    />
  </template>
</template>
<script>
export default {
  name: "RecursiveTableRow",
  props: {
   props: Object
  },
  setup() {
     const prepareProps = (props, row) => {
       props.key = row.id
       props.row = row

       props.cols.map(item => {
         let newItem = JSON.parse(JSON.stringify(item))
         switch(item.name) {
           case 'id':
             newItem.value = row.id
             break;

           case 'name':
             newItem.value = row.label
             break;

           case 'createdAt':
             newItem.value = row.createdAt
             break;
         }
         return newItem
       })

       console.log(props.row.label)

       return props
     }
     return {
       prepareProps
     }
  }
 }
</script>
