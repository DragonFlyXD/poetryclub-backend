<template lang="html">
    <div class="df-editUser">
        <el-form class="c-form main" ref="form" :model="form" :rules="rules">
            <el-form-item label="昵称" prop="nickname">
                <el-input
                        placeholder="行不更名坐不改姓"
                        v-model="form.nickname"
                ></el-input>
            </el-form-item>
            <el-form-item label="性别" prop="gender">
                <el-radio-group v-model="form.gender" fill="#42b983" text-color="rgba(66,185,131,.8)">
                    <el-radio :label="1">男</el-radio>
                    <el-radio :label="2">女</el-radio>
                </el-radio-group>
            </el-form-item>
            <el-form-item label="生日" prop="birthday">
                <el-date-picker
                        v-model="form.birthday"
                        type="date"
                        placeholder="生日格式：1996-07-21"
                        :editable="false"
                        :picker-options="pickerOptions"
                ></el-date-picker>
            </el-form-item>
            <el-form-item label="私人语录" prop="signature">
                <el-input
                        type="textarea"
                        v-model="form.signature"
                        placeholder="私人语录即一句话显逼格，最长为140个字符"
                        :rows="2"
                ></el-input>
            </el-form-item>
            <el-form-item label="居住地" prop="location">
                <el-input
                        placeholder="旅行者，仙乡何处 (黑人问号)"
                        v-model="form.location"
                ></el-input>
            </el-form-item>
            <el-form-item label="工作" prop="occupation">
                <el-input
                        placeholder="说，你会干啥 (和善的眼神)"
                        v-model="form.occupation"
                ></el-input>
            </el-form-item>
            <el-form-item label="个人简介" prop="bio">
                <el-input
                        type="textarea"
                        v-model="form.bio"
                        placeholder="远方来的人儿，介绍下自己呗 (善意的笑容)"
                        :autosize="{minRows:4,maxRows:20}"
                ></el-input>
            </el-form-item>
            <el-form-item label="最钟意的诗人" prop="poet">
                <el-input
                        placeholder="重要的、不能忘记的、不想忘记的诗人。他(她)，是谁？"
                        v-model="form.poet"
                ></el-input>
            </el-form-item>
            <el-form-item class="button-group">
                <el-button
                        class="btn-pub publish"
                        @click="submitForm"
                        :loading="isLoading"
                >提交修改
                </el-button>
            </el-form-item>
        </el-form>
    </div>
</template>

<script>
    export default {
        name: 'editUser',
        props: {
            // 默认表单数据,JSON字符串
            user: {
                type: String,
                required: false,
                default: null
            }
        },
        data() {
            return {
                form: {
                    nickname: '',  // 昵称
                    gender: 0,  // 性别
                    birthday: '',  // 生日
                    signature: '',  // 个性签名
                    location: '',  // 居住地
                    occupation: '',  // 职位
                    bio: '',  // 个人简历
                    poet: ''  // 最爱之诗人
                },
                isLoading: false,  // 按钮是否在加载
                pickerOptions: {
                    // 禁用日期
                    disabledDate(time) {
                        return time.getTime() > Date.now()
                    }
                },
                rules: {
                    nickname: [
                        {
                            min: 2,
                            max: 20,
                            message: '昵称必须介于2-20个字符之间。',
                            trigger: 'blur'
                        },
                        {
                            type: 'string',
                            pattern: /^[\u4E00-\u9FFFa-zA-Z0-9_-]{2,20}$/,
                            message: '格式不正确。',
                            trigger: 'blur'
                        }
                    ],
                    signature: [{
                        max: 50,
                        message: '私人语录不能大于140个字符。',
                        trigger: 'blur'
                    }],
                    location: [{
                        max: 50,
                        message: '居住地不能大于50个字符。',
                        trigger: 'blur'
                    }],
                    occupation: [{
                        max: 50,
                        message: '职业不能大于50个字符。',
                        trigger: 'blur'
                    }],
                    bio: [{
                        max: 400,
                        message: '个人简介不能大于500个字符。',
                        trigger: 'blur'
                    }],
                    poet: [{
                        max: 50,
                        message: '最钟爱的诗文不能大于50个字符。',
                        trigger: 'blur'
                    }]
                }
            }
        },
        created() {
            this.getEditData()
        },
        methods: {
            // 获取待编辑的用户个人信息
            getEditData() {
                const user = JSON.parse(this.user)
                const matches = ['nickname', 'gender', 'birthday', 'signature', 'location', 'occupation', 'bio', 'poet']
                matches.forEach(item => {
                    if (item === 'gender' && user[item]) {
                        this.form[item] = user[item] === '男' ? 1 : 2
                    } else if (user[item]) {
                        this.form[item] = user[item]
                    }
                })
            },
            // 提交表单
            submitForm() {
                this.$refs['form'].validate(valid => {
                            if (valid) {
                                this.isLoading = true
                                this.form.birthday = new Date(this.form.birthday).toLocaleDateString()
                                axios.put(`user/${JSON.parse(this.user)['name']}`, this.form).then(response => {
                                    this.isLoading = false
                                    // 更新用户个人信息成功
                                    if (response.data.updated) {
                                        this.$message({
                                            message: '更新成功。',
                                            type: 'success',
                                            customClass: 'c-msg'
                                        })
                                    } else {
                                        this.$message({
                                            message: '更新失败。',
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
                            return false
                        }
                )
            }
        }
    }
</script>

<style lang="stylus" scoped>
@import '../../../../stylus/common'

.df-editUser
  fj(center)
  margin-top 50px
  .main
    width 50%
    .publish
      width 100%
    .button-group
      padding-top 10px
</style>
