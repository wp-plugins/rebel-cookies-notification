<?php
/*  Copyright YEAR  PLUGIN_AUTHOR_NAME  (email : PLUGIN AUTHOR EMAIL)
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
Plugin Name: Rebel Cookies Notification
Plugin URI: http://wordpress.org/plugins/
Description: This plugin was created by Rebel to notify users that cookies are being set but not mis-used.
Author: Anthony Broadbent   
Version: 1.2
Author URI: http://www.rebel.co.uk
rebel-cookies-notification
*/
function boot_session() {
  setCookie("Rebel_cookie_bar_set", 1);
}
add_action('wp_loaded','boot_session');

//this function sets up the cookie bar and sets the cookie


//registering javascript and css files
wp_register_script('jquery_cookies_script', plugins_url('rebel-cookies-notification/jquery.cookie.js'), array(), '1.0.0', true);
wp_enqueue_script('jquery_cookies_script');
wp_register_script('rebel_cookies_logo_script', plugins_url('rebel-cookies-notification/javascript.js'), array(), '1.0.0', true);
wp_enqueue_script('rebel_cookies_logo_script');
wp_register_style('rebel_cookies_logo_styles', plugins_url('rebel-cookies-notification/style.css'));
wp_enqueue_style('rebel_cookies_logo_styles');

//registering the settings function 'register_my_setting' into the admin_init function
add_action('admin_init', 'register_my_setting');

//registering the rebel_cookies_options_menu into the admin_menu function so the rebel_cookies_options_menu displays in the admin area
add_action('admin_menu', 'rebel_cookies_options_menu');

//settings groups and the names are added to a function
function register_my_setting() {
    
    register_setting('rebel_cookies_options_group', 'rebel_option_name');
}

//this function defines the names of each different attribute for a menu page. See: http://codex.wordpress.org/Function_Reference/add_menu_page
function rebel_cookies_options_menu() {
    
    add_menu_page('Rebel Cookies Notification', 'Rebel Cookies', 'manage_options', 'rebel_cookies_page', 'rebel_cookies_options_page', plugins_url('img/rebel_logo_icon.png', __FILE__));
};

//this is a validation function that uses esc_html to escape any html characters so they convert to html entities
function rebel_settings_validate($options) {
    
    $options = get_option('rebel_option_name');
    $options['option1'] = esc_html($options['option1']);
    
    return $options;
}

//this is a validation function to check if a user can manage the options before rendering the rest of the page, if not trigger wp_die
function rebel_cookies_options_page() {
    if (!current_user_can('manage_options')) {
        
        wp_die('You do not have sufficient permission to access this page!');
    };
    
    $stripinput = rebel_settings_validate($options);
    
    //this is the html for the options page
    
    
?>
<div class="wrap">  
    <?php
    screen_icon(); ?>
    <h2>Rebel Cookies Notification</h2>
    <p>You have installed the Rebel Cookies notification plug-in.<br/>An example of the cookies bar is displayed below and updates when you click submit.</p>
    <form method="post" action="options.php" name="rebel_cookies_settings_form"> 
    <?php
    
    //settings_fields defines the options group name then get_option passes the array of rebel_option_name to $options
    
    settings_fields('rebel_cookies_options_group');
    
    $options = get_option('rebel_option_name'); ?>
            <table class="form-table">
                
                <tr valign="top"><th scope="row">Please enter the text to display on the cookie bar:</th>
                    <td>
                    <input id="rebel_cookie_bar_text" type="text" name="rebel_option_name[option1]" value="<?php
    echo $options['option1']; ?>" />
                    </td>
                </tr>
                                    
                <tr valign="top"><th scope="row">Select your cookies page:</th>
                    <td>
                        <?php
    $args = array('echo' => 1, 'show_option_none' => 'None', 'name' => 'rebel_option_name[option3]', 'selected' => $options["option3"]);
    
    wp_dropdown_pages($args);
    
    echo '</td></tr><tr valign="top"><th scope="row">Background colour:</th><td><input type="color" name="rebel_option_name[option4]" value="';
    $options['option4'];
    echo '" /></tr><tr valign="top"><th scope="row">Text colour:</th><td><input type="color" name="rebel_option_name[option5]" value="';
    $options['option5'];
    echo '" /></tr><tr valign="top"><th scope="row">Link colour:</th><td><input type="color" name="rebel_option_name[option6]" value="';
    $options['option6'];
    echo '" /></tr><tr valign="top"><th scope="row">Position:</th><td>Top of screen: <input type="radio" name="rebel_option_name[option7]" value="top: 0"';
    checked('top:0', $options['option7'], "top: 0");
    echo '"/><br/>Bottom of screen: <input type="radio" name="rebel_option_name[option7]" value="bottom: 0"';
    checked('bottom:0', $options['option7'], "bottom: 0");
    echo '"/></tr></table><p class="submit"><input type="submit" class="button-primary" value="';
    _e('SaveChanges');
    echo '" /></p></form><div id="open_rebel_cookie_notification_bar_id"></div><div id="rebel_cookie_notification_bar_id" class="" style="' . $stripinput['option7'] . '"; background:"' . $stripinput['option4'] . '; "><div class="hello-container"><p style="color:' . $stripinput['option5'] . '">' . $stripinput['option1'];
    if (!$stripinput['option3'] == '') {
        echo " - <a style='color:" . $stripinput['option6'] . "' href='" . site_url('?page_id=' . $stripinput['option3']) . "'>Cookies page</a>";
    };
    echo '</p><div class="close"><a href="#">Close</a></div></div></div></div>';
};
function display_rebel_cookies_bar() {
    
    $stripinput = rebel_settings_validate($options);
    
    
    echo '<div id="open_rebel_cookie_notification_bar_id"></div><div id="rebel_cookie_notification_bar_id" class="" style="' . $stripinput['option7'] . ';background:' . $stripinput['option4'] . '"><div class="hello-container"><p style="color:' . $stripinput['option5'] . '">' . $stripinput['option1'];
    if (!$stripinput['option3'] == '') {
        echo " - <a style='color:" . $stripinput['option6'] . "' href='" . site_url('?page_id=' . $stripinput['option3']) . "'>Cookies page</a>";
    };
    echo '</p><div class="close"><a href="#">Close</a></div></div></div>';
    
}
// this renders the display_rebel_cookies_bar function so it appears in the footer of wp frontend using wp_footer

add_action('wp_footer', 'display_rebel_cookies_bar');


?>