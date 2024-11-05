<template>
  <section class="p-5">
    <div class="rounded-xl relative mx-auto flex h-full w-full flex-col items-center p-[16px]">
      <div class="relative mt-1 flex h-32 w-full justify-center rounded-xl bg-cover" style='background-image: url("../public/img/after_header.svg");'>
        <div class="absolute -bottom-12 flex h-[88px] w-[88px] items-center justify-center rounded-full border-[4px] border-white bg-pink-400 overflow-hidden">
          <img class="h-full w-full object-cover rounded-full" src="/public/img/barbers/barber1.svg" alt="" />
        </div>
      </div>
      <div class="mt-16 flex flex-col items-center">
        <h4 class="text-bluePrimary text-xl font-bold text-center">{{ userName }}</h4>
        <p class="text-lightSecondary text-base font-normal text-center">Барбер</p>
      </div>

      <!-- Переключатель между вкладками -->
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
              @click.prevent="activeTab = 'appointments'" 
              :class="{'border-b-2 border-blue-600 text-blue-600': activeTab === 'appointments', 'border-transparent': activeTab !== 'appointments'}"
              class="inline-block p-4 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300">Расписание</a>
          </li>
        </ul>
      </div>

      <!-- Контент в зависимости от активной вкладки -->
      <div class="w-full mt-4">
        <div v-if="activeTab === 'profile'">
          <div class="flex flex-col">
            <input type="text" v-model="userName" :disabled="!isEditing" class="mb-2 p-2 border rounded"
              placeholder="Имя" />
            <input type="text" v-model="userEmail" :disabled="!isEditing" class="mb-2 p-2 border rounded"
              placeholder="Email" />
            <input type="text" v-model="userPhone" :disabled="!isEditing" class="mb-2 p-2 border rounded"
              placeholder="Телефон" />
            <input type="file" v-if="isEditing" @change="handleFileUpload" class="mb-2" />
            <button @click="toggleEdit" class="bg-red-500 text-white p-2 rounded mt-2">
              {{ isEditing ? 'Сохранить' : 'Изменить данные' }}
            </button>
          </div>
        </div>
        <div v-else>
          <table class="w-full border-collapse">
            <thead>
              <tr>
                <th class="border-b p-2 text-left">Время</th>
                <th class="border-b p-2 text-left" v-for="day in days" :key="day">{{ day }}</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="time in times" :key="time">
                <td class="border-b p-2">{{ time }}</td>
                <td v-for="day in days" :key="day" class="border-b p-2">
                  <span v-if="isTimeAvailable(day, time)">{{ getTimeStatus(day, time) }}</span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, computed } from 'vue';

const activeTab = ref('appointments'); // Установите активную вкладку по умолчанию
const isEditing = ref(false); // Состояние редактирования
const userName = ref('Константин Назаров');
const userEmail = ref('example@example.com');
const userPhone = ref('89234567721');

const appointments = ref([
  { date: '2023-10-01', time: '10:00', service: 'Стрижка', price: 1000 },
  { date: '2023-10-02', time: '12:00', service: 'Укладка', price: 800 },
]);

const times = ['9:00', '10:40', '12:40', '14:20', '16:00', '17:40', '18:20', '19:50'];
const days = ['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс'];

// Функция для переключения режима редактирования
const toggleEdit = () => {
  isEditing.value = !isEditing.value;
};

// Функция для обработки загрузки файла
const handleFileUpload = (event) => {
  const file = event.target.files[0];
  // Обработка файла (например, загрузка на сервер)
};

// Функция для проверки доступности времени
const isTimeAvailable = (day, time) => {
  // Здесь можно добавить логику для проверки, работает ли барбер в этот день и время
  // Например, если барбер не работает в выходные
  const workingDays = ['Пн', 'Вт', 'Ср', 'Чт', 'Пт']; // Рабочие дни
  return workingDays.includes(day);
};

// Функция для получения статуса времени
const getTimeStatus = (day, time) => {
  const appointment = appointments.value.find(a => a.date === getCurrentDate(day) && a.time === time);
  if (appointment) {
    return 'Забронировано';
  }
  return isTimeAvailable(day, time) ? 'Свободно' : '';
};

// Функция для получения текущей даты в формате YYYY-MM-DD
const getCurrentDate = (day) => {
  const today = new Date();
  const currentDay = today.getDay(); // 0 - Вс, 1 - Пн, ..., 6 - Сб
  const dayIndex = days.indexOf(day);
  const dateOffset = dayIndex - currentDay;
  const targetDate = new Date(today);
  targetDate.setDate(today.getDate() + dateOffset);
  return targetDate.toISOString().split('T')[0]; // Возвращает дату в формате YYYY-MM-DD
};
</script>

<style>
/* Добавьте стили для таблицы, если необходимо */
table {
  border-collapse: collapse;
  width: 100%;
}

th, td {
  border: 1px solid #ddd;
  padding: 8px;
  text-align: left;
}
</style>
  
  