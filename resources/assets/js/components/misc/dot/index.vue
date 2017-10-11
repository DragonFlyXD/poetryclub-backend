<template lang="html">
    <canvas id="df-dot"></canvas>
</template>

<script>
    import Dot from './dot.js'
    export default {
        name: 'Dot',
        mounted() {
            Dot('df-dot', {
                //  canvas 默认宽高
                cW: window.innerWidth,
                cH: window.innerHeight,

                // 粒子数目
                numDot: 100,

                // 粒子半径
                radDot: 15,

                // 大小是否不同
                isRangeRad: true,

                // 粒子颜色
                dotColor: '#FFFFFF',

                // 是否连线
                isLine: true,

                // 线条长度
                lineDist: 200,

                // 线条颜色
                lineColor: '#FFFFFF',

                // 反弹系数
                bounce: 1,

                // 透明度
                opacity: 0.5,

                // 是否触摸连线
                isTouch: false,

                // x方向运动速度
                vxRange: 1,

                // y方向运动速度
                vyRange: 1,

                // 是否与边界碰撞检测
                isWallCollisionTest: true,

                // 球体间是否发生碰撞检测
                isBallCollisionTest: true
            })
        }
    }
</script>

<style lang="stylus" scoped>
    #df-dot
        position absolute
        z-index 99
</style>
