<?php

/** 
 * @author https://github.com/Mateodioev
*/

class Captcha {

    private $length;
    private $code;
    private $font;
    public $dir;
  
    public function __construct($length = 7) {
      $this->length = (int) $length;

      $fonts = glob('src/fonts/*.ttf'); // random font
      $this->font = $fonts[array_rand($fonts)];
    }
  
    private function GenCode() {
      $id = '1234567890qwertyuiopasdfghjklzxcvbnmWERTYUIOPASDFGHJKLZXCVBNM';
      $length_id = strlen($id) - 1;
      $result = '';

      for ($i=0; $i < $this->length; $i++) { 
          $result .= $id[mt_rand(0, $length_id)];
      }

      $this->code = $result;

    }

    public function draw ($width = 1000, $height = 500, $fontsize = 90) {

        $this->GenCode();

        $captcha = imagecreatetruecolor($width, $height);
        // Background for the image
        $back = glob('src/backgrounds/*.jpg'); // random background image
        $background = $back[array_rand($back)];

        list($bx, $by) = getimagesize($background);
        if ($bx-$width<0) { $bx = 0; }
        else { $bx = rand(0, $bx-$width); }
        if ($by-$height<0) { $by = 0; }
        else { $by = rand(0, $by-$height); }
        $background = imagecreatefromjpeg($background);
        imagecopy($captcha, $background, 0, 0, $bx, $by, $width, $height);

        // THE TEXT SIZE
        $text_size = imagettfbbox($fontsize, 0, $this->font, $this->code);
        $text_width = max([$text_size[2], $text_size[4]]) - min([$text_size[0], $text_size[6]]);
        $text_height = max([$text_size[5], $text_size[7]]) - min([$text_size[1], $text_size[3]]);

        // CENTERING THE TEXT BLOCK
        $centerX = CEIL(($width - $text_width) / 2);
        $centerX = $centerX<0 ? 0 : $centerX;
        $centerX = CEIL(($height - $text_height) / 2);
        $centerY = $centerX<0 ? 0 : $centerX;

        // RANDOM OFFSET POSITION OF THE TEXT + COLOR
        if (rand(0,1)) { $centerX -= rand(0,55); }
        else { $centerX += rand(0,55); }
        $colornow = imagecolorallocate($captcha, rand(120,255), rand(120,255), rand(120,255)); // Random bright color
        imagettftext($captcha, $fontsize, rand(-10,10), $centerX, $centerY, $colornow, $this->font, $this->code);

        $this->dir = 'img/'.uniqid().'.png';
        imagejpeg($captcha, $this->dir);
        imagedestroy($captcha);
    }

    public function showDatas() {
        return [
          'route' => $this->dir,
          'text' => $this->code
        ];
    }

    public function showImg() {
      header('Content-type: image/png');
      $img = imagecreatefromjpeg($this->dir);
      imagejpeg($img);
      imagedestroy($img);
    }

}

