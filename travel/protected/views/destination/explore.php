<?php
$document = $model->getDocumentWithDistDur();
if ($model->getResultCount() > 0) {
Yii::app()->params['WP_CONFIG'];
foreach ($document['documents'] as $key => $value) {
$descp = strip_tags($value['description']);
$aString = explode(" ",$descp);
$adescription = array_slice($aString,0,35);
$description=implode(" ",$adescription);
?>
<figure class="expolre-the col-sm-12">
<a title="<?php echo $value['destination']; ?>" class="imgt" href="<?php echo $value['url']; ?>">
<img alt="<?php echo $value['destination']; ?>" src="<?php echo get_preset_post_meta( $value['destination_id'],1); //$value['image_small']; ?>">
</a>
<figcaption>
<a title="<?php echo $value['destination']; ?>" href="<?php echo $value['url']; ?>"><h2><?php echo $value['destination']; ?></h2></a>
<p><?php echo $description; ?>  <!-- a class="readm" href="<?php //echo $value['url']; ?>">Read more »</a --></p>                         
<div class="clear"></div>  
<?php
//$near_by_places =null; $events=null;

$mtags = null;
if (isset($value['tag'])) {
$mtags = $value['tag'];
}
$subtag = null;
if (isset($value['subtag'])) {
$subtag = $value['subtag'];
}
$plc_count = 0;
if (isset($value['places_to_visit'])) {
$plc_count = sizeof($value['places_to_visit']);
}
$events = null;
if (isset($value['events'])) {
$events = sizeof($value['events']);
}
?>
   
<section class="plv">
    <?php if($plc_count > 0){ ?> <a href=""> <?php echo $plc_count ?> Places to Visit >> </a> <?php }?>
    <?php if($events > 0){ ?> <a href=""> <?php echo $events?> Activities to do >> </a> <?php }?>
</section>
<div class="clear"></div>
<section class="placeholder-outer">
<?php
if (isset($mtags)) {
foreach ($mtags as $mkey => $mvalue) {
?>
<span class="placeholder"><?php echo $mvalue; ?></span>
<?php
}
}
?>
<?php
if (isset($subtag)) {
foreach ($subtag as $skey => $svalue) {
?>
<span class="placeholder"><?php echo $svalue; ?></span>
<?php
}
}
?>
<div class="clear"></div>  
</section>
</figcaption>
<div class="clear"></div>   
</figure>
<?php
}
  


    ?>
    
    
    <?php
} else {
    ?>
    <figure class="col-sm-12">
        No Result Found.
        <div class="clear"></div> 
    </figure>
<?php } ?>
        



