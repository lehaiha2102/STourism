import React from 'react';
import QRCode from 'qrcode.react';

function MyQRCodeComponent({link}) {
  return (
    <QRCode className='w-100 h-100 d-flex justifi-contenr-center' value={link} />
  );
}

export default MyQRCodeComponent;
