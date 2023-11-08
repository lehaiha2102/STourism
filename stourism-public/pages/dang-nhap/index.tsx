import { useRouter } from 'next/router';
import { useEffect, useRef, useState } from 'react';
import Link from 'next/link';
import SignLayout from '../../components/SignLayout';

const Signin = () => {
    const LoginSubmit = async (event) => {
        event.preventDefault();
        const email = event.target.elements.province.value;
        const password = event.target.elements.password.value;
    
        if (!email || !password) {
          console.error('Please fill in all required fields');
          return;
        }
    
        try {
          const res = await fetch('http://127.0.0.1:8000/api/v2/dang-nhap', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                email: email,
                password: password,
            }),
          });
    
          if (res.ok) {
            const dataSearch = await res.json();
            console.log(dataSearch)
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
                        <h1 className="section-title text-center text-primary text-uppercase">Đăng nhập</h1>
                    </div>
                    <div className="row g-5 d-flex justify-content-center align-content-center">
                        <div className="col-lg-6 d-flex justify-content-center align-content-center">
                            <div className="wow fadeInUp" data-wow-delay="0.2s" style={{ visibility: 'visible', animationDelay: '0.2s', animationName: 'fadeInUp' }}>
                                <form onSubmit={LoginSubmit}>
                                    <div className="row g-3">
                                        <div className="col-md-12">
                                            <div className="form-floating">
                                                <input type="email" name='email' className="form-control" id="email" placeholder="Email của bạn" />
                                                <label htmlFor="email">Email của bạn</label>
                                            </div>
                                        </div>
                                        <div className="col-md-12">
                                            <div className="form-floating">
                                                <input type="password" name='password' className="form-control" id="password" placeholder="Mật khẩu của bạn" />
                                                <label htmlFor="password">Mật khẩu của bạn</label>
                                            </div>
                                        </div>
                                        <Link href={"/quen-mat-khau"}>Quên mật khẩu?</Link>
                                        <div className="col-12">
                                            <button className="btn btn-primary w-100 py-3" type="submit">Đăng nhập</button>
                                        </div>
                                        <div className='d-flex justify-content-between'>
                                            <span>Chưa có tài khoản? </span><Link href="/dang-ky">Đăng ký ngay</Link>
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

export default Signin;