<template lang="html">
    <div class="df-form">
        <el-form class="custom-form main" ref="form" :model="form" :rules="rules">
            <el-form-item prop="title">
                <el-input
                        placeholder="诗文标题，1-50个字符之间。"
                        v-model="form.title"
                ></el-input>
            </el-form-item>
            <el-form-item prop="category">
                <el-autocomplete
                        class="category"
                        placeholder="请选择一个诗文类型"
                        ref="category"
                        v-model="form.category"
                        popper-class="custom-popper"
                        :fetch-suggestions="fetchCategory"
                ></el-autocomplete>
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
                        class="show-ipt"
                        @click="showIpt"
                >添加标签
                </el-button>
            </el-form-item>
            <el-form-item prop="body">
                <quill-editor
                        class="custom-quill"
                        ref="quill"
                        v-model="form.body"
                        :options="editorOptions"
                ></quill-editor>
            </el-form-item>
            <el-form-item>
                <el-button
                        v-if="is_edit"
                        class="custom-btn"
                        @click="submitForm"
                        :loading="isLoading"
                        :disabled="isDisabled"
                >修改作品
                </el-button>
                <el-button
                        v-else
                        class="custom-btn"
                        @click="submitForm"
                        :loading="isLoading"
                        :disabled="isDisabled"
                >发表作品
                </el-button>
            </el-form-item>
        </el-form>
    </div>
</template>

<script>
    import {quillEditor} from 'vue-quill-editor'
    export default {
        name: 'DateForm',
        components: {
            quillEditor
        },
        props: {
            // 是否为编辑页面
            is_edit: {
                type: Boolean,
                required: false,
                default: false
            },
            // 默认表单数据,JSON字符串
            edit_form: {
                type: String,
                required: false,
                default: null
            }
        },
        data() {
            return {
                form: {
                    title: '', // 标题
                    category: '', // 分类名
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
                isDisabled: false, // 按钮是否禁用
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
                            message: '分类不能为空。',
                            trigger: 'change'
                        },
                        {
                            validator: this.checkCategory,
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
                return JSON.parse(this.edit_form)
            }
        },
        created() {
            // 若是编辑页面,则填充默认表单
            this.edit()
        },
        methods: {
            // 远程获取分类列表
            fetchCategory(queryStr = '', cb) {
                axios.get('category/search?query=' + queryStr).then(response => {
                    this.categories = response.data.data.map(item => {
                        item['value'] = item['name']
                        return item
                    })
                    cb && cb(this.categories)
                })
            },
            // 检验分类是否合法
            checkCategory(rule, value, cb) {
                this.categories.some(category => category['name'] === value)
                        ? cb()
                        : cb('旅行者，分类不能自定义，必须从下拉列表中选择。')
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
                            customClass: 'custom-msg'
                        })
                    } else if (this.form.dynamicTags.length >= 5) {
                        // 若标签数大于5
                        this.$message({
                            message: '旅行者，诗文最大标签数不能大于5。',
                            type: 'error',
                            customClass: 'custom-msg'
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
                        if (this.is_edit) {
                            const id = location.pathname.match(/(\d+)/)[1]
                            axios.put('poem/' + id, this.form).then(response => {
                                this.isLoading = false
                                // 诗文更新成功
                                if (response.data.updated) {
                                    this.$message({
                                        message: '诗文更新成功。',
                                        type: 'success',
                                        customClass: 'custom-msg'
                                    })
                                } else {
                                    this.$message({
                                        message: '诗文更新失败。',
                                        type: 'error',
                                        customClass: 'custom-msg'
                                    })
                                }
                            }).catch(error => {
                                this.isLoading = false
                                this.$message({
                                    message: '旅行者，诗词小筑出了点状况，您可以稍后再来光顾，拜托啦/(ㄒoㄒ)/~~',
                                    type: 'error',
                                    customClass: 'custom-msg',
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
                                    // 禁用按钮
                                    this.isDisabled = true
                                    this.$message({
                                        message: '诗文创建成功。',
                                        type: 'success',
                                        customClass: 'custom-msg'
                                    })
                                    // 重定向到诗文页
                                    setTimeout(() => {
                                        location.href = 'http://www.dragonflyxd.com/admin/poem'
                                    },3000)
                                } else if (response.status === 422) {
                                    // 参数错误
                                    this.$message({
                                        message: response.statusText,
                                        type: 'error',
                                        customClass: 'custom-msg'
                                    })
                                } else {
                                    this.$message({
                                        message: response.data.error.message,
                                        type: 'error',
                                        customClass: 'custom-msg'
                                    })
                                }
                            }).catch(error => {
                                this.isLoading = false
                                this.$message({
                                    message: '旅行者，诗词小筑出了点状况，您可以稍后再来光顾，拜托啦/(ㄒoㄒ)/~~',
                                    type: 'error',
                                    customClass: 'custom-msg',
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
            // 若为编辑页面,填充默认数据
            edit() {
                if (this.is_edit && this.localEditForm) {
                    // 远程获取分类
                    this.fetchCategory()
                    const matched = [
                        'title', 'dynamicTags', 'category', 'body'
                    ]
                    matched.forEach(item => {
                        if (this.localEditForm[item]) {
                            this.form[item] = this.localEditForm[item]
                        } else if (item === 'dynamicTags' && this.localEditForm['tags'].length > 0) {
                            // 获取标签名的集合
                            const tags = this.localEditForm['tags']
                            for (let index in tags) {
                                this.form[item].push(tags[index]['name'])
                            }
                        }
                    })
                }
            }

        }
    }
</script>

<style lang="stylus"> @import "index.styl"; </style>