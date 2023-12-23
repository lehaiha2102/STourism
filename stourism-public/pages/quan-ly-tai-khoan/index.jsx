import Layout from '../../components/Layout';
import React, { useEffect, useState } from "react";
import Link from 'next/link';
import { StorageKeys, apiURL } from '../../utils/constant';
import { Tabs, Tab } from 'react-bootstrap';
import { useRouter } from 'next/router';
import { PriceFormatter } from '../../utils/priceFormat';
import { RatingModal } from '../../components/ratingForm';
import Button from 'react-bootstrap/Button';
import Modal from 'react-bootstrap/Modal';
import {EstimatedReadingTime} from "../../components/EstimatedReadingTime";
import ReactHtmlParser from 'react-html-parser';

export default function accountManagerment() {
    const [isLoggedIn, setIsLoggedIn] = useState(false);
    const [user, setUser] = useState(null);
    const [key, setKey] = useState('tab1');
    const router = useRouter();
    const [bookingList, setBooking] = useState();
    const [post, setPost] = useState([]);
    const [show, setShow] = useState(false);
    const handleClose = () => setShow(false);
    const handleShow = () => setShow(true);
    const [currentPage, setCurrentPage] = useState(1);
    const [lastPage, setLastPage] = useState(1);
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

    const getPost = async () => {
        try {
            const token = localStorage.getItem(StorageKeys.jwt);
            const res = await fetch(`http://127.0.0.1:8000/api/v2/my-post?page=${currentPage}`, {
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${token}`,
                },
            });

            if (res.ok) {
                const { data } = await res.json();
                setPost(data.data);
                setLastPage(data.last_page);
            } else {
                console.error('Error fetching data:', res.statusText);
            }
        } catch (error) {
            console.error('Error fetching data:', error.message);
        }
    };

    useEffect(() => {
        getPost();
    }, [currentPage]);

    const [formData, setFormData] = useState({
        email: '',
        phone: '',
        address: '',
        dob: '',
      });
    
      const userData = { /* your user data */ };
    
      const handleSubmit = async (e) => {
        e.preventDefault();
    
        try {
            const token = localStorage.getItem(StorageKeys.jwt);
            const response = await fetch('http://127.0.0.1:8000/api/v2/update-profile', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'Authorization': `Bearer ${token}`,
            },
            body: JSON.stringify(formData),
          });
    
          if (response.ok) {
            console.log('Form submitted successfully!');
            // Handle success, e.g., show a success message to the user.
          } else {
            console.error('Error submitting form:', response.statusText);
            // Handle error, e.g., show an error message to the user.
          }
        } catch (error) {
          console.error('Error submitting form:', error.message);
        }
      };
    
      useEffect(() => {
        // Set initial form data based on user details
        setFormData({
          email: userData.email || '',
          phone: userData.phone || '',
          address: userData.address || '',
          dob: userData.dob || '',
        });
      }, [userData]);

    const handleCancelSubmit = async () => {
        try {
            const token = localStorage.getItem(StorageKeys.jwt);
            const bookingIdInput = document.getElementById('hiddenInputId');
            const bookingId = bookingIdInput.value;
            const response = await fetch(`http://127.0.0.1:8000/api/v2/booking/cancel/${bookingId}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`,
                },
            });
            if (response.ok) {
                const { data } = await res.json();
                console.log(data);
            } else {
                // Xử lý lỗi
                console.error('Đã có lỗi khi gửi yêu cầu:', response);
            }
        } catch (error) {
            console.error('Đã có lỗi khi gửi yêu cầu:', error);
        }
    };

    return (
        <Layout>
            <div className="container-xxl bg-white p-0">
                <div className="container-fluid page-header mb-5 p-0 w-100" style={{
                    backgroundImage: `url(${user && user.banner ? `http://127.0.0.1:8000/images/${user.banner}` : '../../img/Public-Banner.png'})`
                }}>
                    <div className="container-fluid page-header-inner py-5">
                        <div className="container text-center pb-5 w-100">
                            <img
                                src={user && user.avatar ? `http://127.0.0.1:8000/images/${user.avatar}` : '../../img/replace-avt.jpg'}
                                alt="User Banner"
                                width={200}
                                height={200}
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
                                        <form onSubmit={handleSubmit}>
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
                                <div className="container-xxl py-5">
                                    <div className="container">
                                        <div className="text-center wow fadeInUp" data-wow-delay="0.1s">
                                            <h6 className="section-title text-center text-primary text-uppercase">Bài viết</h6>
                                        </div>
                                        <div className="row g-4">
                                            {post && post.length > 0 ?
                                                post.map((post_item, index) => (
                                                    <div
                                                        className={`col-lg-4 col-md-6 wow fadeInUp`}
                                                        data-wow-delay={`${0.1 * (index + 1)}s`}
                                                        key={index}
                                                    >
                                                        <div className="room-item shadow rounded overflow-hidden">
                                                            <div className="position-relative">
                                                                {post_item.images && post_item.images.length > 0 && (
                                                                    <img
                                                                        className="img-fluid rounded w-100 wow zoomIn"
                                                                        src={`http://127.0.0.1:8000/images/${JSON.parse(post_item.images)[0]}`}
                                                                        alt=''
                                                                        data-wow-delay="0.1s"
                                                                        style={{ visibility: 'visible', animationDelay: '0.1s', animationName: 'zoomIn', maxHeight: 200 }}
                                                                    />
                                                                )}
                                                            </div>
                                                            <div className="p-4 mt-2">
                                                                <div className="d-flex flex-column justify-content-between mb-3">
                                                                    <h5 className="mb-0 w-100 text-center">{post_item.title}</h5>
                                                                </div>
                                                                <p className="text-body mb-3">{ReactHtmlParser(post_item.description)}</p>
                                                                <p className='text-body mb-3'>Ước tính thời gian đọc:{EstimatedReadingTime(post_item.content)} phút</p>
                                                                <p className="text-body mb-3">Cập nhật gần nhất: {post_item.updated_at}</p>
                                                                <div className="d-flex justify-content-center">
                                                                    <Link className="btn btn-sm btn-primary rounded py-2 px-4"
                                                                        href={`/bai-viet/${post_item.id}`}>Xem chi tiết</Link>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                ))
                                                : (
                                                    <p>Loading...</p>
                                                )}
                                        </div>
                                        <div className="text-center wow fadeInUp mt-5" data-wow-delay="0.1s">
                                            <nav aria-label="Page navigation">
                                                <ul className="pagination justify-content-center">
                                                    <li className={`page-item ${currentPage === 1 ? 'disabled' : ''}`}>
                                                        <span className="page-link" onClick={() => setCurrentPage(currentPage - 1)}>
                                                            Trước
                                                        </span>
                                                    </li>

                                                    {Array.from(Array(lastPage).keys()).map((page) => (
                                                        <li key={page + 1} className={`page-item ${currentPage === page + 1 ? 'active' : ''}`}>
                                                            <span className="page-link" onClick={() => setCurrentPage(page + 1)}>
                                                                {page + 1}
                                                            </span>
                                                        </li>
                                                    ))}
                                                    <li className={`page-item ${currentPage === lastPage ? 'disabled' : ''}`}>
                                                        <span className="page-link" onClick={() => setCurrentPage(currentPage + 1)}>
                                                            Tiếp theo
                                                        </span>
                                                    </li>
                                                </ul>
                                            </nav>
                                        </div>
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
                                                        <span className="col-lg-6 col-md-6 col-12"><PriceFormatter price={bookItem.payment} /></span>
                                                        <span className="col-lg-6 col-md-6 col-12">Trạng thái</span>
                                                        <span className="col-lg-6 col-md-6 col-12">
                                                            {bookItem.booking_status === 'success' ? 'Thanh toán thành công' :
                                                                bookItem.booking_status === 'unconfirmed' ? 'Giao dịch đang được xử lý' :
                                                                    bookItem.booking_status === 'cancel' && 'Giao dịch đã bị hủy'}
                                                        </span>

                                                        <div className='d-flex justify-content-center'>
                                                            {bookItem.booking_status == 'success' && (
                                                                <Link className="btn btn-primary mx-3" href={`/lich-su/${bookItem?.booking_id || ''}`}>Đánh giá</Link>
                                                            )}
                                                            {new Date() < new Date(bookItem.checkout_time) && bookItem.booking_status !== 'cancel' && (
                                                                <button className="btn btn-primary mx-3" onClick={handleShow}>Hủy đặt phòng</button>
                                                            )}

                                                            <Link className="btn btn-primary mx-3" href={`/phong/${bookItem?.room_slug || ''}`}>Xem lại phòng</Link>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <Modal show={show} onHide={handleClose}>
                                                <Modal.Header closeButton>
                                                    <Modal.Title>Bạn có chắc chắn muốn hủy đặt phòng này hay không?</Modal.Title>
                                                </Modal.Header>
                                                <Modal.Footer>
                                                    <input type='hidden' value={bookItem.booking_id} id='hiddenInputId' />
                                                    <Button variant="secondary" onClick={handleClose}>
                                                        Hủy
                                                    </Button>
                                                    <Button variant="primary" onClick={handleCancelSubmit}>
                                                        Hủy đặt phòng
                                                    </Button>
                                                </Modal.Footer>
                                            </Modal>
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
        </Layout>
    );
}