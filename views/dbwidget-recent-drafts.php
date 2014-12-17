<?php
$args = array('post_type' => 'post',
    'post_status' => array("draft", "pending", "future"),
    //'author' => $GLOBALS['current_user']->ID,
    'posts_per_page' => 5,
    'orderby' => 'modified',
    'order' => 'DESC');

$posts = get_posts($args);



$background_color_draft = get_option(CAPL_Constants::DEPRECATED_SETTING_COLOR_DRAFTS);
$background_color_pending = get_option(CAPL_Constants::DEPRECATED_SETTING_COLOR_PENDING);
$background_color_future = get_option(CAPL_Constants::DEPRECATED_SETTING_COLOR_FUTURE);
?>
<ul>
<?php foreach ($posts as $post): ?>
        <?php
        switch ($post->post_status) {
            case "draft":
                $background_color = $background_color_draft;
                break;
            case "pending":
                $background_color = $background_color_pending;
                break;
            case "future":
                $background_color = $background_color_future;
                break;
            default:
                $background_color = "transparent";
                break;
        }
        ?>
        <li style="border: 1px solid <?php echo $background_color; ?>; padding: 2px;">
            <h4><a href="http://www.plugin.dev/wp-admin/post.php?post=7&amp;action=edit" title="Edit “Draft post”">Draft post</a> <abbr title="2013/06/25 5:34:36 PM">June 25, 2013</abbr></h4><p><?php echo $post->post_title; ?></p>
        </li>
<?php endforeach; ?>
</ul>