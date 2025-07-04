import Slider from '../modules/slider.js?v=64151215';
export default el => {
  const slider = el.querySelector(".slider");
  const myslider = new Slider(slider);
  myslider.add();
};