import { useEffect, useState } from "react"
import Link from 'next/link';
import { StorageKeys } from "../utils/constant";
import { IUser } from "../models/user.model";
import { Dropdown } from 'react-bootstrap';
import { useRouter } from "next/router";

export default function Header() {
    const [categoryList, setCategoryList] = useState([]);
    const [isLoggedIn, setIsLoggedIn] = useState(false);
    const [user, setUser] = useState<IUser>();
    const router = useRouter();
    useEffect(() => {
        const token = localStorage.getItem(StorageKeys.jwt);
        const userString = localStorage.getItem(StorageKeys.USER);
        const user = JSON.parse(userString);
        setUser(user);
        if (token && user) {
            setIsLoggedIn(true);
        } else {
            setIsLoggedIn(false);
        }
    }, []);
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
    const handleLogout = () => {
        localStorage.removeItem('access_token');
        localStorage.removeItem('user');
        router.push('/dang-nhap');
      };
      
    return (
        <div className="container-fluid bg-dark px-0 position-relative py-3" style={{ zIndex: '999' }}>
            <div className="row gx-0">
                <div className="col-lg-3 bg-dark d-none d-lg-block">
                    <Link href="/" className="navbar-brand w-100 h-100 m-0 p-0 d-flex align-items-center justify-content-center">
                        <h1 className="m-0 text-primary text-uppercase">S TRAVEL</h1>
                    </Link>
                </div>
                <div className="col-lg-9">
                    <nav className="navbar navbar-expand-lg bg-dark navbar-dark p-3 p-lg-0">
                        <div className="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                            <div className="navbar-nav mr-auto py-0">
                            </div>
                            {isLoggedIn
                                ? (
                                    <Dropdown>
                                        <Dropdown.Toggle variant="success" style={{ backgroundColor: '#ea9414' }} id="dropdown-basic" className="mx-5">
                                            <strong>
                                                <i className="fa fa-plane mx-3" aria-hidden="true"></i>
                                                {user.full_name}
                                            </strong>
                                        </Dropdown.Toggle>

                                        <Dropdown.Menu>
                                            <Dropdown.Item href="#/action-1">
                                                <Link href={'/quan-ly-tai-khoan'}>
                                                    Quản lý tài khoản
                                                </Link>
                                            </Dropdown.Item>
                                            <Dropdown.Item href="#/action-2">
                                                <Link  href="#" onClick={handleLogout}>Đăng xuất</Link>
                                            </Dropdown.Item>
                                        </Dropdown.Menu>
                                    </Dropdown>
                                ) : (
                                    <Link
                                        href="/dang-nhap"
                                        className="btn btn-primary rounded-0 py-4 px-md-5 d-none d-lg-block">
                                        Đăng nhập
                                        <i className="fa fa-arrow-right ms-3"></i>
                                    </Link>
                                )}
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    )
}