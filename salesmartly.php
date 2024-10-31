<?php

/**
 * Plugin Name: SaleSmartly Chat
 * Description: SaleSmartly Chat - Smart bots and automated marketing, integrated with Messenger、WhatsApp、Instagram、Line、Telegram、Email and other multi-channel chat management, quickly increase your sales
 * Version: 1.2.1
 * Author: SaleSmartly
 * Author URI: http://www.salesmartly.com
 * Text Domain: salesmartly-chat
 * Domain Path: /languages/
 * License: GPL2
 */

define('SALESMARTLY_CHAT_VERSION', '1.2.1');

require_once __DIR__ . '/src/Admin/SaleSmartlyAdminActionLink.php';
require_once __DIR__ . '/src/Admin/SaleSmartlyAdminController.php';
require_once __DIR__ . '/src/Admin/SaleSmartlyAdminRouting.php';
require_once __DIR__ . '/src/Api/SaleSmartlyApiClient.php';
require_once __DIR__ . '/src/SaleSmartlyChat.php';
require_once __DIR__ . '/src/SaleSmartlyIntegrationState.php';
require_once __DIR__ . '/src/Utils/SaleSmartlyQueryParameters.php';
require_once __DIR__ . '/src/Widget/SaleSmartlyWidget.php';

function initializeSaleSmartlyChat()
{
    SaleSmartlyChat::load();
}

add_action('init', 'initializeSaleSmartlyChat');

register_activation_hook(__FILE__, '');
