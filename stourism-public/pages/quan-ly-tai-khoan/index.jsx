import Layout from '../../components/Layout';
import { useEffect, useState } from "react"
import Link from 'next/link';
import { StorageKeys, apiURL } from '../../utils/constant';
import { Tabs, Tab } from 'react-bootstrap';
import { useRouter } from 'next/router';
import { PriceFormatter } from '../../utils/priceFormat';
import { RatingModal } from '../../components/ratingForm';

export default function accountManagerment() {
    const [isLoggedIn, setIsLoggedIn] = useState(false);
    const [user, setUser] = useState(null);
    const [key, setKey] = useState('tab1');
    const router = useRouter();
    const [bookingList, setBooking] = useState();
    const [show, setShow] = useState(false);
    const handleClose = () => setShow(false);
    useEffect(() => {
        const token = localStorage.getItem(StorageKeys.jwt);
        const userString = localStorage.getItem(StorageKeys.USER);
        const user = JSON.parse(userString);
        setUser(user);
        if (token && user) {
            setIsLoggedIn(true);
        } else {
            setIsLoggedIn(false);
        }
    }, []);
    useEffect(() => {
        if (isLoggedIn == true) {
            router.push('/dang-nhap');
        }
    }, []);
    const getBooking = async () => {
        try {
            const token = localStorage.getItem(StorageKeys.jwt);
            const res = await fetch(`http://127.0.0.1:8000/api/v2/booking/get-all-my-booking`, {
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${token}`,
                },
            });

            if (res.ok) {
                const { data } = await res.json();
                setBooking(data);
            } else {
                console.error('Error fetching data:', res.statusText);
            }
        } catch (error) {
            console.error('Error fetching data:', error.message);
        }
    };

    useEffect(() => {
        getBooking();
    }, []);
    return (
        <Layout>
            <div className="container-xxl bg-white p-0">
                <div className="container-fluid page-header mb-5 p-0" style={{
                    backgroundImage: `url(${user && user.banner ? `http://127.0.0.1:8000/image/${user.banner}` : '../../img/Public-Banner.png'})`
                }}>
                    <div className="container-fluid page-header-inner py-5">
                        <div className="container text-center pb-5">
                            <img
                                src={user && user.avatar ? `http://127.0.0.1:8000/image/${user.avatar}` : '../../img/replace-avt.jpg'}
                                alt="User Banner"
                                style={{ borderRadius: '50%' }}
                            />
                        </div>
                    </div>
                </div>
            </div>
            <div className="container-xxl py-5">
                <div className="container">
                    <div className="row g-5 align-items-center">
                        <Tabs
                            id="controlled-tab-example"
                            activeKey={key}
                            onSelect={(k) => setKey(k)}
                            className='d-flex justify-content-center'
                        >
                            <Tab eventKey="tab1" title="Thông tin cá nhân">
                                <div className='row d-flex align-items-center'>
                                    <div className="col-lg-6">
                                        <h1 className="section-title text-start text-primary text-uppercase">{user ? (
                                            user.full_name
                                        ) : (
                                            'Loading...'
                                        )}</h1>
                                        {user && user.address ? (
                                            <p className="mb-4 text-dark"><i className="fa fa-map-marker-alt me-3"></i>{user.address}</p>
                                        ) : (
                                            ''
                                        )}
                                        <form>
                                            <div className="row g-3">
                                                <div className="col-md-12">
                                                    <div className="form-floating">
                                                        <input type="email" className="form-control" disabled value={user && user.email ? (user.email) : ('')} id="email" placeholder="Email của bạn" />
                                                        <label htmlFor="email">
                                                            <i class="fa fa-envelope me-3"></i>
                                                            Email của bạn
                                                        </label>
                                                    </div>
                                                </div>
                                                <div className="col-md-12">
                                                    <div className="form-floating">
                                                        <input
                                                            type="phone"
                                                            className="form-control"
                                                            id="phone"
                                                            disabled={user && user.phone ? true : false}
                                                            value={user?.phone}
                                                            placeholder="Số điện thoại của bạn"
                                                        // onChange={(e) => handlePhoneChange(e)}
                                                        />
                                                        <label htmlFor="phone">
                                                            <i class="fa fa-phone-alt me-3"></i>
                                                            Số điện thoại của bạn
                                                        </label>
                                                    </div>
                                                </div>
                                                <div className="col-md-12">
                                                    <div className="form-floating">
                                                        <input
                                                            type="text"
                                                            className="form-control"
                                                            id="phone"
                                                            disabled={user && user.address ? true : false}
                                                            value={user?.address}
                                                            placeholder="Số điện thoại của bạn"
                                                        // onChange={(e) => handlePhoneChange(e)}
                                                        />
                                                        <label htmlFor="phone">
                                                            <i className="fa fa-map-marker-alt me-3"></i>
                                                            Địa chỉ của bạn
                                                        </label>
                                                    </div>
                                                </div>
                                                <div className="col-md-12">
                                                    <div className="form-floating date" id="date3" data-target-input="nearest">
                                                        <input
                                                            type="date"
                                                            className="form-control"
                                                            id="phone"
                                                            disabled={user && user.dob ? true : false}
                                                            value={user?.dob}
                                                        // onChange={(e) => handlePhoneChange(e)}
                                                        />
                                                        <label htmlFor="checkin">
                                                            <i class="fa fa-calendar me-3" aria-hidden="true"></i>
                                                            Ngày tháng năm sinh</label>
                                                    </div>
                                                </div>
                                                <div className="col-12">
                                                    <button className="btn btn-primary w-100 py-3" type="submit">Cập nhật thông tin</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div className='col-lg-6'>
                                        <img src="../../img/diem-du-lich-01_1632671030 (1)_1661249974.png" className='w-100' />
                                    </div>
                                </div>
                            </Tab>
                            <Tab eventKey="tab2" title="Mật khẩu và bảo mật">
                                <form>
                                    <div className="row g-3 d-flex align-items-center">
                                        <div className='col-lg-6'>
                                            <img src="../../img/diem-du-lich-01_1632671030 (1)_1661249974.png" className='w-100' />
                                        </div>
                                        <div className="col-lg-6">
                                            <h1 className="section-title text-start text-primary text-uppercase"> Mật khẩu và bảo mật</h1>
                                            <div className="form-floating mb-3">
                                                <input type="password" className="form-control" value={user && user.email ? (user.email) : ('')} id="email" placeholder="Email của bạn" />
                                                <label htmlFor="email">
                                                    <i class="fa fa-lock" aria-hidden="true"></i>
                                                    Mật khẩu cũ
                                                </label>
                                            </div>
                                            <div className="form-floating mb-3">
                                                <input type="password" className="form-control" value={user && user.email ? (user.email) : ('')} id="email" placeholder="Email của bạn" />
                                                <label htmlFor="email">
                                                    <i class="fa fa-lock" aria-hidden="true"></i>
                                                    Mật khẩu mới
                                                </label>
                                            </div>
                                            <div className="form-floating mb-3">
                                                <input type="password" className="form-control" value={user && user.email ? (user.email) : ('')} id="email" placeholder="Email của bạn" />
                                                <label htmlFor="email">
                                                    <i class="fa fa-lock" aria-hidden="true"></i>
                                                    Xác nhận mật khẩu
                                                </label>
                                            </div>
                                            <button className="btn btn-primary w-100 py-3" type="submit">Cập nhật thông tin</button>
                                        </div>
                                    </div>
                                </form>
                            </Tab>
                            <Tab eventKey="tab3" title="Thông tin đặt phòng" onClick={getBooking}>
                                <div className="row g-5">
                                    {bookingList && bookingList.length !== 0 ? bookingList.map((bookItem, index) => (
                                        <div className="col-lg-12 col-md-12 wow fadeInUp" data-wow-delay="0.1s" style={{ backgroundColor: '#fff', visibility: 'visible', animationDelay: '0.1s', animationName: 'fadeInUp' }}>
                                            <div className="rounded shadow overflow-hidden row py-5">
                                                <div className="col-lg-3 col-md-3 col-12 d-flex justify-content-center align-items-center">
                                                    {bookItem.room_image && bookItem.room_image.length > 0 && (
                                                        <img
                                                            className="img-fluid rounded w-100 wow zoomIn"
                                                            src={`${apiURL}/images/${JSON.parse(bookItem.room_image)[0]}`}
                                                            data-wow-delay="0.1s"
                                                            style={{ visibility: 'visible', animationDelay: '0.1s', animationName: 'zoomIn' }}
                                                        />
                                                    )}
                                                </div>
                                                <div className="col-lg-9 col-md-9 col-12 flex-column">
                                                    <div className='d-flex flex-column'>
                                                        <h3 className="px-5 text-primary">{bookItem.room_name}</h3>
                                                        <span className="w-100 px-5 font-weight-bold">Giá phòng:<PriceFormatter price={bookItem.room_rental_price} />/1 ngày</span>
                                                        <hr />
                                                    </div>
                                                    <div className="px-5 d-flex justify-content-between px-3 row">
                                                        <span className="col-lg-6 col-md-6 col-12">Thời gian nhận phòng</span>
                                                        <span className="col-lg-6 col-md-6 col-12">{bookItem.checkin_time}</span>
                                                        <span className="col-lg-6 col-md-6 col-12">Thời gian trả phòng</span>
                                                        <span className="col-lg-6 col-md-6 col-12">{bookItem.checkout_time}</span>
                                                        <span className="col-lg-6 col-md-6 col-12">Số tiền đã thanh toán</span>
                                                        <span className="col-lg-6 col-md-6 col-12"><PriceFormatter price={bookItem.advance_payment} /></span>
                                                        <span className="col-lg-6 col-md-6 col-12">Trạng thái</span>
                                                        <span className="col-lg-6 col-md-6 col-12">{bookItem.booking_status === 'success' ? 'Thanh toán thành công' : 'Giao dịch đang được xử lý'}</span>
                                                        <div className='d-flex justify-content-center'>
                                                            {new Date() > new Date(bookItem.checkout_time) && (
                                                                <button
                                                                    className="btn btn-primary mx-3"
                                                                   x
                                                                >Đánh giá
                                                                </button>
                                                            )}
                                                            <Link className="btn btn-primary mx-3" href={`/phong/${bookItem?.room_slug || ''}`}>Xem lại phòng</Link>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    )) :
                                        <div className="col-lg-12 col-md-12 wow fadeInUp" data-wow-delay="0.1s" style={{ backgroundColor: '#fff', visibility: 'visible', animationDelay: '0.1s', animationName: 'fadeInUp' }}>
                                            <div className="rounded shadow overflow-hidden row py-5">
                                                <div className="col-lg-3 col-md-3 col-12 d-flex justify-content-center align-items-center">
                                                    <img
                                                        className="img-fluid rounded w-100 wow zoomIn"
                                                        src="../../img/computer-sleeping.jpg"
                                                        data-wow-delay="0.1s"
                                                        style={{ visibility: 'visible', animationDelay: '0.1s', animationName: 'zoomIn' }}
                                                    />
                                                </div>
                                                <div className="col-lg-9 col-md-9 col-12 flex-column">
                                                    <div className='d-flex flex-column'>
                                                        <h3 className="px-5 text-primary">Không tìm thấy giao dịch<hr /></h3>
                                                    </div>
                                                    <span className="px-5">
                                                        Có vẻ bạn chưa có giao dịch nào. Hãy để chúng tôi giúp bạn có một trải nghiệm du lịch thú vị.
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    }
                                </div>
                            </Tab>
                        </Tabs>
                    </div>
                </div>
            </div>
            {/* <RatingModal
        // show={show}
        // handleClose={handleClose}
        // Truyền các tham số cần thiết vào đây
      /> */}
        </Layout>
    );
}