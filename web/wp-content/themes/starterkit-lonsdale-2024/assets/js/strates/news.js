import Slider from '../modules/slider.js?v=6615386';
export default el => {
  const slider = el.querySelector(".slider");
  const myslider = new Slider(slider);
  myslider.enable();
};