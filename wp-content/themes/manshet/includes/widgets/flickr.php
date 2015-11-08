<?php
add_action('widgets_init', 'bd_flickr');
function bd_flickr(){
	register_widget('Flickr_Widget');
}
class Flickr_Widget extends WP_Widget {
function Flickr_Widget(){
	$widget_ops = array('classname' => 'flickr', 'description' => 'The most recent photos from flickr.');
	$control_ops = array('id_base' => 'flickr-widget');
	$this->WP_Widget('flickr-widget', theme_name .' - Flickr', $widget_ops, $control_ops);
}
function widget($args, $instance){
    extract($args);
    $title = apply_filters('widget_title', $instance['title']);
    $screen_name = $instance['screen_name'];
    $number = $instance['number'];
    $api = $instance['api'];
    if(empty($api)) {
        $api = 'c9d2c2fda03a2ff487cb4769dc0781ea';
    }
    echo $before_widget;
    if($title) {
        echo $before_title.$title.$after_title;
    }
if($screen_name && $number && $api) {
?>
<script type="text/javascript">
function jsonFlickrApi(rsp) {
   if (rsp.stat != "ok"){
       return;
   }
   var s = "";
   for (var i=0; i < rsp.photos.photo.length; i++) {
       photo = rsp.photos.photo[ i ];
       t_url = "http://farm" + photo.farm +
       ".static.flickr.com/" + photo.server + "/" +
       photo.id + "_" + photo.secret + "_" + "s.jpg";

       p_url = "http://www.flickr.com/photos/" +
       photo.owner + "/" + photo.id;

       s +=  '<div class="post-image"><a style="width: 67px; height: 67px;" href="' + p_url + '">' + '<img style="width: 67px; height: 67px;" alt="'+
       photo.title + '"src="' + t_url + '"/>' + '</a></div>';
   }
   document.write(s);
}
</script>
<script type="text/javascript" src="http://api.flickr.com/services/rest/?format=json&amp;method=flickr.photos.search&amp;user_id=<?php echo $screen_name; ?>&amp;api_key=<?php echo $api; ?>&amp;media=photos&amp;per_page=<?php echo $number; ?>&amp;privacy_filter=1"></script>
<?php
}
    echo $after_widget;
}
function update($new_instance, $old_instance){
    $instance = $old_instance;
    $instance['title'] = strip_tags($new_instance['title']);
    $instance['screen_name'] = $new_instance['screen_name'];
    $instance['number'] = $new_instance['number'];
    $instance['api'] = $new_instance['api'];
    return $instance;
}
function form($instance){
    $defaults = array('title' => 'Photos from Flickr', 'screen_name' => '', 'number' => 8, 'api' => 'c9d2c2fda03a2ff487cb4769dc0781ea');
    $instance = wp_parse_args((array) $instance, $defaults);
    ?>
    <p>
        <label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
        <input style="width: 216px;" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
    </p>
    <p>
        <label for="<?php echo $this->get_field_id('screen_name'); ?>">Flickr ID(<a href="http://idgettr.com/">Get your flickr ID</a>):</label>
        <input style="width: 216px;" id="<?php echo $this->get_field_id('screen_name'); ?>" name="<?php echo $this->get_field_name('screen_name'); ?>" value="<?php echo $instance['screen_name']; ?>" />
        <small>Example : envato ( 52617155@N08 )</small>
    </p>
    <p>
        <label for="<?php echo $this->get_field_id('number'); ?>">Number of photos to show:</label>
        <input style="width: 30px;" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" value="<?php echo $instance['number']; ?>" />
    </p>
    <p>
        <label for="<?php echo $this->get_field_id('api'); ?>">API key (Use default or get your own from <a href="http://www.flickr.com/services/apps/create/apply">Flickr APP Garden</a>):</label>
        <input id="<?php echo $this->get_field_id('api'); ?>" name="<?php echo $this->get_field_name('api'); ?>" value="<?php echo $instance['api']; ?>" />
        <small>Default key is: c9d2c2fda03a2ff487cb4769dc0781ea</small>
    </p>
<?php
}

}