<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://estrategai.lt
 * @since      1.0.0
 *
 * @package    Grfe
 * @subpackage Grfe/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap">
    <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
    <form action="options.php" method="post">
        <?php
        settings_fields( $this->plugin_name );
        do_settings_sections( $this->plugin_name );
        submit_button();
        ?>
    </form>
    <label for="grfe_export_url">Linkas skirtas exportinimui</label>
    <input id="grfe_export_url" title="Spausti, kad atidaryti naujame lange" value="<?php echo get_site_url() ?>/?remarketing=run" readonly onclick="window.open(this.value)" style="width: 300px"/>
</div>
