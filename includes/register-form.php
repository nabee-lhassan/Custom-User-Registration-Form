<?php
// Shortcode: [custom_user_registration_form]
function custom_user_registration_form_shortcode() {
    ob_start();
    ?>
    <form method="post" class="custom-registration-form">
        <?php wp_nonce_field('custom_user_registration_action', 'custom_user_registration_nonce'); ?>
        <input type="text" name="custom_username" placeholder="Username" required />
        <input type="email" name="custom_email" placeholder="Email" required />
        <input type="password" name="custom_password" placeholder="Password" required />
        <input type="submit" name="custom_user_register" value="Register" />
    </form>
    <?php
    return ob_get_clean();
}
add_shortcode('custom_user_registration_form', 'custom_user_registration_form_shortcode');


// Handle form submission
function handle_custom_user_registration() {
    if (
        isset($_POST['custom_user_register']) &&
        isset($_POST['custom_user_registration_nonce']) &&
        wp_verify_nonce($_POST['custom_user_registration_nonce'], 'custom_user_registration_action')
    ) {
        $username = sanitize_user($_POST['custom_username']);
        $email = sanitize_email($_POST['custom_email']);
        $password = $_POST['custom_password'];

       if ( username_exists($username) || email_exists($email) ) {
    echo "<script>alert('Username or Email already exists!');</script>";
    return;
}

        $user_id = wp_create_user($username, $password, $email);
        if ( !is_wp_error($user_id) ) {
            wp_set_current_user($user_id);
            wp_set_auth_cookie($user_id);
            wp_redirect(home_url('/register/'));
            exit;
        } else {
            echo "<p style='color:red;'>" . $user_id->get_error_message() . "</p>";
        }
    }
}
add_action('init', 'handle_custom_user_registration');
