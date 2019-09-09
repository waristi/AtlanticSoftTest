<template>
    <div>
        <div class="row justify-content-center content-login">
            <div class="col-md-4 card">
                
                <form class="container-fluid" v-on:submit.prevent="login()"> 
                    <h3><b>Login</b></h3>
                    <hr>
                    <div class="form-group">
                        <label for="categoria"><b>Correo electrónico <span class="req">*</span>:</b></label>
                        <input id="email" v-model="user.email" type="email" class="form-control" name="email" value="" placeholder="Ingrese correo electronico." autofocus>
                    </div> 

                    <div class="form-group">
                        <label for="categoria"><b>Contraseña <span class="req">*</span>:</b></label>
                        <input id="password" v-model="user.password" type="password" class="form-control" name="password" placeholder="Ingrese contraseña de Usuario.">
                    </div> 

                    <br>
                    
                    <button type="submit" class="btn btn-primary btn-ingresar">Ingresar</button>
                </form> 
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data(){
        return { 
           user: {}
        } 
    },
    mounted() {

    },
    methods: {
        // LOGIN ADMIN
        login: function(){
            axios.post('/api/login', this.user).then((response) => {
               window.isLogin = true;
               localStorage.setItem('currentUser', JSON.stringify(response.data));
               this.$router.push('/');
            })
        }
    }
}
</script>
