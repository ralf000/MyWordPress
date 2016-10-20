<?php 
global $post; 
$location = $post->guid;
header('Location: '.$location);
?>