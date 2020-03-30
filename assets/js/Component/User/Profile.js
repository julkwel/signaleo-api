import React, {useEffect, useState} from "react";
import Axios from "axios";
import male from '../../Assets/male.png'
import female from '../../Assets/female.svg'
import Button from "react-bootstrap/Button";

const Profile = () => {
    const [userData, setUserData] = useState([]);
    let user = JSON.parse(localStorage.getItem('user'));

    useEffect(() => {

        if (user && user.id) {
            getUserData();
        }
    }, []);

    const getUserData = () => {
        Axios.post('/api/user/details/' + user.id).then((res) => {
            console.log(res)
            setUserData([res.data.user]);
        })
    };

    return (
        <div style={{top: "50px", position: "relative"}}>
            <div className="container">
                {
                    userData.map(item => {
                        return (
                            <div key={item.id} className="card align-items-center justify-content-center">
                                <div className="d-flex">
                                    <div className="image_outer_container">
                                        <div className="green_icon"/>
                                        <div className="image_inner_container">
                                            <img alt={"userProfileImage"} src={item.photo ? item.photo :
                                                (item.gender === "Lahy" ? male : female)}/>
                                        </div>
                                    </div>
                                </div>
                                <div className={"button-container"}>
                                    <Button href={"/edit-profile"} className={"button-edit-profile"} variant="primary">
                                        <i className={"fas fa-pen"}/>
                                    </Button>
                                </div>
                                <div className={"text-cente"}>
                                    <h2>{item.name ? item.name : "Signaleo"}</h2>
                                </div>
                                <table className={"table table-stripped"}>
                                    <tbody>
                                    <tr>
                                        <td>Lahy/Vavy</td>
                                        <td>{item.gender}</td>
                                    </tr>
                                    <tr>
                                        <td>Contact</td>
                                        <td>{item.contact}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        )
                    })
                }
            </div>
        </div>
    )
};

export default Profile;
