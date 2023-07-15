const deleteFromTree = (array, id) => {
  for (let i = 0; i < array.length; i++) {
    if (array[i].id === id) {
      array.splice(i, 1)
      return
    }
    if (array[i].children) {
      deleteFromTree(array[i].children, id)
    }
  }
}

export default deleteFromTree
