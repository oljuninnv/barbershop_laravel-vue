<template>
    <div class="w-full">
      <div class="flex justify-between items-center w-full p-4">
        <h1 class="mb-5">
          <span class="text-xl font-semibold whitespace-nowrap dark:text-white">Администраторы:</span>
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
              <th class="px-6 py-3">Дата принятия на работу</th>
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
  
        <!-- Modal for Editing Worker -->
        <div v-if="isModalOpen" class="fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-75">
            <div class="bg-white rounded-lg shadow-lg p-6 max-w-lg w-full max-h-[80vh] overflow-y-auto text-black">
                <h2>Редактировать пользователя</h2>
                <div class="flex flex-col gap-2 mt-2">
                  <div class="flex items-center gap-2">
                    <label for="adoptedAt">Дата принятия на работу:</label>
                    <input type="date" id="adoptedAt" v-model="modalUser.adopted_at" @change="calculateWorkExperience" class="input_text" />
                </div>

                    <!-- Positions List -->
                    <div class="flex flex-col gap-2">
                        <label for="positions">Должности:</label>
                        <select id="positions" v-model="modalUser.post_id" class="input_text">
                          <option v-for="post in posts" :key="post.id" :value="post.id">{{ post.name }}</option>
                        </select>
                    </div>
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
  import axios from '../../../libs/axios';
  import SearchInput from "../../SearchInput.vue";
  
  const users = ref([]);
  const posts = ref([]); // To store positions
  const loading = ref(false);
  const pagination = ref({
      page: 1,
      per_page: 3,
      total: 0,
      last_page: 1,
  });
  const isModalOpen = ref(false);
  const modalUser = ref({id: 0, post_id: '', adopted_at: '',});
  
  const loadUsers = async (page = 1, name = null, per_page = pagination.value.per_page) => {
      try {
          loading.value = true;
          const response = await axios.get('/api/get_worker_admin', {
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
  
  const loadPosts = async () => {
      try {
          const response = await axios.get('/api/get_posts'); // Assuming this endpoint returns the list of posts
          posts.value = response.data.response.data;
          console.log(posts.value);
      } catch (error) {
          console.error('Ошибка при загрузке должностей:', error);
      }
  };

  const filterUsers = async (query) => {
    await loadUsers(1, query);
  };
  
  onMounted(() => {
      loadUsers();
      loadPosts(); // Load positions when component mounts
  });
  
  const editUser = (user) => {
    console.log(user);
    
      modalUser.value = { ...user };
      isModalOpen.value = true;
  };
  
  const closeModal = () => {
      isModalOpen.value = false;
  };
  
  const updateUser = async () => {
    const formData = new FormData();

    // Get the original user data for comparison
    const index = users.value.findIndex(user => user.id === modalUser.value.id);
    const originalUserData = users.value[index];
    console.log(modalUser.value.worker_id);
    formData.append('id',modalUser.value.worker_id);

    if (modalUser.value.adopted_at !== originalUserData.adopted_at) {
        formData.append('adopted_at', modalUser.value.adopted_at);
    }

    if (modalUser.value.post_id !== originalUserData.post_id) {
        formData.append('post_id', modalUser.value.post_id);
    }

    formData.append('_method', 'put'); // Specify the PUT method

    // If formData has changes, send the request
    if (formData.has('post_id') || formData.has('adopted_at') ) {
        try {
          console.log(modalUser.value.adopted_at);
            const response = await axios.post(`/api/update_worker/${modalUser.value.worker_id}`, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            });
            console.log('Data successfully updated:', response.data);

            // Update the user data in the users array
            users.value[index] = { ...originalUserData, ...modalUser.value };

            closeModal(); // Close the modal
            await loadUsers(pagination.value.page); // Reload users
        } catch (error) {
            console.error('Error updating user:', error);
        }
    } else {
        console.log('No changes to submit.');
        closeModal(); // Close the modal if no changes
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