<template>
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
                        <td v-for="day in days" :key="day" class="border-b p-2" @click="showModal(currentWeekSchedule[day]?.[time])">
                            <span v-if="getTimeStatus(currentWeekSchedule[day]?.[time]) == 'Занято'" class="text-red-500 cursor-pointer">{{ getTimeStatus(currentWeekSchedule[day]?.[time]) }}</span>
                            <span v-if="getTimeStatus(currentWeekSchedule[day]?.[time]) == 'Свободно'" class="text-green-500 cursor-pointer">{{ getTimeStatus(currentWeekSchedule[day]?.[time]) }}</span>
                            <span v-else> </span>
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
                        <td v-for="day in days" :key="day" class="border-b p-2" @click="showModal(nextWeekSchedule[day]?.[time])">
                            <span v-if="getTimeStatus(nextWeekSchedule[day]?.[time]) === 'Занято'" class="text-red-500 cursor-pointer">{{ getTimeStatus(nextWeekSchedule[day]?.[time]) }}</span>
                            <span v-if="getTimeStatus(nextWeekSchedule[day]?.[time]) === 'Свободно'" class="text-green-500 cursor-pointer">{{ getTimeStatus(nextWeekSchedule[day]?.[time]) }}</span>
                            <span v-else class="text-gray-500"> </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Modal -->
        <div v-if="isModalVisible" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center">
            <div class="bg-white p-5 rounded shadow-lg">
                <h2 class="text-xl font-bold mb-4">Детали записи</h2>
                <p><strong>Статус:</strong> {{ modalData.status }}</p>
                <p><strong>Барбер:</strong> {{ modalData.barber }}</p>
                <p><strong>Имя посетителя:</strong> {{ modalData.user_name }}</p>
                <p><strong>Номер посетителя:</strong> {{ modalData.user_phone }}</p>
                <p><strong>Итоговая стоимость:</strong> {{ String(modalData.total_price) }}</p>
                <p><strong>Дата:</strong> {{ modalData.date }}</p>
                <p><strong>Время:</strong> {{ modalData.time }}</p>
                <p><strong>Услуги:</strong></p>
                <ul>
                    <li v-for="service in modalData.services" :key="service">{{ service }}</li>
                </ul>
                <button @click="isModalVisible = false" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">Закрыть</button>
            </div>
        </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import axios from '../../../libs/axios';

const isModalVisible = ref(false);
const modalData = ref({
    status: '',
    barber: '',
    total_price: '',
    date: '',
    time: '',
    user_name:'',
    user_phone:'',
    services: []
});

const times = ['09:00', '10:40', '12:40', '14:20', '16:00', '17:40', '18:20', '19:50'];
const days = ['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс'];

const today = new Date();

const getBarberId = () => {
  const userData = JSON.parse(localStorage.getItem('UserData'));
  return userData?.worker?.id;
};

const currentWeekSchedule = ref({});
const nextWeekSchedule = ref({});

const fetchSchedule = async () => {

  const barberId = getBarberId();

    try {
        const response = await axios.get(`/api/barber_records/${barberId}`);
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
              currentWeekSchedule.value[day][time] = record;
                console.log(currentWeekSchedule.value[day][time]);
            } else if (isNextWeek(recordDate)) {
                console.log(currentWeekSchedule.value[day][time]);
                
                nextWeekSchedule.value[day] = nextWeekSchedule.value[day] || {};
                nextWeekSchedule.value[day][time] = record;
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
    console.log(start);
    start.setHours(0)
    start.setMinutes(0)
    return start;
});

const nextWeekEnd = computed(() => {
    const end = new Date(nextWeekStart.value);
    end.setDate(end.getDate() + 6); // Конец следующей недели
    end.setHours(23, 59, 59, 999); // Устанавливаем время на конец дня
    return end;
});

const getTimeStatus = (record) => {
    if (record && (record.user_name || record.user_id)) {
        return 'Занято';
    } else if (record && (!record.user_name || !record.user_id)) {
    return 'Свободно';
  }
  else {
    return '';
  }
};

const showModal = (record) => {
    if (!record) return;

    modalData.value = {
        status: getTimeStatus(record),
        barber: record.worker_name,
        total_prcie: record.total_price,
        date: record.date,
        time: record.time,
        user_name: record.user_name,
        user_phone: record.user_phone,
        services: record.services
    };
    if(!modalData.value.total_price) {
        modalData.value.total_price  = record.total_price;
    }

    isModalVisible.value = true;
};

const formatDate = (date) => {
  return date.toLocaleDateString();
};

onMounted(fetchSchedule);
</script>