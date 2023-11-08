import { useRouter } from 'next/router';
import { useEffect, useRef, useState } from 'react';
import Link from 'next/link';
import SignLayout from '../../components/SignLayout';

const ConfirmEmail = () => {
    const router = useRouter();
    const { email } = router.query;
    
    const handleSubmit = async (event) => {
        event.preventDefault();
        const active_key = event.target.elements.active_key.value;


        if ( !active_key || !email ) {
            console.error('Please fill in all required fields');
            return;
        }

        try {
            const res = await fetch('http://127.0.0.1:8000/api/v2/confirm-email', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    active_key: active_key,
                    email: email,
                }),
            });

            if (res.ok) {
                router.push('/dang-nhap');

            } else {
                console.error('Error fetching data:', res.statusText);
            }
        } catch (error) {
            console.error('Error fetching data:', error.message);
        }
    };

    return (
        <SignLayout>
            <div className="container-xxl mb-5 mt-5">
                <div className="container">
                    <div className="text-center wow fadeInUp mb-5" data-wow-delay="0.1s" style={{ visibility: 'visible', animationDelay: '0.1s', animationName: 'fadeInUp' }}>
                        <h1 className="section-title text-center text-primary text-uppercase">Xác nhận tài khoản</h1>
                    </div>
                    <div className="row g-5 d-flex justify-content-center align-content-center">
                        <div className="col-lg-6 d-flex justify-content-center align-content-center">
                            <div className="wow fadeInUp" data-wow-delay="0.2s" style={{ visibility: 'visible', animationDelay: '0.2s', animationName: 'fadeInUp' }}>
                                <form onSubmit={handleSubmit}>
                                    <div className="row g-3">
                                        <div className="col-md-12">
                                            <div className="form-floating">
                                                <input type="password" name='active_key' className="form-control" id="active_key" placeholder="Mã xác nhận của bạn" />
                                                <label htmlFor="active_key">Mã xác nhận được gửi tới email của bạn</label>
                                            </div>
                                        </div>
                                        <div className="col-12">
                                            <button className="btn btn-primary w-100 py-3" type="submit">Xác nhận</button>
                                        </div>
                                        <div className='d-flex justify-content-between'>
                                            <span>Đã có tài khoản? </span><Link href="/dang-nhap">Đăng nhập ngay</Link>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </SignLayout>
    );
};

export default ConfirmEmail;