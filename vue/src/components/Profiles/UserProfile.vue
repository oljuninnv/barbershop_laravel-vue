<template>
  <section class="p-5">
    <div class="rounded-xl relative mx-auto flex h-full w-full flex-col items-center p-[16px]">
      <div class="relative mt-1 flex h-32 w-full justify-center rounded-xl bg-cover"
           style='background-image: url("../public/img/after_header.svg");'>
           <div class="absolute -bottom-12 flex h-[88px] w-[88px] items-center justify-center rounded-full border-[4px] border-white bg-pink-400 overflow-hidden">
    <img class="h-full w-full object-cover rounded-full" :src="userImage" alt="User Image" />
  </div>
      </div>
      <div class="mt-16 flex flex-col items-center">
        <h4 class="text-bluePrimary text-xl font-bold text-center">{{ userName }}</h4>
        <p class="text-lightSecondary text-base font-normal text-center">Гость</p>
      </div>

      <!-- Переключатель между вкладками -->
      <div class="text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:text-gray-400 dark:border-gray-700 w-full">
        <ul class="flex flex-wrap -mb-px">
          <li class="me-2">
            <a href="#" @click.prevent="activeTab = 'profile'"
               :class="{ 'border-b-2 border-blue-600 text-blue-600': activeTab === 'profile', 'border-transparent': activeTab !== 'profile' }"
               class="inline-block p-4 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300">Профиль</a>
          </li>
          <li class="me-2">
            <a href="#" @click.prevent="activeTab = 'appointments'"
               :class="{ 'border-b-2 border-blue-600 text-blue-600': activeTab === 'appointments', 'border-transparent': activeTab !== 'appointments' }"
               class="inline-block p-4 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300">Записи</a>
          </li>
        </ul>
      </div>

      <!-- Контент в зависимости от активной вкладки -->
      <div class="w-full mt-4">
        <div v-if="activeTab === 'profile'">
          <Profile/>
        </div>
        <div v-else>
          <h3 class="text-lg font-bold mb-4">Список записей</h3>
          <div class="tabs">
            <button @click="activeRecordTab = 'active'"
                    :class="{ 'bg-red-500 text-white': activeRecordTab === 'active' }" class="p-2 rounded-l">
              Активные записи
            </button>
            <button @click="activeRecordTab = 'inactive'"
                    :class="{ 'bg-red-500 text-white': activeRecordTab === 'inactive' }" class="p-2 rounded-r">
              Неактивные записи
            </button>
          </div>
          <div class="overflow-y-auto max-h-60">
            <table class="w-full border-collapse">
              <thead>
                <tr>
                  <th class="border-b p-2 text-left">Дата</th>
                  <th class="border-b p-2 text-left">Время</th>
                  <th class="border-b p-2 text-left">Барбер</th>
                  <th class="border-b p-2 text-left">Услуга</th>
                  <th class="border-b p-2 text-left">Цена</th>
                </tr>
              </thead>
              <tbody>
            <tr v-for="(appointment, index) in filteredAppointments" :key="index" class="border-b">
              <td class="p-2">{{ appointment.date }}</td>
              <td class="p-2">{{ appointment.time }}</td>
              <td class="p-2">{{ appointment.worker_name }}</td>
              <td class="p-2">
                <ul>
                  <li v-for="(service, serviceIndex) in appointment.services" :key="serviceIndex">
                    {{ service.service_name }}
                  </li>
                </ul>
              </td>
              <td class="p-2">{{ appointment.total_price }} ₽</td>
            </tr>
          </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from './../../libs/axios';
import Profile from './ProfilesComponent/Profile.vue';
import { useRouter } from 'vue-router';

const router = useRouter();
const activeTab = ref('profile'); // Установите активную вкладку по умолчанию
const activeRecordTab = ref('active'); // Установите активную вкладку записей по умолчанию
const userImage = ref(''); // Инициализируем переменную для изображения пользователя
const userName = ref(''); // Инициализируем переменную для имени пользователя
const appointments = ref([]); // Список записей пользователя

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
    if (parsedData.user?.image){
        return `http://127.0.0.1:8000/storage/${parsedData.user?.image}`
    }
    else{
      return '../../../public/img/default.png'; // Если данных нет, возвращаем путь к default.png
    }
  }
  return './../../public/img/default.png'; // Если данных нет, возвращаем путь к default.png
};

const fetchAppointments = async () => {
  const userData = JSON.parse(localStorage.getItem('UserData'));
  const userId = userData.user?.id;

  if (userId) {
    try {
      const response = await axios.get(`api/visitors_records/${userId}`);
      appointments.value = response.data.response; // Сохраняем записи в appointments
      console.log(response.data.response);
    } catch (error) {
      console.error('Ошибка при получении записей:', error);
    }
  }
};

onMounted(() => {
  userName.value = getUserNameFromLocalStorage();
  userImage.value = getUserImageFromLocalStorage();
  fetchAppointments(); // Получаем записи при монтировании компонента
});

// Фильтрация записей на активные и неактивные
const filteredAppointments = computed(() => {
  const today = new Date().toISOString().split('T')[0]; // Получаем сегодняшнюю дату в формате YYYY-MM-DD

  if (Array.isArray(appointments.value)) {
    return appointments.value.filter(appointment => 
      (activeRecordTab.value === 'active' && appointment.date >= today) || 
      (activeRecordTab.value === 'inactive' && appointment.date < today)
    );
  } else {
    return []; // Если appointments не массив, возвращаем пустой массив
  }
});
</script>

<style>
.tabs {
  display: flex;
  margin-bottom: 10px;
}
</style>
