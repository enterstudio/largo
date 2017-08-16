<?php
/**
 * Customizer settings.
 *
 * @package Largo
 */

/**
 * Register additional scripts.
 *
 * @param obj $wp_customize Theme Customizer object.
 */
function largo_customize_additional_scripts( $wp_customize ) {

	// Site Description.
	$wp_customize->add_setting(
		'site_blurb',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_html',
		)
	);
	$wp_customize->add_control(
		'site_blurb',
		array(
			'label'       => esc_html__( 'Site Description', 'largo' ),
			'description' => esc_html__( 'Enter a short blurb about your site. This is used in a sidebar widget.', 'largo' ),
			'section'     => 'basic_settings',
			'type'        => 'textarea',
		)
	);

	// Feed URL.
	$wp_customize->add_setting(
		'rss_link',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_attr',
		)
	);
	$wp_customize->add_control(
		'rss_link',
		array(
			'label'       => esc_html__( 'Feed URL', 'largo' ),
			'description' => esc_html__( 'Enter the URL for your primary RSS feed. You can override the default if you use Feedburner or some other service to generate or track your RSS feed
Donate Button.', 'largo' ),
			'section'     => 'basic_settings',
			'type'        => 'url',
		)
	);

	// Show Donate Button Toggle.
	$wp_customize->add_setting(
		'show_donate_button',
		array(
			'default'           => '',
		)
	);
	$wp_customize->add_control(
		'show_donate_button',
		array(
			'label'       => esc_html__( 'Show Site Description?', 'largo' ),
			'description' => esc_html__( 'Enter the text for the donate button (e.g. - Support Us).', 'largo' ),
			'section'     => 'basic_settings',
			'type'        => 'checkbox',
		)
	);

	// Donate Button Link.
	$wp_customize->add_setting(
		'donate_link',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url',
		)
	);
	$wp_customize->add_control(
		'donate_link',
		array(
			'label'       => esc_html__( 'Donate Button Link', 'largo' ),
			'description' => esc_html__( 'Enter the link to your donation page or form (include http://).', 'largo' ),
			'section'     => 'basic_settings',
			'type'        => 'url',
		)
	);

	// Donate Button Text.
	$wp_customize->add_setting(
		'donate_button_text',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_attr',
		)
	);
	$wp_customize->add_control(
		'donate_button_text',
		array(
			'label'       => esc_html__( 'Donate Button Text', 'largo' ),
			'description' => esc_html__( 'Enter a short blurb about your site. This is used in a sidebar widget.', 'largo' ),
			'section'     => 'basic_settings',
			'type'        => 'textarea',
		)
	);

	// Copyright Message.
	$wp_customize->add_setting(
		'copyright_msg',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_html',
		)
	);
	$wp_customize->add_control(
		'copyright_msg',
		array(
			'label'       => esc_html__( 'Copyright Message', 'largo' ),
			'description' => esc_html__( 'Enter the <strong>copyright and credit information</strong> to display in the footer. You can use <code>%d</code> to output the current year.', 'largo' ),
			'section'     => 'basic_settings',
			'type'        => 'textarea',
		)
	);

	// Word to use for "Post".
	$wp_customize->add_setting(
		'posts_term_singular',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_attr',
		)
	);
	$wp_customize->add_control(
		'posts_term_singular',
		array(
			'label'       => esc_html__( 'Word to use for "Post" (single)', 'largo' ),
			'description' => esc_html__( 'WordPress calls single article pages "posts" but you might prefer to use another name. Enter the singular and plural forms of the word you want to use here.', 'largo' ),
			'section'     => 'basic_settings',
			'type'        => 'text',
		)
	);

	// Word to use for "Posts" (plural).
	$wp_customize->add_setting(
		'posts_term_plural',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_attr',
		)
	);
	$wp_customize->add_control(
		'posts_term_plural',
		array(
			'label'       => esc_html__( 'Word to use for "Post" (plural)', 'largo' ),
			'section'     => 'basic_settings',
			'type'        => 'text',
		)
	);

	// Link to Facebook Page.
	$wp_customize->add_setting(
		'facebook_link',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url',
		)
	);
	$wp_customize->add_control(
		'facebook_link',
		array(
			'label'       => esc_html__( 'Link to Facebook Page', 'largo' ),
			'description' => esc_html__( '(https://www.facebook.com/username)', 'largo' ),
			'section'     => 'basic_settings',
			'type'        => 'url',
		)
	);

	// Link to Twitter Profile.
	$wp_customize->add_setting(
		'twitter_link',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url',
		)
	);
	$wp_customize->add_control(
		'gplus_link',
		array(
			'label'       => esc_html__( 'Link to Twitter Profile', 'largo' ),
			'description' => esc_html__( '(https://twitter.com/username)', 'largo' ),
			'section'     => 'basic_settings',
			'type'        => 'url',
		)
	);

	// Link to Google+ Page.
	$wp_customize->add_setting(
		'twitter_link',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url',
		)
	);
	$wp_customize->add_control(
		'gplus_link',
		array(
			'label'       => esc_html__( 'Link to Google+ Page', 'largo' ),
			'description' => esc_html__( '(https://plus.google.com/userID/)', 'largo' ),
			'section'     => 'basic_settings',
			'type'        => 'url',
		)
	);

	// Link to YouTube Channel.
	$wp_customize->add_setting(
		'youtube_link',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url',
		)
	);
	$wp_customize->add_control(
		'youtube_link',
		array(
			'label'       => esc_html__( 'Link to YouTube Channel', 'largo' ),
			'description' => esc_html__( '(http://www.youtube.com/user/username)', 'largo' ),
			'section'     => 'basic_settings',
			'type'        => 'url',
		)
	);

	// Link to Instagram Page.
	$wp_customize->add_setting(
		'instagram_link',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url',
		)
	);
	$wp_customize->add_control(
		'instagram_link',
		array(
			'label'       => esc_html__( 'Link to Instagram Page', 'largo' ),
			'description' => esc_html__( '(http://instagram.com/username)', 'largo' ),
			'section'     => 'basic_settings',
			'type'        => 'url',
		)
	);

	// Link to LinkedIn Group or Profile.
	$wp_customize->add_setting(
		'linkedin_link',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url',
		)
	);
	$wp_customize->add_control(
		'linkedin_link',
		array(
			'label'       => esc_html__( 'Link to LinkedIn Group or Profile', 'largo' ),
			'description' => esc_html__( '(http://www.linkedin.com/in/username/)', 'largo' ),
			'section'     => 'basic_settings',
			'type'        => 'url',
		)
	);

	// Link to Tumblr.
	$wp_customize->add_setting(
		'tumblr_link',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url',
		)
	);
	$wp_customize->add_control(
		'tumblr_link',
		array(
			'label'       => esc_html__( 'Link to Tumblr', 'largo' ),
			'description' => esc_html__( '(http://yoursite.tumblr.com)', 'largo' ),
			'section'     => 'basic_settings',
			'type'        => 'url',
		)
	);

	// Link to Pinterest Page.
	$wp_customize->add_setting(
		'pinterest_link',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url',
		)
	);
	$wp_customize->add_control(
		'pinterest_link',
		array(
			'label'       => esc_html__( 'Link to Pinterest Page', 'largo' ),
			'description' => esc_html__( '(http://pinterest.com/username)', 'largo' ),
			'section'     => 'basic_settings',
			'type'        => 'url',
		)
	);

	// Link to Github Page.
	$wp_customize->add_setting(
		'github_link',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url',
		)
	);
	$wp_customize->add_control(
		'github_link',
		array(
			'label'       => esc_html__( 'Link to Github Page', 'largo' ),
			'description' => esc_html__( '(http://github.com/username)', 'largo' ),
			'section'     => 'basic_settings',
			'type'        => 'url',
		)
	);

	// Link to Flickr Photostream.
	$wp_customize->add_setting(
		'flickr_link',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url',
		)
	);
	$wp_customize->add_control(
		'flickr_link',
		array(
			'label'       => esc_html__( 'Link to Flickr Photostream', 'largo' ),
			'description' => esc_html__( '(http://www.flickr.com/photos/username/)', 'largo' ),
			'section'     => 'basic_settings',
			'type'        => 'url',
		)
	);

	// Would you like to display share icons at the top of single posts?
	$wp_customize->add_setting(
		'single_social_icons',
		array(
			'default'           => '',
		)
	);
	$wp_customize->add_control(
		'single_social_icons',
		array(
			'label'       => esc_html__( 'Would you like to display share icons at the top of single posts?', 'largo' ),
			'description' => esc_html__( 'By default social icons appear at the top of single posts but you can choose to not show them at all.', 'largo' ),
			'section'     => 'basic_settings',
			'type'        => 'checkbox',
		)
	);

	// Select the share icons to display at the top of single posts.
	$wp_customize->add_setting(
		'article_utilities',
		array(
			'default'           => '',
		)
	);
	// @TODO this needs to be converted to checkboxes
	$wp_customize->add_control(
		'article_utilities',
		array(
			'label'       => esc_html__( 'Select the share icons to display at the top of single posts.', 'largo' ),
			'section'     => 'basic_settings',
			'type'        => 'radio',
			'choices'     => array(
				'facebook'  => 'Facebook',
				'twitter'   => 'Twitter',
				'email'     => 'Email',
				'print'     => 'Print',
			),
		)
	);

	// Would you like to display share icons in a floating bar beside posts using the single-column post template?
	$wp_customize->add_setting(
		'single_floating_social_icons',
		array(
			'default'           => '',
		)
	);
	$wp_customize->add_control(
		'single_floating_social_icons',
		array(
			'label'       => esc_html__( 'Would you like to display share icons in a floating bar beside posts using the single-column post template?', 'largo' ),
			'section'     => 'basic_settings',
			'type'        => 'checkbox',
		)
	);

	// Verb to use for Facebook buttons?
	$wp_customize->add_setting(
		'fb_verb',
		array(
			'default'           => 'like',
		)
	);
	$wp_customize->add_control(
		'fb_verb',
		array(
			'label'       => esc_html__( 'Verb to use for Facebook buttons?', 'largo' ),
			'section'     => 'basic_settings',
			'type'        => 'select',
			'choices'     => array(
				'like'      => 'Like',
				'recommend' => 'Recommend',
				'share'     => 'Share',
			),
		)
	);
}

add_action( 'customize_register', 'largo_customize_additional_scripts' );
