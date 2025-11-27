<h1 class="d-flex justify-content-center align-items-center">Password Reset</h1><br>
<div class="card login_container mx-auto" style="max-width: 550px">
    <div class="card-body">
        <div class="mx-auto">
            <?= view('includes/flashdata'); ?>
            <?php $validation = \Config\Services::validation(); ?>
            <?php if (session()->getFlashdata('errors')) { ?>

            <?php } ?>
            <?= form_open(base_url('/auth/process-reset-password')); ?>
            <?= csrf_field(); ?>
            <div class="mb-3">
                <a href="<?= base_URL('auth/login'); ?>" type="button" class="btn btn-outline-secondary text-start btn-sm"><&nbsp;&nbsp;Back
                    to Login</a>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="username" name="username"
                       value="<?= set_value('username'); ?>">
                <label for="username">User Name
                    <?php if ($validation->getError('username')) { ?>
                        <span class="help-block" style="color:red;">
                    <?= $validation->getError('username'); ?>
                </span>
                    <?php } ?>
                </label>
            </div>

            <!-- Token input container with 6 separate fields -->
            <div class="token-input-container mb-3 d-flex justify-content-center">
                <label class="bslabel">Token 6-digit number received by mail
                    <?php if ($validation->getError('token')) { ?>
                        <span class="help-block" style="color:red;">
                <?= $validation->getError('token'); ?>
            </span>
                    <?php } ?>
                </label>
                <div class="d-flex gap-2">
                    <?php
                    // Get the existing token value and split it into individual digits
                    $tokenValue = set_value('token');
                    $digits = str_split($tokenValue);

                    // Create 6 input fields
                    for ($i = 0; $i < 6; $i++) {
                        $value = isset($digits[$i]) ? $digits[$i] : '';
                        ?>
                        <input type="text"
                               class="form-control text-center token-digit"
                               maxlength="1"
                               inputmode="numeric"
                               pattern="\d"
                               data-index="<?= $i ?>"
                               name="token_digit[]"
                               value="<?= $value ?>"
                               style="width: 3rem;">
                    <?php } ?>

                    <!-- Hidden input to store the combined token value -->
                    <input type="hidden" name="token" id="tokenCombined" value="<?= set_value('token'); ?>">
                </div>
            </div>

<!--            <div class="form-floating mb-3">-->
<!--                <input type="text" class="form-control" id="token" name="token" maxlength="6"-->
<!--                       value="--><?php //= set_value('token'); ?><!--">-->
<!--                <label for="token">Token 6-digit number received by mail-->
<!--                    --><?php //if ($validation->getError('token')) { ?>
<!--                        <span class="help-block" style="color:red;">-->
<!--                        --><?php //= $validation->getError('token'); ?>
<!--                    </span>-->
<!--                    --><?php //} ?>
<!--                </label>-->
<!--            </div>-->
            <div class="form-floating mb-1">
                <input type="password" class="form-control" id="password" name="password"
                       value="<?= set_value('password'); ?>">
                <label for="password">Password
                    <?php if ($validation->getError('password')) { ?>
                        <span class="help-block" style="color:red;">
                        <?= $validation->getError('password'); ?>
                    </span>
                    <?php } ?>
                </label>
                <span class="show-pass" onclick="toggle()">
                    <i class="far fa-eye-slash"></i>
                </span>
                <div id="popover-password" style="width:90%" class="mx-auto">
                    <p><span id="result"></span></p>
                    <div class="progress">
                        <div id="password-strength"
                             class="progress-bar"
                             role="progressbar"
                             aria-valuenow="40"
                             aria-valuemin="0"
                             aria-valuemax="100"
                             style="width:0%">
                        </div>
                    </div>
                    <ul class="list-unstyled ms-3 mt-2" style="font-size:11px; color:#7f7f7f;">
                        <li class="">
                                    <span class="low-upper-case">
                                        <i class="fas fa-circle" aria-hidden="true"></i>
                                        &nbsp;Lowercase &amp; Uppercase
                                    </span>
                        </li>
                        <li class="">
                                    <span class="one-number">
                                        <i class="fas fa-circle" aria-hidden="true"></i>
                                        &nbsp;Number (0-9)
                                    </span>
                        </li>
                        <li class="">
                                    <span class="one-special-char">
                                        <i class="fas fa-circle" aria-hidden="true"></i>
                                        &nbsp;Special Character (!#$%+&*)
                                    </span>
                        </li>
                        <li class="">
                                    <span class="eight-character">
                                        <i class="fas fa-circle" aria-hidden="true"></i>
                                        &nbsp;Atleast 8 Character
                                    </span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="passconf" name="passconf"
                       value="<?= set_value('passconf'); ?>">
                <label for="passconf">Password Confirm
                    <?php if ($validation->getError('passconf')) { ?>
                        <span class="help-block" style="color:red;">
                        <?= $validation->getError('passconf'); ?>
                    </span>
                    <?php } ?>
                </label>
            </div>
            <div class="form-floating mt-3">
                <button type="submit" class="btn login-button  btn-block float-end btn-sm btn-effect-up">Save the new Password</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>
<div class="text-center fw-lighter">
        It may take a while to receive your code. Haven’t received it?
        <br>
        <a href="<?=base_url('auth/password-forgot')?>">Send a new Reset Code</a>
</div>

<script src="<?= base_URL(); ?>assets/js/pass-strength.js"></script>

<script>
    function toggle() {
        // Get references to the elements
        const password = document.getElementById("password");
        const icon = document.querySelector('.show-pass i');

        // Check if the password is currently visible
        const isPasswordVisible = password.type === "text";

        if (isPasswordVisible) {
            // If password is currently visible, hide it
            password.type = "password";
            icon.classList.remove("fa-eye");     // Changed: Now shows slashed eye when password is hidden
            icon.classList.add("fa-eye-slash");
        } else {
            // If password is currently hidden, show it
            password.type = "text";
            icon.classList.remove("fa-eye-slash"); // Changed: Now shows open eye when password is visible
            icon.classList.add("fa-eye");
        }
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.querySelector('.token-input-container');
        const inputs = container.querySelectorAll('.token-digit');
        const hiddenInput = document.getElementById('tokenCombined');

        // Function to update the hidden input with combined value
        function updateCombinedValue() {
            const combined = Array.from(inputs).map(input => input.value).join('');
            hiddenInput.value = combined;
        }

        // Add event listeners to each input
        inputs.forEach(input => {
            // Handle input events
            input.addEventListener('input', (e) => {
                // Allow only numbers
                e.target.value = e.target.value.replace(/[^\d]/g, '');

                if (e.target.value) {
                    const index = parseInt(e.target.dataset.index);
                    // Move to next input if available
                    if (index < inputs.length - 1) {
                        inputs[index + 1].focus();
                    }
                }
                updateCombinedValue();
            });

            // Handle keydown events
            input.addEventListener('keydown', (e) => {
                const index = parseInt(e.target.dataset.index);

                switch(e.key) {
                    case 'Backspace':
                        if (!e.target.value && index > 0) {
                            // Move to previous input if current is empty
                            inputs[index - 1].focus();
                            inputs[index - 1].value = '';
                        }
                        break;

                    case 'ArrowLeft':
                        if (index > 0) {
                            inputs[index - 1].focus();
                        }
                        break;

                    case 'ArrowRight':
                        if (index < inputs.length - 1) {
                            inputs[index + 1].focus();
                        }
                        break;

                    // Handle paste events
                    case 'v':
                        if (e.ctrlKey || e.metaKey) {
                            e.preventDefault();
                            navigator.clipboard.readText().then(text => {
                                const digits = text.replace(/\D/g, '').split('').slice(0, 6);
                                digits.forEach((digit, i) => {
                                    if (i < inputs.length) {
                                        inputs[i].value = digit;
                                    }
                                });
                                updateCombinedValue();
                                // Focus the next empty input or the last input
                                const nextEmpty = Array.from(inputs).find(input => !input.value)
                                    || inputs[inputs.length - 1];
                                nextEmpty.focus();
                            });
                        }
                        break;
                }
            });
        });

        // Handle form submission
        const form = container.closest('form');
        if (form) {
            form.addEventListener('submit', () => {
                updateCombinedValue();
            });
        }
    });
</script>

<style>
    .token-digit::-webkit-inner-spin-button,
    .token-digit::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    .token-digit {
        font-family: monospace;
        font-size: 1.2rem;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get all the necessary elements
        const container = document.querySelector('.token-input-container');
        const inputs = container.querySelectorAll('.token-digit');
        const hiddenInput = document.getElementById('tokenCombined');

        // Function to update the hidden input with combined values
        function updateCombinedValue() {
            const combined = Array.from(inputs).map(input => input.value).join('');
            hiddenInput.value = combined;
        }

        // Function to distribute digits across input fields
        function distributeDigits(text) {
            // Extract only numbers and limit to 6 digits
            const digits = text.replace(/\D/g, '').slice(0, 6).split('');

            // Fill each input field with corresponding digit
            inputs.forEach((input, index) => {
                input.value = digits[index] || '';
            });

            // Update the hidden combined value
            updateCombinedValue();

            // Focus the next empty input or the last input if all are filled
            const nextEmpty = Array.from(inputs).find(input => !input.value)
                || inputs[inputs.length - 1];
            nextEmpty.focus();
        }

        // Add event listeners to each input field
        inputs.forEach(input => {
            // Handle regular input
            input.addEventListener('input', (e) => {
                // Allow only numbers
                e.target.value = e.target.value.replace(/[^\d]/g, '');

                const currentIndex = parseInt(e.target.dataset.index);

                // If a digit was entered, move to next field
                if (e.target.value && currentIndex < inputs.length - 1) {
                    inputs[currentIndex + 1].focus();
                }

                updateCombinedValue();
            });

            // Handle paste event on individual input fields
            input.addEventListener('paste', (e) => {
                e.preventDefault();
                const pastedText = (e.clipboardData || window.clipboardData).getData('text');
                distributeDigits(pastedText);
            });

            // Handle keyboard navigation
            input.addEventListener('keydown', (e) => {
                const currentIndex = parseInt(e.target.dataset.index);

                switch(e.key) {
                    case 'Backspace':
                        if (!e.target.value && currentIndex > 0) {
                            inputs[currentIndex - 1].focus();
                            inputs[currentIndex - 1].value = '';
                            updateCombinedValue();
                        }
                        break;

                    case 'ArrowLeft':
                        if (currentIndex > 0) {
                            inputs[currentIndex - 1].focus();
                        }
                        break;

                    case 'ArrowRight':
                        if (currentIndex < inputs.length - 1) {
                            inputs[currentIndex + 1].focus();
                        }
                        break;
                }
            });
        });

        // Add paste event listener to the container
        container.addEventListener('paste', (e) => {
            // Prevent default paste behavior
            e.preventDefault();

            // Get pasted content
            const pastedText = (e.clipboardData || window.clipboardData).getData('text');
            distributeDigits(pastedText);
        });

        // Handle form submission
        const form = container.closest('form');
        if (form) {
            form.addEventListener('submit', () => {
                updateCombinedValue();
            });
        }
    });
</script>