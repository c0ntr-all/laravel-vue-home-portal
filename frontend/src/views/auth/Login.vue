<template>
  <div class="login">
    <el-card>
      <h2>Вход в систему</h2>
      <el-form
        class="login-form"
        :model="model"
        :rules="rules"
        ref="form"
        @submit.native.prevent="login"
      >
        <el-form-item prop="username">
          <el-input
            v-model="model.email"
            placeholder="Email"
            type="email"
            :prefix-icon="User"
          ></el-input>
        </el-form-item>
        <el-form-item prop="password">
          <el-input
            v-model="model.password"
            placeholder="Password"
            type="password"
            :prefix-icon="Lock"
          ></el-input>
        </el-form-item>
        <el-form-item>
          <el-button
            :loading="loading"
            class="login-button"
            type="primary"
            native-type="submit"
            block
          >Войти</el-button>
        </el-form-item>
        <a class="forgot-password" href="#">Забыли пароль?</a>
      </el-form>
    </el-card>
  </div>
</template>

<script setup>
  import {
    User,
    Lock,
  } from '@element-plus/icons-vue'
</script>
<script>
  import axios from "axios";

  export default {
    data() {
      return {
        validCredentials: {
          email: "lightscope",
          password: "lightscope"
        },
        model: {
          email: null,
          password: null
        },
        loading: false,
        rules: {
          email: [
            {
              required: true,
              message: "Введите ваш email!",
              trigger: "blur"
            },
            {
              min: 3,
              message: "Email должен быть не менее 3 символов!",
              trigger: "blur"
            }
          ],
          password: [
            { required: true, message: "Введите пароль!", trigger: "blur" },
            {
              min: 5,
              message: "Длина пароля должна быть не менее 5 символов!",
              trigger: "blur"
            }
          ]
        },
        error: null
      };
    },
    methods: {
      login() {
        this.$store.dispatch('login', {email: this.model.email, password: this.model.password})
        .then(() => {
          this.$router.push('/')
        })
        .catch(error => console.log(error))
      }
      // async login() {
      //   await axios.post('api/auth/login', {email: this.model.email, password: this.model.password})
      //     .then(response => {
      //       this.$message.success("Вы успешно вошли в систему!");
      //
      //       localStorage.access_token = response.data.access_token
      //       this.$router.push('/home')
      //     }).catch(error => {
      //       if(error.response.data.error) {
      //         this.$message.error(error.response.data.error);
      //       }
      //     })
      // },
    }
  };
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
  .login {
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .login-button {
    width: 100%;
    margin-top: 40px;
  }
  .login-form {
    width: 290px;
  }
  .forgot-password {
    margin-top: 10px;
  }
</style>
<style lang="scss">
  $teal: rgb(0, 124, 137);

  .el-button--primary {
    background: $teal;
    border-color: $teal;

    &:hover,
    &.active,
    &:focus {
      background: lighten($teal, 7);
      border-color: lighten($teal, 7);
    }
  }
  .login {
    .el-input__inner {
      &:hover {
        border-color: $teal;
      }
    }
  }
  .login {
    .el-input__prefix {
      background: rgb(238, 237, 234);
      height: calc(100% - 2px);
      left: 1px;
      top: 1px;
      border-radius: 3px;

      .el-input__icon {
        width: 30px;
      }
    }
  }
  .login {
    .el-input {
      input {
        padding-left: 35px;
      }
    }
  }
  .login {
    .el-card {
      padding-top: 0;
      padding-bottom: 30px;
    }
  }
  h2 {
    letter-spacing: 1px;
    font-family: Roboto, sans-serif;
    padding-bottom: 20px;
  }
  a {
    color: $teal;
    text-decoration: none;

    &:hover,
    &:active,
    &:focus {
      color: lighten($teal, 7);
    }
  }
  .login {
    .el-card {
      width: 340px;
      display: flex;
      justify-content: center;
    }
  }
</style>
