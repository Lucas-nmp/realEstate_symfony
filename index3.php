<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Múltiples Carruseles de Productos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f9;
        }

        .products-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .product-carousel {
            width: 30%;
            max-width: 400px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .carousel-container {
            position: relative;
        }

        .main-image-container {
            position: relative;
            width: 100%;
            height: 200px;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #000;
            border-radius: 8px;
        }

        .main-image-container img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .thumbnails-container {
            display: flex;
            gap: 10px;
            margin-top: 10px;
            overflow-x: auto;
        }

        .thumbnails-container img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            cursor: pointer;
            border: 2px solid transparent;
            border-radius: 4px;
        }

        .thumbnails-container img.active {
            border-color: #007bff;
        }

        .carousel-buttons {
            position: absolute;
            top: 50%;
            width: 100%;
            display: flex;
            justify-content: space-between;
            transform: translateY(-50%);
        }

        .carousel-buttons button {
            background-color: rgba(0, 0, 0, 0.5);
            color: #fff;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 4px;
        }

        .carousel-buttons button:hover {
            background-color: rgba(0, 0, 0, 0.8);
        }

        h2 {
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Galería de Productos</h1>

    <div class="products-container">
        <?php
        // Simulando productos con imágenes en sus respectivas carpetas
        $products = [
            'Producto 1' => 'uploads/product1/',
            'Producto 2' => 'uploads/product2/',
            'Producto 3' => 'uploads/product3/',
            'Producto 4' => 'uploads/product4/',
            'Producto 5' => 'uploads/product5/'
        ];

        foreach ($products as $productName => $productFolder) {
            $images = array_values(array_diff(scandir($productFolder), ['.', '..'])); // Filtrar imágenes válidas

            if (!empty($images)) {
                echo '<div class="product-carousel">';
                echo "<h2>$productName</h2>";
                echo '<div class="carousel-container">';
                echo '<div class="main-image-container">';
                echo '<img class="main-image" src="' . $productFolder . $images[0] . '" alt="Imagen principal">';
                echo '</div>';

                echo '<div class="carousel-buttons">';
                echo '<button class="prev-btn"><</button>';
                echo '<button class="next-btn">></button>';
                echo '</div>';

                echo '<div class="thumbnails-container">';
                foreach ($images as $index => $image) {
                    $activeClass = $index === 0 ? 'active' : '';
                    echo '<img src="' . $productFolder . $image . '" alt="Miniatura" class="' . $activeClass . '" data-index="' . $index . '">';
                }
                echo '</div>';
                echo '</div>';
                echo '<p>Lorem Ipsum es simplemente el texto de relleno de las imprentas y archivos de texto. Lorem Ipsum ha sido el texto de relleno estándar de las industrias desde el año 1500, cuando un impresor (N. del T. persona que se dedica a la imprenta) desconocido usó una galería de textos y los mezcló de tal manera que logró hacer un libro de textos especimen. No sólo sobrevivió 500 años, sino que tambien ingresó como texto de relleno en documentos electrónicos, quedando esencialmente igual al original. Fue popularizado en los 60s con la creación de las hojas "Letraset", las cuales contenian pasajes de Lorem Ipsum, y más recientemente con software de autoedición, como por ejemplo Aldus PageMaker, el cual incluye versiones de Lorem Ipsum.</p>';
                
                echo '<p>HOla 2</p>';
                echo '</div>';
                
            }
        }
        ?>
    </div>

    <script>
        document.querySelectorAll('.product-carousel').forEach(carousel => {
            const mainImage = carousel.querySelector('.main-image');
            const thumbnails = carousel.querySelectorAll('.thumbnails-container img');
            const prevBtn = carousel.querySelector('.prev-btn');
            const nextBtn = carousel.querySelector('.next-btn');
            let currentIndex = 0;

            // Actualizar imagen principal y miniaturas activas
            function updateMainImage(index) {
                currentIndex = index;
                mainImage.src = thumbnails[index].src;
                thumbnails.forEach(img => img.classList.remove('active'));
                thumbnails[index].classList.add('active');
            }

            // Eventos de clic en las miniaturas
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

            // Marcar la primera miniatura como activa
            if (thumbnails.length > 0) {
                thumbnails[0].classList.add('active');
            }
        });
    </script>
</body>
</html>
