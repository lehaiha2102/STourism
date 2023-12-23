import Link from 'next/link'
import Layout from '../components/Layout'
import { useEffect, useState } from 'react';
import { EstimatedReadingTime } from '../components/EstimatedReadingTime';

function IndexPage() {
  const [categoryList, setCategoryList] = useState([]);
  const [provinceList, setProvinceList] = useState([]);
  const [search, setSearch] = useState(null);
  const [post, setPost] = useState([]);
  const [hotelProductList, setHotelProductList] = useState([]);
  const [currentPage, setCurrentPage] = useState(1);
  const [lastPage, setLastPage] = useState(1);
  useEffect(() => {
    const fetchData = async () => {
      try {
        const res = await fetch(`http://127.0.0.1:8000/api/v2/categories`);
        if (res.ok) {
          const { data } = await res.json();
          setCategoryList(data.data);
        } else {
          console.error('Error fetching data:', res.statusText);
        }
      } catch (error) {
        console.error('Error fetching data:', error.message);
      }
    };

    if (categoryList.length === 0) {
      fetchData();
    }
  }, [categoryList]);

  useEffect(() => {
    const fetchData = async () => {
      try {
        const res = await fetch(`http://127.0.0.1:8000/api/v2/post`);
        if (res.ok) {
          const { data } = await res.json();
          setPost(data.data);
        } else {
          console.error('Error fetching data:', res.statusText);
        }
      } catch (error) {
        console.error('Error fetching data:', error.message);
      }
    };

    if (categoryList.length === 0) {
      fetchData();
    }
  }, [categoryList]);

  useEffect(() => {
    const fetchData = async () => {
      try {
        const res = await fetch(`http://127.0.0.1:8000/api/v2/products/hotel?page=${currentPage}`);
        if (res.ok) {
          const { data } = await res.json();
          setHotelProductList(data.data);
          setLastPage(data.last_page);
        } else {
          console.error('Error fetching data:', res.statusText);
        }
      } catch (error) {
        console.error('Error fetching data:', error.message);
      }
    };

    if (!hotelProductList || hotelProductList.length === 0) {
      fetchData();
    }
  }, [currentPage, hotelProductList]);

  useEffect(() => {
    const fetchData = async () => {
      try {
        const res = await fetch(`http://127.0.0.1:8000/api/v2/provice`);
        if (res.ok) {
          const { data } = await res.json();
          setProvinceList(data);
        } else {
          console.error('Error fetching data:', res.statusText);
        }
      } catch (error) {
        console.error('Error fetching data:', error.message);
      }
    };
    if (!Array.isArray(provinceList) || provinceList.length === 0) {
      fetchData();
    }
  }, [provinceList]);

  const handleSubmit = async (event) => {
    event.preventDefault();
    const selectedProvince = event.target.elements.province.value;
    const adult = event.target.elements.peopleQuantity.value;
    const child = event.target.elements.childQuantity.value;
    const roomQuantity = event.target.elements.roomQuantity.value;

    if (!selectedProvince || !adult || !roomQuantity) {
      console.error('Please fill in all required fields');
      return;
    }

    try {
      const res = await fetch('http://127.0.0.1:8000/api/v2/rooms/search', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          province: selectedProvince,
          adult: adult,
          child: child,
          roomQuantity: roomQuantity,
        }),
      });

      if (res.ok) {
        const dataSearch = await res.json();
        console.log(dataSearch)
        setSearch(dataSearch.data);
      } else {
        console.error('Error fetching data:', res.statusText);
      }
    } catch (error) {
      console.error('Error fetching data:', error.message);
    }
  };

  return (
    <Layout title="Home | Next.js + TypeScript">
      <div className="container-xxl bg-white p-0">
        <div className="container-fluid p-0 mb-5">
          <div id="header-carousel" className="carousel slide" data-bs-ride="carousel">
            <div className="carousel-inner">
              <div className="carousel-item active">
                <img className="w-100" src="../img/carousel-1.jpg" alt="Image" />
                <div className="carousel-caption d-flex flex-column align-items-center justify-content-center">
                  {/* <div className="p-3" style={{ maxWidth: '700px' }}>
                    <h6 className="section-title text-white text-uppercase mb-3 animated slideInDown">Luxury Living</h6>
                    <h1 className="display-3 text-white mb-4 animated slideInDown">Discover A Brand Luxurious Hotel</h1>
                  </div> */}
                </div>
              </div>
              <div className="carousel-item">
                <img className="w-100" src="../img/carousel-2.jpg" alt="Image" />
                <div className="carousel-caption d-flex flex-column align-items-center justify-content-center">
                  {/* <div className="p-3" style={{ maxWidth: '700px' }}>
                    <h6 className="section-title text-white text-uppercase mb-3 animated slideInDown">Luxury Living</h6>
                    <h1 className="display-3 text-white mb-4 animated slideInDown">Discover A Brand Luxurious Hotel</h1>
                  </div> */}
                </div>
              </div>
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
        <div className="container-fluid booking pb-5 wow fadeIn" data-wow-delay="0.1s">
          <div className="container">
            <div className="bg-white shadow" style={{ padding: '35px' }}>
              <form onSubmit={handleSubmit}>
                <div className="row">
                  <div className="col-md-12">
                    <label htmlFor="provice-choose"><strong>Thành phố, địa điểm</strong></label>
                    <select id="provice-choose" className="form-select" defaultValue="" name="province">
                      <option value="3" selected>Đà Nẵng</option>
                      {provinceList ? (
                        provinceList.map((province) => {
                          if (province.id !== 3) {
                            return (
                              <option key={province.id} value={province.id}>
                                {province.name}
                              </option>
                            );
                          }
                          return null;
                        })
                      ) : (
                        <p>Loading...</p>
                      )}
                    </select>
                  </div>
                  <div className="col-md-4 mt-4">
                    <label htmlFor="quantity-people-choose"><strong>Số lượng người</strong></label>
                    <input id="quantity-people-choose" type="number" min={1} className="form-select" defaultValue={1} name="peopleQuantity" />
                  </div>
                  <div className="col-md-4 mt-4">
                    <label htmlFor="quantity-people-choose"><strong>Số lượng trẻ em</strong></label>
                    <input id="quantity-people-choose" type="number" min={0} className="form-select" defaultValue={1} name="childQuantity" />
                  </div>
                  <div className="col-md-4 mt-4">
                    <label htmlFor="quantity-room-choose"><strong>Số lượng phòng</strong></label>
                    <input id="quantity-room-choose" type="number" min={1} className="form-select" defaultValue={1} name="roomQuantity" />
                  </div>
                  <div className="col-md-12 mt-3 d-flex justify-content-center align-items-center">
                    <button className="btn btn-primary px-5">Tìm</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
        {search && Array.isArray(search) ? (
          <div className="container-xxl py-5">
            <div className="container">
              <div className="text-center wow fadeInUp" data-wow-delay="0.1s">
                <h1 className="mb-5">Thông tin khớp với kết quả tìm kiếm của bạn</h1>
              </div>
              <div className="row g-4">
                {search.map((room, index) => (
                  <div
                    className={`col-lg-4 col-md-6 wow fadeInUp`}
                    data-wow-delay={`${0.1 * (index + 1)}s`}
                    key={index}
                  >
                    <div className="room-item shadow rounded overflow-hidden">
                      <div className="position-relative">
                        {room.room_image && room.room_image.length > 0 && (
                          <div className="position-relative">
                            <img
                              className="img-fluid"
                              src={`http://127.0.0.1:8000/images/${JSON.parse(room.room_image)[0]}`}
                              alt=''
                              style={{
                                height: '200px',
                                width: '100%'
                              }}
                            />
                          </div>
                        )}
                      </div>
                      <div className="p-4 mt-2">
                        <div className="d-flex flex-column justify-content-center align-items-center mb-3">
                          <h5 className="mb-0 w-100 text-center">{room.room_name}</h5>
                          <div className="d-flex justify-content-center my-3">
                            <small className="fa fa-star text-primary"></small>
                            <small className="fa fa-star text-primary"></small>
                            <small className="fa fa-star text-primary"></small>
                            <small className="fa fa-star text-primary"></small>
                            <small className="fa fa-star text-primary"></small>
                          </div>
                        </div>
                        <p className="text-body mb-3"><i className="fa fa-map-marker-alt me-3"></i>{room.product_name}, {room.product_address}</p>
                        <p className="text-body mb-3"><i className="fa fa-phone-alt me-3"></i> {room.product_phone}</p>
                        <div className="d-flex justify-content-center">
                          <Link className="btn btn-sm btn-primary rounded py-2 px-4" href={`/phong/${room.room_slug || ''}`}>
                            Xem chi tiết
                          </Link>
                        </div>
                      </div>
                    </div>
                  </div>
                ))}
              </div>
              <div className="text-center wow fadeInUp mt-5" data-wow-delay="0.1s">
                <a className="btn btn-primary py-3 px-5 mt-2" href="">Xem thêm</a>
              </div>
            </div>
          </div>
        ) : (
          ''
        )}
        <div className="container-xxl py-5">
          <div className="container">
            <div className="row g-5 align-items-center">
              <div className="col-lg-6">
                <h6 className="section-title text-start text-primary text-uppercase">Giới thiệu </h6>
                <h1 className="mb-4">Chào mừng đến với <span className="text-primary text-uppercase">STravel</span></h1>
                <p className="mb-4" style={{ textAlign: 'justify' }}>Chào mừng bạn đến với S TRAVEL, website đánh giá nghỉ dưỡng du lịch hàng đầu! Chúng tôi tự hào là địa điểm tốt nhất để bạn khám phá và đặt phòng tại những địa điểm nghỉ dưỡng tuyệt vời trên khắp thế giới.<br /><br />

                  Tại đây, chúng tôi cung cấp cho bạn trải nghiệm đặt phòng trực tuyến thuận lợi, nhanh chóng và an toàn. Với hệ thống đặt phòng thông minh, bạn có thể dễ dàng tìm kiếm và so sánh các lựa chọn phòng, giá cả và tiện ích để chọn ra đúng nơi nghỉ phù hợp với kỳ nghỉ của mình.<br /><br />

                  Ngoài ra, chúng tôi là cộng đồng đánh giá đáng tin cậy, nơi du khách chia sẻ những trải nghiệm thực tế về các địa điểm đã đến. Bạn có thể đọc đánh giá chân thực từ những người đã trải qua và chia sẻ ý kiến của mình để giúp cộng đồng du lịch ngày càng phong phú.

                  Hãy đồng hành cùng chúng tôi để khám phá thế giới và tạo nên những kỷ niệm đáng nhớ tại những điểm đến tuyệt vời nhất!</p>
              </div>
              <div className="col-lg-6">
                <div className="row g-3">
                  <div className="col-6 text-end">
                    <img className="img-fluid rounded w-75 wow zoomIn" data-wow-delay="0.1s" src="img/about-1.jpg" style={{ marginTop: '25%' }} />
                  </div>
                  <div className="col-6 text-start">
                    <img className="img-fluid rounded w-100 wow zoomIn" data-wow-delay="0.3s" src="img/about-2.jpg" />
                  </div>
                  <div className="col-6 text-end">
                    <img className="img-fluid rounded w-50 wow zoomIn" data-wow-delay="0.5s" src="img/about-3.jpg" />
                  </div>
                  <div className="col-6 text-start">
                    <img className="img-fluid rounded w-75 wow zoomIn" data-wow-delay="0.7s" src="img/about-4.jpg" />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div className="container-xxl py-5">
          <div className="container">
            <div className="text-center wow fadeInUp" data-wow-delay="0.1s">
              <h6 className="section-title text-center text-primary text-uppercase">nghỉ dưỡng</h6>
              <h1 className="mb-5"><span className="text-primary">Khám phá top các địa điểm nghỉ dưỡng hàng đầu</span></h1>
            </div>
            <div className="row g-4">
              {Array.isArray(hotelProductList) && hotelProductList.length > 0 ? (
                hotelProductList.map((product, index) => (
                  <div
                    className={`col-lg-4 col-md-6 wow fadeInUp`}
                    data-wow-delay={`${0.1 * (index + 1)}s`}
                    key={index}
                  >
                    <div className="room-item shadow rounded overflow-hidden">
                      <div className="position-relative">
                        <img className="img-fluid" style={{height: '200px'}}  src={`http://127.0.0.1:8000/images/${product.product_main_image}`} alt='' />
                      </div>
                      <div className="p-4 mt-2">
                        <div className="d-flex flex-column justify-content-between mb-3">
                          <h5 className="mb-0 w-100 text-center">{product.product_name}</h5>
                        </div>
                        <p className="text-body mb-3"><i className="fa fa-map-marker-alt me-3"></i>{product.product_address}</p>
                        <p className="text-body mb-3"><i className="fa fa-phone-alt me-3"></i> {product.product_phone}</p>
                        <div className="d-flex justify-content-center">
                          <Link className="btn btn-sm btn-primary rounded py-2 px-4"
                            href={`/san-pham/${product.product_slug}`}>Xem chi tiết</Link>
                        </div>
                      </div>
                    </div>
                  </div>
                ))
              ) : (
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
        <div className="container-xxl py-5">
          <div className="container">
            <div className="text-center wow fadeInUp" data-wow-delay="0.1s">
              <h6 className="section-title text-center text-primary text-uppercase">Bài viết</h6>
            </div>
            <div className="row g-4">
              {post ? (
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
                        <p className="text-body mb-3">{post_item.description}</p>
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
              ) : (
                <p>Loading...</p>
              )}
            </div>
            <div className="text-center wow fadeInUp mt-5" data-wow-delay="0.1s">
              <a className="btn btn-primary py-3 px-5 mt-2" href="">Xem thêm</a>
            </div>
          </div>
        </div>
        <div className="container-xxl py-5">
          <div className="container">
            <div className="text-center wow fadeInUp" data-wow-delay="0.1s">
              <h6 className="section-title text-center text-primary text-uppercase">Dịch vụ</h6>
              <h1 className="mb-5">Khám phá <span className="text-primary">dịch vụ của chúng tôi</span></h1>
            </div>
            <div className="row g-4">
              {categoryList ? (
                categoryList.map((category, index) => (
                  <div
                    className={`col-lg-4 col-md-6 wow fadeInUp`}
                    data-wow-delay={`${0.1 * (index + 1)}s`}
                    key={index}
                  >
                    <Link className="service-item rounded"
                      href={`/danh-muc/${category.category_slug}`}
                    >
                      <div className="service-icon bg-transparent border rounded p-1 w-50 h-50">
                        <div className="w-100 h-100 border rounded d-flex align-items-center justify-content-center">
                          <img className="w-100 h-100 text-primary" src={`http://127.0.0.1:8000/images/${category.category_image}`} alt='' />
                        </div>
                      </div>
                      <h5 className="mb-3">{category.category_name}</h5>
                    </Link>
                  </div>
                ))
              ) : (
                <p>Loading...</p>
              )}
            </div>
          </div>
        </div>
      </div>
    </Layout >
  )
}
export default IndexPage