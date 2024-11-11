<template>
  <section class="p-5">
    <div class="rounded-xl relative mx-auto flex h-full w-full flex-col items-center p-[16px]">
      <div class="relative mt-1 flex h-32 w-full justify-center rounded-xl bg-cover" style='background-image: url("../public/img/after_header.svg");'>
        <div class="absolute -bottom-12 flex h-[88px] w-[88px] items-center justify-center rounded-full border-[4px] border-white bg-pink-400 overflow-hidden">
    <img class="h-full w-full object-cover rounded-full" :src="userImage" alt="User Image" />
  </div>
      </div>
      <div class="mt-16 flex flex-col items-center">
        <h4 class="text-bluePrimary text-xl font-bold text-center">{{ userName }}</h4>
        <p class="text-lightSecondary text-base font-normal text-center">Барбер</p>
      </div>

      <div class="text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:text-gray-400 dark:border-gray-700 w-full">
    <ul class="flex flex-wrap -mb-px">
      <li class="me-2">
        <a 
          href="#" 
          @click.prevent="activeTab = 'profile'" 
          :class="{'border-b-2 border-blue-600 text-blue-600': activeTab === 'profile', 'border-transparent': activeTab !== 'profile'}"
          class="inline-block p-4 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300">Профиль</a>
      </li>
      <li class="me-2">
        <a 
          href="#" 
          @click.prevent="activeTab = 'records'" 
          :class="{'border-b-2 border-blue-600 text-blue-600': activeTab === 'records', 'border-transparent': activeTab !== 'records'}"
          class="inline-block p-4 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300">Записи</a>
      </li>
      <li class="me-2">
        <a 
          href="#" 
          @click.prevent="activeTab = 'GenerateRecords'" 
          :class="{'border-b-2 border-blue-600 text-blue-600': activeTab === 'GenerateRecords', 'border-transparent': activeTab !== 'GenerateRecords'}"
          class="inline-block p-4 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300">Создание записи</a>
      </li>
    </ul>
  </div>

  <div class="w-full mt-4">
    <div v-if="activeTab === 'profile'">
      <Profile />
    </div>
    <div v-if="activeTab === 'records'">
      <Records />
    </div>
    <div v-if="activeTab === 'GenerateRecords'">
      <GenerateRecords />
    </div>
  </div>
  </div>  
  </section>
</template>

<script setup>
import Profile from './ProfilesComponent/Profile.vue';
import Records from './ProfilesComponent/Records.vue';
import GenerateRecords from './ProfilesComponent/GenerateRecords.vue';

import { ref, onMounted } from 'vue';

const activeTab = ref('records'); // Установите активную вкладку по умолчанию

const userImage = ref(''); // Инициализируем переменную для изображения пользователя
const userName = ref(''); // Инициализируем переменную для имени пользователя

// Функция для получения имени пользователя из localStorage
const getUserNameFromLocalStorage = () => {
  const userData = localStorage.getItem('UserData');
  if (userData) {
    const parsedData = JSON.parse(userData);
    return parsedData.user?.name || ''; // Возвращаем имя пользователя или пустую строку
  }
  return '';
};

const getUserImageFromLocalStorage = () => {
  const userData = localStorage.getItem('UserData');
  if (userData) {
    const parsedData = JSON.parse(userData);
    return `http://127.0.0.1:8000/storage/${parsedData.user?.image}` || '../../../public/default.png'; // Возвращаем изображение пользователя или путь к default.png
  }
  return '../../../public/default.png'; // Если данных нет, возвращаем путь к default.png
};

// Используем onMounted для получения имени пользователя при монтировании компонента
onMounted(() => {
  userName.value = getUserNameFromLocalStorage();
  userImage.value = getUserImageFromLocalStorage();
});
</script>