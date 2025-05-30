<?php
/*
Plugin Name: Custom User Registration Form
Description: A simple WordPress plugin to display a custom registration form via shortcode. Use [custom_user_registration_form].
Version: 1.1.1
Author: Nabeel Hassan
*/

// Register the shortcode
function custom_user_registration_form_shortcode() {
    ob_start();
    ?>
    <form method="post">
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
            echo "<p style='color:red;'>Username or Email already exists!</p>";
            return;
        }

        $user_id = wp_create_user($username, $password, $email);
        if ( !is_wp_error($user_id) ) {
            wp_set_current_user($user_id);
            wp_set_auth_cookie($user_id);
            wp_redirect(home_url('/welcome/'));
            exit;
        } else {
            echo "<p style='color:red;'>" . $user_id->get_error_message() . "</p>";
        }
    }
}
add_action('init', 'handle_custom_user_registration');





// add style and js file 

function enqueue_custom_user_registration_assets() {
    $plugin_url = plugin_dir_url(__FILE__);

    wp_enqueue_style('custom-user-registration-style', $plugin_url . 'css/style.css');

    wp_enqueue_script('custom-user-registration-script', $plugin_url . 'js/script.js', array(), null, true);
}
add_action('wp_enqueue_scripts', 'enqueue_custom_user_registration_assets');
