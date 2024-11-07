<template>
    <div class="flex min-h-full flex-1 flex-col justify-center px-6 py-12 lg:px-8">
      <div class="sm:mx-auto sm:w-full sm:max-w-sm">
        <h2 class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">Изменение пароля</h2>
      </div>
  
      <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
        <form @submit.prevent="changePassword">
          <div>
            <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Пароль</label>
            <div class="mt-2">
              <input type="password" v-model="formData.password" placeholder="Введите новый пароль" required
                class="input_text" />
            </div>
          </div>
  
          <div class="mt-4">
            <label for="confirmPassword" class="block text-sm font-medium leading-6 text-gray-900">Подтверждение
              пароля</label>
            <div class="mt-2">
              <input type="password" v-model="formData.password_confirmation" placeholder="Подтвердите новый пароль"
                required class="input_text" />
            </div>
          </div>
  
          <div>
            <button type="submit"
              class="mt-5 flex w-full justify-center rounded-md bg-red-500 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-neutral-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
              Изменить пароль
            </button>
          </div>
  
          <p v-if="message" class="text-green-500">{{ message }}</p>
          <p v-if="errorMessage" class="text-red-500">{{ errorMessage }}</p>
        </form>
      </div>
    </div>
  </template>
  
  <script>
  import { ResetPassword } from '../../services/api/auth';
  
  export default {
    data() {
      return {
        formData: {
          password: '',
          password_confirmation: '',
          token: '' // Токен будет установлен из URL
        },
        message: '',
        errorMessage: ''
      };
    },
    mounted() {
      this.formData.token = this.$route.query.token;
      if (!this.formData.token) {
        this.errorMessage = 'Токен не найден в URL.';
      }
    },
    methods: {
      async changePassword() {
        this.errorMessage = '';
        this.message = '';
  
        // Проверка на совпадение паролей
        if (this.formData.password !== this.formData.password_confirmation) {
          this.errorMessage = 'Пароли не совпадают.';
          return;
        }
  
        try {
          const response = await ResetPassword(this.formData);
  
          console.log(response);
  
          if (response.success) {
            this.message = response.msg; // Сообщение об успешном изменении пароля
            this.$router.push({ name: 'Login' }); // Переход на страницу входа
          } else {
            this.errorMessage = response.msg; // Сообщение об ошибке
          }
        } catch (error) {
          console.log(error);
          // this.errorMessage = 'Произошла ошибка при изменении пароля.'; // Обработка ошибок
        }
      }
    }
  };
  </script>