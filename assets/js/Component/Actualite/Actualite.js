import React, {useEffect, useState} from "react";
import Axios from "axios";
import {useHistory} from "react-router";
import male from '../../Assets/male.png'
import female from '../../Assets/female.svg'

import shell from '../../Assets/logo/shell.png'
import jovenna from '../../Assets/logo/jovenna.jpeg'
import total from '../../Assets/logo/total.png'
import galana from '../../Assets/logo/galana.jpg'

import Card from "react-bootstrap/Card";
import Button from "react-bootstrap/Button";
import Accordion from "react-bootstrap/Accordion";
import '@fortawesome/fontawesome-free/css/fontawesome.min.css';
import Media from "react-bootstrap/Media"
import Form from 'react-bootstrap/Form'
import {useForm} from "react-hook-form";
import AsyncCreatableSelect from "react-select/async-creatable";
import InfiniteScroll from 'react-infinite-scroll-component';
import Badge from "react-bootstrap/Badge";
import {confirmAlert} from 'react-confirm-alert'; // Import
import 'react-confirm-alert/src/react-confirm-alert.css'; // Import css

let limit = 0;
let page = 0;

const Actualite = () => {
    const history = useHistory();
    const {register, handleSubmit} = useForm();
    let userConnected = JSON.parse(localStorage.getItem('user'));

    if (!userConnected) {
        history.push('/login');
    }

    const [actualite, setActualite] = useState([]);
    const [regions, setRegions] = useState([]);
    const [stations, setStations] = useState([]);
    const [nums, setNums] = useState([]);

    const [search, setSearch] = useState('');
    const [user, setUser] = useState('');
    const [lieu, setLieu] = useState('');
    const [hashMore, setHashMore] = useState(true);

    useEffect(() => {
        getData();
        searchStation();
        getNum();
        getAllRegion();
    }, []);

    const getData = () => {
        let userParse = JSON.parse(localStorage.getItem('user'));

        if (userParse && userParse.id && !user) {
            setUser(userParse.id);
        }

        if (!userParse) {
            history.push('/login');

            location.reload();
        }

        let form = new FormData();
        form.append('limit', limit);
        form.append('web', true);
        form.append('user', userParse ? (userParse.id ? userParse.id : '') : '');
        form.append('search', search);

        Axios.post('/api/actualite/list', form).then((res) => {
            console.log(res.data.data.length);
            if (res.data.data === 'nouser') {
                history.push('/login');
                location.reload();
            } else {
                setActualite(res.data.data);
            }
        });
    };

    const searchStation = (needle = '', limit = 10) => {
        let region = document.getElementById('region-station').value;
        Axios.post('/api/station/search/station', {
            search: needle,
            limit: 30,
            region: region === '' ? 'Analamanga' : region,
        }).then((res) => {
            setStations(res.data.data)
        }).catch(err => {
            console.log(err)
        })
    };

    const addVote = (uri, value) => {
        if (user) {
            Axios.post(uri, {vote: value, user: user}).then(getData)
        }
    };

    const getNum = () => {
        Axios.post('/api/numero/list').then((res) => {
            setNums(res.data.data);
        })
    };

    const getAllRegion = () => {
        Axios.post('/api/station/list/region').then((res) => {
            setRegions(res.data.data);
        })
    };

    function ValidateSize(file) {
        let FileSize = file.target.files[0].size / 1024 / 1024; // in MB

        if (FileSize > 2) {
            alert('Mavesatra loatra ny sary alefanao ! ');

            file.target.value = '';
        } else {

        }
    }

    if (document && document.querySelector('.custom-file-input')) {
        document.querySelector('.custom-file-input').addEventListener('change', function (e) {
            let fileName = document.getElementById("file-input").files[0].name;
            let nextSibling = e.target.nextElementSibling
            nextSibling.innerText = fileName.substring(fileName.length - 15, fileName.length);
        })
    }

    const onSubmit = (data) => {
        if (!user) {
            history.push('/login');

            location.reload();
        }

        let form = new FormData();
        form.append('lieu', lieu);
        form.append('cause', data.cause);
        form.append('userId', user);
        form.append('message', data.message);
        form.append('image', data.photo[0]);

        Axios.post('/api/actualite/manage', form).then(res => {
            if (res.status === 200) {
                getData();
            } else {
                alert('Misy olana ny signaleo')
            }
        })
    };

    const fetchData = () => {
        limit = limit + 10;
        page = page + 1;

        getData();
    };

    const promiseOptions = (inputValue) => Axios.post('/api/actualite/fokontany/find', {search: inputValue}).then(res => {
        return res.data.data;
    });

    const comment = (comment, actu) => {
        let form = new FormData();
        form.append('user', user);
        form.append('comment', comment);

        Axios.post('/api/comment/actu/add/' + actu, form).then(res => {
            if (res.data.status === "success") {
                getData();
            }
        });
    };

    const replyComment = (message, commentId) => {
        let form = new FormData();
        form.append('user', user);
        form.append('reply', message);

        Axios.post('/api/comment/actu/response/' + commentId, form).then(res => {
            if (res.data.status === "success") {
                getData();
            }
        });
    };

    let items = [];

    function removeActu(actuId, userActu) {
        if (user && user === userActu) {
            confirmAlert({
                title: 'Hamafa',
                message: 'Tena hofafanao tokoa ve ity zavatra nozarainao ity ? .',
                buttons: [
                    {
                        label: 'Eny',
                        onClick: () => Axios.post('/api/actualite/delete/' + actuId + '/' + userActu).then(() => getData())
                    },
                    {
                        label: 'Tsia',
                        onClick: () => {
                            return false;
                        }
                    }
                ]
            });
        }
    }

    actualite.map((item, key) => {
        let comments = [];
        {
            item.comments.map((comment, key) => {
                comments.push(
                    <li className={"list-group-item list-comment"} key={key}>
                        <div className={"d-flex"} style={{alignItems: "center", padding: "5px"}}>
                            <div>
                                <img
                                    src={comment.user.gender === 'Lahy' ? male : female}
                                    height={"30px"} width={"30px"}
                                    style={{borderRadius: "50%", border: "1px solid #177bfe", padding: "2px"}}
                                    alt="User"/>
                            </div>
                            <div className={"ml-2 comment-data"}>
                                <div className={"col-md-12"}>
                                    <div>
                                        <a href="#">{comment.user.name}</a>
                                        <Badge>{comment.date}</Badge>
                                    </div>
                                </div>
                                <div className="col-md-12">
                                    <span style={{wordBreak: "break-all"}}>
                                        {comment.comment}
                                    </span>
                                </div>
                            </div>
                        </div>
                        {
                            comment.responses.length > 0 ?
                                (
                                    <div className={"card"} style={{border: "none", background: "#e0e0e0"}}>
                                        <div className="card-body" style={{padding: "0px"}}>
                                            <ul className={"list-group"}>
                                                {
                                                    comment.responses.map((item, key) => {
                                                        return (
                                                            <li key={key} className={"list-group-item comment-reply"}>
                                                                <div className={"d-flex"}
                                                                     style={{alignItems: "center"}}>
                                                                    <div>
                                                                        <img
                                                                            src={item.user.gender === 'Lahy' ? male : female}
                                                                            height={"30px"} width={"30px"}
                                                                            style={{
                                                                                borderRadius: "50%",
                                                                                border: "1px solid #177bfe",
                                                                                padding: "2px"
                                                                            }}
                                                                            alt="User"/>
                                                                    </div>
                                                                    <div className={"ml-2 comment-data"}>
                                                                        <div className={"col-md-12"}><span><a
                                                                            href="#">{item.user.name}</a> <Badge>{item.date}</Badge></span>
                                                                        </div>
                                                                        <div className="col-md-12">
                                                                            <span style={{wordBreak: "break-all"}}>
                                                                                {item.comment}
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        )
                                                    })
                                                }
                                            </ul>
                                        </div>
                                    </div>
                                ) : ''
                        }
                        <Accordion>
                            <Accordion.Toggle as={Button} variant="link" eventKey="0">
                                Hamaly
                            </Accordion.Toggle>
                            <Accordion.Collapse eventKey="0">
                                <div className="form-group">
                                    <input type={"text"}
                                           placeholder={"Ny hevitrao ..."}
                                           name={"comment"}
                                           onKeyPress={event => {
                                               if (event.key === 'Enter') {
                                                   replyComment(event.target.value, comment.id)

                                                   event.target.value = '';
                                               }
                                           }
                                           }
                                           className="form-control input-comment"/>
                                </div>
                            </Accordion.Collapse>
                        </Accordion>
                    </li>
                )
            })
        }

        items.push(
            <Card key={key} className={"mt-1"}>
                <Card.Header className="Header">
                    <div className="row">
                        <div className="d-flex w-100" style={{marginLeft: "1.75rem"}}>
                            <img
                                src={item.user.gender === 'Lahy' ? male : female}
                                height={"40px"} width={"40px"}
                                style={{borderRadius: "50%", border: "2px solid #177bfe", padding: "2px"}}
                                alt="User"/>
                            <div className={"ml-2 w-100"} style={{alignItems: "center"}}>
                                <div className="col-md-12 w-100 d-flex">
                                    <div className="name-content">
                                        <a href="#">{item.user.name} &nbsp;</a>
                                        ({(item.user.point && item.user.point > 0) ? item.user.point + '+' : 0})
                                    </div>
                                    <a href={"#"} onClick={() => removeActu(item.id, item.user.id)}
                                       className="btn position-absolute" style={{right: "5px"}}>
                                        <i className={"fas fa-ellipsis-v"}/>
                                    </a>
                                </div>
                                <div className="col-md-12">
                                    <span style={{fontSize: "12px"}}> {item.dateAdd}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </Card.Header>
                <Card.Body>
                    <Card.Title>
                        <Badge
                            className={"badge-info-actu"}
                            variant={"info"}>
                            <i className="fas fa-map-marker-alt"/> {item.lieu ? item.lieu : 'Madagascar'}
                        </Badge> |
                        <Badge variant={
                            item.type === 'CoronaVirus' ? "warning" :
                                item.type === 'Accident' ? "danger" :
                                    "info"}
                               className={"badge-info-actu"}
                        >
                            <i className={item.type === 'CoronaVirus' ? "fas fa-bug" : "fas fa-car"}/>
                            {item.type}
                        </Badge>
                    </Card.Title>
                    <Card.Text className={item.photo ? "text" : "text-message"}>
                        {item.message}
                    </Card.Text>
                </Card.Body>
                <Card.Img variant="top" src={item.photo ? item.photo : ''}/>
                <Card.Footer>
                    <div className="row text-center">
                        <div className="col">
                            <Button
                                onClick={() => addVote('/api/actualite/vote/' + item.id, 'marina')}
                                className={(item.vote.user && item.vote.user[0] && item.vote.user[0].type === "marina") ? "d-inline-flex text-primary reaction-text" : "d-inline-flex text-default reaction-text"}
                                variant="outline-default">
                                <i className="far fa-thumbs-up"/>
                                <span className={"d-sm-none"}>Marina</span>
                                <mark>{item.vote.marina}</mark>
                            </Button>
                        </div>
                        <div className="col">
                            <Button
                                onClick={() => addVote('/api/actualite/vote/' + item.id, 'diso')}
                                className={(item.vote.user && item.vote.user[0] && item.vote.user[0].type === "diso") ? "d-inline-flex text-danger reaction-text" : "d-inline-flex text-default reaction-text"}
                                variant="outline-default">
                                <i className={"far fa-thumbs-down"}/>
                                <span className={"d-sm-none"}>Diso</span>
                                <mark>{item.vote.diso}</mark>
                            </Button>
                        </div>
                        <div className="col">
                            <Button
                                onClick={() => addVote('/api/actualite/vote/' + item.id, 'haha')}
                                className={(item.vote.user && item.vote.user[0] && item.vote.user[0].type === "haha") ? "d-inline-flex text-warning reaction-text" : "d-inline-flex text-default reaction-text"}
                                variant="outline-default">
                                <i className="far fa-laugh-squint"/>
                                <span className={"d-sm-none"}>Haha</span>
                                <mark>{item.vote.haha}</mark>
                            </Button>
                        </div>
                    </div>
                    <div className="form-group">
                        <input type={"text"}
                               placeholder={"Ny hevitrao ..."}
                               name={"comment"}
                               onKeyPress={event => {
                                   if (event.key === 'Enter') {
                                       comment(event.target.value, item.id)

                                       event.target.value = '';
                                   }
                               }
                               }
                               className="form-control input-comment"/>
                    </div>
                    <div>
                        {
                            item.comments.length > 2 ? (
                                <div>
                                    <Accordion>
                                        <Accordion.Toggle as={Button} variant="link" eventKey="1">
                                            Hijery ireo hevitr'olona ...
                                        </Accordion.Toggle>
                                        <Accordion.Collapse eventKey="1">
                                            <ul className={"list-group"}>{comments}</ul>
                                        </Accordion.Collapse>
                                    </Accordion>
                                </div>
                            ) : (
                                <ul className={"list-group"}>{comments}</ul>
                            )
                        }
                    </div>
                </Card.Footer>
            </Card>
        )
    });

    return (
        <div className="row" style={{marginTop: "70px"}}>
            <div className="col-md-3 d-sm-none">
                <div className={"fixed scrollbar card"} style={{height: "100vh", overflow: "auto"}}>
                    <div className={"card-body"}>
                        <div className={"form-group"}>
                            <input type="text" className={"form-control"} placeholder={"hitady"} onChange={
                                (e) => {
                                    searchStation(e.target.value);
                                }
                            }/>
                        </div>
                        <div className={"form-group"}>
                            <Form.Control as="select" id={"region-station"} onChange={(e) => {
                                searchStation();
                            }}>
                                {regions.map((item, key) => {
                                    return (
                                        <option key={key} value={item.region}>{item.region}</option>
                                    )
                                })}
                            </Form.Control>
                        </div>
                        <div style={{marginBottom: "100px"}}>
                            <ul className={"list-group"}>
                                {
                                    stations.map((item, key) => {
                                        return (
                                            <Media key={key} as="li" className={"list-group-item"}>
                                                <div className="row">
                                                    <div className="col-12 d-flex">
                                                        <div className="col-4">
                                                            <img
                                                                width={64}
                                                                height={64}
                                                                className="mr-3"
                                                                src={item.distributeur === 'Galana' ? galana :
                                                                    item.distributeur === 'Vivo' ? shell :
                                                                        item.distributeur === 'Jovena' ? jovenna : total}
                                                                alt="Generic placeholder"
                                                            />
                                                        </div>
                                                        <div className="col-8">
                                                            <span>{item.distributeur === 'Vivo' ? 'Shell' : item.distributeur} </span><br/>
                                                            <span>{item.name}</span><br/>
                                                            <span>{item.localite}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </Media>
                                        )
                                    })
                                }
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div id={"actu-container"} className="col-md-6 m-auto scrollbar overflow-y-auto vh-100"
                 style={{paddingBottom: "10%"}}>
                <div className={"actualite-list"} style={{marginBottom: "150px", margin: "auto"}}>
                    <div className={"card"}>
                        <div className={"card-body"}>
                            <form onSubmit={handleSubmit(onSubmit)}>
                                <div className={"form-group"}>
                                    <select placeholder={"Inona no mitranga ?"}
                                            className={"form-control"}
                                            name={"cause"}
                                            ref={register({required: true})} id="">
                                        <option value="CoronaVirus">Corona Virus</option>
                                        <option value="SamyHafa">Tranga Hafa</option>
                                        <option value="Embouteillage">Embouteillage</option>
                                        <option value="Accident">Lozam-pifamohivoizana</option>
                                        <option value="Fanafihana">Fanafihana</option>
                                        <option value="FiaraMaty">Fiara maty</option>
                                        <option value="Malalaka">Malalaka ny lalana</option>
                                    </select>
                                </div>
                                <div className={"form-group"}>
                                    <AsyncCreatableSelect
                                        placeholder={"Toerana"}
                                        required
                                        name={"lieu"}
                                        styles={{
                                            menu: provided => ({...provided, zIndex: 9999, borderRadius: 0})
                                        }}
                                        className={"ion-select-custom"}
                                        cacheOptions
                                        defaultOptions
                                        onChange={(e) => {
                                            setLieu(e.value)
                                        }}
                                        loadOptions={promiseOptions}
                                    />
                                </div>
                                <div className={"form-group"}>
                                    <textarea placeholder={"Tantarao ..."}
                                              className={"form-control"}
                                              name={"message"}
                                              ref={register({required: true})}/>
                                </div>
                                <div className={"form-group"}>
                                    <div className="custom-file" id="customFile" lang="es">
                                        <input type="file"
                                               id="file-input"
                                               title={"Asio sary"}
                                               onChange={(e) => {
                                                   e.persist();
                                                   ValidateSize(e)
                                               }}
                                               accept="image/x-png,image/gif,image/jpeg"
                                               name={"photo"}
                                               ref={register}
                                               className="custom-file-input"
                                               aria-describedby="fileHelp"/>
                                        <label className="custom-file-label" htmlFor="exampleInputFile">
                                            Asio sary ...
                                        </label>
                                    </div>
                                </div>
                                <input type="submit" className={"btn d-block w-100 btn-primary"}
                                       value={"Hizara"}/>

                            </form>
                        </div>
                    </div>
                    <InfiniteScroll
                        dataLength={items.length}
                        next={fetchData}
                        hasMore={true}
                        loader={<h6 className={"text-center"}>Mahandrasa kely azafady ...</h6>}
                        scrollableTarget="actu-container"
                        endMessage={
                            <p style={{textAlign: 'center'}}>
                                <b>Yay! You have seen it all</b>
                            </p>
                        }>
                        {items}
                    </InfiniteScroll>
                </div>
            </div>
            <div className="col-md-3 d-sm-none">
                <div className={"fixed scrollbar"} style={{height: "100vh", overflow: "auto"}}>
                    <div className={"card"}>
                        <div className={"card-body mb-5"}>
                            <ul className={"list-group mb-5"}>
                                {
                                    nums.map((item, key) => {
                                        return (
                                            <li key={key} className={"list-group-item"}>
                                                <span>{item.name}</span> <br/>
                                                <span>{item.type}</span> <br/>
                                                <span>{item.numero}</span>
                                            </li>
                                        )
                                    })
                                }
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    )
};

export default Actualite;
