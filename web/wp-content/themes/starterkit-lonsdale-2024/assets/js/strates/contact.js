import formValidate from '../modules/formValidate.js?v=3210479';
export default (el => {
  const form = el.querySelector('form');
  if (form) {
    new formValidate(form, () => {
      form.submit();
    });
  }
});