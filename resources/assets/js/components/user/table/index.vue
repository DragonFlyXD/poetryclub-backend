<template>
    <div class="c-table">
        <header class="header">
            <el-input
                    class="search c-form"
                    placeholder="请输入用户昵称..."
                    icon="search"
                    v-model="query"
                    :on-icon-click="search"
                    @keyup.enter.native="search"
            ></el-input>
            <div class="actions">
                <el-button class="add" @click="createUser">
                    <i class="fa fa-plus"></i>
                </el-button>
                <el-button class="edit" @click="editUser">
                    <i class="fa fa-edit"></i>
                </el-button>
                <el-button class="refresh" @click="refresh">
                    <i class="fa fa-refresh"></i>
                </el-button>
            </div>
        </header>
        <el-table
                stripe
                class="main c-form"
                ref="ta®ble"
                :data="this.localData.data"
                @selection-change="handleSelectionChange"
        >
            <el-table-column type="expand">
                <template scope="scope">
                    <el-form class="horizontal">
                        <el-form-item label="作品数">
                            <span>{{ scope.row.works_count }}</span>
                        </el-form-item>
                        <el-form-item label="收藏数">
                            <span>{{ scope.row.favorites_count }}</span>
                        </el-form-item>
                        <el-form-item label="点赞数">
                            <span>{{ scope.row.likes_count }}</span>
                        </el-form-item>
                        <el-form-item label="评论数">
                            <span>{{ scope.row.comments_count }}</span>
                        </el-form-item>
                        <el-form-item label="分享数">
                            <span>{{ scope.row.shares_count }}</span>
                        </el-form-item>
                        <el-form-item label="粉丝数">
                            <span>{{ scope.row.followers_count }}</span>
                        </el-form-item>
                        <el-form-item label="关注数">
                            <span>{{ scope.row.followings_count }}</span>
                        </el-form-item>
                    </el-form>
                </template>
            </el-table-column>
            <el-table-column
                    type="selection"
            ></el-table-column>
            <el-table-column
                    width="75"
                    prop="id"
                    label="ID"
                    sortable
            ></el-table-column>
            <el-table-column label="昵称">
                <template scope="scope">
                    <a class="btn-default" :href="'/admin'+ scope.row.profileUrl">
                        {{ scope.row.nickname }}
                    </a>
                </template>
            </el-table-column>
            <el-table-column label="用户名">
                <template scope="scope">
                    <a class="btn-default" :href="'/admin'+ scope.row.profileUrl">
                        {{ scope.row.name }}
                    </a>
                </template>
            </el-table-column>
            <el-table-column label="Roles">
                <template scope="scope">
                    <template v-if="scope.row.roles.length > 0">
                        <el-tag
                                type="info"
                                v-for="(role, index) in scope.row.roles"
                                :key="index"
                        >{{ role.name }}
                        </el-tag>
                    </template>
                    <el-tag type="danger" v-else>NULL</el-tag>
                </template>
            </el-table-column>
            <el-table-column label="邮箱">
                <template scope="scope">
                    {{ scope.row.email ? scope.row.email : 'NULL' }}
                </template>
            </el-table-column>
            <el-table-column label="登录方式">
                <template scope="scope">
                    <template v-if="scope.row.social_type">
                        <template v-if="scope.row.social_type === 'weibo'">
                            <i class="fa fa-weibo"></i> 微博
                        </template>
                        <template v-else>
                            <i class="fa fa-github"></i> GitHub
                        </template>
                    </template>
                    <template v-else><i class="fa fa-envelope-o"></i> {{ 'Email' }}</template>
                </template>
            </el-table-column>
            <el-table-column label="激活状态">
                <template scope="scope">
                    <el-tag
                            :type="scope.row.is_active ? 'success' : 'danger' "
                    >{{ scope.row.is_active ? '已激活' : '未激活' }}
                    </el-tag>
                </template>
            </el-table-column>
            <el-table-column
                    label="破壳日"
                    prop="publish_time"
            ></el-table-column>
            <el-table-column label="操作" class-name="actions">
                <template scope="scope">
                    <el-button class="btn-pub" @click="editUser(scope.$index)">
                        <i class="fa fa-edit"></i>
                    </el-button>
                </template>
            </el-table-column>
        </el-table>
        <footer class="footer">
            <el-pagination
                    small
                    layout="prev,pager,next"
                    :current-page="this.localData.current_page"
                    :total="this.localData.total"
                    @current-change="handleCurrentChange"
            ></el-pagination>
        </footer>
    </div>
</template>

<script>
    export default {
        name: 'userTable',
        props: ['paginate'],
        data() {
            return {
                query: '',  // 查询关键词
                localData: [],  // 分页数据
                multipleSelection: [],   // 多选框数据
            }
        },
        created() {
            this.getLocalData()
        },
        methods: {
            // 获取表格数据
            getLocalData(paginate = '') {
                this.localData = paginate ? paginate : JSON.parse(this.paginate)
            },
            // 跳转到指定页码的页面
            handleCurrentChange(val) {
                location.href = `http://www.dragonflyxd.com/admin/user?page=${val}`
            },
            // 查找用户
            search() {
                axios.get(`user/search?query=${this.query}`).then(response => {
                    this.getLocalData(response.data)
                }).catch(error => {
                    this.$message({
                        message: '旅行者，诗词小筑出了点状况，您可以稍后再来光顾，拜托啦/(ㄒoㄒ)/~~',
                        type: 'error',
                        customClass: 'c-msg',
                        duration: 0,
                        showClose: true
                    })
                    Promise.reject(error)
                });
            },
            // 处理多选框数据
            handleSelectionChange(val) {
                this.multipleSelection = val
            },
            // 添加用户
            createUser() {
                location.href = 'http://www.dragonflyxd.com/admin/user/create'
            },
            // 编辑用户
            editUser(index) {
                if (typeof index === 'number') {
                    const username = this.localData.data[index].name
                    location.href = `http://www.dragonflyxd.com/admin/user/${username}/edit`
                } else {
                    const msLen = this.multipleSelection.length
                    if (msLen) {
                        const username = this.multipleSelection[msLen - 1].name
                        location.href = `http://www.dragonflyxd.com/admin/user/${username}/edit`
                    } else {
                        this.$message({
                            message: '请选择要编辑的用户。',
                            type: 'warning',
                            customClass: 'c-msg'
                        })
                    }
                }
            },
            refresh() {
                location.reload();
            }
        }
    }
</script>

<style lang="stylus" scoped>
@import '../../../../stylus/common.styl';

.el-tag
    margin-right 2px
</style>