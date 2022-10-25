import React from 'react';
import {Nav} from "react-bootstrap";

export default function Sidebar() {
    return (
        <Nav className="direction-sidebar">
            <div className="container bg-dark">
                {/*<div className="card-header">*/}
                {/*    <h2>Garvin route</h2>*/}
                {/*</div>*/}
                {/*<div className="card-body">*/}
                {/*    <h4>Distance?</h4>*/}
                {/*    <a href="#" className="btn btn-warning btn-sm mb-2">View 4 Mile Route</a>*/}
                {/*    <a href="#" className="btn btn-warning btn-sm mb-2">View 6 Mile Route</a>*/}
                {/*    <a href="#" className="btn btn-warning btn-sm mb-2">View 8 Mile Route</a>*/}
                {/*    <a href="#" className="btn btn-warning btn-sm mb-2">View 10 Mile Route</a>*/}
                {/*</div>*/}


                <div className="card-body">
                    <h4>Directions</h4>
                    <a href="#" className="btn btn-info btn-sm mb-2">Print Directions</a>
                    <ul className="list-unstyled components">
                        <li>Leaving the Y</li>
                        <li>R on 6th</li>
                        <li>R on Sycamore</li>
                    </ul>
                </div>
            </div>

        </Nav>
    );
}