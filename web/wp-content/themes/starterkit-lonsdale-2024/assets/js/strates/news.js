import Slider from '../modules/slider.js.js?v=751323';
export default el => {
  const slider = el.querySelector(".slider");
  const myslider = new Slider(slider);
  myslider.enable();
};