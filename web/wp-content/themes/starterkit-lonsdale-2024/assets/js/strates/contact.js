import formValidate from '../modules/formValidate.js?v=6003922';
export default el => {
  const form = el.querySelector('form');
  if (form) {
    new formValidate(form, () => {
      form.submit();
    });
  }
};