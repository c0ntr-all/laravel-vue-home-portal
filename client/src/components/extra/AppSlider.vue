<template>
  <div class="app-slider" :style="`width: ${width}`">
    <div class="app-slider__wrapper" :class="{active: moving}" ref="rangeLine">
      <div class="app-slider__line">
        <div class="app-slider__amount" :style="`width: ${circlePosition}%`"></div>
        <div class="app-slider__circle" :style="`left: ${circlePosition}%`" ref="rangeHandle"></div>
      </div>
    </div>
  </div>
</template>
<script setup>
import { ref, onMounted, watchEffect } from "vue"

const props = defineProps({
  onlyDrop: {
    type: Boolean,
    default: false
  },
  width: {
    type: String,
    default: '100%'
  },
  data: {
    type: Number,
    default: 0
  }
})
const emit = defineEmits(['move'])

const rangeLine = ref(null)
const rangeHandle = ref(null)
const min = 0
const max = 100
let newValue = ref(null)
const circlePosition = ref(props.data)
let moving = ref(false)
const isMouseDragging = ref(false)

const _move = clientX => {
  moving.value = true
  let bounds = rangeLine.value.getBoundingClientRect()
  let posX = (clientX - bounds.left) / bounds.width

  let value = min + (max - min) * posX
  value = Math.round(Math.min(Math.max(value, min), max))

  if (value < min || value > max) {
    return
  }

  if (props.onlyDrop) {
    if (isMouseDragging.value) {
      circlePosition.value = newValue.value
    }
  } else {
    circlePosition.value = newValue.value
    emit('move', newValue.value)
  }

  if (newValue.value == null || (value !== newValue.value)) {
    newValue.value = value
  }
}

const process = (clientX, target) => {
  if (target !== rangeHandle.value) {
    _move(clientX)
  }

  let move = (e) => {
    e.preventDefault()
    _move(e.clientX)
  }

  let mousemove = e => {
    e.preventDefault()
    isMouseDragging.value = true
    _move(e.clientX)
  }

  let end = () => {
    document.removeEventListener("mousemove", mousemove)
    document.removeEventListener("touchmove", move)
    document.removeEventListener("mouseup", end)
    document.removeEventListener("touchend", end)
    document.removeEventListener("touchcancel", end)

    emit('move', newValue.value)
    moving.value = false
    isMouseDragging.value = false
  }

  document.addEventListener("mousemove", mousemove)
  document.addEventListener("touchmove", move)
  document.addEventListener("mouseup", end)
  document.addEventListener("touchend", end)
  document.addEventListener("touchcancel", end)
}

onMounted(() => {
  rangeLine.value.addEventListener("mousedown", event => {
    // Only left mouse button
    if (event.button !== 0) {
      return
    }
    event.preventDefault()

    process(event.clientX, event.target)
  })

  rangeLine.value.addEventListener("touchstart", event => {
    if (event.changedTouches && event.changedTouches[0]) {
      event.preventDefault()
      process(event.changedTouches[0].clientX, event.target)
    }
  })
})

watchEffect(() => {
  if (props.onlyDrop && !isMouseDragging.value) {
      circlePosition.value = props.data
  }
})
</script>
<style lang="scss" scoped>
.app-slider {
  &__wrapper {
    padding: 7px 0;

    &:hover, &.active {
      .app-slider__circle {
        opacity: 1;
      }
    }
  }
  &__line {
    position: relative;
    width: 100%;
    height: 2px;
    background: $primary-light;
    z-index: -1;
  }
  &__amount {
    width: 0;
    top: auto;
    bottom: 0;
    height: 2px;
    background: $primary;
  }
  &__circle {
    position: absolute;
    top: -2px;
    width: 6px;
    height: 6px;
    margin-left: -3px;
    border-radius: 50%;
    background: $primary;
    opacity: 0;
    -webkit-transition: top 80ms linear,width 80ms linear,height 80ms linear,margin-left 80ms linear,opacity 160ms linear;
    transition: top 80ms linear,width 80ms linear,height 80ms linear,margin-left 80ms linear,opacity 160ms linear;
  }

  &:hover {
    cursor: pointer;
  }
}
</style>
