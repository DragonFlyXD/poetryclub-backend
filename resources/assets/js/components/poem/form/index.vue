<template lang="html">
    <div class="df-poemForm">
        <el-form class="c-form main" ref="form" :model="form" :rules="rules">
            <el-form-item prop="title">
                <el-input
                        placeholder="诗文标题，1-50个字符之间。"
                        v-model="form.title"
                ></el-input>
            </el-form-item>
            <el-form-item prop="category">
                <el-select
                        class="select c-select"
                        v-model="form.category"
                        placeholder="请选择一个分类名"
                        popper-class="c-popper"
                        :filter-method="fetchCategory"
                >
                    <el-option
                            v-for="(category, index) in categories"
                            :key="index"
                            :label="category.name"
                            :value="category.id"
                    ></el-option>
                </el-select>
            </el-form-item>
            <el-form-item class="tags">
                <el-tag
                        class="tag"
                        v-for="(tag,key) in form.dynamicTags"
                        :key="key"
                        :closable="true"
                        :close-transition="true"
                        @close="closeTag(tag)"
                >{{ tag }}
                </el-tag>
                <el-input
                        v-if="iptVisible"
                        v-model="iptValue"
                        ref="saveTagIpt"
                        placeholder="标签名不能重复，且数量不能大于5个。"
                        @keyup.enter.native="confirmIpt"
                        @blur="confirmIpt"
                ></el-input>
                <el-button
                        v-else
                        class="btn-default"
                        @click="showIpt"
                >添加标签
                </el-button>
            </el-form-item>
            <el-form-item prop="body">
                <quill-editor
                        class="c-quill"
                        ref="quill"
                        v-model="form.body"
                        :options="editorOptions"
                ></quill-editor>
            </el-form-item>
            <el-form-item>
                <el-button
                        v-if="isEditPage"
                        class="btn-pub publish"
                        @click="submitForm"
                        :loading="isLoading"
                >修改作品
                </el-button>
                <el-button
                        v-else
                        class="btn-pub publish"
                        @click="submitForm"
                        :loading="isLoading"
                >发表作品
                </el-button>
            </el-form-item>
        </el-form>
    </div>
</template>

<script>
    import {quillEditor} from 'vue-quill-editor'
    export default {
        name: 'poemForm',
        components: {
            quillEditor
        },
        props: {
            // 默认表单数据,JSON字符串
            poem: {
                type: String,
                required: false,
                default: null
            }
        },
        data() {
            return {
                isEditPage: false,// 是否为编辑页面
                form: {
                    title: '', // 标题
                    category: '', // 分类ID
                    dynamicTags: [], // 标签
                    body: '' // 诗文内容
                },
                categories: [], // 分类列表
                iptVisible: false,
                iptValue: '',
                editorOptions: { // quill 编辑器配置
                    theme: 'bubble',
                    placeholder: '诗文内容，支持Markdown语法。'
                },
                isLoading: false,  // 按钮是否在加载
                dialogVisible: false,
                rules: { // 表单验证规则
                    title: [
                        {
                            required: true,
                            message: '标题不能为空。',
                            trigger: 'blur'
                        },
                        {
                            min: 1,
                            max: 50,
                            message: '标题必须介于1-50个字符之间。',
                            trigger: 'blur'
                        }
                    ],
                    category: [
                        {
                            required: true,
                            type: 'number',
                            message: '分类不能为空。',
                            trigger: 'change'
                        }
                    ],
                    body: [
                        {
                            required: true,
                            message: '内容不能为空。',
                            trigger: 'blur'
                        }
                    ]
                }
            }
        },
        computed: {
            localEditForm() {
                return JSON.parse(this.poem)
            }
        },
        created() {
            // 若是编辑页面,则填充默认表单
            this.checkoutPage()
        },
        methods: {
            // 远程获取分类列表
            fetchCategory(queryStr = '') {
                axios.get(`category/search?query=${queryStr}`).then(response => {
                    this.categories = response.data
                })
            },
            // 去除标签
            closeTag(tag) {
                this.form.dynamicTags.splice(this.form.dynamicTags.indexOf(tag), 1)
            },
            // 显示标签输入框
            showIpt() {
                this.iptVisible = true
                this.$nextTick(() => {
                    this.$refs['saveTagIpt'].$refs.input.focus()
                })
            },
            // 检验标签的合法性
            confirmIpt() {
                if (this.iptValue) {
                    // 若标签名重复
                    if (this.form.dynamicTags.indexOf(this.iptValue) > -1) {
                        this.$message({
                            message: '旅行者，标签名不能重复。',
                            type: 'error',
                            customClass: 'c-msg'
                        })
                    } else if (this.form.dynamicTags.length >= 5) {
                        // 若标签数大于5
                        this.$message({
                            message: '旅行者，诗文最大标签数不能大于5。',
                            type: 'error',
                            customClass: 'c-msg'
                        })
                    } else {
                        // 若未重复，则储存该标签
                        this.form.dynamicTags.push(this.iptValue)
                    }
                }
                this.iptVisible = false
                this.iptValue = ''
            },
            // 提交表单
            submitForm() {
                this.$refs['form'].validate(valid => {
                            if (valid) {
                                this.isLoading = true
                                if (this.isEditPage) {
                                    const id = location.pathname.match(/(\d+)/)[1]
                                    axios.put(`poem/${id}`, this.form).then(response => {
                                        this.isLoading = false
                                        // 诗文更新成功
                                        if (response.data.updated) {
                                            this.$message({
                                                message: '诗文更新成功。',
                                                type: 'success',
                                                customClass: 'c-msg'
                                            })
                                        } else {
                                            this.$message({
                                                message: '诗文更新失败。',
                                                type: 'error',
                                                customClass: 'c-msg'
                                            })
                                        }
                                    }).catch(error => {
                                        this.isLoading = false
                                        this.$message({
                                            message: '旅行者，诗词小筑出了点状况，您可以稍后再来光顾，拜托啦/(ㄒoㄒ)/~~',
                                            type: 'error',
                                            customClass: 'c-msg',
                                            duration: 0,
                                            showClose: true
                                        })
                                        Promise.reject(error)
                                    })
                                } else {
                                    axios.post('poem', this.form).then(response => {
                                        this.isLoading = false
                                        // 诗文创建成功
                                        if (response.data.created) {
                                            this.$message({
                                                message: '诗文创建成功。',
                                                type: 'success',
                                                customClass: 'c-msg'
                                            })
                                            location.href = `http://www.dragonflyxd.com/admin/poem/${response.data.poem.id}`
                                        } else if (response.status === 422) {
                                            // 参数错误
                                            this.$message({
                                                message: response.statusText,
                                                type: 'error',
                                                customClass: 'c-msg'
                                            })
                                        } else {
                                            this.$message({
                                                message: response.data.error.message,
                                                type: 'error',
                                                customClass: 'c-msg'
                                            })
                                        }
                                    }).catch(error => {
                                        this.isLoading = false
                                        this.$message({
                                            message: '旅行者，诗词小筑出了点状况，您可以稍后再来光顾，拜托啦/(ㄒoㄒ)/~~',
                                            type: 'error',
                                            customClass: 'c-msg',
                                            duration: 0,
                                            showClose: true
                                        })
                                        Promise.reject(error)
                                    })
                                }
                            }
                            return false

                        }
                )
            },
            // 判断该页面是否为编辑页面
            checkoutPage() {
                if (location.pathname !== '/admin/poem/create') {
                    this.isEditPage = true
                    const matched = [
                        'title', 'dynamicTags', 'category', 'body'
                    ]
                    matched.forEach(item => {
                        if (item === 'category') {
                            axios.get('category/search').then(response => {
                                this.categories = response.data
                                this.categories.some(category => {
                                    if (category.id === this.localEditForm['category_id']) {
                                        this.form.category = category.id
                                        return true
                                    }
                                })
                            })
                        } else if (item === 'dynamicTags' && this.localEditForm['tags'].length > 0) {
                            this.localEditForm['tags'].forEach(item => {
                                this.form['dynamicTags'].push(item.name)
                            })
                        } else if (this.localEditForm[item]) {
                            this.form[item] = this.localEditForm[item]
                        }
                    })
                } else {
                    this.fetchCategory()
                }
            }

        }
    }
</script>

<style lang="stylus" scoped>
@import '../../../../stylus/common'

.df-poemForm
  fj(center)
  margin-top 50px
  .main
    width 66.6%
    .select, .publish
      width 100%
    .tags
      .tag
        margin-right 5px
 </style>
