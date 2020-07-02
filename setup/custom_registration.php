<?php 
add_action( 'register_form', 'ub_register_form' );
function ub_register_form() {

    $first_name = ( ! empty( $_POST['first_name'] ) ) ? trim( $_POST['first_name'] ) : '';
    $last_name = ( ! empty( $_POST['last_name'] ) ) ? trim( $_POST['last_name'] ) : '';

        ?>
        <p>
            <label for="first_name"><?php _e( 'First Name', 'usrbse' ) ?><br />
                <input type="text" name="first_name" id="first_name" class="input" value="<?php echo esc_attr( wp_unslash( $first_name ) ); ?>" size="25" /></label>
        </p>

        <?php
    }

    add_filter( 'registration_errors', 'ub_registration_errors', 10, 3 );
    function ub_registration_errors( $errors, $sanitized_user_login, $user_email ) {

        if ( empty( $_POST['first_name'] ) || ! empty( $_POST['first_name'] ) && trim( $_POST['first_name'] ) == '' ) {
            $errors->add( 'first_name_error', __( '<strong>ERROR</strong>: You must include a first name.', 'usrbse' ) );
        }
        return $errors;
    }

    add_action( 'user_register', 'ub_user_register' );
    function ub_user_register( $user_id ) {
        if ( ! empty( $_POST['first_name'] ) ) {
            update_user_meta( $user_id, 'first_name', trim( $_POST['first_name'] ) );
        }
    }
