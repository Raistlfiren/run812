import React from 'react';
// import {Home, Eye, BookOpen, Code, Menu} from "react-feather";
import {FontAwesomeIcon} from '@fortawesome/react-fontawesome';
import {faCode, faHome, faRoute} from '@fortawesome/free-solid-svg-icons';

export default function Navigation() {
    return (
        <nav className="navbar navbar-expand-md navbar-dark bg-black mb-4">
            <div className="container">
                <a className="navbar-brand" href="#">812 Running</a>
                <button className="navbar-toggler" type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#navbarCollapse"
                        aria-controls="navbarCollapse" aria-expanded="false"
                        aria-label="Toggle navigation">
                    <span className="navbar-toggler-icon"></span>
                </button>
                <div className="collapse navbar-collapse" id="navbarCollapse">
                    <ul className="navbar-nav nav-pills me-auto mb-2 mb-md-0">
                        <li className="nav-item">
                            <a className="nav-link active" aria-current="page"
                               href="#"><FontAwesomeIcon icon={faHome} /> Home</a>
                        </li>
                        <li className="nav-item">
                            <a className="nav-link" href="#"><FontAwesomeIcon icon={faRoute} /> Routes</a>
                        </li>
                        <li className="nav-item">
                            <a className="nav-link" href="https://github.com/Raistlfiren/run812" target="_blank"
                               aria-disabled="true"><FontAwesomeIcon icon={faCode} /> Code</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    );
}