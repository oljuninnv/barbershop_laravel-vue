<template>
    <div class="absolute top-4 left-4 flex items-center">
      <router-link to="/login" class="return flex items-center text-black text-lg">
        <span class="mr-2 w-5 h-5 bg-no-repeat bg-left-center bg-contain"
          style="background-image: url('../public/return.png');"></span>
        Назад
      </router-link>
    </div>
  
    <div class="flex min-h-full flex-1 flex-col justify-center px-6 py-12 lg:px-8">
      <div class="sm:mx-auto sm:w-full sm:max-w-sm">
        <h2 class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">Сброс пароля</h2>
      </div>
  
      <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
        <form @submit.prevent="sendResetLink">
          <div>
            <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email-адрес</label>
            <div class="mt-2">
              <input type="email" v-model="formData.email" placeholder="Введите ваш email" required class="input_text" />
            </div>
          </div>
  
          <div>
            <button type="submit"
              class="mt-5 flex w-full justify-center rounded-md bg-red-500 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-neutral-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
              Сбросить пароль
            </button>
          </div>
  
          <p v-if="message" class="text-green-500">{{ message }}</p>
          <p v-if="errorMessage" class="text-red-500">{{ errorMessage }}</p>
        </form>
      </div>
    </div>
  </template>
  
  <script>
  import { ResetLink } from '../../services/api/auth';
  
  export default {
    data() {
      return {
        formData: {
          email: ''
        },
        message: '',
        errorMessage: ''
      };
    },
    methods: {
      validateInput() {
        this.errorMessage = '';
        if (!this.formData.email) {
          this.errorMessage = 'Email обязателен.';
        }
      },
      async sendResetLink() {
        this.validateInput();
        if (!this.errorMessage) {
          try {
            const response = await ResetLink(this.formData);
            console.log(response);
  
            if (response.success) {
              alert('Ссылка для сброса пароля отправлена на указанную почту!');
              this.message = 'Ссылка для сброса пароля отправлена на указанную почту!';
              localStorage.setItem('success', 'true');
            } else if (response.success == false) {
              alert('Такого пользователя не существует, проверьте данные!!!');
            }
            else {
              this.errorMessage = response.message;
            }
          } catch (error) {
            console.error('Ошибка сети:', error);
            this.errorMessage = 'Произошла ошибка при отправке ссылки.';
          }
        }
      }
    }
  };
  </script>
  