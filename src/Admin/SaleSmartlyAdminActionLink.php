<?php

class SaleSmartlyAdminActionLink
{
    /**
     * @var SaleSmartlyIntegrationState
     */
    private $integrationState;

    /**
     * @param SaleSmartlyIntegrationState $integrationsState
     */
    public function __construct($integrationsState)
    {
        $this->integrationState = $integrationsState;
    
        add_filter('plugin_action_links', [$this, 'addPluginActionLinks'], 10, 2);
    }

    /**
     * @param string[] $links
     * @param string $file
     * @return string[]
     */
    public function addPluginActionLinks($links, $file)
    {
        if (!$this->isPluginConfigurationFile($file)) {
            return $links;
        }

        $links[] = $this->prepareBindingAccountActionLink();
        $links[] = $this->clearAccountDataLink();

        return $links;
    }

    /**
     * @param string $file
     * @return bool
     */
    private function isPluginConfigurationFile($file)
    {
        return strpos($file, SaleSmartlyChat::SALESMARTLY_PLUGIN_NAME) !== false;
    }


    /**
     * @return string
     */
    private function prepareBindingAccountActionLink()
    {
        if ($this->integrationState->isPluginIntegrated()) {
            $url = SaleSmartlyAdminRouting::getEndpointForUninstallIntegrateProjectAction();
            return sprintf(
                    '<a href="%s">%s</a>',
                    $url,
                    '卸载绑定'
                );
        }
        
        $shopId = $this->integrationState->getShopId();
        $shopName = $this->integrationState->getShopName();
        $redirectUrl = SaleSmartlyAdminRouting::getEndpointForIntegrateProjectAction();

        $params = [
            'source' => SaleSmartlyChat::SOURCE,
            'shop_id' => $shopId,
            'shop_name' => $shopName,
            'redirect_url' => $redirectUrl
        ];

        $bindingUrl = SaleSmartlyChat::DOMAIN . '/login?' . http_build_query($params);

        return sprintf(
            '<a href="%s">%s</a>',
            $bindingUrl,
            '一键绑定'
        );
    }

    private function clearAccountDataLink()
    {
        if ($this->integrationState->isPluginIntegrated()) {
            return;
        }
        
        return sprintf(
            '<a href="%s">%s</a>',
            SaleSmartlyAdminRouting::getEndpointForClearAccountDataAction(),
            '初始化数据'
        );
    }
}