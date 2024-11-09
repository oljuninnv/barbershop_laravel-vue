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
    try{
      const postName = workerData.worker.post.name;

      if (postName == 'Barber') {
      console.log('Barber');
      profileComponent.value = BarberProfile;
    } else if (postName == 'Admin') {
      console.log('Admin')
      profileComponent.value = AdminProfile;}
    }
    catch(error){
      profileComponent.value = UserProfile;
    }
  });
  </script>