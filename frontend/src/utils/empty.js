const empty = (data) => {
  if (data === null) {
    return true
  }
  if (typeof data === 'object' || Array.isArray(data)) {
    if ((Object.keys(data).length === 0) || (data.length === 0)) {
      return true;
    }
  } else {
    switch (data) {
      case typeof(data) === "undefined":
      case "":
      case 0:
      case "0":
      case false:
        return true;
    }
  }
  return false;
}

export default empty
