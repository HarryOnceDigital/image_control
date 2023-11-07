<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// URL de la imagen (puede ser proporcionado en la URL)
$imageUrl = isset($_GET['url']) ? $_GET['url'] : '';
$filename = isset($_GET['file']) ? $_GET['file'] : '';

if (!empty($imageUrl)) {
    // Si se proporciona una URL de imagen, descarga la imagen y crea una imagen desde la URL
    $imageData = file_get_contents($imageUrl);
    if ($imageData !== false) {
        // Crea una imagen desde los datos descargados
        $image = imagecreatefromstring($imageData);
        
        // Verifica si se proporcionaron dimensiones personalizadas
        $w = isset($_GET['w']) ? intval($_GET['w']) : imagesx($image); // Ancho original si no se proporciona
        $h = isset($_GET['h']) ? intval($_GET['h']) : imagesy($image); // Alto original si no se proporciona
        
        // Crea una nueva imagen redimensionada
        $newImage = imagecreatetruecolor($w, $h);
        
        // Redimensiona la imagen original a las nuevas dimensiones
        imagecopyresized($newImage, $image, 0, 0, 0, 0, $w, $h, imagesx($image), imagesy($image));
        
        // Establecer el encabezado Content-Type para la imagen resultante
        header('Content-Type: image/jpeg'); // Puedes ajustar esto según el formato de tu imagen
        
        // Mostrar la imagen redimensionada
        imagejpeg($newImage);
        
        // Liberar recursos
        imagedestroy($image);
        imagedestroy($newImage);
        exit(); // Terminar la ejecución del script
    }
} else {
    // Si no se proporciona una URL de imagen, utiliza el archivo local
    // Ruta de la carpeta que contiene las imágenes
    $imageFolder = 'images/';
    // Ruta completa de la imagen
    $imagePath = $imageFolder . $filename;
    // Manejar el error de descarga de datos
    $error = error_get_last();
    if ($error !== null) {
        // Mostrar información de error
        echo "Error al descargar los datos de la imagen: " . $error['message'];
    } else {
        echo "Error desconocido al descargar los datos de la imagen.";
    }
}

// Obtener el ancho y alto de la imagen original
list($originalWidth, $originalHeight) = getimagesize($imagePath);

if (!file_exists($imagePath)) {
    // Manejar errores o mostrar una imagen predeterminada en caso de que el archivo no exista
    $imagePath = 'images/icon.png';
    list($originalWidth, $originalHeight) = getimagesize($imagePath);
    $h = intval($originalHeight);
    $w = intval($originalWidth);
} else {
    // Ancho y alto predeterminados
    $w = isset($_GET['w']) ? intval($_GET['w']) : $originalWidth;
    $h = isset($_GET['h']) ? intval($_GET['h']) : $originalHeight;
}

// Crear una nueva imagen redimensionada
$newImage = imagecreatetruecolor($w, $h);

// Seleccionar la función de creación de la imagen basada en el tipo de archivo
if (pathinfo($imagePath, PATHINFO_EXTENSION) == 'jpg' || pathinfo($imagePath, PATHINFO_EXTENSION) == 'jpeg') {
    $image = imagecreatefromjpeg($imagePath);
} elseif (pathinfo($imagePath, PATHINFO_EXTENSION) == 'png') {
    $image = imagecreatefrompng($imagePath);
} else {
    // Aquí se pueden agregar otros formatos de imagen o manejar una respuesta de excepción
}

// Redimensionar la imagen original a las nuevas dimensiones
imagecopyresized($newImage, $image, 0, 0, 0, 0, $w, $h, $originalWidth, $originalHeight);

// Establecer el encabezado Content-Type para la imagen resultante
header('Content-Type: image/jpeg'); // Puedes ajustar esto según el formato de tu imagen

// Mostrar la imagen redimensionada
imagejpeg($newImage);

// Liberar recursos
imagedestroy($image);
imagedestroy($newImage);