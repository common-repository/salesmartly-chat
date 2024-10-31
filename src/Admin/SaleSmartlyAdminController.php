<?php

class SaleSmartlyAdminController
{

    /**
     * @var SaleSmartlyIntegrationState
     */
    private $integrationState;

    /**
     * @param SaleSmartlyIntegrationState $integrationState
     */
    public function __construct($integrationState)
    {
        $this->integrationState = $integrationState;
    }

    /**
     * 处理集成
     * @return mixed
     */
    public function handleIntegrateProjectAction()
    {
        if (!$this->isRequestNonceValid(SaleSmartlyAdminRouting::INTEGRATE_PROJECT_ACTION)) {
            wp_die('', 403);
        }

        // $cpl = SaleSmartlyQueryParameters::get('cpl');
        $pluginId = SaleSmartlyQueryParameters::get('plugin_id');
        
        // 请求
        $res = [];
        try {
            $res = $this->integrationState->integrated($pluginId);
        } catch(\Exception $ex) {
            $this->redirectToPluginsListDashboardWithError('绑定失败');
        }

        if ($res['curl_error']) {
            $this->redirectToPluginsListDashboardWithError('绑定失败' . $res['curl_error']);
        }

        $this->redirectToPluginsListDashboard();
    }

    /**
     * 处理卸载
     *
     * @return void
     */
    public function handleUninstallIntegrateProjectAction()
    {
        if (!$this->isRequestNonceValid(SaleSmartlyAdminRouting::UNINSTALL_INTEGRATE_PROJECT_ACTION)) {
            wp_die('', 403);
        }
        
        $res = [];
        try {
            $res = $this->integrationState->uninstallIntegrated();
        } catch(\Exception $ex) {
            $this->redirectToPluginsListDashboardWithError('解绑失败');
        }

        if ($res['curl_error']) {
            $this->redirectToPluginsListDashboardWithError('绑定失败' . $res['curl_error']);
        }

        if ($res['code']) {
            $this->redirectToPluginsListDashboardWithError('绑定失败' . $res['code']);
        }

        $this->redirectToPluginsListDashboard();
        
    }

    /**
     * 清理数据
     */
    public function clearAccountAction()
    {
        $this->integrationState->clearAccount();
        $this->redirectToPluginsListDashboard();
    }


    /**
     * @param string $nonce
     * @return bool
     */
    private function isRequestNonceValid($nonce)
    {
        if (!SaleSmartlyQueryParameters::has('_wpnonce')) {
            return false;
        }

        return (bool) wp_verify_nonce(SaleSmartlyQueryParameters::get('_wpnonce'), $nonce);
    }

    private function redirectToPluginsListDashboard()
    {
        wp_redirect(admin_url('plugins.php'));
        die();
    }

    /**
     * @param string $errorCode
     */
    private function redirectToPluginsListDashboardWithError($errorCode)
    {
        $url = sprintf('plugins.php?error=%s', $errorCode);
        wp_redirect(admin_url($url));
        die();
    }


}
