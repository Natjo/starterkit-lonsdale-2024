import formValidate from '../modules/formValidate.js?v=3516651';
export default (el => {
  const form = el.querySelector('form');
  if (form) {
    new formValidate(form, () => {
      form.submit();
    });
  }
});