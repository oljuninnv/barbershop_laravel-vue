<template>
    <div>
      <h3 class="text-lg font-bold mb-4">Создание записи</h3>
      <div class="flex flex-col mb-4">

        <label for="barber" class="mb-2">Выберите барбера:</label>
        <select id="barberSelect" class="flex-1 mb-2 p-2 border rounded" v-model="selectedBarber">
          <option value="" disabled selected>Выберите барбера</option>
          <option v-for="barber in barbers" :key="barber.worker_id" :value="barber.worker_id">{{ barber.name }}</option>
        </select>

        <label for="datePicker" class="mb-2">Выберите дату:</label>
        <input type="date" id="datePicker" v-model="selectedDate" class="mb-2 p-2 border rounded" :min="minDate" :max="maxDate" />
        
        <label for="timeSelect" class="mb-2">Выберите время:</label>
        <select id="timeSelect" v-model="selectedTimes" multiple class="mb-2 p-2 border rounded" :disabled="isCheckboxChecked">
          <option v-for="time in times" :key="time" :value="time">{{ time }}</option>
        </select>
        <div class="flex items-center mb-4">
          <input type="checkbox" id="disableTime" v-model="isCheckboxChecked" @change="toggleTimeSelection" class="mr-2" />
          <label for="disableTime">Выбрать всё</label>
        </div>
        
        <button @click="addAppointment" class="bg-blue-500 text-white p-2 rounded">
          Добавить
        </button>
      </div>
  
      <!-- Модальное окно для сообщений -->
      <div v-if="modalVisible" class="modal">
        <div class="modal-content">
          <span class="close" @click="closeModal">&times;</span>
          <p>{{ modalMessage }}</p>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import axios from '../../../libs/axios';
  
  const barbers = ref([]);
  const selectedBarber = ref(null);
  const times = ['09:00', '10:40', '12:40', '14:20', '16:00', '17:40', '18:20', '19:50'];
  const selectedDate = ref('');
  const selectedTimes = ref([]);
  const isCheckboxChecked = ref(false);
  const modalVisible = ref(false);
  const modalMessage = ref('');
  const minDate = ref('');
  const maxDate = ref('');
  const workerId = ref('');
  
  // Устанавливаем минимальную и максимальную даты
  onMounted(() => {
    const today = new Date();
    minDate.value = today.toISOString().split('T')[0]; // Текущая дата
    const nextMonth = new Date();
    nextMonth.setMonth(today.getMonth() + 1);
    maxDate.value = nextMonth.toISOString().split('T')[0]; // Дата через месяц
  
    // Получаем worker_id из localStorage
    const userData = JSON.parse(localStorage.getItem('UserData'));
    workerId.value = userData?.worker?.id || null; // Получаем worker_id
    fetchData();
  });

  const fetchData = async () => {
  try {
    const response = await axios.get('/api/get_records_info');
    barbers.value = response.data.barbers;
    console.log(barbers.value);
  } catch (error) {
    console.error('Ошибка при загрузке данных:', error);
  }
};
  
const addAppointment = async () => {
  const today = new Date();
  const selected = new Date(selectedDate.value);

  // Проверяем, выбрана ли прошедшая дата
  if (selected < today) {
    modalMessage.value = 'Генерация записи может быть произведена в текущую или будущие даты.';
    modalVisible.value = true;
    return;
  }

  if (selectedDate.value && selectedTimes.value.length > 0) {
    try {
      const response = await axios.post('/api/generate-records', {
        worker_id: selectedBarber.value,
        date: selectedDate.value,
        times: selectedTimes.value,
      });
      modalMessage.value = response.data.message;
      modalVisible.value = true;
      resetForm();
    } catch (error) {
      modalMessage.value = error.response?.data?.message || 'Произошла ошибка при добавлении записи.';
      modalVisible.value = true;
    }
  } else {
    alert('Пожалуйста, выберите дату и хотя бы одно время.');
  }
};
  
  const resetForm = () => {
    selectedDate.value = '';
    selectedTimes.value = [];
    isCheckboxChecked.value = false;
  };
  
  const closeModal = () => {
    modalVisible.value = false;
    setTimeout(() => {
      modalVisible.value = false;
    }, 5000); // Закрытие модального окна через 5 секунд
  };
  
  const toggleTimeSelection = () => {
    if (isCheckboxChecked.value) {
      selectedTimes.value = ['09:00', '10:40', '12:40', '14:20', '16:00', '17:40', '18:20', '19:50']; 
  }};
  </script>
  
  <style scoped>
  .modal {
    display: flex;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    justify-content: center;
    align-items: center;
  }
  
  .modal-content {
    background-color: white;
    padding: 20px;
    border-radius: 5px;
    text-align: center;
  }
  
  .close {
    cursor: pointer;
    float: right;
  }
  </style>