import Slider from '../modules/slider.js?v=42162157';
export default (el => {
  const slider = el.querySelector(".slider");
  const myslider = new Slider(slider);
  myslider.enable();
});