import { useRouter } from 'next/router';
import { useEffect, useState } from 'react';
import Layout from '../../../components/Layout';
import Link from 'next/link';

const Product = () => {
    const router = useRouter();
    const { slug } = router.query;
    const [product, setProduct] = useState();
    const [room, setRoom] = useState([]);
    const [productId, setProductId] = useState('');

    useEffect(() => {
        const fetchData = async () => {
            try {
                const res = await fetch(`http://127.0.0.1:8000/api/v2/${slug}/products`);
                if (res.ok) {
                    const { data } = await res.json();
                    setProduct(data);
                    setProductId(data?.id);
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

    useEffect(() => {
        const fetchData = async () => {
            try {
                const res = await fetch(`http://127.0.0.1:8000/api/v2/product/${productId}/rooms`);
                if (res.ok) {
                    const { data } = await res.json();
                    setRoom(data);
                    console.log(data);

                } else {
                    console.error('Error fetching data:', res.statusText);
                }
            } catch (error) {
                console.error('Error fetching data:', error.message);
            }
        };

        if (productId) {
            fetchData();
        }
    }, [productId]);

    return (
        <Layout>
            <div className="container-xxl bg-white p-0">
                <div className="container-fluid page-header mb-5 p-0" style={{ backgroundImage: `url('http://127.0.0.1:8000/images/${product?.product_main_image}')` }}>
                    <div className="container-fluid page-header-inner py-5">
                        <div className="container text-center pb-5">
                            <h1 className="display-3 text-white mb-3 animated slideInDown text-uppercase">{product ? product?.product_name : <p>Loading...</p>}</h1>
                            <nav aria-label="breadcrumb">
                                <ol className="breadcrumb justify-content-center">
                                    <li className="breadcrumb-item"><Link href={`/`}>Trang chủ</Link></li>
                                    <li className="breadcrumb-item"><Link href={`/danh-muc`}>Sản phẩm</Link></li>
                                    <li className="breadcrumb-item text-white active d-flex" aria-current="page">{product ? product?.product_name : <p>Loading...</p>}</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <div className="container-xxl py-5">
                <div className="container">
                    <div className="row g-5 align-items-center">
                        <div className="col-lg-6">
                            <h1 className="section-title text-start text-primary text-uppercase">{product ? product?.product_name : <p>Loading...</p>}</h1>
                            <p className="mb-4 text-dark"><i className="fa fa-map-marker-alt me-3"></i>{product?.product_address}</p>
                            <p className="mb-4 text-dark"><i className="fa fa-phone-alt me-3"></i>{product?.product_phone}</p>
                            <p className="mb-4 text-dark"><i className="fa fa-envelope me-3"></i>{product?.product_email}</p>
                        </div>
                        <div className="col-lg-6">
                            {product && product.product_image && (
                                <div className="row g-3">
                                    <div id="header-carousel" className="carousel slide" data-bs-ride="carousel">
                                        <div className="carousel-inner">
                                            {JSON.parse(product.product_image).map((image, index) => (
                                                <div className="carousel-item active">
                                                    <img
                                                        src={`http://127.0.0.1:8000/images/${image}`}
                                                        alt={`Image ${index}`}
                                                        className="img-fluid rounded w-100 wow zoomIn mb-5"
                                                    />
                                                </div>
                                            ))}
                                        </div>
                                        <button className="carousel-control-prev" type="button" data-bs-target="#header-carousel"
                                            data-bs-slide="prev">
                                            <span className="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span className="visually-hidden">Previous</span>
                                        </button>
                                        <button className="carousel-control-next" type="button" data-bs-target="#header-carousel"
                                            data-bs-slide="next">
                                            <span className="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span className="visually-hidden">Next</span>
                                        </button>

                                    </div>

                                </div>
                            )}
                        </div>
                    </div>
                </div>
            </div>
            <div className="container-xxl py-5">
                <div className="container">
                <div className="text-center wow fadeInUp" data-wow-delay="0.1s" style={{ visibility: 'visible', animationDelay: '0.1s', animationName: 'fadeInUp' }}>
                        <h6 className="section-title text-center text-primary text-uppercase">Danh sách phòng</h6>
                    </div>
                    <div className="row g-4">
                    {room && room.map ((item, index) => (
                        <div className="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.1s" style={{ visibility: 'visible', animationDelay: '0.1s', animationName: 'fadeInUp' }}>
                            <div className="rounded shadow overflow-hidden">
                                <div className="position-relative">
                                    <img className="img-fluid" src="img/team-1.jpg" alt=""/>
                                        <div className="position-absolute start-50 top-100 translate-middle d-flex align-items-center">
                                            <a className="btn btn-square btn-primary mx-1" href=""><i className="fab fa-facebook-f"></i></a>
                                            <a className="btn btn-square btn-primary mx-1" href=""><i className="fab fa-twitter"></i></a>
                                            <a className="btn btn-square btn-primary mx-1" href=""><i className="fab fa-instagram"></i></a>
                                        </div>
                                </div>
                                <div className="text-center p-4 mt-3">
                                    <h5 className="fw-bold mb-0">{item.room_name}</h5>
                                    <small>Designation</small>
                                </div>
                            </div>
                        </div>
                    ))}
                    </div>
                </div>
            </div>
            <div className="container-xxl mb-5">
                <div className="container">
                    <div className="text-center wow fadeInUp mb-5" data-wow-delay="0.1s" style={{ visibility: 'visible', animationDelay: '0.1s', animationName: 'fadeInUp' }}>
                        <h6 className="section-title text-center text-primary text-uppercase">Đặt phòng ngay</h6>
                    </div>
                    <div className="row g-5">
                        <div className="col-lg-6">
                            <img
                                className="img-fluid rounded w-100 wow zoomIn"
                                data-wow-delay="0.1s"
                                src={`http://127.0.0.1:8000/images/${product?.product_main_image}`}
                                style={{ visibility: 'visible', animationDelay: '0.1s', animationName: 'zoomIn' }}
                            />
                        </div>
                        <div className="col-lg-6 d-flex justify-content-center align-content-center">
                            <div className="wow fadeInUp" data-wow-delay="0.2s" style={{ visibility: 'visible', animationDelay: '0.2s', animationName: 'fadeInUp' }}>
                                <form>
                                    <div className="row g-3">
                                        <div className="col-md-12">
                                            <div className="form-floating">
                                                <input type="text" className="form-control" id="name" placeholder="Tên của bạn" />
                                                <label htmlFor="name">Tên của bạn</label>
                                            </div>
                                        </div>
                                        <div className="col-md-12">
                                            <div className="form-floating">
                                                <input type="email" className="form-control" id="email" placeholder="Email của bạn" />
                                                <label htmlFor="email">Email của bạn</label>
                                            </div>
                                        </div>
                                        <div className="col-md-12">
                                            <div className="form-floating">
                                                <input type="phone" className="form-control" id="phone" placeholder="Số điện thoại của bạn" />
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
                                        <div className="col-md-12">
                                            <div className="form-floating">
                                                <select className="form-select" id="select1">
                                                    <option value="1">1 người</option>
                                                    <option value="2">2 người</option>
                                                    <option value="3">5 người</option>
                                                    <option value="4">Trên 5 người</option>
                                                </select>
                                                <label htmlFor="select1">Số lượng người</label>
                                            </div>
                                        </div>
                                        <div className="col-md-12">
                                            <div className="form-floating">
                                                <select className="form-select" id="select2">
                                                    <option value="1">Child 1</option>
                                                    <option value="2">Child 2</option>
                                                    <option value="3">Child 3</option>
                                                </select>
                                                <label htmlFor="select2">Chọn phòng</label>
                                            </div>
                                        </div>
                                        <div className="col-12">
                                            <button className="btn btn-primary w-100 py-3" type="submit">Book Now</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </Layout>
    );
};

export default Product;