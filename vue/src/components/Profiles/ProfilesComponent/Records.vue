<template>
  <div>
    <h3 class="text-lg font-bold mb-4">Расписание на текущую неделю (с {{ formatDate(currentWeekStart) }} по {{ formatDate(currentWeekEnd) }})</h3>
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
              <span v-if="getTimeStatus(currentWeekSchedule[day]?.[time]) == 'Занято'" class="text-red-500">{{ getTimeStatus(currentWeekSchedule[day]?.[time]) }}</span>
              <span v-if="getTimeStatus(currentWeekSchedule[day]?.[time]) == 'Свободно'" class="text-green-500">{{ getTimeStatus(currentWeekSchedule[day]?.[time]) }}</span>
              <span v-else class="text-gray-500"> </span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <h3 class="text-lg font-bold mb-4">Расписание на следующую неделю (с {{ formatDate(nextWeekStart) }} по {{ formatDate(nextWeekEnd) }})</h3>
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
              <span v-if="getTimeStatus(nextWeekSchedule[day]?.[time]) == 'Занято'" class="text-red-500">{{ getTimeStatus(nextWeekSchedule[day]?.[time]) }}</span>
              <span v-if="getTimeStatus(nextWeekSchedule[day]?.[time]) == 'Свободно'" class="text-green-500">{{ getTimeStatus(nextWeekSchedule[day]?.[time]) }}</span>
              <span v-else class="text-gray-500"> </span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import axios from '../../../libs/axios';

const times = ['09:00', '10:40', '12:40', '14:20', '16:00', '17:40', '18:20', '19:50'];
const days = ['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс'];

const today = new Date();

const currentWeekStart = computed(() => {
  const start = new Date(today);
  const day = start.getDay();
  const diff = start.getDate() - day + (day === 0 ? -6 : 1);
  start.setDate(diff);
  return start;
});

const currentWeekEnd = computed(() => {
  const end = new Date(today);
  const day = end.getDay();
  const diff = end.getDate() - day + (day === 0 ? 0 : 7);
  end.setDate(diff);
  return end;
});

const nextWeekStart = computed(() => {
  const start = new Date(today);
  start.setDate(start.getDate() + 7 - start.getDay());
  return start;
});

const nextWeekEnd = computed(() => {
  const end = new Date(today);
  end.setDate(end.getDate() + 13 - end.getDay());
  return end;
});

const currentWeekSchedule = ref({});
const nextWeekSchedule = ref({});

const getBarberId = () => {
  const userData = JSON.parse(localStorage.getItem('UserData'));
  return userData?.worker?.id;
};

const fetchSchedule = async () => {
  const barberId = getBarberId();
  try {
    const response = await axios.get(`/api/barber_records/${barberId}`);
    const records = response.data.response;
    console.log(records);

    // Обработка записей
    records.forEach(record => {
      const recordDate = new Date(record.date);
      const dayIndex = recordDate.getDay();
      const day = days[dayIndex === 0 ? 6 : dayIndex - 1];

      const time = record.time.split(':').slice(0, 2).join(':');

      if (isCurrentWeek(recordDate)) {
        currentWeekSchedule.value[day] = currentWeekSchedule.value[day] || {};
        if (record.user_name != null || record.user_id != null) {
          currentWeekSchedule.value[day][time] = 'Занято';
          console.log(`Занято на текущей неделе: ${day} ${time} - ${record.user_name || 'Пользователь без имени'}`);
        }
        else{
          currentWeekSchedule.value[day][time] = 'Свободно';
          console.log(`Свободно на текущей неделе: ${day} ${time} - ${record.user_name || 'Пользователь без имени'}`);
        }
      } else if (isNextWeek(recordDate)) {
        nextWeekSchedule.value[day] = nextWeekSchedule.value[day] || {};
        if (record.user_name != null || record.user_id != null) {
          nextWeekSchedule.value[day][time] = 'Занято';
          console.log(`Занято на следующей неделе: ${day} ${time} - ${record.user_name || 'Пользователь без имени'}`);
        }
        else{
          nextWeekSchedule.value[day][time] = 'Свободно';
          console.log(`Свободно на следующей неделе: ${day} ${time} - ${record.user_name || 'Пользователь без имени'}`);
        }
      }
    });
  } catch (error) {
    console.error('Ошибка при получении расписания:', error);
  }
};

const isCurrentWeek = (date) => {
  const start = currentWeekStart.value;
  const end = currentWeekEnd.value;
  return date >= start && date <= end;
};

const isNextWeek = (date) => {
  const start = nextWeekStart.value;
  const end = nextWeekEnd.value;
  return date >= start && date <= end;
};

const getTimeStatus = (status) => {
  if (status === 'Занято') {
    return 'Занято';
  }
  else if (status === 'Свободно') {
    return 'Свободно';
  }
  else {
    return '';
  }
};

const formatDate = (date) => {
  return date.toLocaleDateString();
};

onMounted(fetchSchedule);
</script>