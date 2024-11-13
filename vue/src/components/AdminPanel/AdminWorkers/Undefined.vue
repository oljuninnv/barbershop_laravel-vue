<template>
    <div class="w-full">
      <div class="flex justify-between items-center w-full p-4">
        <h1 class="mb-5">
          <span class="text-xl font-semibold whitespace-nowrap dark:text-white">Неопределённые работники:</span>
        </h1>
        <SearchInput @search="filterUsers" class="w-full max-w-md mb-4" />
      </div>
  
      <div class="flex justify-center">
        <label for="itemsPerPage">Элементов на странице:</label>
        <select id="itemsPerPage" @change="updateItemsPerPage">
          <option value="3">3</option>
          <option value="5">5</option>
          <option value="10">10</option>
          <option value="20">20</option>
          <option value="50">50</option>
        </select>
      </div>
  
      <div class="relative overflow-hidden shadow-md sm:rounded-lg mt-[5%]">
        <div v-if="loading" class="absolute flex justify-center items-center w-full h-full bg-white">
          <span>Loading...</span>
        </div>
        <div class="h-full w-full overflow-x-scroll">
          <table class="table overflow-x-scroll">
          <thead class="thead">
            <tr>
              <th class="px-6 py-3">Имя пользователя</th>
              <th class="px-6 py-3">Почта</th>
              <th class="px-6 py-3">Город</th>
              <th class="px-6 py-3">Телефон</th>
              <th class="px-6 py-3">День рождения</th>
              <th class="px-6 py-3">Опыт работы</th>
              <th class="px-6 py-3">Действия</th>
            </tr>
          </thead>
          <tbody class="overflow-x-scroll">
            <tr v-for="user in users" :key="user.usser_id">
              <td class="px-6 py-3">{{ user.user_name }}</td>
              <td class="px-6 py-3">{{ user.user_email }}</td>
              <td class="px-6 py-3">{{ user.user_city }}</td>
              <td class="px-6 py-3">{{ user.user_phone }}</td>
              <td class="px-6 py-3">{{ user.user_birthday }}</td>
              <td class="px-6 py-3">
    {{ user.work_experience }} 
    {{ user.work_experience > 0 && user.work_experience < 5 ? 'года' : 'лет' }}
</td>
              <td class="flex gap-5 text-right px-6 py-3">
                <button @click="editUser(user)" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</button>
                <button @click="removeUser(user.user_id)" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Delete</button>
              </td>
            </tr>
          </tbody>
        </table>
        </div>
      </div>
  
      <div class="flex justify-center mt-5">
        <button @click="prevPage" :disabled="pagination.page === 1" class="btn">Назад</button>
        <span class="mx-2">Страница {{ pagination.page }} из {{ pagination.last_page }}</span>
        <button class="btn" @click="nextPage" :disabled="pagination.page === pagination.last_page">Вперед</button>
      </div>
  
      <!-- Модальное окно для редактирования пользователя -->
      <div v-if="isModalOpen" class="fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-75">
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-lg w-full max-h-[80vh] overflow-y-auto text-black">
          <h2>Редактировать пользователя</h2>
          <div class="flex flex-col gap-2 mt-2">
            <input v-model="modalUser.name" placeholder="Имя пользователя" class="input_text" />
            <input v-model="modalUser.login" placeholder="Логин пользователя" class="input_text" />
            <input v-model="modalUser.email" placeholder="Почта пользователя" class="input_text" />
            <input v-model="modalUser.phone" placeholder="Телефон пользователя" class="input_text" />
            <input v-model="modalUser.city" placeholder="Город пользователя" class="input_text" />
            <input type="date" v-model="modalUser.birthday" placeholder="Дата рождения пользователя" class="input_text" />
          </div>
          <div class="mt-2 flex gap-2">
            <button @click="updateUser()" class="btn">Сохранить</button>
            <button type="button" @click="closeModal" class="bg-gray-300 hover:bg-gray-400 rounded px-4 py-2">Закрыть</button>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import SearchInput from "../../SearchInput.vue";
  import axios from '../../../libs/axios';
  
  const users = ref([]);
  const loading = ref(false);
  const pagination = ref({
    page: 1,
    per_page: 3,
    total: 0,
    last_page: 1,
  });
  const isModalOpen = ref(false);
  const isEditing = ref(false);
  const modalUser = ref({ id: '', name: '', login: '', email: '', city: '', birthday: '', phone: '' });
  
  const loadUsers = async (page = 1, name = null, per_page = pagination.value.per_page) => {
    try {
      loading.value = true;
      const response = await axios.get('/api/get_worker_undefined', {
        params: {
          page,
          name,
          per_page
        }
      });
      users.value = response.data.response.data;
      pagination.value.total = response.data.response.total;
      pagination.value.last_page = response.data.response.last_page;
      loading.value = false;
    } catch (error) {
      console.error('Ошибка при загрузке Посетителей:', error);
    }
  };
  
  onMounted(() => loadUsers());
  
  const filterUsers = async (query) => {
    await loadUsers(1, query);
  };
  
  const updateItemsPerPage = (event) => {
    pagination.value.per_page = parseInt(event.target.value);
    loadUsers(1);
  };
  
  const prevPage = async () => {
    if (pagination.value.page > 1) {
      await loadUsers(pagination.value.page - 1);
      pagination.value.page--;
    }
  };
  
  const nextPage = async () => {
    if (pagination.value.page < pagination.value.last_page) {
      await loadUsers(pagination.value.page + 1);
      pagination.value.page++;
    }
  };
  
  const editUser = (user) => {
    modalUser.value = { ...user };
    isEditing.value = true;
    isModalOpen.value = true;
  };
  
  const closeModal = () => {
    isModalOpen.value = false;
  };
  
  const updateUser = async () => {
    const formData = new FormData();
  
    // Получаем оригинальные данные пользователя для сравнения
    const index = users.value.findIndex(user => user.id === modalUser.value.id);
    const originalUserData = users.value[index];
  
    // Проверяем, изменились ли данные, и добавляем только измененные
    if (modalUser.value.name !== originalUserData.name) {
      formData.append('name', modalUser.value.name);
    }
    if (modalUser.value.email !== originalUserData.email) {
      formData.append('email', modalUser.value.email);
    }
    if (modalUser.value.phone !== originalUserData.phone) {
      formData.append('phone', modalUser.value.phone);
    }
    if (modalUser.value.login !== originalUserData.login) {
      formData.append('login', modalUser.value.login);
    }
    if (modalUser.value.city !== originalUserData.city) {
      formData.append('city', modalUser.value.city);
    }
    if (modalUser.value.birthday !== originalUserData.birthday) {
      formData.append('birthday', modalUser.value.birthday);
    }
  
    // Если есть изображение, добавляем его
    if (modalUser.value.imageFile) {
      formData.append('image', modalUser.value.imageFile);
    }
  
    formData.append('_method', 'put'); // Добавляем метод PUT
  
    // Если formData пуст, ничего не отправляем
    if (formData.has('name') || formData.has('email') || formData.has('phone') || 
        formData.has('login') || formData.has('city') || formData.has('birthday') || 
        formData.has('image')) {
      try {
        const response = await axios.post(`/api/update_user/${modalUser.value.id}`, formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        });
        console.log('Данные успешно обновлены:', response.data);
        
        // Обновляем данные в массиве пользователей
        users.value[index] = { ...originalUserData, ...modalUser.value };
  
        closeModal(); // Закрываем модальное окно
        await loadUsers(pagination.value.page); // Перезагружаем пользователей
      } catch (error) {
        console.error('Ошибка при обновлении пользователя:', error);
      }
    } else {
      console.log('Нет изменений для отправки.');
      closeModal(); // Закрываем модальное окно, если нет изменений
    }
  };
  
  const removeUser = async (id) => {
    try {
      await axios.delete(`/api/delete_user/${id}`);
      users.value = users.value.filter(user => user.id !== id);
      await loadUsers(pagination.value.page);
    } catch (error) {
      console.error('Ошибка при удалении пользователя:', error);
    }
  };
  </script>
  
  <style>
  .btn {
    padding: 0.5rem 1rem;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 0.25rem;
    cursor: pointer;
  }
  .btn:disabled {
    background-color: #ccc;
  }
  </style>