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
                @input="validateUsername"
                autocomplete="username"
                required
                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
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
                @input="validatePhone"
                required
                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
            />
            <span v-if="errorMessagePhone" class="text-red-500">{{ errorMessagePhone }}</span>          
        </div>
        

        <div>
          <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email-адрес</label>
          <div class="mt-2">
            <input id="email" name="email" type="email" autocomplete="email" required="" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
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
                    @input="validatePassword"
                    autocomplete="current-password"
                    required
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                />
                <span v-if="errorMessagePassword" class="text-red-500">{{ errorMessagePassword }}</span>          
            </div>
        </div>

        <div>
          <button type="submit" class="flex w-full justify-center rounded-md bg-red-500 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-neutral-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Зарегитрироваться</button>
        </div>
      </form>      
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