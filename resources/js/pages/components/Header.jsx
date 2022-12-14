import { FaBars } from "react-icons/fa";
import { AiOutlineClose } from "react-icons/ai";
import { BsMoon, BsSun } from "react-icons/bs";
import { useContext } from "react";
import { darkTheme } from "../context/darkTheme";
import { useNavigate } from "react-router-dom";

import { useCookies } from "react-cookie";

export default function Header() {
    const { isDark, toggleDark, isShow, toggleMenu } = useContext(darkTheme);

    const [cookies, setCookie, removeCookie] = useCookies("");
    const navigate = useNavigate();
    // console.log(cookies);

    // let token = document.head.querySelector('meta[name="XSRF-TOKEN"]');
    // console.log(document.cookie);

    // axios.defaults.withCredentials = true;
    const logoutFunc = async () => {
        const adminTrue = JSON.parse(localStorage.getItem("uTk"));
        // removeCookie();
        try {
            let res = await axios.post(
                `${process.env.MIX_APP_URL}/api/logout`,
                {},
                {
                    headers: { Authorization: `Bearer ${adminTrue}` },
                }
            );
            console.log(res);
            localStorage.removeItem("uTk");
            navigate("/adminlogin");
        } catch (er) {
            console.log(er);
        }
        // axios
        //     .get(`${process.env.MIX_APP_URL}/` + "sanctum/csrf-cookie")
        //     .then(async (res) => {
        //     });
    };
    return (
        <div
            className={
                isShow
                    ? `w-full h-16 bg-white shadow-lg flex items-center justify-between px-10 fixed z-50 dark:bg-slate-800 dark:text-white`
                    : `w-full h-16 bg-white shadow-lg flex items-center justify-between px-10 fixed z-50 dark:bg-slate-800 dark:text-white`
            }
        >
            <div className="cursor-pointer text-xl">
                {!isShow ? (
                    <FaBars onClick={toggleMenu} />
                ) : (
                    <AiOutlineClose onClick={toggleMenu} />
                )}
            </div>
            <div className="flex items-center gap-4 text-xl">
                <button onClick={toggleDark}>
                    {isDark ? <BsSun /> : <BsMoon />}
                </button>
                <button
                    onClick={() => logoutFunc()}
                    className="bg-slate-300 rounded-md text-black"
                >
                    خروج
                </button>
                {/* <span className="text-lg font-semibold cursor-pointer">
          <Link to="/profile">
            <AccountCircleIcon />
          </Link>
        </span> */}
            </div>
        </div>
    );
}
