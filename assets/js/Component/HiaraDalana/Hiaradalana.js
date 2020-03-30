import React, {useEffect, useState} from "react";
import Carousel from "react-bootstrap/Carousel";
import AsyncCreatableSelect from "react-select/async-creatable/dist/react-select.esm";
import Axios from "axios";
import {useForm} from "react-hook-form";
import DateTimePicker from "react-datetime-picker";
import {useHistory} from "react-router";
import InfiniteScroll from "react-infinite-scroll-component";
import coVoiturage from '../../Assets/co-voiturage.jpg';
import moto from '../../Assets/moto.jpg';
import Badge from "react-bootstrap/Badge";

let limit = 0;
let page = 0;

const HiaraDalana = () => {
    const history = useHistory();
    const [depart, setDepart] = useState('');
    const [arrive, setArrive] = useState('');
    const {register, handleSubmit} = useForm();
    const [dateDepart, setDateDepart] = useState(new Date());
    const [demandes, setDemandes] = useState([]);
    const [user, setUser] = useState('');

    let userParse = JSON.parse(localStorage.getItem('user'));

    if ((userParse && userParse.id) && !user) {
        setUser(userParse.id);
    }

    if (!userParse) {
        history.push('/login');
        location.reload();
    }

    useEffect(() => {
        getData();
    }, []);

    const promiseOptions = (inputValue) => Axios.post('/api/actualite/fokontany/find', {search: inputValue}).then(res => {
        return res.data.data;
    });

    const getData = () => {
        let form = new FormData();
        form.append('limit', limit);
        form.append('page', page);
        form.append('web', true);

        Axios.post('/api/zambaento/list', form).then(res => {
            if (res.data.data && res.data.data.length !== 0) {
                setDemandes(res.data.data)
            }
        })
    };

    const fetchData = () => {
        limit = limit + 10;
        page = page + 1;

        getData();
    };

    const onSubmit = (data) => {
        data.dateDepart = dateDepart;
        data.depart = depart;
        data.arrive = arrive;
        data.userId = user;

        Axios.post('/api/zambaento/manage', data).then(res => getData());
        document.getElementById('form-hiara-dalana').reset();
    };

    let demandeDatas = [];

    demandes.map((item, key) => {
        demandeDatas.push(
            <li key={key} className={"list-group-item"}>
                <div className="d-flex align-items-center">
                    <div>
                        <img width={"80"} height={"80"}
                             style={{
                                 border: "solid 2px #177bfe",
                                 borderRadius: "50%"
                             }}
                             src={item.preference === 'Moto' ? moto : coVoiturage}
                             alt=""/>
                    </div>
                    <div className={"ml-2"}>
                        <h6>{item.user.name ? item.user.name : 'Signaleo'} | {item.contact ? item.contact : 'Signaleo'}</h6>
                        <span><Badge
                            variant="primary">{item.depart}</Badge> - <Badge
                            variant="primary">{item.arrive}</Badge></span><br/>
                        <span>
                            <Badge variant="primary">Préference : {item.preference ? item.preference : 'Fiara'}</Badge> |
                            <Badge variant={"primary"}>Isa : {item.nombre ? item.nombre : '1'}</Badge>
                        </span><br/>
                        <span>
                            <Badge variant={"warning"}>Fiaingana : {item.dateDepart}</Badge>
                        </span><br/>
                        <span>
                            <Badge variant={"info"}>{item.lieurecup ? item.lieurecup : 'Tanà'}</Badge>
                        </span><br/>
                    </div>
                </div>
            </li>
        )
    });

    return (
        <div className={""} style={{marginTop: "10%", position: "relative"}}>
            <div className="row">
                <div className={"col-md-4 m-auto"}>
                    <div className={"card"}>
                        <div className="card-body">
                            <form id={"form-hiara-dalana"} onSubmit={handleSubmit(onSubmit)}>
                                <div className="form-group">
                                    <select name="transport"
                                            ref={register({required: true})} className={"form-control"}
                                            id="">
                                        <option value="Fiara">Fiara</option>
                                        <option value="Moto">Moto</option>
                                    </select>
                                </div>
                                <div className="form-group">
                                    <AsyncCreatableSelect
                                        placeholder={"Toerana hiangana"}
                                        required
                                        name={"depart"}
                                        styles={{
                                            menu: provided => ({...provided, zIndex: 9999, borderRadius: 0})
                                        }}
                                        className={"ion-select-custom"}
                                        cacheOptions
                                        defaultOptions
                                        onChange={(e) => {
                                            setDepart(e.value)
                                        }}
                                        loadOptions={promiseOptions}
                                    />
                                </div>
                                <div className="form-group">
                                    <AsyncCreatableSelect
                                        placeholder={"Toerana aleha"}
                                        required
                                        name={"arrive"}
                                        styles={{
                                            menu: provided => ({...provided, zIndex: 9999, borderRadius: 0})
                                        }}
                                        className={"ion-select-custom"}
                                        cacheOptions
                                        defaultOptions
                                        onChange={(e) => {
                                            setArrive(e.value)
                                        }}
                                        loadOptions={promiseOptions}
                                    />
                                </div>
                                <div className="form-group">
                                    <input type="text" required
                                           name={"contact"}
                                           ref={register({required: true})}
                                           className={"form-control"}
                                           placeholder={"Laharan'ny finday ... "}/>
                                </div>
                                <div className="form-group">
                                    <input type="text" required
                                           name={"lieurecup"}
                                           ref={register({required: true})}
                                           className={"form-control"}
                                           placeholder={"Toerana hangalana anao ..."}/>
                                </div>
                                <div className="form-group">
                                    <input type="text"
                                           ref={register({required: true})}
                                           name={"nombre"}
                                           className={"form-control"}
                                           placeholder={"Isan'ny olona handeha ..."}/>
                                </div>
                                <div className="form-group">
                                    <DateTimePicker
                                        className={"form-control"}
                                        onChange={(e) => setDateDepart(e)}
                                        value={dateDepart}
                                    />
                                </div>
                                <div>
                                    <input type="submit" className={"btn btn-primary d-block w-100"} value={"Handefa"}/>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div className={"col-md-4 m-auto"}>
                    <div className={"card"}>
                        <div className="card-body">
                            <div style={{height: "60vh", overflowY: "auto"}} className={"scrollbar"}
                                 id={"list-demande"}>
                                <ul className={"list-group"}>
                                    <InfiniteScroll
                                        dataLength={demandes.length}
                                        next={fetchData}
                                        hasMore={true}
                                        loader={<h6 className={"text-center"}>Mahandrasa kely azafady ...</h6>}
                                        scrollableTarget="list-demande"
                                        endMessage={
                                            <p style={{textAlign: 'center'}}>
                                                <b>Yay! You have seen it all</b>
                                            </p>
                                        }>
                                        {demandeDatas}
                                    </InfiniteScroll>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    )
};

export default HiaraDalana;
