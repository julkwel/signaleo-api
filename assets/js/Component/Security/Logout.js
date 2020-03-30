import React from "react";
import { useHistory} from "react-router";

export default function Logout() {
    const history = useHistory();

    localStorage.removeItem('user');
    history.push('/login');

    location.reload();
}
