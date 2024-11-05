<template>
  <div>
    <header class="text-white bg-red-500 text-center text-[20px] p-4">
      Страница записи на приём
    </header>
    <main class="bg-[#EBE9E9] flex flex-col justify-center items-center w-full min-h-screen px-4 md:px-10 lg:px-20">
      <section class="barbers-list w-full flex flex-col md:flex-row gap-5 mt-5">
        <h2 class="text-[26px]">Выберите барбера:</h2>
        <select id="barberSelect" class="flex-1" v-model="selectedBarber">
          <option value="" disabled selected>Выберите барбера</option>
          <option value="Константин Назаров">Константин Назаров</option>
          <option value="Максим Петров">Максим Петров</option>
          <option value="Никита Кузнецов">Никита Кузнецов</option>
        </select>
      </section>

      <!-- Выбор услуг -->
      <section class="services w-full mt-5">
        <h2 class="text-[26px] mb-5">Услуги:</h2>
        <div class="overflow-y-auto max-h-80"> <!-- Блок со скроллом -->
          <div class="flex flex-col gap-5">
            <div
              class="service-card flex rounded-lg bg-white text-surface shadow-secondary-1 dark:bg-surface-dark dark:text-white p-4"
              v-for="service in services" :key="service.id">
              <img class="rounded-t-lg h-[80px] mr-4" :src="service.image" :alt="service.name" />
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
      </section>

      <!-- Выбор даты -->
      <section class="date-picker w-full mt-5">
        <h2 class="text-[26px] mb-5">Выберите дату</h2>
        <input class="input_text w-full" type="date" :min="minDate" :max="maxDate" @change="selectDate" />
      </section>

      <!-- Выбор времени -->
      <section class="time-picker w-full mt-5">
        <h2 class="text-[26px] mb-5">Выберите время</h2>
        <div class="time-buttons flex flex-wrap gap-2">
          <button v-for="time in times" :key="time" :class="{'bg-red-500': isTimeTaken(time), 'bg-blue-500': !isTimeTaken(time)}"
            @click="selectTime(time)">
            {{ time }}
          </button>
        </div>
      </section>

      <!-- Кнопки управления -->
      <div class="controls flex flex-col md:flex-row gap-5 mt-5 w-full mb-5">
        <button class="bg-[#000000] text-white p-5 rounded flex-1" @click="goBack">Вернуться обратно</button>
        <button class="bg-[#000000] text-white p-5 rounded flex-1" @click="openModal">Добавить запись</button>
      </div>

      <!-- Модальное окно -->
      <div v-if="showModal" class="modal">
        <div class="modal-content text-center text-[20px]">
          <h2>Введите ваши данные</h2>
          <input class="input_text" type="text" placeholder="Имя" v-model="userName" />
          <input class="input_text" type="email" placeholder="Почта" v-model="userEmail" />
          <input class="input_text" type="tel" placeholder="Телефон" v-model="userPhone" />
          <button class="bg-[#000000] text-white p-2 rounded" @click="submitAppointment">Подтвердить</button>
          <button class="bg-[#000000] text-white p-2 rounded" @click="closeModal">Отмена</button>
        </div>
      </div>
    </main>
  </div>
</template>
  
  <script>
  export default {
    data() {
      return {
        services: [
        { id: 1, name: 'Мужская стрижка', price: 1500, image: '/public/img/vzroslay.svg' },
        { id: 2, name: 'Укладка причёски', price: 1000, image: '/public/img/kids.svg' },
        { id: 3, name: 'Стрижка бороды', price: 800, image: '/public/img/beard.svg' },
        { id: 4, name: 'Бритьё шеи', price: 500, image: '/public/img/knife.svg' },
        { id: 5, name: 'Увлажнение головы', price: 300, image: '/public/img/losion.svg' },
        { id: 6, name: 'Уход за бородой', price: 1500, image: '/public/img/Barbershop.svg' }
      ],
      selectedServices: [], // Массив для хранения выбранных услуг
        minDate: new Date().toISOString().split('T')[0],
        maxDate: new Date(new Date().setMonth(new Date().getMonth() + 1)).toISOString().split('T')[0],
        times: ['9:00', '10:40', '12:40', '14:20', '16:00', '17:40', '18:20', '19:50'],
        takenTimes: ['10:40', '14:20'], // Пример занятых времен
        showModal: false,
        userName: '',
        userEmail: '',
        userPhone: '',
      };
    },
    methods: {
      toggleService(service) {
      const index = this.selectedServices.indexOf(service.id);
      if (index > -1) {
        this.selectedServices.splice(index, 1); // Удалить услугу
      } else {
        this.selectedServices.push(service.id); // Добавить услугу
      }
    },
      addService(service) {
        console.log(`Услуга добавлена: ${service}`);
      },
      selectDate(event) {
        console.log(`Дата выбрана: ${event.target.value}`);
      },
      selectTime(time) {
        console.log(`Время выбрано: ${time}`);
      },
      isTimeTaken(time) {
        return this.takenTimes.includes(time);
      },
      goBack() {
        console.log('Возврат на предыдущую страницу');
      },
      openModal() {
        this.showModal = true;
      },
      closeModal() {
        this.showModal = false;
      },
      submitAppointment() {
        console.log(`Запись добавлена: ${this.userName}, ${this.userEmail}, ${this.userPhone}`);
        this.closeModal();
      },
    },
  };
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
  .time-buttons button {
    margin: 5px;
    padding: 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
  }
  .modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
  }
  .modal-content {
    background-color: white;
    padding: 20px;
    border-radius: 5px;
    display: flex;
    width: 500px;
    flex-direction: column;
    gap:5px;
  }
  .overflow-y-auto {
  overflow-y: auto;
}

.service-card {
  transition: background-color 0.3s; /* Плавный переход цвета фона */
}

.service-card:hover {
  background-color: #f0f0f0; /* Цвет при наведении */
}
  </style>