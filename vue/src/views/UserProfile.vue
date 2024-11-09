<template>
    <div>
      <component :is="profileComponent" />
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import { useRouter } from 'vue-router';
  import UserProfile from '../components/Profiles/UserProfile.vue';
  import BarberProfile from '../components/Profiles/BarberProfile.vue';
  import AdminProfile from '../components/Profiles/AdminProfile.vue';
  
  const profileComponent = ref(null);
  const router = useRouter();
  
  onMounted(() => {
    // Проверка наличия токена в localStorage
    const token = localStorage.getItem('token');
    
    if (!token) {
      // Если токена нет, перенаправляем на страницу 404
      router.push({ name: 'NotFoundPage' }); // Убедитесь, что у вас есть маршрут с именем 'NotFoundPage'
      return;
    }
  
    // Проверка наличия данных о работнике
    const workerData = JSON.parse(localStorage.getItem('UserData'));
    console.log(workerData.worker);
    
    if (!workerData.worker) {
      // Если данных о работнике нет, показываем UserProfile
      profileComponent.value = UserProfile;
      return;
    }
  
    // Получаем имя поста
    const postName = workerData.worker.post?.name;
  
    // Выбор компонента в зависимости от имени поста
    if (postName === 'Barber') {
      profileComponent.value = BarberProfile;
    } else if (postName === 'Admin') {
      profileComponent.value = AdminProfile;
    } else {
      profileComponent.value = UserProfile; // По умолчанию
    }
  });
  </script>