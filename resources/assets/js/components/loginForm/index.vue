<template lang="html">
    <el-form class="login-form" ref="loginForm" :model="loginForm" :rules="loginRules">
        <h3 class="title">诗词小筑❤️后台登录</h3>
        <el-form-item prop="name">
            <el-input autoComplete="on" placeholder="用户名" v-model="loginForm.name">
                <template slot="prepend"><i class="fa fa-user"></i></template>
            </el-input>
        </el-form-item>
        <el-form-item prop="password">
            <el-input type="password" placeholder="密码" v-model="loginForm.password" @keyup.enter.native="handleLogin">
                <template slot="prepend"><i class="fa fa-lock"></i></template>
            </el-input>
        </el-form-item>
        <el-form-item>
            <el-button class="custom-btn" :loading="isLoading" @click="handleLogin">登录</el-button>
        </el-form-item>
        <div class="misc">
            <a class="custom-a" href="#">
                忘记密码？
            </a>
            <el-checkbox class="custom-checkbox" v-model="loginForm.isRemember">自动登录️</el-checkbox>
        </div>
    </el-form>
</template>

<script>
    export default {
        name: 'LoginForm',
        data() {
            return {
                loginForm: {
                    name: '',
                    password: '',
                    isRemember: false
                },
                loginRules: {
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
                this.$refs['loginForm'].validate(valid => {
                    if (valid) {
                        this.isLoading = true
                        axios.post('login', this.loginForm).then(response => {
                            this.isLoading = false
                            if (response.data.error) {
                                if (response.data.error.http_code === 401) {
                                    // 用户邮箱未验证
                                    this.$message({
                                        message: response.data.error.message,
                                        type: 'error',
                                        customClass: 'custom-msg'
                                    })
                                } else if (response.data.error.http_code === 422) {
                                    // 用户名或密码错误
                                    this.$message({
                                        message: response.data.error.message,
                                        type: 'error',
                                        customClass: 'custom-msg'
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
                                customClass: 'custom-msg',
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
        },
        mounted() {
            console.log(window.Laravel)
        }
    }
</script>

<style lang="stylus"> @import "index.styl"; </style>