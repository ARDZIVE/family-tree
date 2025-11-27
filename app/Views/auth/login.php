
    <div class="container">
        <div class="card">
            <div class="card-body">
        <div class="row ">
            <div class="col-12 col-md-6 bsb-tpl-bg-platinum">
                <div class="d-flex flex-column justify-content-between h-100 p-3 p-md-4 p-xl-5">
                    <img class="img-fluid rounded-3 mx-auto" loading="lazy" src="<?=Base_URL('assets/images/Talatinian_Logo_2.png') ?>" height="100" alt="Talatinian Family Tree">
                </div>
            </div>
            <div class="col-12 col-md-6 bsb-tpl-bg-lotion">
                <div class="p-3 p-md-4 p-xl-5">
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-5 text-center">
                                <h1>Login</h1>
                            </div>
                        </div>
                    </div>

                    <?= view('includes/flashdata'); ?>
                    <?php $validation = \Config\Services::validation(); ?>
                    <?php if (session()->getFlashdata('error')) { ?>
                    <?php } ?>
                    <?=form_open(base_url('auth/login/sign'));?>
                    <?=csrf_field();?>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="username" name="username" autocomplete="off"
                               value="<?= set_value('username'); ?>">
                        <label for="username">User Name
                            <?php if($validation->getError('username')) {?>
                                <span class="help-block" style="color:red;">
                        <?= $validation->getError('username'); ?>
                    </span>
                            <?php }?>
                        </label>
                    </div>
                    <div class="form-floating mb-1">
                        <input type="password" class="form-control" id="password" name="password"
                               value="<?= set_value('password'); ?>">
                        <label for="password">Password
                            <?php if($validation->getError('password')) {?>
                                <span class="help-block" style="color:red;">
                        <?= $validation->getError('password'); ?>
                    </span>
                            <?php }?>
                        </label>
                        <span class="show-pass" onclick="toggle()">
                            <i class="far fa-eye-slash"></i>
                        </span>
                    </div>
                    <div class="form-floating">
                        <div id="captImg" class="mt-1" style="float: left;"><?= $captchaImg ?> </div>
                        <a href="javascript:void(0);" class="refreshCaptcha"> <i class="fas fa-retweet fa-2x pt-1 m-1" style="color:#9A9282"></i></a>
                    </div>
                    <div class="form-floating mt-1">
                        <input type="text" class="form-control col-md-6" name="captcha" id="captcha" value="" autocomplete="off"/>
                        <label for="captcha">Captcha Code
                            <?php if($validation->getError('captcha')) {?>
                                <span class="help-block" style="color:red;">
                        <?= $validation->getError('captcha'); ?>
                    </span>
                            <?php }?>
                        </label>
                    </div>
                    <div class="d-grid mt-3">
                        <button type="submit" class="btn login-button mb-3"><i class="bi bi-box-arrow-in-right"></i>Sign in</button>
                    </div>
                    <?=form_close();?>
                    <div class="row">
                        <div class="col-12">
                            <hr class="mt-5 mb-4 border-secondary-subtle">
                            <div class="text-end">
                                <a href="<?=base_URL('auth/password-forgot')?>" class="link-secondary text-decoration-none">Forgot Password?</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            </div>
        </div>
    </div>


<!-- captcha refresh code -->
<script>
    $(document).ready(function(){
        $('.refreshCaptcha').on('click', function(){
            $.get('<?= base_url('auth/captcha_refresh'); ?>', function(data){
                $('#captImg').html(data);
            });
        });
    });
</script>

<script>
    let state = false;
    function toggle() {
        const password = document.getElementById("password");
        const icon = document.querySelector('.show-pass i');

        if (state) {
            password.setAttribute("type", "password");
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
            state = false;
        } else {
            password.setAttribute("type", "text");
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
            state = true;
        }
    }
</script>