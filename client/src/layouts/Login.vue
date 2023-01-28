<template>
  <q-layout view="lHh Lpr lFf" class="flex flex-center">
    <q-card class="login-form flex q-pa-lg">
      <q-card-section>
        {{ $q.version }}
        <div class="col text-h6 ellipsis">Войти в систему</div>
      </q-card-section>
      <q-card-section>
        <q-form
          @submit="login()"
          @reset="onReset"
          class="q-gutter-md"
        >
          <q-input
            v-model="email"
            label="Email"
            lazy-rules
            :rules="[val => val && val.length > 0 || 'Поле не заполнено']"
            filled
          />

          <q-input
            type="password"
            v-model="password"
            label="Пароль"
            lazy-rules
            :rules="[val => val !== null && val !== '' || 'Введите пароль']"
            filled
          />
          <div>
            <q-btn label="Войти" type="submit" color="primary"/>
            <q-btn label="Сбросить пароль" type="reset" color="primary" flat class="q-ml-sm"/>
          </div>
        </q-form>
      </q-card-section>
    </q-card>
  </q-layout>
</template>

<script>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useQuasar } from 'quasar'
import { useUserStore } from 'stores/modules/user'

export default {
  setup() {
    const $q = useQuasar()
    const $router = useRouter()
    const user = useUserStore()

    const email = ref('')
    const password = ref('')

    function login() {
      user.login({
        email: email.value,
        password: password.value
      }).then(() => {
          $q.notify({
            type: 'positive',
            message: 'Вы успешно вошли в систему!'
          });
          $router.push('/')
      }).catch(error => {
        $q.notify({
          type: 'negative',
          message: error
        });
        console.log(error)
      })
    }

    return {
      email,
      password,
      user,
      login
    }
  },
  methods: {
    // login() {
    //   this.$patch('login', {
    //     email: this.model.email,
    //     password: this.model.password
    //   }).then(() => {
    //     if (this.status === 'success') {
    //       this.$q.notify({
    //         type: 'positive',
    //         message: 'Вы успешно вошли в систему!'
    //       });
    //       this.$router.push('/')
    //     } else {
    //       this.$q.notify({
    //         type: 'negative',
    //         message: this.message
    //       });
    //     }
    //   }).catch(error => {
    //       console.log(error)
    //     })
    // }
  }
}
</script>
<style lang="scss">
.login-form {
  max-width: 400px;
}
</style>
