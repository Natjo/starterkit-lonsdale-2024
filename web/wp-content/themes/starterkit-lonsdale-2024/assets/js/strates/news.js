import Slider from '../modules/slider.js?v=3210479';
export default (el => {
  const slider = el.querySelector(".slider");
  const myslider = new Slider(slider);
  myslider.enable();
});