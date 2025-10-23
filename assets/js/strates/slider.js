import Slider from "../modules/slider";

export default (el) => {
    console.log("r");
    const slider = el.querySelector(".slider");
    const myslider = new Slider(slider);
    myslider.add();
};
