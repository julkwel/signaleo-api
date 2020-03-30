import React, {useEffect, useState} from 'react';
import {Alert, Button, Col, Form} from "react-bootstrap";
import {useForm} from "react-hook-form";
import axios from "axios";
import {Redirect} from "react-router";

/**
 * @returns {*}
 *
 * @constructor
 */
export default function Login() {
    const {register, handleSubmit} = useForm();
    const [token, setToken] = useState('');
    const [loginStatus, setLoginStatus] = useState({
        isLoggedIn: false,
        flash: 'error',
        isShow: false,
    });

    /**
     * @param data
     */
    const onSubmit = (data) => {
        axios.post('/login/api', data).then(res => {
            setTimeout(() => {
                if (res.data.status === 'success') {
                    localStorage.setItem('user', JSON.stringify(res.data.user));

                    setLoginStatus({
                        isLoggedIn: true,
                        flash: 'success',
                        isShow: true,
                    });

                    location.reload();
                } else {
                    setLoginStatus({
                        isLoggedIn: false,
                        flash: 'danger',
                        isShow: true,
                    })
                }
            }, 300);
        }).catch((res) => {
            setLoginStatus({
                isLoggedIn: false,
                flash: 'danger',
                isShow: true,
            })
        });
    };

    /**
     * get form tokens
     */
    useEffect(() => {
        axios.get('/token/generate').then(res => {
            setToken(res.data.token);
        })
    }, []);

    return (
        <div>
            {
                loginStatus.isLoggedIn ?
                    <Redirect to={"/actualite"}/> :
                    <div className={"top-mobile"} style={{marginTop: "15%"}}>
                        <div className={"col-md-3 m-auto"}>
                            <div className="card">
                                <div className="card-header">
                                    <h1 className={"text-center"}>Signaleo</h1>
                                </div>
                                <div className="card-body">
                                    <Form onSubmit={handleSubmit(onSubmit)} className={"text-center"}>
                                        <div>
                                            {
                                                loginStatus.isShow ? (
                                                    <Alert variant={loginStatus.flash}
                                                           onClose={() => setLoginStatus({
                                                               variant: 'danger',
                                                               isShow: false
                                                           })}
                                                           dismissible>
                                                        {loginStatus.flash === 'success' ? 'Tongasoa' : 'Diso ny mailaka na ny teny miafina'}
                                                    </Alert>
                                                ) : ''
                                            }
                                            <div className="form-group">
                                                <Form.Control name={"email"} type={"email"}
                                                              ref={register({required: true})}
                                                              placeholder="Email"/>
                                            </div>
                                            <div className="form-group">
                                                <Form.Control name={"password"} type={"password"} ref={register}
                                                              placeholder="Password"/>
                                            </div>
                                            <Form.Control name={"_csrf_token"} defaultValue={token} className={"d-none"}
                                                          ref={register}/>
                                            <Button type={"submit"} className={"d-block w-100"}
                                                    variant="primary">Login</Button>
                                        </div>
                                    </Form>
                                </div>
                                <div className="card-footer text-center">
                                    <span>OPENSOURCE</span>
                                </div>
                            </div>
                        </div>
                    </div>
            }
        </div>
    )
}
