<?php
// Shortcode: [custom_login_form]
function custom_user_login_form_shortcode() {
    ob_start();
    ?>
    <form method="post" class="custom-registration-form">
        <?php wp_nonce_field('custom_user_login_action', 'custom_user_login_nonce'); ?>
        <input type="text" name="custom_username" placeholder="Username or Email" required />
        <input type="password" name="custom_password" placeholder="Password" required />
        <input type="submit" name="custom_user_login" value="Login" />
    </form>
    <?php
    return ob_get_clean();
}
add_shortcode('custom_login_form', 'custom_user_login_form_shortcode');

// Handle login form submission
function handle_custom_user_login() {
    if (
        isset($_POST['custom_user_login']) &&
        isset($_POST['custom_user_login_nonce']) &&
        wp_verify_nonce($_POST['custom_user_login_nonce'], 'custom_user_login_action')
    ) {
        $username = sanitize_user($_POST['custom_username']);
        $password = $_POST['custom_password'];

        $creds = array(
            'user_login'    => $username,
            'user_password' => $password,
            'remember'      => true
        );

        $user = wp_signon($creds, false);

        if (is_wp_error($user)) {
            echo "<p style='color:red;'>Invalid username or password.</p>";
        } else {
            wp_redirect(home_url('/my-account/'));
            exit;
        }
    }
}
add_action('init', 'handle_custom_user_login');
