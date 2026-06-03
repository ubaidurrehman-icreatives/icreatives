<center>
<div style="justify-content: center; width:800px;">
        <div style="float:right; width:800px; margin-right:10px; padding-bottom:20px;">
          <div style="float:left; width:784px; padding-top:15px;">
            <div style="float:left;">
              <div style="float:left;" class="largetxt1"><span class="redarrowclass1"> [</span> creative talent application </div>
              <div style="float:left; padding:3px 2px 0 3px;" class="redarrowclass1"> >>> </div>
            </div>
		<div style="float:right; padding:10px 0px 0 0px;"> <span style="font-size: 60px; color: #b22625; font-weight: thin;">67%</span> <span style="font-size: 10px;">completed</span> </div>

          </div>
   
            <form action="/longform/signmeuplongupload.php" ENCTYPE="multipart/form-data" method="post" id="signupform" name="signupform">

            <input id="item_number" name="item_number" value="<?php echo $item_num ?>" type="hidden" value="" />
            <input id="current_state" name="current_state" value="s4" type="hidden" value=""  />
            <input id="RMail" name="RMail" value="<?php echo $RMail ?>" type="hidden"  />

            
          <div style="clear:left; height:0px;"> &nbsp;</div>
          <div style="float:left;" >
           			<div style="clear:both; height:20px;"></div>
			<div style="float:left; padding:0px 0 0 0px; width:100%;">
               <div style="clear:both;" class="accordion-header"><span class="icon">▶</span> Account & Studio Roles (blank for none) </div>
			   <div class="accordion-content">
			               <div style="clear:left; padding:0px 0 0 30px;">
              <div style="clear:left; height:20px;"> </div>
              <div style="float:left; width:100px;" class="graytxt3"> Account Mngr </div>
                <?php printRadio("ASR|Account Manager"); ?>

              <div style="float:left;  width:100px;" class="graytxt3"> Project Mngr </div>
              <?php printRadio("ASR|Project Manager"); ?>
                
              <div style="clear:left; height:8px;"> </div>
              <div style="float:left; width:100px;" class="graytxt3"> Marketing <br />
                Coordinator </div>
              <?php printRadio("ASR|Marketing Coordinator"); ?>
                
              <div style="float:left; width:100px;" class="graytxt3"> Marketing <br />
                Director </div>
               <?php printRadio("ASR|Marketing Director"); ?>
              <div style="clear:left; height:8px;"> </div>
              <div style="float:left; width:100px;" class="graytxt3"> Producer Web </div>
              <?php printRadio("ASR|Web Producer"); ?>
            
              <div style="float:left; width:100px;" class="graytxt3"> Media Buyer </div>
                <?php printRadio("ASR|Media Buyer"); ?>
				              <div style="clear:left; height:8px;"> </div>
              <div style="clear:left; height:8px;"> </div>
              <div style="float:left; width:100px;" class="graytxt3"> Producer Video <br />
                Broadcast </div>
              <?php printRadio("ASR|Video Producer"); ?>

              <div style="float:left; width:100px;" class="graytxt3"> Production Mngr </div>
               <?php printRadio("ASR|Production Manager"); ?>     
               <div style="clear:left; height:8px;"> </div>
              <div style="float:left; width:100px;" class="graytxt3"> Print Buyer/ <br />
                Estimator </div>
              <?php printRadio("ASR|Print Estimator"); ?>
              
			  <div style="float:left; width:100px;" class="graytxt3"> FunctionFox </div>
               <?php printRadio("ASR|FunctionFox"); ?>
           
				<div style="clear:left; height:8px;"> </div>
              <div style="float:left; width:100px;" class="graytxt3"> Media Planner </div>
              <?php printRadio("ASR|Media Planner"); ?>
                          
              <div style="float:left; width:100px;" class="graytxt3"> Studio Mng </div>
              <?php printRadio("ASR|Studio Manager"); ?>

              <div style="float:left; width:100px;" class="graytxt3"> Traffic Mngr</div>
              <?php printRadio("ASR|Traffic Manager"); ?>
                            
            
            </div>
          </div></div>
			<div style="float:left; padding:25px 0 0 0px; width:100%;">
               <div style="clear:both;" class="accordion-header"><span class="icon">▶</span> Marketing Skills (blank for none) </div>
			   <div class="accordion-content">
			               <div style="clear:left; padding:0px 0 0 30px;">
              <div style="clear:left; height:20px;"> </div>
              <div style="float:left; width:100px;" class="graytxt3"> Mailchimp </div>
              <?php printRadio("MMS|Mailchimp"); ?>
                
              <div style="float:left;  width:100px;" class="graytxt3"> Klaviyo </div>
              <?php printRadio("MMS|Klaviyo"); ?>
                
              <div style="clear:left; height:8px;"> </div>
              <div style="float:left; width:100px;" class="graytxt3"> Constant Contact </div>
              <?php printRadio("MMS|Constant Contact"); ?>
             
              <div style="float:left; width:100px;" class="graytxt3"> Campaign Manager </div>
               <?php printRadio("MMS|Campaign Manager"); ?>
               
              <div style="clear:left; height:8px;"> </div>
              <div style="float:left; width:100px;" class="graytxt3"> Salesforce Cloud </div>
              <?php printRadio("MMS|Salesforce Marketing Cloud"); ?>
                
             
              <div style="float:left; width:100px;" class="graytxt3"> HubSpot </div>
               <?php printRadio("MMS|HubSpot"); ?>
             
              <div style="clear:left; height:8px;"> </div>
              <div style="float:left; width:100px;" class="graytxt3"> ActiveCampaign </div>
              <?php printRadio("MMS|ActiveCampaign"); ?>
              
              <div style="float:left; width:100px;" class="graytxt3"> Pardot </div>
              <?php printRadio("MMS|Pardot"); ?>
                
               
              <div style="clear:left; height:8px;"> </div>
              <div style="float:left; width:100px;" class="graytxt3"> Marketo </div>
              <?php printRadio("MMS|Marketo"); ?>
                
             
              <div style="float:left; width:100px;" class="graytxt3"> Google Analytics (GA4) </div>
               <?php printRadio("MMS|Google Analytics (GA4)"); ?>
                
              
              <div style="clear:left; height:8px;"> </div>
              <div style="float:left; width:100px;" class="graytxt3"> Hotjar </div>
               <?php printRadio("MMS|Hotjar"); ?>
                
              
              <div style="float:left; width:100px;" class="graytxt3"> SEMrush </div>
               <?php printRadio("MMS|SEMrush"); ?>
                
              
              <div style="clear:left; height:8px;"> </div>
              <div style="float:left; width:100px;" class="graytxt3"> MRI-Simmons </div>
              <?php printRadio("MMS|MRI-Simmons"); ?>
                
              
              <div style="float:left; width:100px;" class="graytxt3"> Ahrefs </div>
              <?php printRadio("MMS|Ahrefs"); ?>

              <div style="clear:left; height:8px;"> </div>
              <div style="float:left; width:100px;" class="graytxt3"> Hootsuite </div>
              <?php printRadio("MMS|Hootsuite"); ?>
                
                
              <div style="float:left; width:100px;" class="graytxt3"> Sprout Social </div>
               <?php printRadio("MMS|Sprout Social"); ?>
              
              <div style="clear:left; height:8px;"> </div>
			  
			  <div style="float:left; width:100px;" class="graytxt3"> Buffer </div>
              <?php printRadio("MMS|Buffer"); ?>
                
              <div style="float:left;  width:100px;" class="graytxt3"> Later </div>
              <?php printRadio("MMS|Later"); ?>
                
              <div style="clear:left; height:8px;"> </div>
              <div style="float:left; width:100px;" class="graytxt3"> Planoly </div>
              <?php printRadio("MMS|Planoly"); ?>
             
              <div style="float:left; width:100px;" class="graytxt3"> Moz </div>
               <?php printRadio("MMS|Moz"); ?>
               
              <div style="clear:left; height:8px;"> </div>
              <div style="float:left; width:100px;" class="graytxt3"> Screaming Frog</div>
              <?php printRadio("MMS|Screaming Frog"); ?>
                
             
              <div style="float:left; width:100px;" class="graytxt3"> Google Ads </div>
               <?php printRadio("MMS|Google Ads"); ?>
             
              <div style="clear:left; height:8px;"> </div>
              <div style="float:left; width:100px;" class="graytxt3"> Meta Ads Mngr </div>
              <?php printRadio("MMS|Meta Ads Manager"); ?>
              
              <div style="float:left; width:100px;" class="graytxt3"> LinkedIn Campaign Mngr </div>
              <?php printRadio("MMS|LinkedIn Campaign Manager"); ?>
                
               
              <div style="clear:left; height:8px;"> </div>
              <div style="float:left; width:100px;" class="graytxt3"> TikTok Ads </div>
              <?php printRadio("MMS|TikTok Ads"); ?>
                
             
              <div style="float:left; width:100px;" class="graytxt3"> Google Analytics (GA4) </div>
               <?php printRadio("MMS|Google Analytics (GA4)"); ?>
                
              
              <div style="clear:left; height:8px;"> </div>
              <div style="float:left; width:100px;" class="graytxt3"> Amazon DSP </div>
               <?php printRadio("MMS|Amazon DSP"); ?>
                
              
              <div style="float:left; width:100px;" class="graytxt3"> WordPress </div>
               <?php printRadio("MMS|WordPress"); ?>
                
              
              <div style="clear:left; height:8px;"> </div>
              <div style="float:left; width:100px;" class="graytxt3"> Webflow </div>
              <?php printRadio("MMS|Webflow"); ?>
                
              
              <div style="float:left; width:100px;" class="graytxt3"> Shopify </div>
              <?php printRadio("MMS|Shopify"); ?>

              <div style="clear:left; height:8px;"> </div>
              <div style="float:left; width:100px;" class="graytxt3"> Wix </div>
              <?php printRadio("MMS|Wix"); ?>
                
                
              <div style="float:left; width:100px;" class="graytxt3"> Squarespace </div>
               <?php printRadio("MMS|Squarespace"); ?>
              
			  <div style="clear:left; height:8px;"> </div>
              <div style="float:left; width:100px;" class="graytxt3"> Canva </div>
              <?php printRadio("MMS|Canva"); ?>
                
                
              <div style="float:left; width:100px;" class="graytxt3"> Adobe Express </div>
               <?php printRadio("MMS|Adobe Express"); ?>
              
              <div style="clear:left; height:8px;"> </div>

            </div>
          </div></div>
		  
		  <div style="float:left; padding:25px 0 0 0px; width:100%;">
               <div style="clear:both;" class="accordion-header"><span class="icon">▶</span> Account Management Skills (blank for none) </div>
			   <div class="accordion-content">
			               <div style="clear:left; padding:0px 0 0 30px;">
              <div style="clear:left; height:20px;"> </div>
			  <div style="float:left; width:100px;" class="graytxt3"> Salesforce</div>
              <?php printRadio("AMS|Salesforce"); ?>
                
              <div style="float:left;  width:100px;" class="graytxt3"> HubSpot </div>
              <?php printRadio("AMS|HubSpot"); ?>
              <div style="clear:left; height:8px;"> </div>

              <div style="float:left; width:100px;" class="graytxt3"> Zoho CRM </div>
              <?php printRadio("AMS|Zoho CRM"); ?>
                
              <div style="float:left;  width:100px;" class="graytxt3"> Asana </div>
              <?php printRadio("AMS|Asana"); ?>
                
              <div style="clear:left; height:8px;"> </div>
              <div style="float:left; width:100px;" class="graytxt3"> Trello </div>
              <?php printRadio("AMS|Trello"); ?>
             
              <div style="float:left; width:100px;" class="graytxt3"> Monday.com </div>
               <?php printRadio("AMS|Monday.com"); ?>
               
              <div style="clear:left; height:8px;"> </div>
              <div style="float:left; width:100px;" class="graytxt3"> ClickUp </div>
              <?php printRadio("AMS|ClickUp"); ?>
                
             
              <div style="float:left; width:100px;" class="graytxt3"> Wrike </div>
               <?php printRadio("AMS|Wrike"); ?>
             
              <div style="clear:left; height:8px;"> </div>
              <div style="float:left; width:100px;" class="graytxt3"> Basecamp </div>
              <?php printRadio("AMS|Basecamp"); ?>
              
              <div style="float:left; width:100px;" class="graytxt3"> Smartsheet </div>
              <?php printRadio("AMS|Smartsheet"); ?>
                
               
              <div style="clear:left; height:8px;"> </div>
              <div style="float:left; width:100px;" class="graytxt3"> Slack </div>
              <?php printRadio("AMS|Slack"); ?>
                
             
              <div style="float:left; width:100px;" class="graytxt3"> Microsoft Teams </div>
               <?php printRadio("AMS|Microsoft Teams"); ?>
                
              
              <div style="clear:left; height:8px;"> </div>
              <div style="float:left; width:100px;" class="graytxt3"> Zoom </div>
               <?php printRadio("AMS|Zoom"); ?>
                
              
              <div style="float:left; width:100px;" class="graytxt3"> Harvest </div>
               <?php printRadio("AMS|Harvest"); ?>
                
              
              <div style="clear:left; height:8px;"> </div>
              <div style="float:left; width:100px;" class="graytxt3"> Float </div>
              <?php printRadio("AMS|Float"); ?>
                
              
              <div style="float:left; width:100px;" class="graytxt3"> Toggl </div>
              <?php printRadio("AMS|Toggl"); ?>

              <div style="clear:left; height:8px;"> </div>
              <div style="float:left; width:100px;" class="graytxt3"> Jira </div>
              <?php printRadio("AMS|Jira"); ?>
                
                
              <div style="float:left; width:100px;" class="graytxt3"> Workamajig </div>
               <?php printRadio("AMS|Workamajig"); ?>
              
			         <div style="clear:left; height:8px;"> </div>
              <div style="float:left; width:100px;" class="graytxt3"> Advantage </div>
              <?php printRadio("AMS|Advantage"); ?>
                
                
              <div style="float:left; width:100px;" class="graytxt3"> Synergist </div>
               <?php printRadio("AMS|Synergist"); ?>
              
              <div style="clear:left; height:8px;"> </div>
			  
			  <div style="float:left; width:100px;" class="graytxt3"> DoubleClick </div>
               <?php printRadio("AMS|DoubleClick"); ?>
			  <div style="float:left; width:100px;" class="graytxt3"> FunctionFox </div>
               <?php printRadio("AMS|FunctionFox"); ?>
			   
              
              <div style="clear:left; height:8px;"> </div>
            </div>
          </div></div>
		  
			<div style="float:left; padding:25px 0 0 0px; width:100%;">
               <div style="clear:both;" class="accordion-header"><span class="icon">▶</span> Media Buying Skills (blank for none) </div>
			   <div class="accordion-content">
			               <div style="clear:left; padding:0px 0 0 30px;">
              <div style="clear:left; height:20px;"> </div>
			  <div style="float:left; width:100px;" class="graytxt3"> Google Ads</div>
              <?php printRadio("MBS|Google Ads"); ?>
                
              <div style="float:left;  width:100px;" class="graytxt3"> Meta Ads Manager </div>
              <?php printRadio("MBS|Meta Ads Manager"); ?>
              <div style="clear:left; height:8px;"> </div>

              <div style="float:left; width:100px;" class="graytxt3"> TikTok Ads </div>
              <?php printRadio("MBS|TikTok Ads"); ?>
                
              <div style="float:left;  width:100px;" class="graytxt3"> Amazon DSP </div>
              <?php printRadio("MBS|Amazon DSP"); ?>
                
              <div style="clear:left; height:8px;"> </div>
              <div style="float:left; width:100px;" class="graytxt3"> The Trade Desk </div>
              <?php printRadio("MBS|The Trade Desk"); ?>
             
              <div style="float:left; width:100px;" class="graytxt3"> DV360 </div>
               <?php printRadio("MBS|DV360"); ?>
               
              <div style="clear:left; height:8px;"> </div>
              <div style="float:left; width:100px;" class="graytxt3"> MediaMath </div>
              <?php printRadio("MBS|MediaMath"); ?>
                
             
              <div style="float:left; width:100px;" class="graytxt3"> StackAdapt </div>
               <?php printRadio("MBS|StackAdapt"); ?>
             
              <div style="clear:left; height:8px;"> </div>
              <div style="float:left; width:100px;" class="graytxt3"> Pageflex </div>
              <?php printRadio("MBS|Pageflex"); ?>
              
              <div style="float:left; width:100px;" class="graytxt3"> PrintSmith Vision </div>
              <?php printRadio("MBS|PrintSmith Vision"); ?>
                
               
              <div style="clear:left; height:8px;"> </div>
              <div style="float:left; width:100px;" class="graytxt3"> EFI </div>
              <?php printRadio("MBS|EFI"); ?>
                
             
              <div style="float:left; width:100px;" class="graytxt3"> Nielsen </div>
               <?php printRadio("MBS|Nielsen"); ?>
                
              
              <div style="clear:left; height:8px;"> </div>
              <div style="float:left; width:100px;" class="graytxt3"> comScore </div>
               <?php printRadio("MBS|comScore"); ?>
                
              
              <div style="float:left; width:100px;" class="graytxt3"> Kantar </div>
               <?php printRadio("MBS|Kantar"); ?>
                
              
              <div style="clear:left; height:8px;"> </div>
              <div style="float:left; width:100px;" class="graytxt3"> MRI-Simmons </div>
              <?php printRadio("MBS|MRI-Simmons"); ?>
                
              
              <div style="float:left; width:100px;" class="graytxt3"> Tableau </div>
              <?php printRadio("vTableau"); ?>

              <div style="clear:left; height:8px;"> </div>
              <div style="float:left; width:100px;" class="graytxt3"> Excel </div>
              <?php printRadio("MBS|Excel"); ?>
                
                
              <div style="float:left; width:100px;" class="graytxt3"> Looker Studio </div>
               <?php printRadio("MBS|Looker Studio"); ?>
              
              <div style="clear:left; height:8px;"> </div>
			                <div style="float:left; width:100px;" class="graytxt3"> LinkedIn Campaign </div>
               <?php printRadio("MBS|LinkedIn Campaign Manager"); ?>
			  
 
            </div>
          </div></div>
		  
		  <div style="float:left; padding:25px 0 0 0px; width:100%;">
               <div style="clear:both;" class="accordion-header"><span class="icon">▶</span> Languages (blank for none) </div>
			   <div class="accordion-content">
			               <div style="clear:left; padding:0px 0 0 30px;">
              <div style="clear:left; height:20px;"> </div>
              <div style="float:left; width:100px;" class="graytxt3"> Arabic </div>
              <?php printRadio("LNG|Arabic"); ?>
                
              <div style="float:left;  width:100px;" class="graytxt3"> Italian </div>
              <?php printRadio("LNG|Italian"); ?>
                
              <div style="clear:left; height:8px;"> </div>
              <div style="float:left; width:100px;" class="graytxt3"> Cantonese </div>
              <?php printRadio("LNG|Cantonese"); ?>
             
              <div style="float:left; width:100px;" class="graytxt3"> Japanese </div>
               <?php printRadio("LNG|Japanese"); ?>
               
              <div style="clear:left; height:8px;"> </div>
              <div style="float:left; width:100px;" class="graytxt3"> Creole </div>
              <?php printRadio("LNG|Creole"); ?>
                
             
              <div style="float:left; width:100px;" class="graytxt3"> Korean </div>
               <?php printRadio("LNG|Korean"); ?>
             
              <div style="clear:left; height:8px;"> </div>
              <div style="float:left; width:100px;" class="graytxt3"> English </div>
              <?php printRadio("LNG|English"); ?>
              
              <div style="float:left; width:100px;" class="graytxt3"> Mandarin </div>
              <?php printRadio("LNG|Mandarin "); ?>
                
               
              <div style="clear:left; height:8px;"> </div>
              <div style="float:left; width:100px;" class="graytxt3"> Farsi </div>
              <?php printRadio("LNG|Farsi"); ?>
                
             
              <div style="float:left; width:100px;" class="graytxt3"> Portuguese </div>
               <?php printRadio("LNG|Portuguese"); ?>
                
              
              <div style="clear:left; height:8px;"> </div>
              <div style="float:left; width:100px;" class="graytxt3"> French </div>
               <?php printRadio("LNG|French"); ?>
                
              
              <div style="float:left; width:100px;" class="graytxt3"> Russian </div>
               <?php printRadio("LNG|Russian"); ?>
                
              
              <div style="clear:left; height:8px;"> </div>
              <div style="float:left; width:100px;" class="graytxt3"> German </div>
              <?php printRadio("LNG|German"); ?>
                
              
              <div style="float:left; width:100px;" class="graytxt3"> Spanish </div>
              <?php printRadio("LNG|Spanish"); ?>

              <div style="clear:left; height:8px;"> </div>
              <div style="float:left; width:100px;" class="graytxt3"> Hebrew </div>
              <?php printRadio("LNG|Hebrew"); ?>
                
                
              <div style="float:left; width:100px;" class="graytxt3"> Swedish </div>
               <?php printRadio("LNG|Swedish"); ?>
              
              <div style="clear:left; height:8px;"> </div>
              <div style="float:left; width:100px;" class="graytxt3"> Hindi </div>
               <?php printRadio("LNG|Hindi"); ?>
                
              
              <div style="float:left; width:100px;" class="graytxt3"> Swahili </div>
               <?php printRadio("LNG|Swahili"); ?>
               <div style="float:left; width:100px;" class="graytxt3"> Ukrainian </div>
               <?php printRadio("LNG|Ukrainian"); ?>               
              <div style="float:left; width:100px;" class="graytxt3"> Polish </div>
               <?php printRadio("LNG|Polish"); ?>
			   </div>
          </div></div>

<!--
          <div style="float:left; padding-top:0px;">
            <div style="float:left;" class="btnbg">
              <div style="float:left; padding:0px 0 0 60px;"> <a href="#" class="whitetxt1"> employment info </a> </div>
            </div>
            <div style="float:left; padding:0px 0 0 70px;">
              <div style="float:left;" class="bottomarrow"> I’m available to start: </div>
              <div style="clear:left; height:1px;"> </div>
              <div style="float:left; padding:20px 0 0 0px;">
                <div style="float:left;">
					<?php renderStartDateInput(); ?>
                </div> 
                <div style="float:left; padding:3px 0px 0 5px;"><img src="https://<?php echo  $_SERVER['SERVER_NAME'] . '/' .  $_SERVER['PATH_INFO'] . StrToLower($formpath); ?>/images/star_img.png" alt="" /> </div>
              </div>
              <div style="clear:left; height:40px;"> </div>
              <div style="float:left;">
                <div style="float:left; width:300px;" class="bottomarrow">I’m available these hours: </div>
                <div style="float:left;" class="bottomarrow"> I can work this many hours: </div>
              </div>
              <div style="clear:left; height:1px;"> </div>
              <div style="float:left; padding-top:15px;">
                <div style="float:left;">
                  <select id="AVAIL_HOURS_WEEK" name="AVAIL_HOURS_WEEK" class="styled" style=" width:168px;">
                        <?php AvailHourOption("AVAIL_HOURS_WEEK", ""); ?>
                  </select>
                </div>
		<div style="float:left; padding-left:15px;">
                <div style="float:left; padding:3px 10px 0 0px;"> <img src="https://<?php echo  $_SERVER['SERVER_NAME'] . '/' .  $_SERVER['PATH_INFO'] . StrToLower($formpath); ?>/images/star_img.png" alt="" /> </div>
                <div style="float:left; width:8px;"> &nbsp; </div>
                <div style="float:left; padding-left:105px;" >
                  <select id="AVAIL_SHIFT_WEEKDAY" name="AVAIL_SHIFT_WEEKDAY" class="styled" style=" width:155px;">
                        <?php AvailTimeOption("AVAIL_SHIFT_WEEKDAY", ""); ?>
                  </select>
                </div>
                <div style="float:left; padding:3px 0px 0 0px;"> <img src="https://<?php echo  $_SERVER['SERVER_NAME'] . '/' .  $_SERVER['PATH_INFO'] . StrToLower($formpath); ?>/images/star_img.png" alt="" /> </div>
              </div>
              <div style="clear:left; height:40px;"> </div>
              <div style="float:left;">
                <div style="float:left; width:300px;" class="bottomarrow"> Minimum hourly or yearly pay: </div>
                <div style="float:left;" class="bottomarrow"> I ‘d like this much notice: </div>
              </div>
              <div style="clear:left; padding-top:15px;">
                <div style="float:left;">
                  <input type="text" id="AVAIL_MIN_PAY" name="AVAIL_MIN_PAY" placeholder="<?php the_field_value($item_num, "AVAIL_MIN_PAY", "Hourly or yearly pay (no commas or period)");?>" style="width:200px;"  />
                </div>
                <div style="float:left; padding:3px 0px 0 8px;"> <img src="https://<?php echo  $_SERVER['SERVER_NAME'] . '/' .  $_SERVER['PATH_INFO'] . StrToLower($formpath); ?>/images/star_img.png" alt="" /> </div>
                <div style="float:left; width:91px;"> &nbsp; </div>
                <div style="float:left;" >
                  <select id="AVAIL_NOTICE_NEED" name="AVAIL_NOTICE_NEED" class="styled" style=" width:170px;">
                    <?php NoticePeriodOption("AVAIL_NOTICE_NEED", "");?>
                  </select>
                </div>
                <div style="float:left; padding:3px 0px 0 14px;"> <img src="https://<?php echo  $_SERVER['SERVER_NAME'] . '/' .  $_SERVER['PATH_INFO'] . StrToLower($formpath); ?>/images/star_img.png" alt="" /> </div>
              </div>
              <div style="clear:left; height:40px;"> </div>
              <div style="float:left;">
                <div style="float:left; width:300px;" class="bottomarrow">I’m willing to travel: </div>
                <div style="float:left;" class="bottomarrow"> My personal web link: </div>
              </div>
              <div style="clear:left; height:1px;"> </div>
              <div style="float:left; padding-top:15px;">
                <div style="float:left;">
                  <select id="AVAIL_MILES" name="AVAIL_MILES" class="styled" style=" width:172px;">
                    <?php MilesDistanceOption("AVAIL_MILES", "");?>                    
                  </select>
                </div>
                <div style="float:left; padding:3px 0px 0 14px;"> <img src="https://<?php echo  $_SERVER['SERVER_NAME'] . '/' .  $_SERVER['PATH_INFO'] . StrToLower($formpath); ?>/images/star_img.png" alt="" /> </div>
                <div style="float:left; width:1250x;"> &nbsp; </div>
                <div style="float:left; padding-left:118px;" >
                  <input type="text" id="EM_HTTP2" name="EM_HTTP2" placeholder="<?php the_field_value($item_num, "EM_HTTP2", "www.MyUrl.com");?>" style="width:200px;"   />
                </div>
                <div style="float:left; padding:3px 0px 0 8px;"> <img src="https://<?php echo  $_SERVER['SERVER_NAME'] . '/' .  $_SERVER['PATH_INFO'] . StrToLower($formpath); ?>/images/star_img.png" alt="" /> </div>
              </div>
            </div>
          </div>
          <div style="float:left; padding-top:50px;">
		  <!-- 
            <div style="float:left;" class="btnbg">
              <div style="float:left; padding:16px 0 0 60px;"> <a href="#" class="whitetxt1"> resume </a> </div>
            </div>
            <div style="float:left; padding:20px 0 0 70px;">
              <div style="float:left;" class="bottomarrow1"> Upload resume: <span style="color:#231f20">First time in DOC or RTF (Microsoft Word® or Rich Text Format) for our software </span> </div>
            </div>
            <div style="clear:left; height:1px;"> </div>
            <div style="margin:10px 0 0 70px; float:left; background-color:#f9f9f9">
              <div style="float:left; padding:10px 10px;" >
                <div style="float:left; padding-left:5px;" class="graytxt1"> <input type="FILE" size="30" name="FILE1" id="FILE1"> </div>
              </div>

            </div>

            <div style="clear:left; height:1px;"> </div>
            <div style="float:left; padding-left:70px; height:1px; color:#B22625; font-size:10px;">file name must be under 20 characters; letters &amp; numbers only, no spaces, no dashes, only 1 period</div>
			-->
			
			
            <div style="float:right; padding:60px 60px 30px 0;"> 
			
			<button 
  type="submit"
  onclick="return submit_app();"
  style="
    background-color:#b22625;
    color:#ffffff;
    border:none;
    padding:14px 30px;
    font-size:16px;
    font-weight:600;
    cursor:pointer;
    border-radius:4px;
  ">
  Finish
</button>
			
		</div>

          </div>
        </div>

       
        <input id="radio_field" name="radio_field" value="<?php echo $radio_field; ?>" type="hidden"/>
        <input id="cbo_field" name="cbo_field" value="<?php echo $cbo_field; ?>" type="hidden"/>        
        <input id="text_field" name="text_field" value="<?php echo $text_field; ?>" type="hidden"/>
	<input id="RMail" name="RMail" value="<?php echo $RMail; ?>" type="hidden"/>
        <?php save_form_variables();?>
    </form>
      </div
	  </center>