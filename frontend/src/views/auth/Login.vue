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
            v-model="model.username"
            placeholder="Username"
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
  export default {
    data() {
      return {
        validCredentials: {
          username: "lightscope",
          password: "lightscope"
        },
        model: {
          username: "",
          password: ""
        },
        loading: false,
        rules: {
          username: [
            {
              required: true,
              message: "Введите Имя пользователя!",
              trigger: "blur"
            },
            {
              min: 3,
              message: "Имя пользователя должно быть не менее 3 символов!",
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
        }
      };
    },
    methods: {
      simulateLogin() {
        return new Promise(resolve => {
          setTimeout(resolve, 800);
        });
      },
      async login() {
        let valid = await this.$refs.form.validate();
        if (!valid) {
          return;
        }
        this.loading = true;
        await this.simulateLogin();
        this.loading = false;
        if (
          this.model.username === this.validCredentials.username &&
          this.model.password === this.validCredentials.password
        ) {
          this.$message.success("Вы успешно вошли в систему!");
        } else {
          this.$message.error("Неверные данные для входа!");
        }
      }
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
  .login .el-input__inner:hover {
    border-color: $teal;
  }
  .login .el-input__prefix {
    background: rgb(238, 237, 234);
    left: 0;
    height: calc(100% - 2px);
    left: 1px;
    top: 1px;
    border-radius: 3px;
    .el-input__icon {
      width: 30px;
    }
  }
  .login .el-input input {
    padding-left: 35px;
  }
  .login .el-card {
    padding-top: 0;
    padding-bottom: 30px;
  }
  h2 {
    font-family: "Open Sans";
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
  .login .el-card {
    width: 340px;
    display: flex;
    justify-content: center;
  }
</style>
