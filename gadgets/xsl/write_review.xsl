<?xml version="1.0" encoding="utf-8"?><!DOCTYPE xsl:stylesheet  [
        <!ENTITY nbsp   "&#160;">
        <!ENTITY copy   "&#169;">
        <!ENTITY reg    "&#174;">
        <!ENTITY trade  "&#8482;">
        <!ENTITY mdash  "&#8212;">
        <!ENTITY ldquo  "&#8220;">
        <!ENTITY rdquo  "&#8221;">
        <!ENTITY pound  "&#163;">
        <!ENTITY yen    "&#165;">
        <!ENTITY euro   "&#8364;">
]>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
    <xsl:output method="html" encoding="utf-8" doctype-public="-//W3C//DTD XHTML 1.0 Transitional//EN" doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"/>
    <xsl:param name="gallery_product_id" />
    <xsl:template name="WriteReview">
    <div id="sample-report-modal" class="modal-report wreviewbox">
    <a title="Close" class="simplemodal-close"><i></i></a>
    <div class="blkwreview">
	<div id="disp_msg"></div>
      <h2 class="hdh2">Write a Review on <span><xsl:value-of select="/XML/PRODUCT_DETAIL/PRODUCT_DETAIL_DATA/DISPLAY_PRODUCT_NAME" disable-output-escaping="yes" /></span></h2>
        
        <form name="add_usr_review" method="post" id="form1" action="" class="frmwreview">
            <div id="writereview1">
            
            <div class="brand-rating">
                <span class="rate-ttl">Rating</span>
                <xsl:for-each select="/XML/QUESTIONAIRE_MASTER/QUESTIONAIRE_MASTER_DATA">
                    <xsl:if test="position()=1">
                        <input type="hidden" name="que_id_{position()}" id="que_id_{position()}" value="{QUEID}"/>
                        <!-- <span class="review_label"><xsl:value-of select="QUENAME"/></span> -->
                        <span class="rating_label_error fr" id="errormsgdisplay_que_id_{position()}"></span>
                        <input type="hidden" name="ques_{QUEID}_{position()}" id="ques_{QUEID}_{position()}" value="{QUENAME}"/>
                        <input type="hidden" name="total_ans_ques_{QUEID}" id="total_ans_ques_{QUEID}" value="{QUESTIONAIRE_ANS_MASTER/ANS_COUNT}"/>
                        <input type="hidden" name="algo_{QUEID}_{position()}" id="algo_{QUEID}_{position()}" value="{ALGORITHM}"/>
                        <xsl:choose>
                          <xsl:when test="QUESTIONAIRE_ANS_MASTER/ANS_COUNT&gt;0">
                            <xsl:for-each select="QUESTIONAIRE_ANS_MASTER/QUESTIONAIRE_ANS_MASTER_DATA">
                              <xsl:if test="position()=1">
                                    <input type="hidden" name="ans_{QUEID}_{position()}" id="ans_{QUEID}_{position()}" value="{ANS_ID}"/>
                                    <input type="hidden" name="user_review_{QUEID}_{ANS_ID}" id="user_review_{QUEID}_{ANS_ID}" value="0"/>
                                    <input type="hidden" name="ans_review_{QUEID}_{ANS_ID}" id="ans_review_{QUEID}_{ANS_ID}" value="{ANS}"/> 
                                    <!-- <span class="rating_type_label"><xsl:value-of select="ANS" disable-output-escaping="yes"/>:</span> --> 
                                      <input type="hidden" name="ansstr_{QUEID}_{position()}" id="ansstr_{QUEID}_{position()}" value="{ANS}"/>   
                                 <span class="avg_user_stars">
                 
                                    <span class="rating" style="width:0"> </span>
                                     <!-- new addition starts -->
                                     <span class="btnrate">
                                         <a href="javascript:void(0)"></a>
                                         <a href="javascript:void(0)"></a>
                                         <a href="javascript:void(0)"></a>
                                         <a href="javascript:void(0)"></a>
                                         <a href="javascript:void(0)"></a>
                                     </span>
                                     <!-- new addition ends -->
                                 </span>

                                 <span class="rate-ttl"></span>
                                <span id="errormsgdisplay_rate" style="display:none;"></span>
                                 <!-- new addition starts -->
                                <input class="col-xs-10 txtrate" type="hidden" id="txtrate" name="txtrate" value=""/> 
                                <!-- new addition ends -->
                                 <!-- <input type="hidden" name="clickdone_{QUEID}_{ANS_ID}" id="clickdone_{QUEID}_{ANS_ID}" /> -->
                             </xsl:if>
                            </xsl:for-each>
                         </xsl:when>
                         <xsl:otherwise>
                         </xsl:otherwise>
                         </xsl:choose>    
                    </xsl:if>
                </xsl:for-each>        

                 <div class="clear"></div>
            </div>
            <fieldset>
                <label class="col-xs-2">Title: </label>
                <input  class="col-xs-10" type="text"  name="title" id="title"/>
            </fieldset>
            <div class="errormsgdisplay" id="errormsgdisplay_title" style="display:none;">Write a review title </div>
            <xsl:for-each select="/XML/QUESTIONAIRE_MASTER/QUESTIONAIRE_MASTER_DATA">
                <xsl:if test="position()=7">
                    <fieldset>
                        <label class="col-xs-2">Review: </label>
                        <input type="hidden" name="que_id_{position()}" id="que_id_{position()}" value="{QUEID}"/>
                        <textarea class="col-xs-10" name="comment_{QUEID}" id="comment_{QUEID}"></textarea>
                    </fieldset>
                    <div class="errormsgdisplay" id="errormsgdisplay_review" style="display:none;">Write a your review </div>
                </xsl:if>
            </xsl:for-each>
            <input type="button" class="btnwrvwSubmit" value="Submit Review"  onclick="submitUserReview('{/XML/SELECTED_CATEGORY_ID}','{/XML/BRAND_ID}','{/XML/PRODUCT_NAME_ID}','{/XML/WR_PRODUCT_ID}')"/>


        </div>
        </form>
    </div>
</div>

<div id="sample-report-modal" class="modal-report loginbox">
  <div id="writereviewmsg"></div>
  <div id="writereview">
    <a title="Close" class="simplemodal-close"><i></i></a>
    <div class="blklogin">
       <form name="send_usr_review" method="post" id="form2" action="" class="frmwreview">
      <p class="loghd">Connect with</p>
      <input type="hidden" name="add_review" id="add_review" value="1"/>
      <input type="hidden" name="user_event" id="user_event" value="add_review"/>
      <input type="hidden" name="user_rev_url" value="{/XML/RETURN_REVIEW_URL}"/>
      <input type="hidden" name="cat_id" id="cat_id" value="{/XML/SELECTED_CATEGORY_ID}"/>
      <input type="hidden" name="brand_id" id="brand_id" value="{/XML/BRAND_ID}"/>
      <input type="hidden" name="product_info_id" id="product_info_id" value="{/XML/PRODUCT_NAME_ID}"/>
      <input type="hidden" name="product_id" id="product_id" value="{/XML/WR_PRODUCT_ID}"/>
      <input type="hidden" name="fbid" id="fbid" value=""/>
      <input type="hidden" name="email" id="email" value=""/>
      <input type="hidden" name="fname" id="fname" value=""/>
      <input type="hidden" name="lname" id="lname" value=""/>
      <input type="hidden" name="username" id="username" value=""/>
      <input type="hidden" name="totalquestion" id="totalquestion" value="1"/>
      <!-- input type="text"  name="wusername" id="wusername" value=""/>
      <input type="text"  name="wemail" id="wemail" value=""/ -->
      <input type="hidden" name="title_1" id="title_1" value=""/>
      <input type="hidden" name="comment_7_1" id="comment_7_1" value=""/>
      <input type="hidden" name="que_id_1" id="que_id_1" value="1"/>
      <input type="hidden" name="que_id_7" id="que_id_7" value="7"/>
      <input type="hidden" name="total_ans_ques_1" id="total_ans_ques_1" value="1"/>
      <input type="hidden" name="algo_1_1" id="algo_1_1" value="{{ans}}"/>
      <input type="hidden" name="ques_1_1" id="ques_1_1" value="1"/>
      <input type="hidden" name="ans_1_1" id="ans_1_1" value="1"/>
      <input type="hidden" name="user_review_1_1" id="user_review_1_1" value="0"/>
      <input type="hidden" name="ans_review_1_1" id="ans_review_1_1" value="1"/> 
      <input type="hidden" name="ansstr_1_1" id="ansstr_1_1" value="1"/>

      <!-- input type="button" class="btnwrvwSubmit" value="Submit Review" onclick="submitLoginUserReview('{/XML/SELECTED_CATEGORY_ID}','{/XML/BRAND_ID}','{/XML/PRODUCT_NAME_ID}','{/XML/WR_PRODUCT_ID}')"/ -->
        <a href="javascript:void(0)" class="fb" name="fb-login" id="fb-login"></a>
	<!-- logout -->
        <!-- div id="fb-logout">Logout</div -->
	<!-- logout -->
	<!--
        <a href="javascript:void(0)" class="tw" name="tw-login" id="tw-login"></a>
	-->
        <a href="javascript:void(0)" class="gp" name="gp-login" id="gp-login"></a>
        <div class="from_facebook">
        <!-- a id="fb-auth" class="facebooklogin"></a -->
        <!-- div id="user-info" style="display:none;"></div -->
        </div>
        </form>
    </div>
  </div>
</div>
 <div id="fb-root"></div>
  <script type="text/javascript">
  	var bkpageurl='<xsl:value-of select="/XML/WRITE_REVIEW_LINK"/>';
  	var email='<xsl:value-of select="/XML/EMAILID"/>';
  	var uid ='<xsl:value-of select="/XML/USER_UID"/>';
  	var user_name = '<xsl:value-of select="/XML/USERNAME"/>';
	var seo_web_url = '<xsl:value-of select="/XML/SEO_WEB_URL" />';
	var web_url = '<xsl:value-of select="/XML/WEB_URL" />';
  </script>
	<script src="http://new.gadgets.in/scripts/jquery-1.8.3.min.js"></script>
	<!-- script src="http://crypto-js.googlecode.com/svn/tags/3.1.2/build/rollups/aes.js"></script -->
	<script src="http://crypto-js.googlecode.com/svn/tags/3.1.2/build/rollups/rabbit.js"></script>
	<script src="{/XML/JS_URL}social_login.js"></script>
	<script src="{/XML/JS_URL}review.js"></script>
     </xsl:template>
</xsl:stylesheet>
