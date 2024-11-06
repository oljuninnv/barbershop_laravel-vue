<template>
  <section class="p-5">
    <div class="rounded-xl relative mx-auto flex h-full w-full flex-col items-center p-[16px]">
      <div class="relative mt-1 flex h-32 w-full justify-center rounded-xl bg-cover"
        style='background-image: url("../public/img/after_header.svg");'>
        <div
          class="absolute -bottom-12 flex h-[88px] w-[88px] items-center justify-center rounded-full border-[4px] border-white bg-pink-400 overflow-hidden">
          <img class="h-full w-full object-cover rounded-full" src="/public/img/barbers/barber1.svg" alt="" />
        </div>
      </div>
      <div class="mt-16 flex flex-col items-center">
        <h4 class="text-bluePrimary text-xl font-bold text-center">{{ userName }}</h4>
        <p class="text-lightSecondary text-base font-normal text-center">{{ userRole }}</p>
      </div>

      <!-- Переключатель между вкладками -->
      <div
        class="text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:text-gray-400 dark:border-gray-700 w-full">
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
          <div class="flex flex-col">
            <input type="text" v-model="userName" :disabled="!isEditing" class="mb-2 p-2 border rounded"
              placeholder="Имя" />
            <input type="text" v-model="userEmail" :disabled="!isEditing" class="mb-2 p-2 border rounded"
              placeholder="Email" />
            <input type="text" v-model="userPhone" :disabled="!isEditing" class="mb-2 p-2 border rounded"
              placeholder="Телефон" />
            <input type="text" v-model="userCity" :disabled="!isEditing" class="mb-2 p-2 border rounded"
              placeholder="Город" />
            <input type="file" v-if="isEditing" @change="handleFileUpload" class="mb-2" />
            <button @click="toggleEdit" class="bg-red-500 text-white p-2 rounded mt-2">
              {{ isEditing ? 'Сохранить' : 'Изменить данные' }}
            </button>
          </div>
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
            <table class="w-full border-collapse ">
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
                <tr v-for="(appointment, index) in filteredAppointments" :key="index"
                  :class="{ 'text-gray-400': !appointment.active, 'text-black': appointment.active }" class="border-b">
                  <td class="p-2">{{ appointment.date }}</td>
                  <td class="p-2">{{ appointment.time }}</td>
                  <td class="p-2">{{ appointment.master }}</td>
                  <td class="p-2">{{ appointment.service }}</td>
                  <td class="p-2">{{ appointment.price }} ₽</td>
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
import { ref, computed } from 'vue';

const activeTab = ref('profile'); // Установите активную вкладку по умолчанию
const activeRecordTab = ref('active'); // Установите активную вкладку записей по умолчанию
const isEditing = ref(false); // Состояние редактирования
const userName = ref('Константин Назаров');
const userEmail = ref('example@example.com');
const userRole = ref('Гость');
const userPhone = ref('89069081178');
const userCity = ref('Кемерово');

const appointments = ref([
  { date: '2024-11-05', time: '20:00', service: 'Стрижка', price: 1000, master: 'Константин Назаров', active: true },
  { date: '2023-10-02', time: '12:00', service: 'Укладка', price: 800, master: 'Константин Назаров', active: false },
]);

// Функция для проверки, является ли дата прошедшей
const isPastDate = (dateString) => {
  const appointmentDate = new Date(dateString);
  const currentDate = new Date();
  return appointmentDate < currentDate;
};

// Вычисляемое свойство для фильтрации записей
const filteredAppointments = computed(() => {
  return appointments.value.filter(appointment => 
    (activeRecordTab.value === 'active' && appointment.active) || 
    (activeRecordTab.value === 'inactive' && !appointment.active)
  );
});

// Функция для переключения режима редактирования
const toggleEdit = () => {
  isEditing.value = !isEditing.value;
};

// Функция для обработки загрузки файла
const handleFileUpload = (event) => {
  const file = event.target.files[0];
  // Обработка файла (например, загрузка на сервер)
};

// Сохранение данных пользователя (можно добавить логику для сохранения)
</script>

<style>
.tabs {
  display: flex;
  margin-bottom: 10px;
}
</style>