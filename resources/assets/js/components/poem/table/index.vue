<template>
    <div class="c-table">
        <header class="header">
            <el-input
                    class="search c-form"
                    placeholder="请输入诗文标题..."
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
                class="main c-form"
                ref="table"
                :data="this.localData.data"
                @selection-change="handleSelectionChange"
        >
            <el-table-column type="expand">
                <template scope="scope">
                    <el-form>
                        <el-form-item label="标题">
                            <span>{{ scope.row.title }}</span>
                        </el-form-item>
                        <el-form-item label="作者">
                            <span>{{ scope.row.authorName }}</span>
                        </el-form-item>
                        <el-form-item label="朝代">
                            <span>{{ scope.row.dynasty }}</span>
                        </el-form-item>
                        <el-form-item>
                            <h3>内容</h3>
                            <div v-html="scope.row.body"></div>
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
            <el-table-column label="标题">
                <template scope="scope">
                    <a class="btn-default" :href="'/admin'+ scope.row.poemUrl">
                        {{ scope.row.title }}
                    </a>
                </template>
            </el-table-column>
            <el-table-column
                    prop="dynasty"
                    label="朝代"
            ></el-table-column>
            <el-table-column label="是否发布">
                <template scope="scope">
                    <el-tag
                            :type="scope.row.is_valid ? 'success' : 'danger' "
                    >{{ scope.row.is_valid ? '已发布' : '未发布' }}
                    </el-tag>
                </template>
            </el-table-column>
            <el-table-column label="是否原创">
                <template scope="scope">
                    <el-tag
                            :type="scope.row.is_original ? 'success' : 'danger' "
                    >{{ scope.row.is_original ? '原创' : '非原创' }}
                    </el-tag>
                </template>
            </el-table-column>
            <el-table-column label="是否关闭评论">
                <template scope="scope">
                    <el-tag
                            :type="scope.row.close_comment ? 'success' : 'danger' "
                    >{{ scope.row.close_comment ? '已关闭' : '未关闭' }}
                    </el-tag>
                </template>
            </el-table-column>
            <el-table-column label="是否隐藏">
                <template scope="scope">
                    <el-tag
                            :type="scope.row.is_hidden ? 'success' : 'danger' "
                    >{{ scope.row.is_hidden ? '已隐藏' : '未隐藏' }}
                    </el-tag>
                </template>
            </el-table-column>
            <el-table-column
                    label="日期"
                    prop="publish_time"
            ></el-table-column>
            <el-table-column label="操作" class-name="actions">
                <template scope="scope">
                    <el-button class="btn-pub" @click="editPoem(scope.row.id)">
                        <i class="fa fa-edit"></i>
                    </el-button>
                    <el-button
                            class="btn-can"
                            @click="deletePoem(scope.row.id,scope.$index)"
                    ><i class="fa fa-trash"></i>
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
        name: 'poemTable',
        props: ['paginate'],
        data() {
            return {
                query: '',  // 查询关键词
                localData: [],  // 分页数据
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
                this.localData = paginate ? paginate : JSON.parse(this.paginate)
            },
            // 跳转到指定页码的页面
            handleCurrentChange(val) {
                location.href = `http://www.dragonflyxd.com/admin/poem?page=${val}`
            },
            // 查找诗文
            search() {
                axios.get(`poem/search?query=${this.query}`).then(response => {
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
            // 创建诗文
            createPoem() {
                location.href = 'http://www.dragonflyxd.com/admin/poem/create'
            },
            // 编辑诗文
            editPoem(id) {
                if (typeof id === 'number') {
                    location.href = `http://www.dragonflyxd.com/admin/poem/${id}/edit`
                } else {
                    const msLen = this.multipleSelection.length
                    if (msLen) {
                        const poemId = this.multipleSelection[msLen - 1].id
                        location.href = `http://www.dragonflyxd.com/admin/poem/${poemId}/edit`
                    } else {
                        this.$message({
                            message: '请选择要编辑的诗文。',
                            type: 'warning',
                            customClass: 'c-msg'
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
                        confirmButtonClass: 'btn-pub',
                        cancelButtonClass: 'btn-can',
                        type: 'warning'
                    }).then(()=> {
                        this.isMultipleDeleting = true
                        const ids = this.multipleSelection.map(item => {
                            return item.id
                        })
                        axios.post('poem/destroy', ids).then(response => {
                            this.isMultipleDeleting = false
                            // 若删除成功
                            if (response.data.deleted) {
                                ids.forEach(id => {
                                    this.localData.data.forEach((item, index) => {
                                        if (item['id'] === id) {
                                            this.localData.data.splice(index, 1)
                                        }
                                    })
                                })
                                this.$message({
                                    message: '删除成功。',
                                    type: 'success',
                                    customClass: 'c-msg'
                                })
                            } else {
                                this.$message({
                                    message: '删除失败。',
                                    type: 'error',
                                    customClass: 'c-msg'
                                })
                            }
                        }).catch(error => {
                            this.isMultipleDeleting = false
                            this.$message({
                                message: '旅行者，诗词小筑出了点状况，您可以稍后再来光顾，拜托啦/(ㄒoㄒ)/~~',
                                type: 'error',
                                customClass: 'c-msg',
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
                        customClass: 'c-msg'
                    })
                }
            },
            // 删除诗文
            deletePoem(id, index) {
                this.$confirm('此操作将永久删除该诗文,是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    confirmButtonClass: 'btn-pub',
                    cancelButtonClass: 'btn-can',
                    type: 'warning'
                }).then(()=> {
                    axios.delete(`poem/${id}`).then(response => {
                        // 若删除成功
                        if (response.data.deleted) {
                            // 删除表格数据里选中项
                            this.localData.data.splice(index, 1)
                            this.$message({
                                message: '删除成功。',
                                type: 'success',
                                customClass: 'c-msg'
                            })
                        } else {
                            this.$message({
                                message: '删除失败。',
                                type: 'error',
                                customClass: 'c-msg'
                            })
                        }
                    }).catch(error => {
                        this.$message({
                            message: '旅行者，诗词小筑出了点状况，您可以稍后再来光顾，拜托啦/(ㄒoㄒ)/~~',
                            type: 'error',
                            customClass: 'c-msg',
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

<style lang="stylus" scoped> @import '../../../../stylus/common.styl'; </style>
