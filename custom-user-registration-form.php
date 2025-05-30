<?php
/*
Plugin Name: Custom User Registration Form
Description: A simple WordPress plugin to display a custom registration form via shortcode. Use shortcode For Resiter Form [custom_user_registration_form] Use shortcode For Login Form [custom_login_form] .
Version: 1.0
Author: Nabeel Hassan
*/



// for check login status


add_action('wp_footer', 'custom_check_user_login_status');
function custom_check_user_login_status() {
    ?>
    <script>
        var isUserLoggedIn = <?php echo is_user_logged_in() ? 'true' : 'false'; ?>;
        var registerUrl = "<?php echo home_url('/register'); ?>";
        

        document.addEventListener("DOMContentLoaded", function () {
            if (!isUserLoggedIn) {
                const myAccountLink = document.querySelector(".My-account a");
                if (myAccountLink) {
                    myAccountLink.textContent = "Register/Login";
                    myAccountLink.href = registerUrl;
                }

               
            }
        });
    </script>
    <?php
}


// login user away from register page

add_action('template_redirect', 'redirect_logged_in_user_from_register');

function redirect_logged_in_user_from_register() {
    if (is_user_logged_in() && is_page('register')) {
        wp_redirect(home_url('/my-account/'));
        exit;
    }
}


// redirect after logout

add_action('wp_logout', 'custom_redirect_after_logout');
function custom_redirect_after_logout() {
    wp_redirect(home_url('/register'));
    exit;
}


// Register form

require_once plugin_dir_path(__FILE__) . 'includes/register-form.php';

// login form 

require_once plugin_dir_path(__FILE__) . 'includes/login-form.php';









// add style and js file 

function enqueue_custom_user_registration_assets() {
    $plugin_url = plugin_dir_url(__FILE__);

    wp_enqueue_style('custom-user-registration-style', $plugin_url . 'css/style.css');

    wp_enqueue_script('custom-user-registration-script', $plugin_url . 'js/script.js', array(), null, true);
}
add_action('wp_enqueue_scripts', 'enqueue_custom_user_registration_assets');




