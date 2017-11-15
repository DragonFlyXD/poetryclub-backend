<template lang="html">
    <el-menu class="df-nav" :collapse="isCollapse">
        <el-menu-item class="collapse" index="admin" @click="collapse">
            <i class="fa fa-navicon"></i>
        </el-menu-item>
        <a href="/admin">
            <el-menu-item index="/admin">
                <i class="fa fa-dashboard"></i>
                <span slot="title">Dashboard</span>
            </el-menu-item>
        </a>
        <a href="/admin/poem">
            <el-menu-item index="/admin/poem">
                <i class="fa fa-book"></i>
                <span slot="title">诗文管理</span>
            </el-menu-item>
        </a>
        <a href="/admin/appreciation">
            <el-menu-item index="/admin/appreciation">
                <i class="fa fa-pencil-square-o"></i>
                <span slot="title">品鉴管理</span>
            </el-menu-item>
        </a>
        <a href="/admin/category">
            <el-menu-item index="/admin/category">
                <i class="fa fa-cubes"></i>
                <span slot="title">分类管理</span>
            </el-menu-item>
        </a>
        <a href="/admin/user">
            <el-menu-item index="/admin/user">
                <i class="fa fa-users"></i>
                <span slot="title">用户管理</span>
            </el-menu-item>
        </a>
        <el-submenu index="/admin/auth">
            <template slot="title">
                <i class="fa fa-cogs"></i>
                <span slot="title">权限管理</span>
            </template>
            <a href="/admin/auth/role">
                <el-menu-item index="/admin/auth/role">
                    <i class="fa fa-user-secret"></i>
                    <span slot="title">Role</span>
                </el-menu-item>
            </a>
            <a href="/admin/auth/permission">
                <el-menu-item index="/admin/auth/permission">
                    <i class="fa fa-ban"></i>
                    <span slot="title">Permission</span>
                </el-menu-item>
            </a>
        </el-submenu>
    </el-menu>
</template>

<script>
    export default {
        name: 'nav',
        data() {
            return {
                isCollapse: false
            }
        },
        methods: {
            collapse() {
                this.isCollapse = !this.isCollapse
            }
        }
    }
</script>

<style lang="stylus">
@import '../../../stylus/common'

.df-nav
  min-height 100%
  border-radius 0
  border-right 1px solid Extra-Light-Grey
  background-color White !important
  .collapse
    text-align center
  li
    &:hover,&:focus
      background-color Extra-Light-Grey
  a
    text-decoration none
    .is-active
      color Green
  i
    margin-right 5px
    color Green

.df-nav:not(.el-menu--collapse)
  min-width 200px
</style>
