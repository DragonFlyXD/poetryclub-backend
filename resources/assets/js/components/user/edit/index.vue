<template lang="html">
    <div class="df-editUser">
        <el-tabs class="main-wrapper" type="border-card">
            <el-tab-pane label="Auth">
                <el-form class="c-form main" :model="authForm">
                    <el-form-item>
                        <div>Roles</div>
                        <el-transfer
                                v-model="authForm.roles"
                                filter-placeholder="请输入Role name..."
                                filterable
                                :titles="['未选择','已选择']"
                                :props="{key: 'id', label: 'name'}"
                                :data="remoteRoles"
                        ></el-transfer>
                    </el-form-item>
                    <el-form-item label="激活状态">
                        <el-switch
                                v-model="authForm.is_active"
                                active-color="#42b983"
                        ></el-switch>
                    </el-form-item>
                    <el-form-item>
                        <el-button
                                class="btn-pub publish"
                                @click="submitAuthForm"
                                :loading="isAuthBtnLoading"
                        >提交修改
                        </el-button>
                    </el-form-item>
                </el-form>
            </el-tab-pane>
            <el-tab-pane label="Profile">
                <el-form class="c-form main" ref="profileForm" :model="profileForm" :rules="profileRules">
                    <el-form-item label="昵称" prop="nickname">
                        <el-input
                                placeholder="行不更名坐不改姓"
                                v-model="profileForm.nickname"
                        ></el-input>
                    </el-form-item>
                    <el-form-item label="性别" prop="gender">
                        <el-radio-group v-model="profileForm.gender" fill="#42b983" text-color="rgba(66,185,131,.8)">
                            <el-radio :label="1">男</el-radio>
                            <el-radio :label="2">女</el-radio>
                        </el-radio-group>
                    </el-form-item>
                    <el-form-item label="生日" prop="birthday">
                        <el-date-picker
                                v-model="profileForm.birthday"
                                type="date"
                                placeholder="生日格式：1996-07-21"
                                :editable="false"
                                :picker-options="pickerOptions"
                        ></el-date-picker>
                    </el-form-item>
                    <el-form-item label="私人语录" prop="signature">
                        <el-input
                                type="textarea"
                                v-model="profileForm.signature"
                                placeholder="私人语录即一句话显逼格，最长为140个字符"
                                :rows="2"
                        ></el-input>
                    </el-form-item>
                    <el-form-item label="居住地" prop="location">
                        <el-input
                                placeholder="旅行者，仙乡何处 (黑人问号)"
                                v-model="profileForm.location"
                        ></el-input>
                    </el-form-item>
                    <el-form-item label="工作" prop="occupation">
                        <el-input
                                placeholder="说，你会干啥 (和善的眼神)"
                                v-model="profileForm.occupation"
                        ></el-input>
                    </el-form-item>
                    <el-form-item label="个人简介" prop="bio">
                        <el-input
                                type="textarea"
                                v-model="profileForm.bio"
                                placeholder="远方来的人儿，介绍下自己呗 (善意的笑容)"
                                :autosize="{minRows:4,maxRows:20}"
                        ></el-input>
                    </el-form-item>
                    <el-form-item label="最钟意的诗人" prop="poet">
                        <el-input
                                placeholder="重要的、不能忘记的、不想忘记的诗人。他(她)，是谁？"
                                v-model="profileForm.poet"
                        ></el-input>
                    </el-form-item>
                    <el-form-item>
                        <el-button
                                class="btn-pub publish"
                                @click="submitProfileForm"
                                :loading="isProfileBtnLoading"
                        >提交修改
                        </el-button>
                    </el-form-item>
                </el-form>
            </el-tab-pane>
        </el-tabs>
    </div>
</template>

<script>
    export default {
        name: 'editUser',
        props: {
            user: {}
        },
        data() {
            return {
                authForm: {
                    roles: [],   // roles
                    is_active: false,   // 激活状态
                },
                profileForm: {
                    nickname: '',  // 昵称
                    gender: 0,  // 性别
                    birthday: '',  // 生日
                    signature: '',  // 个性签名
                    location: '',  // 居住地
                    occupation: '',  // 职位
                    bio: '',  // 个人简历
                    poet: ''  // 最爱之诗人
                },
                remoteRoles: [],    // 远程获取Roles
                isAuthBtnLoading: false,
                isProfileBtnLoading: false,
                pickerOptions: {
                    // 禁用日期
                    disabledDate(time) {
                        return time.getTime() > Date.now()
                    }
                },
                profileRules: {
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
            // 获取待编辑的Auth信息与Profile信息
            getEditData() {
                const user = JSON.parse(this.user)
                // Auth
                this.fetchRoles()
                let matches = ['roles', 'is_active']
                matches.forEach(item => {
                    if (item === 'roles' && user['roles']) {
                        this.authForm.roles = user.roles.map(item => {
                            return item.id
                        })
                    } else if (user[item]) {
                        this.authForm[item] = !!user[item]
                    }
                })

                // Profile
                matches = ['nickname', 'gender', 'birthday', 'signature', 'location', 'occupation', 'bio', 'poet']
                matches.forEach(item => {
                    if (item === 'gender' && user[item]) {
                        this.profileForm[item] = user[item] === '男' ? 1 : 2
                    } else if (user[item]) {
                        this.profileForm[item] = user[item]
                    }
                })
            },
            // 远程获取Permission列表
            fetchRoles(queryStr = '') {
                axios.get(`auth/role/search?query=${queryStr}`).then(response => {
                    this.remoteRoles = response.data.data
                })
            },
            submitAuthForm() {
                this.isAuthBtnLoading = true
                const name = JSON.parse(this.user)['name']
                axios.put(`user/${name}/auth`, this.authForm).then(response => {
                    this.isAuthBtnLoading = false
                    // 更新Auth信息成功
                    if (response.data.updated) {
                        this.$message({
                            message: '更新Auth信息成功。',
                            type: 'success',
                            customClass: 'c-msg'
                        })
                    } else {
                        this.$message({
                            message: '更新Auth信息失败。',
                            type: 'error',
                            customClass: 'c-msg'
                        })
                    }
                }).catch(error => {
                    this.isAuthBtnLoading = false
                    this.$message({
                        message: '旅行者，诗词小筑出了点状况，您可以稍后再来光顾，拜托啦/(ㄒoㄒ)/~~',
                        type: 'error',
                        customClass: 'c-msg',
                        duration: 0,
                        showClose: true
                    })
                    Promise.reject(error)
                })
            },
            // 提交Profile表单
            submitProfileForm() {
                this.$refs['profileForm'].validate(valid => {
                            if (valid) {
                                this.isProfileBtnLoading = true
                                const name = JSON.parse(this.user)['name']
                                // 格式化用户生日
                                if (this.profileForm.birthday) {
                                    this.profileForm.birthday = new Date(this.profileForm.birthday).toLocaleDateString()
                                }

                                axios.put(`user/${name}`, this.profileForm).then(response => {
                                    this.isProfileBtnLoading = false
                                    // 更新Profile信息成功
                                    if (response.data.updated) {
                                        this.$message({
                                            message: '更新Profile信息成功。',
                                            type: 'success',
                                            customClass: 'c-msg'
                                        })
                                    } else {
                                        this.$message({
                                            message: '更新Profile信息失败。',
                                            type: 'error',
                                            customClass: 'c-msg'
                                        })
                                    }
                                }).catch(error => {
                                this.isProfileBtnLoading = false
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

<style lang="stylus">
@import '../../../../stylus/common'

.df-editUser
  fj(center)
  margin-top 50px
  .main-wrapper
    width 70%
    .el-tabs__item.is-active
        color Green
    .main
      .publish
        width 100%
      .button-group
        padding-top 10px
</style>
