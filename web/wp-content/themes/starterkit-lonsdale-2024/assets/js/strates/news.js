import Slider from '../modules/slider.js.js?v=75142';
export default el => {
  const slider = el.querySelector(".slider");
  const myslider = new Slider(slider);
  myslider.enable();
};