import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

import routers from './router'

const router = new VueRouter({
    routes: routers
})

new Vue({
    router,
    template: `
        <div id="app">
            <div class="ui head menu">
                <span class="logo">
                    <img src="${pubPath}/views/static/img/logo.png" alt="瑞星">
                </span>
                <h1>安全云管理后台</h1>
                 <div class="right menu">
                    <span class="ui item">您好！欢迎登录</span>
                    <a class="ui item logout" @click="exit">安全退出</a>
                </div>
            </div>
            <div class="warp">
                <div class="ui tabular menu warp-nav">
                    <router-link to="Home" active-class="active" class="item">首页</router-link>
                    <router-link to="Mange" active-class="active" class="item">组织管理</router-link>
                </div>
                <div class="column thirteen wide warp-content">
                    <router-view></router-view>
                </div>
            </div>
        </div>
    `,
    methods: {
        exit: function() {
            location.href = '/index/loginOut'
        }
    }

}).$mount('#app')
