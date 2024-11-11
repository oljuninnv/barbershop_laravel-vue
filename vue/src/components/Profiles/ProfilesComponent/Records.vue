<template>
    <div>
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
    </div>
  </template>
  
  <script setup>
  import { ref, computed } from 'vue';
  
  const times = ['9:00', '10:40', '12:40', '14:20', '16:00', '17:40', '18:20', '19:50'];
  const days = ['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс'];
  
  const today = new Date();
  const currentWeekStart = computed(() => {
    const start = new Date(today);
    const day = start.getDay();
    const diff = start.getDate() - day + (day === 0 ? -6 : 1);
    start.setDate(diff);
    return start.toLocaleDateString();
  });
  const currentWeekEnd = computed(() => {
    const end = new Date(today);
    const day = end.getDay();
    const diff = end.getDate() - day + (day === 0 ? 0 : 7);
    end.setDate(diff);
    return end.toLocaleDateString();
  });
  
  const isTimeAvailable = (day, time) => {
    const workingDays = ['Пн', 'Вт', 'Ср', 'Чт', 'Пт'];
    return workingDays.includes(day);
  };
  
  const getTimeStatus = (day, time) => {
    return 'Свободно'; // Пример статуса
  };
  </script>