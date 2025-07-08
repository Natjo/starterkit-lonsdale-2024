import Slider from '../modules/slider.js?v=6215024';
export default el => {
  const slider = el.querySelector(".slider");
  const myslider = new Slider(slider);
  myslider.add();
};