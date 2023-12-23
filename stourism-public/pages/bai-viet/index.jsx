'use client'
import dynamic from 'next/dynamic';
import React, { useEffect, useState } from 'react';
import Layout from '../../components/Layout';
import { StorageKeys, apiURL } from '../../utils/constant'

const CustomEditor = dynamic(() => {
  return import('../../components/custom-editor');
}, { ssr: false });

export default function Post() {
  const [postData, setPostData] = useState({
    title: '',
    target: '',
    description: '',
    images: [],
    content: '',
  });
  const [images, setImages] = useState([]);
  const [fileError, setFileError] = useState('');
  const [isLoading, setIsLoading] = useState(false);
  const handleImageChange = (e) => {
    const files = Array.from(e.target.files);
    const allowedExtensions = ['jpg', 'jpeg', 'png'];

    // Kiểm tra đuôi của từng file
    const isValidFiles = files.every((file) => {
      const extension = file.name.split('.').pop().toLowerCase();
      return allowedExtensions.includes(extension);
    });

    if (isValidFiles) {
      setFileError(''); // Đặt thông báo lỗi về rỗng nếu tất cả các file hợp lệ
      setImages(files);
    } else {
      setFileError('Chỉ chấp nhận các file với đuôi jpg, jpeg, png.');
    }
  };

  const [productList, setProductList] = useState([]);

  useEffect(() => {
    const fetchData = async () => {
      try {
        const res = await fetch(`http://127.0.0.1:8000/api/v2/products`);
        if (res.ok) {
          const { data } = await res.json();
          setProductList(data);
        } else {
          console.error('Error fetching data:', res.statusText);
        }
      } catch (error) {
        console.error('Error fetching data:', error.message);
      }
    };

    if (!productList || productList.length === 0) {
      fetchData();
    }
  }, [productList]);

  const getContent = (content) => {
    setPostData((prevData) => ({ ...prevData, content: content }));
  }

  const handleInputChange = (e) => {
    const { name, value } = e.target;
    setPostData((prevData) => ({ ...prevData, [name]: value }));
  };

  const handleFormSubmit = async (e) => {
    e.preventDefault();
    try {
      setIsLoading(true);
      const token = localStorage.getItem(StorageKeys.jwt);
      const formData = new FormData();
      formData.append('title', postData.title);
      formData.append('target', postData.target);
      formData.append('description', postData.description);
      formData.append('content', postData.content);
      images.forEach((image, index) => {
        formData.append(`post_images[${index}]`, image);
      });

      const response = await fetch(`${apiURL}/api/v2/post`, {
        method: 'POST',
        headers: {
          'Authorization': `Bearer ${token}`,
        },
        body: formData,
      });

      const result = await response.json();
    } catch (error) {
      console.error('Error saving data:', error);
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <Layout>
      <div className="container-xxl py-5 bg-white">
        <div className="container">
          <div className="row g-5 align-items-center">
            <div className="col-lg-12">
              <h1 className="section-title text-start text-primary text-uppercase">Tạo mới bài viết</h1>
              <form onSubmit={handleFormSubmit}>
                <div className="form-floating mb-3">
                  <input type="text" className="form-control" id="title" name='title' onChange={handleInputChange} placeholder='Nhập vào tiêu đề của bài viết' />
                  <label htmlFor="title">Tên bài viết</label>
                </div>
                <div className="form-floating mb-3">
                  <select className="form-select" id="target" name='target' onChange={handleInputChange}>
                  <option selected></option>
                    {productList && productList.length > 0 ? (
                      productList.map((product) => (
                        <option key={product.id} value={product.id}>
                          {product.product_name}
                        </option>
                      ))
                    ) : (
                      <option value="" disabled selected>
                        Không tìm thấy đối tượng để viết đánh giá
                      </option>
                    )}
                  </select>
                  <label htmlFor="target">Đối tượng đang được nhắc đến</label>
                </div>
                <div className="form-floating mb-3">
                  <input type="text" className="form-control" id="description" name='description' onChange={handleInputChange} placeholder='Nhập vào tiêu đề của bài viết' />
                  <label htmlFor="description">Mô tả ngắn</label>
                </div>
                <div className="form-floating mb-3">
                  <div className="form-floating mb-3">
                    <input
                      type="file"
                      multiple
                      className="form-control"
                      id="images"
                      name='images'
                      onChange={handleImageChange}
                    />
                    <label htmlFor="images">Hình ảnh về sản phẩm/dịch vụ</label>
                    {fileError && <div className="text-danger">{fileError}</div>}
                  </div>
                </div>
                <div className="mb-3">
                  <CustomEditor
                    getContent={getContent}
                  />
                </div>
                <div className="mb-3">
                  <button
                    className="btn btn-primary w-100"
                    disabled={isLoading}
                  >
                    {isLoading ? 'Loading...' : 'Save'}
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </Layout>
  )
}
