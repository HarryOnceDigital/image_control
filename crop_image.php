<?php
// Obtener la URL de la imagen de la consulta GET
$imageUrl = isset($_GET['url']) ? $_GET['url'] : null;

// Verificar si la URL de la imagen está presente
if (!$imageUrl) {
    die('Error: Debes proporcionar la URL de la imagen.');
}

// Obtener información sobre la imagen
$imageInfo = getimagesize($imageUrl);

// Verificar si la función getimagesize fue exitosa y si el tipo de imagen es compatible
if ($imageInfo !== false && in_array($imageInfo[2], [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_WEBP], true)) {
    // Cargar la imagen desde la URL
    $image = imagecreatefromstring(file_get_contents($imageUrl));

    // Obtener las dimensiones originales de la imagen
    $originalWidth = imagesx($image);
    $originalHeight = imagesy($image);

    // Coordenadas de inicio y tamaño del recorte (puedes ajustar estos valores según tus necesidades)
    $x = 50;
    $y = 0;
    $width = 1220;
    $height = 250;

    // Crear una nueva imagen con el recorte
    $croppedImage = imagecrop($image, ['x' => $x, 'y' => $y, 'width' => $width, 'height' => $height]);

    // Verificar si el recorte fue exitoso
    if ($croppedImage !== false) {
        // Encabezado para indicar que la salida es una imagen JPEG
        header('Content-Type: ' . $imageInfo['mime']);

        // Mostrar la imagen recortada en el navegador
        switch ($imageInfo[2]) {
            case IMAGETYPE_JPEG:
                imagejpeg($croppedImage, null, 100);
                break;
            case IMAGETYPE_PNG:
                imagepng($croppedImage, null, 9); // Usar 9 para la máxima compresión en formato PNG
                break;
            case IMAGETYPE_WEBP:
                imagewebp($croppedImage, null, 100);
                break;
        }

        // Liberar la memoria utilizada por las imágenes
        imagedestroy($image);
        imagedestroy($croppedImage);
    } else {
        echo "Error al recortar la imagen.";
    }
} else {
    echo "Error: El tipo de imagen no es compatible o la URL de la imagen no es válida.";
}
?>
