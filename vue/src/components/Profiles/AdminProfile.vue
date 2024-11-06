<template>
    <section class="p-5">
      <div class="rounded-xl relative mx-auto flex h-full w-full flex-col items-center p-[16px]">
        <div class="relative mt-1 flex h-32 w-full justify-center rounded-xl bg-cover"
          style='background-image: url("../public/img/after_header.svg");'>
          <div class="absolute -bottom-12 flex h-[88px] w-[88px] items-center justify-center rounded-full border-[4px] border-white bg-pink-400 overflow-hidden">
            <img class="h-full w-full object-cover rounded-full" src="/public/img/barbers/barber1.svg" alt="" />
          </div>
        </div>
        <div class="mt-16 flex flex-col items-center">
          <h4 class="text-bluePrimary text-xl font-bold text-center">{{ userName }}</h4>
          <p class="text-lightSecondary text-base font-normal text-center">Admin</p>
          <p class="text-lightSecondary text-base font-normal text-center">Email: {{ userEmail }}</p>
          <p class="text-lightSecondary text-base font-normal text-center">Телефон: {{ userPhone }}</p>
        </div>
        <button @click="goToAdminPanel" class="bg-blue-500 text-white p-2 rounded mt-4">
          Перейти в админ-панель
        </button>
      </div>
    </section>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  
  const userName = ref('Константин Назаров');
  const userEmail = ref('example@example.com');
  const userRole = ref('Гость');
  const userPhone = ref('89069081178');
  
  // Функция для перехода на админ-панель
  const goToAdminPanel = () => {
    // Здесь можно добавить логику для перехода на админ-панель, например:
    window.location.href = '/admin'; // Замените на нужный путь
  };
  </script>