<?php
namespace BitCode\BITGFZC\Admin\GF;

use BitCode\BITGFZC\Core\Util\Route;

final class Router{
    public function __construct()
    {
        //
    }
    
    
    public static function registerAjax()
    {
        Route::post('gf/get/form', [Handler::class, 'get_a_form']);
    }
} 