<div class="edit-username-form">
    <form id="tww-edit-user-name-form" action="" method="post">
        <label for="first_name">First Name</label>
        <input type="text" name="first_name" id="tww-userForm-firstName" value="<?php echo $first_name; ?>" placeholder="First Name" />

        <label for="last_name">Last Name</label>
        <input type="text" name="last_name" id="tww-userForm-lastName" value="<?php echo $last_name; ?>" placeholder="Last Name" />

        <label for="email">Email <span class="required"><sup>*</sup></span></label>
        <input type="email" name="email" id="tww-userForm-email" value="<?php echo $email; ?>" placeholder="Enter your email" required />
        <button id="tww-edit-user-button" class="tww-form-submit-button loader-default loader-default--primary"><span class="loader-default--inner"></span> Save</button>
    </form>
</div>