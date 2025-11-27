<center><h1>Login</h1></center><br>
<style>
    .login_container {max-width: 450px;}
</style>
<div class="card login_container mx-auto">
    <div class="card-body">

        <?= view('includes/flashdata'); ?>
        <?php $validation = \Config\Services::validation(); ?>
        <?php if (session()->getFlashdata('error')) { ?>

        <?php } ?>
        <?=form_open(base_url('login/sign'));?>
            <?=csrf_field();?>

            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="username" name="username"
                    value="<?= set_value('username'); ?>">
                <label for="username">User Name
                    <?php if($validation->getError('username')) {?>
                        <span class="help-block" style="color:red;">
                        <?= $validation->getError('username'); ?>
                    </span>
                    <?php }?>
                </label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control" id="password" name="password"
                    value="<?= set_value('password'); ?>">
                <label for="password">Password
                <?php if($validation->getError('password')) {?>
                    <span class="help-block" style="color:red;">
                        <?= $validation->getError('password'); ?>
                    </span>
                <?php }?>
                </label>
            </div>
            <div class="form-text text-end">&nbsp;
                <a href="<?=base_URL('password-forgot')?>">Reset Password</a>
            </div>
            <div class="form-floating">
                <div id="captImg" style="float: left;"><?= $captchaImg ?> </div>
                <a href="javascript:void(0);" class="refreshCaptcha"> <i class="fas fa-retweet fa-2x ms-3"></i></a>
            </div>
            <div class="form-floating">
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
                <button type="submit" class="btn btn-primary btn-block btn-sm">Submit</button>
            </div>
<!--        <div class="form-text">&nbsp;-->
<!--            Don't have an account? <a href="--><?php //=base_URL('register')?><!--" >Sign Up</a>-->
<!--        </div>-->
<!--        <div class="form-floating mt-3">-->
<!--            <center>-->
<!--                <a href="member_lost_pwd.html" id="Forgot_pwd" class="btn btn-danger btn-sm">Forgot Your Password ?</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="register.html" id="Register" class="btn btn-warning btn-sm">Register for an Account ?</a>-->
<!--            </center>-->
<!--        </div>-->
        <div class="btn-group mt-3 d-flex justify-content-center" role="group">
            <a href="<?=base_URL('password-forgot')?>" role="button" class="btn btn-outline-secondary btn-sm">Forgot Your Password ?</a>
            <a href="<?=base_URL('register')?>" role="button" class="btn btn-outline-secondary btn-sm">Register for an Account ?</a>
        </div>
        <?=form_close();?>
</div>
</div>


<!-- Login 3 - Bootstrap Brain Component -->
<section class="p-3 p-md-4 p-xl-5">
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-6 bsb-tpl-bg-platinum">
                <div class="d-flex flex-column justify-content-between h-100 p-3 p-md-4 p-xl-5">
                    <h3 class="m-0">Welcome!</h3>
                    <img class="img-fluid rounded mx-auto my-4" loading="lazy" src="./assets/img/coq.svg" width="245" height="80" alt="BootstrapBrain Logo">
                    <p class="mb-0">Not a member yet? <a href="#!" class="link-secondary text-decoration-none">Register now</a></p>
                </div>
            </div>
            <div class="col-12 col-md-6 bsb-tpl-bg-lotion">
                <div class="p-3 p-md-4 p-xl-5">
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-5">
                                <h3>Log in</h3>
                            </div>
                        </div>
                    </div>

                    <?= view('includes/flashdata'); ?>
                    <?php $validation = \Config\Services::validation(); ?>
                    <?php if (session()->getFlashdata('error')) { ?>

                    <?php } ?>
                    <?=form_open(base_url('login/sign'));?>
                    <?=csrf_field();?>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="username" name="username"
                               value="<?= set_value('username'); ?>">
                        <label for="username">User Name
                            <?php if($validation->getError('username')) {?>
                                <span class="help-block" style="color:red;">
                        <?= $validation->getError('username'); ?>
                    </span>
                            <?php }?>
                        </label>
                    </div>
                    <div class="form-floating">
                        <input type="password" class="form-control" id="password" name="password"
                               value="<?= set_value('password'); ?>">
                        <label for="password">Password
                            <?php if($validation->getError('password')) {?>
                                <span class="help-block" style="color:red;">
                        <?= $validation->getError('password'); ?>
                    </span>
                            <?php }?>
                        </label>
                    </div>
<!--                    <div class="form-text text-end">&nbsp;-->
<!--                        <a href="--><?php //=base_URL('password-forgot')?><!--">Reset Password</a>-->
<!--                    </div>-->
                    <div class="form-floating">
                        <div id="captImg" style="float: left;"><?= $captchaImg ?> </div>
                        <a href="javascript:void(0);" class="refreshCaptcha"> <i class="fas fa-retweet fa-2x ms-3"></i></a>
                    </div>
                    <div class="form-floating">
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
                        <button type="submit" class="btn btn-primary btn-block btn-sm">Submit</button>
                    </div>
<!--                    <div class="btn-group mt-3 d-flex justify-content-center" role="group">-->
<!--                        <a href="--><?php //=base_URL('password-forgot')?><!--" role="button" class="btn btn-outline-secondary btn-sm">Forgot Your Password ?</a>-->
<!--                        <a href="--><?php //=base_URL('register')?><!--" role="button" class="btn btn-outline-secondary btn-sm">Register for an Account ?</a>-->
<!--                    </div>-->
                    <?=form_close();?>

                    <div class="row">
                        <div class="col-12">
                            <hr class="mt-5 mb-4 border-secondary-subtle">
                            <div class="text-end">
                                <a href="#!" class="link-secondary text-decoration-none">Forgot password</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- captcha refresh code -->
<script>
    $(document).ready(function(){
        $('.refreshCaptcha').on('click', function(){
            $.get('<?= base_url('captcha_refresh'); ?>', function(data){
                $('#captImg').html(data);
            });
        });
    });
</script>