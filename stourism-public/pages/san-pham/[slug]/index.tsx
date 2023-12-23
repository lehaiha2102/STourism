import { useRouter } from 'next/router';
import { useEffect, useState } from 'react';
import Layout from '../../../components/Layout';
import Link from 'next/link';
import { apiURL } from '../../../utils/constant';
import IProduct from '../../../models/product.model';
import IRoom from '../../../models/room.model';
import ReactHtmlParser from 'react-html-parser';

const Product = () => {
    const router = useRouter();
    const { slug } = router.query;
    const [product, setProduct] = useState<IProduct>();
    const [room, setRoom] = useState<IRoom>();
    const [productId, setProductId] = useState('');
    const [currentPage, setCurrentPage] = useState(1);
    const [lastPage, setLastPage] = useState(1);

    useEffect(() => {
        const fetchData = async () => {
            try {
                const res = await fetch(`http://127.0.0.1:8000/api/v2/${slug}/products`);
                if (res.ok) {
                    const { data } = await res.json();
                    setProduct(data);
                    setProductId(data?.product_slug);
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

    console.log(product);


    useEffect(() => {
        const fetchData = async () => {
            try {
                const res = await fetch(`http://127.0.0.1:8000/api/v2/product/${productId}/rooms?page=${currentPage}`);
                if (res.ok) {
                    const { data } = await res.json();
                    setRoom(data.data);
                    setCurrentPage(data.current_page)
                    setLastPage(data.last_page)
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
    }, [productId, currentPage]);

    return (
        <Layout>
            <div className="container bootdey row card m-3 py-3">
                <div className="col-md-12 col-lg-12 col-12">
                    <section className="panel">
                        <div className="row">
                            <div className="col-md-12 col-lg-6 col-12">
                                <div className="pro-img-details w-100 h-100 d-flex justify-content-center align-items-center">
                                    <img className='w-100' src={`http://127.0.0.1:8000/images/${product?.product_main_image}`} alt="" />
                                </div>
                                {/* <div className="pro-img-list">
                                    {Array.isArray(product?.product_image) && product?.product_image.length > 0 && (
                                        <>
                                            {tryParseJson(product?.product_image).map((image, index) => (
                                                <div className={`carousel-item ${index === 0 ? 'active' : ''}`} key={index}>
                                                    <img
                                                        className="img-fluid rounded w-100 wow zoomIn mb-5"
                                                        src={`http://127.0.0.1:8000/images/${image}`}
                                                        alt={`Slide ${index + 1}`}
                                                    />
                                                </div>
                                            ))}
                                        </>
                                    )}

                                </div> */}
                            </div>
                            <div className="col-md-6 d-flex flex-column justify-content-center">
                                <h4 className="pro-d-title">
                                    {product?.product_name}
                                </h4>
                                <p>
                                    Địa chỉ: {product?.product_address}
                                </p>
                                <p>
                                    Số điện thoại: {product?.product_phone}
                                </p>
                                <p>
                                    Email: {product?.product_email}
                                </p>
                                <div className="product_meta flex-column">
                                    <span className="posted_in"> Mô tả: {ReactHtmlParser(product?.product_description)}</span>
                                    <span className="tagged_as">Chủ sở hữu: {product?.business_name}</span>
                                </div>
                                <div >
                                    <span>Dịch vụ:</span>
                                    <ul>
                                        {product?.product_service && (
                                            JSON.parse(product.product_service).map((service, index) => (
                                                <li key={index}>{service}</li>
                                            ))
                                        )}
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
                <div className="row g-4">
                    {Array.isArray(room) && room.length > 0 ? (
                        room.map((item, index) => (
                            <div
                                className={`col-lg-4 col-md-6 wow fadeInUp`}
                                data-wow-delay={`${0.1 * (index + 1)}s`}
                                key={index}
                            >
                                <div className="room-item shadow rounded overflow-hidden">
                                    <div className="position-relative">
                                        <img className="img-fluid" src={`http://127.0.0.1:8000/images/${JSON.parse(item.room_image)[0]}`} alt='' />
                                    </div>
                                    <div className="p-4 mt-2">
                                        <div className="d-flex flex-column justify-content-between mb-3">
                                            <h5 className="mb-0 w-100 text-center">{item.room_name}</h5>
                                        </div>
                                        <p className="text-body mb-3">{ReactHtmlParser(item.room_description)}</p>
                                        <p className="text-body mb-3">Giá phòng: {item.room_rental_price} VND/Đêm</p>
                                        <div className="d-flex justify-content-center">
                                            <Link className="btn btn-sm btn-primary rounded py-2 px-4"
                                                href={`/phong/${item.room_slug}`}>Xem chi tiết</Link>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        ))
                    ) : (
                        <p>Loading...</p>
                    )}
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
        </Layout>
    );
};

export default Product;