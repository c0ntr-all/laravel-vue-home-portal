const getChanges = (oldProps, newProps) => {
  const data = {}
  for (const key in newProps) {
    if (newProps[key] !== oldProps[key]) {
      data[key] = newProps[key];
    }
  }
  return data
}

export default getChanges
