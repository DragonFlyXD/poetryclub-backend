<template>
    <div class="df-table">
        <header class="header">
            <el-input
                    class="search custom-ipt"
                    placeholder="请输入用户名..."
                    icon="search"
                    v-model="query"
                    :on-icon-click="search"
                    @keyup.enter.native="search"
            ></el-input>
            <div class="actions">
                <el-button class="add" @click="createPoem">
                    <i class="fa fa-plus"></i>
                </el-button>
                <el-button class="edit" @click="editPoem">
                    <i class="fa fa-edit"></i>
                </el-button>
                <el-button
                        class="destroy"
                        :loading="isMultipleDeleting"
                        @click="deleteMultiplePoem"
                ><i class="fa fa-trash"></i>
                </el-button>
                <el-button class="refresh" @click="refresh">
                    <i class="fa fa-refresh"></i>
                </el-button>
            </div>
        </header>
        <el-table
                stripe
                class="main"
                ref="table"
                :data="this.localData"
                :default-sort="{prop: 'id'}"
                @selection-change="handleSelectionChange"
        >
            <el-table-column type="expand">
                <template scope="scope">
                    <el-form label-position="left" inline class="expand-table">
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
            <el-table-column
                    prop="name"
                    label="用户名"
            ></el-table-column>
            <el-table-column
                    width="250"
                    prop="email"
                    label="邮箱"
            ></el-table-column>
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
                    prop="created_at"
                    :formatter="dateFormatter"
            ></el-table-column>
            <el-table-column label="操作" class-name="actions">
                <template scope="scope">
                    <el-button class="custom-btn" @click="editPoem(scope.row.id)">
                        <i class="fa fa-edit"></i>
                    </el-button>
                    <el-button
                            :loading="isDeleting"
                            class="custom-btn"
                            @click="deletePoem(scope.row.id,scope.$index)"
                    ><i class="fa fa-trash"></i>
                    </el-button>
                </template>
            </el-table-column>
        </el-table>
    </div>
</template>

<script>
    export default {
        name: 'PoemTable',
        props: ['paginate'],
        data() {
            return {
                query: '',  // 查询关键词
                isDeleting: false,  // 是否正在删除状态
                localData: [],  // 表格数据
                multipleSelection: [],   // 多选框数据
                isMultipleDeleting: false, // 是否正在多选删除状态
            }
        },
        created() {
            this.getLocalData()
        },
        methods: {
            // 获取表格数据
            getLocalData(paginate = '') {
                this.localData = paginate ? paginate.data : JSON.parse(this.paginate).data
            },
            // 格式化日期
            dateFormatter(r, c) {
                return new Date(r.created_at).toLocaleDateString()
            },
            // 查找用户
            search() {
                axios.get('user/search?query=' + this.query).then(response => {
                    this.getLocalData(response.data)
                }).catch(error => {
                    this.$message({
                        message: '旅行者，诗词小筑出了点状况，您可以稍后再来光顾，拜托啦/(ㄒoㄒ)/~~',
                        type: 'error',
                        customClass: 'custom-msg',
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
            // 创建诗文
            createPoem() {
                location.href = 'http://www.dragonflyxd.com/admin/poem/create'
            },
            // 编辑诗文
            editPoem(id) {
                if (typeof id === 'number') {
                    location.href = 'http://www.dragonflyxd.com/admin/poem/' + id + "/edit"
                } else {
                    if (this.multipleSelection.length) {
                        const mId = this.multipleSelection[0].id
                        location.href = 'http://www.dragonflyxd.com/admin/poem/' + mId + "/edit"
                    } else {
                        this.$message({
                            message: '请选择要编辑的诗文。',
                            type: 'warning',
                            customClass: 'custom-msg'
                        })
                    }
                }
            },
            // 删除多选的诗文
            deleteMultiplePoem(){
                // 若有选择
                if (this.multipleSelection.length) {
                    this.$confirm('此操作将永久删除选中的诗文,是否继续?', '提示', {
                        confirmButtonText: '确定',
                        cancelButtonText: '取消',
                        confirmButtonClass: 'custom-confirm',
                        cancelButtonClass: 'custom-cancel',
                        type: 'warning'
                    }).then(()=> {
                        this.isMultipleDeleting = true
                        const ids = this.multipleSelection.map(item => {
                            return item.id
                        })
                        axios.delete('poem', ids).then(response => {
                            this.isMultipleDeleting = false
                            // 删除表格数据里选中项
                            ids.forEach(id => {
                                this.localData.forEach((item, index) => {
                                    if (item['id'] === id) {
                                        this.localData.splice(index, 1)
                                    }
                                })
                            })
                            // 若删除成功
                            if (response.data.deleted) {
                                this.$message({
                                    message: '删除成功。',
                                    type: 'success',
                                    customClass: 'custom-msg'
                                })
                            } else {
                                this.$message({
                                    message: '删除失败。',
                                    type: 'error',
                                    customClass: 'custom-msg'
                                })
                            }
                        }).catch(error => {
                            this.isMultipleDeleting = false
                            this.$message({
                                message: '旅行者，诗词小筑出了点状况，您可以稍后再来光顾，拜托啦/(ㄒoㄒ)/~~',
                                type: 'error',
                                customClass: 'custom-msg',
                                duration: 0,
                                showClose: true
                            })
                            Promise.reject(error)
                        })
                    })
                } else {
                    this.$message({
                        message: '请选择要删除的诗文。',
                        type: 'warning',
                        customClass: 'custom-msg'
                    })
                }
            },
            // 删除诗文
            deletePoem(id, index) {
                this.$confirm('此操作将永久删除该诗文,是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    confirmButtonClass: 'custom-confirm',
                    cancelButtonClass: 'custom-cancel',
                    type: 'warning'
                }).then(()=> {
                    this.isDeleting = true
                    axios.delete('poem/' + id).then(response => {
                        this.isDeleting = false
                        // 删除表格数据里选中项
                        this.localData.splice(-index - 1, 1)
                        // 若删除成功
                        if (response.data.deleted) {
                            this.$message({
                                message: '删除成功。',
                                type: 'success',
                                customClass: 'custom-msg'
                            })
                        } else {
                            this.$message({
                                message: '删除失败。',
                                type: 'error',
                                customClass: 'custom-msg'
                            })
                        }
                    }).catch(error => {
                        this.isDeleting = false
                        this.$message({
                            message: '旅行者，诗词小筑出了点状况，您可以稍后再来光顾，拜托啦/(ㄒoㄒ)/~~',
                            type: 'error',
                            customClass: 'custom-msg',
                            duration: 0,
                            showClose: true
                        })
                        Promise.reject(error)
                    })
                })
            },
            refresh() {
                location.reload();
            }
        }
    }
</script>

<style lang="stylus"> @import "../poemTable/index.styl"; </style>