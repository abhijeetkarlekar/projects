<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="html" encoding="iso-8859-1" />
<xsl:template name="incLeftNavigation">
<div class="leftArea">
<div class="grid_2">
            <div class="box sidemenu">
                <div class="block" id="section-menu">
                    <ul class="section menu">
                        <li><a class="menuitem">Category Management Module</a>
                            <ul class="submenu">
                                <li><a href="{XML/ADMIN_WEB_URL}category.php">Category Management</a> </li>
                                
                            </ul>
                        </li>
                        <li><a class="menuitem">Brand Management Module</a>
                            <ul class="submenu">
                                <li><a href="{XML/ADMIN_WEB_URL}brand.php">Brand Management</a> </li>
                                <li><a href="{XML/ADMIN_WEB_URL}popular_brand.php">Popular Brand Management</a> </li>
                            </ul>
                        </li>
                        <li><a class="menuitem">Feature Management Module</a>
                            <ul class="submenu">
                                <li><a href="{XML/ADMIN_WEB_URL}feature_unit.php">Feature Unit Management</a> </li>
                                <li><a href="{XML/ADMIN_MAIN_URL}feature_group.php">Main Feature Group/Tab Management</a> </li>
                                <li><a href="{XML/ADMIN_MAIN_URL}feature_sub_group.php">Feature Sub Group Management</a> </li>
                                <li><a href="{XML/ADMIN_WEB_URL}feature.php">Feature Management</a> </li>
                               
                            </ul>
                        </li>
                        
                        <li><a class="menuitem">Overview Management Module</a>
                            <ul class="submenu">
                                <li><a href="{XML/ADMIN_MAIN_URL}feature_overview.php">Feature Overview Management</a> </li>
                               <!--  <li><a href="{XML/ADMIN_MAIN_URL}compare_overview.php">Feature Compare Page Overview Management</a> </li>
                                <li><a href="{XML/ADMIN_MAIN_URL}popular_feature_cars.php">Feature Based Popular Cars Management</a> </li> -->
                                
                            </ul>
                        </li>
                        <li><a class="menuitem">Pivot Management Module</a>
                            <ul class="submenu">
                                <li><a href="{XML/ADMIN_MAIN_URL}pivot_display_type.php">Pivot Display Management</a> </li>
                                <li><a href="{XML/ADMIN_WEB_URL}pivot_sub_group.php">Pivot Sub Group Management</a> </li>
                                <li><a href="{XML/ADMIN_MAIN_URL}pivot.php">Pivot Management</a></li>
                                
                            </ul>
                        </li>
                        <li><a class="menuitem">Product Management Module</a>
                            <ul class="submenu">
                                    <li>
                                        <a href="{XML/ADMIN_MAIN_URL}model.php">Product Model Management</a>
                                    </li>
                                    <li>
                                    <a href="{XML/ADMIN_MAIN_URL}product.php">Product Management</a>
                                    </li>
                                    <li>
                                    <a href="{XML/ADMIN_MAIN_URL}newarrival_product.php">New Arrival Product Management</a>
                                    </li>
                                    <li>
                                    <a href="{XML/ADMIN_MAIN_URL}featured_product.php">Featured Product Management</a>
                                    </li>
                                    <li>
                                    <a href="{XML/ADMIN_MAIN_URL}trending_product.php">Trending Product Management</a>
                                    </li>
                                    <li>
                                    <a href="{XML/ADMIN_MAIN_URL}top_product.php">Top Product Management</a>
                                    </li>
                                    <li>
                                    <a href="{XML/ADMIN_MAIN_URL}budget_product.php">Budget Product Management</a>
                                    </li>
                                    <li>
                                    <a href="{XML/ADMIN_MAIN_URL}other_product.php">Other Product Management</a>
                                    </li>
                                    <li>
                                    <a href="{XML/ADMIN_MAIN_URL}best_seller_product.php">Best Seller Product Management</a>
                                    </li>
                                    <li>
                                    <a href="{XML/ADMIN_MAIN_URL}upcoming_product.php">Upcoming Product Management</a>
                                    </li>
                                
                            </ul>
                        </li>
                        <li>
                            <a class="menuitem">Slideshow Management Module</a>
                            <ul class="submenu">
                            <li>
                                    <a href="{XML/ADMIN_MAIN_URL}slideshow.php">Slideshow Management</a>
                            </li>
                            <li>
                                 <a href="{XML/ADMIN_MAIN_URL}add_slides.php">Assign Slides Management</a>
                            </li>
                            </ul>
                        </li>
                            <li>
                            <a class="menuitem">Video Management Module</a>
                            <ul class="submenu">
                            <li>
                                    <a href="{XML/ADMIN_MAIN_URL}add_video.php">Video Management</a>
                            </li>
                            
                            </ul>
                        </li>
                        <li>
                            <a class="menuitem">Compare Management Module</a>
                            <ul class="submenu">
                            <li>
                                    <a href="{XML/ADMIN_MAIN_URL}compare_top_competitor.php">Compare Management</a> 
                            </li>
                            <li>
                                    <a href="{XML/ADMIN_MAIN_URL}featured_oncars_comparison.php">Featured Compare Management</a> 
                            </li>
                            <li>
                                    <a href="{XML/ADMIN_MAIN_URL}top_oncars_comparison.php">Top Compare Management</a> 
                            </li>
                            
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
</div>
</xsl:template>
</xsl:stylesheet>

