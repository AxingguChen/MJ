<?php
/**
 * Created by PhpStorm.
 * User: jackie
 * Date: 10/11/2015
 * Time: 3:13 PM
 */
?>

<body>
<!-- contact form -->
<div class="text-center wow fadeInDown  text-center animated"
     data-wow-duration="500ms" data-wow-delay="300ms">
    <div class="contact-form">

        <div class="input-group row firstname-lastname">

            <?php echo form_open("verification/verify"); ?>
            <div class="input-text-field col-md-1">Email:</div>
            <div class="input-field col-md-4">
                <input type="text" name="email" id="email"
                       placeholder="email address" class="form-control">
            </div>
            <div class="input-text-field col-md-1">Password:</div>
            <div class="input-field col-md-4">
                <input type="password" name="password" id="password"
                       placeholder="password" class="form-control">
            </div>
            <div class="input-group">
                <input type="submit" id="form-submit" class="pull-right"
                       value="Login here">
            </div>
            <?php echo form_close(); ?>
        </div>



    </div>
</div>
<!-- end contact form -->

</body>