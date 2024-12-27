import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');


window.onload = () => {
    document.querySelectorAll('.product-carousel').forEach(carousel => {
        const mainImage = carousel.querySelector('.main-image');
        const thumbnails = carousel.querySelectorAll('.thumbnails-container img');
        const prevBtn = carousel.querySelector('.prev-btn');
        const nextBtn = carousel.querySelector('.next-btn');
        let currentIndex = 0;

        if (!mainImage || thumbnails.length === 0 || !prevBtn || !nextBtn) {
            console.warn('Faltan elementos en el carrusel:', carousel);
            return; // Salir si algún elemento no está presente
        }

        // Actualizar imagen principal y miniaturas activas
        function updateMainImage(index) {
            currentIndex = index;
            mainImage.src = thumbnails[index].src;
            thumbnails.forEach(img => img.classList.remove('active'));
            thumbnails[index].classList.add('active');
        }

        // Evento de clic en miniaturas
        thumbnails.forEach((thumbnail, index) => {
            thumbnail.addEventListener('click', () => {
                updateMainImage(index);
            });
        });

        // Botón anterior
        prevBtn.addEventListener('click', () => {
            const newIndex = (currentIndex - 1 + thumbnails.length) % thumbnails.length;
            updateMainImage(newIndex);
        });

        // Botón siguiente
        nextBtn.addEventListener('click', () => {
            const newIndex = (currentIndex + 1) % thumbnails.length;
            updateMainImage(newIndex);
        });

        // Marcar la primera miniatura como activa y establecer la imagen principal inicial
        if (thumbnails.length > 0) {
            thumbnails[0].classList.add('active');
            mainImage.src = thumbnails[0].src;
        }
    });
};
