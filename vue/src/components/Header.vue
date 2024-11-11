<template>
  <header class="bg-black">
    <nav class="mx-auto flex max-w-7xl items-center justify-between p-6 lg:px-8" aria-label="Global">
      <div class="flex lg:flex-1 text-[24px]">
        <a href="/"><span class="font-normal text-white">Man</span><span class="bg-white">Style</span></a>
      </div>
      <div class="flex lg:hidden">
        <button type="button" class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-white"
          @click="mobileMenuOpen = true">
          <Bars3Icon class="h-6 w-6" aria-hidden="true" />
        </button>
      </div>
      <PopoverGroup class="hidden lg:flex lg:gap-x-12">
        <a href="#services" class="text-sm font-semibold leading-6 text-white hover:text-red-600">Услуги</a>
        <a href="#barbers" class="text-sm font-semibold leading-6 text-white hover:text-red-600">Барберы</a>
        <a href="#contacts" class="text-sm font-semibold leading-6 text-white hover:text-red-600">Контакты</a>
        <router-link class="text-sm font-semibold leading-6 text-white hover:text-red-600"
          to="/record">Записаться</router-link>
      </PopoverGroup>
      <div class="hidden lg:flex lg:flex-1 lg:justify-end">
        <div v-if="!isAuthenticated">
          <router-link class="text-sm font-semibold leading-6 text-white hover:text-red-600"
            to="/login">Войти</router-link>
        </div>
        <div v-else class="relative bg-white rounded-full">
          <router-link to="/user_profile">
            <img v-if="userImage"
              class="rounded-full cursor-pointer h-[50px] w-[50px] object-cover border-2 border-white"
              :src="`http://127.0.0.1:8000/storage/${userImage}`" alt="User's Face" @mouseover="showTooltip = true"
              @mouseleave="showTooltip = false">
            <img v-else class="h-[50px] w-[50px] rounded-full cursor-pointer object-cover border-2 border-white"
              src="../../../public/default.png" alt="User's Face" @mouseover="showTooltip = true"
              @mouseleave="showTooltip = false">
          </router-link>
          <div v-if="showTooltip" class="absolute bg-gray-700 text-white text-xs rounded py-1 -bottom-8 right-0 z-50">
            Профиль
          </div>
        </div>
      </div>
    </nav>
    <div
      class="relative h-[350px] overflow-hidden bg-[url('../public/img/after_header.svg')] bg-cover w-full bg-no-repeat">
      <div class="absolute bottom-0 left-0 right-0 top-0 h-full w-full overflow-hidden bg-black/60 bg-fixed">
        <div class="flex h-full items-center justify-left">
          <div class="px-6 text-center text-white md:px-12 max-w-[500px]">
            <h1 class="mb-6 text-4xl font-bold">Место, где рождается ваш стиль</h1>
            <h3 class="mb-8 text-2xl font-bold">Хотите знать, почему мы лучшие?
              Записывайтесь, пока есть места!</h3>
            <router-link to="/record"
              class="inline-block rounded border-2 border-neutral-50 px-6 pb-[6px] pt-2 text-xs font-medium uppercase leading-normal text-neutral-50 transition duration-150 ease-in-out hover:border-neutral-300 hover:text-neutral-200 focus:border-neutral-300 focus:text-neutral-200 focus:outline-none focus:ring-0 active:border-neutral-300 active:text-neutral-200 dark:hover:bg-neutral-600 dark:focus:bg-neutral-600"
              data-twe-ripple-init data-twe-ripple-color="light">
              Записаться
            </router-link>
          </div>
        </div>
      </div>
    </div>
    <Dialog class="lg:hidden" @close="mobileMenuOpen = false" :open="mobileMenuOpen">
      <div class="fixed inset-0 z-10" />
      <DialogPanel
        class="fixed inset-y-0 right-0 z-10 w-full overflow-y-auto bg-white px-6 py-6 sm:max-w-sm sm:ring-1 sm:ring-gray-900/10">
        <div class="flex items-center justify-between">
          <a href="#" class="-m-1.5 p-1.5 text-[24px]"><span class="font-normal">Man</span><span
              class="bg-black text-white">Style</span></a>
          <button type="button" class="-m-2.5 rounded-md p-2.5 text-gray-700" @click="mobileMenuOpen = false">
            <span class="sr-only">Close menu</span>
            <XMarkIcon class="h-6 w-6" aria-hidden="true" />
          </button>
        </div>
        <div class="mt-6 flow-root">
          <div class="-my-6 divide-y divide-gray-500/10">
            <div class="space-y-2 py-6">
              <a href="#services"
                class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50"
                @click="mobileMenuOpen = false">Услуги</a>
              <a href="#barbers"
                class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50"
                @click="mobileMenuOpen = false">Барберы</a>
              <a href="#contacts"
                class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50"
                @click="mobileMenuOpen = false">Контакты</a>
              <router-link
                class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50"
                to="/record" @click.native="mobileMenuOpen = false">Записаться</router-link>
            </div>
            <div class="py-6">
              <div v-if="isAuthenticated">
                <router-link
                  class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50"
                  to="/user_profile" @click.native="mobileMenuOpen = false">Профиль</router-link>
              </div>
              <div v-else>
                <router-link
                  class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50"
                  to="/login" @click.native="mobileMenuOpen = false">Войти</router-link>
              </div>
            </div>
          </div>
        </div>
      </DialogPanel>
    </Dialog>
  </header>
</template>

<script setup>
import {
  Dialog,
  DialogPanel,
  PopoverGroup,
} from '@headlessui/vue'
import {
  Bars3Icon,
  XMarkIcon,
} from '@heroicons/vue/24/outline'
import { ref, computed, onMounted } from 'vue';

const showTooltip = ref(false);
const userImage = ref('');

const mobileMenuOpen = ref(false)

// Проверка авторизации
const isAuthenticated = computed(() => {
  return localStorage.getItem('UserData') !== null; // Замените 'user' на ключ, который вы используете для хранения данных пользователя
});

// Получение данных пользователя
onMounted(() => {
  const user = JSON.parse(localStorage.getItem('UserData'));
  if (user) {
    userImage.value = user.user.image; // Предполагается, что у вас есть поле image в объекте пользователя
    console.log(userImage.value);
  }
});
</script>