import React from "react";

const UsersComponent = (usersArray) => {
    const updateUserInfo = (oneuser) => {
        console.log(oneuser);
    };

    return (
        <div className="users-container flex flex-wrap gap-5 p-1">
            {usersArray.usersArray &&
                usersArray.usersArray.map((oneUser) => (
                    <div
                        key={oneUser.id}
                        className="bg-slate-200 rounded-md p-1"
                    >
                        <h1>
                            {oneUser.f_name} {oneUser.m_name} {oneUser.l_name}
                        </h1>
                        <h1>{oneUser.email}</h1>
                        <h1>{oneUser.phone}</h1>
                        {/* <button
                            onClick={() => updateUserInfo(oneUser)}
                            className="bg-green-500 text-white p-1 m-1"
                        >
                            تعديل المعلومات
                        </button> */}
                    </div>
                ))}
        </div>
    );
};

export default UsersComponent;
