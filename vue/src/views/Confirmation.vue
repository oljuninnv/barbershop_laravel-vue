<template>
    <div class="flex flex-col items-center justify-center gap-5 mt-5">
      <h1 class="text-xl font-bold">Подтверждение записи</h1>
      <p><strong>Дата:</strong> {{ date }}</p>
      <p><strong>Время:</strong> {{ time }}</p>
      <p><strong>Стоимость:</strong> {{ price }}</p>
      <router-link to="/" class="bg-[#000000] text-white p-5 rounded">Вернуться на главную</router-link>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        date: this.$route.query.date,
        time: this.$route.query.time,
        price: this.$route.query.total_price,
      };
    },
  };
  </script>