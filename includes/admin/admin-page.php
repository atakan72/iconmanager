<?php
/** Admin Page – Icon Manager */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function iconmanager_admin_menu() {
    add_menu_page(
        __( 'Icon Manager', 'iconmanager' ),
        __( 'Icons', 'iconmanager' ),
        'manage_options',
        'iconmanager',
        'iconmanager_render_admin_page',
        'dashicons-art',
        59
    );
}
add_action( 'admin_menu', 'iconmanager_admin_menu' );

function iconmanager_render_admin_page() {
    $icons = iconmanager_get_available_icons();
    $show_import = get_option( 'iconmanager_show_import_buttons', '1' ) === '1';
    ?>
    <div class="wrap">
        <h1>🎨 Icon Manager</h1>
        <p><?php _e( 'Verwalte Brand & UI SVG Icons lokal (DSGVO-konform).', 'iconmanager' ); ?></p>

        <p>
            <button id="iconmanager-help-toggle" class="button">❓ <?php _e('Hilfe / Anleitung','iconmanager'); ?></button>
            <button id="iconmanager-options-toggle" class="button">⚙️ <?php _e('Optionen','iconmanager'); ?></button>
            <button id="iconmanager-licenses-toggle" class="button">🧾 <?php _e('Lizenzen','iconmanager'); ?></button>
        </p>

        <div id="iconmanager-help" style="display:none;margin:15px 0;padding:15px;border:1px solid #ccd0d4;background:#fff;max-width:980px;">
            <h2 style="margin-top:0;">ℹ️ <?php _e('Anleitung','iconmanager'); ?></h2>
            <p><strong><?php _e('Speicherort','iconmanager'); ?>:</strong> <code><?php echo esc_html( wp_upload_dir()['basedir'] . '/iconmanager-icons/{brands|ui}' ); ?></code><br>
            <?php _e('Icons werden im Uploads-Verzeichnis gespeichert und bleiben bei Plugin-Updates erhalten.','iconmanager'); ?></p>
            <p><strong><?php _e('Verwendung im Theme','iconmanager'); ?>:</strong><br>
            <code>&lt;?php echo iconmanager_render_icon('facebook','brand',24); ?&gt;</code><br>
            <code>&lt;?php iconmanager_icon('menu','ui',32,'#666'); ?&gt;</code><br>
            Shortcode: <code>[icon name="menu" type="ui" size="32" color="#666" class="me-2"]</code></p>
            <p><strong><?php _e('Medienbibliothek (optional)','iconmanager'); ?>:</strong><br>
            <?php _e('Für Standard-Verwendung ist kein Import in die Medien nötig. Wenn du Icons z.B. in Gutenberg Bild- oder Galerie-Blöcken auswählbar machen willst, kannst du sie in die Mediathek importieren.','iconmanager'); ?></p>
            <?php
            // Hinweis falls alter Ordner existiert
            $old_dir = trailingslashit( wp_upload_dir()['basedir'] ) . 'iconmanagement-dsgvo-icons';
            if ( is_dir( $old_dir ) ) {
                echo '<p style="margin-top:15px;" class="notice notice-info"><strong>' . esc_html__('Hinweis:','iconmanager') . '</strong> ' . esc_html__('Alter Icon-Ordner erkannt, Inhalte wurden bei Aktivierung migriert (falls noch nicht vorhanden).','iconmanager') . '</p>';
            }
            echo '<p style="margin-top:10px;font-size:12px;color:#555;">' . sprintf( esc_html__( 'Credits: Brand Icons von %1$s (CC0) – UI Icons von %2$s (ISC).', 'iconmanager' ), 'Simple Icons', 'Lucide' ) . '</p>';            
            ?>
        </div>

        <div id="iconmanager-licenses" style="display:none;margin:15px 0;padding:15px;border:1px solid #ccd0d4;background:#fff;max-width:900px;">
            <h2 style="margin-top:0;">🧾 <?php _e('Lizenzen & Hinweise','iconmanager'); ?></h2>
            <h3><?php _e('Hinweis zu den Icon-Lizenzen','iconmanager'); ?></h3>
            <p><strong>Lucide:</strong> <?php _e('Lizenztext muss enthalten sein. Credits sind freiwillig.','iconmanager'); ?> <a href="https://lucide.dev/license" target="_blank" rel="noopener">lucide.dev/license</a></p>
            <p><strong>Simple Icons:</strong> <?php _e('Meist frei nutzbar, aber Markenrechte beachten. Credits freiwillig.','iconmanager'); ?> <a href="https://github.com/simple-icons/simple-icons/blob/master/DISCLAIMER.md" target="_blank" rel="noopener">DISCLAIMER.md</a></p>
            <h3><?php _e('Empfohlener Credit (optional)','iconmanager'); ?></h3>
            <textarea id="iconmanager-credit-snippet" style="width:100%;height:70px;">Icons: Brand Icons (Simple Icons) & UI Icons (Lucide).</textarea>
            <p><button class="button" id="iconmanager-copy-credit">📋 <?php _e('Credit kopieren','iconmanager'); ?></button> <span id="iconmanager-copy-status" style="margin-left:10px;font-size:12px;color:#4b8;"></span></p>
        </div>

        <div id="iconmanager-options" style="display:none;margin:15px 0;padding:15px;border:1px solid #ccd0d4;background:#fff;max-width:780px;">
            <h2 style="margin-top:0;">⚙️ <?php _e('Optionen & Tools','iconmanager'); ?></h2>
            <form id="iconmanager-options-form">
                <?php wp_nonce_field( 'iconmanager_save_options', 'iconmanager_options_nonce' ); ?>
                <p>
                    <label><input type="checkbox" name="show_import" value="1" <?php checked( $show_import ); ?>> <?php _e('Pro Icon "Import" Button anzeigen','iconmanager'); ?></label>
                </p>
                <p><button type="submit" class="button button-primary">💾 <?php _e('Speichern','iconmanager'); ?></button></p>
            </form>
            <hr>
            <h3>🗂️ <?php _e('Mediathek Bulk-Import','iconmanager'); ?></h3>
            <p><?php _e('Alle vorhandenen Icons in die WordPress Medienbibliothek importieren. Bereits importierte werden übersprungen.','iconmanager'); ?></p>
            <p><button class="button" id="iconmanager-import-all-media">🗂️ <?php _e('Alle Icons in Mediathek importieren','iconmanager'); ?></button>
            <span id="iconmanager-import-all-status" style="margin-left:10px;"></span></p>
        </div>

        <div class="iconmanager-tabs" style="margin-top:25px;">
            <nav style="margin-bottom:12px;display:flex;gap:6px;flex-wrap:wrap;">
                <button class="button button-secondary iconmanager-tab-button active" data-target="#iconmanager-panel-downloads">⬇️ <?php _e('Downloads','iconmanager'); ?></button>
                <button class="button button-secondary iconmanager-tab-button" data-target="#iconmanager-panel-icons">📋 <?php _e('Icons','iconmanager'); ?></button>
            </nav>
            <div id="iconmanager-panel-downloads" class="iconmanager-tab-panel" style="display:block;">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                    <div class="card">
                        <h2>📦 <?php _e( 'Social / Brand Icons', 'iconmanager' ); ?></h2>
                        <p><?php _e( 'Lädt ein Set häufiger Social Media Icons herunter.', 'iconmanager' ); ?></p>
                        <button id="iconmanager-download-common-brands" class="button button-primary"><?php _e( 'Alle Brand Icons laden', 'iconmanager' ); ?></button>
                        <div style="margin-top:10px;">
                            <input type="text" id="iconmanager-single-brand" placeholder="facebook, twitter, github" />
                            <button id="iconmanager-download-single-brand" class="button">Download</button>
                        </div>
                    </div>
                    <div class="card">
                        <h2>🎛️ <?php _e( 'UI Icons', 'iconmanager' ); ?></h2>
                        <p><?php _e( 'Basis Icon-Set für Navigation & UI.', 'iconmanager' ); ?></p>
                        <button id="iconmanager-download-common-ui" class="button button-primary"><?php _e( 'Alle UI Icons laden', 'iconmanager' ); ?></button>
                        <div style="margin-top:10px;">
                            <input type="text" id="iconmanager-single-ui" placeholder="menu, search, arrow-right" />
                            <button id="iconmanager-download-single-ui" class="button">Download</button>
                        </div>
                    </div>
                </div>
            </div>
            <div id="iconmanager-panel-icons" class="iconmanager-tab-panel" style="display:none;">
                <div class="card" style="margin-top:0;">
                    <h2 style="display:flex;align-items:center;gap:12px;">📋 <?php _e( 'Verfügbare Icons', 'iconmanager' ); ?> (<span id="iconmanager-count-total"><?php echo count( $icons['brands'] ) + count( $icons['ui'] ); ?></span>)
                    <span style="flex:1"></span>
                    <input type="search" id="iconmanager-filter" placeholder="<?php esc_attr_e('Filter...','iconmanager'); ?>" style="max-width:220px;">
                    </h2>
                    <h3 style="margin-top:20px;">🏢 Brand (<span id="iconmanager-count-brand"><?php echo count( $icons['brands'] ); ?></span>)</h3>
                    <div id="iconmanager-grid-brands" class="iconmanager-grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(120px,1fr));gap:8px;margin-bottom:30px;">
                        <?php foreach ( $icons['brands'] as $icon ): ?>
                            <div class="iconmanager-tile" data-icon="<?php echo esc_attr($icon); ?>" data-type="brand" style="padding:8px;border:1px solid #ddd;border-radius:4px;text-align:center;">
                                <?php echo iconmanager_render_icon( $icon, 'brand', 24, null, [ 'class' => 'icon' ] ); ?>
                                <br><small><?php echo esc_html( $icon ); ?></small>
                                <?php if ( $show_import ): ?>
                                <br><button class="button button-small iconmanager-import-single" data-icon="<?php echo esc_attr($icon); ?>" data-type="brand" style="margin-top:4px;">⬆ <?php _e('Import','iconmanager'); ?></button>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <h3>🎛️ UI (<span id="iconmanager-count-ui"><?php echo count( $icons['ui'] ); ?></span>)</h3>
                    <div id="iconmanager-grid-ui" class="iconmanager-grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(120px,1fr));gap:8px;">
                        <?php foreach ( $icons['ui'] as $icon ): ?>
                            <div class="iconmanager-tile" data-icon="<?php echo esc_attr($icon); ?>" data-type="ui" style="padding:8px;border:1px solid #ddd;border-radius:4px;text-align:center;">
                                <?php echo iconmanager_render_icon( $icon, 'ui', 24, null, [ 'class' => 'icon' ] ); ?>
                                <br><small><?php echo esc_html( $icon ); ?></small>
                                <?php if ( $show_import ): ?>
                                <br><button class="button button-small iconmanager-import-single" data-icon="<?php echo esc_attr($icon); ?>" data-type="ui" style="margin-top:4px;">⬆ <?php _e('Import','iconmanager'); ?></button>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div style="margin-top:25px;font-size:12px;color:#666;">
        <?php echo wp_kses_post( sprintf( __( 'Quellen: Brand Icons via %1$s (Simple Icons Project, CC0) & UI Icons via %2$s (Lucide, ISC). Bitte Credits respektieren.', 'iconmanager' ), '<a href="https://simpleicons.org" target="_blank" rel="noopener">simpleicons.org</a>', '<a href="https://lucide.dev" target="_blank" rel="noopener">lucide.dev</a>' ) ); ?>
    </div>

    <script>
    jQuery(function($){
        window.iconmanagerShowImport = <?php echo $show_import ? 'true' : 'false'; ?>;
        var lastFilter = '';
        function applyFilter(){
            var q = lastFilter.toLowerCase();
            $('.iconmanager-grid .iconmanager-tile').each(function(){
                var name = $(this).data('icon');
                if(!q || name.indexOf(q) !== -1){
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }
        $('#iconmanager-filter').on('input', function(){
            lastFilter = $(this).val();
            applyFilter();
        });
        $('.iconmanager-tab-button').on('click', function(){
            var target = $(this).data('target');
            $('.iconmanager-tab-button').removeClass('active');
            $(this).addClass('active');
            $('.iconmanager-tab-panel').hide();
            $(target).show();
        });
        function simplePost(action,data,cb){ $.post(ajaxurl,Object.assign({action:action},data||{}),function(r){ cb && cb(r); }); }
        function notify(result){ if(result && typeof result==='object'){ alert(result.success ? (result.data||'OK') : (result.data||'Error')); } }
        function refreshIcons(){
            $.post(ajaxurl,{action:'iconmanager_get_icons'},function(r){
                if(!r.success) return;
                $('#iconmanager-grid-brands').html(r.data.brands_html);
                $('#iconmanager-grid-ui').html(r.data.ui_html);
                $('#iconmanager-count-brand').text(r.data.counts.brand);
                $('#iconmanager-count-ui').text(r.data.counts.ui);
                $('#iconmanager-count-total').text(r.data.counts.total);
                bindSingleImports();
                applyImportVisibility();
                applyFilter();
            });
        }
        $('#iconmanager-download-common-brands').on('click',function(){ const btn=$(this); btn.prop('disabled',true).text('…'); simplePost('iconmanager_download_common_brands',{},function(r){ notify(r); btn.prop('disabled',false).text('<?php echo esc_js(__('Alle Brand Icons laden','iconmanager')); ?>'); refreshIcons(); }); });
        $('#iconmanager-download-common-ui').on('click',function(){ const btn=$(this); btn.prop('disabled',true).text('…'); simplePost('iconmanager_download_common_ui',{},function(r){ notify(r); btn.prop('disabled',false).text('<?php echo esc_js(__('Alle UI Icons laden','iconmanager')); ?>'); refreshIcons(); }); });
        $('#iconmanager-download-single-brand').on('click',function(){ const n=$('#iconmanager-single-brand').val(); if(!n){alert('Name?');return;} const b=$(this); b.prop('disabled',true).text('…'); simplePost('iconmanager_download_brand_icon',{icon_name:n},function(r){ notify(r); b.prop('disabled',false).text('Download'); refreshIcons(); }); });
        $('#iconmanager-download-single-ui').on('click',function(){ const n=$('#iconmanager-single-ui').val(); if(!n){alert('Name?');return;} const b=$(this); b.prop('disabled',true).text('…'); simplePost('iconmanager_download_ui_icon',{icon_name:n},function(r){ notify(r); b.prop('disabled',false).text('Download'); refreshIcons(); }); });

        $('#iconmanager-help-toggle').on('click',function(e){ e.preventDefault(); $('#iconmanager-help').slideToggle(150); });
        $('#iconmanager-options-toggle').on('click',function(e){ e.preventDefault(); $('#iconmanager-options').slideToggle(150); });
        $('#iconmanager-licenses-toggle').on('click',function(e){ e.preventDefault(); $('#iconmanager-licenses').slideToggle(150); });

        function bindSingleImports(){
            $('.iconmanager-import-single').off('click').on('click',function(e){
                e.preventDefault(); const btn=$(this); const icon=btn.data('icon'); const type=btn.data('type');
                btn.prop('disabled',true).text('…');
                $.post(ajaxurl,{action:'iconmanager_import_single',icon:icon,type:type},function(r){
                    alert(r.success ? r.data : (r.data||'Error'));
                    btn.prop('disabled',false).text('⬆ <?php echo esc_js(__('Import','iconmanager')); ?>');
                });
            });
        }
        bindSingleImports();

        function applyImportVisibility(){
            if(window.iconmanagerShowImport){
                $('.iconmanager-tile').each(function(){
                    if($(this).find('.iconmanager-import-single').length===0){
                        const icon=$(this).data('icon'); const type=$(this).data('type');
                        $('<br><button class="button button-small iconmanager-import-single" data-icon="'+icon+'" data-type="'+type+'" style="margin-top:4px;">⬆ <?php echo esc_js(__('Import','iconmanager')); ?></button>').appendTo($(this));
                    }
                });
                bindSingleImports();
            } else {
                $('.iconmanager-import-single').remove();
            }
        }
        applyImportVisibility();

        $('#iconmanager-import-all-media').on('click',function(e){
            e.preventDefault(); const btn=$(this); const status=$('#iconmanager-import-all-status');
            btn.prop('disabled',true).text('<?php echo esc_js(__('Import läuft…','iconmanager')); ?>'); status.text('');
            $.post(ajaxurl,{action:'iconmanager_import_all'},function(r){
                if(r.success){ let ok=0; r.data.forEach(function(x){ if(Number.isInteger(x.result)) ok++; }); status.text(ok+' <?php echo esc_js(__('Icons importiert','iconmanager')); ?>'); refreshIcons(); }
                else { status.text(r.data||'Error'); }
                btn.prop('disabled',false).text('🗂️ <?php echo esc_js(__('Alle Icons in Mediathek importieren','iconmanager')); ?>');
            });
        });

        $('#iconmanager-options-form').on('submit',function(e){
            e.preventDefault(); const frm=$(this); const btn=frm.find('button[type="submit"]');
            btn.prop('disabled',true).text('<?php echo esc_js(__('Speichern…','iconmanager')); ?>');
            $.post(ajaxurl,frm.serialize()+'&action=iconmanager_save_options',function(r){
                alert(r.success ? '<?php echo esc_js(__('Gespeichert','iconmanager')); ?>' : (r.data||'Error'));
                btn.prop('disabled',false).text('💾 <?php echo esc_js(__('Speichern','iconmanager')); ?>');
                if(r.success){ window.iconmanagerShowImport = frm.find('input[name="show_import"]').is(':checked'); applyImportVisibility(); }
            });
        });

        $('#iconmanager-copy-credit').on('click',function(e){
            e.preventDefault();
            const txt=$('#iconmanager-credit-snippet').val();
            const done=()=>{ $('#iconmanager-copy-status').text('<?php echo esc_js(__('Kopiert','iconmanager')); ?>'); setTimeout(()=>$('#iconmanager-copy-status').text(''),2500); };
            if(navigator.clipboard){ navigator.clipboard.writeText(txt).then(done); } else { $('#iconmanager-credit-snippet').select(); document.execCommand('copy'); done(); }
        });
    });
    </script>
    <?php
}

// AJAX: Speichere Optionen
add_action( 'wp_ajax_iconmanager_save_options', function(){
    if ( ! current_user_can( 'manage_options' ) ) wp_die();
    check_admin_referer( 'iconmanager_save_options', 'iconmanager_options_nonce' );
    $show = isset( $_POST['show_import'] ) ? '1' : '0';
    update_option( 'iconmanager_show_import_buttons', $show, false );
    wp_send_json_success();
});

// AJAX: Return icon grids & counts
add_action( 'wp_ajax_iconmanager_get_icons', function(){
    if ( ! current_user_can( 'manage_options' ) ) wp_die();
    $icons = iconmanager_get_available_icons();
    $show_import = get_option( 'iconmanager_show_import_buttons', '1' ) === '1';
    ob_start();
    foreach ( $icons['brands'] as $icon ) {
        echo '<div class="iconmanager-tile" data-icon="' . esc_attr( $icon ) . '" data-type="brand" style="padding:8px;border:1px solid #ddd;border-radius:4px;text-align:center;">';
        echo iconmanager_render_icon( $icon, 'brand', 24, null, [ 'class' => 'icon' ] );
        echo '<br><small>' . esc_html( $icon ) . '</small>';
        if ( $show_import ) {
            echo '<br><button class="button button-small iconmanager-import-single" data-icon="' . esc_attr( $icon ) . '" data-type="brand" style="margin-top:4px;">⬆ ' . esc_html__( 'Import', 'iconmanager' ) . '</button>';
        }
        echo '</div>';
    }
    $brands_html = ob_get_clean();
    ob_start();
    foreach ( $icons['ui'] as $icon ) {
        echo '<div class="iconmanager-tile" data-icon="' . esc_attr( $icon ) . '" data-type="ui" style="padding:8px;border:1px solid #ddd;border-radius:4px;text-align:center;">';
        echo iconmanager_render_icon( $icon, 'ui', 24, null, [ 'class' => 'icon' ] );
        echo '<br><small>' . esc_html( $icon ) . '</small>';
        if ( $show_import ) {
            echo '<br><button class="button button-small iconmanager-import-single" data-icon="' . esc_attr( $icon ) . '" data-type="ui" style="margin-top:4px;">⬆ ' . esc_html__( 'Import', 'iconmanager' ) . '</button>';
        }
        echo '</div>';
    }
    $ui_html = ob_get_clean();
    wp_send_json_success([
        'brands_html' => $brands_html,
        'ui_html' => $ui_html,
        'counts' => [
            'brand' => count( $icons['brands'] ),
            'ui' => count( $icons['ui'] ),
            'total' => count( $icons['brands'] ) + count( $icons['ui'] )
        ]
    ]);
});
