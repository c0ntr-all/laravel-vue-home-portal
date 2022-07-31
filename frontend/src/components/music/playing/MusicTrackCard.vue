<template>
  <div class="track-card" @click="play(track)">
    <div class="track-card__play-icon" v-if="status === 'pause' || (status === 'play' && player.track.id !== track.id)">
      <svg version="1.1" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 485 485"
           style="enable-background:new 0 0 485 485;"
           xml:space="preserve"
      >
        <path d="M413.974,71.026C368.171,25.225,307.274,0,242.5,0S116.829,25.225,71.026,71.026C25.225,116.829,0,177.726,0,242.5
          s25.225,125.671,71.026,171.474C116.829,459.775,177.726,485,242.5,485s125.671-25.225,171.474-71.026
          C459.775,368.171,485,307.274,485,242.5S459.775,116.829,413.974,71.026z M242.5,455C125.327,455,30,359.673,30,242.5
          S125.327,30,242.5,30S455,125.327,455,242.5S359.673,455,242.5,455z"/>
        <polygon points="181.062,336.575 343.938,242.5 181.062,148.425 	"/>
      </svg>
    </div>
    <div class="track-card__play-icon" v-else>
      <svg version="1.1" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"
           viewBox="0 0 485 485" style="enable-background:new 0 0 485 485;" xml:space="preserve">
        <path d="M413.974,71.026C368.171,25.225,307.274,0,242.5,0S116.829,25.225,71.026,71.026C25.225,116.829,0,177.726,0,242.5
          s25.225,125.671,71.026,171.474C116.829,459.775,177.726,485,242.5,485s125.671-25.225,171.474-71.026
          C459.775,368.171,485,307.274,485,242.5S459.775,116.829,413.974,71.026z M242.5,455C125.327,455,30,359.673,30,242.5
          S125.327,30,242.5,30S455,125.327,455,242.5S359.673,455,242.5,455z"/>
        <rect x="172.5" y="140" width="55" height="205"/>
        <rect x="257.5" y="140" width="55" height="205"/>
      </svg>
    </div>
    <div class="track-card__number">{{ track.number }}</div>
    <div class="track-card__name">{{ track.name }}</div>
    <div class="track-card__duration">{{ track.duration }}</div>
  </div>
</template>
<script>
  import {mapGetters, mapActions} from 'vuex'

  export default {
    props: {
      track: Object
    },
    methods: {
      ...mapActions(['play']),
    },
    computed: {
      ...mapGetters(['player','status'])
    }
  }
</script>
<style lang="scss" scoped>
  .track-card {
    display: flex;
    position: relative;
    padding: .75rem;
    border-bottom: 1px solid #d7d7d7;

    &__play-icon {
      display: none;
      position: absolute;
      top: 0.325rem;
      left: 0.325rem;

      svg {
        width: 30px;
        height: 30px;
      }
    }

    &:hover {
      cursor: pointer;
      background: rgba(0, 0, 0, .1);

      .track-card__play-icon {
        display: inline-block;
      }
      .track-card__number {
        color: #fff;
      }
    }

    &__number {
      flex: 0 0 40px;
      color: #777;
    }
    &__name {
      flex: 1 1 100%;
    }
    &__duration {
      color: #777;
    }
  }
</style>
