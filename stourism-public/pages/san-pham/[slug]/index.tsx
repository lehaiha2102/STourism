import { useRouter } from 'next/router';
import { useEffect, useState } from 'react';
import Layout from '../../../components/Layout';
import Link from 'next/link';
import { apiURL } from '../../../utils/constant';
import IProduct from '../../../models/product.model';
import IRoom from '../../../models/room.model';
import ReactHtmlParser from 'react-html-parser';

function PriceFormatter({ price }) {
    const formattedPrice = new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(price);

    return <span>{formattedPrice}</span>;
}

const Product = () => {
    const router = useRouter();
    const { slug } = router.query;
    const [product, setProduct] = useState<IProduct>();
    const [room, setRoom] = useState<IRoom>();
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
                    console.log('data', data);

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
            <div className="container-xxl bg-white p-3 d-flex justify-content-center">
                <div className="container row">
                    <div className="col-lg-6">
                        {product && product.product_main_image && (
                            <div className="g-3">
                                <img
                                    src={`http://127.0.0.1:8000/images/${product.product_main_image}`}
                                    alt={`Image ${product.product_main_image}`}
                                    className="img-fluid rounded w-100 wow zoomIn mb-5"
                                />
                            </div>
                        )}

                    </div>
                    <div className='col-lg-6 row'>
                        {product && product.product_image &&
                            JSON.parse(product.product_image).slice(0, 5).map((image, index) => (
                                <div className="col-lg-6">
                                    <img
                                        src={`http://127.0.0.1:8000/images/${image}`}
                                        alt={`Image ${index}`}
                                        className="img-fluid rounded w-100 wow zoomIn mb-5"
                                    />
                                </div>
                            )
                            )}
                    </div>
                </div>
            </div>
            <div className="container-xxl py-5 bg-white">
                <div className="container">
                    <div className="row g-5 align-items-center">
                        <div className="col-lg-6">
                            <h1 className="section-title text-start text-primary text-uppercase">{product ? product?.product_name : <p>Loading...</p>}</h1>
                            <p className="mb-4 text-dark"><i className="fa fa-map-marker-alt me-3"></i>{product?.product_address}</p>
                            <p className="mb-4 text-dark"><i className="fa fa-phone-alt me-3"></i>{product?.product_phone}</p>
                            <p className="mb-4 text-dark"><i className="fa fa-envelope me-3"></i>{product?.product_email}</p>
                            <p className='mb-4 text-dark'>
                                {ReactHtmlParser(product?.product_description)}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div className="container-xxl py-5 d-flex justify-content-center">
                <div className="container row g-4">
                    <div className="col-lg-12 col-md-12 bg-white wow fadeInUp" data-wow-delay="0.1s" style={{ visibility: 'visible', animationDelay: '0.1s', animationName: 'fadeInUp' }}>
                        <div className="rounded shadow overflow-hidden row py-5">
                            <div className="text-center wow fadeInUp mb-5" data-wow-delay="0.1s" style={{ visibility: 'visible', animationDelay: '0.1s', animationName: 'fadeInUp' }}>
                                <h6 className="section-title text-center text-primary text-uppercase">Dịch vụ của {product ? product?.product_name : <p>Loading...</p>}</h6>
                            </div>
                            {product && product.product_service &&
                                JSON.parse(product.product_service).map((service, index) => (
                                    <div key={index} className="col-lg-3 d-flex justify-content-center mb-3">
                                        <span className='text-center w-100'><i className="fa fa-check me-3"></i>{service}</span>
                                    </div>
                                )
                                )}
                        </div>
                    </div>
                </div>
            </div>
            <div className="container-xxl">
                <div className="container">
                    <div className="row g-4 rounded shadow overflow-hidden">
                        <div className="text-center wow fadeInUp" data-wow-delay="0.1s" style={{ visibility: 'visible', animationDelay: '0.1s', animationName: 'fadeInUp' }}>
                            <h6 className="section-title text-center text-primary text-uppercase">Danh sách phòng của {product ? product?.product_name : <p>Loading...</p>}</h6>
                        </div>
                        {room ? room.map((item, index) => (
                            <div className="col-lg-12 col-md-12 wow fadeInUp" data-wow-delay="0.1s" style={{ visibility: 'visible', animationDelay: '0.1s', animationName: 'fadeInUp' }}>
                                <div className="row py-5">
                                    <div className="col-lg-3">
                                        {item.room_image && item.room_image.length > 0 && (
                                            <img
                                                className="img-fluid rounded w-100 wow zoomIn"
                                                src={`${apiURL}/images/${JSON.parse(item.room_image)[0]}`}
                                                data-wow-delay="0.1s"
                                                style={{ visibility: 'visible', animationDelay: '0.1s', animationName: 'zoomIn' }}
                                            />
                                        )}
                                    </div>
                                    <div className="col-lg-6 flex-column">
                                        <h3 className="px-5 w-100 text-center">{item.room_name}</h3>
                                        <hr />
                                        <div className="d-flex flex-column px-3">
                                            <span>{ReactHtmlParser(item?.room_description)}</span>
                                        </div>
                                        <div className="d-flex justify-content-between px-3 row">
                                            <span className="col-lg-6">1 giường đơn</span>
                                            <span className="col-lg-6">1 giường đơn</span>
                                            <span className="col-lg-6">1 giường đơn</span>
                                            <span className="col-lg-6">1 giường đơn</span>
                                            <span className="col-lg-6">1 giường đơn</span>
                                        </div>
                                    </div>
                                    <div className="col-md-3 flex-column">
                                    <span className="w-100 d-flex justify-content-center" style={{ fontWeight: 'bold'}}>Giá phòng:<PriceFormatter price={item.room_rental_price} /></span>
                                        <Link className="btn btn-primary w-100 py-3" href={`/dat-phong/${item.room_slug || ''}`}>Đặt phòng ngay</Link>
                                    </div>
                                </div>
                            </div>
                        )) : <span className='d-flex w-100 justify-content-center'>Hiện tại không tìm thấy bất kỳ phòng trống nào</span>}
                    </div>
                </div>
            </div>
        </Layout>
    );
};

export default Product;