import formValidate from '../modules/formValidate.js.js?v=75142';
import { ParamsData } from '../app.js.js?v=75142';
export default el => {
  const form = el.querySelector('form');
  const status_msg = el.querySelector('.msg');
  const url = ParamsData.ajax_url;
  if (form) {
    new formValidate(form, () => {
      const formData = new FormData(form);
      formData.append('nonce', form.dataset.nonce);
      formData.append('action', form.getAttribute('action'));
      const xhr = new XMLHttpRequest();
      xhr.open('POST', url, true);
      xhr.onload = () => {
        const response = JSON.parse(xhr.response);
        form.reset();
        status_msg.innerHTML = response.msg;
        status_msg.classList.add('show');
        status_msg.classList.add('valid');
        status_msg.scrollIntoView();
      };
      xhr.send(formData);
    });
  }
};