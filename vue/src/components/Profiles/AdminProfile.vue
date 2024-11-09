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
        <p class="text-lightSecondary text-base font-normal text-center">{{ userRole }}</p>
      </div>
      <div class="flex flex-col mt-4 w-full">
        <input type="text" v-model="userName" :disabled="!isEditing" class="mb-2 p-2 border rounded" placeholder="Имя" />
        <input type="text" v-model="userLogin" :disabled="!isEditing" class="mb-2 p-2 border rounded" placeholder="Login" />
        <input type="text" v-model="userEmail" :disabled="!isEditing" class="mb-2 p-2 border rounded" placeholder="Email" />
        <input type="text" v-model="userPhone" :disabled="!isEditing" class="mb-2 p-2 border rounded" placeholder="Телефон" />
        <input type="text" v-model="userCity" :disabled="!isEditing" class="mb-2 p-2 border rounded" placeholder="Город" />
        <input type="file" v-if="isEditing" @change="handleFileUpload" class="mb-2" />
        <button @click="toggleEdit" class="bg-red-500 text-white p-2 rounded mt-2">
          {{ isEditing ? 'Сохранить' : 'Изменить данные' }}
        </button>
      </div>
      <button @click="goToAdminPanel" class="bg-blue-500 text-white p-2 rounded mt-4 w-full">
        Перейти в админ-панель
      </button>
    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue';

const userName = ref('Константин Назаров');
const userEmail = ref('example@example.com');
const userLogin = ref('admin');
const userRole = ref('Гость');
const userPhone = ref('89069081178');
const userCity = ref('Москва'); // Добавляем город
const isEditing = ref(false); // Состояние редактирования

// Функция для переключения режима редактирования
const toggleEdit = () => {
  if (isEditing.value) {
    // Здесь можно добавить логику для сохранения данных, например, отправка на сервер
    console.log('Данные сохранены:', {
      userName: userName.value,
      userLogin: userLogin.value,
      userEmail: userEmail.value,
      userPhone: userPhone.value,
      userCity: userCity.value,
    });
  }
  isEditing.value = !isEditing.value; // Переключаем состояние редактирования
};

// Функция для обработки загрузки файла (например, для аватара)
const handleFileUpload = (event) => {
  const file = event.target.files[0];
  if (file) {
    // Здесь можно добавить логику для загрузки файла на сервер
    console.log('Загружен файл:', file.name);
  }
};

// Функция для перехода на админ-панель
const goToAdminPanel = () => {
  window.location.href = '/admin'; // Замените на нужный путь
};
</script>