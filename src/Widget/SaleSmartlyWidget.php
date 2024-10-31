<?php

class SaleSmartlyWidget
{

    /**
     *
     * @var SaleSmartlyIntegrationState
     */
    private $integrationState;
    
    /**
     *
     * @param SaleSmartlyIntegrationState $integrationState
     */
    public function __construct($integrationState)
    {
        $this->integrationState = $integrationState;

        // add_action('wp_footer', [$this, 'enqueueScriptsAsync'], 100);

        add_action('wp_enqueue_scripts', [$this, 'enqueueScriptsAsync'], 1000);
    }

    public function enqueueScriptsAsync()
    {
        if (!$this->integrationState->isPluginIntegrated()) {
            return;
        }

        $scriptUrl =  esc_js($this->integrationState->getPluginScript());

        wp_enqueue_script(SaleSmartlyChat::SALESMARTLY_PLUGIN_NAME, $scriptUrl, [], SALESMARTLY_CHAT_VERSION, true);

    }


}