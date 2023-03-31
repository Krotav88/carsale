<?php
/**
 * @package WordPress
 * @subpackage WL Test Theme
 * @since WL Test Theme 0.1
 */

get_header(); ?>

<article>
    

<?php echo do_shortcode( '[krt_list_car]' );?>
</article>
<?php
if(!empty($_GET['xxx'])){
    echo "get +";
}
get_footer();