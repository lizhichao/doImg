<?php

namespace OneImg;

class Border
{
    protected $img;
    protected $width  = 0;
    protected $height = 0;

    protected function gray($r, $g, $b)
    {
        return 0.3 * $r + 0.59 * $g + 0.11 * $b;
    }

    public function setImg($src)
    {
        $data         = file_get_contents($src);
        $this->img    = imagecreatefromstring($data);
        $img_info     = getimagesizefromstring($data);
        $this->width  = intval($img_info[0]);
        $this->height = intval($img_info[1]);
        return $this;
    }

    public function save($src = null)
    {
        if ($src) {
            imagejpeg($this->img, $src, 90);
        } else {
            header('Content-Type: image/jpeg');
            imagejpeg($this->img);
        }

    }

    protected function getRgb($x, $y)
    {
        $rgb = imagecolorat($this->img, $x, $y);
        return [($rgb >> 16) & 0xFF, ($rgb >> 8) & 0xFF, $rgb & 0xFF];
    }

    public function scale($w, $h)
    {
        if ($this->width <= $w && $this->height <= $h) {
            return $this;
        }
        if ($this->width / $w > $this->height / $h) {
            $nw = $w;
            $nh = $w * $this->height / $this->width;
        } else {
            $nh = $h;
            $nw = $h * $this->width / $this->height;
        }
        $img = imagecreatetruecolor($nw, $nh);
        imagecopyresampled($img, $this->img, 0, 0, 0, 0, $nw, $nh, $this->width, $this->height);
        $this->img    = $img;
        $this->width  = intval($nw);
        $this->height = intval($nh);
        return $this;
    }

    protected $value = [];

    public function outline()
    {
        $new_img = imagecreatetruecolor($this->width, $this->height);

        $mx = $this->width;
        $my = $this->height;
        for ($x = 0; $x < $mx; $x++) {
            for ($y = 0; $y < $my; $y++) {
                $h1 = $this->gray(...$this->getRgb($x, $y));
                $h2 = $this->gray(...$this->getRgb($x + 1 >= $mx ? $x : $x + 1, $y));
                $h3 = $this->gray(...$this->getRgb($x, $y + 1 >= $my ? $y : $y + 1));
                $v  = max(abs($h1 - $h2), abs($h1 - $h3));
                if ($v > 24) {
                    $c = 255;
                } else if ($v > 12) {
                    $c = 128;
                } else {
                    $c = 0;
                }
                $this->value[$x][$y] = $c;
                $color               = imageColorAllocate($this->img, $c, $c, $c);
                imagesetpixel($new_img, $x, $y, $color);
            }
        }
        $this->img = $new_img;
        return $this;
    }

    public function light()
    {
        $new_img = imagecreatetruecolor($this->width, $this->height);
        $mx      = $this->width;
        $my      = $this->height;
        for ($x = 0; $x < $mx; $x++) {
            $row = $this->value[$x];
            $p   = 0;
            $y1  = 0;
            foreach ($row as $y => $v) {
                if ($v > $p) {
                    $p  = $v;
                    $y1 = $y;
                }
                if ($p > 0 && $v === 0) {
                    $color = imageColorAllocate($this->img, $p, $p, $p);
                    imagesetpixel($new_img, $x, $y1, $color);
                    $p = 0;
                }
            }
        }

        for ($y = 0; $y < $my; $y++) {
            $p  = 0;
            $x1 = 0;
            for ($x = 0; $x < $mx; $x++) {
                $v = $this->value[$x][$y];
                if ($v > $p) {
                    $p  = $v;
                    $x1 = $x;
                }
                if ($p > 0 && $v === 0) {
                    $color = imageColorAllocate($this->img, $p, $p, $p);
                    imagesetpixel($new_img, $x1, $y, $color);
                    $p = 0;
                }
            }
        }

        $this->img = $new_img;
        return $this;
    }
}
