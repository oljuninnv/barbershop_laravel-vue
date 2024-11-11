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
        <p class="text-lightSecondary text-base font-normal text-center">Администратор</p>
      </div>
      <div class="flex flex-col mt-4 w-full">
        <Profile />
      </div>
      <button @click="goToAdminPanel" class="bg-red-500 text-white p-2 rounded mt-4 w-full">
        Перейти в админ-панель
      </button>
      <router-link to="/" class="bg-black text-white p-2 rounded mt-4 w-full text-center">
        На главную страницу
      </router-link>
      <button @click="logout" class="bg-gray-500 text-white p-2 rounded mt-4 w-full">
        Выйти из аккаунта
      </button>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import Profile from './ProfilesComponent/Profile.vue';

const router = useRouter();
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

const logout = () => {
  // Очистка данных из localStorage
  localStorage.clear();
  router.push('/login');
};

// Используем onMounted для получения имени пользователя при монтировании компонента
onMounted(() => {
  userName.value = getUserNameFromLocalStorage();
  userImage.value = getUserImageFromLocalStorage();
});
</script>