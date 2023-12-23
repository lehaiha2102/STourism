import { useRouter } from 'next/router';
import { useEffect, useState } from 'react';
import Layout from "../../../components/Layout";
import ReactHtmlParser from 'react-html-parser';
import { EstimatedReadingTime } from '../../../components/EstimatedReadingTime';


const PostDetail = () => {
    const router = useRouter();
    const { id } = router.query;
    const [post, getPost] = useState();

    useEffect(() => {
        const fetchData = async () => {
            try {
                const res = await fetch(`http://127.0.0.1:8000/api/v2/post/${id}`, {
                    method: 'GET',
                });

                if (res.ok) {
                    const { data } = await res.json();
                    console.log(data);
                    getPost(data);
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

    console.log(post);
    return (
        <Layout>
            <div className="container-xxl py-5">
                <div className="container">
                    <div className="text-center wow fadeInUp" data-wow-delay="0.1s">
                        <h1 className="mb-5">{post?.title}</h1>
                    </div>
                    <div className="row mb-5 shadow p-3 mb-5 bg-white rounded">
                        <div className="col-md-12">
                            <span>Tác giả: {post?.full_name}</span>
                        </div>
                        <div className="col-md-12">
                            <span>Bài viết liên quan đến địa điểm: {post?.product_name}</span>
                        </div>
                        <div className="col-md-6">
                            <span>Đã đăng vào: {post?.created_at}</span>
                        </div>
                        <div className="col-md-6">
                            <span>Đã cập nhật vào: {post?.updated_at}</span>
                        </div>
                        <div className="col-md-6">
                            <span>Ước tính thời gian đọc:{EstimatedReadingTime(ReactHtmlParser(post?.content))} phút</span>
                        </div>
                    </div>
                    <div className="description mb-5">
                        <span style={{ textAlign: 'justify', fontWeight: 'bold' }}>{ReactHtmlParser(post?.description)}</span>
                    </div>
                    <div className="content mb-5">
                        <span style={{ textAlign: 'justify' }}>{ReactHtmlParser(post?.content)}</span>
                    </div>
                </div>
            </div>
        </Layout >
    );
};

export default PostDetail;