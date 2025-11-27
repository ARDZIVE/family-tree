<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = ['html','date','form','database','array','session','url','string','smtp_mail','captcha'];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
     protected $session;
     protected $captcha_config;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        $this->session = \Config\Services::session();

        $this->captcha_config = [
            'word' => random_int(100, 999),  // Using random_int for better randomization and ensuring 3 digits
            'img_path' => FCPATH . 'captchas/images/',  // Removed redundant directory separator
            'img_url' => base_url('captchas/images/'),  // Using base_url() more efficiently
            'font_path' => FCPATH . 'captchas/fonts/captcha5.ttf',
            'img_width' => 180,
            'img_height' => 33,
            'word_length' => 3,
            'font_size' => 20,
            'expiration' => 200,
            'colors' => [
                'background' => [255, 255, 255],
                'border' => [234, 236, 244],
                'text' => [252, 21, 0],
                'grid' => [211, 211, 211]
            ]
        ];
    }
}
