<?php

namespace Mateodioev\CaptchaImg;

/** 
 * @author https://github.com/Mateodioev
*/
class Captcha {
    private string $code;
    private string $font;
    private string $background;
    public string $fileName;

    /**
     * @param int $length The length of the captcha code
     */
    public function __construct(
        protected int $length = 7
    ) {}

    /**
     * Set the font file
     */
    public function setFont(string $fontFile): static
    {
        $this->font = $fontFile;
        return $this;
    }

    /**
     * Chose a random font from a directory
     */
    public function choseFontFromDir(string $dir, string $ext = 'ttf'): static
    {
        $fonts = glob($dir . '/*.' . $ext);

        return $this->setFont($fonts[array_rand($fonts)]);
    }

    /**
     * Set background image file
     */
    public function setBackground(string $file): static
    {
        $this->background = $file;
        return $this;
    }

    /**
     * Generate the captcha code
     */
    private function genCode(): void
    {
        $id = '1234567890qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM';
        $length_id = strlen($id) - 1;
        $result = '';

        for ($i=0; $i < $this->length; $i++) {
            $result .= $id[mt_rand(0, $length_id)];
        }

        $this->code = $result;
    }

    public function draw ($width = 1000, $height = 500, $fontsize = 90): void
    {
        $this->genCode();

        $captcha = imagecreatetruecolor($width, $height);

        list($bx, $by) = getimagesize($this->background);

        if ($bx-$width<0) {
            $bx = 0;
        } else {
            $bx = rand(0, $bx-$width);
        }
        if ($by-$height<0) {
            $by = 0;
        } else {
            $by = rand(0, $by-$height);
        }
        $this->background = imagecreatefromjpeg($this->background);
        imagecopy($captcha, $this->background, 0, 0, $bx, $by, $width, $height);

        // THE TEXT SIZE
        $text_size = imagettfbbox($fontsize, 0, $this->font, $this->code);
        $text_width = max([$text_size[2], $text_size[4]]) - min([$text_size[0], $text_size[6]]);
        $text_height = max([$text_size[5], $text_size[7]]) - min([$text_size[1], $text_size[3]]);

        // CENTERING THE TEXT BLOCK
        $centerX = ceil(($width - $text_width) / 2);
        $centerX = max($centerX, 0);
        $centerY = ceil(($height - $text_height) / 2);
        $centerY = max($centerY, 0);

        // RANDOM OFFSET POSITION OF THE TEXT + COLOR
        if (rand(0,1)) {
            $centerX -= rand(0,55);
        }
        else {
            $centerX += rand(0,55);
        }
        $colorNow = imagecolorallocate($captcha, rand(120,255), rand(120,255), rand(120,255)); // Random bright color
        imagettftext($captcha, $fontsize, rand(-10,10), $centerX, $centerY, $colorNow, $this->font, $this->code);

        $this->fileName = 'img/'.uniqid().'.png';
        imagejpeg($captcha, $this->fileName);
        imagedestroy($captcha);
    }

    /**
     * @return array The route and the text of the captcha
     */
    public function show(): array
    {
        return [
          'route' => $this->fileName,
          'text' => $this->code
        ];
    }

    /**
     * Render the captcha image in the browser
     */
    public function render(): void
    {
      header('Content-type: image/png');
      $img = imagecreatefromjpeg($this->fileName);
      imagejpeg($img);
      imagedestroy($img);
    }

}

