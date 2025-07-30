/* eslint-disable */

/**
 * formValidate
 * 
 * data-manadatory
 * data-typemismatch
 * data-patternmismatch
 * 
 */

const FormValidate = function (form, onSend) {
    const inputs = form.querySelectorAll(':required');
    let validity = true;
    let init = true;

    this.reset = () => {
        init = true;
        for (let input of inputs) {
            input.removeAttribute('aria-invalid');
            input.parentNode.querySelector('.invalid-msg').remove();
        }
    };

    const validate = () => {
        if (init) return;
        validity = true;
        for (let input of inputs) {
            const group = input.closest('[role="group"]');
            const field = group ? group : input.parentNode;
            const element = group ? group : input;

            const dataMandatory = element.dataset.mandatory;
            const dataTypeMismatch = element.dataset.typemismatch;
            const dataPatternMismatch = input.dataset.patternmismatch;
            const typeMismatch = input.validity.typeMismatch;
            const tooShort = input.validity.tooShort;
            const tooLong = input.validity.tooLong;
            const stepMismatch = input.validity.stepMismatch;
            const patternMismatch = input.validity.patternMismatch;
            const valueMissing = input.validity.valueMissing;

            let invalid_msg = field.querySelector('.invalid-msg');

            if (!invalid_msg) {
                invalid_msg = document.createElement('div');
                invalid_msg.className = 'invalid-msg';
                invalid_msg.id = input.getAttribute('aria-describedby').split(' ')[0];
                field.insertAdjacentElement('beforeend', invalid_msg);
            }

            if (!input.checkValidity()) {
                input.setAttribute('aria-invalid', true);

                let msg = '';

                if (stepMismatch) msg = stepMismatch;

                if (tooShort) msg = tooShort;

                if (tooLong) msg = tooLong;

                if (typeMismatch) msg = dataTypeMismatch ? dataTypeMismatch : typeMismatch;

                if (patternMismatch) msg = dataPatternMismatch ? dataPatternMismatch : patternMismatch;

                if (valueMissing && dataMandatory) msg = dataMandatory;

                group && field.classList.add("error");
                input.setCustomValidity(msg);
                invalid_msg.innerHTML = input.validationMessage;
                validity = false;
            } else {
                input.removeAttribute('aria-invalid');
                invalid_msg.innerHTML = '';
            }
        }
        return validity;
    };

    for (let input of inputs) {
        input.addEventListener('input', () => validate());
        input.addEventListener('change', () => validate());
    }

    form.onsubmit = (e) => {
        e.preventDefault();
        init = false;
        validate() && onSend(this);
    };
};

export default FormValidate;
