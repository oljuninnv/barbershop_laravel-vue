<template>
  <div class="flex min-h-full flex-1 flex-col justify-center px-6 py-12 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
      <h2 class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">Авторизация</h2>
    </div>

    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
      <form class="space-y-6" action="#" method="POST">
        <div>
          <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email-адрес</label>
          <div class="mt-2">
            <input id="email" name="email" type="email" autocomplete="email" class="input_text" />
          </div>
        </div>

        <div>
          <div class="flex items-center justify-between">
            <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Пароль</label>
            <div class="text-sm">
              <router-link class="font-semibold text-red-600 hover:text-neutral-400" to="/#">Забыли пароль?</router-link>
            </div>
          </div>
          <div class="mt-2">
            <input id="password" name="password" type="password" autocomplete="current-password" class="input_text" />
          </div>
        </div>

        <div>
          <button type="submit" class="flex w-full justify-center rounded-md bg-red-500 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-neutral-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Войти</button>
        </div>
      </form>

      <p class="mt-10 text-center text-sm text-gray-500">
        Ещё не зарегистрированы?
        {{ ' ' }}
        <router-link class="font-semibold leading-6 text-red-600 hover:text-neutral-400" to="/register">Регистрация</router-link>
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