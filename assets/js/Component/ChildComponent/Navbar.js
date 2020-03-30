import {Dropdown, Nav, Navbar} from "react-bootstrap";
import {Route, Switch} from "react-router";
import React, {useState} from "react";
import notFoundPage from "../Error/404";
import Login from "../Security/Login";
import Logout from "../Security/Logout";
import Actualite from "../Actualite/Actualite";
import VirusStatus from "../VirusStatus/VirusStatus";
import Inscription from "../Security/Inscription";
import NavDropdown from "react-bootstrap/NavDropdown";
import Axios from "axios";
import Badge from "react-bootstrap/Badge";
import Profile from "../User/Profile";
import InfiniteScroll from "react-infinite-scroll-component";
import HiaraDalana from "../HiaraDalana/Hiaradalana";
import EditProfile from "../User/EditProfile";

let limit = 0;
let page = 0;

/**
 * Navbar
 *
 * @returns {*}
 *
 * @constructor
 */
export default function NavigationBar() {
    const [userData, setUserData] = useState();
    let user = JSON.parse(localStorage.getItem('user'));
    let isLogged = false;
    const [notifs, setNotifs] = useState([]);
    const [notifsCount, setNotifsCount] = useState([]);
    let intervalId = 0;

    if (user) {
        isLogged = true;
        if (!userData) {
            setUserData(user);
        }
    }

    intervalId = setInterval(() => {
            clearInterval(intervalId);
            getNotificationsCount().then();
        },
        10000
    );

    async function getNotificationsCount() {
        if (userData && userData.id) {
            await Axios.post('/api/user/notifications/count/' + userData.id).then(res => {
                if (res.data.notifs !== notifsCount) {
                    setNotifsCount(res.data.notifs);
                }
            })
        }
    }

    const getAllNotifs = () => {
        if (userData && (userData.id !== undefined)) {
            let form = new FormData();
            form.append('limit',limit);
            form.append('page',page);

            Axios.post('/api/user/notifications/all/' + userData.id,form).then(res => {
                console.log(res);
                setNotifs(res.data.notifs)
            })
        }
    };

    const fetchData = () => {
        limit = limit + 10;
        page = page + 1;

        getAllNotifs();
    };

    const showAllNotifications = () => {
        if (userData && userData.id) {
            Axios.post('/api/user/viewAll/notifications/' + userData.id).then();
        }
    };

    return (
        <div className={"container-fluid"}>
            <Navbar className="fixed-top" collapseOnSelect expand="lg" bg="primary"
                    variant="dark">
                <div className={"container"}>
                    <Navbar.Brand href="/">Signaleo</Navbar.Brand>
                    {
                        isLogged ? (
                                <div>
                                    <Navbar.Toggle aria-controls="responsive-navbar-nav"/>
                                    <Navbar.Collapse id="responsive-navbar-nav">
                                        <Nav className="ml-auto">
                                            <Nav.Link href="/"><i className="fas fa-stream"/> Tranga </Nav.Link>
                                            <Nav.Link href="/virus"><i className="fas fa-bug"/> COVID-19 </Nav.Link>
                                            <Nav.Link href="/hiaradalana"><i className="fas fa-car"/> Hiara-dalana</Nav.Link>
                                            <Nav.Link href="#">
                                                <Dropdown onToggle={() => {
                                                    showAllNotifications();
                                                    getAllNotifs();
                                                }}>
                                                    <Dropdown.Toggle
                                                        className={"notifDrop"} variant="default"
                                                        id="dropdown-basic">
                                                        <i className="fas fa-bell"/> Fanairana
                                                        <Badge variant="danger">
                                                            {notifsCount > 0 ? notifsCount : ''}
                                                        </Badge>
                                                    </Dropdown.Toggle>
                                                    {
                                                        notifs.length > 0 ? (
                                                            <Dropdown.Menu alignRight style={{padding:"0px"}}>
                                                                <div className="card">
                                                                    <div className="card-body" style={{padding:"0px"}}>
                                                                        <ul className={"list-group"}>
                                                                            <div id={"list-notifs"} className={"scrollbar"} style={{height:"500px",overflowX:"auto"}}>
                                                                                <InfiniteScroll
                                                                                    dataLength={notifs.length}
                                                                                    next={fetchData}
                                                                                    hasMore={true}
                                                                                    loader={<h6 className={"text-center"}>Mahandrasa kely azafady...</h6>}
                                                                                    scrollableTarget="list-notifs"
                                                                                    endMessage={
                                                                                        <p style={{textAlign: 'center'}}>
                                                                                            <b>Yay! You have seen it all</b>
                                                                                        </p>
                                                                                    }>
                                                                                    {
                                                                                        notifs.map(item => {
                                                                                            return (
                                                                                                <li className={"list-group-item"}>
                                                                                                    <div className="d-flex">
                                                                                                        <div
                                                                                                            className={"notif-icon"}>
                                                                                                            <i className={"fas fa-bell"}
                                                                                                               style={{
                                                                                                                   width: "40px",
                                                                                                                   height: "40px",
                                                                                                                   borderRadius: "50%"
                                                                                                               }}/>
                                                                                                        </div>
                                                                                                        <span className={"ml-1"}
                                                                                                              style={{fontSize: "13px"}}> {item.title}</span>
                                                                                                    </div>
                                                                                                </li>
                                                                                            )
                                                                                        })
                                                                                    }
                                                                                </InfiniteScroll>
                                                                            </div>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </Dropdown.Menu>
                                                        ) : ''
                                                    }
                                                </Dropdown>
                                            </Nav.Link>
                                            <NavDropdown
                                                title={user.name ? user.name : 'Signaleo'}
                                                id="collasible-nav-dropdown">
                                                <NavDropdown.Item href="/userprofile">
                                                    <div className="d-flex">
                                                        <div className={"nav-drop-icon"}>
                                                            <i className="fas fa-user"/>
                                                        </div>
                                                        <span>{user.name ? user.name : 'Signaleo'}</span>
                                                    </div>
                                                </NavDropdown.Item>
                                                <NavDropdown.Divider/>
                                                <NavDropdown.Item href="/logout">
                                                    <div className="d-flex">
                                                        <div className={"nav-drop-icon"}>
                                                            <i className="fas fa-sign-out-alt"/>
                                                        </div>
                                                        <span>Hiala</span>
                                                    </div>
                                                </NavDropdown.Item>
                                            </NavDropdown>
                                        </Nav>
                                    </Navbar.Collapse>
                                </div>
                            ) :
                            <>
                                <Navbar.Toggle aria-controls="responsive-navbar-nav"/>
                                <Navbar.Collapse id="responsive-navbar-nav">
                                    <Nav className="ml-auto">
                                        <Nav.Link className={"inscription"} href="/inscription">Hisoratra
                                            anarana</Nav.Link>
                                    </Nav>
                                </Navbar.Collapse>
                            </>
                    }
                </div>
            </Navbar>
            <div className={"mt-5"}/>
            <Switch>
                <Route exact path={"/"} component={Actualite}/>
                <Route exact path={"/inscription"} component={Inscription}/>
                <Route exact path={"/logout"} component={Logout}/>
                <Route exact path={"/login"} component={Login}/>
                <Route exact path={"/actualite"} component={Actualite}/>
                <Route exact path={"/hiaradalana"} component={HiaraDalana}/>
                <Route exact path={"/userprofile"} component={Profile}/>
                <Route exact path={"/virus"} component={VirusStatus}/>
                <Route exact path={"/edit-profile"} component={EditProfile}/>
                <Route exact path={"*"} component={notFoundPage}/>
            </Switch>
        </div>
    )
}
