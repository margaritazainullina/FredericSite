<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package fr-theme
 */

?>
<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Frederic Lamantia</title>
        <link rel="stylesheet" href="<?php bloginfo( 'template_directory' ); ?>/css/style.css">
		<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
		<link href='https://fonts.googleapis.com/css?family=Noto+Serif:400,700' rel='stylesheet' type='text/css'>
		
		<link href="<?php bloginfo( 'template_directory' ); ?>/owl-carousel/owl.carousel.css" rel="stylesheet">
		<link href="<?php bloginfo( 'template_directory' ); ?>/owl-carousel/owl.theme.css" rel="stylesheet">
        <link href="<?php bloginfo( 'template_directory' ); ?>/owl-carousel/owl.carousel.js" rel="stylesheet">
<!--[if lt IE 9]>
	<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/js/html5.js"></script>
	<![endif]-->
	<?php wp_head(); ?>

<script>
/*$(function(){
$('.lng').mouseenter(function(){
$(".lng_list").slideToggle('fast');
});
$('ul.lng_list li').click(function(){
$(".lng_list").slideUp('fast');
});
})*/
</script>
    </head>
    <body>
        <div class="page">
           <header id="header" class="clearfix">
				<div class="width-page">
					<div class="header-logo">
						<a href="/"><img src="<?php bloginfo( 'template_directory' ); ?>/image/logo.png" alt="Frederic Lamantia"></a>
					</div>
	                <nav class="main-menu">
						<?php wp_nav_menu('primary'); ?>
					</nav>
					<!-- <div id="lng-top">
						<select class="lng">
					        <option><a href="#">FR</a></option>
					        <option><a href="#">EN</a></option>
					        <option><a href="#">RU</a></option>
					    </select>
					</div> -->
                    
					<div class="lng_block" id="lng-top">
                        <?php if ( function_exists( 'mltlngg_display_switcher' ) ) mltlngg_display_switcher(); ?>
<!--
						<div class="lng"><span>EN</span></div>

						<ul class="lng_list">
						<li>EN</li>
						<li>RU</li>
						<li>FR</li>
						</ul>
-->

					</div>
				</div>
            </header>