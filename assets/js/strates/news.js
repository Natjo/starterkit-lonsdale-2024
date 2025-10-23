import Slider from '../modules/slider';

export default (el) => {
    const slider = el.querySelector(".slider");
    const myslider = new Slider(slider);
    myslider.enable();
};
