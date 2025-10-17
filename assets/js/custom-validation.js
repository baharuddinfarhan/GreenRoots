document.addEventListener('DOMContentLoaded', function() {

    const registrationForm = document.getElementById('registrationForm');

    const fields = {
        cust_name: {regex: /^[a-zA-Z ]*$/, errorId: 'error-name', message: 'Only alphabets allowed'},
        cust_email: {regex: /^[^\s@]+@[^\s@]+\.[^\s@]+$/, errorId: 'error-email', message: 'Invalid email address'},
        cust_phone: {regex: /^\+?[0-9]{0,17}$/, errorId: 'error-phone', message: 'Only digits and "+" allowed, max 17 chars'},
        cust_address: {regex: /.+/, errorId: 'error-address', message: 'Address can not be empty'},
        cust_city: {regex: /^[a-zA-Z ]*$/, errorId: 'error-city', message: 'Only alphabets allowed'},
        cust_state: {regex: /^[a-zA-Z ]*$/, errorId: 'error-state', message: 'Only alphabets allowed'},
        cust_zip: {regex: /^[0-9]*$/, errorId: 'error-zip', message: 'Only digits allowed'},
        cust_password: {regex: /.+/, errorId: 'error-password', message: 'Password can not be empty'},
        cust_re_password: {regex: /.+/, errorId: 'error-repassword', message: 'Password can not be empty'}
    };

    Object.keys(fields).forEach(key => {
        const field = document.getElementById(key);
        const errorSpan = document.getElementById(fields[key].errorId);

        if(field) {
            field.addEventListener('input', () => {

                let val = field.value;

                // Special handling for phone
                if(key === 'cust_phone') {
                    val = val.replace(/[^0-9\+]/g,'');
                    if(val.indexOf('+') > 0) val = val.replace(/\+/g,'');
                    val = val.substring(0,17);
                } 
                // Only alphabets for name, city, state
                else if(key === 'cust_name' || key === 'cust_city' || key === 'cust_state') {
                    val = val.replace(/[^a-zA-Z ]/g,'');
                } 
                // Only digits for zip
                else if(key === 'cust_zip') {
                    val = val.replace(/[^0-9]/g,'');
                }

                field.value = val;

                // Show error if invalid
                if(!fields[key].regex.test(val) || val === '') {
                    errorSpan.textContent = fields[key].message;
                } else {
                    errorSpan.textContent = '';
                }

                // Password match check
                if(key === 'cust_re_password' || key === 'cust_password') {
                    const pass = document.getElementById('cust_password').value;
                    const rePass = document.getElementById('cust_re_password').value;
                    const repassError = document.getElementById('error-repassword');
                    if(rePass !== '' && pass !== rePass) {
                        repassError.textContent = 'Passwords do not match';
                    } else {
                        repassError.textContent = '';
                    }
                }
            });
        }
    });

    // Optional: prevent form submit if errors exist
    registrationForm.addEventListener('submit', function(e) {
        let hasError = false;
        Object.keys(fields).forEach(key => {
            const field = document.getElementById(key);
            const errorSpan = document.getElementById(fields[key].errorId);
            if(field && (field.value === '' || errorSpan.textContent !== '')) {
                hasError = true;
                errorSpan.textContent = errorSpan.textContent || fields[key].message;
            }
        });
        if(hasError) e.preventDefault();
    });

});
