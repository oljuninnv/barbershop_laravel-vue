<template>
    <div v-if="isAdmin" class="flex flex-wrap bg-gray-100 w-full h-full">
        <div class="bg-white rounded p-3 shadow-lg w-full md:w-3/12 lg:w-2/12 min-h-screen">
            <div class="flex items-center space-x-4 p-2 mb-5">
                <h4 class="font-semibold text-lg text-gray-700 capitalize font-poppins tracking-wide">AdminPanel</h4>
            </div>
            <hr>
            <ul class="space-y-2 text-sm">
                <li class="relative">
                    <button type="button"
                        class="flex items-center w-full p-2 text-base text-gray-900 font-medium transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                        aria-controls="dropdown-example" data-collapse-toggle="dropdown-example"
                        @click="toggleDropdown">
                        <span class="flex-1 text-left rtl:text-right whitespace-nowrap">Пользователи</span>
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 4 4 4-4" />
                        </svg>
                    </button>
                    <ul v-if="isDropdownVisible" id="dropdown-example" class="py-2 space-y-2">
                        <li>
                            <a href="#"
                                class="flex items-center w-full font-medium p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-200 focus:bg-gray-200 focus:shadow-outline dark:text-white dark:hover:bg-gray-700"
                                @click.prevent="showTable('add_visitors')">Добавить пользователя</a>
                        </li>
                        <li>
                            <a href="#"
                                class="flex items-center font-medium w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-200 focus:bg-gray-200 focus:shadow-outline dark:text-white dark:hover:bg-gray-700"
                                @click.prevent="showTable('visitors')">Посетители</a>
                        </li>
                        <li>
                            <a href="#"
                                class="flex items-center w-full font-medium p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-200 focus:bg-gray-200 focus:shadow-outline dark:text-white dark:hover:bg-gray-700"
                                @click.prevent="showTable('worker_visitors')">Работники</a>
                        </li>
                    </ul>
                </li>
                <li class="relative">
                    <button type="button"
                        class="flex items-center w-full p-2 font-medium text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                        @click.prevent="toggleSubmenu('staff')">
                        <span class="flex-1 text-left rtl:text-right whitespace-nowrap">Сотрудники</span>
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 4 4 4-4" />
                        </svg>
                    </button>
                    <ul v-if="isStaffMenuVisible" class="py-2 space-y-2 pl-4">
                        <li>
                            <a href="#" @click.prevent="showTable('administrators')"
                                class="block px-4 py-2 font-medium text-gray-900 rounded-lg hover:bg-gray-200 focus:bg-gray-200 focus:shadow-outline">Администраторы</a>
                        </li>
                        <li>
                            <a href="#" @click.prevent="showTable('barbers')"
                                class="block px-4 py-2 font-medium rounded-lg text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:shadow-outline">Барберы</a>
                        </li>
                        <li>
                            <a href="#" @click.prevent="showTable('staff')"
                                class="block px-4 py-2 font-medium rounded-lg text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:shadow-outline">Обслуживающий персонал</a>
                        </li>
                        <li>
                            <a href="#" @click.prevent="showTable('undefined_worker')"
                                class="block px-4 py-2 font-medium rounded-lg text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:shadow-outline">Не определённые работники</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#" @click.prevent="showTable('services')"
                        class="flex items-center space-x-3 text-gray-900 p-2 rounded-md font-medium hover:bg-gray-200 focus:bg-gray-200 focus:shadow-outline">
                        <span>Услуги</span>
                    </a>
                </li>
                <li>
                    <a href="#" @click.prevent="showTable('records')"
                        class="flex items-center space-x-3 text-gray-900 p-2 rounded-md font-medium hover:bg-gray-200 focus:bg-gray-200 focus:shadow-outline">
                        <span>Записи</span>
                    </a>
                </li>
                <hr>
                <li>
                    <a href="#" @click.prevent="showTable('generate_records')"
                        class="flex items-center space-x-3 text-gray-700 p-2 rounded-md font-medium hover:bg-gray-200 focus:bg-gray-200 focus:shadow-outline">
                        <span>Создать записи</span>
                    </a>
                </li>
                <hr>
                <li>
                    <a href="#"
                        class="flex items-center space-x-3 text-gray-700 p-2 rounded-md font-medium hover:bg-gray-200 focus:bg-gray-200 focus:shadow-outline">
                        <router-link to="/departments" class="flex gap-2">
                            <span class="text-gray-600">
                                <svg class="h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </span>
                            <span>Выйти</span>
                        </router-link>
                    </a>
                </li>
            </ul>
        </div>

        <div class="w-full h-full md:w-9/12 lg:w-10/12 p-4">
            <div class="text-gray-500">
                <AdminCreateUser v-if="activeTable === 'add_visitors'" />
                <AdminUserWorkers v-if="activeTable === 'worker_visitors'" />
                <AdminUser v-if="activeTable === 'visitors'" />
                <AdminWorkPositionsTable v-if="activeTable === 'work-positions'" />
                <AdminTable v-if="activeTable === 'admins'" />
            </div>
        </div>
    </div>

    <!-- Страница ошибки 404 -->
    <div v-else class="flex items-center justify-center w-full h-screen bg-gray-100">
        <h1 class="text-xl font-bold text-red-500">404 - Доступ запрещен</h1>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
  import AdminUser from "./../components/AdminPanel/AdminUsers/AdminUser.vue";
  import AdminCreateUser from "./../components/AdminPanel/AdminUsers/AdminCreateUser.vue";
  import AdminUserWorkers from "./../components/AdminPanel/AdminUsers/AdminUserWorkers.vue";

const activeTable = ref('');

// Получаем данные о пользователе из localStorage
const userData = JSON.parse(localStorage.getItem('UserData'));
const isAdmin = computed(() => {
    return userData && userData.roles && userData.roles.includes('Admin');
});

const isDropdownVisible = ref(false);
const isStaffMenuVisible = ref(false);

const toggleDropdown = () => {
    isDropdownVisible.value = !isDropdownVisible.value; // Переключаем видимость основного меню
    isStaffMenuVisible.value = false; // Скрываем подменю сотрудников при закрытии основного меню
};

const toggleSubmenu = (menu) => {
    if (menu === 'staff') {
        isStaffMenuVisible.value = !isStaffMenuVisible.value; // Переключаем видимость подменю сотрудников
    }
};

const showTable = (table) => {
    activeTable.value = table; // Закрываем основное меню после выбора
};
</script>