const insertIntoTree = (array, id, tag) => {
  for (let i = 0; i < array.length; i++) {
    if (id === array[i].id) {
      if (array[i].children) {
        array[i].children.push(tag)
      } else {
        array[i].children = [tag]
      }
      return
    } else if (array[i].children) {
      insertIntoTree(array[i].children, id, tag)
    }
  }
}

export default insertIntoTree
