<template>
    <span>
      {{displayValue}}
    </span>
</template>
<script>
    import {requestAnimationFrame, cancelAnimationFrame} from './requestAnimationFrame.js'
    export default {
        props: {
            startval: {
                type: Number,
                required: false,
                default: 0
            },
            endval: {
                type: Number,
                required: false,
                default: 2017
            },
            duration: {
                type: Number,
                required: false,
                default: 3000
            },
            autoplay: {
                type: Boolean,
                required: false,
                default: true
            },
            decimals: {
                type: Number,
                required: false,
                default: 0,
                validator(value) {
                    return value >= 0
                }
            },
            decimal: {
                type: String,
                required: false,
                default: '.'
            },
            separator: {
                type: String,
                required: false,
                default: ','
            },
            prefix: {
                type: String,
                required: false,
                default: ''
            },
            suffix: {
                type: String,
                required: false,
                default: ''
            },
            useEasing: {
                type: Boolean,
                required: false,
                default: true
            },
            easingFn: {
                type: Function,
                default(t, b, c, d) {
                    return c * (-Math.pow(2, -10 * t / d) + 1) * 1024 / 1023 + b;
                }
            }
        },
        data() {
            return {
                localstartval: this.startval,
                displayValue: this.formatNumber(this.startval),
                printVal: null,
                paused: false,
                localDuration: this.duration,
                startTime: null,
                timestamp: null,
                remaining: null,
                rAF: null
            };
        },
        computed: {
            countDown() {
                return this.startval > this.endval
            }
        },
        mounted() {
            if (this.autoplay) {
                this.start();
            }
            this.$emit('mountedCallback')
        },
        methods: {
            start() {
                this.localstartval = this.startval;
                this.startTime = null;
                this.localDuration = this.duration;
                this.paused = false;
                this.rAF = requestAnimationFrame(this.count);
            },
            pauseResume() {
                if (this.paused) {
                    this.resume();
                    this.paused = false;
                } else {
                    this.pause();
                    this.paused = true;
                }
            },
            pause() {
                cancelAnimationFrame(this.rAF);
            },
            resume() {
                this.startTime = null;
                this.localDuration = +this.remaining;
                this.localstartval = +this.printVal;
                requestAnimationFrame(this.count);
            },
            reset() {
                this.startTime = null;
                cancelAnimationFrame(this.rAF);
                this.displayValue = this.formatNumber(this.startval);
            },
            count(timestamp) {
                if (!this.startTime) this.startTime = timestamp;
                this.timestamp = timestamp;
                const progress = timestamp - this.startTime;
                this.remaining = this.localDuration - progress;
                if (this.useEasing) {
                    if (this.countDown) {
                        this.printVal = this.localstartval - this.easingFn(progress, 0, this.localstartval - this.endval, this.localDuration)
                    } else {
                        this.printVal = this.easingFn(progress, this.localstartval, this.endval - this.localstartval, this.localDuration);
                    }
                } else {
                    if (this.countDown) {
                        this.printVal = this.localstartval - ((this.localstartval - this.endval) * (progress / this.localDuration));
                    } else {
                        this.printVal = this.localstartval + (this.localstartval - this.startval) * (progress / this.localDuration);
                    }
                }
                if (this.countDown) {
                    this.printVal = this.printVal < this.endval ? this.endval : this.printVal;
                } else {
                    this.printVal = this.printVal > this.endval ? this.endval : this.printVal;
                }
                this.displayValue = this.formatNumber(this.printVal)
                if (progress < this.localDuration) {
                    this.rAF = requestAnimationFrame(this.count);
                } else {
                    this.$emit('callback');
                }
            },
            isNumber(val) {
                return !isNaN(parseFloat(val))
            },
            formatNumber(num) {
                num = num.toFixed(this.decimals);
                num += '';
                const x = num.split('.');
                let x1 = x[0];
                const x2 = x.length > 1 ? this.decimal + x[1] : '';
                const rgx = /(\d+)(\d{3})/;
                if (this.separator && !this.isNumber(this.separator)) {
                    while (rgx.test(x1)) {
                        x1 = x1.replace(rgx, '$1' + this.separator + '$2');
                    }
                }
                return this.prefix + x1 + x2 + this.suffix;
            }
        },
        destroyed() {
            cancelAnimationFrame(this.rAF)
        }
    };
</script>