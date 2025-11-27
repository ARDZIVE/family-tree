<h1 class="d-flex justify-content-center align-items-center">Forgot Password</h1><br>
<style>
    .login_container {max-width: 550px;}
</style>
<div class="card col-md mx-auto login_container justify-content-center">
    <div class="card-body">


    <?php $validation = \Config\Services::validation(); ?>
        <?= view('includes/flashdata'); ?>

    <?=form_open(base_url('/auth/process-forgot-password'));?>
    <?=csrf_field();?>
    <div class="mx-auto">
        <div class="mb-3">
            <a href="<?=base_URL('auth/login');?>" type="button" class="btn btn-outline-secondary text-start btn-sm"><&nbsp;&nbsp;Back to Login</a>
        </div>
        <div class="mb-3" style="font-size:0.8rem;background-color:#E5EFFD">
            <div class="card">
                <div class="card-body text-bg-light p-3">
                    <strong>Not to worry!</strong><br>
                    Just enter your User Name.
                    We will then send your Reset Code to the email address associated with that account.
                </div>
            </div>
        </div>
        <div class="form-floating mb-3">
        <input type="text" class="form-control" id="name" name="username"
               value="<?= set_value('username'); ?>">
        <label for="username">Enter your Username
            <?php if($validation->getError('username')) {?>
                <span class="help-block" style="color:red;">
                        <?= $validation->getError('username'); ?>
                    </span>
            <?php }?>
        </label>
    </div>
    <button type="submit" class="btn login-button float-end btn-sm"><i class="bi bi-envelope"></i> Send Reset Code</button>
    </div>
</form>
    <?=form_close();?>

<br>
<div>
<!--    <a href="--><?php //=base_URL('register');?><!--">Create an Account!</a>-->
</div>
    </div>
</div>
