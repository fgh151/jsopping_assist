<?php
defined('_JEXEC') or die('Restricted access');



class pm_assist extends PaymentRoot
{
    
    function showPaymentForm($params, $pmconfigs)
    {
        include(dirname(__FILE__)."/paymentform.php");
    }

    function showAdminFormParams($params)
    {
        $jmlThisDocument = & JFactory::getDocument();

        $array_params = array('testmode', 'merchantid', 'merchanturl', 'transaction_end_status', 'transaction_pending_status', 'transaction_failed_status');
        foreach ($array_params as $key)
            if (!isset($params[$key])) 
                $params[$key] = '';
        $orders = &JModelLegacy ::getInstance('orders', 'JshoppingModel');
        $currency = &JModelLegacy ::getInstance('currencies', 'JshoppingModel'); 
        


        include(dirname(__FILE__)."/adminparamsform.php");	
        
        jimport('joomla.html.pane');
		

	include(dirname(__FILE__)."/admininfo.php");	  
    }

    function checkTransaction($pmconfigs, $order, $act)
    {
	$billnumber = $_GET['billnumber']; //полный_уникальный_номер $_GET
	$checkurl = 'https://test.paysecure.ru/orderstate/orderstate.cfm';

	$postdata = http_build_query(
		array(
		    'Ordernumber' => $_GET['ordernumber'],
		    'Merchant_ID' => $pmconfigs['merchantid'],
		'Login' => $pmconfigs['merchantlogin'],
		'Password' => $pmconfigs['merchantpassword'],
		'Startyear' => date('Y'),
		'Startmonth' => date('m'),
		'Startday'=> date('d'),
		'Endyear'=> date('Y'),
		'Endmonth'=> date('m'),
		'Endday'=> date('d'),
		'Format'=>'3', //XML
		)
	);

	$opts = array('http' =>
		array(
		    'method'  => 'POST',
		    'header'  => 'Content-type: application/x-www-form-urlencoded',
		    'content' => $postdata
		)
	);

	$context  = stream_context_create($opts);
	$result = file_get_contents($checkurl, false, $context);
	$result = simplexml_load_string($result);
	$result = (array) $result;
	$operations = (array) $result['order'];
	$operations = $operations['operation'];
	$good = false;
	foreach($operations as $oper){
		$oper = (array) $oper;
		if ($oper['billnumber'] == '511111100000001.1'){  
			if ($oper['operationstate'] == 'Success'){ return array(1, ''); $good = true;}
		}
	}
	if (!$good){return array(0, $_GET['ordernumber']);}


		}

		function showEndForm($pmconfigs, $order)
		{
		    $jshopConfig = &JSFactory::getConfig();        
		    $item_name = sprintf(_JSHOP_PAYMENT_NUMBER, $order->order_number);
		    

		    if ($pmconfigs['testmode'])
		        $host = "https://test.paysecure.ru/pay/order.cfm";
		    else
		        $host = $pmconfigs['merchanturl'];
		        
		    $email = $pmconfigs['email_received'];
		    //$notify_url = JURI::root() . "index.php?option=com_jshopping&controller=checkout&task=step7&act=notify&js_paymentclass=pm_assist";
		    $return = JURI::root(). "index.php?option=com_jshopping&controller=checkout&task=step7&act=return&js_paymentclass=pm_assist";
		    $cancel_return = JURI::root() . "index.php?option=com_jshopping&controller=checkout&task=step7&act=cancel&js_paymentclass=pm_assist";
		    
		    $_country = &JTable::getInstance('country', 'jshop');
		    $_country->load($order->country);
		    $country = $_country->country_code_2;
		    
		    $inv_id = $order->order_id;


		    $inv_desc = 'good #'.$order->order_id;

		    $out_summ = $order->order_total / $order->currency_exchange;

		    $shp_item = "2";

		    $in_curr = $pmconfigs['currency_'.$order->currency_code_iso];

		    if(empty($_GET['lang']))
		        $culture = 'ru';
		    else
		        $culture = $_GET['lang'];


	?>
		    <html>
		    <head>
		        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />          
		    </head>        
		    <body>

	<FORM id="paymentform" ACTION="<?php print $host?>" METHOD="POST">
	<INPUT TYPE="HIDDEN" NAME="Merchant_ID" VALUE="<?php echo $pmconfigs['merchantid'];?>">
	<INPUT TYPE="HIDDEN" NAME="OrderNumber" VALUE="<?php print $inv_id?>">
	<INPUT TYPE="HIDDEN" NAME="OrderAmount" VALUE="<?php print $out_summ?>">
	<INPUT TYPE="HIDDEN" NAME="OrderCurrency" VALUE="RUB">
	<INPUT TYPE="HIDDEN" NAME="Delay" VALUE="1">
	<INPUT TYPE="HIDDEN" NAME="Language" VALUE="RU">
	<INPUT TYPE="HIDDEN" NAME="Email" VALUE="<?php print $email?>">
	<INPUT TYPE="HIDDEN" NAME="OrderComment" VALUE="Оплата заказа <?php print $inv_id?>">
	<INPUT TYPE="HIDDEN" NAME="URL_RETURN_OK" VALUE="<?php print $return ?>">
	<INPUT TYPE="HIDDEN" NAME="URL_RETURN_NO" VALUE="<?php print $cancel_return ?>">
	<INPUT TYPE="HIDDEN" NAME="CardPayment" VALUE="1">
	<INPUT TYPE="HIDDEN" NAME="WMPayment" VALUE="0">
	<INPUT TYPE="HIDDEN" NAME="YMPayment" VALUE="0">
	<INPUT TYPE="HIDDEN" NAME="AssistIDPayment" VALUE="0">
	<INPUT TYPE="SUBMIT" NAME="Submit" VALUE="Купить">
	</FORM>

    
        <?php print _JSHOP_REDIRECT_TO_PAYMENT_PAGE ?>
        <br>
        <script type="text/javascript">document.getElementById('paymentform').submit();</script>
        </body>
        </html>
        <?php
        die();
      }
	
    function getUrlParams($pmconfigs)
    {                        
        $params = array(); 
        $params['order_id'] = JRequest::getInt("InvId");
        $params['hash'] = "";
        $params['checkHash'] = 0;
        $params['checkReturnParams'] = $pmconfigs['checkdatareturn'];
        return $params;
    }
    
}
?>
