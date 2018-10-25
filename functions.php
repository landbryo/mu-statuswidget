<?php
////////////////////////////////////
// CUSTOM STATUS DASHBOARD WIDGET //
////////////////////////////////////

function dash_widgets() {
    wp_add_dashboard_widget('shelter-stats', 'Stats Year to Date', 'stats_dash');
}
add_action('wp_dashboard_setup', 'dash_widgets');

function stats_dash($blog_id) {
    $site_ids = get_sites();
    foreach($site_ids as $site_id) {
        if ($site_id->blog_id != 1) {
            global $wpdb;

            $posts_path = $wpdb->prefix . $site_id->blog_id . '_posts';
            $options_path = $wpdb->prefix . $site_id->blog_id . '_options';

            $new_year =  date('Y') . '-01-01 00:00:00';

            $ad_count = $wpdb->get_var( "SELECT COUNT(*) FROM ".$posts_path." WHERE post_date >= '".$new_year."' AND post_status = 'adopted'" );
            $sur_count = $wpdb->get_var( "SELECT COUNT(*) FROM ".$posts_path." WHERE post_date >= '".$new_year."' AND post_status = 'surrendered'" );

            switch_to_blog($site_id->blog_id);
            
            $html = '<div class="shelter shelter-' . $current_id . '">';
            $html .= '<h3>' . get_bloginfo('title') . '</h3>';
            $html .= '<table>';
            $html .= '<tr>';
            $html .= '<th>Adopted Pets</th><th>Surrendered Pets</th>';
            $html .= '</tr>';
            $html .= '<tr>';
            $html .= '<td>' . $ad_count . '</td>';
            $html .= '<td>' . $sur_count . '</td>';
            $html .= '</tr>';
            $html .= '</table>';
            $html .= '</div>';

            echo $html;
            restore_current_blog();
        }
    }
}