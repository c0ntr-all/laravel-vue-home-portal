<template>
  <slot>
    <div class="playlist-track">
      {{ idx }} - {{ track.name }}
    </div>
  </slot>
</template>
<script>
import { ref, computed} from 'vue'
export default {
  props: {
    idx: Number,
    track: Object
  },
  setup(props) {
    return {
      idx: computed(() => {
        return props.idx + 1
      })
    }
  }
}
</script>
<style>

</style>
