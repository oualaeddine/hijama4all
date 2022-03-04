<?php

defined('ABSPATH') or exit('Please don&rsquo;t call the plugin directly. Thanks :)');

//Advanced SECTION=========================================================================
add_settings_section(
    'seopress_setting_section_advanced_advanced', // ID
    '',
    //__("Advanced","wp-seopress"), // Title
    'print_section_info_advanced_advanced', // Callback
    'seopress-settings-admin-advanced-advanced' // Page
);

add_settings_field(
    'seopress_advanced_advanced_tax_desc_editor', // ID
    __('Add WP Editor to taxonomy description textarea', 'wp-seopress'), // Title
    'seopress_advanced_advanced_tax_desc_editor_callback', // Callback
    'seopress-settings-admin-advanced-advanced', // Page
    'seopress_setting_section_advanced_advanced' // Section
);

add_settings_field(
    'seopress_advanced_advanced_category_url', // ID
    __('Remove /category/ in URL', 'wp-seopress'), // Title
    'seopress_advanced_advanced_category_url_callback', // Callback
    'seopress-settings-admin-advanced-advanced', // Page
    'seopress_setting_section_advanced_advanced' // Section
);

add_settings_field(
    'seopress_advanced_advanced_product_cat_url', // ID
    __('Remove /product-category/ in URL', 'wp-seopress'), // Title
    'seopress_advanced_advanced_product_cat_url_callback', // Callback
    'seopress-settings-admin-advanced-advanced', // Page
    'seopress_setting_section_advanced_advanced' // Section
);

add_settings_field(
    'seopress_advanced_advanced_trailingslash', // ID
    __('Disable trailing slash for metas', 'wp-seopress'), // Title
    'seopress_advanced_advanced_trailingslash_callback', // Callback
    'seopress-settings-admin-advanced-advanced', // Page
    'seopress_setting_section_advanced_advanced' // Section
);

add_settings_field(
    'seopress_advanced_advanced_wp_generator', // ID
    __('Remove WordPress generator meta tag', 'wp-seopress'), // Title
    'seopress_advanced_advanced_wp_generator_callback', // Callback
    'seopress-settings-admin-advanced-advanced', // Page
    'seopress_setting_section_advanced_advanced' // Section
);

add_settings_field(
    'seopress_advanced_advanced_hentry', // ID
    __('Remove hentry post class', 'wp-seopress'), // Title
    'seopress_advanced_advanced_hentry_callback', // Callback
    'seopress-settings-admin-advanced-advanced', // Page
    'seopress_setting_section_advanced_advanced' // Section
);

add_settings_field(
    'seopress_advanced_advanced_comments_author_url', // ID
    __('Remove author URL', 'wp-seopress'), // Title
    'seopress_advanced_advanced_comments_author_url_callback', // Callback
    'seopress-settings-admin-advanced-advanced', // Page
    'seopress_setting_section_advanced_advanced' // Section
);

add_settings_field(
    'seopress_advanced_advanced_comments_website', // ID
    __('Remove website field in comment form', 'wp-seopress'), // Title
    'seopress_advanced_advanced_comments_website_callback', // Callback
    'seopress-settings-admin-advanced-advanced', // Page
    'seopress_setting_section_advanced_advanced' // Section
);

add_settings_field(
    'seopress_advanced_advanced_comments_form_link', // ID
    __('Add "nofollow noopener noreferrer" rel attributes to the comments form link', 'wp-seopress'), // Title
    'seopress_advanced_advanced_comments_form_link_callback', // Callback
    'seopress-settings-admin-advanced-advanced', // Page
    'seopress_setting_section_advanced_advanced' // Section
);

add_settings_field(
    'seopress_advanced_advanced_wp_shortlink', // ID
    __('Remove WordPress shortlink meta tag', 'wp-seopress'), // Title
    'seopress_advanced_advanced_wp_shortlink_callback', // Callback
    'seopress-settings-admin-advanced-advanced', // Page
    'seopress_setting_section_advanced_advanced' // Section
);

add_settings_field(
    'seopress_advanced_advanced_wp_wlw', // ID
    __('Remove Windows Live Writer meta tag', 'wp-seopress'), // Title
    'seopress_advanced_advanced_wp_wlw_callback', // Callback
    'seopress-settings-admin-advanced-advanced', // Page
    'seopress_setting_section_advanced_advanced' // Section
);

add_settings_field(
    'seopress_advanced_advanced_wp_rsd', // ID
    __('Remove RSD meta tag', 'wp-seopress'), // Title
    'seopress_advanced_advanced_wp_rsd_callback', // Callback
    'seopress-settings-admin-advanced-advanced', // Page
    'seopress_setting_section_advanced_advanced' // Section
);

add_settings_field(
    'seopress_advanced_advanced_google', // ID
    __('Google site verification', 'wp-seopress'), // Title
    'seopress_advanced_advanced_google_callback', // Callback
    'seopress-settings-admin-advanced-advanced', // Page
    'seopress_setting_section_advanced_advanced' // Section
);

add_settings_field(
    'seopress_advanced_advanced_bing', // ID
    __('Bing site verification', 'wp-seopress'), // Title
    'seopress_advanced_advanced_bing_callback', // Callback
    'seopress-settings-admin-advanced-advanced', // Page
    'seopress_setting_section_advanced_advanced' // Section
);

add_settings_field(
    'seopress_advanced_advanced_pinterest', // ID
    __('Pinterest site verification', 'wp-seopress'), // Title
    'seopress_advanced_advanced_pinterest_callback', // Callback
    'seopress-settings-admin-advanced-advanced', // Page
    'seopress_setting_section_advanced_advanced' // Section
);

add_settings_field(
    'seopress_advanced_advanced_yandex', // ID
    __('Yandex site verification', 'wp-seopress'), // Title
    'seopress_advanced_advanced_yandex_callback', // Callback
    'seopress-settings-admin-advanced-advanced', // Page
    'seopress_setting_section_advanced_advanced' // Section
);

//Appearance SECTION=======================================================================
add_settings_section(
    'seopress_setting_section_advanced_appearance', // ID
    '',
    //__("Appearance","wp-seopress"), // Title
    'print_section_info_advanced_appearance', // Callback
    'seopress-settings-admin-advanced-appearance' // Page
);

add_settings_field(
    'seopress_advanced_appearance_adminbar', // ID
    __('SEO in admin bar', 'wp-seopress'), // Title
    'seopress_advanced_appearance_adminbar_callback', // Callback
    'seopress-settings-admin-advanced-appearance', // Page
    'seopress_setting_section_advanced_appearance' // Section
);

add_settings_field(
    'seopress_advanced_appearance_universal_metabox', // ID
    __('Universal Metabox (Gutenberg)', 'wp-seopress'), // Title
    'seopress_advanced_appearance_universal_metabox_callback', // Callback
    'seopress-settings-admin-advanced-appearance', // Page
    'seopress_setting_section_advanced_appearance' // Section
);
add_settings_field(
    'seopress_advanced_appearance_universal_metabox_disable', // ID
    __('Disable Universal Metabox', 'wp-seopress'), // Title
    'seopress_advanced_appearance_universal_metabox_disable_callback', // Callback
    'seopress-settings-admin-advanced-appearance', // Page
    'seopress_setting_section_advanced_appearance' // Section
);

add_settings_field(
    'seopress_advanced_appearance_adminbar_noindex', // ID
    __('Noindex in admin bar', 'wp-seopress'), // Title
    'seopress_advanced_appearance_adminbar_noindex_callback', // Callback
    'seopress-settings-admin-advanced-appearance', // Page
    'seopress_setting_section_advanced_appearance' // Section
);

add_settings_field(
    'seopress_advanced_appearance_metabox_position', // ID
    __("Move SEO metabox's position", 'wp-seopress'), // Title
    'seopress_advanced_appearance_metaboxe_position_callback', // Callback
    'seopress-settings-admin-advanced-appearance', // Page
    'seopress_setting_section_advanced_appearance' // Section
);

if (is_plugin_active('wp-seopress-pro/seopress-pro.php')) {
    add_settings_field(
        'seopress_advanced_appearance_schema_default_tab', // ID
        __('Set default tab for Structured data metabox', 'wp-seopress'), // Title
        'seopress_advanced_appearance_schema_default_tab_callback', // Callback
        'seopress-settings-admin-advanced-appearance', // Page
        'seopress_setting_section_advanced_appearance' // Section
    );
}

add_settings_field(
    'seopress_advanced_appearance_notifications', // ID
    __('Hide Notifications Center', 'wp-seopress'), // Title
    'seopress_advanced_appearance_notifications_callback', // Callback
    'seopress-settings-admin-advanced-appearance', // Page
    'seopress_setting_section_advanced_appearance' // Section
);

add_settings_field(
    'seopress_advanced_appearance_news', // ID
    __('Hide SEO News', 'wp-seopress'), // Title
    'seopress_advanced_appearance_news_callback', // Callback
    'seopress-settings-admin-advanced-appearance', // Page
    'seopress_setting_section_advanced_appearance' // Section
);

add_settings_field(
    'seopress_advanced_appearance_seo_tools', // ID
    __('Hide Site Overview', 'wp-seopress'), // Title
    'seopress_advanced_appearance_seo_tools_callback', // Callback
    'seopress-settings-admin-advanced-appearance', // Page
    'seopress_setting_section_advanced_appearance' // Section
);

add_settings_field(
    'seopress_advanced_appearance_title_col', // ID
    __('Show Title tag column in post types', 'wp-seopress'), // Title
    'seopress_advanced_appearance_title_col_callback', // Callback
    'seopress-settings-admin-advanced-appearance', // Page
    'seopress_setting_section_advanced_appearance' // Section
);

add_settings_field(
    'seopress_advanced_appearance_meta_desc_col', // ID
    __('Show Meta description column in post types', 'wp-seopress'), // Title
    'seopress_advanced_appearance_meta_desc_col_callback', // Callback
    'seopress-settings-admin-advanced-appearance', // Page
    'seopress_setting_section_advanced_appearance' // Section
);

add_settings_field(
    'seopress_advanced_appearance_redirect_enable_col', // ID
    __('Show Redirection Enable column in post types', 'wp-seopress'), // Title
    'seopress_advanced_appearance_redirect_enable_col_callback', // Callback
    'seopress-settings-admin-advanced-appearance', // Page
    'seopress_setting_section_advanced_appearance' // Section
);

add_settings_field(
    'seopress_advanced_appearance_redirect_url_col', // ID
    __('Show Redirect URL column in post types', 'wp-seopress'), // Title
    'seopress_advanced_appearance_redirect_url_col_callback', // Callback
    'seopress-settings-admin-advanced-appearance', // Page
    'seopress_setting_section_advanced_appearance' // Section
);

add_settings_field(
    'seopress_advanced_appearance_canonical', // ID
    __('Show canonical URL column in post types', 'wp-seopress'), // Title
    'seopress_advanced_appearance_canonical_callback', // Callback
    'seopress-settings-admin-advanced-appearance', // Page
    'seopress_setting_section_advanced_appearance' // Section
);

add_settings_field(
    'seopress_advanced_appearance_target_kw_col', // ID
    __('Show Target Keyword column in post types', 'wp-seopress'), // Title
    'seopress_advanced_appearance_target_kw_col_callback', // Callback
    'seopress-settings-admin-advanced-appearance', // Page
    'seopress_setting_section_advanced_appearance' // Section
);

add_settings_field(
    'seopress_advanced_appearance_noindex_col', // ID
    __('Show noindex column in post types', 'wp-seopress'), // Title
    'seopress_advanced_appearance_noindex_col_callback', // Callback
    'seopress-settings-admin-advanced-appearance', // Page
    'seopress_setting_section_advanced_appearance' // Section
);

add_settings_field(
    'seopress_advanced_appearance_nofollow_col', // ID
    __('Show nofollow column in post types', 'wp-seopress'), // Title
    'seopress_advanced_appearance_nofollow_col_callback', // Callback
    'seopress-settings-admin-advanced-appearance', // Page
    'seopress_setting_section_advanced_appearance' // Section
);

add_settings_field(
    'seopress_advanced_appearance_words_col', // ID
    __('Show total number of words column in post types', 'wp-seopress'), // Title
    'seopress_advanced_appearance_words_col_callback', // Callback
    'seopress-settings-admin-advanced-appearance', // Page
    'seopress_setting_section_advanced_appearance' // Section
);

if (is_plugin_active('wp-seopress-pro/seopress-pro.php')) {
    add_settings_field(
        'seopress_advanced_appearance_ps_col', // ID
        __('Show Google Page Speed column in post types', 'wp-seopress'), // Title
        'seopress_advanced_appearance_ps_col_callback', // Callback
        'seopress-settings-admin-advanced-appearance', // Page
        'seopress_setting_section_advanced_appearance' // Section
    );
}

if (is_plugin_active('wp-seopress-insights/seopress-insights.php')) {
    add_settings_field(
        'seopress_advanced_appearance_insights_col', // ID
        __('Show Insights column in post types', 'wp-seopress'), // Title
        'seopress_advanced_appearance_insights_col_callback', // Callback
        'seopress-settings-admin-advanced-appearance', // Page
        'seopress_setting_section_advanced_appearance' // Section
    );
}

add_settings_field(
    'seopress_advanced_appearance_score_col', // ID
    __('Show content analysis score column in post types', 'wp-seopress'), // Title
    'seopress_advanced_appearance_score_col_callback', // Callback
    'seopress-settings-admin-advanced-appearance', // Page
    'seopress_setting_section_advanced_appearance' // Section
);

add_settings_field(
    'seopress_advanced_appearance_ca_metaboxe', // ID
    __('Remove Content Analysis Metabox', 'wp-seopress'), // Title
    'seopress_advanced_appearance_ca_metaboxe_callback', // Callback
    'seopress-settings-admin-advanced-appearance', // Page
    'seopress_setting_section_advanced_appearance' // Section
);

add_settings_field(
    'seopress_advanced_appearance_genesis_seo_metaboxe', // ID
    __('Hide Genesis SEO Metabox', 'wp-seopress'), // Title
    'seopress_advanced_appearance_genesis_seo_metaboxe_callback', // Callback
    'seopress-settings-admin-advanced-appearance', // Page
    'seopress_setting_section_advanced_appearance' // Section
);

add_settings_field(
    'seopress_advanced_appearance_genesis_seo_menu', // ID
    __('Hide Genesis SEO Settings link', 'wp-seopress'), // Title
    'seopress_advanced_appearance_genesis_seo_menu_callback', // Callback
    'seopress-settings-admin-advanced-appearance', // Page
    'seopress_setting_section_advanced_appearance' // Section
);

add_settings_field(
    'seopress_advanced_appearance_advice_schema', // ID
    __('Hide advice in Structured Data Types metabox', 'wp-seopress'), // Title
    'seopress_advanced_appearance_advice_schema_callback', // Callback
    'seopress-settings-admin-advanced-appearance', // Page
    'seopress_setting_section_advanced_appearance' // Section
);

//Security SECTION=======================================================================
add_settings_section(
    'seopress_setting_section_advanced_security', // ID
    '',
    //__("Security","wp-seopress"), // Title
    'print_section_info_advanced_security', // Callback
    'seopress-settings-admin-advanced-security' // Page
);

add_settings_field(
    'seopress_advanced_security_metaboxe_role', // ID
    __('Block SEO metabox to user roles', 'wp-seopress'), // Title
    'seopress_advanced_security_metaboxe_role_callback', // Callback
    'seopress-settings-admin-advanced-security', // Page
    'seopress_setting_section_advanced_security' // Section
);

add_settings_section(
    'seopress_setting_section_advanced_security_roles', // ID
    '',
    //__("Security","wp-seopress"), // Title
    'print_section_info_advanced_security_roles', // Callback
    'seopress-settings-admin-advanced-security' // Page
);

add_settings_field(
    'seopress_advanced_security_metaboxe_ca_role', // ID
    __('Block Content analysis metabox to user roles', 'wp-seopress'), // Title
    'seopress_advanced_security_metaboxe_ca_role_callback', // Callback
    'seopress-settings-admin-advanced-security', // Page
    'seopress_setting_section_advanced_security' // Section
);

seopress_get_service('SectionPagesSEOPress')->printSectionPages();
