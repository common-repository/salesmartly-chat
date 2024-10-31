<?php

class SaleSmartlyIntegrationState
{

    const SHOP_ID = 'salesmartly-wordpress-shop-id';

    const PLUGIN_ID = 'salesmartly-wordpress-plugin-id';

    const PLUGIN_SCRIPT = 'salesmartly-wordpress-plugin-script';

    /**
     * 设置唯一id
     * @return void
     */
    public function getShopId()
    {
        $shopId = get_option(self::SHOP_ID);
        if (!empty($shopId)) {
            return $shopId;
        }
        $siteUrl = get_option('siteurl');
        $shopId = md5($siteUrl . uniqid() .time());
        add_option(self::SHOP_ID, $shopId);
        return $shopId;
    }

    public function getShopName()
    {
        return get_option('blogname');
    }

    public function getPluginId()
    {
        return get_option(self::PLUGIN_ID);
    }

    public function getPluginScript()
    {
        return get_option(self::PLUGIN_SCRIPT);
    }

    /**
     * @return bool
     */
    public function isPluginIntegrated()
    {
        return !empty(get_option(self::PLUGIN_ID));
    }

    /**
     * 集成插件
     * @param $pluginId
     * @return mixed
     */
    public function integrated($pluginId)
    {
        // 获取script
        $params = [
            'plugin_id' => $pluginId,
        ];
        $pluginSign = $this->getPluginSign($params);
        $params['plugin_sign'] = $pluginSign;

        $apiUrl = SaleSmartlyChat::API . '/sys/company/plugin/get-plugin-script-url?' . http_build_query($params);
        $data = SaleSmartlyApiClient::curl($apiUrl, 'GET');
        if (!empty($data)) {
            if ($data['curl_error']) {
                return $data;
            }
            $script = $data['data']['script_url'];
            // 获取插件code
            if ($this->isPluginIntegrated()) {
                update_option(self::PLUGIN_ID, $pluginId);
                update_option(self::PLUGIN_SCRIPT, $script);
            } else {
                add_option(self::PLUGIN_ID, $pluginId);
                add_option(self::PLUGIN_SCRIPT, $script);
            }
        }
        return [];
    }

    /**
     * 集成插件
     */
    public function uninstallIntegrated()
    {
        $shopId = $this->getShopId();
        $apiUrl = SaleSmartlyChat::API . '/shop/wordpress/emit';
        $params = [
            'topic' => 'app/uninstalled',
            'shop_id' => $shopId,
        ];
        $data = SaleSmartlyApiClient::curl($apiUrl, 'POST', $params);
        if (!empty($data) && $data['code'] == 0) {
            delete_option(self::PLUGIN_ID);
            delete_option(self::PLUGIN_SCRIPT);
        }
        return $data;
    }

    private function getPluginSign($params)
    {
        $mdStr = SaleSmartlyChat::CLIENT_SECRET;
        ksort($params);
        $tmp = '';
        foreach ($params as $paramKey => $paramStr) {
            $tmp .= strlen($tmp) > 0 ? '&' . $paramKey . '=' . $paramStr : $paramKey . '=' . $paramStr;
        }
        $mdStr .= '&' . $tmp;
        return md5($mdStr);
    }

    /**
     * 清理数据
     *
     * @return void
     */
    public function clearAccount()
    {
        delete_option(self::SHOP_ID);
        delete_option(self::PLUGIN_ID);
        delete_option(self::PLUGIN_SCRIPT);
    }

}