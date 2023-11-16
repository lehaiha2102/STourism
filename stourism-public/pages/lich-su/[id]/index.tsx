import { useRouter } from 'next/router';
import { useEffect, useState } from 'react';
import Layout from "../../../components/Layout";
import { PriceFormatter } from '../../../utils/priceFormat';
import { StorageKeys, apiURL } from '../../../utils/constant';
import Link from 'next/link';
import MyQRCodeComponent from '../../../utils/qr';
import Button from 'react-bootstrap/Button';
import Modal from 'react-bootstrap/Modal';
import IRoom from '../../../models/room.model';
import IUser from '../../../models/user.model';
import IBooking from '../../../models/booking.model';
import StarRatings from 'react-star-ratings';
import IRating from '../../../models/rating.model';

const BookingHistory = () => {
    const router = useRouter();
    const { id } = router.query;
    const [history, setHistory] = useState({
        rooms: {} as IRoom,
        users: {} as IUser,
        booking: {} as IBooking,
    });
    const [show, setShow] = useState(false);
    const handleClose = () => setShow(false);
    const handleShow = () => setShow(true);
    const [newRating, setNewRating] = useState(5);
    const [valueRating, setvalueRating] = useState<IRating>();
    const [isRating, setIsRating] = useState(false);

    const handleRatingChange = (updateRating) => {
        setNewRating(updateRating);
    };
    useEffect(() => {
        const fetchData = async () => {
            try {
                const token = localStorage.getItem(StorageKeys.jwt);
                const res = await fetch(`http://127.0.0.1:8000/api/v2/booking/${id}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`,
                    },
                });

                if (res.ok) {
                    const { data } = await res.json();
                    setHistory(data);
                } else {
                    console.error('Error fetching data:', res.statusText);
                }
            } catch (error) {
                console.error('Error fetching data:', error.message);
            }
        };

        if (id) {
            fetchData();
        }
    }, [id]);  // Thêm token vào danh sách dependency để useEffect lắng nghe sự thay đổi của token


    const { rooms, users, booking } = history;
    const link = apiURL + '/api/v2/' + booking.id + '/' + users.id + '/' + rooms.id;

    const handleRatingSubmit = async () => {
        try {
            const token = localStorage.getItem(StorageKeys.jwt);
            const response = await fetch(`${apiURL}/api/v2/rating`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`,
                },
                body: JSON.stringify({
                    booking_id: id,
                    room_id: rooms.id,
                    rating_star: valueRating ? valueRating.rating_star : newRating,
                    comment: "Nội dung bình luận",
                }),
            });

            if (response.ok) {
                console.log('Đánh giá đã được gửi thành công!');
            } else {
                // Xử lý lỗi
                console.error('Đã có lỗi khi gửi đánh giá:', response);
            }
        } catch (error) {
            console.error('Đã có lỗi khi gửi đánh giá:', error);
        }
    };

    useEffect(() => {
        const fetchData = async () => {
            try {
                const token = localStorage.getItem(StorageKeys.jwt);
                const res = await fetch(`http://127.0.0.1:8000/api/v2/rating/${id}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`,
                    },
                });

                if (res.ok) {
                    const { data } = await res.json();
                    setIsRating(true);
                    setvalueRating(data);

                } else {
                    console.error('Error fetching data:', res.statusText);
                }
            } catch (error) {
                console.error('Error fetching data:', error.message);
            }
        };

        if (id) {
            fetchData();
        }
    }, [id]);
    return (
        <Layout>
            <div className="container-xxl bg-white p-0">
                <div className="container-fluid page-header mb-5 p-0" style={{ backgroundImage: `url('../../img/Public-Banner.png')` }}>
                    <div className="container-fluid page-header-inner py-5">
                        <div className="container text-center pb-5">
                            <h1 className="display-3 text-white mb-3 animated slideInDown text-uppercase"></h1>
                            <nav aria-label="breadcrumb">
                                <ol className="breadcrumb justify-content-center">

                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <div className="container-xxl mb-5">
                <div className="container">
                    <div className="text-center wow fadeInUp mb-5" data-wow-delay="0.1s" style={{ visibility: 'visible', animationDelay: '0.1s', animationName: 'fadeInUp' }}>
                        <h6 className="section-title text-center text-primary text-uppercase">Cảm ơn quý khách đã tin tưởng và sử dụng dịch vụ của chúng tôi, chúc quý khách một ngày vui vẻ!</h6>
                    </div>
                    <div className="row g-5">
                        <div className="col-lg-12 col-md-12 wow fadeInUp" data-wow-delay="0.1s" style={{ visibility: 'visible', animationDelay: '0.1s', animationName: 'fadeInUp' }}>
                            <div className="rounded shadow overflow-hidden row py-5">
                                <div className="col-lg-3 col-md-3 col-12 d-flex justify-content-center align-items-center">
                                    {rooms.room_image && rooms.room_image.length > 0 && (
                                        <img
                                            className="img-fluid rounded w-100 wow zoomIn"
                                            src={`${apiURL}/images//${JSON.parse(rooms.room_image)[0]}`}
                                            data-wow-delay="0.1s"
                                            style={{ visibility: 'visible', animationDelay: '0.1s', animationName: 'zoomIn' }}
                                        />
                                    )}
                                </div>
                                <div className="col-lg-6 col-md-6 col-12 flex-column">
                                    <div className='d-flex flex-column'>
                                        <h3 className="px-5 text-primary">{rooms.room_name}</h3>
                                        <span className="w-100 px-5 mb-5 font-weight-bold">Giá phòng:<PriceFormatter price={rooms.room_rental_price} />/1 ngày</span>
                                    </div>
                                    <hr />
                                    <div className="d-flex justify-content-between px-3 row">
                                        <span className="col-lg-6 col-md-6 col-12">Thời gian nhận phòng</span>
                                        <span className="col-lg-6 col-md-6 col-12">{booking.checkin_time}</span>
                                        <span className="col-lg-6 col-md-6 col-12">Thời gian trả phòng</span>
                                        <span className="col-lg-6 col-md-6 col-12">{booking.checkout_time}</span>
                                        <span className="col-lg-6 col-md-6 col-12">Số tiền cần thanh toán trước</span>
                                        <span className="col-lg-6 col-md-6 col-12"><PriceFormatter price={booking.advance_payment} /></span>
                                        <span className="col-lg-6 col-md-6 col-12">Trạng thái</span>
                                        <span className="col-lg-6 col-md-6 col-12">{booking.booking_status === 'success' ? 'Thanh toán thành công' : 'Giao dịch đang được xử lý'}</span>
                                        {new Date() > new Date(booking.checkout_time) && (
                                            <button className="btn btn-primary mt-3" onClick={handleShow}>Đánh giá</button>
                                        )}
                                    </div>
                                </div>
                                <div className="col-md-3 flex-column mt-3 justify-content-center align-items-center">
                                    <div className='w-100 d-flex justifi-content-center mb-3'>
                                        <MyQRCodeComponent link={link} />
                                    </div>
                                    <Link className="btn btn-primary w-100 py-3" href={`/phong/${rooms?.room_slug || ''}`}>Xem lại phòng</Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <Modal show={show} onHide={handleClose}>
                <Modal.Header closeButton>
                    <Modal.Title>Đánh giá phòng {rooms.room_name}</Modal.Title>
                </Modal.Header>
                <Modal.Body>
                    <div className='d-flex flex-column'>
                        <span>Đánh giá phòng</span>
                        {valueRating ?
                            <StarRatings
                                className="d-flex align-content-center justify-center"
                                starRatedColor="orange"
                                starHoverColor="orange"
                                numberOfStars={5}
                                starDimension="65px"
                                starSpacing="15px"
                                name="rate"
                                rating={valueRating?.rating_star}
                            />
                            :
                            <StarRatings
                                className="d-flex align-content-center justify-center"
                                rating={newRating}
                                starRatedColor="orange"
                                starHoverColor="orange"
                                numberOfStars={5}
                                starDimension="65px"
                                starSpacing="15px"
                                changeRating={handleRatingChange}
                                name="rate"
                                value={newRating}
                            />
                        }
                    </div>
                    <div className='d-flex flex-column'>
                        <span>Bình luận về phòng</span>
                        {valueRating ? <strong className='text-justify'>{valueRating.comment}</strong> :
                            <input type='text' name='comment' />}
                    </div>
                    <input type='hidden' name='booking_id' value={id} />
                    <input type='hidden' name='room_id' value={rooms.id} />
                </Modal.Body>
                {isRating ? '' :
                    <Modal.Footer>
                        <Button variant="secondary" onClick={handleClose}>
                            Hủy
                        </Button>
                        <Button variant="primary" onClick={handleRatingSubmit}>
                            Đánh giá
                        </Button>
                    </Modal.Footer>
                }
            </Modal>
        </Layout>
    );
};

export default BookingHistory;