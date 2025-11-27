
<center><h1>Register</h1></center><br>
<div class="card mx-auto" style="max-width: 550px">
    <div class="card-body">
        <div class="mx-auto">
            <?= view('includes/flashdata'); ?>
            <?php $validation = \Config\Services::validation(); ?>
            <?php if (session()->getFlashdata('errors')) { ?>

            <?php } ?>
            <?= form_open(base_url('register/sign')); ?>
            <?= csrf_field(); ?>

            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="firstname" name="firstname"
                       value="<?= set_value('firstname'); ?>">
                <label for="firstname">First Name
                    <?php if ($validation->getError('firstname')) { ?>
                        <span class="help-block" style="color:red;">
                        <?= $validation->getError('firstname'); ?>
                    </span>
                    <?php } ?>
                </label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="lastname" name="lastname"
                       value="<?= set_value('lastname'); ?>">
                <label for="lastname">Last Name
                    <?php if ($validation->getError('lastname')) { ?>
                        <span class="help-block" style="color:red;">
                                    <?= $validation->getError('lastname'); ?>
                                </span>
                    <?php } ?>
                </label>
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
            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="email" name="email"
                       value="<?= set_value('email'); ?>">
                <label for="phone">Email
                    <?php if ($validation->getError('email')) { ?>
                        <span class="help-block" style="color:red;">
                        <?= $validation->getError('email'); ?>
                    </span>
                    <?php } ?>
                </label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="phone" name="phone"
                       value="<?= set_value('phone'); ?>">
                <label for="phone">Phone
                    <?php if ($validation->getError('phone')) { ?>
                        <span class="help-block" style="color:red;">
                                    <?= $validation->getError('phone'); ?>
                                </span>
                    <?php } ?>
                </label>
            </div>
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
                        <i class="far fa-eye" onclick="myFunction(this)"></i>
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
            <div class="mb-3">
                <label class="ms-1">
                    <input id="check" name="checkbox" type="checkbox">
                    Accept terms by clicking the checkbox
                </label>
            </div>
            <div class="d-grid mt-3">
                <button type="submit" id="register" class="btn btn-success btn-block btn-lg" disabled>Create Account</button>
            </div>
            <div class="mx-auto text-center mt-3">
                Already have an account? <a href="<?=base_URL('login')?>">Sign in here</a>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<script src="<?= base_URL(); ?>assets/js/pass-strength.js"></script>
<script>
    $('#check').change(function(){
        $('#register').prop('disabled',!this.checked);
        });
</script>
