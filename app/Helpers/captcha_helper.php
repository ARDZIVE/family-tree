<?php
/**
 * CodeIgniter CAPTCHA Helper
 *
 * @package     CodeIgniter
 * @subpackage  Helpers
 * @category    Helpers
 * @author      EllisLab Dev Team
 * @modified    Updated for modern PHP compatibility
 */

declare(strict_types=1);

if (!function_exists('create_captcha')) {
    /**
     * Create CAPTCHA
     *
     * @param  array|string  $data      Data for the CAPTCHA
     * @param  string  $img_path  Path to create the image in
     * @param  string  $img_url   URL to the CAPTCHA image folder
     * @param  string  $font_path Server path to font
     * @return array|bool
     */
    function create_captcha(array|string $data = '', string $img_path = '', string $img_url = '', string $font_path = ''): array|bool
    {
        $defaults = [
            'word'        => '',
            'img_path'    => '',
            'img_url'     => '',
            'img_width'   => '150',
            'img_height'  => '30',
            'font_path'   => '',
            'expiration'  => 7200,
            'word_length' => 8,
            'font_size'   => 16,
            'img_id'      => '',
            'pool'        => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
            'colors'      => [
                'background' => [255, 255, 255],
                'border'    => [153, 102, 102],
                'text'      => [204, 153, 153],
                'grid'      => [255, 182, 182]
            ]
        ];

        // Convert string data to array if necessary
        $data = is_array($data) ? $data : [];

        // Merge defaults with provided data
        $config = array_merge($defaults, $data);
        extract($config);

        if (!extension_loaded('gd')) {
            log_message('error', 'create_captcha(): GD extension is not loaded.');
            return false;
        }

        if (empty($img_path) || empty($img_url)) {
            log_message('error', 'create_captcha(): $img_path and $img_url are required.');
            return false;
        }

        if (!is_dir($img_path) || !is_writable($img_path)) {
            log_message('error', "create_captcha(): '{$img_path}' is not a dir, nor is it writable.");
            return false;
        }

        // Clean up old images
        $now = microtime(true);
        $current_dir = @opendir($img_path);
        while ($filename = @readdir($current_dir)) {
            if (substr($filename, -4) === '.jpg' || substr($filename, -4) === '.png') {
                $base = str_replace(['.jpg', '.png'], '', $filename);
                if (($base + $expiration) < $now) {
                    @unlink($img_path . $filename);
                }
            }
        }
        @closedir($current_dir);

        // Generate word if not provided
        if (empty($word)) {
            $word = '';
            $pool_length = strlen($pool);
            $rand_max = $pool_length - 1;

            try {
                for ($i = 0; $i < $word_length; $i++) {
                    $word .= $pool[random_int(0, $rand_max)];
                }
            } catch (Exception $e) {
                // Fallback to more secure random bytes
                $security = get_instance()->security;
                $bytes = $security->get_random_bytes($pool_length);

                if ($bytes !== false) {
                    $byte_index = $word_index = 0;
                    while ($word_index < $word_length) {
                        if ($byte_index === $pool_length) {
                            $bytes = $security->get_random_bytes($pool_length);
                            if ($bytes === false) {
                                break;
                            }
                            $byte_index = 0;
                        }

                        $rand_index = ord($bytes[$byte_index++]);
                        if ($rand_index > $rand_max) {
                            continue;
                        }

                        $word .= $pool[$rand_index];
                        $word_index++;
                    }
                }

                // Final fallback to mt_rand if needed
                if (empty($word)) {
                    for ($i = 0; $i < $word_length; $i++) {
                        $word .= $pool[mt_rand(0, $rand_max)];
                    }
                }
            }
        }

        // Force word to be string
        $word = (string)$word;
        $length = strlen($word);

        // Calculate image properties
        $angle = ($length >= 6) ? mt_rand(-($length - 6), ($length - 6)) : 0;
        $x_axis = mt_rand(6, (int)((360 / $length) - 16));
        $y_axis = ($angle >= 0) ? mt_rand($img_height, $img_width) : mt_rand(6, $img_height);

        // Create image
        $im = imagecreatetruecolor($img_width, $img_height)
            ?: imagecreate($img_width, $img_height);

        // Allocate colors
        foreach (array_keys($defaults['colors']) as $key) {
            $colors[$key] = imagecolorallocate(
                $im,
                $colors[$key][0],
                $colors[$key][1],
                $colors[$key][2]
            );
        }

        // Fill background
        imagefilledrectangle($im, 0, 0, $img_width, $img_height, $colors['background']);

        // Generate spiral pattern
        $theta = 1;
        $thetac = 7;
        $radius = 16;
        $circles = 20;
        $points = 32;

        for ($i = 0, $cp = ($circles * $points) - 1; $i < $cp; $i++) {
            $theta += $thetac;
            $rad = $radius * ($i / $points);
            $x = ($rad * cos($theta)) + $x_axis;
            $y = ($rad * sin($theta)) + $y_axis;
            $theta += $thetac;
            $rad1 = $radius * (($i + 1) / $points);
            $x1 = ($rad1 * cos($theta)) + $x_axis;
            $y1 = ($rad1 * sin($theta)) + $y_axis;
            imageline($im, (int)$x, (int)$y, (int)$x1, (int)$y1, $colors['grid']);
            $theta -= $thetac;
        }

        // Add text
        $use_font = ($font_path !== '' && file_exists($font_path) && function_exists('imagettftext'));

        if (!$use_font) {
            $font_size = min($font_size, 5);
            $x = mt_rand(0, (int)($img_width / ($length / 3)));
            $y = 0;
        } else {
            $font_size = min($font_size, 30);
            $x = mt_rand(0, (int)($img_width / ($length / 1.5)));
            $y = $font_size + 2;
        }

        for ($i = 0; $i < $length; $i++) {
            if (!$use_font) {
                $y = mt_rand(0, (int)($img_height / 2));
                imagestring($im, $font_size, (int)$x, (int)$y, $word[$i], $colors['text']);
                $x += ($font_size * 2);
            } else {
                $y = mt_rand((int)($img_height / 2), $img_height - 3);
                imagettftext($im, (float)$font_size, (float)$angle, (int)$x, (int)$y, $colors['text'], $font_path, $word[$i]);
                $x += $font_size;
            }
        }

        // Add border
        imagerectangle($im, 0, 0, $img_width - 1, $img_height - 1, $colors['border']);

        // Generate image file
        $img_url = rtrim($img_url, '/') . '/';
        $img_filename = $now . (function_exists('imagejpeg') ? '.jpg' : '.png');

        if (function_exists('imagejpeg')) {
            imagejpeg($im, $img_path . $img_filename);
        } elseif (function_exists('imagepng')) {
            imagepng($im, $img_path . $img_filename);
        } else {
            return false;
        }

        // Create HTML image tag
        $img = sprintf(
            '<img %s src="%s%s" style="width: %dpx; height: %dpx; border: 0;" alt=" " />',
            $img_id ? 'id="' . $img_id . '"' : '',
            $img_url,
            $img_filename,
            $img_width,
            $img_height
        );

        imagedestroy($im);

        return [
            'word' => $word,
            'time' => $now,
            'image' => $img,
            'filename' => $img_filename
        ];
    }
}