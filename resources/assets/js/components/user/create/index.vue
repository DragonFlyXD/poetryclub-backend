<template lang="html">
    <div class="df-createUser">
        <el-form class="c-form main" ref="form" :model="form" :rules="rules">
            <el-form-item label="用户名" prop="name">
                <el-input
                        placeholder="用户名和登录名"
                        v-model="form.name"
                ></el-input>
            </el-form-item>
            <el-form-item label="邮箱" prop="email">
                <el-input
                        placeholder="建议填写常用邮箱"
                        v-model="form.email"
                ></el-input>
            </el-form-item>
            <el-form-item label="密码" prop="password">
                <el-input
                        placeholder="密码至少6位"
                        type="password"
                        v-model="form.password"
                ></el-input>
            </el-form-item>
            <el-form-item label="确认密码" prop="password_confirmation">
                <el-input
                        placeholder="再次输入密码"
                        type="password"
                        v-model="form.password_confirmation"
                ></el-input>
            </el-form-item>
            <el-form-item>
                <div>Roles</div>
                <el-transfer
                        v-model="form.roles"
                        filter-placeholder="请输入Role name..."
                        filterable
                        :titles="['未选择','已选择']"
                        :props="{key: 'id', label: 'name'}"
                        :data="remoteRoles"
                ></el-transfer>
            </el-form-item>
            <el-form-item label="激活状态">
                <el-switch
                        v-model="form.is_active"
                        active-color="#42b983"
                ></el-switch>
            </el-form-item>
            <el-form-item>
                <el-button
                        class="btn-pub publish"
                        @click="submitForm"
                        :loading="isLoading"
                >添加用户
                </el-button>
            </el-form-item>
        </el-form>
    </div>
</template>

<script>
    export default {
        name: 'createUser',
        data() {
            return {
                form: {
                    name: '',   // 用户名
                    email: '',  // 邮箱
                    password: '',   // 密码
                    password_confirmation: '',  // 重复密码
                    is_active: true,    // 激活状态
                    roles: []  // Roles
                },
                remoteRoles: [],    // 远程获取Roles
                isLoading: false,  // 按钮是否在加载
                rules: { // 表单验证规则
                    name: [{
                        required: true,
                        message: '用户名不能为空。',
                        trigger: 'blur'
                    }, {
                        min: 2,
                        max: 20,
                        message: '用户名必须介于2-20个字符之间。',
                        trigger: 'blur'
                    }, {
                        type: 'string',
                        pattern: /^[\u4E00-\u9FFFa-zA-Z0-9_-]{4,20}$/,
                        message: '格式不正确。',
                        trigger: 'blur'
                    }, {
                        validator: this.validateUsername,
                        trigger: 'blur'
                    }],
                    email: [{
                        required: true,
                        message: '邮箱不能为空。',
                        trigger: 'blur'
                    }, {
                        max: 50,
                        message: '邮箱不能大于50个字符。',
                        trigger: 'blur'
                    }, {
                        type: 'string',
                        pattern: /^(\w-*\.*)+@(\w-?)+(\.\w{2,})+$/,
                        message: '格式不正确。',
                        trigger: 'blur'
                    }, {
                        validator: this.validateEmail,
                        trigger: 'blur'
                    }],
                    password: [{
                        required: true,
                        message: '密码不能为空。',
                        trigger: 'blur'
                    }, {
                        min: 6,
                        max: 20,
                        message: '密码必须介于6-20个字符之间。',
                        trigger: 'blur'
                    }, {
                        type: 'string',
                        pattern: /^[a-zA-Z0-9_-]{6,20}$/,
                        message: '格式不正确。',
                        trigger: 'blur'
                    }],
                    password_confirmation: [{
                        validator: this.validatePassword,
                        trigger: 'blur'
                    }]
                }
            }
        },
        created() {
            this.fetchRoles()
        },
        methods: {
            // 验证用户名是否存在
            validateUsername(r, v, cb) {
                axios.post('user/register', this.form).then(response => {
                    response.data.name ? cb(new Error('用户名已经存在。')) : cb()
                })
            },
            // 验证邮箱是否存在
            validateEmail(r, v, cb) {
                axios.post('user/register', this.form).then(response => {
                    response.data.email ? cb(new Error('邮箱已经存在。')) : cb()
                })
            },
            // 验证密码
            validatePassword(rule, value, cb) {
                if (value === '') {
                    cb(new Error('请再次输入密码。'))
                } else if (value !== this.form.password) {
                    cb(new Error('两次输入密码不一致。'))
                } else {
                    cb()
                }
            },
            // 远程获取Roles列表
            fetchRoles(queryStr = '') {
                axios.get(`auth/role/search?query=${queryStr}`).then(response => {
                    this.remoteRoles = response.data.data
                })
            },
            // 提交表单
            submitForm() {
                this.$refs['form'].validate(valid => {
                            if (valid) {
                                this.isLoading = true
                                this.form.protocol = this.form.is_submit = true
                                axios.post('user/register', this.form).then(response => {
                                    this.isLoading = false
                                    delete this.form.is_submit
                                    // 用户创建成功
                                    if (response.data.registered) {
                                        this.$message({
                                            message: '用户创建成功。',
                                            type: 'success',
                                            customClass: 'c-msg'
                                        })
                                        location.href = `http://www.dragonflyxd.com/admin/user/${this.form.name}`
                                    } else {
                                        this.$message({
                                            message: '用户创建失败。',
                                            type: 'error',
                                            customClass: 'c-msg'
                                        })
                                    }
                                }).catch(error => {
                                    this.isLoading = false
                                    delete this.form.is_submit
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
                            return false
                        }
                )
            }
        }
    }
</script>

<style lang="stylus" scoped>
@import '../../../../stylus/common'

.df-createUser
  fj(center)
  margin-top 50px
  .main
    width 66.6%
    .publish
      width 100%
    .button-group
      padding-top 10px
</style>
