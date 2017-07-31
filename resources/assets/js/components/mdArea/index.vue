<template>
    <div class="markdown-area" v-html="localData"></div>
</template>

<script>
    import marked from 'marked'
    import highlight from 'highlight.js'
    import 'highlight.js/styles/github.css'
    export default {
        name: 'MDArea',
        props: ['oriData'],
        computed: {
            localData() {
                return marked(this.oriData)
            }
        },
        created() {
            this.setMdConf()
        },
        methods: {
            setMdConf() {
                marked.setOptions({
                    highlight: function (code) {
                        return highlight.highlightAuto(code).value
                    }
                })
            }
        }
    }
</script>

<style lang="stylus">
    @import "index.styl";
</style>