<?php
namespace BitCode\BITGFZC\Admin\Gclid;

use BitCode\BITGFZC\Core\Util\Route;

final class Router{
    public function __construct()
    {
        //
    }
    
    
    public static function registerAjax()
    {
        
        Route::post('gclid/enable', [Handler::class, 'enable']);
        Route::post('gclid/disable', [Handler::class, 'disable']);
    }
} 