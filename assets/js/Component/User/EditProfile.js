import React, {useEffect, useState} from "react";
import {useForm} from "react-hook-form";
import {useHistory} from "react-router";
import Axios from "axios";
import male from "../../Assets/male.png";
import female from "../../Assets/female.svg";

const EditProfile = () => {
    const {register, handleSubmit} = useForm();
    const [user, setUser] = useState();
    const history = useHistory();

    useEffect(() => {
        getUser();
    }, []);

    const getUser = () => {
        let user = JSON.parse(localStorage.getItem('user'));
        if (!user) {
            history.push('/login');
            location.reload();
        } else {
            Axios.post('/api/user/details/' + user.id).then(res => {
                setUser(res.data.user);
            })
        }
    };

    const onsubmit = (data) => {
        Axios.post('/add/user/api', data).then((res) => history.push('/'))
    };

    return (
        <div className={"edit-profile"} style={{marginTop: "10%", position: "relative"}}>
            <div className={"col-md-4 m-auto"}>
                <div className={"card"}>
                    <div className="card-body">
                        <div className="d-flex justify-content-center">
                            <div className="image_outer_container">
                                <div className="green_icon"/>
                                <div className="image_inner_container">
                                    <img alt={"userProfileImage"} src={user && user.gender === "Lahy" ? male : female}/>
                                </div>
                            </div>
                        </div>
                        <div className="mt-2"/>
                        <form action="" onSubmit={handleSubmit(onsubmit)}>
                            <input type="text" name={"id"} defaultValue={user && user.id} ref={register}
                                   className={"d-none"}/>
                            <div className="form-group">
                                <select name="gender" className={"form-control"} ref={register({required: true})} id="">
                                    <option
                                        value="Lahy" {...(user && user.gender === 'Lahy' ? {"selected": true} : "")}>Lahy
                                    </option>
                                    <option
                                        value="Vavy" {...(user && user.gender === 'Vavy' ? {"selected": true} : "")}>Vavy
                                    </option>
                                </select>
                            </div>
                            <div className="form-group">
                                <input placeholder={"Anarana"} defaultValue={user && user.name}
                                       ref={register({required: true})} type="text"
                                       name={"name"}
                                       className={"form-control"}/>
                            </div>
                            <div className="form-group">
                                <input placeholder={"e-mailaka"} defaultValue={user && user.contact} type="text"
                                       name={"email"} ref={register({required: true})}
                                       className={"form-control"}/>
                            </div>
                            <div className="form-group">
                                <input placeholder={"teny miafina"}
                                       type="text" ref={register({required: true})}
                                       name={"password"}
                                       className={"form-control"}/>
                            </div>
                            <div className="form-group">
                                <input className={"btn btn-primary d-block w-100"} type="submit" value={"Hanova"}/>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    )
};

export default EditProfile;
