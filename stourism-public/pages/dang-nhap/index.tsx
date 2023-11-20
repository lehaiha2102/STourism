import Link from 'next/link';
import { useRouter } from 'next/router';
import { SyntheticEvent, useState, useEffect } from 'react';
import SignLayout from '../../components/SignLayout';
import { StorageKeys } from '../../utils/constant';

const Signin = () => {
    const [email, setEmail] = useState("");
    const [password, setPassword] = useState("");
    const router = useRouter();
    const {param} = router.query;

    useEffect(() => {
        const token = localStorage.getItem(StorageKeys.jwt);
        const userString = localStorage.getItem(StorageKeys.USER);
        const user = JSON.parse(userString);
        if (token && user) {
            router.push(`/`);
        } 
    }, []);
    const LoginSubmit = async (e: SyntheticEvent) => {
        e.preventDefault();
        await fetch("http://localhost:8000/api/v2/login", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                email,
                password,
            }),
        })
            .then((res) => res.json())
            .then(async (data) => {
                console.log(data);
                
                localStorage.setItem(StorageKeys.jwt, data.access_token);
                localStorage.setItem(StorageKeys.USER, JSON.stringify(data.user));
                await router.push(`/`);
            });
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
                                                <input
                                                    type="email"
                                                    name='email'
                                                    className="form-control"
                                                    id="email"
                                                    placeholder="Email của bạn"
                                                    required
                                                    onChange={(e) => setEmail(e.target.value)}
                                                />
                                                <label htmlFor="email">Email của bạn</label>
                                            </div>
                                        </div>
                                        <div className="col-md-12">
                                            <div className="form-floating">
                                                <input
                                                    type="password"
                                                    name='password'
                                                    className="form-control"
                                                    id="password"
                                                    placeholder="Mật khẩu của bạn"
                                                    required
                                                    onChange={(e) => setPassword(e.target.value)}
                                                />
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