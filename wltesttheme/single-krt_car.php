<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

get_header();

$power = get_post_meta( get_the_ID(), 'power', true );
$color = get_post_meta( get_the_ID(), 'color', true );
$price = get_post_meta( get_the_ID(), 'price', true );
$fuel = get_post_meta( get_the_ID(), 'fuel', true );
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
<div class="entry-content">
<?php
        the_content();
    ?>
    <div class="img_and_dop">    
        <?php if ( has_post_thumbnail() ) { ?>
        <div class="car_img">
            <?php the_post_thumbnail( 'large' ); ?>

            <?php
                global $post;
                $gallery = get_post_gallery_images( $post ); 
            
                foreach( $gallery as $image_url ) { ?>
                    <img src="<?php echo $image_url; ?>" alt="">                
            <?php } ?>
        </div>
        <?php } ?> 
        <div class="dops">
        <?php 
            the_taxonomies( array(
            'post'          => get_the_ID(),
            'term_template' => '%2$s',
            'before'        => '<div class="pd15">',
            'sep'           => '</div><div class="pd15">',
            'after'         => '</div>',
            ) );
        ?>
        <?php if( $price ){ ?>
        <div class="dop_line">
            <div class="pd15 price dib val">Цена: </div>
            <div class="dib val"><?php echo esc_attr( $price ); ?> $</div>
        </div>
        <?php } ?>
        <?php if( $color ){ ?>
        <div class="dop_line">
            <div class="pd15 color_pick dib val">Цвет: </div>
            <div class="dib val"><span class="color_auto" style="background-color:<?php echo esc_attr( $color ); ?>"></span></div>
        </div>
        <?php } ?>
        <?php if( $power ){ ?>
        <div class="dop_line">
            <div class="pd15 power dib val">Мощность (л/с): </div>
            <div class="dib val"><?php echo esc_attr( $power ); ?></div>
        </div>
        <?php } ?>
        <?php if( $fuel ){ ?>
        <div class="dop_line">
            <div class="pd15 fuel dib val">Тип топлива: 
            <div class="dib val"><?php if( $fuel ) echo esc_attr( $fuel ); ?></div>            
        </div>
        <?php } ?>
        </div>
    </div>
    
</div>

</article>
<?php

get_footer();