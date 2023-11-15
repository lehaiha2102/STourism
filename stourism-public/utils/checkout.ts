import { NextResponse } from "next/server";

const paypal = require('@paypal/checkout-server-sdk');

let clientId = "Ac5e4GqEHjI1z8qIbZpv60vDrH2fyG0HvvIxQV5Porhb2SrGr58E49WCwOUb5hvZtDqfD6dF0IfLkRtK";
let clientSecret = "EBcMf7iX8N6p1cXF0zFre0B80fPUAoJQJR_qpjhCs-X5iqwCi-H1gHO7EHnut9lWfPIReguqukSb0OhQ";

let environment = new paypal.core.SandboxEnvironment(clientId, clientSecret);
let client = new paypal.core.PayPalHttpClient(environment);

export function post(){
    return NextResponse.json({
        messenger: 'Payment prosessing'
    })
}

export function createOrderFunction(){
    
}

export function vnpay(){
    useEffect(() => {
        // Gọi hàm xử lý thanh toán khi component được tạo
        handlePayment();
      }, []);
    
      const handlePayment = async () => {
        const vnp_TxnRef = Math.floor(Math.random() * 10000);
        const vnp_Amount = 100000;
        const vnp_Locale = 'vn';
        const vnp_BankCode = 'NCB';
        const vnp_IpAddr = '192.168.1.1';
    
        const inputParams = {
          vnp_Version: '2.1.0',
          vnp_TmnCode: 'YOUR_TMNCODE',
          vnp_Amount: vnp_Amount * 100,
          vnp_Command: 'pay',
          vnp_CreateDate: new Date().toISOString().replace(/[-:]/g, '').slice(0, -5),
          vnp_CurrCode: 'VND',
          vnp_IpAddr: vnp_IpAddr,
          vnp_Locale: vnp_Locale,
          vnp_OrderInfo: `Thanh toan GD: ${vnp_TxnRef}`,
          vnp_OrderType: 'other',
          vnp_ReturnUrl: 'YOUR_RETURN_URL',
          vnp_TxnRef: vnp_TxnRef,
          vnp_ExpireDate: '20231115000000', 
        };
    
        if (vnp_BankCode) {
          inputParams.vnp_BankCode = vnp_BankCode;
        }
    
        const sortedParams = Object.keys(inputParams).sort();
        const hashData = sortedParams.map(key => `${key}=${encodeURIComponent(inputParams[key])}`).join('&');
        const apiUrl = 'YOUR_API_URL'; // Thay YOUR_API_URL bằng địa chỉ URL của API xử lý thanh toán
    
        try {
          const response = await fetch(apiUrl, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify({
              hashData: hashData,
            }),
          });
    
          if (response.ok) {
            const { vnp_SecureHash, vnp_Url } = await response.json();
            const finalUrl = `${vnp_Url}&vnp_SecureHash=${vnp_SecureHash}`;
    
            // Redirect đến URL thanh toán
            window.location.href = finalUrl;
          } else {
            console.error('Error handling payment');
          }
        } catch (error) {
          console.error('Error handling payment:', error);
        }
      };
}