<template>
    <form class="flex flex-col gap-5">
                <div>
                    <label for="Name" class="mb-2 block text-sm font-bold text-gray-700">Имя</label>
                    <input type="text" id="Name" value="Константин Назаров"
                        class="input_text" />
                </div>
                <div>
                    <label for="email" class="mb-2 block text-sm font-bold text-gray-700">Email</label>
                    <input type="email" id="email" value="m251n@yahoo.com"
                        class="input_text" />
                </div>
                <div>
                    <label for="password" class="mb-2 block text-sm font-bold text-gray-700">Телефон</label>
                    <input type="text" id="phone" value="84563452211"
                        class="input_text" />
                </div>
                <div class="flex justify-end">
                    <button
                        class="focus:shadow-outline-red rounded bg-red-500 px-4 py-2 text-white hover:bg-red-600 focus:outline-none"
                        type="button">Сохранить изменения</button>
                </div>
            </form>
</template>