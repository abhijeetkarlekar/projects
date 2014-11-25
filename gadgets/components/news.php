<?php

require_once(CLASSPATH . 'DbConn.php');
require_once(CLASSPATH . 'product.class.php');
require_once(CLASSPATH . 'category.class.php');
require_once(CLASSPATH . 'brand.class.php');

$dbconn = new DbConn;
$product = new ProductManagement;
$category = new CategoryManagement;
$brand = new BrandManagement;

$category_id = $component_params['category_id'] ? $component_params['category_id'] : '1';
$brand_id = $component_params['brand_id'];
$brand_name = $component_params['brand_name'];
$model_id = $component_params['model_id'];
$model_name = $component_params['model_name'];
$variant_id = $component_params['variant_id'];
$variant_name = $component_params['variant_name'];
$startlimit = $component_params['offset'];
$limitcnt = $component_params['count'];
$view_all_news = $component_params['view_all_news'];
if (!empty($category_id)) {
        $category_result = $category->arrGetCategoryDetails($category_id);
}
$cat_path = $category_result[0]['seo_path'];
$model_name = str_replace("-", " ", $model_name);
$product_name = $brand_name . " " . $model_name;

if (!empty($category_id)) {

    $file_path_name = BASEPATH . "newsxml/" . str_replace(array(" ", "-"), "_", strtolower($product_name)) . ".xml";
    $file_mod_time = filemtime($file_path_name);
    $curr_date_time = strtotime("now");
    $file_diff_time = $curr_date_time - $file_mod_time;

    if (file_exists($file_path_name) && $file_diff_time < 86400) {
        //echo "IN";
        $component_xml .= file_get_contents($file_path_name);
    } else {
        //echo "ELSE";
        $feed_url = "http://www.bgr.in/feed/?tag=" . urlencode(str_replace(" ", "-", $product_name));
        $content1 = @file_get_contents($feed_url);
        $content = str_replace('&', '&amp;', $content1);
//    header('Content-type: text/xml');
//    echo $content; die;
        if ($content1 != false) {
            $x = new SimpleXmlElement($content);
            $newscnt = count($x->channel->item);
            $news_xml .= "<NEWS_MASTER>";
            $news_xml .= "<COUNT><![CDATA[$newscnt]]></COUNT>";
            $news_xml .= "<FEED_URL><![CDATA[$feed_url]]></FEED_URL>";
            if ($newscnt > 0) {
                $start_count = 0;
                foreach ($x->channel->item as $entry) {
                    if ($start_count == $limitcnt) {
                        break;
                    }
                    $categoryNameArr = array();
                    //print_r($entry); die;
                    $news_xml .= "<NEWS_MASTER_DATA>";
                    $news_xml .= "<SEO_URL>$entry->link</SEO_URL>";
                    $news_xml .= "<TITLE>$entry->title</TITLE>";
                    $disp_date = date('d M Y', strtotime($entry->pubDate));
                    $news_xml .= "<DISP_DATE>$disp_date</DISP_DATE>";
                    $description = getCompactString(strip_tags($entry->description), 50, true) . ' ...';
                    $news_xml .= "<DESCRIPTION>$description</DESCRIPTION>";
                    $image_path = $entry->enclosure->attributes()->url;
                    $news_xml .= "<IMAGE_PATH>$image_path</IMAGE_PATH>";
                    $news_xml .= "<CATEGORIES>";
                    foreach ($entry->category as $tag => $categoryName) {
                        if (strlen($categoryNameArr[$tag]) > 0) {
                            break;
                        }
                        $news_xml .= "<CATEGORY>$categoryName</CATEGORY>";
                        $categoryNameArr[$tag] = $categoryName;
                    }
                    $news_xml .= "</CATEGORIES>";
                    $news_xml .= "</NEWS_MASTER_DATA>";
                    $start_count++;
                }
            }
            $news_xml .= "<VIEW_ALL_NEWS>$view_all_news</VIEW_ALL_NEWS>";
            $news_xml .= "</NEWS_MASTER>";
        }
        $component_xml .= $news_xml;
        $fp = fopen($file_path_name, "w+");
        fwrite($fp, $news_xml);
        fclose($fp);
    }
}
//$xml = "<XML>";
//$xml .= $component_xml;
//$xml .= "</XML>";
//header('Content-type: text/xml');
//echo $xml;
//exit;
?>
