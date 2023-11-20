import Link from 'next/link';
import { useRouter } from 'next/router';
import { useEffect, useState } from 'react';
import ReactHtmlParser from 'react-html-parser';
import Layout from '../../../components/Layout';
import IRoom from "../../../models/room.model";
import { StorageKeys, apiURL } from '../../../utils/constant';
import { PriceFormatter } from '../../../utils/priceFormat';

const limit = (htmlString, maxLength) => {
    if (htmlString?.length > maxLength) {
      const truncatedHtml = htmlString?.substring(0, maxLength) + '...';
      return truncatedHtml;
    }
    return htmlString;
  };

const Booking = () => {
    const router = useRouter();
    const { slug } = router.query;
    const [booking, setBooking] = useState(false);
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
            const token = localStorage.getItem(StorageKeys.jwt);
            const response = await fetch(`${apiURL}/api/v2/booking`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`,
                },
                body: JSON.stringify({
                    booker: booker?.id,
                    email: document.getElementById('email').value,
                    phone: document.getElementById('phone').value,
                    checkin: document.getElementById('checkin').value,
                    checkout: document.getElementById('checkout').value,
                    room_id: room?.id,
                    price: room?.room_rental_price,
                }),
            });

            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }

            const data = await response.json();
            const vnpUrl = data.url;
            router.push(vnpUrl);
        } catch (error) {
            console.error('Error when booking:', error);
        }
    };

    const truncatedDescription = limit(room?.room_description, 150);

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
                        <h2 className="section-title text-center text-primary text-uppercase">Thông tin đặt phòng</h2>
                    </div>
                    <div className="row g-5 d-flex justify-content-center align-content-center">
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
                                                <input type="date" className="form-control datetimepicker-input" id="checkin" placeholder="Thời gian nhận phòng" />
                                                <label htmlFor="checkin">Thời gian nhận phòng</label>
                                            </div>
                                        </div>
                                        <div className="col-md-12">
                                            <div className="form-floating date" id="date4" data-target-input="nearest">
                                                <input type="date" className="form-control datetimepicker-input" id="checkout" placeholder="Thời gian trả phòng" />
                                                <label htmlFor="checkout">Thời gian trả phòng</label>
                                            </div>
                                        </div>
                                        <div className="col-md-12">
                                            {isLoggedIn ? (
                                                <div>
                                                    {booking && booking == true ? '' : <button className="btn btn-primary w-100 py-3 mb-3 rounded" onClick={bookRoom}>Xác nhận</button>}
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
                                </form>
                            </div>
                        </div>
                        <div className="col-lg-4">
                            <div style={{ border: '1px solid #e9ecef', padding: 10, borderRadius: 5}}>
                            <h3 className="section-title text-start text-primary text-uppercase mb-3">{room ? room?.room_name : <p>Loading...</p>}</h3>
                            {room?.room_image && room?.room_image.length > 0 && (
                                <img
                                    className="img-fluid rounded w-100 wow zoomIn"
                                    src={`${apiURL}/images/${JSON.parse(room.room_image)[0]}`}
                                    data-wow-delay="0.1s"
                                    style={{ visibility: 'visible', animationDelay: '0.1s', animationName: 'zoomIn' }}
                                />
                            )}
                            <p className='my-3'>
                            {ReactHtmlParser(truncatedDescription)}
                            </p>
                            <p>Tối đa: {room?.adult_capacity} người lớn {room?.children_capacity ? 'và' + room?.children_capacity + 'trẻ em' : ''}</p>
                            <p>Giá phòng: <PriceFormatter price={room?.room_rental_price}/></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div className="container-xxl py-5 d-flex justify-content-center">
                <div className="container row g-4">
                    <div className="col-lg-12 col-md-12 bg-white wow fadeInUp" data-wow-delay="0.1s" style={{ visibility: 'visible', animationDelay: '0.1s', animationName: 'fadeInUp' }}>
                        <div className="rounded shadow overflow-hidden row py-5">
                            <div className="text-center wow fadeInUp mb-5" data-wow-delay="0.1s" style={{ visibility: 'visible', animationDelay: '0.1s', animationName: 'fadeInUp' }}>
                                <h6 className="section-title text-center text-primary text-uppercase">Quy định về đặt phòng</h6>
                            </div>
                            <ul className='mx-3'>
                                <li>Thời gian đặt phòng phải được thực hiện trước thời gian nhận phòng 12 giờ.</li>
                                <li>Thời gian nhận phòng phải trước 12 giờ trưa.</li>
                                <li>Thời gian trả phòng vào lúc 12 giờ trưa. Lưu ý nếu bạn trả phòng muộn, bạn sẽ phải trả thêm phí dịch vụ là 100.000 đ /1 giờ</li>
                                <li>Trong trường hợp bạn trả phòng sớm hơn dự kiến, chúng tôi không hoàn lại phần tiền thừa.</li>
                                <li>Việc hủy phòng phải được thực hiện sớm nhất có thể (tối đa 8 giờ sau khi đặt phòng thành công để không mất phí, trong trường hợp còn lại, bạn có thể sẽ mất 5% số tiền phòng đã đặt.)</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </Layout>
    );
};

export default Booking;