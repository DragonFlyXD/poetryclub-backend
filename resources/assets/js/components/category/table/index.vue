<template>
    <div class="c-table">
        <header class="header">
            <el-input
                    class="search c-form"
                    placeholder="请输入分类名称..."
                    icon="search"
                    v-model="query"
                    :on-icon-click="search"
                    @keyup.enter.native="search"
            ></el-input>
            <div class="actions">
                <el-button class="add" @click="toggleCategoryDialog">
                    <i class="fa fa-plus"></i>
                </el-button>
                <el-button class="edit" @click="toggleEditCategoryDialog">
                    <i class="fa fa-edit"></i>
                </el-button>
                <el-button
                        class="destroy"
                        :loading="isMultipleDeleting"
                        @click="deleteMultipleCategory"
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
                :data="localData.data"
                @selection-change="handleSelectionChange"
        >
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
                    label="标题"
            ></el-table-column>
            <el-table-column label="软删除">
                <template scope="scope">
                    {{ scope.row.deleted_at ? scope.row.deleted_at : 'NULL' }}
                </template>
            </el-table-column>
            <el-table-column
                    label="日期"
                    prop="publish_time"
            ></el-table-column>
            <el-table-column label="操作" class-name="actions">
                <template scope="scope">
                    <template v-if="scope.row.deleted_at">
                        <el-button class="btn-pub" @click="restore(scope.row.id,scope.$index)">
                            <i class="fa fa-reply"></i>
                        </el-button>
                    </template>
                    <template v-else>
                        <el-button class="btn-pub" @click="toggleEditCategoryDialog(scope.row.id,scope.$index)">
                            <i class="fa fa-edit"></i>
                        </el-button>
                        <el-button
                                class="btn-can"
                                @click="deleteCategory(scope.row.id,scope.$index)"
                        ><i class="fa fa-trash"></i>
                        </el-button>
                    </template>
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
        <el-dialog
                class="c-dialog"
                :visible.sync="categoryDialogVisible"
                :title="isEdit ? '修改分类' : '添加分类'"
                :before-close="handleClose"
        >
            <el-form class="c-form" ref="form" :model="form" :rules="rules">
                <el-form-item prop="category">
                    <el-input v-model="form.category" @keyup.enter.native="submitForm"></el-input>
                </el-form-item>
                <el-form-item>
                    <el-button class="btn btn-can" @click="toggleCategoryDialog">取消</el-button>
                    <el-button class="btn-pub publish" :loading="isLoading" @click="submitForm">确定</el-button>
                </el-form-item>
            </el-form>
        </el-dialog>
    </div>
</template>

<script>
    export default {
        name: 'categoryTable',
        props: ['paginate'],
        data() {
            return {
                query: '',  // 查询关键词
                localData: [],  // 分页数据
                multipleSelection: [],   // 多选框数据
                isMultipleDeleting: false, // 是否正在多选删除状态
                isEdit: false,  // 是否为编辑分类
                categoryDialogVisible: false,    // 分类对话框可视度
                isLoading: false,
                form: {
                    category: '',
                    id: 0,
                    is_submit: false
                },
                rules: {
                    category: [
                        {
                            required: true,
                            message: '分类不能为空。',
                            trigger: 'blur'
                        },
                        {
                            min: 1,
                            max: 50,
                            message: '分类必须介于1-50个字符之间。',
                            trigger: 'blur'
                        },
                        {
                            validator: this.validateCategory,
                            trigger: 'blur'
                        }
                    ]
                }

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
            // 验证分类是否存在
            validateCategory(r, v, cb) {
                axios.post('category', {'name': this.form.category}).then(response => {
                    response.data.name ? cb(new Error('分类已经存在。')) : cb()
                })
            },
            // 跳转到指定页码的页面
            handleCurrentChange(val) {
                location.href = `http://www.dragonflyxd.com/admin/category?page=${val}`
            },
            // 查找诗文
            search() {
                axios.get(`category/search?query=${this.query}`).then(response => {
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
            // 对话框关闭前的回调函数
            handleClose(done) {
                this.isEdit = false
                this.form = {}
                done()
            },
            // 恢复被删除的分类
            restore(id, index) {
                axios.get(`category/${id}/restore`).then(response => {
                    if (response.data.restored) {
                        this.$message({
                            message: '被软删除的分类取回成功。',
                            type: 'success',
                            customClass: 'c-msg'
                        })
                        this.localData.data[index].deleted_at = null
                    } else {
                        this.$message({
                            message: '被软删除的分类取回失败。',
                            type: 'error',
                            customClass: 'c-msg'
                        })
                    }
                })
            },
            toggleCategoryDialog() {
                this.categoryDialogVisible = !this.categoryDialogVisible
            },
            toggleEditCategoryDialog(id, index) {
                const msLen = this.multipleSelection.length
                if (typeof id === 'number' || msLen) {
                    if (typeof id === 'number') {
                        this.form.category = this.localData.data[index]['name']
                        this.form.id = this.localData.data[index]['id']
                    } else {
                        this.form.category = this.multipleSelection[msLen - 1]['name']
                        this.form.id = this.multipleSelection[msLen - 1]['id']
                    }
                    this.isEdit = this.categoryDialogVisible = true
                } else {
                    this.$message({
                        message: '请选择要编辑的诗文。',
                        type: 'warning',
                        customClass: 'c-msg'
                    })
                }
            },
            // 提交表单
            submitForm(){
                this.$refs['form'].validate(valid => {
                    if (valid) {
                        this.isLoading = true
                        const data = {
                            'name': this.form.category,
                            'is_submit': true
                        }
                        if (this.isEdit) {
                            axios.put(`category/${this.form.id}`, data).then(response => {
                                this.isLoading = false
                                this.toggleCategoryDialog()
                                if (response.data.updated) {
                                    this.localData.data.forEach((item, index) => {
                                        if (item.id === this.form.id) {
                                            this.localData.data[index].name = this.form.category
                                        }
                                    })
                                    this.$message({
                                        message: '分类更新成功。',
                                        type: 'success',
                                        customClass: 'c-msg',
                                    })
                                } else {
                                    this.$message({
                                        message: '分类更新失败。',
                                        type: 'error',
                                        customClass: 'c-msg',
                                    })
                                }
                                this.isEdit = false
                                this.form = {}
                            }).catch(error => {
                                this.isLoading = false
                                this.toggleCategoryDialog()
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
                            axios.post('category', data).then(response => {
                                this.isLoading = false
                                if (response.data.created) {
                                    this.localData.data.splice(-1, 0, response.data.category)
                                    this.$message({
                                        message: '分类添加成功。',
                                        type: 'success',
                                        customClass: 'c-msg',
                                    })
                                } else {
                                    this.$message({
                                        message: '分类添加失败。',
                                        type: 'error',
                                        customClass: 'c-msg',
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
                    } else {
                        return false
                    }
                })
            },
            // 删除多选的分类
            deleteMultipleCategory(){
                // 若有选择
                if (this.multipleSelection.length) {
                    this.$confirm('此操作将软删除选中的分类,是否继续?', '提示', {
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
                        axios.post('category/destroy', ids).then(response => {
                            this.isMultipleDeleting = false
                            // 若删除成功
                            if (response.data.deleted) {
                                ids.forEach(id => {
                                    this.localData.data.forEach((item, index) => {
                                        if (item['id'] === id) {
                                            this.localData.data.deleted_at = '已删除'
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
            // 删除分类
            deleteCategory(id, index) {
                this.$confirm('此操作将软删除该分类,是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    confirmButtonClass: 'btn-pub',
                    cancelButtonClass: 'btn-can',
                    type: 'warning'
                }).then(()=> {
                    axios.delete(`category/${id}`).then(response => {
                        // 若删除成功
                        if (response.data.deleted) {
                            // 删除表格数据里选中项
                            this.localData.data[index].deleted_at = '已删除'
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
