<template>
    <div>
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
  </template>
  
  <script setup>
  import { ref } from 'vue';
  
  const times = ['09:00', '10:40', '12:40', '14:20', '16:00', '17:40', '18:20', '19:50'];
  const selectedDate = ref('');
  const selectedTimes = ref([]);
  const isCheckboxChecked = ref(false);
  
  const addAppointment = () => {
    if (selectedDate.value && selectedTimes.value.length > 0) {
      console.log(`Запись добавлена на ${selectedDate.value} с временными отрезками: ${selectedTimes.value.join(', ')}`);
      selectedDate.value = '';
      selectedTimes.value = [];
      isCheckboxChecked.value = false;
    } else {
      alert('Пожалуйста, выберите дату и хотя бы одно время.');
    }
  };
  </script>