<?php

namespace BitCode\BITGFZC\Core\Ajax;

use BitCode\BITGFZC\Core\Util\Request;
use BitCode\BITGFZC\Admin\AdminAjax;
use BitCode\BITGFZC\Integration\Integrations;

class AjaxService
{
    public function __construct()
    {
        if (Request::Check('ajax')) {
            $this->loadPublicAjax();
        }
        if (Request::Check('admin')) {
            $this->loadAdminAjax();
            $this->loadIntegrationsAjax();
        }
    }

    /**
     * Helps to register admin side ajax
     * 
     * @return null
     */
    public function loadAdminAjax()
    {
        (new AdminAjax())->register();
    }

    /**
     * Helps to register frontend ajax
     * 
     * @return null
     */
    protected function loadPublicAjax()
    {
        // (new FrontendAjax())->register();
    }

    /**
     * Helps to register integration ajax
     * 
     * @return null
     */
    public function loadIntegrationsAjax()
    {
        (new Integrations())->registerAjax();
    }
}
