import React from "react";
import {useForm} from "react-hook-form";
import Axios from "axios";
import {useHistory} from "react-router";

const Inscription = () => {
    const {register, handleSubmit} = useForm();
    const history = useHistory();

    const onSubmit = (data) => {
        console.log(data);
        Axios.post('/add/user/api', data).then((res) => {
            if ('success' === res.data.message) {
                history.push('/login');
            } else {
                alert('Misy olana ny signaleo')
            }
        })
    };

    return (
        <div className={"top-mobile p-relative"} style={{marginTop: "15%"}}>
            <div className={"col-md-4 m-auto"}>
                <div className="card">
                    <div className="card-header">
                        <h1 className={"text-center"}>Signaleo</h1>
                    </div>
                    <div className="card-body">
                        <form action="" onSubmit={handleSubmit(onSubmit)}>
                            <div className={"form-group"}>
                                <select placeholder={"Lahy/Vavy"} className={"form-control"} name={"gender"}
                                        ref={register({required: true})} id="">
                                    <option value="Lahy">Lahy</option>
                                    <option value="Vahy">Vavy</option>
                                </select>
                            </div>
                            <div className={"form-group"}>
                                <input placeholder={"Anarana"} className={"form-control"} name={"name"} type={"text"}
                                       ref={register({required: true})}/>
                            </div>
                            <div className={"form-group"}>
                                <input placeholder={"Email"} className={"form-control"} name={"email"} type={"email"}
                                       ref={register({required: true})}/>
                            </div>
                            <div className={"form-group"}>
                                <input placeholder={"Teny miafina"} className={"form-control"} name={"password"} type={"text"}
                                       ref={register({required: true})}/>
                            </div>

                            <input type="submit" className={"btn d-block w-100 btn-primary"} value={"Hisoratra anarana"}/>
                        </form>
                    </div>
                    <div className="card-footer text-center">
                        <span>OPENSOURCE</span>
                    </div>
                </div>
            </div>
        </div>
    )
}

export default Inscription;
