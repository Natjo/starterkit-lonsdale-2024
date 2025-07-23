import formValidate from '../modules/formValidate.js?v=637531';
export default el => {
  const form = el.querySelector('form');
  if (form) {
    new formValidate(form, () => {
      form.submit();
    });
  }
};