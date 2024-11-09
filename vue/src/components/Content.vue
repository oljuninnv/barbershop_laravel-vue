<template>

    <!-- О нас -->
<section class="px-4 md:px-[25%]">
    <div class="flex justify-center m-auto mt-5">
        <p class="text-[18px] text-center">Наша команда профессиональных барберов может предложить профессиональные услуги стрижки и бритья для мужчин всех возрастов. Наша цель - создать уникальный и неповторимый образ для каждого клиента, подчеркнуть его индивидуальность и стиль.</p>
    </div>
</section>

<!-- Почему мы -->
<section class="px-4 md:px-[15%]">
    <div>
        <div class="flex m-auto justify-center mt-5">
            <h2 class="text-[26px] mb-5">Почему мы?</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
            <div class="flex flex-col rounded-lg bg-white text-surface shadow-secondary-1 dark:bg-surface-dark dark:text-white">
                <img class="rounded-t-lg h-[100px] mx-auto" src="/public/img/cup.svg" alt="Cup of tea" />
                <div class="p-6 text-center">
                    <h5 class="mb-2 text-xl font-medium leading-tight">Уютная атмосфера</h5>
                </div>
            </div>

            <div class="flex flex-col rounded-lg bg-white text-surface shadow-secondary-1 dark:bg-surface-dark dark:text-white">
                <img class="rounded-t-lg h-[100px] mx-auto" src="/public/img/masters.svg" alt="Masters" />
                <div class="p-6 text-center">
                    <h5 class="mb-2 text-xl font-medium leading-tight">Профессионализм</h5>
                </div>
            </div>

            <div class="flex flex-col rounded-lg bg-white text-surface shadow-secondary-1 dark:bg-surface-dark dark:text-white">
                <img class="rounded-t-lg h-[100px] mx-auto" src="/public/img/talk.svg" alt="Skyscrapers" />
                <div class="p-6 text-center">
                    <h5 class="mb-2 text-xl font-medium leading-tight">Коммуникабельность</h5>
                </div>
            </div>

            <div class="flex flex-col rounded-lg bg-white text-surface shadow-secondary-1 dark:bg-surface-dark dark:text-white">
                <img class="rounded-t-lg h-[100px] mx-auto" src="/public/img/britva.svg" alt="Los Angeles Skyscrapers" />
                <div class="p-6 text-center">
                    <h5 class="mb-2 text-xl font-medium leading-tight">Профессиональное оборудование</h5>
                </div>
            </div>
        </div>
    </div>
</section>

    <!-- Услуги -->
<section class="px-4 md:px-[20%] lg:px-[25%]" id="services">
    <div class="flex m-auto justify-center mt-5">
        <h2 class="text-[26px] mb-5">Услуги</h2>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        <div class="flex rounded-lg bg-white text-surface shadow-secondary-1 dark:bg-surface-dark dark:text-white p-4">
            <img class="rounded-t-lg h-[80px] mr-4" src="/public/img/vzroslay.svg" alt="Взрослая стрижка" />
            <div class="flex flex-col justify-between w-full">
                <h5 class="mb-2 text-xl font-medium leading-tight">Мужская стрижка</h5>
                <div class="text-[18px]">1500 Руб</div>
            </div>
        </div>

        <div class="flex rounded-lg bg-white text-surface shadow-secondary-1 dark:bg-surface-dark dark:text-white p-4">
            <img class="rounded-t-lg h-[80px] mr-4" src="/public/img/kids.svg" alt="Детская стрижка" />
            <div class="flex flex-col justify-between w-full">
                <h5 class="mb-2 text-xl font-medium leading-tight">Укладка причёски</h5>
                <div class="text-[18px]">1000 Руб</div>
            </div>
        </div>

        <div class="flex rounded-lg bg-white text-surface shadow-secondary-1 dark:bg-surface-dark dark:text-white p-4">
            <img class="rounded-t-lg h-[80px] mr-4" src="/public/img/beard.svg" alt="Стрижка бороды" />
            <div class="flex flex-col justify-between w-full">
                <h5 class="mb-2 text-xl font-medium leading-tight">Стрижка бороды</h5>
                <div class="text-[18px]">800 Руб</div>
            </div>
        </div>

        <div class="flex rounded-lg bg-white text-surface shadow-secondary-1 dark:bg-surface-dark dark:text-white p-4">
            <img class="rounded-t-lg h-[80px] mr-4" src="/public/img/knife.svg" alt="Бритьё шеи" />
            <div class="flex flex-col justify-between w-full">
                <h5 class="mb-2 text-xl font-medium leading-tight">Бритьё шеи</h5>
                <div class="text-[18px]">500 Руб</div>
            </div>               
        </div>

        <div class="flex rounded-lg bg-white text-surface shadow-secondary-1 dark:bg-surface-dark dark:text-white p-4">
            <img class="rounded-t-lg h-[80px] mr-4" src="/public/img/losion.svg" alt="Увлажнение головы" />
            <div class="flex flex-col justify-between w-full">
                <h5 class="mb-2 text-xl font-medium leading-tight">Увлажнение головы</h5>
                <div class="text-[18px]">300 Руб</div>
            </div>             
        </div>

        <div class="flex rounded-lg bg-white text-surface shadow-secondary-1 dark:bg-surface-dark dark:text-white p-4">
            <img class="rounded-t-lg h-[80px] mr-4" src="/public/img/Barbershop.svg" alt="Уход за бородой" />
            <div class="flex flex-col justify-between w-full">
                <h5 class="mb-2 text-xl font-medium leading-tight">Уход за бородой</h5>
                <div class="text-[18px]">1 500 Руб</div>
            </div>               
        </div>
    </div>          
</section>

    <!-- Барберы -->
    <section class="px-4 md:px-[20%] lg:px-[25%]" id="barbers">
    <div class="flex m-auto justify-center mt-5">
        <h2 class="text-[26px] mb-5">Наша команда</h2>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        <div class="mx-3 mt-6 flex flex-col rounded-lg bg-white text-surface shadow-secondary-1 dark:bg-surface-dark dark:text-white">
            <a href="#!">
                <img
                    class="rounded-t-lg w-full"
                    src="/public/img/barbers/barber1.svg"
                    alt="Константин Назаров" />
            </a>
            <div class="p-6">
                <h5 class="mb-2 text-xl font-medium leading-tight">Константин Назаров</h5>
                <p class="mb-4 text-base">Стаж: 8 лет</p>
            </div>
        </div>

        <div class="mx-3 mt-6 flex flex-col rounded-lg bg-white text-surface shadow-secondary-1 dark:bg-surface-dark dark:text-white">
            <a href="#!">
                <img
                    class="rounded-t-lg w-full"
                    src="/public/img/barbers/barber2.svg"
                    alt="Максим Петров" />
            </a>
            <div class="p-6">
                <h5 class="mb-2 text-xl font-medium leading-tight">Максим Петров</h5>
                <p class="mb-4 text-base">Стаж: 4 года</p>
            </div>
        </div>

        <div class="mx-3 mt-6 flex flex-col rounded-lg bg-white text-surface shadow-secondary-1 dark:bg-surface-dark dark:text-white">
            <a href="#!">
                <img
                    class="rounded-t-lg w-full"
                    src="/public/img/barbers/barber3.svg"
                    alt="Никита Кузнецов" />
            </a>
            <div class="p-6">
                <h5 class="mb-2 text-xl font-medium leading-tight">Никита Кузнецов</h5>
                <p class="mb-4 text-base">Стаж: 12 лет</p>
            </div>
        </div>
    </div>
</section>

    <!-- Контакты -->
<section class="px-4 md:px-[20%]" id="contacts">
    <div class="flex m-auto justify-center mt-5">
        <h2 class="text-[26px] mb-5">Контакты</h2>
    </div>
    <div class="flex flex-col flex-wrap gap-5">
        <YandexMap></YandexMap>     
        <div class="flex flex-wrap gap-4 justify-center">
            <div class="flex items-center gap-2 mb-5 w-full sm:w-auto">
                <img src="/public/img/contacts/geo.svg" alt="Геолокация" class="w-[30px] h-[30px]">
                <p>г.Кемерово, ул.Мичурина, 58</p>
            </div>
            <div class="flex items-center gap-2 mb-5 w-full sm:w-auto">
                <img src="/public/img/contacts/phone.svg" alt="Телефон" class="w-[30px] h-[30px]">
                <p>+7 000 000 00 00</p>
            </div>
            <div class="flex items-center gap-2 mb-5 w-full sm:w-auto">
                <a href="#" class="hover:translate-y-1 ease-out duration-500 active:animate-bounce">
                    <img src="/public/img/contacts/inst.svg" alt="Инстаграмм" class="w-[30px] h-[30px]">
                </a>
                <p>@man_style</p>
            </div>
            <div class="flex items-center gap-2 mb-5 w-full sm:w-auto">
                <img src="/public/img/contacts/hours.svg" alt="Часы работы" class="w-[30px] h-[30px]">
                <p>9:00 - 21:00</p>
            </div>
        </div>
    </div>
</section>

<!-- Запись -->
<section class="px-4 md:px-[10%] bg-red-500 text-white flex flex-col md:flex-row justify-center items-center gap-10 pt-10 pb-10 mb-10">
    <div class="text-center md:text-left">
        <h2 class="text-lg md:text-xl">
            Готовы обновить свой стиль? Запишитесь онлайн прямо сейчас.
            Нажмите здесь, чтобы записаться!
        </h2>
    </div>
    <div class="hover:translate-y-1 ease-out duration-500 active:animate-bounce">
        <a href="/record" class="bg-black text-[20px] p-2 rounded">Записаться</a>
    </div>
</section>

<div class="text-white bg-[#000000] text-[20px]">
    <p>© Copyright 2024 barbershop - All right reserved</p>
</div>

</template>

<script>
    import YandexMap from './Map.vue';

    export default {
    name: 'App',
    components: {
        YandexMap
    }
    }
</script>