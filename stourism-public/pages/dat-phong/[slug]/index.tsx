import { PayPalButtons, PayPalScriptProvider } from "@paypal/react-paypal-js";
import Link from 'next/link';
import { useRouter } from 'next/router';
import { useEffect, useRef, useState } from 'react';
import Layout from '../../../components/Layout';
import { StorageKeys, apiURL } from '../../../utils/constant';
import IRoom from "../../../models/room.model";


const Booking = () => {
    const router = useRouter();
    const { slug } = router.query;
    const [product, setProduct] = useState();
    const [booker, setBooker] = useState<any>();
    const [room, setRoom] = useState<IRoom>();

    const [isLoggedIn, setIsLoggedIn] = useState(false);

    useEffect(() => {
        const token = localStorage.getItem(StorageKeys.jwt);
        const userString = localStorage.getItem(StorageKeys.USER);
        const user = JSON.parse(userString);
        
        setBooker(user);
        if (token && user) {
            setIsLoggedIn(true);
        } else {
            setIsLoggedIn(false);
        }
    }, []);

    useEffect(() => {
        const fetchData = async () => {
            try {
                const res = await fetch(`http://127.0.0.1:8000/api/v2/${slug}/rooms`);
                if (res.ok) {
                    const { data } = await res.json();
                    setRoom(data);

                } else {
                    console.error('Error fetching data:', res.statusText);
                }
            } catch (error) {
                console.error('Error fetching data:', error.message);
            }
        };

        if (slug) {
            fetchData();
        }
    }, [slug]);

    const bookRoom = async () => {
        try {
            const response = await fetch(`${apiURL}/api/v2/booking`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    booker: booker?.id,
                    email: document.getElementById('email').value,
                    phone: document.getElementById('phone').value,
                    checkin: document.getElementById('checkin').value,
                    checkout: document.getElementById('checkout').value,
                    room_id: room?.id,
                    booking_type: 'Pay upon check-in',
                }),
            });
    
            if (response.ok) {
                const { data } = await response.json();
                
                router.push(`/lich-su/${data}`);
                
            } else {
                console.log('Error when booking');
            }
        } catch (error) {
            console.error('Error when booking:', error);
        }
    };

    return (
        <Layout>
            <div className="container-xxl bg-white p-0">
                <div className="container-fluid page-header mb-5 p-0" style={{ backgroundImage: `url('../../img/Public-Banner.png')` }}>
                    <div className="container-fluid page-header-inner py-5">
                        <div className="container text-center pb-5">
                            <h1 className="display-3 text-white mb-3 animated slideInDown text-uppercase">{room ? room?.room_name : <p>Loading...</p>}</h1>
                            <nav aria-label="breadcrumb">
                                <ol className="breadcrumb justify-content-center">
                                    <li className="breadcrumb-item"><Link href={`/`}>Trang chủ</Link></li>
                                    <li className="breadcrumb-item"><Link href={`/danh-muc`}>Đặt phòng</Link></li>
                                    <li className="breadcrumb-item text-white active d-flex" aria-current="page">{room ? room?.room_name : <p>Loading...</p>}</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <div className="container-xxl mb-5">
                <div className="container">
                    <div className="text-center wow fadeInUp mb-5" data-wow-delay="0.1s" style={{ visibility: 'visible', animationDelay: '0.1s', animationName: 'fadeInUp' }}>
                        <h6 className="section-title text-center text-primary text-uppercase">Hoàn thiện thông tin trước khi đăt phòng</h6>
                    </div>
                    <div className="row g-5">
                        <div className="col-lg-6 d-flex justify-content-center align-content-center">
                            <div className="wow fadeInUp" data-wow-delay="0.2s" style={{ visibility: 'visible', animationDelay: '0.2s', animationName: 'fadeInUp' }}>
                                <form>
                                    <div className="row g-3">
                                        <div className="col-md-12">
                                            <div className="form-floating">
                                                <input type="text" disabled className="form-control" id="name" value={booker?.full_name} placeholder={booker?.full_name} />
                                                <label htmlFor="name">Tên của bạn</label>
                                            </div>
                                        </div>
                                        <div className="col-md-12">
                                            <div className="form-floating">
                                                <input type="email" className="form-control" id="email" value={booker?.email} placeholder="Email của bạn" />
                                                <label htmlFor="email">Email của bạn</label>
                                            </div>
                                        </div>
                                        <div className="col-md-12">
                                            <div className="form-floating">
                                                <input type="phone" className="form-control" id="phone" value={booker?.phone} placeholder="Số điện thoại của bạn" />
                                                <label htmlFor="phone">Số điện thoại của bạn</label>
                                            </div>
                                        </div>
                                        <div className="col-md-12">
                                            <div className="form-floating date" id="date3" data-target-input="nearest">
                                                <input type="datetime-local" className="form-control datetimepicker-input" id="checkin" placeholder="Thời gian nhận phòng" />
                                                <label htmlFor="checkin">Thời gian nhận phòng</label>
                                            </div>
                                        </div>
                                        <div className="col-md-12">
                                            <div className="form-floating date" id="date4" data-target-input="nearest">
                                                <input type="datetime-local" className="form-control datetimepicker-input" id="checkout" placeholder="Thời gian trả phòng" />
                                                <label htmlFor="checkout">Thời gian trả phòng</label>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div className="col-lg-6">
                            {isLoggedIn ? (
                                <div>
                                    <button className="btn btn-primary w-100 py-3 mb-3 rounded" onClick={bookRoom}>Thanh toán khi nhận phòng</button>
                                    <PayPalScriptProvider
                                        options={{
                                            clientId: "Ac5e4GqEHjI1z8qIbZpv60vDrH2fyG0HvvIxQV5Porhb2SrGr58E49WCwOUb5hvZtDqfD6dF0IfLkRtK",
                                        }}
                                    >
                                        <PayPalButtons />
                                    </PayPalScriptProvider>
                                </div>
                            ) : (
                                <Link
                                    className="btn btn-primary w-100 py-3"
                                    href={`/dang-nhap?dat-phong/${room?.room_name}`}
                                >
                                    Vui lòng đăng nhập để thực hiện đặt phòng
                                </Link>
                            )}
                        </div>

                    </div>
                </div>
            </div>
        </Layout>
    );
};

export default Booking;