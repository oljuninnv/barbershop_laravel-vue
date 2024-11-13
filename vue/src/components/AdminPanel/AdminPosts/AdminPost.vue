<template>
    <div class="w-full">
        <div class="flex justify-between items-center w-full p-4">
            <h1 class="mb-5">
                <span class="text-xl font-semibold whitespace-nowrap dark:text-white">Должности:</span>
            </h1>
            <SearchInput @search="filterPosts" class="w-full max-w-md mb-4" />
            <button @click="openAddPostModal" class="btn">Добавить должность</button>
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
                        <th class="px-6 py-3">Название должности</th>
                        <th class="px-6 py-3">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="post in posts" :key="post.id"
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <td class="px-6 py-4">{{ post.id }}</td>
                        <td @click="openPostInfoModal(post.id)"
                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white cursor-pointer">
                            {{ post.name }}</td>
                        <td class="flex gap-5 px-6 py-4">
                            <button @click="editPost(post)"
                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</button>
                            <button @click="removePost(post.id)"
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
            <div class="bg-white rounded-lg shadow-lg p-6 max-w-lg w-full max-h-[80vh] overflow-y-auto text-black">
                <h2>{{ isEditing ? 'Редактировать должность' : 'Добавить должность' }}</h2>
                <input v-model="modalPost.name" placeholder="Название должности" class="input_text" />
                <div class="mt-2 flex gap-2">
                    <button @click="isEditing ? updatePost() : addPost()" class="btn">
                        {{ isEditing ? 'Сохранить' : 'Добавить' }}
                    </button>
                    <button type="button" @click="closeModal"
                        class="bg-gray-300 hover:bg-gray-400 rounded px-4 py-2">Закрыть</button>
                </div>

            </div>
        </div>

        <!-- Модальное окно для информации о должности -->
        <div v-if="isInfoModalOpen" @close="closeInfoModal"
            class="fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-75">
            <div class="bg-white rounded-lg shadow-lg p-6 max-w-lg w-full max-h-[80vh] overflow-y-auto text-black">
                <h2>Информация о должности</h2>
                <div v-if="postInfo">
                    <p><strong>Название:</strong> {{ postInfo.post_name }}</p>
                    <p><strong>Количество сотрудников:</strong> {{ postInfo.employee_count }}</p>
                    <p><strong>Сотрудники:</strong></p>
                    <ul>
                        <li v-for="name in postInfo.employee_names" :key="name">{{ name }}</li>
                    </ul>
                    <div class="flex justify-end mt-4">
                        <button type="button" @click="closeInfoModal"
                            class="bg-gray-300 hover:bg-gray-400 rounded px-4 py-2">Закрыть</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import SearchInput from "../../SearchInput.vue";
import axios from '../../../libs/axios';

const posts = ref([]);
const loading = ref(false);
const pagination = ref({
    page: 1,
    per_page: 3,
    total: 0,
    last_page: 1,
});
const isModalOpen = ref(false);
const isEditing = ref(false);
const modalPost = ref({ id: '', name: '' });
const isInfoModalOpen = ref(false);
const postInfo = ref(null);

const loadPosts = async (page = 1, name = null, per_page = pagination.value.per_page) => {
    try {
        loading.value = true;
        const response = await axios.get('/api/get_posts', {
            params: {
                page,
                name,
                per_page
            }
        });
        posts.value = response.data.response.data;
        pagination.value.total = response.data.response.total;
        pagination.value.last_page = response.data.response.last_page;
        loading.value = false;
    } catch (error) {
        console.error('Ошибка при загрузке должностей:', error);
    }
};

onMounted(() => loadPosts());

const filterPosts = async (query) => {
    await loadPosts(1, query);
};

const updateItemsPerPage = (event) => {
    pagination.value.per_page = parseInt(event.target.value);
    loadPosts(1);
};

const prevPage = async () => {
    if (pagination.value.page > 1) {
        await loadPosts(pagination.value.page - 1);
        pagination.value.page--;
    }
};

const nextPage = async () => {
    if (pagination.value.page < pagination.value.last_page) {
        await loadPosts(pagination.value.page + 1);
        pagination.value.page++;
    }
};

const openAddPostModal = () => {
    modalPost.value = { id: '', name: '' };
    isEditing.value = false;
    isModalOpen.value = true;
};

const editPost = (post) => {
    modalPost.value = { ...post };
    isEditing.value = true;
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
};

const addPost = async () => {
    try {
        const response = await axios.post('/api/add_post', modalPost.value);
        posts.value.push(response.data);
        closeModal();
        await loadPosts(pagination.value.page);
    } catch (error) {
        console.error('Ошибка при добавлении должности:', error);
    }
};

const updatePost = async () => {
    try {
        await axios.put(`/api/update_post/${modalPost.value.id}`, modalPost.value);
        const index = posts.value.findIndex(post => post.id === modalPost.value.id);
        if (index !== -1) {
            posts.value[index] = modalPost.value;
        }
        closeModal();
        await loadPosts(pagination.value.page);
    } catch (error) {
        console.error('Ошибка при обновлении должности:', error);
    }
};

const removePost = async (id) => {
    try {
        await axios.delete(`/api/delete_post/${id}`);
        posts.value = posts.value.filter(post => post.id !== id);
        await loadPosts(pagination.value.page);
    } catch (error) {
        console.error('Ошибка при удалении должности:', error);
    }
};

const openPostInfoModal = async (id) => {
    try {
        const response = await axios.get(`/api/get_post_information/${id}`);
        postInfo.value = response.data;
        isInfoModalOpen.value = true;
    } catch (error) {
        console.error('Ошибка при получении информации о должности:', error);
    }
};

const closeInfoModal = () => {
    isInfoModalOpen.value = false;
    postInfo.value = null;
};
</script>