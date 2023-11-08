import { useRouter } from 'next/router';
import { useEffect, useRef, useState } from 'react';
import Link from 'next/link';
import SignLayout from '../../components/SignLayout';

const ForgotPassword = () => {
    return (
        <SignLayout>
            <div className="container-xxl mb-5 mt-5">
                <div className="container">
                    <div className="text-center wow fadeInUp mb-5" data-wow-delay="0.1s" style={{ visibility: 'visible', animationDelay: '0.1s', animationName: 'fadeInUp' }}>
                        <h1 className="section-title text-center text-primary text-uppercase">Đăng ký</h1>
                    </div>
                    <div className="row g-5 d-flex justify-content-center align-content-center">
                        <div className="col-lg-6 d-flex justify-content-center align-content-center">
                            <div className="wow fadeInUp" data-wow-delay="0.2s" style={{ visibility: 'visible', animationDelay: '0.2s', animationName: 'fadeInUp' }}>
                                <form>
                                    <div className="row g-3">
                                        <div className="col-md-12">
                                            <div className="form-floating">
                                                <input type="email" className="form-control" id="email" placeholder="Email của bạn" />
                                                <label htmlFor="email">Email của bạn</label>
                                            </div>
                                        </div>
                                        <div className="col-12 d-flex w-100">
                                            <button className="btn btn-primary w-100 mx-2 py-3" type="submit">Quên mật khẩu</button>
                                            <Link className='btn btn-secondary w-100 mx-2 py-3' href='/dang-nhap'>Hủy</Link>
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

export default ForgotPassword;