import React from 'react';
import Navigation from "./Navigation";

export default function Base() {
    return (
        <div>
            <Navigation />
            <main>
            </main>
        </div>

        // <div className="main-wrapper">
        //     <div className="main-sidebar">
        //         <Navigation />
        //     </div>
        //     <div className="page-content">
        //         <Home />
        //         {/*<Link to="/">Home</Link>*/}
        //     </div>
        // </div>
    );
}