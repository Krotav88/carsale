<?php
/**
 * Header
 *
 * @package WordPress
 * @subpackage WL Test Theme
 * @since WL Test Theme 0.1
 */

?>
<!doctype html>
<html>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title><?php bloginfo('name'); ?><?php wp_title(); ?></title>
	<?php wp_head(); ?>
</head>

<body>
	<div class="header">
		<div class="logo">
			<?php the_custom_logo(); ?>
		</div>
		<div class="support">
			<?php echo get_theme_mod('title_tagline_phone'); ?>
		</div>
	</div>