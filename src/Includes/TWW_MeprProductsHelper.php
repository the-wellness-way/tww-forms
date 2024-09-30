<?php
namespace TWWForms\Includes;

/**
 * Some replacements for \MeprProductsHelper class
 */
class TWW_MeprProductsHelper {
    public static function preset_period_dropdown($period_str, $period_type_str) {
        ?>
        <select id="<?php echo $period_type_str; ?>-presets"
                data-period-id="<?php echo $period_str; ?>"
                data-period-type-id="<?php echo $period_type_str; ?>">
            <option value="monthly"><?php _e('Monthly', 'memberpress'); ?>&nbsp;</option>
            <option value="yearly"><?php _e('Yearly', 'memberpress'); ?>&nbsp;</option>
            <option value="weekly"><?php _e('Weekly', 'memberpress'); ?>&nbsp;</option>
            <option value="quarterly"><?php _e('Every 3 Months', 'memberpress'); ?>&nbsp;</option>
            <option value="semi-annually"><?php _e('Every 6 Months', 'memberpress'); ?>&nbsp;</option>
            <option default value="fixed-date"><?php _e('Fixed Date', 'memberpress'); ?>&nbsp;</option>
            <option value="custom"><?php _e('Custom', 'memberpress'); ?>&nbsp;</option>
        </select>
        <?php
      }
}