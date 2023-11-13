<?php
/**
 * @param    array        $block      The block settings and attributes.
 * @param    string       $content    The block inner HTML (empty).
 * @param    bool         $is_preview True during AJAX preview.
 * @param    (int|string) $post_id    The post ID this block is saved to.
 */

// WordPress function that generates a lot in the background for us from the block editor
$wrapper_attributes = get_block_wrapper_attributes( [
    "class" => "slick-slider-container"
] );

if ( ! function_exists( 'get_field' ) ) {
    return 'Error Advanced Custom Fields plugin is missing.';
}

// Generate a unique ID
$count = 20;
$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
$id = '';
for ($i = 0; $i < $count; $i++) {
    $index = rand(0, strlen($characters) - 1);
    $id .= $characters[$index];
}
$id = 'a'.$id;      // Make sure the ID doesn't start with a number

$dots =             !empty(get_field("bs_dots"))                   ? get_field("bs_dots")                 : true;
$arrows =           !empty(get_field("bs_arrows"))                 ? get_field("bs_arrows")               : true;
$arrowStyle =       !empty(get_field("bs_arrow_style"))            ? get_field("bs_arrow_style")          : 'block';
$autoplay =         !empty(get_field("bs_autoplay"))               ? get_field("bs_autoplay")             : false;
$speed =            !empty(get_field("bs_autoplay_speed"))         ? get_field("bs_autoplay_speed")       : 5000;
$slidesShow =       !empty(get_field("bs_slides_to_show"))         ? get_field("bs_slides_to_show")       : 1;
$slidesScroll =     !empty(get_field("bs_slides_to_scroll"))       ? get_field("bs_slides_to_scroll")     : 1;
$infinite =         !empty(get_field("bs_infinite_loop"))          ? get_field("bs_infinite_loop")        : false;
$space_between =    !empty(get_field("bs_space_between_slides"))   ? get_field("bs_space_between_slides") : '';
$same_height =      !empty(get_field("bs_same_height"))            ? get_field("bs_same_height")          : false;

if( empty($space_between) ){
    $space_between = 0;
}

// Data to be passed to JavaScript
$data_content = 'data-slidesToShow="'. $slidesShow .'"';
$data_content .= ' data-space-between="'. $space_between .'"';
$data_content .= ' data-same-height="'. $same_height .'"';

?>
<div id="<?php echo $id; ?>" <?php echo $wrapper_attributes; ?>>
    <div class="slick-slider-inner-container arrow-style-<?php echo $arrowStyle; ?>" <?php echo $data_content; ?>>
        <InnerBlocks 
            template="<?php echo esc_attr( wp_json_encode( array( array( "shenk/slick-slider-content" ) ) ) ); ?>"  
            allowedBlocks="<?php echo esc_attr( wp_json_encode( array( "shenk/slick-slider-content" ) ) ); ?>"
        />
        <div class="previous-slideshow">
            <i class="fa fa-angle-left"></i>
        </div>
        <div class="next-slideshow">
            <i class="fa fa-angle-right"></i>
        </div>
    </div>
</div>

<?php if( !is_admin() ){ ?>
<script type="module">
jQuery(document).ready(function() {
    jQuery('#<?php echo $id; ?>').children('.slick-slider-inner-container').children(".acf-innerblocks-container").slick({
        arrows: <?php echo $arrows; ?>,
        dots: <?php echo $dots; ?>,
        infinite: <?php echo $infinite; ?>,
        cssEase: "linear",
        slidesToShow: <?php echo $slidesShow; ?>,
        slidesToScroll: <?php echo $slidesScroll; ?>,
        autoplay: <?php echo $autoplay; ?>,

        <?php if( $autoplay != "false" ){ ?>
        autoplaySpeed: <?php echo $speed; ?>,
        <?php }

        // Dynamically assign a responsive breakpoint based on:  Slides to Show  &  Slides to Scroll
        switch( $slidesShow ){
            case 10:
            case 9:
            case 8:
            case 7:
            case 6: ?>
        responsive: [
            {
                breakpoint: 1199,
                settings: {
                    slidesToShow: 5,
                    slidesToScroll: <?php $slidesScroll >= 5 ? 5 : ($slidesScroll == 4 ? 4 : ($slidesScroll == 3 ? 3 : ($slidesScroll == 2 ? 2 : 1))); ?>
                }
            },
            {
                breakpoint: 1023,
                settings: {
                    slidesToShow: 4,
                    slidesToScroll: <?php echo $slidesScroll >= 4 ? 4 : ($slidesScroll == 3 ? 3 : ($slidesScroll == 2 ? 2 : 1 )); ?>
                }
            },
            {
                breakpoint: 850,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: <?php echo $slidesScroll >= 3 ? 3 : ($slidesScroll == 2 ? 2 : 1); ?>
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: <?php echo ( $slidesScroll >= 2 ? 2 : 1 ); ?>
                }
            },
            {
                breakpoint: 550,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                }
            }
        ]
        <?php break;
            case 5: ?>
        responsive: [
            {
                breakpoint: 1023,
                settings: {
                    slidesToShow: 4,
                    slidesToScroll: <?php echo $slidesScroll >= 4 ? 4 : ($slidesScroll == 3 ? 3 : ($slidesScroll == 2 ? 2 : 1 )); ?>
                }
            },
            {
                breakpoint: 850,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: <?php echo $slidesScroll >= 3 ? 3 : ($slidesScroll == 2 ? 2 : 1); ?>
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: <?php echo ( $slidesScroll >= 2 ? 2 : 1 ); ?>
                }
            },
            {
                breakpoint: 550,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                }
            }
        ]
        <?php break;
            case 4: ?>
        responsive: [
            {
                breakpoint: 850,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: <?php echo $slidesScroll >= 3 ? 3 : ($slidesScroll == 2 ? 2 : 1); ?>
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: <?php echo ( $slidesScroll >= 2 ? 2 : 1 ); ?>
                }
            },
            {
                breakpoint: 550,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                }
            }
        ]
        <?php break;
            case 3: ?>
        responsive: [
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: <?php echo ( $slidesScroll >= 2 ? 2 : 1 ); ?>
                }
            },
            {
                breakpoint: 550,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                }
            }
        ]
        <?php break;
            case 2: ?>
        responsive: [
            {
                breakpoint: 550,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                }
            }
        ]
        <?php break;
            default:
            case 1:
        // Nothing
        break;
        } ?>
    });
});
</script>
<?php } ?>
