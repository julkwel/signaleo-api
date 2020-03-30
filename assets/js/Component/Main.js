import React from 'react';
import ReactDom from 'react-dom';
import {BrowserRouter as Router} from "react-router-dom";
import NavigationBar from "./ChildComponent/Navbar";

/**
 * This is our main entry
 */
ReactDom.render(
    <Router>
        <NavigationBar/>
    </Router>,
    document.getElementById('root')
);
