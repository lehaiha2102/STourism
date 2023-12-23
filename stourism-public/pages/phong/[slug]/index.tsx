import { useRouter } from 'next/router';
import { useEffect, useState } from 'react';
import Layout from '../../../components/Layout';
import Link from 'next/link';
import ReactHtmlParser from 'react-html-parser';

function tryParseJson(jsonString) {
    try {
        const parsed = JSON.parse(jsonString);
        return Array.isArray(parsed) ? parsed : [];
    } catch (error) {
        return [];
    }
}

const Room = () => {
    const router = useRouter();
    const { slug } = router.query;
    const [room, setRoom] = useState([]);
    const [rating, setRating] = useState([]);

    useEffect(() => {
        const fetchData = async () => {
            try {
                const res = await fetch(`http://127.0.0.1:8000/api/v2/${slug}/rooms`);
                if (res.ok) {
                    const { data } = await res.json();
                    setRoom(data);
                    setCurrentImage(JSON.parse(room.room_image)[0]);

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
                const res = await fetch(`http://127.0.0.1:8000/api/v2/rating-for-room/${room?.room_slug}`);
                if (res.ok) {
                    const { data } = await res.json();
                    setRating(data);
                    console.log('setRating', data.ratings);

                } else {
                    console.error('Error fetching data:', res.statusText);
                }
            } catch (error) {
                console.error('Error fetching data:', error.message);
            }
        };

        if (room?.room_slug) {
            fetchData();
        }
    }, [room?.room_slug]);

    const [currentImage, setCurrentImage] = useState(
        room?.room_image && room?.room_image.length > 0
            ? JSON.parse(room.room_image)[0]
            : 'logos/favicon.png'
    );

    const handleImageClick = (image) => {
        setCurrentImage(image);
    };
    return (
        <Layout>
            <div className="container bootdey row m-3 py-3">
                <div className="col-md-12 col-lg-12 col-12">
                    <section className="panel">
                        <div className="panel-body row">
                            <div className="col-md-12 col-lg-6 col-12">
                                <div className="pro-img-details w-100">
                                    {room?.room_image && room?.room_image.length > 0 && (
                                        <img
                                            className="img-fluid rounded w-100 wow zoomIn"
                                            src={`http://127.0.0.1:8000/images/${JSON.parse(room.room_image)[0]}`}
                                            data-wow-delay="0.1s"
                                            style={{ visibility: 'visible', animationDelay: '0.1s', animationName: 'zoomIn' }}
                                        />
                                    )}
                                </div>
                                {/* <div className="pro-img-list d-flex flex-1 w-100">
                                    {room?.room_image && room?.room_image.length > 0 && JSON.parse(room.room_image).map((image, index) => (
                                        <a key={index} href="#" onClick={() => handleImageClick(image)} className='m-2'>
                                            <img
                                                src={`http://127.0.0.1:8000/images/${image}`}
                                                alt=""
                                                className='w-100' height={50}
                                            />
                                        </a>
                                    ))}
                                </div> */}
                            </div>
                            <div className="col-md-6 d-flex flex-column justify-content-center">
                                <h4 className="pro-d-title">
                                    {room?.room_name}
                                </h4>
                                <p>
                                    {ReactHtmlParser(room?.room_description)}
                                </p>
                                <div className="product_meta">
                                    <span className="posted_in">Sức chứa: {room?.adult_capacity} người lớn, {room?.children_capacity} trẻ em / 1 phòng </span>
                                </div>
                                <div className="m-bot15"> <span className="amount-old">Giá phòng: {room?.room_rental_price} VND</span></div>
                                <div className='d-flex justify-content-center align-items-center'>
                                    <Link className="btn btn-round btn-primary" href={`/dat-phong/${room?.room_slug}`}>
                                        <i className="fa fa-shopping-cart"></i> Thuê phòng
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
            <div className="container">

                <div className="row">
                    <div className="col-lg-12 col-md-12 col-12">
                        <div className="rating-block d-flex">
                            <h4>Đánh giá của người dùng về phòng này: </h4>
                            <h4 className="bold padding-bottom-7 text-primary">{rating?.avg_rating ? rating?.avg_rating : 0}<small>/ 5</small><span>({rating?.sum_rating ? rating?.sum_rating : 0} đánh giá)</span></h4>
                        </div>
                    </div>
                </div>

                <div className="row">
                    <div className="col-lg-12 col-md-12 col-12">
                        <hr />
                        <div className="review-block">
                            {Array.isArray(rating?.ratings) && rating?.ratings.map((rate) => {
                                return (
                                    <>
                                        <div className="row">
                                            <div className="col-sm-3 col-lg-3 col-md-3 col-3 d-flex flex-column justify-content-center align-items-center">
                                                {rate?.avatar ?
                                                    (<img className='img-rounded' height={100} width={100} src={`http://127.0.0.1:8000/images/${rate?.avatar}`} />)
                                                    :
                                                    (<img src="http://dummyimage.com/60x60/666/ffffff&text=No+Image" className="img-rounded" />)
                                                }
                                            </div>
                                            <div className="col-sm-9 col-lg-9 col-md-9 col-9">
                                                <div className="review-block-rate">
                                                    <div className="review-block-name">{rate?.full_name}</div>
                                                    <div className="review-block-date">{rate?.updated_at}<br /></div>
                                                    <div>
                                                        {Array.from({ length: 5 }, (_, index) => (
                                                            <small
                                                                key={index}
                                                                className={`fa ${rate?.rating_star >= index + 1 ? 'fa-star' : 'fa-star-o'} text-primary`}
                                                            ></small>
                                                        ))}
                                                    </div>

                                                </div>
                                                <div className="review-block-description">{rate?.comment}</div>
                                            </div>
                                        </div>
                                        <hr />
                                    </>
                                )
                            })}
                        </div>
                    </div>
                </div>

            </div>
        </Layout>
    );
};

export default Room;