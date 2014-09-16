<?php

class CAPL_Helper {

    public static function validate_html_color($color) {
        if (preg_match('/^#[a-f0-9]{6}$/i', $color)) {
            return $color;
        } else if (preg_match('/^[a-f0-9]{6}$/i', $color)) {
            $color = '#' . $color;
            return $color;
        }

        $named = array('transparent', 'aliceblue', 'antiquewhite', 'aqua', 'aquamarine', 'azure', 'beige', 'bisque', 'black', 'blanchedalmond', 'blue', 'blueviolet', 'brown', 'burlywood', 'cadetblue', 'chartreuse', 'chocolate', 'coral', 'cornflowerblue', 'cornsilk', 'crimson', 'cyan', 'darkblue', 'darkcyan', 'darkgoldenrod', 'darkgray', 'darkgreen', 'darkkhaki', 'darkmagenta', 'darkolivegreen', 'darkorange', 'darkorchid', 'darkred', 'darksalmon', 'darkseagreen', 'darkslateblue', 'darkslategray', 'darkturquoise', 'darkviolet', 'deeppink', 'deepskyblue', 'dimgray', 'dodgerblue', 'firebrick', 'floralwhite', 'forestgreen', 'fuchsia', 'gainsboro', 'ghostwhite', 'gold', 'goldenrod', 'gray', 'green', 'greenyellow', 'honeydew', 'hotpink', 'indianred', 'indigo', 'ivory', 'khaki', 'lavender', 'lavenderblush', 'lawngreen', 'lemonchiffon', 'lightblue', 'lightcoral', 'lightcyan', 'lightgoldenrodyellow', 'lightgreen', 'lightgrey', 'lightpink', 'lightsalmon', 'lightseagreen', 'lightskyblue', 'lightslategray', 'lightsteelblue', 'lightyellow', 'lime', 'limegreen', 'linen', 'magenta', 'maroon', 'mediumaquamarine', 'mediumblue', 'mediumorchid', 'mediumpurple', 'mediumseagreen', 'mediumslateblue', 'mediumspringgreen', 'mediumturquoise', 'mediumvioletred', 'midnightblue', 'mintcream', 'mistyrose', 'moccasin', 'navajowhite', 'navy', 'oldlace', 'olive', 'olivedrab', 'orange', 'orangered', 'orchid', 'palegoldenrod', 'palegreen', 'paleturquoise', 'palevioletred', 'papayawhip', 'peachpuff', 'peru', 'pink', 'plum', 'powderblue', 'purple', 'red', 'rosybrown', 'royalblue', 'saddlebrown', 'salmon', 'sandybrown', 'seagreen', 'seashell', 'sienna', 'silver', 'skyblue', 'slateblue', 'slategray', 'snow', 'springgreen', 'steelblue', 'tan', 'teal', 'thistle', 'tomato', 'turquoise', 'violet', 'wheat', 'white', 'whitesmoke', 'yellow', 'yellowgreen');

        if (in_array(strtolower($color), $named)) {
            return $color;
        }

        return false;
    }

    public static function get_post_statuses_default() {
        $default_post_stati = array("publish", "pending", "future", "private", "draft", "trash");

        return self::get_post_statuses($default_post_stati);
    }

    public static function get_post_statuses_custom() {
        $default_post_stati = array("publish", "pending", "future", "private", "draft", "trash");

        return self::get_post_statuses(array(), $default_post_stati);
    }

    private static function get_post_statuses($include = array(), $exclude = array()) {
        $post_stati = get_post_stati($post_stati = array(), "objects");

        $custom_post_statuses = array();

       

        foreach ($post_stati as $post_status):

            if ($post_status->show_in_admin_status_list === false || (sizeof($include) > 0 && !in_array($post_status->name, $include)) || in_array($post_status->name, $exclude)):
                continue;
            endif;

            $handle = "capl-color-" . sanitize_key($post_status->name);
            $custom_post_statuses[$post_status->name] = array("option_handle" => $handle, "label" => $post_status->label, "name" => $post_status->name);

        endforeach;
        ksort($custom_post_statuses);
        return $custom_post_statuses;
    }

}

?>
