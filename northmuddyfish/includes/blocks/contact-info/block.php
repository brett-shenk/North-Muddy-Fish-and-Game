<?php
/**
 * @param    array        $block      The block settings and attributes.
 * @param    string       $content    The block inner HTML (empty).
 * @param    bool         $is_preview True during AJAX preview.
 * @param    (int|string) $post_id    The post ID this block is saved to.
 */

if ( ! function_exists( 'get_field' ) ) {
    return 'Error Advanced Custom Fields plugin is missing.';
}

$type = get_field('cib_what_kind') ? get_field('cib_what_kind') : 'phone';

// WordPress function that generates a lot in the background for us from the block editor
$wrapper_attributes = get_block_wrapper_attributes( [
    "class" => "contact-info-container block-type type-". $type
] );

if( is_admin() ){       // Prevent links from being annoying when in the editor
    $tag = "div";
} else {
    $tag = "a";
}
?>

<div <?php echo $wrapper_attributes; ?>>
    <?php 
    switch($type){
        case 'phone':
        default:
            $phone = get_field('cib_phone_number') ? get_field('cib_phone_number') : '717-777-7777';
            ?>
            <<?php echo $tag; ?> href="tel:<?php echo $phone; ?>">
                <svg xmlns="http://www.w3.org/2000/svg" height="48" width="48"><path d="M39.75 42q-6.1 0-12.125-3T16.8 31.2Q12 26.4 9 20.375 6 14.35 6 8.25q0-.95.65-1.6Q7.3 6 8.25 6h7q.7 0 1.225.475.525.475.675 1.275l1.35 6.3q.1.7-.025 1.275t-.525.975l-5 5.05q2.8 4.65 6.275 8.1Q22.7 32.9 27.1 35.3l4.75-4.9q.5-.55 1.15-.775.65-.225 1.3-.075l5.95 1.3q.75.15 1.25.75T42 33v6.75q0 .95-.65 1.6-.65.65-1.6.65Zm-28.3-23.4 4.05-4.1L14.35 9H9q0 1.95.6 4.275t1.85 5.325ZM29.9 36.75q2.05.95 4.45 1.55 2.4.6 4.65.7v-5.35l-5.15-1.05ZM11.45 18.6ZM29.9 36.75Z"/></svg>
                <span> <?php echo $phone; ?> </span>
            </<?php echo $tag; ?>>
            <?php
            break;
        case 'email':
            $email = get_field('cib_email') ? get_field('cib_email') : 'you-email@some-company.com';
            ?>
            <<?php echo $tag; ?> href="mailto:<?php echo antispambot($email); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" height="48" width="48"><path d="M7 40q-1.2 0-2.1-.9Q4 38.2 4 37V11q0-1.2.9-2.1Q5.8 8 7 8h34q1.2 0 2.1.9.9.9.9 2.1v26q0 1.2-.9 2.1-.9.9-2.1.9Zm17-15.1L7 13.75V37h34V13.75Zm0-3L40.8 11H7.25ZM7 13.75V11v26Z"/></svg>
                <span> <?php echo antispambot($email); ?> </span>
            </<?php echo $tag; ?>>
            <?php
            break;
        case 'link':
            $site_txt = get_field('cbi_website_link_text') ? get_field('cbi_website_link_text') : 'Google';
            $site_link = get_field('cbi_website_link') ? get_field('cbi_website_link') : 'http://google.com/';
            ?>
            <<?php echo $tag; ?> href="<?php echo $site_link; ?>" target="_blank" rel="noreferrer noopener">
                <svg xmlns="http://www.w3.org/2000/svg" height="48" viewBox="0 96 960 960" width="48"><path d="M480 976q-83 0-156-31.5T197 859q-54-54-85.5-127T80 576q0-83 31.5-156T197 293q54-54 127-85.5T480 176q83 0 156 31.5T763 293q54 54 85.5 127T880 576q0 83-31.5 156T763 859q-54 54-127 85.5T480 976Zm-43-61v-82q-35 0-59-26t-24-61v-44L149 497q-5 20-7 39.5t-2 39.5q0 130 84.5 227T437 915Zm294-108q22-24 38.5-51t28-56.5q11.5-29.5 17-60.5t5.5-63q0-106-58-192.5T607 257v18q0 35-24 61t-59 26h-87v87q0 17-13.5 28T393 488h-83v88h258q17 0 28 13t11 30v127h43q29 0 51 17t30 44Z"/></svg>
                <span> <?php echo $site_txt; ?> </span>
            </<?php echo $tag; ?>>
            <?php
            break;
        case 'address':
            $address = get_field('cib_address') ? get_field('cib_address') : '1234 Random Rd, <br /> Somewhere PA 56789';
            $address_url = get_field('cib_location_direction_url');

            if( $address_url ){ ?>
                <<?php echo $tag; ?> href="<?php echo $address_url; ?>" target="_break">
            <?php } else { ?>
                <div>
            <?php } ?>
            
            <svg xmlns="http://www.w3.org/2000/svg" height="48" width="48"><path d="M24 23.5q1.45 0 2.475-1.025Q27.5 21.45 27.5 20q0-1.45-1.025-2.475Q25.45 16.5 24 16.5q-1.45 0-2.475 1.025Q20.5 18.55 20.5 20q0 1.45 1.025 2.475Q22.55 23.5 24 23.5Zm0 16.55q6.65-6.05 9.825-10.975Q37 24.15 37 20.4q0-5.9-3.775-9.65T24 7q-5.45 0-9.225 3.75Q11 14.5 11 20.4q0 3.75 3.25 8.675Q17.5 34 24 40.05ZM24 44q-8.05-6.85-12.025-12.725Q8 25.4 8 20.4q0-7.5 4.825-11.95Q17.65 4 24 4q6.35 0 11.175 4.45Q40 12.9 40 20.4q0 5-3.975 10.875T24 44Zm0-23.6Z"/></svg>
            <span> <?php echo $address; ?> </span>
            
            <?php
            if( $address_url ){ ?>
                </<?php echo $tag; ?>>
            <?php } else { ?>
                </div>
            <?php }

            break;
    }
    ?>
</div>
