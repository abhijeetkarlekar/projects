
<?php
$document = $model->getDocumentWithDistDur();
if ($model->getResultCount() > 0) {
?>
<section class="t-count"><?php echo $model->getResultCount(); ?> destinations to visit</section>
<?php    
foreach ($document['documents'] as $key => $value) {

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
$descp = strip_tags($value['description']);
$aString = explode(" ",$descp);
$adescription = array_slice($aString,0,35);
$description=implode(" ",$adescription);
?>
<figure class="col-sm-12">
<a title="<?php echo $value['destination']; ?>" class="imgt" href="<?php echo $value['url']; ?>">
<img alt="<?php echo $value['destination']; ?>" src="<?php echo get_preset_post_meta( $value['destination_id'],1); //$value['image_small']; ?>">
</a>
<figcaption>
<a title="<?php echo $value['destination']; ?>" href="<?php echo $value['url']; ?>"><h2><?php echo $value['destination']; ?></h2></a>
<?php if($value['destination_type']=="Places to visit") { ?>
    <section class=""><a title="<?php echo $value['contained_in'][0]; ?>" href="<?php echo $value['contained_in_link']; ?>"><span class="placeholder"><?php echo $value['contained_in'][0]; ?></span></a></section>
<?php } ?>
<p><?php echo $description; ?>  <!-- a class="readm" href="<?php //echo $value['url']; ?>">Read more »</a --></p>

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
$pageurl = $seourl;
//$pagingnum = 0;

    $iLimitPages = 10;
    $limit = 10; $qryparams=''; $link_type='';
    $curpage = $currpage;
    if($curpage==0 || $curpage==''){$curpage=1;}
    $pagesc = (($count % $limit) == 0) ? $count / $limit : floor($count / $limit) + 1;

    if($curpage == 1){
        $iStartNo=$curpage;
    }elseif($curpage == 2 ){
        $iStartNo = $curpage-1;
    }else{
        $iStartNo = $curpage-2;
    }
    $iEndNo =($iStartNo + $iLimitPages) -1;
    if($iEndNo>$pagesc) $iEndNo=$pagesc;
    $iDiff = $iEndNo-$iStartNo;
    if($iDiff<($iLimitPages-1)) {
        $iStartNo = $iStartNo -($iLimitPages-$iDiff-1);
    }
    if($iStartNo<1) $iStartNo=1;
    $next_prev  = "";
    if ($qryparams){
        $qryparams = trim($qryparams);
        if ($qryparams{0} != ","){
            $qryparams = "," . $qryparams;
        }
    }
    $qrparamsarr=explode(',',$qryparams);
    $qryparams=implode("','",$qrparamsarr);
    $first_link = ($link_type == "text") ? " << " : " << ";
    $prev_link = ($link_type == "text") ? " < " :" < ";
    $next_link = ($link_type == "text") ? " > " : " > ";
    $last_link = ($link_type == "text") ? " >> " : " >> ";
    $first_link = " << ";
    $prev_link = " < ";
    $next_link = " > ";
    $last_link = " >> ";
    $next_prev .= "<div class=\"clear\"></div>";	
    $next_prev .= "<ul class=\"pagination\">";
    if($iLimitPages < $pagesc){
    if (($curpage-1) <= 0){
        $next_prev .= "<li class=\"\"><span class=\"pagination_text\">$first_link</span></li>";
        if($curpage >1 ){
            $next_prev .= "<li class=\"\"><span class=\"pagination_text\">$prev_link</span></li>";
        }
    }else{
        $next_prev .= "<li class=\"\"><a href=\"$pageurl/page/1\" title=\"First\" class=\"pagination_text\">$first_link</a></li>";
        $next_prev .="<li class=\"\"><a href=\"$pageurl/page/".($iStartNo-1)."\" title=\"Previous\" class=\"pagination_text\">$prev_link</a></li>";
    }
    }
    for ($i=$iStartNo; $i<=$iEndNo; $i++){
        //echo $i ."==". $curpage."<br>";
        if ($i == $curpage){
            $next_prev .= "<li class=\"active\"><span class='currentpage'>$i</span></li>";
        }else {
            $next_prev .= "<li class=\"\"><a href=\"$pageurl/page/$i\" title=\"Page $i\"  class='pagedigit'>$i</a></li> ";
        }
        $next_prev .= " ";
    }
    if($iLimitPages < $pagesc){
    if (($iEndNo+1) > $pagesc){
        if($curpage < $pagesc){
            $next_prev .= "<li class=\"\"><span class=\"pagination_text\" >$next_link</span></li>";
        }
        $next_prev .=  "<li class=\"\"><span class=\"pagination_text\">$last_link</span> </li>";
    }else{
        $next_prev .=  "<li class=\"\"><a href=\"$pageurl/page/".($iEndNo+1)."\" title=\"Next\" class=\"pagination_text\">$next_link</a></li>";
        $next_prev .=  "<li class=\"\"><a href=\"$pageurl/page/$pagesc\" title=\"Last\" class=\"pagination_text\">$last_link</a></li>";
    }
    }
    $next_prev .=  "</ul>";
    echo  $next_prev;
} else {
    ?>
    <figure class="col-sm-12">
        No Result Found.
        <div class="clear"></div> 
    </figure>
<?php }  ?>
