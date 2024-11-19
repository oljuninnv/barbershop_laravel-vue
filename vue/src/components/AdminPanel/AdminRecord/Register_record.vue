<template>
    <div>
      <header class="text-white bg-red-500 text-center text-[20px] p-4">
        Страница записи на приём
      </header>
      <main class="bg-[#EBE9E9] flex flex-col justify-center items-center w-full min-h-screen px-4 md:px-10 lg:px-20">
        <!-- Выбор барбера -->
        <section class="barbers-list w-full flex flex-col md:flex-row gap-5 mt-5">
          <h2 class="text-[26px]">Выберите барбера:</h2>
          <select id="barberSelect" class="flex-1" v-model="selectedBarber">
            <option value="" disabled selected>Выберите барбера</option>
            <option v-for="barber in barbers" :key="barber.worker_id" :value="barber.worker_id">{{ barber.name }}</option>
          </select>
        </section>
  
        <!-- Выбор услуг -->
        <section class="services w-full mt-5">
          <h2 class="text-[26px] mb-5">Услуги:</h2>
          <div class="overflow-y-auto max-h-80">
            <div class="flex flex-col gap-5">
              <div
                class="service-card flex rounded-lg bg-white text-surface shadow-secondary-1 dark:bg-surface-dark dark:text-white p-4"
                v-for="service in services" :key="service.id">
                <img v-if="service.image" class="rounded-t-lg h-[80px] mr-4" :src="service.image" :alt="service.name" />
                <img v-if="!service.image" class="rounded-t-lg h-[80px] mr-4" src="../../../../public/img/styling.svg" :alt="service.name" />
                <div class="flex flex-col justify-between w-full">
                  <h5 class="mb-2 text-xl font-medium leading-tight">{{ service.name }}</h5>
                  <div class="text-[18px]">{{ service.price }} Руб</div>
                  <button @click="toggleService(service)"
                    :class="{ 'bg-green-500 text-white': selectedServices.includes(service.id), 'bg-blue-500 text-white': !selectedServices.includes(service.id) }">
                    {{ selectedServices.includes(service.id) ? 'Отменить' : 'Добавить' }}
                  </button>
                </div>
              </div>
            </div>
          </div>
          <div class="text-[18px] font-bold mt-4">Итоговая стоимость: {{ totalPrice }} Руб</div>
        </section>
  
        <!-- Выбор даты -->
        <section class="date-picker w-full mt-5">
          <h2 class="text-[26px] mb-5">Выберите дату</h2>
          <input class="input_text w-full" type="date" :min="minDate" :max="maxDate" v-model="selectedDate" @change="fetchAvailableRecords" />
        </section>
  
        <!-- Выбор времени -->
        <section class="time-picker w-full mt-5">
          <h2 class="text-[26px] mb-5">Выберите время</h2>
      <div class="time-buttons flex flex-wrap gap-2">
        <button 
          v-for="time in times" 
          :key="time" 
          :class="{'bg-red-500': selectedTime === time, 'bg-white': selectedTime !== time && !isTimeTaken(time)}"
          @click="selectTime(time)">
          {{ time }}
        </button>
      </div>
        </section>
  
        <!-- Кнопки управления -->
        <div class="controls flex flex-col md:flex-row gap-5 mt-5 w-full mb-5">
          <button class="bg-[#000000] text-white p-5 rounded flex-1" @click="goBack">Вернуться обратно</button>
          <button class="bg-[#000000] text-white p-5 rounded flex-1" @click="addAppointment">Добавить запись</button>
        </div>
  
        <!-- Модальное окно -->
        <div v-if="showModal" class="modal-overlay">
          <div class="modal-content text-center text-[20px]">
            <h2 class="mb-5">Введите данные пользователя</h2>
            <div class="flex flex-col gap-5">
              <input class="input_text" type="text" placeholder="Имя" v-model="userName" />
              <input class="input_text" type="email" placeholder="Почта" v-model="userEmail" />
              <input class="input_text" type="tel" placeholder="Телефон" v-model="userPhone" />
            </div>
            <div class="flex gap-5 mt-5 justify-center">
              <button class="bg-[#000000] text-white p-2 rounded" @click="submitAppointment">Подтвердить</button>
              <button class="bg-[#000000] text-white p-2 rounded" @click="closeModal">Отмена</button>
            </div>
            
          </div>
        </div>
      </main>
    </div>
  </template>
  
  <script setup>
  import { ref, computed, onMounted, watch } from 'vue';
  import { useRouter } from 'vue-router';
  import axios from '../../../libs/axios';; 
  
  const router = useRouter();
  const barbers = ref([]);
  const services = ref([]);
  const selectedServices = ref([]);
  const selectedBarber = ref(null);
  const selectedDate = ref(null);
  const selectedTime = ref(null);
  const showModal = ref(false);
  const userName = ref('');
  const userEmail = ref('');
  const userPhone = ref('');
  const minDate = ref(new Date().toISOString().split('T')[0]);
  const maxDate = ref(new Date(new Date().setMonth(new Date().getMonth() + 1)).toISOString().split('T')[0]);
  const times = ref(['9:00:00', '10:40:00', '12:40:00', '14:20:00', '16:00:00', '17:40:00', '18:20:00', '19:50:00']);
  const takenTimes = ref([]);
  const selectedRecord = ref(null);
  const records = ref([]);
  const totalPrice = computed(() => {
    return services.value.reduce((total, service) => {
      return Number(total) + (selectedServices.value.includes(service.id) ? Number(service.price) : 0);
    }, 0);
  });
  
  const fetchData = async () => {
    try {
      const response = await axios.get('/api/get_records_info');
      barbers.value = response.data.barbers;
      services.value = response.data.services;
    } catch (error) {
      console.error('Ошибка при загрузке данных:', error);
    }
  };
  
  const toggleService = (service) => {
    console.log(service.name);
    const index = selectedServices.value.indexOf(service.id);
    if (index > -1) {
      selectedServices.value.splice(index, 1);
    } else {
      selectedServices.value.push(service.id);
    }
  };
  
  const fetchAvailableRecords = async (workerId) => {
    console.log(workerId);
    try {
        const response = await axios.get('/api/available-records', {
            params: {
                date: selectedDate.value,
                worker_id: selectedBarber.value,
            }
        });
        
        // Очищаем массив times перед обновлением
        times.value = [];
        records.value = response.data;

        // Получаем уникальные времена
        const allTimes = [...new Set(response.data.map(element => element.time))];

        // Получаем текущую дату и время
        const currentDateTime = new Date();

        // Получаем текущую дату без времени
        const currentDate = new Date(new Date().setHours(0, 0, 0, 0));
        const selectedDateObj = new Date(selectedDate.value);

        // Фильтруем времена в зависимости от выбранной даты
        if (selectedDateObj < currentDate) {
            // Если выбрана прошедшая дата, не выводим время
            times.value = []; // Очищаем времена
        } else if (selectedDateObj.toDateString() === currentDate.toDateString()) {
            // Если выбрана текущая дата, не выводим прошедшее время
            times.value = allTimes.filter(time => {
                const appointmentDateTime = new Date(`${selectedDate.value}T${time}`);
                return appointmentDateTime >= currentDateTime; // Оставляем только будущие времена
            });
        } else {
            // Если выбрана будущая дата, выводим все времена
            times.value = allTimes;
        }

        times.value.sort(); // Сортируем доступные времена

        takenTimes.value = []; // Очищаем занятые времена
        selectedTime.value = null; // Сбрасываем выбранное время
    } catch (error) {
        console.error('Ошибка при загрузке доступных записей:', error);
    }
}
  
  watch([() => selectedBarber.value, () => selectedDate.value], () => {
    console.log(selectedBarber.value);
    fetchAvailableRecords(selectedBarber.value);
  });
  
  const selectTime = (time) => {
    console.log(time);
    
    if (!isTimeTaken(time)) {
      selectedTime.value = time; // Устанавливаем выбранное время
    } else {
      console.log('Это время занято');
    }
  };
  
  const isTimeTaken = (time) => {
    return takenTimes.value.includes(time);
  };
  
  const goBack = () => {
    router.push('/');
  };
  
  const addAppointment = () => {
    if (!selectedBarber.value) {
      alert('Пожалуйста, выберите барбера.');
      return;
    }
    if (!selectedDate.value) {
      alert('Пожалуйста, выберите дату.');
      return;
    }
    if (!selectedTime.value) {
      alert('Пожалуйста, выберите время.');
      return;
    }
    if (selectedServices.value.length === 0) {
      alert('Пожалуйста, выберите хотя бы одну услугу.');
      return;
    }
      showModal.value = true;
  };
  
  const closeModal = () => {
    showModal.value = false;
  };
  
  const submitAppointment = async () => {

    selectedRecord.value = records.value.find(i => i.time == selectedTime.value)
  
    const appointmentData = {
      worker_id: selectedBarber.value,
      date: selectedDate.value,
      time: selectedTime.value,
      services: selectedServices.value,
    };
  
    appointmentData.user_name = userName.value;
    appointmentData.user_email = userEmail.value;
    appointmentData.user_phone = userPhone.value;
  
    console.log(appointmentData);
  
    try {
      await axios.put(`/api/records_update/${selectedRecord.value.id}`, appointmentData);
      alert("Запись успешно добавлена")
      window.location.reload()
    } catch (error) {
      console.error('Ошибка при добавлении записи:', error);
    }
  };
  
  onMounted(() => {
    fetchData();
  });
  </script>
  
  <style scoped>
  .barbers-list ul {
    list-style-type: none;
    padding: 0;
  }
  .service-card {
    background-color: white;
    padding: 10px;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  }
  .modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.5); /* Затемнение фона */
    display: flex;
    justify-content: center; /* Центрирование по горизонтали */
    align-items: center; /* Центрирование по вертикали */
    z-index: 1000; /* Убедитесь, что модальное окно выше других элементов */
  }
  
  .modal-content {
    background-color: white; /* Цвет фона модального окна */
    padding: 20px;
    border-radius: 8px; /* Закругленные углы */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Тень для модального окна */
    width: 300px; /* Ширина модального окна */
  }
  </style>