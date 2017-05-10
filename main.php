<?php

libxml_use_internal_errors(true);

require_once('vendor/autoload.php');

require_once('settings.php');

function selectProducts($client)
{
    $options = array();
    $response = $client->request('GET', '/admin/products/index.php', $options);
    if ($response->getStatusCode() !== 200) {
        die("selectProducts() - #1");
    }

    $body = (string) $response->getBody();
    $sql_hash = getSQLHash($body);
    if ($sql_hash === '') {
        die("selectProducts() - #2");
    }

    $options = array(
        'query' => array(
            'ACTION' => 'AJAX',
            'ARCHIVE_VIEW' => '0',
            'BUTTON_VALUE' => 'productListJump',
            'LIST_COL_SORT_ORDER' => 'ASC',
            'LIST_COL_SORT' => '',
            'LIST_FILTER_ALL' => '',
            'list_jump' => '1',
            'LIST_NAME' => 'productList',
            'LIST_SEQUENCE' => '1',
            'PAGE_ID' => 'index.php',
            'ROW_LIMIT' => '999999999',
            'SQL_HASH' => $sql_hash,
        ),
    );
    $response = $client->request('GET', '/admin/products/index.php', $options);
    if ($response->getStatusCode() !== 200) {
        die("selectProducts() - #3");
    }

    $body = $response->getBody();
    $body = json_decode($body, true);
    $body = $body['list'];

    $document = new DOMDocument();
    $document->loadHTML($body);
    $xpath = new DomXPath($document);
    $trs = $xpath->query('//div[@class="list-data"]/table[@class="list "]/tr[@class!="list_header"]');
    if (empty($trs)) {
        die("selectProducts() - #4");
    }

    $products = array();
    foreach ($trs as $tr) {
        $sku = (string) $xpath->query('.//td[10]', $tr)->item(0)->textContent;
        $id = (string) $xpath->query('.//td[2]', $tr)->item(0)->textContent;
        $products[$sku] = $id;
    }
    if (empty($products)) {
        die("selectProducts() - #5");
    }

    return $products;
}

function insertProduct($client, $product)
{
    $options = array(
        'form_params' => array(
            'action' => '1',
            'bool_non_var_sub' => '0',
            'bool_originally_shippable' => '0',
            'category_id' => $product['category_id'],
            'ccbill_subscription_id' => '',
            'cost_of_goods_sold' => '0.00',
            'event_type_id' => '',
            'product_alt_provider' => '',
            'product_description_converted' => $product['description'],
            'product_description' => $product['description'],
            'product_id' => $product['id'],
            'product_max_quantity' => '1',
            'product_name_converted' => $product['name'],
            'product_name' => $product['name'],
            'product_price' => $product['price'],
            'product_restocking_fee' => '0.00',
            'product_sku' => $product['sku'],
            'recurring_days' => '0',
            'recurring_discount_max' => '0',
            'recurring_next_product' => '1',
            'shipping_declared_value' => '0.00',
            'shipping_digital_url' => '',
            'shipping_weight' => '0.00',
            'subscription_day' => '7',
            'subscription_type' => '1',
            'subscription_week' => '1',
            'vertical_id' => $product['vertical_id'],
        ),
    );
    $response = $client->request('POST', '/admin/products/product_ajax.php', $options);
    if ($response->getStatusCode() !== 200) {
        die("insertProduct() - #1");
    }

    $body = $response->getBody();
    $body = json_decode($body, true);
    if ($body['message'] !== 'Successfully created product.') {
        die("insertProduct() - #2");
    }

    $products = selectProducts($client);
    if (empty($products)) {
        die("insertProduct() - #3");
    }

    if (!empty($products[$product['sku']])) {
        $product['id'] = $products[$product['sku']];
    }
    if ($product['id'] === 'N/A') {
        die("insertProduct() - #4");
    }

    return $product;
}

function deleteProduct($client, $product)
{
    $options = array(
        'form_params' => array(
            'action' => '4',
            'product_id' => $product['id'],
        ),
    );
    $response = $client->request('POST', '/admin/products/product_ajax.php', $options);
    if ($response->getStatusCode() !== 200) {
        die("deleteProduct() - #1");
    }

    $body = $response->getBody();
    $body = json_decode($body, true);
    if (strpos($body['status_message'], 'Successfully deleted product.') === false) {
        die("deleteProduct() - #2");
    }
}

function selectGateways($client)
{
    $options = array();
    $response = $client->request('GET', '/admin/gateway/index.php', $options);
    if ($response->getStatusCode() !== 200) {
        die("selectGateways() - #1");
    }

    $body = (string) $response->getBody();
    $sql_hash = getSQLHash($body);
    if ($sql_hash === '') {
        die("selectGateways() - #2");
    }

    $options = array(
        'query' => array(
            'ACTION' => 'AJAX',
            'ARCHIVE_VIEW' => '0',
            'BUTTON_VALUE' => 'list_gateway',
            'LIST_COL_SORT_ORDER' => 'ASC',
            'LIST_COL_SORT' => '',
            'LIST_FILTER_ALL' => '',
            'list_jump' => '1',
            'LIST_NAME' => 'list_gateway',
            'LIST_SEQUENCE' => '1',
            'PAGE_ID' => 'index.php',
            'ROW_LIMIT' => '999999999',
            'SQL_HASH' => $sql_hash,
        ),
    );
    $response = $client->request('GET', '/admin/gateway/index.php', $options);
    if ($response->getStatusCode() !== 200) {
        die("selectGateways() - #3");
    }

    $body = $response->getBody();
    $body = json_decode($body, true);
    $body = $body['list'];

    $document = new DOMDocument();
    $document->loadHTML($body);
    $xpath = new DomXPath($document);
    $trs = $xpath->query('//div[@class="list-data"]/table[@class="list "]/tr[@class!="list_header"]');
    if (empty($trs)) {
        die("selectGateways() - #4");
    }

    $gateways = array();
    foreach ($trs as $tr) {
        $alias = (string) $xpath->query('.//td[5]', $tr)->item(0)->textContent;
        $id = (string) $xpath->query('.//td[2]', $tr)->item(0)->textContent;
        $gateways[$alias] = $id;
    }
    if (empty($gateways)) {
        die("selectGateways() - #5");
    }

    return $gateways;
}

function insertGateway($client, $gateway)
{
    $options = array(
        'form_params' => array(
            'action' => 'addNewGateway',
            'API Key' => $gateway['key'],
            'chargeback_fee' => '0.00',
            'Currency' => '1',
            'customer_service_number' => $gateway['phone'],
            'descriptor' => $gateway['description'],
            'Gateway Alias' => $gateway['alias'],
            'gatewayId' => '104',
            'global_monthly_cap' => '0.00',
            'isRunningAjax' => '0',
            'originalCurrencyId' => '',
            'processing_percent' => '0.00',
            'reserve_percent' => '0.00',
            'Test Mode' => 'no',
            'transaction_fee' => '0.00',
        ),
    );
    $response = $client->request('POST', '/admin/edit_gateways.php', $options);
    if ($response->getStatusCode() !== 200) {
        die("insertGateway() - #1");
    }

    $body = (string) $response->getBody();
    if (strpos($body, 'window.opener.RefreshGatewayList') === false) {
        die("insertGateway() - #2");
    }

    $gateways = selectGateways($client);
    if (empty($gateways)) {
        die("insertGateway() - #3");
    }

    if (!empty($gateways[$gateway['alias']])) {
        $gateway['id'] = $gateways[$gateway['alias']];
    }
    if ($gateway['id'] === 'N/A') {
        die("insertGateway() - #4");
    }

    return $gateway;
}

function deleteGateway($client, $gateway)
{
    $options = array(
        'form_params' => array(
            'action' => '1',
            'id' => $gateway['id'],
            'type' => 'Credit Card',
        ),
    );
    $response = $client->request('POST', '/admin/gateway/ajax.php', $options);
    if ($response->getStatusCode() !== 200) {
        die("deleteGateway() - #1");
    }

    $body = $response->getBody();
    $body = json_decode($body, true);
    if (!empty($body['errors']) or $body['errors'] !== '0') {
        die("deleteGateway() - #2");
    }
}

function selectCampaigns($client)
{
    $options = array();
    $response = $client->request('GET', '/admin/campaign/index.php', $options);
    if ($response->getStatusCode() !== 200) {
        die("selectCampaigns() - #1");
    }

    $body = (string) $response->getBody();
    $sql_hash = getSQLHash($body);
    if ($sql_hash === '') {
        die("selectCampaigns() - #2");
    }

    $options = array(
        'query' => array(
            'ACTION' => 'AJAX',
            'ARCHIVE_VIEW' => '0',
            'BUTTON_VALUE' => 'list_campaign',
            'LIST_COL_SORT_ORDER' => 'ASC',
            'LIST_COL_SORT' => '',
            'LIST_FILTER_ALL' => '',
            'list_jump' => '1',
            'LIST_NAME' => 'list_campaign',
            'LIST_SEQUENCE' => '1',
            'PAGE_ID' => 'index.php',
            'ROW_LIMIT' => '999999999',
            'SQL_HASH' => $sql_hash,
        ),
    );
    $response = $client->request('GET', '/admin/campaign/index.php', $options);
    if ($response->getStatusCode() !== 200) {
        die("selectCampaigns() - #3");
    }

    $body = $response->getBody();
    $body = json_decode($body, true);
    $body = $body['list'];

    $document = new DOMDocument();
    $document->loadHTML($body);
    $xpath = new DomXPath($document);
    $trs = $xpath->query('//div[@class="list-data"]/table[@class="list "]/tr[@class!="list_header"]');
    if (empty($trs)) {
        die("selectCampaigns() - #4");
    }

    $campaigns = array();
    foreach ($trs as $tr) {
        $name = (string) $xpath->query('.//td[5]', $tr)->item(0)->textContent;
        $id = (string) $xpath->query('.//td[2]', $tr)->item(0)->textContent;
        $campaigns[$name] = $id;
    }
    if (empty($campaigns)) {
        die("selectCampaigns() - #5");
    }

    return $campaigns;
}

function insertCampaign($client, $campaign, $product, $gateway)
{
    $options = array(
        'form_params' => array(
            'account_updater_id' => '',
            'action' => 'll_ajax_create_campaign',
            'campaign_id' => $campaign['id'],
            'flag_gateway_disabled' => '0',
            'input_auto_responder_customer_name' => '',
            'input_auto_responder_prospect_name' => '',
            'input_campaign_description' => $campaign['description'],
            'input_campaign_name' => $campaign['name'],
            'input_daily_subscription_limit' => '',
            'input_higher_dollar_pre_auth' => '',
            'input_webform_url_page_one_name' => 'https://www.example.com/confirmation.php',
            'input_webform_url_page_two_name' => 'https://www.example.com/confirmation.php',
            'list_products[]' => $product['id'],
            'post_back_url_name[0]' => '',
            'products_main_sequence[362]' => '<!--SEQUENCE-->',
            'provider_anti_fraud_id' => '',
            'provider_auto_responder_name' => '',
            'provider_charge_back_id' => '',
            'provider_collection_id' => '',
            'provider_data_id' => '',
            'provider_fulfillment_id' => '',
            'provider_membership_id' => '',
            'provider_optimize_customer_outcome_id' => '',
            'provider_order_confirm_id' => '',
            'provider_prospect_id' => '',
            'provider_tax_id' => '',
            'radio_campaign_type' => '1',
            'radio_integration' => '2',
            'radio_post_back_order_status[0]' => '1',
            'radio_post_back_order_type[0]' => '1',
            'radio_post_back_payments[0]' => '2',
            'search_product' => 'Add a Product: Search by Id or Name',
            'search_product_upsell' => 'Add a Product: Search by Product Id or Name',
            'select_alt_pay_provider_bitcoin_pg' => '',
            'select_alt_pay_provider_bp_boleto' => '',
            'select_alt_pay_provider_brazilpay' => '',
            'select_alt_pay_provider_eps' => '',
            'select_alt_pay_provider_eurodebit' => '',
            'select_alt_pay_provider_giropay' => '',
            'select_alt_pay_provider_gocoin' => '',
            'select_alt_pay_provider_icepay' => '',
            'select_alt_pay_provider_ideal' => '',
            'select_alt_pay_provider_mistercash' => '',
            'select_alt_pay_provider_paypal' => '',
            'select_alt_pay_provider_poli' => '',
            'select_alt_pay_provider_przelewy24' => '',
            'select_alt_pay_provider_safetypay' => '',
            'select_alt_pay_provider_sepa' => '',
            'select_alt_pay_provider_teleingreso' => '',
            'select_alt_pay_provider_trustpay' => '',
            'select_alt_pay_provider_verkkopankki' => '',
            'select_channel_id' => '1',
            'select_checking_gateway' => '',
            'select_countries[]' => '223',
            'select_expense_id' => '1',
            'select_gateway' => $gateway['id'],
            'select_lbc_gateway' => '',
            'select_payment_types[]' => 'amex',
            'select_post_back_type[0]' => '1',
            'select_salvage_time' => '',
            'select_shipping_method[]' => '1',
        ),
    );
    $response = $client->request('POST', '/admin/ajax_min.php', $options);
    if ($response->getStatusCode() !== 200) {
        die("insertCampaign() - #1");
    }

    $body = $response->getBody();
    $body = json_decode($body, true);
    print_r($body);
    if (strpos($body['message'], 'API Integration successfully saved!') === false) {
        die("insertCampaign() - #2");
    }

    $campaigns = selectCampaigns($client);
    if (empty($campaigns)) {
        die("insertCampaign() - #3");
    }

    if (!empty($campaigns[$campaign['name']])) {
        $campaign['id'] = $campaigns[$campaign['name']];
    }
    if ($campaign['id'] === 'N/A') {
        die("insertCampaign() - #4");
    }

    return $campaign;
}

function deleteCampaign($client, $campaign)
{
    $options = array(
        'form_paramsy' => array(
            'action' => 'll_ajax_delete_campaign',
            'id' => $campaign['id'],
        ),
    );
    $response = $client->request('POST', '/admin/ajax_min.php', $options);
    if ($response->getStatusCode() !== 200) {
        die("deleteCampaign() - #1");
    }

    $body = $response->getBody();
    $body = json_decode($body, true);
    print_r($body);
    if ($body['message'] !== 'Successfully Deleted Campaign!') {
        die("deleteCampaign() - #2");
    }
}

function newOrderCardOnFile($order)
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
    return $order;
}

function newOrderWithProspect($order)
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
    return $order;
}

function getClient()
{
    $options = array(
        'base_uri' => $GLOBALS['settings']['url'],
        'cookies' => true,
    );
    $client = new \GuzzleHttp\Client($options);
    $response = $client->request('GET', '/admin/login.php');
    if ($response->getStatusCode() !== 200) {
        die("getClient() - #1");
    }

    $body = (string) $response->getBody();
    $security_token = getSecurityToken($body);
    if ($security_token === '') {
        die("getClient() - #2");
    }

    $options = array(
        'form_params' => array(
            'admin_name' => $GLOBALS['settings']['username'],
            'admin_pass' => $GLOBALS['settings']['password'],
            'login_url' => '',
            'securityToken' => $security_token,
        ),
    );
    $response = $client->request('POST', '/admin/login.php', $options);
    if ($response->getStatusCode() !== 200) {
        die("getClient() - #3");
    }

    $body = (string) $response->getBody();
    if (strpos($body, '<h1>Dashboard</h1>') === false) {
        die("getClient() - #4");
    }

    return $client;
}

function getSecurityToken($body)
{
    preg_match('#name="securityToken" value="(.*)"#', $body, $match);
    if (!empty($match) and !empty($match[1])) {
        return $match[1];
    }
    return '';
}

function getSQLHash($body)
{
    preg_match("#SearchLimeList\(event, '.*?SQL_HASH=(.*?)'#", $body, $match);
    if (!empty($match) and !empty($match[1])) {
        return $match[1];
    }
    return '';
}

function getURL($path)
{
    $url = sprintf('%s%s', $GLOBALS['settings']['url'], $path);
    return $url;
}

$client = getClient();

// $products = selectProducts($client);
// print_r($products);

// $gateways = selectGateways($client);
// print_r($gateways);

// $campaigns = selectCampaigns($client);
// print_r($campaigns);

// $product = array(
//     'category_id' => '1',
//     'description' => 'MK - limelightcrm.com - Test',
//     'id' => 'N/A',
//     'name' => 'MK - limelightcrm.com - Test',
//     'price' => '100.00',
//     'sku' => 'MK-000000001',
//     'vertical_id' => '1',
// );
// $product = insertProduct($client, $product);
// print_r($product);

// $gateway = array(
//      'alias' => 'MK-000000001',
//      'description' => 'MK-000000001',
//      'key' => 'MK-000000001',
//      'phone' => '1-800-OFFERLAUNCHHERO',
// );
// $gateway = insertGateway($client, $gateway);
// print_r($gateway);

// $product = array(
//     'category_id' => '1',
//     'description' => 'MK - limelightcrm.com - Test',
//     'id' => '362',
//     'name' => 'MK - limelightcrm.com - Test',
//     'price' => '100.00',
//     'sku' => 'MK-000000001',
//     'vertical_id' => '1',
// );
// $gateway = array(
//     'alias' => 'MK-000000001',
//     'description' => 'MK-000000001',
//     'id' => '66',
//     'key' => 'MK-000000001',
//     'phone' => '1-800-OFFERLAUNCHHERO',
// );
// $campaign = array(
//     'description' => 'MK-000000001',
//     'id' => '',
//     'name' => 'MK-000000001',
// );
// $campaign = insertCampaign($client, $campaign, $product, $gateway);
// print_r($campaign);

// $campaign = array(
//     'id' => '203',
// );
// deleteCampaign($client, $campaign);

// deleteGateway($client, $gateway);

// deleteProduct($client, $product);

// $order = array();
// $order = newOrderCardOnFile($order);

// $order = array();
// $order = newOrderWithProspect($order);
