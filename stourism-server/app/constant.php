<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');
$vnp_TmnCode = "BFE3ZL6D";
$vnp_HashSecret = "XIQPVJOWWPFNLQCFYEDWVOMFIGDNSBBW";
$vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
$vnp_Returnurl = "http://127.0.0.1:8080/php_ecommerce/vnpay_php/vnpay_return.php";
$vnp_apiUrl = "http://sandbox.vnpayment.vn/merchant_webapi/merchant.html";
$apiUrl = "https://sandbox.vnpayment.vn/merchant_webapi/api/transaction";
$startTime = date("YmdHis");
$expire = date('YmdHis',strtotime('+15 minutes',strtotime($startTime)));