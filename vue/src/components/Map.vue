<template>
  <div id="map" class="w-full h-[400px]"></div>
</template>
  
  <script>
import { YANDEX_API_KEY } from '/api.js';
export default {
  name: 'YandexMap',
  mounted() {
    this.initMap();
  },
  methods: {
    initMap() {
      const script = document.createElement('script');
      script.src ="https://api-maps.yandex.ru/2.1/?lang=ru_RU&apikey=${YANDEX_API_KEY}";
      script.onload = () => {
        ymaps.ready(() => {
          const myMap = new ymaps.Map("map", {
            center: [55.342515, 86.083581], // Центр карты
            zoom: 17, // Начальный уровень зума
            controls: ['zoomControl'], // Оставляем только контрол зума
          });

          const myPlacemark = new ymaps.Placemark([55.342515, 86.083581], {
            hintContent: 'Маркер',
            balloonContent: 'Г.Кемерово,ул.Мичурина 58К1'
          });

          myMap.geoObjects.add(myPlacemark);
        });
      };
      document.head.appendChild(script);
    }
  }
}
  </script>