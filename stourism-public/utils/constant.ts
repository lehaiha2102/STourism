import { User } from '../interfaces'

/** Dummy user data. */
export const sampleUserData: User[] = [
  { id: 101, name: 'Alice' },
  { id: 102, name: 'Bob' },
  { id: 103, name: 'Caroline' },
  { id: 104, name: 'Dave' },
]

export const apiURL = 'http://127.0.0.1:8000';

export const StorageKeys = {
  USER: 'user',
  jwt: 'access_token',
};

  export const vnpTmnCode = "BFE3ZL6D";
  export const vnpHashSecret = "XIQPVJOWWPFNLQCFYEDWVOMFIGDNSBBW";
  export const vnpUrl = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
  export const vnpReturnUrl = "http://127.0.0.1:8080/php_ecommerce/vnpay_php/vnpay_return.php";
  export const vnpApiUrl = "http://sandbox.vnpayment.vn/merchant_webapi/merchant.html";
  export const apiUrl = "https://sandbox.vnpayment.vn/merchant_webapi/api/transaction";

  // Config input format
  // Expire
  export const startTime = new Date().toISOString();
  export const expireTime = new Date(Date.now() + 15 * 60 * 1000).toISOString();
