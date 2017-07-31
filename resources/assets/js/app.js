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

import Dot from './components/dot'  // 粒子动画
import LoginForm from './components/loginForm'  // 登录表单
import PoemTable from './components/poemTable'  // 表格
import CateTable from './components/cateTable'
import UserTable from './components/userTable'
import DataForm from './components/dataForm'    // 表单
import CountTo from './components/countTo'  // 自动增长

new Vue({
    el: '#app',
    components: {
        Dot,
        LoginForm,
        PoemTable,
        CateTable,
        UserTable,
        DataForm,
        CountTo
    }
})