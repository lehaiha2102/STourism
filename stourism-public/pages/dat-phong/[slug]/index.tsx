import { useRouter } from 'next/router';
import { useEffect, useRef, useState } from 'react';
import Layout from '../../../components/Layout';
import Link from 'next/link';
import { PayPalScriptProvider, PayPalButtons } from "@paypal/react-paypal-js";
import { useDispatch } from 'react-redux';

const Booking = () => {
    const router = useRouter();
    const dataOrderRef = useRef();
    const { slug } = router.query;
    console.log(slug);
    
    const [product, setProduct] = useState();
    const [room, setRoom] = useState([]);
    const [productId, setProductId] = useState('');
    const dispatch = useDispatch();

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
                                    <li className="breadcrumb-item"><Link href={`/danh-muc`}>Đặt phòng</Link></li>
                                    <li className="breadcrumb-item text-white active d-flex" aria-current="page">{product ? product?.product_name : <p>Loading...</p>}</li>
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
                        <div className="col-lg-6">
                            <PayPalScriptProvider options={{
                                clientId: "Ac5e4GqEHjI1z8qIbZpv60vDrH2fyG0HvvIxQV5Porhb2SrGr58E49WCwOUb5hvZtDqfD6dF0IfLkRtK",

                            }}>
                                <PayPalButtons
                                    createOrder={(data, actions) => {
                                        return actions.order.create({
                                            purchase_units: [
                                                {
                                                    amount: {
                                                        value: totalPriceUSD,
                                                    },
                                                },
                                            ],
                                        });
                                    }}
                                    onApprove={async (data, actions) => {
                                        const details = await actions.order.capture();

                                        const idPayment = details.id;
                                        const status = details.status;
                                        const paymentTime = details.create_time;
                                        const dateTimeString = paymentTime
                                            .replace("T", " ")
                                            .replace("Z", "");
                                        const formattedPaymentTime = new Date(dateTimeString)
                                            .toISOString()
                                            .slice(0, 19)
                                            .replace("T", " ");

                                        const user = JSON.parse(localStorage.getItem("user"));
                                        const userId = user.id;

                                        const dataAll = dataOrderRef.current;

                                        const updatedDataAll = {
                                            // ...dataAll,
                                            user_id: userId,
                                            status: status,
                                            paymentmode: "Paid by Paypal",
                                            idPayment: idPayment,
                                            paymentTime: formattedPaymentTime,
                                        };
                                        // const response = await checkoutApi.order(updatedDataAll);
                                        // dataAll.order_items.forEach((item) => {
                                        //     const productId = item.id;
                                        //     dispatch(removeFromCart(productId));
                                        // });
                                        router.push('/');
                                        // enqueueSnackbar("Mua hàng thành công !!!", {
                                        //     variant: "success",
                                        //     autoHideDuration: 7000,
                                        // });
                                    }}
                                />
                            </PayPalScriptProvider>
                        </div>
                    </div>
                </div>
            </div>
        </Layout>
    );
};

export default Booking;