<template>
  <section class="p-5">
    <div class="rounded-xl relative mx-auto flex h-full w-full flex-col items-center p-[16px]">
      <div class="relative mt-1 flex h-32 w-full justify-center rounded-xl bg-cover"
        style='background-image: url("../public/img/after_header.svg");'>
        <div class="absolute -bottom-12 flex h-[88px] w-[88px] items-center justify-center rounded-full border-[4px] border-white bg-white overflow-hidden">
          <img v-if="userImage" class="h-full w-full object-cover rounded-full" src="/public/img/barbers/barber1.svg" alt="User_Avatar" />
          <img v-else class="h-full w-full object-cover rounded-full" src="../../../public/img/default.png" alt="User_Avatar" />

        </div>
      </div>
      <div class="mt-16 flex flex-col items-center">
        <h4 class="text-bluePrimary text-xl font-bold text-center">{{ userName }}</h4>
        <p class="text-lightSecondary text-base font-normal text-center">{{ userPost }}</p>
      </div>
      <div class="flex flex-col mt-4 w-full">
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

        <label v-if="isEditing" for="userImage" class="mb-1">Аватар</label>
        <input type="file" id="userImage" v-if="isEditing" @change="handleFileUpload" class="mb-2" />

        <button @click="toggleEdit" class="bg-red-500 text-white p-2 rounded mt-2">
          {{ isEditing ? 'Сохранить' : 'Изменить данные' }}
        </button>
      </div>
      <button @click="goToAdminPanel" class="bg-blue-500 text-white p-2 rounded mt-4 w-full">
        Перейти в админ-панель
      </button>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from '../../libs/axios';


const userName = ref('');
const userEmail = ref('');
const userLogin = ref('');
const userPhone = ref('');
const userCity = ref('');
const userImage = ref(null);
const isEditing = ref(false);

onMounted(() => {
  const userData = JSON.parse(localStorage.getItem('UserData'));
  if (userData) {
    userName.value = userData.user.name;
    userEmail.value = userData.user.email;
    userLogin.value = userData.user.login;
    userPhone.value = userData.user.phone;
    userCity.value = userData.city;
  }
});

const handleFileUpload = (event) => {
  userImage.value = event.target.files[0]; // Сохраняем файл в реактивную переменную
};

const toggleEdit = async () => {
  if (isEditing.value) {
    try {

      const formData = new FormData();
      formData.append('name', userName.value);
      formData.append('email', userEmail.value);
      formData.append('login', userLogin.value);
      formData.append('phone', userPhone.value);
      formData.append('city', userCity.value);
      if (userData.value.image) {
            formData.append('image', userData.value.image, userData.value.image.name);
        }

        formData.append('_method', 'put');

      const response = await axios.post(`api/update_user/${id}`, formData, {
      });

      console.log('Данные успешно обновлены:', response.data);

      // Обновляем данные в localStorage
      userData.user.name = userName.value;
      userData.user.email = userEmail.value;
      userData.user.login = userLogin.value;
      userData.user.phone = userPhone.value;
      userData.city = userCity.value;

      localStorage.setItem('UserData', JSON.stringify(userData)); // Сохраняем обновленные данные обратно в localStorage
    } catch (error) {
      console.error('Ошибка при обновлении данных:', error);
    }
  }
  isEditing.value = !isEditing.value;
};
</script>