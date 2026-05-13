<?php
/*
Plugin Name: FooGallery Dynamic Style Manager (Pro Fix)
Description: Gestore di stili dinamici con fix di sicurezza e logica JS corretta.
Version: 2.3
*/

if (!defined('ABSPATH')) exit;

add_action('add_meta_boxes', function() {
    add_meta_box('fg_dynamic_switcher', '🎨 Gestore Stili Dinamici', 'fg_render_dynamic_box', 'foogallery', 'side', 'high');
});

function fg_render_dynamic_box($post) {
    $presets = ['justified' => 'Justified', 'responsive' => 'Responsive', 'masonry' => 'Masonry'];
    ?>
    <div id="fg-switcher-container" style="padding: 10px 0;">
        <?php foreach ($presets as $id => $label) : 
            $saved = get_option("fg_preset_data_$id");
            ?>
            <div style="margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px;">
                <strong style="display:block; margin-bottom:5px;"><?php echo strtoupper($id); ?></strong>
                <button type="button" class="button fg-apply" data-preset="<?php echo $id; ?>" <?php disabled(!$saved); ?> style="width: 68%;">
                    <?php echo $saved ? 'Applica' : 'Slot Vuoto'; ?>
                </button>
                <button type="button" class="button fg-save" data-preset="<?php echo $id; ?>" title="Salva design attuale" style="width: 28%;">💾</button>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
    jQuery(document).ready(function($) {
        // Funzione Unificata per AJAX
        function handlePresetAction(btn, subAction) {
            var preset = btn.data('preset');
            var post_id = <?php echo $post->ID; ?>;
            var nonce = '<?php echo wp_create_nonce("fg_dynamic_nonce"); ?>';

            $.post(ajaxurl, {
                action: 'fg_manage_preset',
                sub_action: subAction,
                preset: preset,
                post_id: post_id,
                _ajax_nonce: nonce
            })
            .done(function(res) {
                if (res.success) {
                    alert(subAction === 'save' ? 'Stile salvato!' : 'Stile applicato!');
                    location.reload();
                } else {
                    alert('Errore: ' + (res.data || 'Operazione fallita.'));
                }
            })
            .fail(function() {
                alert('Errore di rete o permessi insufficienti.');
            });
        }

        $('.fg-apply').click(function() {
            if (confirm('Applicare questo preset?')) handlePresetAction($(this), 'apply');
        });

        $('.fg-save').click(function() {
            if (confirm('Salvare lo stile attuale in questo slot?')) handlePresetAction($(this), 'save');
        });
    });
    </script>
    <?php
}

add_action('wp_ajax_fg_manage_preset', function() {
    check_ajax_referer('fg_dynamic_nonce');
    
    $post_id = intval($_POST['post_id']);
    $preset_id = sanitize_text_field($_POST['preset']);
    $sub_action = sanitize_text_field($_POST['sub_action']);

    $allowed_presets = ['justified', 'responsive', 'masonry'];
    if (!in_array($preset_id, $allowed_presets, true)) {
        wp_send_json_error('Preset non valido.');
    }

    if (!current_user_can('edit_post', $post_id)) {
        wp_send_json_error('Permessi insufficienti.');
    }

    if (get_post_type($post_id) !== 'foogallery') {
        wp_send_json_error('Post non valido.');
    }

    if ($sub_action === 'save') {
        $current_template = get_post_meta($post_id, 'foogallery_template', true);
        $current_settings = get_post_meta($post_id, '_foogallery_settings', true);

        if (!$current_template) wp_send_json_error('Nessun dato da salvare.');

        update_option("fg_preset_template_$preset_id", $current_template, false);
        update_option("fg_preset_data_$preset_id", $current_settings, false);
        wp_send_json_success();

    } elseif ($sub_action === 'apply') {
        $target_template = get_option("fg_preset_template_$preset_id");
        $target_settings = get_option("fg_preset_data_$preset_id");

        if ($target_template && $target_settings) {
            update_post_meta($post_id, 'foogallery_template', $target_template);
            update_post_meta($post_id, '_foogallery_settings', $target_settings);
            wp_send_json_success();
        } else {
            wp_send_json_error('Preset vuoto.');
        }
    }
    wp_send_json_error('Azione non valida.');
});
