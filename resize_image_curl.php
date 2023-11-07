<?php
if (isset($_GET['url']) && isset($_GET['w']) && isset($_GET['h'])) {
    // Obtén la URL de la imagen y los valores de ancho y alto desde los parámetros GET
    $image_url = $_GET['url'];
    $width = intval($_GET['w']); // Convierte el valor de ancho a un entero
    $height = intval($_GET['h']); // Convierte el valor de alto a un entero

    // Verifica si la extensión cURL está habilitada en PHP
    if (!extension_loaded('curl')) {
        die('La extensión cURL no está habilitada en PHP. Debes habilitarla para que este script funcione.');
    }

    // Verifica si la extensión GD está habilitada en PHP
    if (!extension_loaded('gd')) {
        die('La extensión GD y/o GD2 no está habilitada en PHP. Debes habilitarla para que este script funcione.');
    }

    // Inicializa una instancia cURL
    $ch = curl_init();

    // Desactiva la verificación del certificado SSL para permitir conexiones a sitios con certificados no válidos
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    // Configura la URL a la que se realizará la solicitud
    curl_setopt($ch, CURLOPT_URL, $image_url);

    // Establece que la respuesta se almacene en una variable en lugar de mostrarse directamente
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Realiza la solicitud cURL y obtiene los datos de la imagen
    $image_data = curl_exec($ch);

    // Verifica si se ha producido un error al obtener la imagen
    if ($image_data === false) {
        die('Error al obtener la imagen: ' . curl_error($ch));
    } else {
        // Crea una imagen a partir de los datos obtenidos
        $original_image = imagecreatefromstring($image_data);

        // Verifica si se ha producido un error al crear la imagen desde los datos obtenidos
        if ($original_image === false) {
            die('Error al crear la imagen desde los datos obtenidos.');
        }

        // Redimensiona la imagen al ancho y alto especificados
        $resized_image = imagescale($original_image, $width, $height);

        // Verifica si se ha producido un error al redimensionar la imagen
        if ($resized_image === false) {
            die('Error al redimensionar la imagen.');
        }

        // Establece las cabeceras de respuesta para que el navegador interprete el contenido como una imagen JPEG
        header('Content-Type: image/jpeg');

        // Muestra la imagen redimensionada
        imagejpeg($resized_image);

        // Libera la memoria de las imágenes
        imagedestroy($original_image);
        imagedestroy($resized_image);
    }

    // Cierra la instancia cURL
    curl_close($ch);
} else {
    echo "Se deben proporcionar la URL de la imagen, el ancho (w) y el alto (h) como parámetros en la URL.";
}
?>
