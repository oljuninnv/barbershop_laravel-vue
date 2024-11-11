<template>
  <div class="text-center text-xl mt-5">
    <h1>404 - Страница не найдена</h1>
    <p>Извините, такой страницы не существует.</p>
    <router-link 
      to="/" 
      class="mt-4 inline-block rounded bg-blue-500 text-white px-4 py-2 hover:bg-blue-600">
      Вернуться на главную
    </router-link>
  </div>
</template>
  
  <script>
  export default {
    name: 'NotFoundPage',
  };
  </script>