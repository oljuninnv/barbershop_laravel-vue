<template>
  <div class="flex flex-col">
    <label for="userName" class="mb-1">Имя</label>
    <input type="text" id="userName" v-model="userName" :disabled="!isEditing" class="mb-2 p-2 border rounded" placeholder="Имя" />
    
    <label for="userLogin" class="mb-1">Login</label>
    <input type="text" id="userLogin" v-model="userLogin" :disabled="!isEditing" class="mb-2 p-2 border rounded" placeholder="Login" />
    
    <label for="userEmail" class="mb-1">Email</label>
    <input type="text" id="userEmail" v-model="userEmail" :disabled="!isEditing" class="mb-2 p-2 border rounded" placeholder="Email" />
    
    <label for="userPhone" class="mb-1">Телефон</label>
    <input type="text" id="userPhone" v-model="userPhone" :disabled="!isEditing" class="mb-2 p-2 border rounded" placeholder="Телефон" />
    
    <label for="userCity" class="mb-1">Город</label>
    <input type="text" id="userCity" v-model="userCity" :disabled="!isEditing" class="mb-2 p-2 border rounded" placeholder="Город" />
    
    <input type="file" v-if="isEditing" @change="handleFileUpload" class="mb-2" accept="image/*" />
    
    <button @click="toggleEdit" class="bg-blue-500 text-white p-2 rounded mt-2">
      {{ isEditing ? 'Сохранить' : 'Изменить данные' }}
    </button>

    <div v-if="errorMessage" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
      <div class="bg-white p-4 rounded shadow-lg">
        <p class="text-red-500">{{ errorMessage }}</p>
        <button @click="closeError" class="text-red-700 mt-2">Закрыть</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from '../../../libs/axios';

const isEditing = ref(false);
const userName = ref('');
const userEmail = ref('');
const userPhone = ref('');
const userLogin = ref('');
const userCity = ref('');
const userImageFile = ref(null);
const errorMessage = ref('');
const userId = ref('');
const originalUserData = ref({}); // Хранение оригинальных данных пользователя
const originalImageName = ref(''); // Хранение оригинального имени изображения

// Функция для получения данных пользователя из localStorage
const getUserDataFromLocalStorage = () => {
  const userData = localStorage.getItem('UserData');
  if (userData) {
    const parsedData = JSON.parse(userData);
    userName.value = parsedData.user?.name || '';
    userEmail.value = parsedData.user?.email || '';
    userPhone.value = parsedData.user?.phone || '';
    userLogin.value = parsedData.user?.login || '';
    userCity.value = parsedData.user?.city || '';
    userId.value = parsedData.user?.id || '';
    originalImageName.value = parsedData.user?.image || ''; // Сохраняем оригинальное имя изображения
    
    // Сохраняем оригинальные данные для сравнения
    originalUserData.value = {
      name: parsedData.user?.name || '',
      email: parsedData.user?.email || '',
      phone: parsedData.user?.phone || '',
      login: parsedData.user?.login || '',
      city: parsedData.user?.city || ''
    };
  }
};

// Функция для переключения режима редактирования
const toggleEdit = () => {
  if (isEditing.value) {
    saveUserData();
  } else {
    isEditing.value = true;
  }
};

// Функция для обработки загрузки файла
const handleFileUpload = (event) => {
  userImageFile.value = event.target.files[0];
};

// Функция для сохранения данных пользователя
const saveUserData = async () => {
  const formData = new FormData();

  // Проверяем, изменились ли данные, и добавляем только измененные
  if (userName.value !== originalUserData.value.name) {
    formData.append('name', userName.value);
  }
  if (userEmail.value !== originalUserData.value.email) {
    formData.append('email', userEmail.value);
  }
  if (userPhone.value !== originalUserData.value.phone) {
    formData.append('phone', userPhone.value);
  }
  if (userLogin.value !== originalUserData.value.login) {
    formData.append('login', userLogin.value);
  }
  if (userCity.value !== originalUserData.value.city) {
    formData.append('city', userCity.value);
  }
  
  if (userImageFile.value) {
    formData.append('image', userImageFile.value);
  }

  formData.append('_method', 'put'); // Добавляем метод PUT

  // Если formData пуст, ничего не отправляем
  if (formData.has('name') || formData.has('email') || formData.has('phone') || 
      formData.has('login') || formData.has('city') || formData.has('image')) {
    try {
      const response = await axios.post(`/api/update_user/${userId.value}`, formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      });
      console.log('Данные успешно обновлены:', response.data);
      
      // Обновляем все данные в localStorage
      const newUserData = {
        ...JSON.parse(localStorage.getItem('UserData')),
        user: {
          ...JSON.parse(localStorage.getItem('UserData')).user,
          name: userName.value,
          email: userEmail.value,
          phone: userPhone.value,
          login: userLogin.value,
          city: userCity.value,
        }
      };

      // Проверяем, изменилось ли имя изображения
      if (userImageFile.value) {
        const newImageName = `users/${userLogin.value}.${userImageFile.value.name.split('.').pop()}`;
        newUserData.user.image = newImageName; // Обновляем имя изображения
      }

      // Сохраняем обновленные данные в localStorage
      localStorage.setItem('UserData', JSON.stringify(newUserData));

      isEditing.value = false; // Выход из режима редактирования
      location.reload(); // Автообновление страницы
    } catch (error) {
      errorMessage.value = error.response?.data?.message || 'Произошла ошибка при обновлении данных.';
      setTimeout(() => {
        errorMessage.value = ''; // Скрываем сообщение об ошибке через 5 секунд
      }, 5000);
    }
  } else {
    console.log('Нет изменений для отправки.');
    isEditing.value = false; // Выход из режима редактирования, если нет изменений
  }
};

// Функция для закрытия сообщения об ошибке
const closeError = () => {
  errorMessage.value = '';
};

// Получаем данные пользователя при монтировании компонента
onMounted(() => {
  getUserDataFromLocalStorage();
});
</script>