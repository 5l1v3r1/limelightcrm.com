<?php

/**
 * - create campaign
 *   $name = $client_id . $funnel_name
 *
 * - create products
 *   $name = $client_id . $product_name
 *   $sku = $client_id . $product_sku
 *
 * - add products to campaign
 *
 * - add gateways
 *   stripe.com
 *   $name = $client_id . $domain
 *
 * - add gateways to campaign
 */

require_once('vendor/autoload.php');

require_once('settings.php');

function getSession()
{
    $driver = new \Behat\Mink\Driver\GoutteDriver();
    $session = new \Behat\Mink\Session($driver);
    $session->start();
    $session->visit($GLOBALS['settings']['url']);
    $page = $session->getPage();
    $form = $page->findById('login');
    $input = $form->findField('admin_name');
    $input->setValue($GLOBALS['settings']['username']);
    $input = $form->findField('admin_pass');
    $input->setValue($GLOBALS['settings']['password']);
    $form->submit();
    $page = $session->getPage();
    $content = $page->getContent();
    return $session;
}

function createProduct($parameters)
{
    /**
     * /admin/ajax_min.php?action=ll_ajax_save_inventory_profile'
     * desc = $description
     * is_edit = 0
     * low_quan_limit = 0
     * name = $name
     * profile_id = 0
     * reorder_amount = 0
     * reorder_notification = 1
     * sku = $sku
     * tracking = 1
     */
    $product = array();
    return $product;
}

function createGateway($parameters)
{
    /**
     * /admin/edit_gateways.php
     * action = addNewGateway
     * API Key = $api_key
     * chargeback_fee = 0.00
     * Currency = 1
     * customer_service_number = $customer_service_number
     * descriptor = $descriptor
     * Gateway Alias = $alias
     * gatewayId = 104
     * global_monthly_cap = 0.00
     * isRunningAjax = 0
     * originalCurrencyId =
     * processing_percent = 0.00
     * reserve_percent = 0.00
     * Test Mode = no
     * transaction_fee = 0.00
     */
    $gateway = array();
    return $gateway;
}

function createCampaign($product, $gateway)
{
    $campaign = array();
    return $campaign;
}

function newOrderCardOnFile($parameters)
{
    /**
     * /admin/transact.php
     * AFFID =
     * AFID =
     * AID =
     * alt_pay_payer_id =
     * alt_pay_token =
     * auth_amount =
     * billingAddress1 =
     * billingAddress2 =
     * billingCity =
     * billingCountry =
     * billingFirstName =
     * billingLastName =
     * billingSameAsShipping =
     * billingState =
     * billingZip =
     * C1 =
     * C2 =
     * C3 =
     * campaignId =
     * cascade_enabled =
     * cascade_override =
     * checkAccountNumber =
     * checkRoutingNumber =
     * click_id =
     * createdBy =
     * creditCardNumber =
     * creditCardType =
     * CVV =
     * dynamic_product_price_XX =
     * email =
     * eurodebit_acct_num =
     * eurodebit_route_num =
     * expirationDate =
     * firstName =
     * force_subscription_cycle =
     * forceGatewayId =
     * initializeNewSubscription =
     * ipAddress =
     * lastName =
     * master_order_id =
     * method = NewOrderCardOnFile
     * notes =
     * OPT =
     * password
     * phone =
     * preserve_force_gateway =
     * previousOrderId =
     * product_attribute =
     * product_qty_x =
     * productId =
     * promoCode =
     * prospectId =
     * recurring_days =
     * save_customer =
     * secretSSN =
     * sepa_bic =
     * sepa_iban =
     * sessionId =
     * shippingAddress1 =
     * shippingAddress2 =
     * shippingCity =
     * shippingCountry =
     * shippingId =
     * shippingState =
     * shippingZip =
     * SID =
     * subscription_day =
     * subscription_week =
     * temp_customer_id =
     * thm_session _id =
     * three_d_redirect_url =
     * total_installments =
     * tranType =
     * upsellCount =
     * upsellProductIds =
     * username =
     */
    $order = array();
    return $order;
}

function newOrderWithProspect($parameters)
{
    /**
     * /admin/transact.php
     * AFFID =
     * AFID =
     * AID =
     * alt_pay_payer_id =
     * alt_pay_token =
     * auth_amount =
     * billingAddress1 =
     * billingAddress2 =
     * billingCity =
     * billingCountry =
     * billingFirstName =
     * billingLastName =
     * billingSameAsShipping =
     * billingState =
     * billingZip =
     * C1 =
     * C2 =
     * C3 =
     * campaignId =
     * cascade_enabled =
     * cascade_override =
     * checkAccountNumber =
     * checkRoutingNumber =
     * click_id =
     * createdBy =
     * creditCardNumber =
     * creditCardType =
     * CVV =
     * dynamic_product_price_XX =
     * email =
     * eurodebit_acct_num =
     * eurodebit_route_num =
     * expirationDate =
     * firstName =
     * force_subscription_cycle =
     * forceGatewayId =
     * initializeNewSubscription =
     * ipAddress =
     * lastName =
     * master_order_id =
     * method = NewOrderWithProspect
     * notes =
     * OPT =
     * password
     * phone =
     * preserve_force_gateway =
     * previousOrderId =
     * product_attribute =
     * product_qty_x =
     * productId =
     * promoCode =
     * prospectId =
     * recurring_days =
     * save_customer =
     * secretSSN =
     * sepa_bic =
     * sepa_iban =
     * sessionId =
     * shippingAddress1 =
     * shippingAddress2 =
     * shippingCity =
     * shippingCountry =
     * shippingId =
     * shippingState =
     * shippingZip =
     * SID =
     * subscription_day =
     * subscription_week =
     * temp_customer_id =
     * thm_session _id =
     * three_d_redirect_url =
     * total_installments =
     * tranType =
     * upsellCount =
     * upsellProductIds =
     * username =
     */
    $order = array();
    return $order;
}

$parameters = array();
$product = createProduct($parameters);

$parameters = array();
$gateway = createGateway($parameters);

$campaign = createCampaign($product, $gateway);

$parameters = array();
$order = newOrderCardOnFile($parameters);

$parameters = array();
$order = newOrderWithProspect($parameters);
