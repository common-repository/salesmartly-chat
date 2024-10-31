<?php

class SaleSmartlyAdminRouting
{

    const INTEGRATE_PROJECT_ACTION = 'salesmartly-chat-integrate-project';
    const UNINSTALL_INTEGRATE_PROJECT_ACTION = 'salesmartly-chat-uninstall-integrate-project';
    const CLEAR_ACCOUNT_DATA_ACTION = 'salesmartly-chat-clear-account-data';

    /**
     * @param SaleSmartlyAdminController $adminController
     */
    public function __construct($adminController)
    {
        add_action('admin_post_' . self::INTEGRATE_PROJECT_ACTION, [$adminController, 'handleIntegrateProjectAction']);
        add_action('admin_post_' . self::UNINSTALL_INTEGRATE_PROJECT_ACTION, [$adminController, 'handleUninstallIntegrateProjectAction']);
        add_action('admin_post_' . self::CLEAR_ACCOUNT_DATA_ACTION, [$adminController, 'clearAccountAction']);
    }


    /**
     * @return string
     */
    public static function getEndpointForIntegrateProjectAction()
    {
        return self::getEndpointForAction(self::INTEGRATE_PROJECT_ACTION);
    }

    /**
     * @return string
     */
    public static function getEndpointForUninstallIntegrateProjectAction()
    {
        return self::getEndpointForAction(self::UNINSTALL_INTEGRATE_PROJECT_ACTION);
    }

    /**
     * @return string
     */
    public static function getEndpointForClearAccountDataAction()
    {
        return self::getEndpointForAction(self::CLEAR_ACCOUNT_DATA_ACTION);
    }
    
    /**
     * @param string $action
     * @return string
     */
    private static function getEndpointForAction($action)
    {
        $queryString = http_build_query([
            'action' => $action,
            '_wpnonce' => wp_create_nonce($action),
        ]);
        return admin_url('admin-post.php?' . $queryString);
    }
    
}
