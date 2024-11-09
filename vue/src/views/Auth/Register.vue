<template>
    <div class="flex min-h-full flex-1 flex-col justify-center px-6 py-12 lg:px-8">
      <div class="sm:mx-auto sm:w-full sm:max-w-sm">
        <h2 class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">Регистрация</h2>
      </div>
  
      <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
        <form class="space-y-6" @submit.prevent="register">
          <!-- Поля формы -->
          <div>
            <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Имя</label>
            <div class="mt-2">
              <input id="name" name="name" v-model="formData.name" required class="input_text" />
            </div>
          </div>
  
          <div>
            <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email</label>
            <div class="mt-2">
              <input id="email" name="email" type="email" v-model="formData.email" required class="input_text" />
            </div>
          </div>
  
          <div>
            <label for="phone" class="block text-sm font-medium leading-6 text-gray-900">Телефон</label>
            <div class="mt-2">
              <input id="phone" name="phone" v-model="formData.phone" required class="input_text" />
            </div>
          </div>
  
          <div>
            <label for="city" class="block text-sm font-medium leading-6 text-gray-900">Город</label>
            <div class="mt-2">
              <input id="city" name="city" v-model="formData.city" required class="input_text" />
            </div>
          </div>
  
          <div>
            <label for="birthday" class="block text-sm font-medium leading-6 text-gray-900">Дата рождения</label>
            <div class="mt-2">
              <input id="birthday" name="birthday" type="date" v-model="formData.birthday" class="input_text" />
            </div>
          </div>
  
          <div>
            <label for="login" class="block text-sm font-medium leading-6 text-gray-900">Логин</label>
            <div class="mt-2">
              <input id="login" name="login" v-model="formData.login" required class="input_text" />
            </div>
  
            <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Пароль</label>
            <div class="mt-2">
              <input id="password" name="password" type="password" v-model="formData.password" class="input_text" />
            </div>
          </div>
  
          <div>
            <button type="submit" class="flex w-full justify-center rounded-md bg-red-500 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-neutral-400">Зарегистрироваться</button>
          </div>
        </form>
  
        <p class="mt-10 text-center text-sm text-gray-500">
          Уже зарегистрированы?
          {{ ' ' }}
          <router-link class="font-semibold leading-6 text-red-600 hover:text-neutral-400" to="/auth/login">Войти</router-link>
        </p>
      </div>
  
      <!-- Модальное окно для ошибок -->
      <div v-if="errorMessages.length > 0" class="flex justify-center mt-4">
    <div class="shadow-1 dark:bg-dark-2 flex w-full max-w-md rounded-lg border-l-[6px] border-transparent bg-white px-7 py-8 md:p-9">
      <div class="w-full">
        <h5 class="mb-3 text-base font-semibold text-[#BC1C21]">
          Обнаружены ошибки:
        </h5>
        <ul class="list-inside list-disc">
          <li v-for="(message, index) in errorMessages" :key="index" class="text-red-light text-base leading-relaxed">
            {{ message }}
          </li>
        </ul>
      </div>
    </div>
      </div>
    </div>
  </template>
  
  <script>
  import { registerUser } from '../../services/api/auth';
  
  export default {
    data() {
      return {
        formData: {
          name: '',
          email: '',
          phone: '',
          city: '',
          birthday: '',
          login: '',
          password: '',
        },
        errorMessages: [],
        showErrorModal: false
      };
    },
    methods: {
      async register() {
        try {
          const response = await registerUser(this.formData);
          console.log(response);
          if (response.access_token) {
            localStorage.setItem('token', response.access_token);
            this.$router.push('/login');
          }
        } catch (error) {
          this.errorMessages = []; // Сброс ошибок перед добавлением новых
          if (error.response) {
            const errorData = error.response.data;
  
            if (errorData.messages) {
              for (const [key, messages] of Object.entries(errorData.messages)) {
                this.errorMessages.push(...messages);
              }
            } else {
              this.errorMessages.push('Произошла неизвестная ошибка. Пожалуйста, попробуйте позже.');
            }
          }
  
          this.showErrorModal = true; // Показать модальное окно
        }
      }
    }
  };
  </script>