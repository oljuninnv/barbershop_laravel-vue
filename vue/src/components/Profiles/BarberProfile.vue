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
          class="inline-block p-4 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300">Записи</a>
      </li>
      <li class="me-2">
        <a 
          href="#" 
          @click.prevent="activeTab = 'createAppointment'" 
          :class="{'border-b-2 border-blue-600 text-blue-600': activeTab === 'createAppointment', 'border-transparent': activeTab !== 'createAppointment'}"
          class="inline-block p-4 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300">Создание записи</a>
      </li>
    </ul>
  </div>

  <!-- Контент в зависимости от активной вкладки -->
  <div class="w-full mt-4">
    <div v-if="activeTab === 'profile'">
      <div class="flex flex-col">
        <input type="text" v-model="userName" :disabled="!isEditing" class="mb-2 p-2 border rounded" placeholder="Имя" />
        <input type="text" v-model="userLogin" :disabled="!isEditing" class="mb-2 p-2 border rounded" placeholder="Login" />
        <input type="email" v-model="userEmail" :disabled="!isEditing" class="mb-2 p-2 border rounded" placeholder="Email" />
        <input type="text" v-model="userPhone" :disabled="!isEditing" class="mb-2 p-2 border rounded" placeholder="Телефон" />
        <input type="file" v-if="isEditing" @change="handleFileUpload" class="mb-2" />
        <button @click="toggleEdit" class="bg-blue-500 text-white p-2 rounded mt-2">
          {{ isEditing ? 'Сохранить' : 'Изменить данные' }}
        </button>
      </div>
    </div>
    <div v-else-if="activeTab === 'appointments'">
      <h3 class="text-lg font-bold mb-4">Расписание на текущую неделю (с {{ currentWeekStart }} по {{ currentWeekEnd }})</h3>
      <div class="overflow-y-auto max-h-60 mb-4">
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

      <h3 class="text-lg font-bold mb-4">Расписание на следующую неделю (с {{ nextWeekStart }} по {{ nextWeekEnd }})</h3>
      <div class="overflow-y-auto max-h-60">
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
    <div v-else-if="activeTab === 'createAppointment'">
      <h3 class="text-lg font-bold mb-4">Создание записи</h3>
      <div class="flex flex-col mb-4">
        <label for="datePicker" class="mb-2">Выберите дату:</label>
        <input type="date" id="datePicker" v-model="selectedDate" class="mb-2 p-2 border rounded" />

        <label for="timeSelect" class="mb-2">Выберите время:</label>
        <select id="timeSelect" v-model="selectedTimes" multiple class="mb-2 p-2 border rounded" :disabled="isCheckboxChecked">
          <option v-for="time in times" :key="time" :value="time">{{ time }}</option>
        </select>

        <div class="flex items-center mb-4">
          <input type="checkbox" id="disableTime" v-model="isCheckboxChecked" class="mr-2" />
          <label for="disableTime">Отключить выбор времени</label>
        </div>

        <button @click="addAppointment" class="bg-blue-500 text-white p-2 rounded">
          Добавить
        </button>
      </div>
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
const userPhone = ref('89049562211');
const userLogin = ref('barber');

const times = ['9:00', '10:40', '12:40', '14:20', '16:00', '17:40', '18:20', '19:50'];
const days = ['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс'];

// Получение текущей даты
const today = new Date();
const currentWeekStart = computed(() => {
  const start = new Date(today);
  const day = start.getDay();
  const diff = start.getDate() - day + (day === 0 ? -6 : 1); // Пн - 1, Вс - 0
  start.setDate(diff);
  return start.toLocaleDateString();
});
const currentWeekEnd = computed(() => {
  const end = new Date(today);
  const day = end.getDay();
  const diff = end.getDate() - day + (day === 0 ? 0 : 7); // Пн - 1, Вс - 0
  end.setDate(diff);
  return end.toLocaleDateString();
});
const nextWeekStart = computed(() => {
  const start = new Date(currentWeekStart.value);
  start.setDate(start.getDate() + 7);
  return start.toLocaleDateString();
});
const nextWeekEnd = computed(() => {
  const end = new Date(currentWeekEnd.value);
  end.setDate(end.getDate() + 7);
  return end.toLocaleDateString();
});

// Переменные для создания записи
const selectedDate = ref(''); // Для выбранной даты
const selectedTimes = ref([]); // Массив для выбранных времён
const isCheckboxChecked = ref(false); // Состояние чекбокса

// Функция для переключения режима редактирования
const toggleEdit = () => {
  isEditing.value = !isEditing.value;
};

// Функция для обработки загрузки файла
const handleFileUpload = (event) => {
  const file = event.target.files[0];
  // Обработка файла (например, загрузка на сервер)
};

// Функция для добавления записи
const addAppointment = () => {
  if (selectedDate.value && selectedTimes.value.length > 0) {
    // Логика для добавления записи
    console.log(`Запись добавлена на ${selectedDate.value} с временными отрезками: ${selectedTimes.value.join(', ')}`);
    // Сброс выбранных значений
    selectedDate.value = '';
    selectedTimes.value = [];
    isCheckboxChecked.value = false;
  } else {
    alert('Пожалуйста, выберите дату и хотя бы одно время.');
  }
};

// Функция для проверки доступности времени
const isTimeAvailable = (day, time) => {
  // Здесь можно добавить логику для проверки, работает ли барбер в этот день и время
  const workingDays = ['Пн', 'Вт', 'Ср', 'Чт', 'Пт']; // Рабочие дни
  return workingDays.includes(day);
};

// Функция для получения статуса времени
const getTimeStatus = (day, time) => {
  // Генерация случайных занятых слотов
  const busySlots = generateRandomBusySlots(5); // Генерируем 5 случайных занятых слотов

  const currentDate = getCurrentDate(day);
  const isBusy = busySlots.some(slot => slot.date === currentDate && slot.time === time);
  
  // Логика для определения, свободно ли время
  if (isBusy) {
    return 'Забронировано';
  } else if (Math.random() < 0.3) { // Пример случайного заполнения пустых ячеек
    return ''; // Пустая ячейка
  }
  
  return 'Свободно';
};

// Функция для генерации случайных занятых слотов
const generateRandomBusySlots = (count) => {
  const busySlots = [];
  const startDate = new Date(); // Начальная дата (сегодня)
  const endDate = new Date();
  endDate.setDate(startDate.getDate() + 30); // Конечная дата (через 30 дней)

  // Увеличиваем количество занятых слотов
  const totalSlots = count * 3; // Генерируем в 3 раза больше слотов, чтобы увеличить вероятность занятости

  for (let i = 0; i < totalSlots; i++) {
    const randomDate = new Date(startDate.getTime() + Math.random() * (endDate - startDate));
    const formattedDate = randomDate.toISOString().split('T')[0]; // Формат YYYY-MM-DD
    const randomTime = times[Math.floor(Math.random() * times.length)]; // Случайное время из доступных

    if (Math.random() < 1) {
      busySlots.push({ date: formattedDate, time: randomTime });
    }
  }

  return busySlots;
};

// Функция для получения текущей даты в формате YYYY-MM-DD
const getCurrentDate = (day) => {
  const today = new Date();
  const currentDay = today.getDay(); // 0 - Вс, 1 - Пн, ..., 6 - Сб
  const dayIndex = days.indexOf(day);
  const dateOffset = dayIndex - currentDay;
  const targetDate = new Date(today);
  targetDate.setDate(today.getDate() + dateOffset);
  return targetDate.toISOString().split('T')[0]; // Возвращаем в формате YYYY-MM-DD
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