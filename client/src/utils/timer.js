export default class Timer {
  constructor() {
    this.targetSeconds = null;
    this.startedAt = null;
    this.pausedAt = null;
    this.pausedSeconds = 0;
    this.timerId = null

    this.clearTrigger();
  }

  start() {
    this.clearTrigger();
    this.startedAt = this.now();
  }

  pause() {
    this.pausedAt = this.now();
    this.clearTrigger();
  }

  resume() {
    if (this.startedAt !== null && this.pausedAt !== null) {
      this.pausedSeconds += this.now() - this.pausedAt;
      this.pausedAt = null;

      if (!this.timerId && this.targetSeconds !== null) {
        this.setTrigger(this.targetSeconds - this.getElapsed());
      }
    }
  }

  update(seconds) {
    if (this.startedAt !== null) {
      this.targetSeconds = seconds;

      if (this.targetSeconds !== null) {
        if (this.pausedAt === null) {
          this.setTrigger(this.targetSeconds - this.getElapsed());
        }
      } else {
        this.clearTrigger();
      }
    }
  }

  getRemainingSeconds() {
    return this.targetSeconds !== null ? this.targetSeconds - this.getElapsed() : null;
  }

  setTrigger(seconds) {
    this.clearTrigger();
    this.timeoutId = setTimeout(seconds * 1000);
  }

  clearTrigger() {
    if (this.timerId) {
      clearTimeout(this.timerId);
    }
    this.timerId = null;
  }

  getElapsed() {
    if (this.startedAt === null) {
      return null;
    }

    let elapsed = this.now() - this.startedAt - this.pausedSeconds;

    if (this.pausedAt !== null) {
      elapsed -= this.now() - this.pausedAt;
    }

    return elapsed;
  }

  isExpired() {
    return this.getRemainingSeconds() !== null && remainingSeconds < 0;
  }

  now() {
    return Math.round(new Date().valueOf() / 1000);
  }
}
