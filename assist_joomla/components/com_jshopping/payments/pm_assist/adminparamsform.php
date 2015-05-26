<div class="col100">
<fieldset class="adminform">
<table class="admintable" width = "100%" >
 <tr>
   <td style="width:250px;" class="key">
     Тестовый режим?
   </td>
   <td>
     <?php              
     print JHTML::_('select.booleanlist', 'pm_params[testmode]', 'class = "inputbox" size = "1"', $params['testmode']);
     echo " ".JHTML::tooltip('переключение режимов');
     ?>
   </td>
 </tr>
 <tr>
   <td style="width:250px;" class="key">
     Мерчант айди
   </td>
   <td>
     <input type = "text" class = "inputbox" name = "pm_params[merchantid]" size="45" value = "<?php echo $params['merchantid']?>" /> 
   </td>
 </tr>

 <tr>
   <td style="width:250px;" class="key">
     Мерчант login
   </td>
   <td>
     <input type = "text" class = "inputbox" name = "pm_params[merchantlogin]" size="45" value = "<?php echo $params['merchantlogin']?>" /> 
   </td>
 </tr>

 <tr>
   <td style="width:250px;" class="key">
     Мерчант password
   </td>
   <td>
     <input type = "text" class = "inputbox" name = "pm_params[merchantpassword]" size="45" value = "<?php echo $params['merchantpassword']?>" /> 
   </td>
 </tr>

 <tr>
   <td style="width:250px;" class="key">
     URL обработки
   </td>
   <td>
     <input type = "text" class = "inputbox" name = "pm_params[merchanturl]" size="45" value = "<?php echo $params['merchanturl']?>" />
	<?php  echo " ".JHTML::tooltip('выдается в личном кабинете'); ?> 
   </td>
 </tr>
 <tr>
   <td class="key">
     Статус заказа для успешных транзакций
   </td>
   <td>
     <?php              
         print JHTML::_('select.genericlist', $orders->getAllOrderStatus(), 'pm_params[transaction_end_status]', 'class = "inputbox" size = "1"', 'status_id', 'name', $params['transaction_end_status'] );
     ?>
   </td>
 </tr>
 <tr>
   <td class="key">
     Статус заказа для незавершенных транзакций
   </td>
   <td>
     <?php 
         echo JHTML::_('select.genericlist',$orders->getAllOrderStatus(), 'pm_params[transaction_pending_status]', 'class = "inputbox" size = "1"', 'status_id', 'name', $params['transaction_pending_status']);
     ?>
   </td>
 </tr>
 <tr>
   <td class="key">
     Статус заказа для неуспешных транзакций
   </td>
   <td>
     <?php 
     echo JHTML::_('select.genericlist',$orders->getAllOrderStatus(), 'pm_params[transaction_failed_status]', 'class = "inputbox" size = "1"', 'status_id', 'name', $params['transaction_failed_status']);
     ?>
   </td>
 </tr>
  <tr>
   <td>
   v 0.1a
   </td>
   <td>
<a href="http://openitstudio.ru">Powered by Fedor B Gorsky</a>
   </td>
 </tr>
</table>
</fieldset>
</div>
<div class="clr"></div>
