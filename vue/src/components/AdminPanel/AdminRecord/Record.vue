<template>
    <div>
        <div class="flex gap-5 mb-5">
            <h2 class="text-[26px]">Выберите барбера:</h2>
            <select id="barberSelect" class="flex-1" v-model="selectedBarber" @change="fetchSchedule">
                <option value="" disabled selected>Выберите барбера</option>
                <option v-for="barber in barbers" :key="barber.worker_id" :value="barber.worker_id">{{ barber.name }}</option>
            </select>
        </div>

        <h3 class="text-lg font-bold mb-4">Расписание на текущую неделю (с {{ formatDate(currentWeekStart) }} по {{
            formatDate(currentWeekEnd) }})</h3>
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
                            <span v-if="getTimeStatus(currentWeekSchedule[day]?.[time]) == 'Занято'"
                                class="text-red-500">{{ getTimeStatus(currentWeekSchedule[day]?.[time]) }}</span>
                            <span v-if="getTimeStatus(currentWeekSchedule[day]?.[time]) == 'Свободно'"
                                class="text-green-500">{{ getTimeStatus(currentWeekSchedule[day]?.[time]) }}</span>
                            <span v-else class="text-gray-500"> </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <h3 class="text-lg font-bold mb-4">Расписание на следующую неделю (с {{ formatDate(nextWeekStart) }} по {{
            formatDate(nextWeekEnd) }})</h3>
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
                            <span v-if="getTimeStatus(nextWeekSchedule[day]?.[time]) == 'Занято'"
                                class="text-red-500">{{ getTimeStatus(nextWeekSchedule[day]?.[time]) }}</span>
                            <span v-if="getTimeStatus(nextWeekSchedule[day]?.[time]) == 'Свободно'"
                                class="text-green-500">{{ getTimeStatus(nextWeekSchedule[day]?.[time]) }}</span>
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
const selectedBarber = ref(null);
const barbers = ref([]);
const today = new Date();

const fetchData = async () => {
    try {
        const response = await axios.get('/api/get_records_info');
        barbers.value = response.data.barbers;
    } catch (error) {
        console.error('Ошибка при загрузке данных:', error);
    }
};

onMounted(() => {
    fetchData();
});

const currentWeekStart = computed(() => {
    const start = new Date(today);
    const day = start.getDay() || 7; // Понедельник = 1, Воскресенье = 7
    const diff = start.getDate() - day + 1; // Начало недели
    start.setDate(diff);
    start.setHours(0, 0, 0, 0); // Устанавливаем время на начало дня
    return start;
});

const currentWeekEnd = computed(() => {
    const end = new Date(today);
    const day = end.getDay() || 7; // Понедельник = 1, Воскресенье = 7
    const diff = end.getDate() + (7 - day); // Конец недели
    end.setDate(diff);
    end.setHours(23, 59, 59, 999); // Устанавливаем время на конец дня
    return end;
});

const nextWeekStart = computed(() => {
    const start = new Date(currentWeekEnd.value);
    start.setDate(start.getDate() + 1); // Начало следующей недели
    return start;
});

const nextWeekEnd = computed(() => {
    const end = new Date(nextWeekStart.value);
    end.setDate(end.getDate() + 6); // Конец следующей недели
    end.setHours(23, 59, 59, 999); // Устанавливаем время на конец дня
    return end;
});

const currentWeekSchedule = ref({});
const nextWeekSchedule = ref({});

const fetchSchedule = async () => {
    // Очистка предыдущих данных расписания
    currentWeekSchedule.value = {};
    nextWeekSchedule.value = {};

    if (!selectedBarber.value) {
        console.log('Барбер не выбран');
        return;
    }

    try {
        const response = await axios.get(`/api/barber_records/${selectedBarber.value}`);
        const records = response.data.response;
        console.log(records);

        // Обработка записей
        records.forEach(record => {
            const recordDate = new Date(record.date);
            const dayIndex = recordDate.getDay() || 7; // Понедельник = 1, Воскресенье = 7
            const day = days[dayIndex - 1]; // Преобразуем индекс в день

            const time = record.time.split(':').slice(0, 2).join(':');

            if (isCurrentWeek(recordDate)) {
                currentWeekSchedule.value[day] = currentWeekSchedule.value[day] || {};
                currentWeekSchedule.value[day][time] = record.user_name || record.user_id ? 'Занято' : 'Свободно';
            } else if (isNextWeek(recordDate)) {
                nextWeekSchedule.value[day] = nextWeekSchedule.value[day] || {};
                nextWeekSchedule.value[day][time] = record.user_name || record.user_id ? 'Занято' : 'Свободно';
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