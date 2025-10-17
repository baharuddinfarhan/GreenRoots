document.addEventListener("DOMContentLoaded", function () {
    const form = document.forms['contact_form'];

    const nameField = form.visitor_name;
    const emailField = form.visitor_email;
    const phoneField = form.visitor_phone;
    const messageField = form.visitor_message;

    function validateName() {
        const value = nameField.value.trim();
        if (value === "") return "Please enter your name.";
        if (!/^[a-zA-Z ]+$/.test(value)) return "Name can contain only letters and spaces.";
        return "";
    }

    function validateEmail() {
        const value = emailField.value.trim();
        if (value === "") return "Please enter your email.";
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) return "Please enter a valid email.";
        return "";
    }

    function validatePhone() {
        const value = phoneField.value.trim();
        if (value === "") return "Please enter your phone number.";
        if (!/^\+?[0-9]{1,17}$/.test(value)) return "Phone can contain only digits and optional '+' at start, max 17 digits.";
        return "";
    }

    function validateMessage() {
        const value = messageField.value.trim();
        if (value === "") return "Please enter your message.";
        return "";
    }

    function showError(field, message) {
        const feedback = field.nextElementSibling;
        if (message) {
            field.classList.add('is-invalid');
            feedback.textContent = message;
            feedback.style.color = 'red';
        } else {
            field.classList.remove('is-invalid');
            feedback.textContent = '';
        }
    }

    // Live validation on typing
    nameField.addEventListener('input', () => showError(nameField, validateName()));
    emailField.addEventListener('input', () => showError(emailField, validateEmail()));
    phoneField.addEventListener('input', () => showError(phoneField, validatePhone()));
    messageField.addEventListener('input', () => showError(messageField, validateMessage()));

    // Final check on submit
    form.addEventListener("submit", function (e) {
        const errors = [
            { field: nameField, msg: validateName() },
            { field: emailField, msg: validateEmail() },
            { field: phoneField, msg: validatePhone() },
            { field: messageField, msg: validateMessage() },
        ];

        let hasError = false;
        errors.forEach(error => {
            showError(error.field, error.msg);
            if (error.msg) hasError = true;
        });

        if (hasError) e.preventDefault();
    });
});
