<?php

class SaleSmartlyChat
{

    const SOURCE = 'wordpress';

    const SALESMARTLY_PLUGIN_NAME = 'salesmartly-chat';

    const DOMAIN = 'https://app.salesmartly.com';

    const API = 'https://api.salesmartly.com';

    const CLIENT_SECRET = '9c2210efee9b603e09f8d742917bb538';

    public static function load()
    {
        $integrationState = new SaleSmartlyIntegrationState();

        if (!is_admin()) {
            new SaleSmartlyWidget($integrationState);
            return;
        }

        if (current_user_can('activate_plugins')) {

            $adminController = new SaleSmartlyAdminController($integrationState);

            new SaleSmartlyAdminRouting($adminController);
            new SaleSmartlyAdminActionLink($integrationState);
        }
    }

}
