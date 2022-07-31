<template>
  <svg xmlns="http://www.w3.org/2000/svg"
    :width="width"
    :height="height"
    viewBox="0 0 485 485"
    :aria-labelledby="iconName"
    role="presentation"
    style="enable-background:new 0 0 485 485;"
  >
    <title :id="iconName" lang="en">{{ iconName }} icon </title>
    <g :fill="iconColor"><slot /></g>
  </svg>
</template>
<script>
  export default {
    props: {
      iconName: {
        type: String,
        default: 'box'
      },
      width: {
        type: [Number, String],
        default: 18
      },
      height: {
        type: [Number, String],
        default: 18
      },
      iconColor: {
        type: String,
        default: 'currentColor'
      }
    }
  }
</script>
