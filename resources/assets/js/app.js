/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap'

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import ElementUI from 'element-ui'
import 'element-ui/lib/theme-default/index.css'
import 'font-awesome/css/font-awesome.css'
Vue.use(ElementUI)

// 登录相关
import LoginForm from './components/login/form'

// 左侧导航栏
import AppNav from './components/nav'

// 诗文相关
import PoemTable from './components/poem/table'
import PoemForm from './components/poem/form'

// 品鉴相关
import AppreciationTable from './components/appreciation/table'
import AppreciationForm from './components/appreciation/form'

// 分类相关
import CategoryTable from './components/category/table'

// 用户相关
import UserTable from './components/user/table'
import CreateUser from './components/user/create'
import EditUser from './components/user/edit'

// 权限相关
import RoleTable from './components/auth/role/table'
import RoleForm from './components/auth/role/form'
import PermissionTable from './components/auth/permission/table'
import PermissionForm from './components/auth/permission/form'

// 杂项
import Dot from './components/misc/dot'  // 粒子动画
import CountTo from './components/misc/countTo'  // 自动增长

new Vue({
    el: '#app',
    components: {
        LoginForm,
        AppNav,
        PoemTable,
        PoemForm,
        AppreciationTable,
        AppreciationForm,
        CategoryTable,
        UserTable,
        CreateUser,
        EditUser,
        RoleTable,
        RoleForm,
        PermissionTable,
        PermissionForm,
        Dot,
        CountTo
    }
})
