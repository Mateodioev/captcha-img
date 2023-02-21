<?php

use Mateodioev\CaptchaImg\Captcha;

$captcha = new Captcha;
$captcha->setFont('path/to/font.ttf')
    ->setBackground('path/to/background.jpg');

$captcha->draw(rand(900, 1000), rand(400, 500), rand(60, 80)); // Crea el texto, y dibuja la imagen
$data = $captcha->show(); // Asigna los datos de la imagen a un array
$captcha->render(); // Muestra la imagen

unlink($data['route']); // Elimina la imagen