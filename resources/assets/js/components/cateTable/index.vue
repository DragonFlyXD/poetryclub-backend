<template>
    <div class="df-table">
        <header class="header">
            <el-input
                    class="search custom-ipt"
                    placeholder="请输入分类名..."
                    icon="search"
                    v-model="query"
                    :on-icon-click="search"
                    @keyup.enter.native="search"
            ></el-input>
            <div class="actions">
                <el-button class="add" @click="createCate">
                    <i class="fa fa-plus"></i>
                </el-button>
                <el-button class="edit" @click="editCate">
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
                    label="分类名"
            ></el-table-column>
            <el-table-column
                    label="日期"
                    prop="created_at"
                    :formatter="dateFormatter"
            ></el-table-column>
            <el-table-column label="操作" class-name="actions">
                <template scope="scope">
                    <el-button class="custom-btn" @click="editCate(scope.row.id,scope.$index)">
                        <i class="fa fa-edit"></i>
                    </el-button>
                    <el-button
                            :loading="isDeleting"
                            class="custom-btn"
                            @click="deleteCate(scope.row.id,scope.$index)"
                    ><i class="fa fa-trash"></i>
                    </el-button>
                </template>
            </el-table-column>
        </el-table>
        <el-dialog
                custom-class="custom-dialog"
                :title="dialogTitle"
                :visible.sync="dialogFormVisible"
        >
            <el-form class="custom-form" :model="form" :rules="rules" ref="form">
                <el-form-item label="分类名" prop="name">
                    <el-input
                            v-model="form.name"
                            placeholder="分类名, 1-50个字符之间。"
                    ></el-input>
                </el-form-item>
            </el-form>
            <div slot="footer">
                <el-button class="custom-cancel" @click="toggleCateDialog">取消</el-button>
                <el-button class="custom-confirm" @click="submitForm">确定</el-button>
            </div>
        </el-dialog>
    </div>
</template>

<script>
    export default {
        name: 'CateTable',
        props: ['paginate'],
        data() {
            return {
                query: '',  // 查询关键词
                isDeleting: false,  // 是否正在删除状态
                localData: [],  // 表格数据
                multipleSelection: [],   // 多选框数据
                isMultipleDeleting: false, // 是否正在多选删除状态
                dialogFormVisible: false,   // 表单对话框的可视度
                dialogTitle: '',
                form: {
                    name: '',
                    id: null
                },
                isUpdate: false, // 是否为更新操作
                currentIndex: null, // 当前选择的表格行数
                rules: {
                    name: [
                        {
                            required: true,
                            message: '分类名不能为空。',
                            trigger: 'blur'
                        },
                        {
                            min: 1,
                            max: 50,
                            message: '分类名必须介于1-50个字符之间。',
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
                this.localData = paginate ? paginate.data : JSON.parse(this.paginate).data
            },
            // 格式化日期
            dateFormatter(r, c) {
                return new Date(r.created_at).toLocaleDateString()
            },
            // 查找分类
            search() {
                axios.get('category/search?query=' + this.query).then(response => {
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
                })
            },
            // 处理多选框数据
            handleSelectionChange(val) {
                this.multipleSelection = val
            },
            // 添加分类
            createCate() {
                this.isUpdate = false
                this.dialogTitle = '添加分类'
                this.form.name = ''
                this.toggleCateDialog()
            },
            // 编辑分类
            editCate(id, index) {
                this.isUpdate = true
                this.dialogTitle = '编辑分类'
                this.currentIndex = index
                if (typeof id === 'number') {
                    this.form.name = this.localData[this.currentIndex].name
                    this.toggleCateDialog()
                } else if (this.multipleSelection.length) {
                    this.form.name = this.multipleSelection[0].name
                    this.toggleCateDialog()
                } else {
                    this.$message({
                        message: '请选择要编辑的分类。',
                        type: 'warning',
                        customClass: 'custom-msg'
                    })
                }
            },
            submitForm() {
                this.$refs['form'].validate(valid => {
                    if (valid) {
                        // 若为更新分类操作
                        if (this.isUpdate) {
                            axios.put('category/' + this.form.id, this.form).then(response => {
                                this.toggleCateDialog()
                                // 表单验证
                                if (response.data.name) {
                                    this.$message({
                                        message: response.data.name.join(','),
                                        type: 'warning',
                                        customClass: 'custom-msg'
                                    })
                                } else if (response.data.updated) {
                                    // 更新成功
                                    this.localData[this.currentIndex].name = this.form.name
                                    this.$message({
                                        message: '更新成功。',
                                        type: 'success',
                                        customClass: 'custom-msg'
                                    })
                                } else {
                                    // 更新失败
                                    this.$message({
                                        message: '更新失败。',
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
                            // 若为添加分类操作
                        } else {
                            axios.post('category', this.form).then(response => {
                                this.toggleCateDialog()
                                // 表单验证
                                if (response.data.name) {
                                    this.$message({
                                        message: response.data.name.join(','),
                                        type: 'warning',
                                        customClass: 'custom-msg'
                                    })
                                } else if (response.data.created) {
                                    // 添加成功
                                    this.localData.push(response.data.data)
                                    this.$message({
                                        message: '添加成功。',
                                        type: 'success',
                                        customClass: 'custom-msg'
                                    })
                                } else {
                                    // 添加失败
                                    this.$message({
                                        message: '添加失败。',
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
                        }
                    }
                    return false
                })
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
            toggleCateDialog() {
                this.dialogFormVisible = !this.dialogFormVisible
            },
            refresh() {
                location.reload();
            }
        }
    }
</script>

<style lang="stylus"> @import "../poemTable/index.styl"; </style>