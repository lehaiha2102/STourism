import { useEffect, useState } from "react"
import Link from 'next/link';


export default function Header() {
    const [categoryList, setCategoryList] = useState([]);

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

    return (
        <div className="container-fluid bg-dark px-0 position-relative" style={{ zIndex: '999'}}>
            <div className="row gx-0">
                <div className="col-lg-3 bg-dark d-none d-lg-block">
                    <Link href="/" className="navbar-brand w-100 h-100 m-0 p-0 d-flex align-items-center justify-content-center">
                        <h1 className="m-0 text-primary text-uppercase">STOURISM</h1>
                    </Link>
                </div>
                <div className="col-lg-9">
                    <div className="row gx-0 bg-white d-none d-lg-flex">
                        <div className="col-lg-7 px-5 text-end">
                            <div className="h-100 d-inline-flex align-items-center py-2 me-4">
                                <i className="fa fa-envelope text-primary me-2"></i>
                                <a href="mailto: lehaiha.dev@gmail.com" className="mb-0">lehaiha.dev@gmail.com</a>
                            </div>
                            <div className="h-100 d-inline-flex align-items-center py-2">
                                <i className="fa fa-phone-alt text-primary me-2"></i>
                                <a href="tel: 0903569598" className="mb-0">+84912745231</a>
                            </div>
                        </div>
                    </div>
                    <nav className="navbar navbar-expand-lg bg-dark navbar-dark p-3 p-lg-0">
                        <a href="index.html" className="navbar-brand d-block d-lg-none">
                            <h1 className="m-0 text-primary text-uppercase">S Tourism</h1>
                        </a>
                        <button type="button" className="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                            <span className="navbar-toggler-icon"></span>
                        </button>
                        <div className="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                            <div className="navbar-nav mr-auto py-0">
                                <Link className="nav-item nav-link" href={`/`}>TRANG CHỦ</Link>
                                <Link className="nav-item nav-link" href={`/gioi-thieu`}>GIỚI THIỆU</Link>
                                <div className="nav-item dropdown">
                                    <a href="#" className="nav-link dropdown-toggle" data-bs-toggle="dropdown">Dịch vụ</a>
                                    <div className="dropdown-menu rounded-0 m-0">
                                    {Array.isArray(categoryList) && categoryList.map((category, index) => (
                                        <Link className="dropdown-item" href={`/danh-muc/${category.category_slug}`} key={index}>
                                            {category.category_name}
                                        </Link>
                                    ))}
                                    </div>
                                </div>
                                <Link className="nav-item nav-link" href={`/bai-viet`}>BÀI VIẾT</Link>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    )
}