<template>
    <div>
        <template>
            <div  v-if="isOffline">
                <b-alert show dismissible variant="warning" style="margin-bottom:0px;">
                    <h6 class="alert-heading">Connection Interrupted</h6>
                    Your connection is unstable.  The application may not work properly at the moment.
                </b-alert>
            </div>
        </template>
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