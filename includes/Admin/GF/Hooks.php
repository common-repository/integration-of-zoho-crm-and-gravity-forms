<?php
namespace BitCode\BITGFZC\Admin\GF;

use BitCode\BITGFZC\Core\Util\Request;

final class Hooks{
    public function __construct()
    {
        //
    }
    
    
    public function registerHooks()
    {
        if (Request::Check('frontend')) {
            add_action('gform_after_submission', [Handler::class, 'gform_after_submission'], 9, 2);
        }
    }
} 