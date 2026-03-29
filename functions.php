<?php

/*
**
**
**
**
**
*/

    define('THEME_DIR', dirname(__FILE__));
    define('THEME_URL', get_bloginfo('template_directory'));

    
    foreach (glob(THEME_DIR . '/classes/*.php')    as $file) require_once($file);
    foreach (glob(THEME_DIR . '/shortcodes/*.php')    as $file) require_once($file);
    
    // hide language selector on login page
    add_filter('login_display_language_dropdown', '__return_false');

    require THEME_DIR . '/inc/transparent-header-option.php';

    function bina_sidebars() {
        register_sidebar(array(
            'name' => __('Primary Sidebar', 'bina'),
            'id' => 'primary-sidebar',
            'description' => __('Widgets in this area will be shown on the right-hand side.', 'bina'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h4 class="widget-title">',
            'after_title' => '</h4>',
        ));
    }
    add_action('widgets_init', 'bina_sidebars');
    

    $inc_files = array(
        'theme-setup.php',
        'theme-scripts.php',
        'theme-widgets.php',
        'theme-customizer.php',
        'dashboard-helpers.php',
        'dashboard-pages.php',
        'admin-bar.php',
        'cpt-bina-project.php',
        'ajax-bina-project.php',
    );

    foreach ( $inc_files as $file ) {
        require_once get_template_directory() . '/inc/' . $file;
    }

    require_once(get_template_directory() . '/elementor/widgets-manager.php');

    // Auth roles used by register/login widgets.
    function bina_register_auth_roles() {
        add_role('customer', 'Customer', array('read' => true));
        add_role('service_provider', 'Service Provider', array('read' => true));
    }
    add_action('init', 'bina_register_auth_roles');
    
    if(isset($_GET['bina_ajax'])) require_once THEME_DIR . '/ajax/ajax.php';

    add_action( 'init', 'create_header_post_type' );
    function create_header_post_type() {
        register_post_type( 'header',
            array(
                'labels' => array(
                    'name' => __( 'Headers' ),
                    'singular_name' => __( 'Header' )
                ),
                'public' => true,
                'has_archive' => false,
                'menu_icon' => 'dashicons-admin-appearance',
                'supports' => array( 'title', 'editor', 'thumbnail', 'elementor' ),
            )
        );
    }
    
    add_action( 'init', 'create_footer_post_type' );
    function create_footer_post_type() {
        register_post_type( 'footer',
            array(
                'labels' => array(
                    'name' => __( 'Footers' ),
                    'singular_name' => __( 'Footer' )
                ),
                'public' => true,
                'has_archive' => false,
                'menu_icon' => 'dashicons-admin-appearance',
                'supports' => array( 'title', 'editor', 'thumbnail', 'elementor' ),
            )
        );
    }    


    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function custom_breadcrumbs()
    {
        global $post;
        
        $delimiter = '<span class="breadcrumb-delimiter">&raquo;</span>';
        $home = '<i class="fa-solid fa-house"></i> الرئيسية';
        $before = '<li class="breadcrumbs-item">';
        $after = '</li>';
        echo '<ul class="breadcrumbs-1">';
        if (!is_front_page()) {
            echo $before . '<a href="' . home_url() . '">' . $home . '</a>' . $delimiter . $after;
            if (is_page()) {
                if ($post->post_parent) {
                    $anc = get_post_ancestors(get_the_ID());
                    $title_array = array();
                    foreach ($anc as $ancestor) {
                        $title_array[] = get_the_title($ancestor);
                    }
                    echo $before . implode($delimiter, $title_array) . $delimiter . $after;
                }
                echo $before;
                echo the_title();
                echo $after;
            } else if (is_category()) {
                $category = get_queried_object();
                if ($category && !is_wp_error($category)) {
                    echo $before;
                    echo $category->name;
                }
            } else if (is_singular()) {
                $category = get_the_category();
                if ($category) {
                    echo $before;
                    the_category(' </li> - <li class="breadcrumbs-item"> ');
                    echo $delimiter . $after;
                }
                echo $before;
                the_title();
                echo $after;
            } elseif (is_search(  )){
                echo $before . 'Search results for "' . get_search_query() . '"' . $after;    
            } elseif (is_tag()) {
                echo $before . 'Posts tagged "' . single_tag_title('', false) . '"' . $after;
            } elseif (is_day()) {
                echo $before . 'Archive for ' . get_the_time('F jS, Y') . $after;
            } elseif (is_month()) {
                echo $before . 'Archive for ' . get_the_time('F, Y') . $after;
            } elseif (is_year()) {
                echo $before . 'Archive for ' . get_the_time('Y') . $after;
            } elseif (is_author()) {
                echo $before . 'Author Archive' . $after;
            } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) {
                //echo $before . 'المدونة' . $after;
            }
        } elseif (is_tag()) {
            single_tag_title();
        } elseif (is_day()) {
            echo "<li class='breadcrumbs-item'>Archive for ";
            the_time('F jS, Y');
            echo '</li>';
        } elseif (is_month()) {
            echo "<li class='breadcrumbs-item'>Archive for ";
            the_time('F, Y');
            echo '</li>';
        } elseif (is_year()) {
            echo "<li class='breadcrumbs-item'>Archive for ";
            the_time('Y');
            echo '</li>';
        } elseif (is_author()) {
            echo "<li class='breadcrumbs-item'>Author Archive";
            echo '</li>';
        } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) {
            echo "<li class='breadcrumbs-item'>Blog Archives";
            echo '</li>';
        } elseif (is_search()) {
            echo "<li class='breadcrumbs-item'>نتائج البحث";
            echo '</li>';
        }
        echo '</ul>';
    }
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function zad_pagination() {
        global $wp_query;
        $total = $wp_query->max_num_pages;
        if ( $total > 1 ) {
            if ( !$current_page = (get_query_var('paged')) ? get_query_var('paged'): ((get_query_var('page')) ? get_query_var('page') : 1) ) {
                $current_page = 1;
            }
            echo '<div class="pagination"><div class="pagination-inner">'.paginate_links(array(
                'base' => @add_query_arg( 'page', '%#%' ),
                'total'        => $wp_query->max_num_pages,
                'current'      => max( 1, get_query_var( 'paged' ) ),
                'format'       => '?page=%#%',
                'show_all'     => false,
                'type'         => 'plain',
                'end_size'     => 2,
                'mid_size'     => 1,
                'prev_next'    => true,
                'prev_text'    => sprintf( '%1$s', __( '<i class="fas fa-angle-right"></i>', 'text-domain' ) ),
                'next_text'    => sprintf( '%1$s', __( '<i class="fas fa-angle-left"></i>', 'text-domain' ) ),
                'add_args'     => false,
                'add_fragment' => '',
            )).'</div></div>';
        }
    }


    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Add this to your theme's functions.php file or a custom plugin

    // AJAX handler for sending contact us email
    add_action('wp_ajax_send_contact_us_email', 'send_contact_us_email_ajax');
    add_action('wp_ajax_nopriv_send_contact_us_email', 'send_contact_us_email_ajax'); // if you want this AJAX functionality for non-logged in users too

    function send_contact_us_email_ajax() {
        // Initialize error array
        $response = array();
    
        // Get form data
        $name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
        if (empty($name)) {
            $response['success'] = false;
            $response['message'] = 'Name is required';
            wp_send_json($response);
        }
    
        $title = isset($_POST['title']) ? sanitize_text_field($_POST['title']) : '';
        if (empty($title)) {
            $response['success'] = false;
            $response['message'] = 'Title is required';
            wp_send_json($response);
        }
    
        $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
        if (empty($email) || !is_email($email)) {
            $response['success'] = false;
            $response['message'] = 'Valid email is required';
            wp_send_json($response);
        }
    
        $phone = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';
        // Assuming phone number is optional, no validation needed
    
        $message = isset($_POST['message']) ? sanitize_textarea_field($_POST['message']) : '';
        if (empty($message)) {
            $response['success'] = false;
            $response['message'] = 'Message is required';
            wp_send_json($response);
        }
    
        // Prepare data for the email function
        $data = array(
            'name' => $name,
            'title' => $title,
            'email' => $email,
            'phone' => $phone,
            'message' => $message,
        );
    
        // Get administrators' emails
        $admin_emails = array();
        $admins = get_users(array('role' => 'administrator'));
        foreach ($admins as $admin) {
            $admin_emails[] = $admin->user_email;
        }
    
        // Send email
        $mail_sent = send_contact_us_mail($data, $admin_emails);
    
        // Check if email was sent successfully
        if ($mail_sent) {
            $response['success'] = true;
            $response['message'] = 'Email sent successfully';
        } else {
            $response['success'] = false;
            $response['message'] = 'Failed to send email';
        }
    
        // Return response
        wp_send_json($response);
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Register page (cities + user registration)

    function bina_normalize_phone($phone) {
        $phone = (string) $phone;
        $digits = preg_replace('/\D+/', '', $phone);

        if ($digits === '') {
            return '';
        }

        if (strpos($digits, '00966') === 0) {
            $digits = substr($digits, 5);
        } elseif (strpos($digits, '966') === 0) {
            $digits = substr($digits, 3);
        }

        if (strlen($digits) === 9 && strpos($digits, '5') === 0) {
            $digits = '0' . $digits;
        }

        return $digits;
    }

    add_action('wp_ajax_bina_get_cities', 'bina_get_cities_ajax');
    add_action('wp_ajax_nopriv_bina_get_cities', 'bina_get_cities_ajax');

    function bina_get_cities_ajax() {
        wp_send_json_success( bina_get_cities_for_select() );
    }

    add_action('wp_ajax_bina_register_user', 'bina_register_user_ajax');
    add_action('wp_ajax_nopriv_bina_register_user', 'bina_register_user_ajax');

    function bina_register_user_ajax() {
        $field_errors = array();

        $first_name = isset($_POST['firstName']) ? sanitize_text_field($_POST['firstName']) : '';
        $last_name = isset($_POST['lastName']) ? sanitize_text_field($_POST['lastName']) : '';
        $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
        $phone = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';
        $city = isset($_POST['city']) ? sanitize_text_field($_POST['city']) : '';
        $password = isset($_POST['password']) ? (string) $_POST['password'] : '';
        $confirm_password = isset($_POST['confirmPassword']) ? (string) $_POST['confirmPassword'] : '';
        $account_type = isset($_POST['accountType']) ? sanitize_text_field($_POST['accountType']) : '';
        $phone_normalized = bina_normalize_phone($phone);
        if ($account_type === 'contractor') {
            $account_type = 'service_provider';
        }

        if ($first_name === '') $field_errors['firstName'] = 'الاسم الأول مطلوب';
        if ($email === '' || !is_email($email)) $field_errors['email'] = 'يرجى إدخال بريد إلكتروني صحيح';
        if ($phone === '') $field_errors['phone'] = 'رقم الهاتف مطلوب';
        if ($city === '') $field_errors['city'] = 'المدينة مطلوبة';
        if (strlen($password) < 8) $field_errors['password'] = 'يجب أن تكون كلمة المرور 8 أحرف على الأقل';
        if ($confirm_password === '') $field_errors['confirmPassword'] = 'يرجى تأكيد كلمة المرور.';
        if ($confirm_password !== '' && $confirm_password !== $password) $field_errors['confirmPassword'] = 'كلمتا المرور غير متطابقتين';

        if ($email !== '' && email_exists($email)) {
            $field_errors['email'] = 'هذا البريد الإلكتروني مستخدم بالفعل';
        }

        if ($phone !== '') {
            $existing_phone_raw = new WP_User_Query(array(
                'meta_key'   => 'bina_phone',
                'meta_value' => $phone,
                'number'     => 1,
                'fields'     => 'ID',
            ));
            $existing_phone_normalized = new WP_User_Query(array(
                'meta_key'   => 'bina_phone_normalized',
                'meta_value' => $phone_normalized,
                'number'     => 1,
                'fields'     => 'ID',
            ));
            if (!empty($existing_phone_raw->get_results()) || !empty($existing_phone_normalized->get_results())) {
                $field_errors['phone'] = 'رقم الهاتف مستخدم بالفعل';
            }
        }

        if (!in_array($account_type, array('customer', 'service_provider'), true)) {
            $field_errors['accountType'] = 'نوع الحساب غير صالح';
        }

        if (!empty($field_errors)) {
            wp_send_json_error(array(
                'message' => 'يرجى تصحيح الأخطاء في النموذج',
                'fieldErrors' => $field_errors,
            ), 400);
        }

        // Use email as username base
        $username_base = sanitize_user(current(explode('@', $email)), true);
        if ($username_base === '') $username_base = 'user';
        $username = $username_base;
        $i = 1;
        while (username_exists($username)) {
            $username = $username_base . $i;
            $i++;
        }

        $user_id = wp_create_user($username, $password, $email);
        if (is_wp_error($user_id)) {
            wp_send_json_error(array('message' => $user_id->get_error_message()), 500);
        }

        wp_update_user(array(
            'ID' => $user_id,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'display_name' => trim($first_name . ' ' . $last_name),
        ));

        $role_to_set = ($account_type === 'service_provider') ? 'service_provider' : 'customer';
        $wp_user = new WP_User($user_id);
        $wp_user->set_role($role_to_set);

        update_user_meta($user_id, 'bina_phone', $phone);
        update_user_meta($user_id, 'bina_phone_normalized', $phone_normalized);
        update_user_meta($user_id, 'bina_city', $city);
        update_user_meta($user_id, 'bina_account_type', $account_type);

        // Auto-login after registration
        wp_set_current_user($user_id);
        wp_set_auth_cookie($user_id);

        $redirect_url = ($role_to_set === 'service_provider')
            ? bina_get_service_provider_dashboard_url()
            : bina_get_customer_dashboard_url();

        wp_send_json_success(array(
            'user_id' => $user_id,
            'redirect_url' => $redirect_url,
        ));
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Login page (AJAX)

    add_action('wp_ajax_bina_login_user', 'bina_login_user_ajax');
    add_action('wp_ajax_nopriv_bina_login_user', 'bina_login_user_ajax');

    function bina_login_user_ajax() {
        $field_errors = array();

        $identifier = isset($_POST['identifier']) ? sanitize_text_field($_POST['identifier']) : '';
        $password = isset($_POST['password']) ? (string) $_POST['password'] : '';

        if ($identifier === '') {
            $field_errors['identifier'] = 'البريد الإلكتروني أو اسم المستخدم أو رقم الهاتف مطلوب';
        }
        if ($password === '') {
            $field_errors['password'] = 'كلمة المرور مطلوبة';
        }

        if (!empty($field_errors)) {
            wp_send_json_error(array(
                'message' => 'يرجى تصحيح الأخطاء في النموذج',
                'fieldErrors' => $field_errors,
            ), 400);
        }

        $user = null;

        // Email login
        if (is_email($identifier)) {
            $user = get_user_by('email', $identifier);
        }

        // Username login
        if (!$user) {
            $user = get_user_by('login', $identifier);
        }

        // Phone login: match registered phone meta
        if (!$user) {
            $user_query = new WP_User_Query(array(
                'meta_key' => 'bina_phone_normalized',
                'meta_value' => bina_normalize_phone($identifier),
                'number' => 1,
                'fields' => 'all',
            ));
            $users = $user_query->get_results();
            if (!empty($users)) {
                $user = $users[0];
            }
        }

        if (!$user) {
            $user_query = new WP_User_Query(array(
                'meta_key' => 'bina_phone',
                'meta_value' => $identifier,
                'number' => 1,
                'fields' => 'all',
            ));
            $users = $user_query->get_results();
            if (!empty($users)) {
                $user = $users[0];
            }
        }

        if (!$user) {
            $field_errors['identifier'] = 'بيانات الدخول غير صحيحة';
            wp_send_json_error(array(
                'message' => 'بيانات الدخول غير صحيحة',
                'fieldErrors' => $field_errors,
            ), 400);
        }

        if (!wp_check_password($password, $user->user_pass, $user->ID)) {
            $field_errors['password'] = 'كلمة المرور غير صحيحة';
            wp_send_json_error(array(
                'message' => 'بيانات الدخول غير صحيحة',
                'fieldErrors' => $field_errors,
            ), 400);
        }

        wp_set_current_user($user->ID);
        wp_set_auth_cookie($user->ID, true);
        do_action('wp_login', $user->user_login, $user);

        $account_type = get_user_meta($user->ID, 'bina_account_type', true);
        if ($account_type === 'contractor') {
            $account_type = 'service_provider';
        }

        $redirect_url = home_url('/');
        if ($account_type === 'service_provider' || in_array('service_provider', (array) $user->roles, true)) {
            $redirect_url = bina_get_service_provider_dashboard_url();
        } elseif ($account_type === 'customer' || in_array('customer', (array) $user->roles, true)) {
            $redirect_url = bina_get_customer_dashboard_url();
        }

        wp_send_json_success(array(
            'redirect_url' => $redirect_url,
        ));
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Forgot password (AJAX)

    add_action('wp_ajax_bina_forgot_password', 'bina_forgot_password_ajax');
    add_action('wp_ajax_nopriv_bina_forgot_password', 'bina_forgot_password_ajax');

    function bina_forgot_password_ajax() {
        $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
        $nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';

        if (!wp_verify_nonce($nonce, 'bina_forgot_password_nonce')) {
            wp_send_json_error(array(
                'message' => 'طلب غير صالح',
                'fieldErrors' => array('email' => 'حدث خطأ في الأمان، حاول مرة أخرى'),
            ), 400);
        }

        if ($email === '' || !is_email($email)) {
            wp_send_json_error(array(
                'message' => 'يرجى إدخال بريد إلكتروني صحيح',
                'fieldErrors' => array('email' => 'يرجى إدخال بريد إلكتروني صحيح'),
            ), 400);
        }

        $user = get_user_by('email', $email);
        if (!$user) {
            wp_send_json_error(array(
                'message' => 'هذا البريد الإلكتروني غير مسجل لدينا',
                'fieldErrors' => array('email' => 'هذا البريد الإلكتروني غير مسجل لدينا'),
            ), 400);
        }

        $sent = retrieve_password($user->user_login);
        if (is_wp_error($sent)) {
            wp_send_json_error(array(
                'message' => 'تعذر إرسال رابط إعادة التعيين حاليًا',
                'fieldErrors' => array('email' => 'تعذر إرسال رابط إعادة التعيين حاليًا'),
            ), 500);
        }

        wp_send_json_success(array(
            'message' => 'تم إرسال رابط إعادة تعيين كلمة المرور إلى بريدك الإلكتروني',
        ));
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    function send_contact_us_mail($data = array(), $to_emails = array()){

        $name = isset($data['name']) ? $data['name'] : '';
        $title = isset($data['title']) ? $data['title'] : '';
        $email = isset($data['email']) ? $data['email'] : '';
        $phone = isset($data['phone']) ? $data['phone'] : '';
        $message_body = isset($data['message']) ? $data['message'] : '';
    
        // Create HTML message
        $message = "<html>
        <head>
        <style>
            /* Add your CSS styles here for a beautiful design */
            body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            }
            .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }
            table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            }
            th, td {
            border: 1px solid #dddddd;
            padding: 10px;
            }
        </style>
        </head>
        <body dir='rtl'>
        <div class='container'>
                
            <h2>$title</h2>
            
            <table>
            <tr>
                <td>$message_body</td>
            </tr>
            </table>
    
            <h2>بيانات الإتصال</h2>
            <table>
            <tr>
                <th>الإسم</th>
                <td>$name</td>
            </tr>
            <tr>
                <th>الجوال</th>
                <td>$phone</td>
            </tr>
            <tr>
                <th>البريد الإلكتروني</th>
                <td>$email</td>
            </tr>
            </table>
        </div>
        </body>
        </html>";
    
      // Subject
      $subject = $title . ' - ' . $name;
  
      // Headers
      $headers = array(
          'Content-Type: text/html;charset=UTF-8',
          'From: bina Contact <no-reply@bina.com>', // Replace with your email and name
      );
      
      // Send email to each recipient
      foreach ($to_emails as $to_email) {
          wp_mail($to_email, $subject, $message, $headers);
      }
  
      return true;
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    add_action('wp_ajax_register_interest', 'register_interest_callback');
    add_action('wp_ajax_nopriv_register_interest', 'register_interest_callback');
    
    function register_interest_callback() {
        // Initialize an empty array to store validation errors
        $errors = array();
    
        // Retrieve and sanitize form data
        parse_str($_POST['form_data'], $formData); // Convert form data to array
        $name = isset($formData['name']) ? sanitize_text_field($formData['name']) : '';
        // $product_id = isset($formData['product_id']) ? sanitize_text_field($formData['product_id']) : '';
        $title = isset($formData['title']) ? sanitize_text_field($formData['title']) : '';
        $phone = isset($formData['phone']) ? sanitize_text_field($formData['phone']) : '';
        $email = isset($formData['email']) ? sanitize_email($formData['email']) : '';
        $message = isset($formData['message']) ? sanitize_textarea_field($formData['message']) : '';
        $variation = isset($formData['variation']) ? $formData['variation'] : '';
        $product_id = isset($formData['product_id']) ? $formData['product_id'] : '';
    
        // Perform validation for each field
        if (empty($name)) {
            $errors['name'] = 'Name is required.';
        }
        if (empty($product_id)) {
            $errors['product_id'] = 'Product is required.';
        }
        if (empty($title)) {
            $errors['title'] = 'Title is required.';
        }
        if (empty($phone)) {
            $errors['phone'] = 'Phone is required.';
        }
        if (empty($email)) {
            $errors['email'] = 'Email is required.';
        } elseif (!is_email($email)) {
            $errors['email'] = 'Invalid email format.';
        }
        if (empty($message)) {
            $errors['message'] = 'Message is required.';
        }

        // If there are validation errors, send them back as response
        if (!empty($errors)) {
            $errors_string = '';
            foreach ($errors as $error) {
                $errors_string .= $error . '<br>';
            }
            wp_send_json(['success' => false, 'message' => $errors_string]);
            wp_die();
        }

        $product_name = get_the_title($product_id);
        $product_link = get_the_permalink($product_id);

        // If all data is valid, proceed with further processing
        $email_content = "<html>
        <head>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f5f5f5;
            }
            .container {
                max-width: 600px;
                margin: 0 auto;
                padding: 20px;
                background-color: #ffffff;
                border-radius: 10px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }
            th, td {
                border: 1px solid #dddddd;
                padding: 10px;
                text-align: start;
            }
        </style>
        </head>
        <body dir='ltr'>
        <div class='container'>
                
            <h2>Interesting Details:</h2>
            
            <table>
            <tr>
                <th>Name</th>
                <td>$name</td>
            </tr>
            <tr>
                <th>Title</th>
                <td>$title</td>
            </tr>
            <tr>
                <th>Phone</th>
                <td>$phone</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>$email</td>
            </tr>
            <tr>
                <th>Message</th>
                <td>$message</td>
            </tr>
            <tr>
                <th>Product ID</th>
                <td>$product_id</td>
            </tr>
            <tr>
                <th>Product Name</th>
                <td><a href=\"$product_link\">$product_name</a></td>
            </tr>
            </table>

            <h2>Variations:</h2>
            <table>
            <tr>
                <th>Variation</th>
                <th>Value</th>
            </tr>";
            
        foreach ($variation as $key => $value) {
            // Format the variation key
            $key_formatted = ucwords(str_replace('_', ' ', $key));
            // Add variation key and value to the table row
            $email_content .= "<tr><td>$key_formatted</td><td>$value</td></tr>";
        }

        $email_content .= "</table>
        </div>
        </body>
        </html>";

        // Get all WordPress administrators
        $admins = get_users(['role' => 'administrator']);

        // Headers
        $headers = array(
            'Content-Type: text/html;charset=UTF-8',
            'From: bina Contact <no-reply@bina.sa>', // Replace with your email and name
        );
        // Send email to each administrator
        foreach ($admins as $admin) {
            $admin_email = $admin->user_email;
            $subject = 'New Interesting';
            // Send email
            wp_mail($admin_email, $subject, $email_content, $headers);
        }

        // file_put_contents(THEME_DIR . '/email.html', $email_content);
    
        // Send success response
        wp_send_json(['success' => true, 'message' => 'Your interest has been registered successfully.']);
        wp_die();
    }
    


    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    function auto_login_user_by_id() {
        if ( isset($_GET['user_id']) && !is_user_logged_in() ) {
            $user_id = intval($_GET['user_id']);
            
            // Verify the user ID exists
            $user = get_userdata($user_id);
            if ($user) {
                // Log in the user
                wp_set_current_user($user_id);
                wp_set_auth_cookie($user_id);
                do_action('wp_login', $user->user_login, $user);
    
                // Redirect to avoid exposing the user ID in the URL
                wp_redirect(home_url());
                exit;
            } else {
                // Invalid user ID, handle accordingly
                wp_die('Invalid user ID.');
            }
        }
    }
    add_action('init', 'auto_login_user_by_id');
    



    // Register and load the custom widgets
    function load_custom_widgets() {
        register_widget('Form_Search_Widget');
        register_widget('Recent_Articles_Widget');
        register_widget('Suggestions_Tags_Widget');
    }
    add_action('widgets_init', 'load_custom_widgets');

    // Form Search Widget
    class Form_Search_Widget extends WP_Widget {
        function __construct() {
            parent::__construct(
                'form_search_widget',
                __('Form Search', 'bina'),
                array('description' => __('A custom search form widget', 'bina'))
            );
        }

        public function widget($args, $instance) {
            echo $args['before_widget'];
            ?>
            <form class="form-search" method="get" action="<?php echo home_url('/'); ?>">
                <div class="form-group">
                    <input type="text" name="s" class="form-control" placeholder="البحث" />
                    <span><i class="fa-solid fa-magnifying-glass"></i></span>
                </div>
            </form>
            <?php
            echo $args['after_widget'];
        }
    }

    // Recent Articles Widget
    class Recent_Articles_Widget extends WP_Widget {
        function __construct() {
            parent::__construct(
                'recent_articles_widget',
                __('Recent Articles', 'bina'),
                array('description' => __('A widget to display recent articles', 'bina'))
            );
        }

        public function widget($args, $instance) {
            echo $args['before_widget'];
            ?>
            <div class="cont-blog">
                <h4>مقالات أخرى</h4>
                <div class="lst-blog">
                    <?php
                    $recent_posts = new WP_Query([
                        'posts_per_page' => 5,
                        'post__not_in' => [get_the_ID()]
                    ]);
                    if ($recent_posts->have_posts()) : while ($recent_posts->have_posts()) : $recent_posts->the_post(); ?>
                        <div class="item-blog">
                            <p><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></p>
                        </div>
                    <?php endwhile; wp_reset_postdata(); endif; ?>
                </div>
            </div>
            <?php
            echo $args['after_widget'];
        }
    }

    // Suggestions Tags Widget
    class Suggestions_Tags_Widget extends WP_Widget {
        function __construct() {
            parent::__construct(
                'suggestions_tags_widget',
                __('Suggestions Tags', 'bina'),
                array('description' => __('A widget to display suggestion tags', 'bina'))
            );
        }

        public function widget($args, $instance) {
            echo $args['before_widget'];
            ?>
            <div class="suggestions-tags">
                <h4>أقتراحات</h4>
                <ul>
                    <?php
                    $tags = get_tags();
                    foreach ($tags as $tag) :
                    ?>
                        <li><a href="<?php echo get_tag_link($tag->term_id); ?>"><?php echo $tag->name; ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php
            echo $args['after_widget'];
        }
    }


    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    add_action( 'wp_footer', 'bina_preloader');
    function bina_preloader() {
        // return;
        echo '
        <div class="loader" style="display:none"><i class="fas fa-sync fa-spin"></i></div>
        <div id="preloader" style="display:none">
            <div class="preloader-inner">
                <svg viewBox="0 0 100 20">
                    <defs>
                        <linearGradient id="gradient" x1="0" x2="0" y1="0" y2="1">
                            <stop offset="5%" stop-color="#080986"></stop>
                            <stop offset="95%" stop-color="#06075b"></stop>
                        </linearGradient>
                        <pattern id="wave" x="0" y="0" width="120" height="20" patternUnits="userSpaceOnUse">
                            <path id="wavePath" d="M-40 9 Q-30 7 -20 9 T0 9 T20 9 T40 9 T60 9 T80 9 T100 9 T120 9 V20 H-40z"
                                mask="url(#mask)" fill="url(#gradient)">
                                <animateTransform attributeName="transform" begin="0s" dur="1.5s" type="translate"
                                    from="0,0" to="40,0" repeatCount="indefinite"></animateTransform>
                            </path>
                        </pattern>
                    </defs>
                    <text text-anchor="middle" x="50" y="15" font-size="17" fill="url(#wave)" fill-opacity="0.6">bina</text>
                    <text text-anchor="middle" x="50" y="15" font-size="17" fill="url(#gradient)" fill-opacity="0.1">bina</text>
                </svg>
            </div>
        </div>';
    }

    // Add developer signature in page source
    add_action('wp_footer', function() {
        echo "\n<!-- Developed by Amin Omer through Meawal -->\n";
    }, 100);

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    function upload_image_from_url($url) {

        if (!$url) {
            return;
        }
        // Get the filename from the URL
        $filename = basename($url);

        // Fetch the image data
        $image_data = file_get_contents($url);

        if (!$image_data) {
            echo 'Failed to fetch image: ' . $url;
            return;
        }

        // Upload the image data
        $upload = wp_upload_bits($filename, null, $image_data);

        if (!$upload['error']) {
            // Include the WordPress media handling functions
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            require_once(ABSPATH . 'wp-admin/includes/media.php');
            require_once(ABSPATH . 'wp-admin/includes/image.php');

            // Set up the attachment metadata
            $attachment = array(
                'post_title' => sanitize_file_name($filename),
                'post_mime_type' => wp_check_filetype($filename)['type'],
                'post_content' => '',
                'post_status' => 'inherit'
            );

            // Insert the attachment
            $attach_id = wp_insert_attachment($attachment, $upload['file']);

            // Optional: Update additional image meta data, set post-thumbnail, etc.
            if (!is_wp_error($attach_id)) {
                $attach_data = wp_generate_attachment_metadata($attach_id, $upload['file']);
                wp_update_attachment_metadata($attach_id, $attach_data);
            }
        } else {
            echo 'Failed to upload image: ' . $filename;
        }
    }

    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////// AJAX HANDLER ///////////////////////////////////////////

    function handle_contact_form_submission() {
        // Get the form data
        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);
        $phone = sanitize_text_field($_POST['phone']);
        $service_type = sanitize_text_field($_POST['service_type']);
    
        // Prepare the email
        $to = 'binaweb00@gmail.com';
        $subject = 'New Contact Form Submission';
        $headers = array('Content-Type: text/html; charset=UTF-8');
        $body = "
            <h2>New Contact Form Submission</h2>
            <p><strong>Name:</strong> {$name}</p>
            <p><strong>Email:</strong> {$email}</p>
            <p><strong>Phone:</strong> {$phone}</p>
            <p><strong>Service Type:</strong> {$service_type}</p>
        ";
    
        // Send the email
        $mail_sent = wp_mail($to, $subject, $body, $headers);
    
        // Return response
        if ($mail_sent) {
            wp_send_json_success(['message' => 'Your message was successfully sent!']);
        } else {
            wp_send_json_error(['message' => 'Failed to send your message. Please try again later.']);
        }
    
        wp_die(); // All AJAX functions must call wp_die() at the end
    }
    add_action('wp_ajax_submit_contact_form', 'handle_contact_form_submission');
    add_action('wp_ajax_nopriv_submit_contact_form', 'handle_contact_form_submission');    


    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    function custom_language_redirect() {
        if (isset($_GET['lang'])) {
            $lang = $_GET['lang'];
    
            // Ensure base URL does not contain "/en/"
            $base_url = preg_replace('|/en/?$|', '', site_url());
    
            // Set the redirect URL
            if ($lang === 'en') {
                $redirect_url = $base_url . '/en/'; // Redirect to English version
            } elseif ($lang === 'ar') {
                $redirect_url = $base_url; // Redirect to Arabic version
            } else {
                return; // Invalid language, do nothing
            }
    
            // Redirect and remove ?lang= parameter
            wp_redirect($redirect_url);
            exit;
        }
    }
    add_action('template_redirect', 'custom_language_redirect');

    
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function bina_visibility_control($element, $section_id) {
        if ('section' === $element->get_name() && 'section_advanced' === $section_id) {
            $element->add_control(
                'custom_section_visibility',
                [
                    'label' => __('Hide Section', 'bina'),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => __('Hide', 'bina'),
                    'label_off' => __('Show', 'bina'),
                    'return_value' => 'yes',
                    'default' => '',
                ]
            );
        }
    }
    add_action('elementor/element/section/section_advanced/after_section_end', 'bina_visibility_control', 10, 2);
    
    //////////////////////////header + footer  ///////////////////////////////////////
    