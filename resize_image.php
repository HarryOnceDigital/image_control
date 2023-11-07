<?php
/*if (function_exists('gd_info')) {
    echo 'GD is supported.';
} else {
    echo 'GD is not supported.';
}*/
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Ruta de la carpeta que contiene las imágenes
$imageFolder = 'images/';

// Nombre del archivo de imagen (puede ser proporcionado en la URL)
$filename = isset($_GET['file']) ? $_GET['file'] : '';

// Ruta completa de la imagen
$imagePath = $imageFolder . $filename;

// Obtener el ancho y alto de la imagen original
list($originalWidth, $originalHeight) = getimagesize($imagePath);


if (!file_exists($imagePath)) {
    // Manejar errores o mostrar una imagen predeterminada en caso de que el archivo no exista
    $imagePath = 'images/icon.png';
    list($originalWidth, $originalHeight) = getimagesize($imagePath);
    $h = intval($originalHeight);
    $w = intval($originalWidth);
    
}else{
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
    //Aquí se pueden meter otros formatos de imagen o en su caso una respuesta de excepción
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
?>
