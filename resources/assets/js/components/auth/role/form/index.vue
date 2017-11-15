<template lang="html">
    <div class="df-roleForm">
        <el-form class="c-form main" ref="form" :model="form" :rules="rules">
            <el-form-item prop="name" label="名称">
                <el-input v-model="form.name"></el-input>
            </el-form-item>
            <el-form-item>
                <div>Permissions</div>
                <el-transfer
                        v-model="form.perms"
                        filter-placeholder="请输入Permission name..."
                        filterable
                        :titles="['未选择','已选择']"
                        :props="{key: 'id', label: 'name'}"
                        :data="remotePerms"
                ></el-transfer>
            </el-form-item>
            <el-form-item prop="display_name" label="显示名">
                <el-input
                        v-model="form.display_name"
                        type="textarea"
                        :autosize="{ minRows: 2, maxRows: 4 }"
                ></el-input>
            </el-form-item>
            <el-form-item prop="description" label="简述">
                <el-input
                        v-model="form.description"
                        type="textarea"
                        :autosize="{ minRows: 2, maxRows: 4 }"
                ></el-input>
            </el-form-item>
            <el-form-item>
                <el-button
                        v-if="isEditPage"
                        class="btn-pub publish"
                        @click="submitForm"
                        :loading="isLoading"
                >修改
                </el-button>
                <el-button
                        v-else
                        class="btn-pub publish"
                        @click="submitForm"
                        :loading="isLoading"
                >提交
                </el-button>
            </el-form-item>
        </el-form>
    </div>
</template>

<script>
    export default {
        name: 'roleForm',
        props: {
            role: {}
        },
        data() {
            return {
                isEditPage: false,// 是否为编辑页面
                form: {
                    name: '', // 名称
                    display_name: '', // 显示名
                    description: '',  // 简述
                    perms: []   // permissions
                },
                remotePerms: [],
                isLoading: false,  // 按钮是否在加载
                dialogVisible: false,
                rules: { // 表单验证规则
                    name: [
                        {
                            required: true,
                            message: '名称不能为空。',
                            trigger: 'blur'
                        },
                        {
                            min: 1,
                            max: 50,
                            message: '名称必须介于1-50个字符之间。',
                            trigger: 'blur'
                        },
                        {
                            type: 'string',
                            pattern: /^[\u4E00-\u9FFFa-zA-Z0-9_-]{1,50}$/,
                            message: '格式不正确。',
                            trigger: 'blur'
                        },
                        {
                            validator: this.validateRoleName,
                            trigger: 'blur'
                        }
                    ],
                    display_name: [
                        {
                            type: 'string',
                            message: '显示名必须为字符串。',
                            trigger: 'blur'
                        },
                        {
                          max: 50,
                          message: '显示名至多为50个字符。',
                          trigger: 'blur'
                        }
                    ],
                    description: [
                        {
                            type: 'string',
                            message: '简述必须为字符串。',
                            trigger: 'blur'
                        },
                        {
                          max: 191,
                          message: '简述至多为191个字符。',
                          trigger: 'blur'
                        }
                    ]
                }
            }
        },
        created() {
            // 若是编辑页面,则填充默认表单
            this.checkoutPage()
        },
        methods: {
            // 验证role name是否已经存在
            validateRoleName(r, v, cb) {
              axios.post('auth/role', this.form).then(response => {
                  response.data.name ? cb(new Error('名称已经存在。')) : cb()
              })
            },
            // 远程获取Permission列表
            fetchPerms(queryStr = '') {
                axios.get(`auth/permission/search?query=${queryStr}`).then(response => {
                    this.remotePerms = response.data.data
                })
            },
            // 提交表单
            submitForm() {
                this.$refs['form'].validate(valid => {
                            if (valid) {
                                this.isLoading = true
                                this.form.is_submit = true
                                if (this.isEditPage) {
                                    const id = location.pathname.match(/(\d+)/)[1]
                                    axios.put(`auth/role/${id}`, this.form).then(response => {
                                        this.isLoading = false
                                        delete this.form.is_submit
                                        // Role更新成功
                                        if (response.data.updated) {
                                            this.$message({
                                                message: 'Role更新成功。',
                                                type: 'success',
                                                customClass: 'c-msg'
                                            })
                                        } else {
                                            this.$message({
                                                message: 'Role更新失败。',
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
                                    axios.post('auth/role', this.form).then(response => {
                                        this.isLoading = false
                                        // Role创建成功
                                        if (response.data.created) {
                                            this.$message({
                                                message: 'Role创建成功。',
                                                type: 'success',
                                                customClass: 'c-msg'
                                            })
                                            location.href = `http://www.dragonflyxd.com/admin/auth/role`
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
                if (location.pathname !== '/admin/auth/role/create') {
                    this.isEditPage = true
                    const matched = [
                        'name', 'perms', 'display_name', 'description'
                    ]
                    const role = JSON.parse(this.role)
                    matched.forEach(item => {
                        if (item === 'perms') {
                            this.form[item] = role[item].map(perm => {
                                return perm.id
                            })
                        }
                        else if(role[item]) {
                            this.form[item] = role[item]
                        }
                    })
                }
                // 获取Permissions
                this.fetchPerms()
            }

        }
    }
</script>

<style lang="stylus" scoped>
@import '../../../../../stylus/common'

.df-roleForm
  fj(center)
  margin-top 50px
  .main
    width 66.6%
    .publish
      width 100%
    .tags
      .tag
        margin-right 5px
</style>
