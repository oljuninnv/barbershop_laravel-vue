<template>
    <div class="flex min-h-full flex-1 flex-col justify-center px-6 py-12 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
      <h2 class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">Регистрация</h2>
    </div>

    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
      <form class="space-y-6" action="#" method="POST">

        <div>
          <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Ваше имя</label>
            <div class="mt-2">
                <input
                id="username"
                name="username"
                type="text"
                v-model="username"
                
                autocomplete="username"
                required
                class="input_text"
                />
                <span v-if="errorMessageLogin" class="text-red-500">{{ errorMessageLogin }}</span>          
            </div>
        </div>

        <div class="flex items-center justify-between mt-4">
            <label for="phone" class="block text-sm font-medium leading-6 text-gray-900">Номер телефона</label>            
        </div>
            <div class="mt-2">
            <input
                id="phone"
                name="phone"
                type="tel"
                v-model="phone"
                
                required
                class="input_text"
            />
            <span v-if="errorMessagePhone" class="text-red-500">{{ errorMessagePhone }}</span>          
        </div>
        

        <div>
          <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email-адрес</label>
          <div class="mt-2">
            <input id="email" name="email" type="email" autocomplete="email" required="" class="input_text" />
          </div>
        </div>

        <div>
          <div class="flex items-center justify-between">
            <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Пароль</label>            
          </div>
          <div class="mt-2">
                <input
                    id="password"
                    name="password"
                    type="password"
                    v-model="password"
                    
                    autocomplete="current-password"
                    required
                    class="input_text"
                />
                <span v-if="errorMessagePassword" class="text-red-500">{{ errorMessagePassword }}</span>          
            </div>
        </div>

        <div>
          <button type="submit" class="flex w-full justify-center rounded-md bg-red-500 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-neutral-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Зарегитрироваться</button>
        </div>
      </form> 
      <p class="mt-10 text-center text-sm text-gray-500">
          Уже зарегистрированы?
          {{ ' ' }}
          <router-link class="font-semibold leading-6 text-red-600 hover:text-neutral-400" to="/login">Войти</router-link>
        </p> 
        
        <div class="absolute top-4 left-4 flex items-center">
          <router-link to="/" class="return flex items-center text-black text-lg">
              <span class="mr-2 w-5 h-5 bg-no-repeat bg-left-center bg-contain" style="background-image: url('../public/return.png');"></span>
              Назад
          </router-link>
      </div>
    </div>
  </div>
</template>

<script>
    export default {
        data() {
            return {
            username: '',
            errorMessageLogin: '',
            password: '',
            errorMessagePassword: '',
            phone:'',
            errorMessagePhone: ''
        };
    },
    methods: {
            validateUsername() {
                const regex = /^[a-zA-Zа-яА-ЯёЁ]{1,50}$/;
                if (!regex.test(this.username)) {
                    this.errorMessageLogin = 'Имя должно содержать только русские или английские буквы до 50 символов.';
                } else {
                    this.errorMessageLogin = '';
                }
            },
            validatePassword() {
                const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
                if (!regex.test(this.password)) {
                    this.errorMessagePassword = 'Пароль должен содержать минимум 8 символов, включая заглавные и строчные латинские буквы, цифры и специальные символы.';
                } else {
                    this.errorMessagePassword = '';
                }
            },
            validatePhone() {
            const regex = /^\+?[1-9]\d{1,14}$/; 
                if (!regex.test(this.phone)) {
                    this.errorMessagePhone = 'Введите корректный номер телефона.';
                } else {
                    this.errorMessagePhone = '';
                }
            }
        }
    };
</script>