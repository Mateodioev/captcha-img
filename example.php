<?php

require_once 'captcha.php';

$captcha = new Captcha;
$captcha->draw(rand(900, 1000), rand(400, 500), rand(60, 80)); // Crea el texto, y dibuja la imagen
$data = $captcha->showDatas(); // Asigna los datos de la imagen a un array
$captcha->showImg(); // Muestra la imagen

unlink($data['route']); // Elimina la imagen