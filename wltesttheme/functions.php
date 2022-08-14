<?php

add_action( 'wp_enqueue_scripts', 'add_scripts' );
function add_scripts(){
	wp_enqueue_style( 'style', get_stylesheet_uri() );
}

add_theme_support( 'custom-logo' );
function customize_register_action( $wp_customize ) {   
    $wp_customize->add_setting( 'title_tagline_phone', array(
                'default' => '+3 (777) 777-77-77',
            )
        );
    $wp_customize->add_control( 'title_tagline_phone', array(
            'label'   => 'Телефон',
            'section' => 'title_tagline',
            'type'    => 'text',
        )
    );

	$wp_customize->add_setting( 'title_qty_posts', array(
		'default' => '10',
	)
	);
	$wp_customize->add_control( 'title_qty_posts', array(
		'label'   => 'Кол-во постов на главной',
		'section' => 'title_tagline',
		'type'    => 'number',
	)
	);
}

add_action( 'customize_register', 'customize_register_action' );
add_action( 'init', 'krt_car_init' );

function krt_car_init() {
    register_post_type( 'krt_car',
        array(
            'labels'      => array(
                'name'          => __( 'Автомобили' ),
                'singular_name' => __( 'Автомобиль' ),
				'add_new'		=>__( 'Добавить авто' )
            ),
                'menu_position' => 6,
                'public'        => true,
                'has_archive'   => true,
				'rewrite'       => array( 'slug' => 'cars' ),
				'hierarchical'  => false,
				'has_archive'   => false,
				'supports'      => [ 'title', 'editor', 'thumbnail' ],
				// 'show_in_rest'  => true,
				'menu_icon'     => 'dashicons-car',
        )
    );
	register_taxonomy(
		'brand',
		'krt_car',
		array(
			'label' => __( 'Марка автомобиля' ),
			'rewrite' => array( 'slug' => 'brands' ),
			'hierarchical' => true
		)
	);
	register_taxonomy(
		'countries',
		'krt_car',
		array(
			'label' => __( 'Страна производитель' ),
			'rewrite' => array( 'slug' => 'countries' ),
			'hierarchical' => true
		)
	);
}
add_theme_support( 'post-thumbnails', array( 'krt_car' ) );
add_action( 'admin_enqueue_scripts', 'krt_color_pic');
if ( ! function_exists( 'krt_color_pic' ) ){
    function krt_color_pic($hook) {
        wp_enqueue_media();
        wp_enqueue_style( 'wp-color-picker');
        wp_enqueue_script( 'wp-color-picker');
    }
}

add_action( 'admin_init', 'my_fields' );
function my_fields() {
	add_meta_box( 'extra_fields', 'Дополнительные поля', 'extra_fields_box_page_func', 'krt_car', 'normal', 'high'  );
}

function extra_fields_box_page_func(){
	$post = get_post();
	?>

	<script>
		jQuery(document).ready( function($){
				$('.color_pick').wpColorPicker();
		});
	</script>
	<p><label><input type="text" class="color_pick" name="extra[color]" value="<?php echo get_post_meta($post->ID, 'color', 1); ?>" style="width:200px" /> - Цвет</label></p>

	<p><label><input type="number" name="extra[price]" value="<?php echo get_post_meta($post->ID, 'price', 1); ?>" style="width:200px" /> - Цена</label></p>

	<p><label><input type="number" name="extra[power]" value="<?php echo get_post_meta($post->ID, 'power', 1); ?>" style="width:200px" /> - Мощность (л/с)</label></p>
	

	<p><select name="extra[fuel]" style="width:200px" >
		<?php $sel_v = get_post_meta($post->ID, 'fuel', 1); ?>
		<option value="0">----</option>
		<option value="Бензин" <?php selected( $sel_v, 'Бензин' )?> >Бензин</option>
		<option value="Дизель" <?php selected( $sel_v, 'Дизель' )?> >Дизель</option>
		<option value="Газ/Бензин" <?php selected( $sel_v, 'Газ/Бензин' )?> >Газ/Бензин</option>
		<option value="Электро" <?php selected( $sel_v, 'Электро' )?> >Электро</option>
	</select> - Тип топлива</p>

	<input type="hidden" name="extra_fields_nonce" value="<?php echo wp_create_nonce(__FILE__); ?>" />
	
	<?php
}

add_action( 'save_post', 'my_fields_update', 0 );
function my_fields_update( $post_id ){	
	if (
		   empty( $_POST['extra'] )
		|| ! wp_verify_nonce( $_POST['extra_fields_nonce'], __FILE__ )
		|| wp_is_post_autosave( $post_id )
		|| wp_is_post_revision( $post_id )
	)
	return false;

	$_POST['extra'] = array_map( 'sanitize_text_field', $_POST['extra'] );
	foreach( $_POST['extra'] as $key => $value ){
		if( empty($value) ){
			delete_post_meta( $post_id, $key );
			continue;
		}

		update_post_meta( $post_id, $key, $value );
	}

	return $post_id;
}

add_shortcode('krt_list_car', 'show_list_car' );

function show_list_car($atrb){
	$atrb = shortcode_atts( array (	
		'count' => get_theme_mod('title_qty_posts')
	), $atrb);

	$args = array(
		'post_type' => 'krt_car',
		'post_status' => 'publish',
		'posts_per_page' => $atrb['count'],		
	);

	$show_posts = get_posts( $args );
	$out = '<ul>';
	foreach ( $show_posts as $post){
		setup_postdata( $post );
		$out .= '<li><a href="'. get_the_permalink($post->ID) .'">'. get_the_title($post->ID) .'</a></li>';
	}
	$out .= '</ul>';
	wp_reset_postdata();

	return $out;
}

