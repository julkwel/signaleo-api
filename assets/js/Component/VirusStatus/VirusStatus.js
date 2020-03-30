import React, {useEffect, useState} from "react";
import {useHistory} from "react-router";
import Card from "react-bootstrap/Card";
import Spinner from "react-bootstrap/Spinner";
import Table from "react-bootstrap/Table";

const VirusStatus = () => {
    const history = useHistory();
    let userConnected = JSON.parse(localStorage.getItem('user'));
    if (!userConnected) {
        history.push('/login');
    }

    const [status, setStatus] = useState();
    const [load, setLoad] = useState(true);
    const [worldStatus, setWorldStatus] = useState([]);

    const getStatus = () => {
        fetch("https://coronavirus-monitor.p.rapidapi.com/coronavirus/latest_stat_by_country.php?country=madagascar", {
            "method": "GET",
            "headers": {
                "x-rapidapi-host": "coronavirus-monitor.p.rapidapi.com",
                "x-rapidapi-key": "6971f295e2mshbfe21de8eb8daebp1caec1jsn035d0fc36e9c"
            }
        })
            .then(response => {
                response.json().then(body => {
                    setStatus(body);

                    getWorldStatus()
                });
            })
            .catch(err => {
                console.log(err);
            });
    };

    const getWorldStatus = () => {
        fetch("https://coronavirus-monitor.p.rapidapi.com/coronavirus/worldstat.php", {
            "method": "GET",
            "headers": {
                "x-rapidapi-host": "coronavirus-monitor.p.rapidapi.com",
                "x-rapidapi-key": "6971f295e2mshbfe21de8eb8daebp1caec1jsn035d0fc36e9c"
            }
        })
            .then(response => {
                console.log(response);
                response.json().then(body => {
                    setWorldStatus([body]);

                    setLoad(false)
                });
            })
            .catch(err => {
                console.log(err);
            });
    };

    useEffect(() => {
        getStatus();
    }, []);

    return (
        <div style={{marginTop: "65px"}}>
            {
                load ? <Spinner className={"m-auto d-block"} animation="grow"/> :
                    (
                        <div className="row">
                            <div className={"col-md-4 m-auto"}>
                                {
                                    worldStatus ? worldStatus.map((res, key) => {
                                        return (
                                            <Card key={key}>
                                                <Card.Header className={"text-center"}> Manerantany
                                                    - {res.statistic_taken_at}  </Card.Header>
                                                <Card.Body>
                                                    <Table striped bordered hover>
                                                        <tbody>
                                                        <tr>
                                                            <td>Isan'ny voa</td>
                                                            <td className={"text-center"}>{res.total_cases}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Olona maty</td>
                                                            <td className={"text-center"}>{res.total_deaths ? res.total_deaths : 0}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Tranga vaovao</td>
                                                            <td className={"text-center"}>{res.new_cases ? res.new_cases : 0}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Olona Avotra</td>
                                                            <td className={"text-center"}>{res.total_recovered}</td>
                                                        </tr>
                                                        </tbody>
                                                    </Table>
                                                </Card.Body>
                                            </Card>
                                        )
                                    }) : ''
                                }
                            </div>
                            <div className={"col-md-4 m-auto"}>
                                {
                                    status && status.latest_stat_by_country &&
                                    status.latest_stat_by_country.length !== 0 ?
                                        status.latest_stat_by_country.map((res, key) => {
                                            return (
                                                <Card key={key}>
                                                    <Card.Header className={"text-center"}> Madagascar
                                                        - {res.record_date}  </Card.Header>
                                                    <Card.Body>
                                                        <Table striped bordered hover>
                                                            <tbody>
                                                            <tr>
                                                                <td scope="row">Firenena</td>
                                                                <td className={"text-center"}>Madagascar - #ID{res.id}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Isan'ny voa</td>
                                                                <td className={"text-center"}>{res.total_cases}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Olona maty</td>
                                                                <td className={"text-center"}>{res.total_deaths ? res.total_deaths : 0}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Tranga vaovao</td>
                                                                <td className={"text-center"}>{res.new_cases ? res.new_cases : 0}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Olona voa ankehitriny</td>
                                                                <td className={"text-center"}>{res.active_cases}</td>
                                                            </tr>
                                                            </tbody>
                                                        </Table>
                                                    </Card.Body>
                                                </Card>
                                            )
                                        })
                                        :
                                        ''
                                }
                            </div>
                        </div>
                    )
            }
        </div>
    )
};

export default VirusStatus;
