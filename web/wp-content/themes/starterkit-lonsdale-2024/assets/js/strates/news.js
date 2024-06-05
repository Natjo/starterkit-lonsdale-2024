import Slider from '../modules/slider.js?v=53143843';
export default (el => {
  const slider = el.querySelector(".slider");
  const myslider = new Slider(slider);
  myslider.enable();
});