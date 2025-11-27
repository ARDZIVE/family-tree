// Password Strength Meter
document.getElementById('password').addEventListener('input', function() {
    let password = this.value;
    let strength = 0;

    // Initialize UI elements
    const strengthBar = document.getElementById('password-strength');
    const result = document.getElementById('result');
    const criteriaIcons = {
        lowercase: document.querySelector('.low-upper-case i'),
        number: document.querySelector('.one-number i'),
        special: document.querySelector('.one-special-char i'),
        length: document.querySelector('.eight-character i')
    };

    // Check criteria
    const criteria = {
        lowUpper: password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/),
        numbers: password.match(/([0-9])/),
        special: password.match(/([!,%,&,@,#,$,^,*,?,_,~])/),
        length: password.length > 7
    };

    // Update UI for each criteria
    function updateCriteria(element, isValid) {
        if (isValid) {
            element.classList.remove('fa-circle');
            element.classList.add('fa-check-circle');
            element.parentElement.classList.add('valid-criteria');
            element.parentElement.classList.remove('invalid-criteria');
            strength += 25;
        } else {
            element.classList.remove('fa-check-circle');
            element.classList.add('fa-circle');
            element.parentElement.classList.remove('valid-criteria');
            element.parentElement.classList.add('invalid-criteria');
        }
    }

    // Update all criteria
    updateCriteria(criteriaIcons.lowercase, criteria.lowUpper);
    updateCriteria(criteriaIcons.number, criteria.numbers);
    updateCriteria(criteriaIcons.special, criteria.special);
    updateCriteria(criteriaIcons.length, criteria.length);

    // Update strength bar
    strengthBar.style.width = strength + '%';

    // Update strength level text and colors
    if (strength === 0) {
        result.textContent = '';
        strengthBar.className = 'progress-bar';
    } else if (strength <= 25) {
        result.textContent = 'Very Weak';
        result.style.color = '#dc3545';
        strengthBar.className = 'progress-bar bg-danger';
    } else if (strength <= 50) {
        result.textContent = 'Weak';
        result.style.color = '#ffc107';
        strengthBar.className = 'progress-bar bg-warning';
    } else if (strength <= 75) {
        result.textContent = 'Medium';
        result.style.color = '#0dcaf0';
        strengthBar.className = 'progress-bar bg-info';
    } else {
        result.textContent = 'Strong';
        result.style.color = '#198754';
        strengthBar.className = 'progress-bar bg-success';
    }
});

// Password visibility toggle
let state = false;
function toggle() {
    const password = document.getElementById("password");
    const icon = document.querySelector('.show-pass i');

    if (state) {
        password.setAttribute("type", "password");
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    } else {
        password.setAttribute("type", "text");
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    }
    state = !state;
}