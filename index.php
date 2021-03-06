<?php
/**
 * QPay Checkout Page Demo
 * - Terms of use can be found under
 * https://guides.qenta.com/prerequisites
 * - License can be found under:
 * https://github.com/qenta-cee/qcp-example-php/blob/master/LICENSE.
 */
require_once 'includes/function.inc.php';
require_once 'includes/config.inc.php';


// sets request parameters regarding the shop
$requestParameters['customerId'] = $shop['customerId'];
$requestParameters['shopId'] = $shop['shopId'];

// sets request parameters regarding the order
$requestParameters['amount'] = '99.99';
$requestParameters['currency'] = 'EUR';
$requestParameters['orderDescription'] = 'Jane Doe (33562), Order: ' . $shop["orderNumber"];
$requestParameters['customerStatement'] = 'Your Shopname: Order: ' . $shop["orderNumber"];
$requestParameters['orderReference'] = $shop["orderNumber"];

// sets request parameters regarding the handling of the transaction
$requestParameters['duplicateRequestCheck'] = 'no';
// $requestParameters["autoDeposit"] = "yes";
// $requestParameters["maxRetries"] = "3";

// sets request parameters regarding the URLs
$requestParameters['successUrl'] = $common["baseUrl"].'return.php#success';
$requestParameters['cancelUrl'] = $common["baseUrl"].'return.php#cancel';
$requestParameters['failureUrl'] = $common["baseUrl"].'return.php#failure';
$requestParameters['pendingUrl'] = $common["baseUrl"].'return.php#pending';
$requestParameters['serviceUrl'] = $common["baseUrl"].'service.html';

// sets request parameters regarding confirmations of orders
$requestParameters['confirmUrl'] = $common["baseUrl"].'confirm.php';
// $requestParameters["confirmMail"] = "set.your@mail-address.com"; // not used because of using confirmUrl

// sets request parameters regarding the user interface
$requestParameters['language'] = 'en';
$requestParameters['displayText'] = 'Thank you very much for your order.';
$requestParameters['imageUrl'] = $common["baseUrl"].'ui/logo.png';
// $requestParameters["layout"] = "smartphone"; // "desktop", "tablet" or "smartphone" are valid values
// $requestParameters["paymentTypeSortOrder"] = "CCARD,ELV,EPS,SOFORTUEBERWEISUNG,INVOICE,INSTALLMENT,PAYPAL";

// sets your custom request parameters
$requestParameters['shopname_customParameter1'] = 'your first custom parameter';
$requestParameters['shopname_customParameter2'] = 'your second custom parameter';

// Sets always at last the request paramters regarding security,
// because these uses values of the above defined request parameters.
$requestParameters['requestFingerprintOrder'] = getRequestFingerprintOrder($requestParameters);
$requestParameters['requestFingerprint'] = getRequestFingerprint($requestParameters, $shop['secret']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>QPay Checkout Page Demo</title>
    <link rel="stylesheet" type="text/css" href="ui/styles.css">
    <link rel="stylesheet" href="https://use.typekit.net/ucf2gvc.css">
</head>
<body>
<div id="content">
<h1>QPay Checkout Page Demo</h1>

<form action="<?php echo $api["endpoint"] ?>" method="post" name="form" id="checkoutForm">
    <?php
    // adds the request parameters as hidden form fields to this form
    foreach ($requestParameters as $key => $value) {
        echo "\n<input type='hidden' name='{$key}' value='{$value}' />\n";
    }
    ?>
    <input type="hidden" name="windowName" value=""/>
    <script>document.form.windowName.value = window.name;</script>
    <table id="qcpTable" border="1" bordercolor="lightgray" cellpadding="10" cellspacing="0">
        <tr>
            <td align="right"><b>Order description</b></td>
            <td><?php echo $requestParameters['orderDescription']; ?></td>
        </tr>
        <tr>
            <td align="right"><b>Amount</b></td>
            <td><?php echo $requestParameters['amount']; ?>&nbsp;<?php echo $requestParameters['currency']; ?></td>
        </tr>
        <tr>
            <td align="right"><b>Payment type</b></td>
            <td>
                <select name="paymenttype">
                    <?php
                    // adds the list of activated payment types as options to the drop-down field
                    foreach ($paymentTypes as $key => $value) {
                        echo "<option value='{$key}'>{$value}</option>\n";
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="right"><input id="btnCheckout" type="submit" value="Checkout"/></td>
        </tr>
    </table>

    <input type="button" id="btnShowPayloadTable" onclick="togglePayloadTable()" value="Show Form Data (Payload)"/>
    <table border="1" bordercolor="lightgray" cellpadding="10" cellspacing="0" class="payload">

    </table>
</form>
<p>
    This is a simple checkout page for demonstration purposes only.
    It displays the order description, amount, currency, a selection for a payment type
    and offers a "Checkout" button for the consumer to start the checkout process via QENTA.
</p>

<p>
    To integrate the QPay Checkout Page to your web shop, please read the
    <a href="https://guides.qenta.com/" target="_blank">Online Guide</a> very carefully
    and set the parameters according to your requirements and your configuration
    settings obtained from QENTA. Additionally there you can find a list of demo data,
    which can be used for testing this example with demo credit cards and other payment types
    without doing a real purchase.
</p>

<p>
    If you have any questions or troubles regarding the integration please do not
    hesitate to contact our support teams.
</p>

<p>
    Please consider that this checkout page example is configured for demonstration purposes only,
    so there are no real purchases done and also the demo transactions are not displayed
    within the QENTA Payment Center. To change to a production environment you have to
    change the customerId and the secret according to the values obtained from QENTA.
</p>

<p>
    To start a checkout please press the button "Checkout". By default you will see a list of
    all supported payment types by QPay Checkout Page. Otherwise you are also able
    to select one of the payment types in the drop-down list and then press the "Checkout"
    button to go directly to a specific payment type.
</p>
</div>
<footer></footer>
<script>
    const formElement = document.getElementById('checkoutForm');
    const payloadElement = document.querySelector('table.payload');
    var formData;
    function formChanged() {
        const formEntries = new FormData(formElement).entries();
        // formData = Object.assign(...Array.from(formEntries, ([name, value]) => ({[name]: value})));
        // payloadElement.innerHTML = JSON.stringify(formData, null, 2);
        var st = '';
        Array.from(formEntries, ([name, value]) => ([name, value])).forEach((a) => {
            st = st + '<tr><td>' + a[0] + '</td><td><input readonly value="' + a[1] + '" /></td></tr>'
        });
        payloadElement.innerHTML = st;
    }

    function togglePayloadTable() {
        payloadElement.style.display = payloadElement.style.display == "table" ? "none" : "table";
    }

    if (formElement !== undefined) {
        formElement.addEventListener('change', formChanged);
        formChanged();
    }
</script>
</body>
</html>
