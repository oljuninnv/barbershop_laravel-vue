<template>
    <div>
        <div class="flex gap-5 mb-5">
            <h2 class="text-[26px]">Выберите барбера:</h2>
            <select id="barberSelect" class="flex-1" v-model="selectedBarber" @change="fetchSchedule">
                <option value="" disabled selected>Выберите барбера</option>
                <option v-for="barber in barbers" :key="barber.worker_id" :value="barber.worker_id">{{ barber.name }}
                </option>
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
                        <td v-for="day in days" :key="day" class="border-b p-2"
                            @click="showModal(currentWeekSchedule[day]?.[time])">
                            <span v-if="getTimeStatus(currentWeekSchedule[day]?.[time]) == 'Занято'"
                                class="text-red-500 cursor-pointer">{{ getTimeStatus(currentWeekSchedule[day]?.[time])
                                }}</span>
                            <span v-if="getTimeStatus(currentWeekSchedule[day]?.[time]) == 'Свободно'"
                                class="text-green-500 cursor-pointer">{{ getTimeStatus(currentWeekSchedule[day]?.[time])
                                }}</span>
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
                        <td v-for="day in days" :key="day" class="border-b p-2"
                            @click="showModal(nextWeekSchedule[day]?.[time])">
                            <span v-if="getTimeStatus(nextWeekSchedule[day]?.[time]) === 'Занято'"
                                class="text-red-500 cursor-pointer">{{ getTimeStatus(nextWeekSchedule[day]?.[time]) }}
                            </span>
                            <span v-if="getTimeStatus(nextWeekSchedule[day]?.[time]) === 'Свободно'"
                                class="text-green-500 cursor-pointer">{{ getTimeStatus(nextWeekSchedule[day]?.[time])
                                }}</span>
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
                <p><strong>Заказ выполнен:</strong> {{ modalData.is_finished === null || modalData.is_finished === '' ||
                    modalData.is_finished === false ? 'нет' : 'да' }}</p>
                <p><strong>Услуги:</strong></p>
                <ul>
                    <li v-for="service in modalData.services" :key="service">{{ service }}</li>
                </ul>
                <div class="flex gap-5 mt-2">
                    <button @click="deleteRecord(modalData.id)" class="mt-4 bg-red-500 text-white px-4 py-2 rounded">
                        Удалить запись
                    </button>
                    <button v-if="modalData.user_name !== null" @click="deleteUserFromRecord(modalData.id)"
                        class="mt-4 bg-red-500 text-white px-4 py-2 rounded">
                        Удалить пользователя из записи
                    </button>
                </div>
                <div class="flex gap-5 mt-2">
                    <button @click="isModalVisible = false"
                        class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">Закрыть</button>
                    <button v-if="!modalData.is_finished" @click="markAsFinished(modalData)"
                        class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">
                        Добавить в выполненные
                    </button>
                </div>
            </div>
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

const isModalVisible = ref(false);
const modalData = ref({
    id: '',
    status: '',
    barber: '',
    total_price: '',
    date: '',
    time: '',
    user_name: '',
    user_phone: '',
    is_finished: '',
    services: []
});

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
                currentWeekSchedule.value[day][time] = record;
                console.log(currentWeekSchedule.value[day][time]);
            } else if (isNextWeek(recordDate)) {
                console.log(recordDate);

                nextWeekSchedule.value[day] = nextWeekSchedule.value[day] || {};
                nextWeekSchedule.value[day][time] = record;
                console.log(nextWeekSchedule.value[day][time]);
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
    console.log(record.is_finished)

    modalData.value = {
        id: record.id,
        status: getTimeStatus(record),
        barber: record.worker_name,
        total_prcie: record.total_price,
        date: record.date,
        time: record.time,
        user_name: record.user_name,
        user_phone: record.user_phone,
        services: record.services,
        is_finished: record.is_finished
    };
    if (!modalData.value.total_price) {
        modalData.value.total_price = record.total_price;
    }

    if (!modalData.value.is_finished) {
        console.log(modalData.is_finished);
        modalData.value.is_finished = '';
    }

    isModalVisible.value = true;
};

const formatDate = (date) => {
    return date.toLocaleDateString();
};

const markAsFinished = async (record) => {
    console.log(record);

    // Создаем объект Date из даты и времени
    const appointmentDateTime = new Date(`${record.date}T${record.time}`); // Формат YYYY-MM-DDTHH:mm:ss
    const currentDateTime = new Date(); // Получаем текущее время

    // Проверяем, что дата и время записи больше или равны текущему времени
    if (appointmentDateTime > currentDateTime) {
        alert('Нельзя завершать будущие заказы!'); // Выводим сообщение об ошибке
        return; // Прерываем выполнение метода
    }
    console.log(currentDateTime);

    try {
        const response = await axios.post(`/api/records_finished/${record.id}`, {
            is_finished: 1
        });
        console.log('Запись обновлена:', response.data);

        // Обновляем состояние модальных данных
        modalData.is_finished = 1; // Обновляем локальное состояние

        // Закрываем модальное окно
        isModalVisible.value = false;
        fetchSchedule();
    } catch (error) {
        console.error('Ошибка при обновлении записи:', error);
    }
}

const deleteRecord = (id) => {
    if (confirm('Вы уверены, что хотите удалить эту запись?')) {
        axios.delete(`api/records/${id}`)
            .then(response => {
                console.log(response.data.message);
                isModalVisible.value = false; // Закрываем модальное окно
                fetchSchedule();
            })
            .catch(error => {
                console.error('Ошибка при удалении записи:', error.response.data.error);
                alert('Ошибка при удалении записи: ' + error.response.data.error);
            });
    }
};

const deleteUserFromRecord = (id) => {
    if (confirm('Вы уверены, что хотите удалить пользователя из записи?')) {
        axios.delete(`api/del_user_records/${id}`)
            .then(response => {
                console.log(response.data.message);
                isModalVisible.value = false; // Закрываем модальное окно
                fetchSchedule();
            })
            .catch(error => {
                console.error('Ошибка при удалении пользователя из записи:', error.response.data.error);
                alert('Ошибка при удалении пользователя: ' + error.response.data.error);
            });
    }
};

onMounted(fetchSchedule);
</script>