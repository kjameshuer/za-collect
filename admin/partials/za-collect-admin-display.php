<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       kjhuer.com
 * @since      1.0.0
 *
 * @package    Za_Collect
 * @subpackage Za_Collect/admin/partials
 */
?>
<div id="za-collect-form-wrapper">
    <?php  
    
     ?>
     <h2><?php echo esc_html(get_admin_page_title()); ?></h2>
       <form action='options.php' method='post'>
           
           <?php $options=get_option($this->plugin_name); ?>
    
           <?php settings_fields($this->plugin_name); 
           do_settings_sections($this->plugin_name);?>
        <fieldset>
            <legend class="screen-reader-text"><span>Referral/Associate ID</span></legend>
            <label for="<?php echo $this->plugin_name; ?>-referral_id">
                <span><?php esc_attr_e('Referral/Associate ID', $this->plugin_name); ?></span>
                <input type="text" id="<?php echo $this->plugin_name; ?>-referral_id" name="<?php echo $this->plugin_name; ?>[referral_id]" value="<?php echo sanitize_text_field($options['referral_id'])?>"/>
            </label>
        </fieldset>
        
        <fieldset>
            <legend class="screen-reader-text"><span>Buy button text</span></legend>
            <label for="<?php echo $this->plugin_name; ?>-buy_button_text">
                <span><?php esc_attr_e('Buy button text', $this->plugin_name); ?></span>
                <input type="text" id="<?php echo $this->plugin_name; ?>-buy_button_text" name="<?php echo $this->plugin_name; ?>[buy_button_text]" value="<?php echo sanitize_text_field($options['buy_button_text'])?>"/>
            </label>
        </fieldset>
           
        <fieldset>
            <legend class="screen-reader-text"><span>Accent color</span></legend>
            <label for="<?php echo $this->plugin_name; ?>-accent_color">
                <span><?php esc_attr_e('Accent color', $this->plugin_name); ?></span>
                <input type="text" class="<?php echo $this->plugin_name;?>-color-picker" id="<?php echo $this->plugin_name; ?>-accent_color" name="<?php echo $this->plugin_name; ?>[accent_color]" value="<?php echo sanitize_text_field( $options['accent_color'])?>"/>
            </label>
        </fieldset>
           
        <fieldset>
            <legend class="screen-reader-text"><span>Accent text color</span></legend>
            <label for="<?php echo $this->plugin_name; ?>-accent_text_color">
                <span><?php esc_attr_e('Accent text color', $this->plugin_name); ?></span>
                <input type="text" class="<?php echo $this->plugin_name;?>-color-picker" id="<?php echo $this->plugin_name; ?>-accent_text_color" name="<?php echo $this->plugin_name; ?>[accent_text_color]" value="<?php echo $options['accent_text_color']?>"/>
            </label>
        </fieldset>
           
        <fieldset>
        <legend class="screen-reader-text">
            <span>Open new window on product link</span>
        </legend>
        <label for="<?php echo $this->plugin_name; ?>-new_window">
          <span><?php esc_attr_e('Open new window on product link', $this->plugin_name); ?></span>
            <input type="checkbox" id="<?php echo $this->plugin_name; ?>-new_window" name="<?php echo $this->plugin_name; ?>[new_window]" value="1" <?php checked( $options['new_window'], 1); ?> />
            
            
        </label>
        </fieldset>
           <?php submit_button('Save all changes', 'primary','submit', TRUE); ?>
        </form>
     
     <h2><?php echo _e('Instructions','za-collect') ?></h2>
     <p>zaCollect uses shortcodes to determine where collections are placed throughout your website. You can use shortcodes within WordPress Posts and Pages.</p>
     <p>To use zaCollect shortcodes, place the text 'za-collect' within a set of square brackets. Example:</p>
     <code>[za-collect]</code>
     <p>This will pull a default collection with 6 products into your post.</p>
     <p>You can pull a specific collection by adding the 'collection' option and providing it with the collection ID like so:</p>
     <code>[za-collect collection=119187790299772415]</code>
     <p>The collection ID for your collection can be found by visiting your collection page on Zazzle. Look at the URL and copy the last part of the collection URL (the 18 digit code).
         If the collection ID is not valid (entered incorrectly) then the default collection will show</p>
     <p>You can change the amount of products pulled from the collection by adding the 'count' option to the shortcode and providing a number between 1 & 100. For example:</p>
     <code>[za-collect collection=119187790299772415 count=20]</code>
     <p>This will add a grid of 20 products to your post with products from the specified collection</p>
     <p>Tracking can be added to outbound links through shortcode options as well. Using the 'tracking' option. Example:</p>
     <code>[za-collect collection=119187790299772415 count=20 tracking=zaCollect]</code>
     <p>If you have provided your associate ID above the term 'zaCollect' will now be used as your tracking text.</p>
     <p>Here's a table that shows the options and defaults so you can better understand what's going on.
     <table id='za-collect-options-table'>
         <tbody>
             <tr>
                 <th>Option</th>
                 <th>Details</th>
                 <th>Default</th>
             </tr>
             <tr>
                 <td class="option-heading">collection</td>
                 <td>The ID of the collection you would like to display</td>
                 <td class="default">119187790299772415</td>
             </tr>
             <tr>
                 <td class="option-heading">count</td>
                 <td>The number of products from 1-100 that you would like to display</td>
                 <td class="default">6</td>
             </tr>
             <tr>
                 <td class="option-heading">tracking</td>
                 <td>The tracking code added to outbound links with a referral ID</td>
                 <td class="default">zaCollect</td>
             </tr>
         </tbody>
     </table>
     
     
</div>    