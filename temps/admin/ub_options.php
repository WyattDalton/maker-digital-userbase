<div id="ub_settings_wrapper" class="ub_settings-displayed">
    <form method="post" action="options.php">
        <?php settings_fields( 'userbase_settings' ); ?>
        <?php do_settings_sections( 'userbase' ); ?>
        <?php submit_button(); ?>
    </form>
</div>
