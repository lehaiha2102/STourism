import { useRouter } from 'next/router';
import { useEffect, useState } from 'react';
import Layout from '../../../components/Layout';
import Link from 'next/link';
const Category = () => {
  const router = useRouter();
  const { slug } = router.query;
  const [category, setCategory] = useState();
  const [product, setProduct] = useState();
  const [productId, setProductId] = useState();

  useEffect(() => {
    const fetchData = async () => {
      try {
        const res = await fetch(`http://127.0.0.1:8000/api/v2/${slug}/categories`);
        if (res.ok) {
          const { data } = await res.json();
          setCategory(data);
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

  //   useEffect(() => {
  //     const fetchData = async () => {
  //         try {
  //             const res = await fetch(`http://127.0.0.1:8000/api/v2/${slug}/products`);
  //             if (res.ok) {
  //                 const { data } = await res.json();
  //                 setProduct(data);
  //                 setProductId(data?.id);
  //             } else {
  //                 console.error('Error fetching data:', res.statusText);
  //             }
  //         } catch (error) {
  //             console.error('Error fetching data:', error.message);
  //         }
  //     };

  //     if (slug) {
  //         fetchData();
  //     }
  // }, [slug]);

  return (
    <Layout>
      <div className="container-xxl bg-white p-0">
        <div className="container-fluid page-header mb-5 p-0" style={{ backgroundImage: `url('http://127.0.0.1:8000/images/${category?.category_banner}')` }}>
          <div className="container-fluid page-header-inner py-5">
            <div className="container text-center pb-5">
              <h1 className="display-3 text-white mb-3 animated slideInDown text-uppercase">{category ? category?.category_name : <p>Loading...</p>}</h1>
              <nav aria-label="breadcrumb">
                <ol className="breadcrumb justify-content-center">
                  <li className="breadcrumb-item"><Link href={`/`}>Trang chủ</Link></li>
                  <li className="breadcrumb-item"><Link href={`/danh-muc`}>Dịch vụ</Link></li>
                  <li className="breadcrumb-item text-white active d-flex" aria-current="page">{category ? category?.category_name : <p>Loading...</p>}</li>
                </ol>
              </nav>
            </div>
          </div>
        </div>
      </div>
      <div className="row g-4">
        {/* {hotelProductList ? (
          hotelProductList.map((product, index) => (
            <div
              className={`col-lg-4 col-md-6 wow fadeInUp`}
              data-wow-delay={`${0.1 * (index + 1)}s`}
              key={index}
            >
              <div className="room-item shadow rounded overflow-hidden">
                <div className="position-relative">
                  <img className="img-fluid" src={`http://127.0.0.1:8000/images/${product.product_main_image}`} alt='' />
                </div>
                <div className="p-4 mt-2">
                  <div className="d-flex flex-column justify-content-between mb-3">
                    <h5 className="mb-0">{product.product_name}</h5>
                    <div className="d-flex justify-content-center my-3">
                      <small className="fa fa-star text-primary"></small>
                      <small className="fa fa-star text-primary"></small>
                      <small className="fa fa-star text-primary"></small>
                      <small className="fa fa-star text-primary"></small>
                      <small className="fa fa-star text-primary"></small>
                    </div>
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
        )} */}
      </div>
    </Layout>
  );
};

export default Category;