Codigos captcha
=======

![](https://i.imgur.com/35T2UP7.png)

- Genera imagenes aleatorias que pueden ser usadas como un captcha simple
  - Características: 
    1. Tres fuentes diferentes (18thCentury, 39 Smooth, Arial)
    2. Dos backgrounds

Genera la imagen
---------

```php
$captcha = new Captcha;
$captcha->draw();
```


Mostrar datos de la imagen
--------

```php
$data = $captcha->showDatas();
echo json_encode($data);
```


Mostrar la imagen
---------

```php
$captcha->showImg();
```


