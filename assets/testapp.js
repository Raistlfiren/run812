import './styles/app.scss';
import './js/rSlider';
import {dom, library} from '@fortawesome/fontawesome-svg-core';
import {faStar as faStarSolid} from '@fortawesome/free-solid-svg-icons';
import {faStar as faStarRegular} from '@fortawesome/free-regular-svg-icons';

library.add(
    faStarRegular,
    faStarSolid
);

dom.watch();

var slider3 = new rSlider({
    target: '#distanceSearch',
    values: {min: 0, max: 25},
    step: 2,
    range: true,
    set: [0, 25],
    scale: true,
    labels: false
});
