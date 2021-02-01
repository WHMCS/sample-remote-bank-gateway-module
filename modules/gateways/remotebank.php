<?php
/**
 * WHMCS Remote Input Bank Gateway Module
 *
 * Within the module itself, all functions must be prefixed with the module
 * filename, followed by an underscore, and then the function name. For this
 * example file, the filename is "remotebank" and therefore all functions
 * begin "remotebank_".
 *
 * For more information, please refer to the online documentation.
 *
 * @see https://developers.whmcs.com/payment-gateways/
 *
 * @copyright Copyright (c) WHMCS Limited 2021
 * @license https://www.whmcs.com/license/ WHMCS Eula
 */

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

/**
 * Define module related meta data.
 *
 * Values returned here are used to determine module related capabilities and
 * settings.
 *
 * @see https://developers.whmcs.com/payment-gateways/meta-data-params/
 *
 * @return array
 */
function remotebank_MetaData()
{
    return [
        'DisplayName' => 'Sample Remote Input Bank Gateway Module',
        'gatewayType' => 'Bank', // Must be set to 'Bank' to associate the type
        'failedEmail' => 'Direct Debit Payment Failed',
        'successEmail' => 'Direct Debit Payment Confirmation',
        'pendingEmail' => 'Direct Debit Payment Pending',
        'APIVersion' => '1.1', // Use API Version 1.1
    ];
}

/**
 * Define gateway configuration options.
 *
 * The fields you define here determine the configuration options that are
 * presented to administrator users when activating and configuring your
 * payment gateway module for use.
 *
 * Supported field types include:
 * * text
 * * password
 * * yesno
 * * dropdown
 * * radio
 * * textarea
 *
 * Examples of each field type and their possible configuration parameters are
 * provided in the sample function below.
 *
 * @see https://developers.whmcs.com/payment-gateways/configuration/
 *
 * @return array
 */

function remotebank_config()
{
    return [
        // the friendly display name for a payment gateway should be
        // defined here for backwards compatibility
        'FriendlyName' => [
            'Type' => 'System',
            'Value' => 'Sample Token Bank Gateway Module',
        ],
        // a text field type allows for single line text input
        'accountID' => [
            'FriendlyName' => 'Account ID',
            'Type' => 'text',
            'Size' => '25',
            'Default' => '',
            'Description' => 'Enter your account ID here',
        ],
        // a password field type allows for masked text input
        'secretKey' => [
            'FriendlyName' => 'Secret Key',
            'Type' => 'password',
            'Size' => '25',
            'Default' => '',
            'Description' => 'Enter secret key here',
        ],
        // the yesno field type displays a single checkbox option
        'testMode' => [
            'FriendlyName' => 'Test Mode',
            'Type' => 'yesno',
            'Description' => 'Tick to enable test mode',
        ],
        // the dropdown field type renders a select menu of options
        'dropdownField' => [
            'FriendlyName' => 'Dropdown Field',
            'Type' => 'dropdown',
            'Options' => [
                'option1' => 'Display Value 1',
                'option2' => 'Second Option',
                'option3' => 'Another Option',
            ],
            'Description' => 'Choose one',
        ],
        // the radio field type displays a series of radio button options
        'radioField' => [
            'FriendlyName' => 'Radio Field',
            'Type' => 'radio',
            'Options' => 'First Option,Second Option,Third Option',
            'Description' => 'Choose your option!',
        ],
        // the textarea field type allows for multi-line text input
        'textareaField' => [
            'FriendlyName' => 'Textarea Field',
            'Type' => 'textarea',
            'Rows' => '3',
            'Cols' => '60',
            'Description' => 'Freeform multi-line text input field',
        ],
    ];
}


/**
 * No local credit card input.
 *
 * This is a required function declaration. Denotes that the module should
 * not allow local card data input.
 */
function remotebank_nolocalcc()
{
}

/**
 * Capture payment.
 *
 * Called when a payment is requested to be processed and captured.
 *
 * This function must have a token stored when a payment is attempted which is
 * created and stored upon the _remoteinput function
 *
 * @param array $params Payment Gateway Module Parameters
 *
 * @return array
 * @see https://developers.whmcs.com/payment-gateways/merchant-gateway/
 *
 */

function remotebank_capture($params)
{
    // Gateway Configuration Parameters
    $accountId = $params['accountID'];
    $secretKey = $params['secretKey'];
    $testMode = $params['testMode'];
    $dropdownField = $params['dropdownField'];
    $radioField = $params['radioField'];
    $textareaField = $params['textareaField'];

    // Capture Parameters
    $remoteGatewayToken = $params['gatewayid'];// Store Remote Parameters
    $bankType = $params['banktype']; // Bank Type, 'checking' or 'savings'
    $bankName = $params['bankname']; // Bank Name
    $bankCode = $params['bankcode']; // Bank Code/Routing Number
    $bankAccount = $params['bankacct']; // Account Number

    // Invoice Parameters
    $invoiceId = $params['invoiceid'];
    $description = $params['description'];
    $amount = $params['amount'];
    $currencyCode = $params['currency'];

    // Client Parameters
    $firstname = $params['clientdetails']['firstname'];
    $lastname = $params['clientdetails']['lastname'];
    $email = $params['clientdetails']['email'];
    $address1 = $params['clientdetails']['address1'];
    $address2 = $params['clientdetails']['address2'];
    $city = $params['clientdetails']['city'];
    $state = $params['clientdetails']['state'];
    $postcode = $params['clientdetails']['postcode'];
    $country = $params['clientdetails']['country'];
    $phone = $params['clientdetails']['phonenumber'];

    // System Parameters
    $companyName = $params['companyname'];
    $systemUrl = $params['systemurl'];
    $returnUrl = $params['returnurl'];
    $langPayNow = $params['langpaynow'];
    $moduleDisplayName = $params['name'];
    $moduleName = $params['paymentmethod'];
    $whmcsVersion = $params['whmcsVersion'];

    // A token is required for a remote input gateway capture attempt
    if (!$remoteGatewayToken) {
        return [
            'status' => 'declined',
            'decline_message' => 'No Remote Token',
        ];
    }

    $postFields = [
        'token' => $remoteGatewayToken,
        'invoice_number' => $invoiceId,
        'amount' => $amount,
        'currency' => $currencyCode,
    ];

    // Utilise your own code to perform API call to initiate capture.

    // Sample response data from API call:
    $response = [
        'success' => true,
        'transaction_id' => 'ABC123',
        'fee' => '1.23',
        'token' => 'abc' . rand(100000, 999999),
    ];

    if ($response['success']) {
        return [
            // 'success' if successful, otherwise 'declined', 'error' for failure
            'status' => 'success',
            // The unique transaction id for the payment
            'transid' => $response['transaction_id'],
            // Optional fee amount for the transaction
            'fee' => $response['fee'],
            // Return only if the token has updated or changed
            'gatewayid' => $response['token'],
            // Data to be recorded in the gateway log - can be a string or array
            'rawdata' => $response,
        ];
    }

    return [
        // 'success' if successful, otherwise 'declined', 'error' for failure
        'status' => 'declined',
        // For declines, a decline reason can optionally be returned
        'declinereason' => $response['decline_reason'],
        // Data to be recorded in the gateway log - can be a string or array
        'rawdata' => $response,
    ];
}

/**
 * Remote input.
 *
 * Called when a pay method is requested to be created or a payment is
 * being attempted.
 *
 * New pay methods can be created or added without a payment being due.
 * In these scenarios, the amount parameter will be empty and the workflow
 * should be to create a token without performing a charge.
 *
 * @param array $params Payment Gateway Module Parameters
 *
 * @return string
 * @see https://developers.whmcs.com/payment-gateways/remote-input-gateway/
 *
 */
function remotebank_remoteinput($params)
{
    // Gateway Configuration Parameters
    $accountId = $params['accountID'];
    $secretKey = $params['secretKey'];
    $testMode = $params['testMode'];
    $dropdownField = $params['dropdownField'];
    $radioField = $params['radioField'];
    $textareaField = $params['textareaField'];

    // Invoice Parameters
    $invoiceId = $params['invoiceid'];
    $description = $params['description'];
    $amount = $params['amount'];
    $currencyCode = $params['currency'];

    // Client Parameters
    $clientId = $params['clientdetails']['id'];
    $firstname = $params['clientdetails']['firstname'];
    $lastname = $params['clientdetails']['lastname'];
    $email = $params['clientdetails']['email'];
    $address1 = $params['clientdetails']['address1'];
    $address2 = $params['clientdetails']['address2'];
    $city = $params['clientdetails']['city'];
    $state = $params['clientdetails']['state'];
    $postcode = $params['clientdetails']['postcode'];
    $country = $params['clientdetails']['country'];
    $phone = $params['clientdetails']['phonenumber'];

    // System Parameters
    $companyName = $params['companyname'];
    $systemUrl = $params['systemurl'];
    $returnUrl = $params['returnurl'];
    $langPayNow = $params['langpaynow'];
    $moduleDisplayName = $params['name'];
    $moduleName = $params['paymentmethod'];
    $whmcsVersion = $params['whmcsVersion'];

    // Build a form which can be submitted to an iframe target to render
    // the payment form.

    $action = '';
    if ($amount > 0) {
        $action = 'payment';
    } else {
        $action = 'create';
    }

    $formAction = $systemUrl . 'demo/remote-iframe-demo.php';
    $formFields = [
        'action' => $action,
        'account_id' => $accountId,
        'invoice_id' => $invoiceId,
        'amount' => $amount,
        'currency' => $currencyCode,
        'customer_id' => $clientId,
        'first_name' => $firstname,
        'last_name' => $lastname,
        'email' => $email,
        'address1' => $address1,
        'address2' => $address2,
        'city' => $city,
        'state' => $state,
        'postcode' => $postcode,
        'country' => $country,
        'phonenumber' => $phone,
        'return_url' => $systemUrl . 'modules/gateways/callback/remotebank.php',
        // Sample verification hash to protect against form tampering
        'verification_hash' => sha1(
            implode('|', [
                $accountId,
                $clientId,
                $invoiceId,
                $amount,
                $currencyCode,
                $secretKey,
                '', // This will be the remoteStorageToken in an update
            ])
        ),
    ];

    $formOutput = '';
    foreach ($formFields as $key => $value) {
        $formOutput .= '<input type="hidden" name="' . $key . '" value="' . $value . '">' . PHP_EOL;
    }

    // This is a working example which posts to the file: demo/remote-iframe-demo.php
    return <<<HTML
<form method="post" action="{$formAction}">
    {$formOutput}
    <noscript>
        <input type="submit" value="Click here to continue &raquo;">
    </noscript>
</form>
HTML;
}

/**
 * Remote update.
 *
 * Called when a pay method is requested to be updated.
 *
 * The expected return of this function is direct HTML output. It provides
 * more flexibility than the remote input function by not restricting the
 * return to a form that is posted into an iframe. We still recommend using
 * an iframe where possible and this sample demonstrates use of an iframe,
 * but the update can sometimes be handled by way of a modal, popup or
 * other such facility.
 *
 * @param array $params Payment Gateway Module Parameters
 *
 * @return string
 * @see https://developers.whmcs.com/payment-gateways/remote-input-gateway/
 *
 */
function remotebank_remoteupdate($params)
{
    // Gateway Configuration Parameters
    $accountId = $params['accountID'];
    $secretKey = $params['secretKey'];
    $remoteStorageToken = $params['gatewayid'];
    $testMode = $params['testMode'];
    $dropdownField = $params['dropdownField'];
    $radioField = $params['radioField'];
    $textareaField = $params['textareaField'];

    // Invoice Parameters
    $invoiceId = $params['invoiceid'];
    $description = $params['description'];
    $amount = $params['amount'];
    $currencyCode = $params['currency'];

    // Client Parameters
    $clientId = $params['clientdetails']['id'];
    $firstname = $params['clientdetails']['firstname'];
    $lastname = $params['clientdetails']['lastname'];
    $email = $params['clientdetails']['email'];
    $address1 = $params['clientdetails']['address1'];
    $address2 = $params['clientdetails']['address2'];
    $city = $params['clientdetails']['city'];
    $state = $params['clientdetails']['state'];
    $postcode = $params['clientdetails']['postcode'];
    $country = $params['clientdetails']['country'];
    $phone = $params['clientdetails']['phonenumber'];

    // System Parameters
    $companyName = $params['companyname'];
    $systemUrl = $params['systemurl'];
    $returnUrl = $params['returnurl'];
    $langPayNow = $params['langpaynow'];
    $moduleDisplayName = $params['name'];
    $moduleName = $params['paymentmethod'];
    $whmcsVersion = $params['whmcsVersion'];

    // Build a form which can be submitted to an iframe target to render
    // the payment form.

    $action = '';
    if ($amount > 0) {
        $action = 'payment';
    } else {
        $action = 'create';
    }

    $formAction = $systemUrl . 'demo/remote-iframe-demo.php';
    $formFields = [
        'action' => $action,
        'account_id' => $accountId,
        'invoice_id' => $invoiceId,
        'amount' => $amount,
        'currency' => $currencyCode,
        'customer_id' => $clientId,
        'first_name' => $firstname,
        'last_name' => $lastname,
        'email' => $email,
        'address1' => $address1,
        'address2' => $address2,
        'city' => $city,
        'state' => $state,
        'postcode' => $postcode,
        'country' => $country,
        'phonenumber' => $phone,
        'return_url' => $systemUrl . 'modules/gateways/callback/remotebank.php',
        // Sample verification hash to protect against form tampering
        'verification_hash' => sha1(
            implode('|', [
                $accountId,
                $clientId,
                0, // Invoice ID - there is no invoice for an update
                0, // Amount - there is no amount when updating
                '', // Currency Code - there is no currency when updating
                $secretKey,
                $remoteStorageToken,
            ])
        ),
    ];

    $formOutput = '';
    foreach ($formFields as $key => $value) {
        $formOutput .= '<input type="hidden" name="' . $key . '" value="' . $value . '">' . PHP_EOL;
    }

    // This is a working example which posts to the file: demo/remote-iframe-demo.php
    return <<<HTML
<div id="frmRemoteBankProcess" class="text-center">
    <form method="post" action="{$formAction}">
        {$formOutput}
        <noscript>
            <input type="submit" value="Click here to continue &raquo;">
        </noscript>
    </form>
    <iframe name="remoteUpdateIFrame" class="auth3d-area" width="90%" height="600" scrolling="auto" src="about:blank"></iframe>
</div>
<script>
    setTimeout("autoSubmitFormByContainer(\'frmRemoteBankProcess\')", 1000);
</script>
HTML;
}

/**
 * Admin Status Message.
 *
 * Called when an invoice is viewed in the admin area.
 *
 * @param array $params Payment Gateway Module Parameters.
 *
 * @return array
 */
function remotebank_adminstatusmsg($params)
{
    // Gateway Configuration Parameters
    $accountId = $params['accountID'];
    $secretKey = $params['secretKey'];
    $remoteStorageToken = $params['gatewayid'];
    $testMode = $params['testMode'];
    $dropdownField = $params['dropdownField'];
    $radioField = $params['radioField'];
    $textareaField = $params['textareaField'];

    // Invoice Parameters
    $remoteGatewayToken = $params['gatewayid'];
    // The id of the invoice being viewed
    $invoiceId = $params['id'];
    // The id of the user the invoice belongs to
    $userId = $params['userid'];
    // The creation date of the invoice
    $date = $params['date'];
    // The due date of the invoice
    $dueDate = $params['duedate'];
    // The status of the invoice
    $status = $params['status'];

    if ($remoteGatewayToken) {
        return [
            'type' => 'info',
            'title' => 'Token Gateway Profile',
            'msg' => 'This customer has a Remote Token storing their bank'
                . ' details for automated recurring billing with ID ' . $remoteGatewayToken,
        ];
    }
}