<template>
    <div class="flex min-h-full flex-1 flex-col justify-center px-6 py-2 lg:px-8">
      <div class=" sm:mx-auto sm:w-full sm:max-w-md">
        <h2 class="text-center text-lg font-semibold text-gray-900 mb-4">Форма добавления пользователя</h2>
        <form class="max-w-lg mx-auto p-4 bg-white shadow-md rounded flex flex-col gap-5" form @submit.prevent="addUser"
          enctype="multipart/form-data">
          <div>
            <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Имя</label>
            <div class="mt-2">
              <input id="name" name="name" v-model="formData.name" required class="input_text" />
            </div>
          </div>
  
          <div>
            <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email</label>
            <div class="mt-2">
              <input id="email" name="email" type="email" v-model="formData.email" required class="input_text" />
            </div>
          </div>
  
          <div>
            <label for="phone" class="block text-sm font-medium leading-6 text-gray-900">Телефон</label>
            <div class="mt-2">
              <input id="phone" name="phone" v-model="formData.phone" required class="input_text" />
            </div>
          </div>
  
          <div>
            <label for="city" class="block text-sm font-medium leading-6 text-gray-900">Город</label>
            <div class="mt-2">
              <input id="city" name="city" v-model="formData.city" required class="input_text" />
            </div>
          </div>
  
          <div>
            <label for="birthday" class="block text-sm font-medium leading-6 text-gray-900">Дата рождения</label>
            <div class="mt-2">
              <input id="birthday" name="birthday" type="date" v-model="formData.birthday" class="input_text" />
            </div>
          </div>
    
          <div>
            <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Пароль</label>
            <div class="mt-2">
              <input id="password" name="password" type="password" v-model="formData.password" required
                class="input_text" />
            </div>
          </div>

          <!-- Загрузка изображения -->
          <div>
            <label for="image" class="block text-sm font-medium leading-6 text-gray-900">Изображение профиля</label>
            <div class="mt-2">
              <input id="image" name="image" type="file" @change="handleFileUpload" accept=".jpg, .jpeg, .png, .webp" />
            </div>
          </div>

          <div class="flex items-center mb-4">
          <input type="checkbox" id="add_to_worker" v-model="isCheckboxChecked" class="mr-2" />
          <label for="add_to_worker">Добавить в работники?</label>
        </div>
  
          <div>
            <button type="submit"
              class="flex w-full justify-center rounded-md bg-red-500 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-neutral-400">Добавить</button>
          </div>
  
          <ul v-if="errors.length" class="mt-4 text-red-600">
            <li v-for="(error, index) in errors" :key="index">{{ error }}</li>
          </ul>
  
        </form> 
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, defineEmits } from 'vue';
//   import { AddUser } from '../../../services/api/auth';
  
  const emit = defineEmits(['UserAdd']);
  const showForm = ref(true);
  const errors = ref([]);
  
  const formData = ref(new FormData());
  
  function handleFileUpload(event) {
    const file = event.target.files[0];
    if (file) {
      formData.value.append('image', file); // Добавляем файл в FormData
    }
  }
  
  async function addUser() {
    try{
        formData.value.append('name', formData.value.name);
        formData.value.append('email', formData.value.email);
        formData.value.append('phone', formData.value.phone);
        formData.value.append('city', formData.value.city);
        formData.value.append('birthday', formData.value.birthday);
        formData.value.append('password', formData.value.password);
    
    errors.value = [];
    await AddUser(formData.value);
    emit('UserAdd', formData.value); // Передаем данные родительскому компоненту
  
    // Очищаем форму после добавления пользователя
    formData.value = new FormData();
    }
    catch (error) {
      console.log(error);
            if (error.response) {
              switch (error.response.status) {
                case 422:
                  alert(error.response.data.error);
                  for (const [key, messages] of Object.entries(error.response.data.messages)) {
                    errors.value.push(...messages);
                }
                  break;
                default:
                  alert('Произошла неизвестная ошибка. Попробуйте позже.');
                  console.error('Произошла ошибка:', error);
              }
            } else {
              alert('Проблема с сетью. Проверьте подключение к интернету.');
              console.error('Ошибка сети:', error);
            }
          
    }
  }
  </script>