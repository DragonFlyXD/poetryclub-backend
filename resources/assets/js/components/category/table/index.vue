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
                <el-button class="add" @click="createCategory">
                    <i class="fa fa-plus"></i>
                </el-button>
                <el-button class="edit" @click="editCategory">
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
                :data="this.localData.data"
                :default-sort="{prop: 'id'}"
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
                    prop="created_at"
            ></el-table-column>
            <el-table-column label="操作" class-name="actions">
                <template scope="scope">
                    <el-button class="btn-pub" @click="editCategory(scope.row.id)">
                        <i class="fa fa-edit"></i>
                    </el-button>
                    <el-button
                            class="btn-can"
                            @click="deleteCategory(scope.row.id,scope.$index)"
                    ><i class="fa fa-trash"></i>
                    </el-button>
                </template>
            </el-table-column>
        </el-table>
        <footer class="footer">
            <el-pagination
                    small
                    layout="prev,pager,next"
                    :current-page.sync="this.localData.current_page"
                    :total="this.localData.total"
                    @current-change="handleCurrentChange"
            ></el-pagination>
        </footer>
        <el-dialog
                class="c-dialog"
                :visible.sync="categoryDialogVisible"
                :title="isEdit ? '修改分类' : '添加分类'"
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
                    category: ''
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
            // 验证分类是否存在
            validateCategory(r, v, cb) {
                axios.post('category', this.form).then(response => {
                    response.data.data ? cb(new Error('分类已经存在。')) : cb()
                })
            },
            // 提交表单
            submitForm(){
                this.$refs['form'].validate(valid => {
                    if (valid) {
                        this.isLoading = true
                        axios.post('category', this.form).then(response => {
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
            },
            // 获取表格数据
            getLocalData(paginate = '') {
                this.localData = paginate ? paginate : JSON.parse(this.paginate)
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
            // 创建分类
            createCategory() {
                this.categoryDialogVisible = true
            },
            toggleCategoryDialog() {
                this.categoryDialogVisible = !this.categoryDialogVisible
            },
            // 编辑分类
            editCategory(id) {
                if (typeof id === 'number') {
                    location.href = `http://www.dragonflyxd.com/admin/category/${id}/edit`
                } else {
                    if (this.multipleSelection.length) {
                        const categoryId = this.multipleSelection[this.multipleSelection.length - 1].id
                        location.href = `http://www.dragonflyxd.com/admin/category/${categoryId}/edit`
                    } else {
                        this.$message({
                            message: '请选择要编辑的诗文。',
                            type: 'warning',
                            customClass: 'custom-msg'
                        })
                    }
                }
            },
            // 删除多选的分类
            deleteMultipleCategory(){
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
                        axios.delete('category/destroy', ids).then(response => {
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
            // 删除分类
            deleteCategory(id, index) {
                this.$confirm('此操作将永久删除该诗文,是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    confirmButtonClass: 'custom-confirm',
                    cancelButtonClass: 'custom-cancel',
                    type: 'warning'
                }).then(()=> {
                    axios.delete(`category/${id}`).then(response => {
                        // 若删除成功
                        if (response.data.deleted) {
                            // 删除表格数据里选中项
                            this.localData.data.splice(-index - 1, 1)
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

<style lang="stylus" scoped> @import '../../../../stylus/common.styl'; </style>
