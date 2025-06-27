import formValidate from '../modules/formValidate.js?v=55145945';
export default el => {
  const form = el.querySelector('form');
  if (form) {
    new formValidate(form, () => {
      form.submit();
    });
  }
};