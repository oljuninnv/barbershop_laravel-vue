<template>
    <div class="w-full">
      <div class="flex justify-between items-center w-full p-4">
        <h1 class="mb-5">
          <span class="text-xl font-semibold whitespace-nowrap dark:text-white">Пользователи:</span>
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
        <table class="table">
          <thead>
            <tr>
              <th>Имя пользователя</th>
              <th>Почта</th>
              <th>Изображение</th>
              <th>Город</th>
              <th>Телефон</th>
              <th>День рождения</th>
              <th>Действия</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="user in paginatedUsers" :key="user.id">
              <td>{{ user.name }}</td>
              <td>{{ user.email }}</td>
              <td>{{ user.image }}</td>
              <td>{{ user.city }}</td>
              <td>{{ user.phone }}</td>
              <td>{{ user.birthday }}</td>
              <td class="flex gap-5 text-right">
                <button @click="editUser(user)" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</button>
                <button @click="removeUser(user.id)" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Delete</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
  
      <div class="flex justify-center mt-5">
        <button @click="prevPage" :disabled="pagination.page === 1" class="btn">Назад</button>
        <span class="mx-2">Страница {{ pagination.page }} из {{ pagination.last_page }}</span>
        <button class="btn" @click="nextPage" :disabled="pagination.page === pagination.last_page">Вперед</button>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, computed, onMounted } from 'vue';
  import SearchInput from "../../SearchInput.vue";
  
  const users = ref([]);
  const searchQuery = ref('');
  const pagination = ref({
    page: 1,
    per_page: 3,
    total: 0,
    last_page: 1,
  });
  
  const filteredUsers = computed(() => {
    if (!searchQuery.value) return users.value;
    return users.value.filter(user =>
      user.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
  });
  
  const paginatedUsers = computed(() => {
  const start = (pagination.value.page - 1) * pagination.value.per_page;
  const end = start + pagination.value.per_page;
  return filteredUsers.value.slice(start, end);
});
  
const filterUsers = () => {
  pagination.value.page = 1; // Reset to first page on search
  recalculatePagination();
};
  
  const updateItemsPerPage = (event) => {
    pagination.value.per_page = parseInt(event.target.value);
    recalculatePagination();
  };
  
  const recalculatePagination = () => {
    pagination.value.total = filteredUsers.value.length;
    pagination.value.last_page = Math.ceil(pagination.value.total / pagination.value.per_page);
    if (pagination.value.page > pagination.value.last_page) {
      pagination.value.page = pagination.value.last_page;
    }
  };
  
  const prevPage = () => {
    if (pagination.value.page > 1) pagination.value.page--;
  };
  
  const nextPage = () => {
    if (pagination.value.page < pagination.value.last_page) pagination.value.page++;
  };
  
  const editUser = (user) => {
    // Implement edit logic
  };
  
  const removeUser = (userId) => {
    users.value = users.value.filter(user => user.id !== userId);
    recalculatePagination(); // Обновляем количество страниц после удаления пользователя
  };
  
  const generateRandomUsers = () => {
    users.value = []; // Clear existing users
    for (let i = 0; i < 20; i++) {
      users.value.push({
        id: i,
        name: `User ${i}`,
        email: `user${i}@example.com`,
        image: `https://via.placeholder.com/150?text=User+${i}`,
        city: `City ${i}`,
        phone: `+1234567890${i}`,
        birthday: `1990-01-${(i % 30) + 1}`,
      });
    }
    recalculatePagination(); // Обновляем количество страниц после генерации пользователей
  };
  
  onMounted(() => {
    generateRandomUsers();
  });
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