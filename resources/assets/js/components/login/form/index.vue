<template lang="html">
    <el-form class="df-loginForm" ref="form" :model="form" :rules="rules">
        <h3 class="title">诗词小筑❤️后台登录</h3>
        <el-form-item prop="name">
            <el-input autoComplete="on" placeholder="用户名" v-model="form.name">
                <template slot="prepend"><i class="fa fa-user"></i></template>
            </el-input>
        </el-form-item>
        <el-form-item prop="password">
            <el-input type="password" placeholder="密码" v-model="form.password" @keyup.enter.native="handleLogin">
                <template slot="prepend"><i class="fa fa-lock"></i></template>
            </el-input>
        </el-form-item>
        <el-form-item>
            <el-button class="btn-pub publish" :loading="isLoading" @click="handleLogin">登录</el-button>
        </el-form-item>
        <div class="misc">
            <a class="forget" href="#">
                忘记密码？
            </a>
            <el-checkbox v-model="form.isRemember">自动登录️</el-checkbox>
        </div>
    </el-form>
</template>

<script>
    export default {
        name: 'loginForm',
        data() {
            return {
                form: {
                    name: '',
                    password: '',
                    isRemember: false
                },
                rules: {
                    name: [
                        {
                            required: true,
                            message: '用户名不能为空。',
                            trigger: 'blur'
                        },
                        {
                            min: 2,
                            max: 20,
                            message: '用户名必须介于2-20个字符之间。',
                            trigger: 'blur'
                        }
                    ],
                    password: [
                        {
                            required: true,
                            message: '密码不能为空。',
                            trigger: 'blur'
                        },
                        {
                            min: 6,
                            max: 20,
                            message: '密码必须介于6-20个字符之间。',
                            trigger: 'blur'
                        }
                    ]
                },
                isLoading: false,
                DialogVisible: false
            }
        },
        methods: {
            handleLogin() {
                this.$refs['form'].validate(valid => {
                    if (valid) {
                        this.isLoading = true
                        axios.post('login', this.form).then(response => {
                            this.isLoading = false
                            if (response.data.error) {
                                if (response.data.error.http_code === 401) {
                                    // 用户邮箱未验证
                                    this.$message({
                                        message: response.data.error.message,
                                        type: 'error',
                                        customClass: 'c-msg'
                                    })
                                } else if (response.data.error.http_code === 422) {
                                    // 用户名或密码错误
                                    this.$message({
                                        message: response.data.error.message,
                                        type: 'error',
                                        customClass: 'c-msg'
                                    })
                                }
                            } else {
                                if (response.data.login) {
                                    location.href = 'http://www.dragonflyxd.com/admin'
                                }
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
                        return false
                    }
                })
            }
        }
    }
</script>

<style lang="stylus">
@import '../../../../stylus/common'

.df-loginForm
  z-index 999
  wh(30vw, 50vh)
  .is-error input
    border-color Silver !important
  .el-form-item__error
    color Red
  .el-input-group__prepend
    padding-left 15px
    border-color Silver
    bc(transparent, White)
  .el-checkbox__inner:hover,.el-checkbox__inner:focus,.el-checkbox__inner:visited
    border-color Green !important
  .is-checked
    span
      border-color Green !important
      background-color Green !important
      transition all .3s ease
  input
    padding 20px 0
    border-left 0
    border-color Silver
    bc(transparent, Green)
    &:hover
      border-color Silver
    &:focus
      border-color Silver
  .title
    padding 20px 0
    font-size 1.5em
    text-align center
    color Green
  .publish
    width 100%
  .misc
    fj(space-between)
    .forget
      text-decoration none
    a,span
      color Green
</style>
