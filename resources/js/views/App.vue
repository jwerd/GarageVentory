<template>
    <div>
        <nav class="navbar navbar-dark bg-dark navbar-laravel" style="background-color: #e3f2fd;">
            <router-link :to="{name: 'dashboard'}" class="navbar-brand">GarageVentory</router-link>

               <router-link :to="{name: 'itemCreate'}" v-show="this.isLoggedIn" class="btn btn-primary">New Product</router-link>
        </nav>
        <main class="py-4">
            <router-view></router-view>
        </main>
    </div>
</template>
<script>
    export default {
        data(){
            return {
                isLoggedIn : null,
                name : null
            }
        },
        mounted(){
            this.isLoggedIn = localStorage.getItem('jwt')
            this.name = localStorage.getItem('user')
        }
    }
</script>