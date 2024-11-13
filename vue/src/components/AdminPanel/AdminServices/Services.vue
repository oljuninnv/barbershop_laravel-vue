<template>
    <div class="w-full">
        <div class="flex justify-between items-center w-full p-4">
            <h1 class="mb-5">
                <span class="text-xl font-semibold whitespace-nowrap dark:text-white">Услуги:</span>
            </h1>
            <SearchInput @search="filterServices" class="w-full max-w-md mb-4" />
            <button @click="openAddServiceModal" class="btn">Добавить Услугу</button>
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
                <thead class="thead">
                    <tr>
                        <th class="px-6 py-3">id</th>
                        <th class="px-6 py-3">Название услуги</th>
                        <th class="px-6 py-3">Стоимость</th>
                        <th class="px-6 py-3">Продолжительность</th>
                        <th class="px-6 py-3">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="service in services" :key="service.id"
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <td class="px-6 py-4">{{ service.id }}</td>
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white ">
                            {{ service.name }}</td>
                        <td class="px-6 py-4">{{ service.price }}</td>
                        <td class="px-6 py-4">{{ service.execution_time }}</td>
                        <td class="flex gap-5 px-6 py-4">
                            <button @click="editService(service)"
                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</button>
                            <button @click="removeService(service.id)"
                                class="font-medium text-red-600 dark:text-red-500 hover:underline">Delete</button>
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

        <!-- Модальное окно для добавления/редактирования должности -->
        <div v-if="isModalOpen" @close="closeModal"
            class="fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-75">
            <div class="bg-white rounded-lg shadow-lg  p-6 max-w-lg w-full max-h-[80vh] overflow-y-auto text-black">
                <h2>{{ isEditing ? 'Редактировать услугу' : 'Добавить услугу' }}</h2>
                <div class="flex flex-col gap-2 mt-2">
                    <input v-model="modalService.name" placeholder="Название услуги" class="input_text" />
                    <input v-model="modalService.price" placeholder="Цена услуги" class="input_text" />
                    <input v-model="modalService.execution_time" placeholder="Продолжительность услуги" class="input_text" />
                </div>               
                <div class="mt-2 flex gap-2">
                    <button @click="isEditing ? updateService() : addService()" class="btn">
                        {{ isEditing ? 'Сохранить' : 'Добавить' }}
                    </button>
                    <button type="button" @click="closeModal"
                        class="bg-gray-300 hover:bg-gray-400 rounded px-4 py-2">Закрыть</button>
                </div>

            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import SearchInput from "../../SearchInput.vue";
import axios from '../../../libs/axios';

const services = ref([]);
const loading = ref(false);
const pagination = ref({
    page: 1,
    per_page: 3,
    total: 0,
    last_page: 1,
});
const isModalOpen = ref(false);
const isEditing = ref(false);
const modalService = ref({ id: '', name: '',price:'',execution_time: ''});
const isInfoModalOpen = ref(false);
const serviceInfo = ref(null);

const loadServices = async (page = 1, name = null, per_page = pagination.value.per_page) => {
    try {
        loading.value = true;
        const response = await axios.get('/api/get_services', {
            params: {
                page,
                name,
                per_page
            }
        });
        services.value = response.data.response.data;
        pagination.value.total = response.data.response.total;
        pagination.value.last_page = response.data.response.last_page;
        loading.value = false;
    } catch (error) {
        console.error('Ошибка при загрузке должностей:', error);
    }
};

onMounted(() => loadServices());

const filterServices = async (query) => {
    await loadServices(1, query);
};

const updateItemsPerPage = (event) => {
    pagination.value.per_page = parseInt(event.target.value);
    loadServices(1);
};

const prevPage = async () => {
    if (pagination.value.page > 1) {
        await loadServices(pagination.value.page - 1);
        pagination.value.page--;
    }
};

const nextPage = async () => {
    if (pagination.value.page < pagination.value.last_page) {
        await loadServices(pagination.value.page + 1);
        pagination.value.page++;
    }
};

const openAddServiceModal = () => {
    modalService.value = { id: '', name: '',price:'',execution_time:'' };
    isEditing.value = false;
    isModalOpen.value = true;
};

const editService = (service) => {
    modalService.value = { ...service };
    isEditing.value = true;
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
};

const addService = async () => {
    try {
        const response = await axios.post('/api/add_service', modalService.value);
        services.value.push(response.data);
        closeModal();
        await loadServices(pagination.value.page);
    } catch (error) {
        console.error('Ошибка при добавлении должности:', error);
    }
};

const updateService = async () => {
    try {
        await axios.put(`/api/update_service/${modalService.value.id}`, modalService.value);
        const index = services.value.findIndex(service => service.id === modalService.value.id);
        if (index !== -1) {
            services.value[index] = modalService.value;
        }
        closeModal();
        await loadServices(pagination.value.page);
    } catch (error) {
        console.error('Ошибка при обновлении должности:', error);
    }
};

const removeService = async (id) => {
    try {
        await axios.delete(`/api/delete_service/${id}`);
        services.value = services.value.filter(service => service.id !== id);
        await loadServices(pagination.value.page);
    } catch (error) {
        console.error('Ошибка при удалении должности:', error);
    }
};
</script>