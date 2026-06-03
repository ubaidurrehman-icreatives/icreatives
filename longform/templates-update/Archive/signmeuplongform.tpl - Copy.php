<div class="rightpart" style="width:1068px;" >
          <div style="float:left;">
            <div style="clear:left; padding:0px 0 0 0px;">
              <div style="float:left; width:200px; height:41px;"><a href="/talent-home-graphic-web-designer-jobs/"><img src="<?php echo  $_SERVER['PATH_INFO'] . $formpath; ?>/images/returntocreativehome.png" alt="" /></a></div>
              <div style="float:left; width:468px;  height:41px;"><img src="<?php echo  $_SERVER['PATH_INFO'] . $formpath; ?>/images/progressbar.png" alt="" /></div>
              <div style="float:left; width:118px;  height:41px;"><a href="#"><img src="<?php echo  $_SERVER['PATH_INFO'] . $formpath; ?>/images/contact.png" alt="" /></a></div>
            </div>
          </div>
      </div>
      <div style="float:left; width:960px;">
        <div style="float:left; width:142px;"> &nbsp; </div>
        <div style="float:right; width:794px; margin-right:10px; padding-bottom:20px;">
          <div style="float:left; width:784px; padding-top:15px;">
            <div style="float:left;">
              <div style="float:left;" class="largetxt1"><span class="redarrowclass1"> [</span> creative talent application </div>
              <div style="float:left; padding:3px 2px 0 3px;" class="redarrowclass1"> >>> </div>
            </div>
            <div style="float:right; padding:10px 40px 0 0px;"> <img src="<?php echo  $_SERVER['PATH_INFO'] . $formpath; ?>/images/thirty_img.png" /> </div>
          </div>
          <div style="float:left; padding-top:8px;">
            <div style="float:left; width:310px;" > <img src="<?php echo  $_SERVER['PATH_INFO'] . $formpath; ?>/images/applyimg.png" alt="" /> </div>
            <div style="float:left; width:445px; padding-top:40px;" class="applytxt"> Please fill out the form below to qualify for an interview.
              It will take approximately 30 Minutes to Complete. </div>
          </div>

			<div style="clear:left; float:left; padding-top:50px;color:white;">
          		   <div style="clear:left; float:left; width:45%; border-radius: 0px 30px 30px 0px; background: #9D9898; ">
            			<div style="float:left; padding:60px 50px; height:125px;">
					All icreatives applicants must have at least three years of practical experience in their field. i creatives recruits based on client needs. Before you fill out the application, please prepare three to five of your best portfolio pieces to be uploaded to our server.
				</div>
			   </div>
            		   <div style="float:left; width:45%; border-radius: 30px 0px 0px 30px; background: #9D9898; ">
				<div style="float:right; padding:60px 50px;  height:125px;"> 
					You will also be asked to upload a resume twice: Once in DOC or RFT (Microsoft Word® or Rich Text Format) for our software and once again in PDF (Adobe Acrobat®) or DOC format for our review.
          			</div>
            		   </div>
			</div>
        
         <form action="/wp-content/themes/vg-mirinae/LongForm/SignMeUpLongForm2.php/" ENCTYPE="multipart/form-data" method="post" id="signupform" name="signupform">
                <input id="item_number" name="item_number" value="<?php echo $item_num ?>" type="hidden" value="" />
                <input id="current_state" name="current_state" value="s1" type="hidden"  />
                <input id="RMail" name="RMail" value="<?php echo $RMail ?>" type="hidden"  />
            
          <div style="clear:left; height:50px;"> &nbsp;</div>            
          <div style="float:left;" >
            <div style="float:left;" class="btnbg">
              <div style="float:left; width:300px;  padding:16px 0 0 60px;"> <a href="#" class="whitetxt1"> contact information </a> </div>
              <div style="float:right; height:37px; padding-right:100px;"><img src="<?php echo  $_SERVER['PATH_INFO'] . $formpath; ?>/images/requiredfield.png" alt="" /></div>
            </div>
            <div style="clear:left; padding:20px 0 0 70px;">
              <div style="float:left;" class="bottomarrow"> “Moniker...”: </div>
              <div style="clear:left; padding:20px 0 0 0px;">
                <div style="float:left;">
 <input type="text" name="First" id="First" placeholder="firstname" value="<?php the_field_value($item_num, "First", ""); ?>" style="width:150px;"  />
                </div>
                <div style="float:left; padding:3px 40px 0 5px;"><img src="<?php echo  $_SERVER['PATH_INFO'] . $formpath; ?>/images/star_img.png" alt="" /> </div>
                <div style="float:left;">
                  <input type="text" id="Last"  name="Last"  placeholder="lastname" value="<?php the_field_value($item_num, "Last", ""); ?>" style="width:172px;"  />
                </div>
                <div style="float:left; padding:3px 40px 0 5px;"><img src="<?php echo  $_SERVER['PATH_INFO'] . $formpath; ?>/images/star_img.png" alt="" /> </div>
                <div style="float:left;">
                  <input type="text" id="NICKNAME"  name="NICKNAME"  placeholder="nickname" value="<?php the_field_value($item_num, "NICKNAME", ""); ?>" style="width:150px;" />
                </div>
              </div>
            </div>
            <div style="float:left; padding:25px 0 0 0px;">
              <div style="float:left;"> <img src="<?php echo  $_SERVER['PATH_INFO'] . $formpath; ?>/images/form_topbar.png" alt="" /> </div>
              <div style="float:left; padding-left:70px;">
                <div style="float:left;" class="bottomarrow"> I’m living large at: </div>
                <div style="clear:left;"> </div>
                <div style="float:left; padding:20px 0 0 0px;">
                  <div style="float:left;">
                    <input id="ADDRESS" name="ADDRESS" type="text"  placeholder="street number and name" value="<?php the_field_value($item_num, "ADDRESS", ""); ?>" style="width:470px;" />	    
                  </div>
		  <div style="float:left;padding-left:7px;"><img src="<?php echo  $_SERVER['PATH_INFO'] . $formpath; ?>/images/star_img.png" alt="" /> </div>
                  <div style="float:left; padding-left:28px;">
                    <input type="text" id="ADDRESS_APT" name="ADDRESS_APT"  placeholder="apt. number" value="<?php the_field_value($item_num, "ADDRESS_APT", ""); ?>" style="width:80px;"  />
                  </div>
                </div>
                
                <div style="float:left; padding:20px 0 0 0px;">
<!--
                  <div style="float:left;" >
                    <select id="Region" name="Region"  style="width:139px;">
                           <?php // RegionOption("Region", "");?>
                    </select>
                  </div>

                  <div style="float:left; padding:3px 20px 0 5px;"><img src="<?php echo  $_SERVER['PATH_INFO'] . $formpath; ?>/images/star_img.png" alt="" /> </div>
-->
                  <div style="float:left;">
                    <input type="text"  id="CITY" name="CITY" placeholder="city" value="<?php the_field_value($item_num, "CITY", ""); ?>" style="width:145px; z-index:1000;"   />
                  </div>
                  <div style="float:left; padding:3px 25px 0 5px;">
                    <img src="<?php echo  $_SERVER['PATH_INFO'] . $formpath; ?>/images/star_img.png" alt="" /> 
                  </div>
                  <div style="float:left; padding-left:0px;">
                      <select size="1" name="State" id="State"  style="width:150px;">
                             <?php StateOption("State", ""); ?>
                      </select>
                  </div> 
                   <div style="float:left; padding:3px 6px 0 10px;"><img src="<?php echo  $_SERVER['PATH_INFO'] . $formpath; ?>/images/star_img.png" alt="" /> </div>
                  

                  <div style="float:left; padding-left:20px;">
                    <input type="text"  id="ZIP" name="ZIP" maxlength="5" placeholder="zipcode" value="<?php the_field_value($item_num, "ZIP", ""); ?>" style="width:76px;"  />
                  </div>
                  <div style="float:left; padding:3px 0px 0 5px;"><img src="<?php echo  $_SERVER['PATH_INFO'] . $formpath; ?>/images/star_img.png" alt="" /> </div>
                </div>
              </div>
            </div>
            <div style="float:left; padding:25px 0 0 0px; ">
              <div style="float:left; width:334px; ">
                <div style="float:left;"> <img src="<?php echo  $_SERVER['PATH_INFO'] . $formpath; ?>/images/form_topbar.png" alt="" /> </div>
                <div style="float:left; padding-left:70px;">
                  <div style="float:left;" class="bottomarrow"> Here’s my digits: </div>
                  <div style="clear:left;"> </div>
                  <div style="float:left;   padding:20px 0 0 0px;">
                    <div style="float:left;;">
                    
                      <input type="text" maxlength="3" id="HOME_PHONE_AREA" 
                      onkeyup="if($(this).val().length == '3'){ $('#HOME_PHONE_PREFIX').focus();}" maxlength="3" id="PHONE_AREA" name="HOME_PHONE_AREA" placeholder="area code" value="<?php the_field_value($item_num, "HOME_PHONE_AREA", ""); ?>" style="width:60px;"  />
                    </div>
                    <div style="float:left; height:9px; padding:9px 3px 0 3px;"><img src="<?php echo  $_SERVER['PATH_INFO'] . $formpath; ?>/images/dot_img.png" alt="" style="vertical-align:top;" /></div>
                    <div style="float:left;">
                      <input type="text" 
                      onkeyup="if($(this).val().length == '3'){ $('#HOME_PHONE_SUBSCRIBER').focus();}"
                      name="HOME_PHONE_PREFIX" id="HOME_PHONE_PREFIX" placeholder="123" value="<?php the_field_value($item_num, "HOME_PHONE_PREFIX", ""); ?>" style="width:25px;" />
                    </div>
                    <div style="float:left; padding:9px 3px 0 3px;  height:9px;"><img src="<?php echo  $_SERVER['PATH_INFO'] . $formpath; ?>/images/dot_img.png" alt="" style="vertical-align:top;" /></div>
                    <div style="float:left;">
                      <input type="text" maxlength="4" id="HOME_PHONE_SUBSCRIBER" name="HOME_PHONE_SUBSCRIBER" placeholder="4567" value="<?php the_field_value($item_num, "HOME_PHONE_SUBSCRIBER", ""); ?>" style="width:60px;" />
                    </div>
                    <div style="float:left; padding:3px 0px 0 5px;"><img src="<?php echo  $_SERVER['PATH_INFO'] . $formpath; ?>/images/star_img.png" alt="" /> </div>
                  </div>
                </div>
                <div style="float:left; padding:5px 0 0 70px; " class="graytxt1"> home (use cell here if primary) </div>
                <div style="clear:left;"> </div>
                <div style="float:left; padding-left:70px;">
                  <div style="float:left;  padding:20px 0 0 0px;">
                    <div style="float:left;">
                      <input type="text" maxlength="3" 
                       onkeyup="if($(this).val().length == '3'){ $('#MOBILE_PHONE_PREFIX').focus();}"
                       name="MOBILE_PHONE_AREA" id="MOBILE_PHONE_AREA"  placeholder="area code" value="<?php the_field_value($item_num, "MOBILE_PHONE_AREA", ""); ?>" style="width:60px;" />
                    </div>
                    <div style="float:left; padding:9px 3px 0 3px;  height:9px;"><img src="<?php echo  $_SERVER['PATH_INFO'] . $formpath; ?>/images/dot_img.png" alt="" style="vertical-align:top;" /></div>
                    <div style="float:left;">
                      <input type="text" maxlength="3" 
                      onkeyup="if($(this).val().length == '3'){ $('#MOBILE_PHONE_SUBSCRIBER').focus();}"
                      name="MOBILE_PHONE_PREFIX" id="MOBILE_PHONE_PREFIX" placeholder="123" value="<?php the_field_value($item_num, "MOBILE_PHONE_PREFIX", ""); ?>" style="width:25px;" />
                    </div>
                    <div style="float:left; padding:9px 3px 0 3px; height:9px;"><img src="<?php echo  $_SERVER['PATH_INFO'] . $formpath; ?>/images/dot_img.png" alt="" style="vertical-align:top;" /></div>
                    <div style="float:left;">
                      <input type="text" maxlength="4" id="MOBILE_PHONE_SUBSCRIBER" name="MOBILE_PHONE_SUBSCRIBER" placeholder="4567" value="<?php the_field_value($item_num, "MOBILE_PHONE_SUBSCRIBER", ""); ?>" style="width:60px;"  />
                    </div>
                    <div style="float:left; padding:3px 0px 0 5px;"><img src="<?php echo  $_SERVER['PATH_INFO'] . $formpath; ?>/images/star_img.png" alt="" /> </div>
                  </div>
                  <div style="float:left; padding:5px 0 0 0px;" class="graytxt1"> mobile </div>
                </div>
              </div>
              <div style="float:right; width:450px;">
                <div style="float:left; background-color:#b22625; width:1px; height:95px; margin-top:66px;"> </div>
                <div style="float:left; background-color:#b22625; width:30px; height:1px; margin-top:110px;"> </div>
                <div style="float:left; padding:100px 0 0 5px;">
                  <div style="float:left;">
                    <select id="EM_USER_DEF4" name="EM_USER_DEF4"  style="width:193px;" >
                         <?php CellCarrierOption("EM_USER_DEF4", ""); ?>
                    </select>
                  </div>
                  <div style="float:left; padding:3px 25px 0 5px;">
                    <img src="<?php echo  $_SERVER['PATH_INFO'] . $formpath; ?>/images/star_img.png" alt="" /> 
                  </div>
                  
                  <div style="clear:left; height:8px;"> </div>
                  <div style="float:left;"  class="graytxt1"> for instant texting of job assignments </div>
                </div>
              </div>
            </div>
            <div style="float:left; padding:25px 0 0 0px; ">
              <div style="float:left; ">
                <div style="float:left;"> <img src="<?php echo  $_SERVER['PATH_INFO'] . $formpath; ?>/images/form_topbar.png" alt="" /> </div>
                <div style="float:left; padding-left:70px;">
                  <div style="float:left;" class="bottomarrow"> Now that we got "old school" out of the way: </div>
                  <div style="clear:left;"> </div>
                  <div style="float:left;   padding:20px 0 0 0px;">
                    <div style="float:left;;">
                      <input type="text" name="Email" id="Email" placeholder="email address" value="<?php the_field_value($item_num, "Email", ""); ?>" style="width:180px;"   />
                    </div>
                    <div style="float:left; padding:3px 0px 0 5px;"> <img src="<?php echo  $_SERVER['PATH_INFO'] . $formpath; ?>/images/star_img.png" alt="" /> </div>
                  </div>
                </div>
              </div>
            </div>
            <div style="float:left; padding:25px 0 0 0px; ">
              <div style="float:left; width:520px;">
                <div style="float:left;"> <img src="<?php echo  $_SERVER['PATH_INFO'] . $formpath; ?>/images/form_topbar.png" alt="" /> </div>
                <div style="float:left; padding-left:70px;">
                  <div style="float:left;" class="bottomarrow"> So how did you find us? : </div>
                  <div style="clear:left;"> </div>
                  <div style="float:left; padding:21px 0 0 0px;">
                    <div style="float:left;">                        
                      <select size="1" name="RECRUITING_SOURCE" id="RECRUITING_SOURCE"  style="width:193px; z-index:1000;">
                             <?php RecruitingSourceOption("RECRUITING_SOURCE", ""); ?>
                      </select>
                        
                    </div>
                  </div>
                </div>
                
                
                    <div style="float:left; padding-left:40px; width:200px;">
                        <div style="float:left;" class="bottomarrow"> Discipline : </div>
                        <div style="clear:left;"> </div>
                        <div style="float:left; padding:21px 0 0 0px;">
                            <div style="float:left;">
                                <select size="1" name="Discipline" id="Discipline" style="z-index:1000;">
                                    <?php DisciplineOption("Discipline", ""); ?>
                                </select>
                            </div>
                        </div>
                    </div>   
              </div>
              <div style="float:left; width:225px; padding:30px 0 0 35px;">

                <!-- <div style="float:left;" class="bottomarrow"> your job title </div> -->
                <div style="clear:left; height:3px;"> </div>
                <div style="float:left; padding:20px 0 0 0px;">
<!--
                  <div style="float:left;">
                    <input type="text"  id="PAST1_JOB_TITLE" name="PAST1_JOB_TITLE" placeholder="your job title" value="<?php the_field_value($item_num, "PAST1_JOB_TITLE", ""); ?>" style="width:160px;"  />
                    
                   
                  </div>
                   <div style="float:left; padding:5px 0px 0 5px;"> 
                	<img src="<?php echo  $_SERVER['PATH_INFO'] . $formpath; ?>/images/star_img.png" alt="" /> 
                </div>
-->                
                </div>
                <div style="clear:left;"> </div>

              </div>
            </div>
            <div style="float:left; padding-top:30px;">
              <div style="float:left;" class="btnbg">
                <div style="float:left; padding:16px 0 0 60px;"> <a href="#" class="whitetxt1"> work preference </a> </div>
              </div>
              <div class="applytxt1" style="padding:30px 0 0 70px; float:left;"> Of course we would all prefer not to work at all but you’re here <br />
                for a reason so make your selections below. </div>
              <div style="clear:left; height:1px;"> </div>
                
              <div style="padding:30px 0 0 70px; float:left;">
                <div style="float:left; text-align:right; padding-top:25px;">
                  <ul class="blackclass1">
                    <li> Are you willing to work temporary? </li>
                    <li> Are you looking for a full-time career position? </li>
                    <li> Are you looking for contract positions? </li>
                  </ul>
                </div>
             
                <?php printAVAIL(); ?>
                <div style='float:left'>
                <div style=" padding:28px 0px 0 5px;"> 
                	<img src="<?php echo  $_SERVER['PATH_INFO'] . $formpath; ?>/images/star_img.png" alt="" /> 
                </div>
                <div style="padding:13px 0px 0 5px;"> 
                	<img src="<?php echo  $_SERVER['PATH_INFO'] . $formpath; ?>/images/star_img.png" alt="" /> 
                </div>
                <div style=" padding:13px 0px 0 5px;"> 
                	<img src="<?php echo  $_SERVER['PATH_INFO'] . $formpath; ?>/images/star_img.png" alt="" /> 
                </div>
                </div>
                   
              </div>
            </div>
            <div style="float:left; padding-top:30px;">
              <div style="float:left;" class="btnbg">
                <div style="float:left; padding:16px 0 0 60px;"> <a href="#" class="whitetxt1"> work experience </a> </div>
              </div>
              <div style="float:left; padding:20px 0 0 70px;">
                <div style="float:left;" class="bottomarrow">Job No. 1 (most recent first): </div>
                <div style="clear:left; height:1px;"> </div>
                <div style="float:left; padding:20px 0 0 0px;">
                  <div style="float:left;">
                    <input type="text" id="PAST1_COMPANY" name="PAST1_COMPANY" placeholder="company name" value="<?php the_field_value($item_num, "PAST1_COMPANY", "");?>" style="width:305px;"  />
                  </div>
                  <div style="float:left; padding:3px 0px 0 9px;"><img src="<?php echo  $_SERVER['PATH_INFO'] . $formpath; ?>/images/star_img.png" alt="" /> </div>
                  <div style="float:left; padding-left:22px;">
                    <input type="text" id="PDF_PAST1_URL" name="PDF_PAST1_URL" placeholder="company url" value="<?php the_field_value($item_num, "PDF_PAST1_URL", ""); ?>" style="width:257px;"   />
                  </div>
                  <div style="float:left; padding:3px 0px 0 11px;"><img src="<?php echo  $_SERVER['PATH_INFO'] . $formpath; ?>/images/star_img.png" alt="" /> </div>
                </div>
                <div style="float:left; padding:20px 0 0 0px;">

                  
                  <div style="float:left;">
                    <input type="text" name="PAST1_CITY" id="PAST1_CITY"  placeholder="city" value="<?php the_field_value($item_num, "PAST1_CITY", ""); ?>" style="width:305px;" />
                  </div>

		  <div style="float:left; padding:3px 15px 0 10px;"><img src="<?php echo  $_SERVER['PATH_INFO'] . $formpath; ?>/images/star_img.png" alt="" /> </div>
                  <div style="float:left; padding-left:06px;">
                    <select id="PAST1_STATE" name="PAST1_STATE" style="width:154px;">
                       <?php StateOption("PAST1_STATE", ""); ?>
                    </select>
                  </div>
                  <div style="float:left; padding:3px 10px 0 5px;"><img src="<?php echo  $_SERVER['PATH_INFO'] . $formpath; ?>/images/star_img.png" alt="" /> </div>
                  <div style="float:left;  padding-left:00px;">
                    <input type="text" id="PAST1_ZIP" name="PAST1_ZIP" maxlength="5" placeholder="zipcode" value="<?php the_field_value($item_num, "PAST1_ZIP", ""); ?>" style="width:76px;"  />
                  </div>

                  <div style="float:left;   padding:20px 0 0 0px;">
                    <div style="float:left;  padding-top:1px;">
                      <input type="text" id="PAST1_PHONE_AREA" name="PAST1_PHONE_AREA" 
                      onkeyup="if($(this).val().length == '3'){ $('#PAST1_PHONE_PREFIX').focus();}"
                      placeholder="area code" value="<?php the_field_value($item_num, "PAST1_PHONE_AREA", ""); ?>" style="width:25px;"  />
                    </div>
                    <div style="float:left; height:9px; padding:9px 3px 0 3px;"><img src="<?php echo  $_SERVER['PATH_INFO'] . $formpath; ?>/images/dot_img.png" alt="" style="vertical-align:top;" /></div>
                    <div style="float:left; padding-top:1px;">
                      <input type="text" 
                      onkeyup="if($(this).val().length == '3'){ $('#PAST1_PHONE_SUBSCRIBER').focus();}"
                      name="PAST1_PHONE_PREFIX" id="PAST1_PHONE_PREFIX" placeholder="123" value="<?php the_field_value($item_num, "PAST1_PHONE_PREFIX", ""); ?>" style="width:25px;" />
                    </div>
                    <div style="float:left; padding:9px 3px 0 4px;  height:9px;"><img src="<?php echo  $_SERVER['PATH_INFO'] . $formpath; ?>/images/dot_img.png" alt="" style="vertical-align:top;" /></div>
                    <div style="float:left;  padding-top:1px;">
                      <input type="text" maxlength="4" id="PAST1_PHONE_SUBSCRIBER" name="PAST1_PHONE_SUBSCRIBER" placeholder="4567" value="<?php the_field_value($item_num, "PAST1_PHONE_SUBSCRIBER", ""); ?>" style="width:35px;"  />
                    </div>

                    <div style="float:left; padding-left:16px;">
                      <select  id="PAST1_CAN_CONTACT" name="PAST1_CAN_CONTACT" style="width:150px;">
                        <?php EmploymentContact("PAST1_CAN_CONTACT", ""); ?>
                      </select>
                    </div>

                    <div style="float:left; padding-left:48px;">
		      <input type="text"  id="PAST1_JOB_TITLE" name="PAST1_JOB_TITLE" placeholder="your job title" value="<?php the_field_value($item_num, "PAST1_JOB_TITLE", ""); ?>" style="width:257px;"  />
                    </div>
                    <div style="float:left; padding:3px 0px 0 11px;"><img src="<?php echo  $_SERVER['PATH_INFO'] . $formpath; ?>/images/star_img.png" alt="" /> </div>


                    <div style="clear:left; height:20px;"> </div>

                    <div style="float:left;">
                    <input type="text" id="PAST1_SUPERVISOR" name="PAST1_SUPERVISOR" placeholder="supervisor name" value="<?php the_field_value($item_num, "PAST1_SUPERVISOR", ""); ?>" style="width:150px;"  />
                    </div>
                    <div style="float:left; padding:3px 0px 0 7px;"><img src="<?php echo  $_SERVER['PATH_INFO'] . $formpath; ?>/images/star_img.png" alt="" /> </div>

                    <div style="float:left; padding-left:10px;">
                      <input type="text" id="PAST1_SUPERVISOR_TITLE" name="PAST1_SUPERVISOR_TITLE" placeholder="supervisor title" value="<?php the_field_value($item_num, "PAST1_SUPERVISOR_TITLE", ""); ?>" style="width:116px;" />
                    </div>
                    <div style="float:left; padding:3px 0px 0 10px;"><img src="<?php echo  $_SERVER['PATH_INFO'] . $formpath; ?>/images/star_img.png" alt="" /> </div>


                    
                    <div style="float:left; padding-left:20px;">
                    	<?php echo getSelectedMonth($item_num,"PAST1_START_MONTH") ?> 
		    </div>
		    <div style="float:left; padding-left:25px;">
                    	<?php echo getSelectedYears($item_num,"PAST1_START_YEAR","year start") ?>                                             
                    </div>

                    <div style="float:left; padding:3px 0px 0 5px;"><img src="<?php echo  $_SERVER['PATH_INFO'] . $formpath; ?>/images/star_img.png" alt="" /> </div>
                    
                    
                    <div style="clear:left; height:20px;"> </div>
                    <div style="float:left;  padding-top:2px;">

             	      <input type="text" id="PAST1_END_WAGE" name="PAST1_END_WAGE" placeholder="wages" value="<?php the_field_value($item_num, "PAST1_END_WAGE", "");?>" style="width:75px;"  />
		     </div>
		     <div style="float:left; Padding-Left:10px;">
		        <select id="PAST1_END_WAGE_TYPE" name="PAST1_END_WAGE_TYPE" style="width:120px;">
                           <?php WageOption("PAST1_END_WAGE_TYPE", ""); ?>
                        </select>
		     </div>


                    <div style="float:left; padding-left:140px;">
                    	<?php echo getSelectedMonth($item_num,"PAST1_END_MONTH") ?> 
		    </div>
		    <div style="float:left; padding-left:25px;">
                    	<?php echo getSelectedYears($item_num,"PAST1_END_YEAR","year end&nbsp;&nbsp;&nbsp;") ?>                                             
                    </div>

                    <div style="clear:left; height:20px;"> </div>
                    <div style="float:left;" class="graytxt1"> Job description: </div>
                    <div style="clear:left; height:2px;"> </div>
                    <div style="float:left;">
                      <textarea  id="PAST1_JOB_DUTIES" name="PAST1_JOB_DUTIES"  rows="7" cols="74" style="background-color:#edebeb; border:0px;"><?php the_field_value($item_num, "PAST1_JOB_DUTIES", ""); ?></textarea>
                    </div>
                      <div style="float:left; padding:3px 0px 0 5px;"><img src="<?php echo  $_SERVER['PATH_INFO'] . $formpath; ?>/images/star_img.png" alt="" /> </div>
                  </div>
                </div>
              </div>
            </div>
            <div style="float:left; padding-top:30px;">
              <div style="float:left;"> <img src="<?php echo  $_SERVER['PATH_INFO'] . $formpath; ?>/images/form_topbar.png" alt="" /> </div>
              <div style="float:left; padding:20px 0 0 70px;">
                <div style="float:left;" class="bottomarrow">Job No. 2: </div>

<!-- -->
                <div style="clear:left; height:1px;"> </div>
                <div style="float:left; padding:20px 0 0 0px;">
                  <div style="float:left;">
                    <input type="text" id="PAST2_COMPANY" name="PAST2_COMPANY" placeholder="company name" value="<?php the_field_value($item_num, "PAST2_COMPANY", "");?>" style="width:305px;"  />
                  </div>
                  <div style="float:left; padding-left:42px;">
                    <input type="text" id="PDF_PAST2_URL" name="PDF_PAST2_URL" placeholder="company url" value="<?php the_field_value($item_num, "PDF_PAST2_URL", ""); ?>" style="width:257px;"   />
                  </div>
                  </div>
                <div style="float:left; padding:20px 0 0 0px;">

                  
                  <div style="float:left;">
                    <input type="text" name="PAST2_CITY" id="PAST2_CITY"  placeholder="city" value="<?php the_field_value($item_num, "PAST2_CITY", ""); ?>" style="width:305px;" />
                  </div>

		  <div style="float:left; padding-left:40px;">
                    <select id="PAST2_STATE" name="PAST2_STATE" style="width:154px;">
                       <?php StateOption("PAST2_STATE", ""); ?>
                    </select>
                  </div>
                  <div style="float:left;  padding-left:30px;">
                    <input type="text" id="PAST2_ZIP" name="PAST2_ZIP" maxlength="5" placeholder="zipcode" value="<?php the_field_value($item_num, "PAST2_ZIP", ""); ?>" style="width:76px;"  />
                  </div>

                  <div style="float:left;   padding:20px 0 0 0px;">
                    <div style="float:left;  padding-top:1px;">
                      <input type="text" id="PAST2_PHONE_AREA" name="PAST2_PHONE_AREA" 
                      onkeyup="if($(this).val().length == '3'){ $('#PAST2_PHONE_PREFIX').focus();}"
                      placeholder="area code" value="<?php the_field_value($item_num, "PAST2_PHONE_AREA", ""); ?>" style="width:25px;"  />
                    </div>
                    <div style="float:left; height:9px; padding:9px 3px 0 3px;"><img src="<?php echo  $_SERVER['PATH_INFO'] . $formpath; ?>/images/dot_img.png" alt="" style="vertical-align:top;" /></div>

                    <div style="float:left; padding-top:1px;">
                      <input type="text" 
                      onkeyup="if($(this).val().length == '3'){ $('#PAST2_PHONE_SUBSCRIBER').focus();}"
                      name="PAST2_PHONE_PREFIX" id="PAST2_PHONE_PREFIX" placeholder="123" value="<?php the_field_value($item_num, "PAST2_PHONE_PREFIX", ""); ?>" style="width:25px;" />
                    </div>
                    <div style="float:left; height:9px; padding:9px 3px 0 3px;"><img src="<?php echo  $_SERVER['PATH_INFO'] . $formpath; ?>/images/dot_img.png" alt="" style="vertical-align:top;" /></div>

                    <div style="float:left;  padding-top:1px;">
                      <input type="text" maxlength="4" id="PAST2_PHONE_SUBSCRIBER" name="PAST2_PHONE_SUBSCRIBER" placeholder="4567" value="<?php the_field_value($item_num, "PAST2_PHONE_SUBSCRIBER", ""); ?>" style="width:35px;"  />
                    </div>

                    <div style="float:left; padding-left:16px;">
                      <select  id="PAST2_CAN_CONTACT" name="PAST2_CAN_CONTACT" style="width:150px;">
                        <?php EmploymentContact("PAST2_CAN_CONTACT", ""); ?>
                      </select>
                    </div>

                    <div style="float:left; padding-left:48px;">
		      <input type="text"  id="PAST2_JOB_TITLE" name="PAST2_JOB_TITLE" placeholder="your job title" value="<?php the_field_value($item_num, "PAST2_JOB_TITLE", ""); ?>" style="width:257px;"  />
                    </div>


                    <div style="clear:left; height:20px;"> </div>

                    <div style="float:left;">
                    <input type="text" id="PAST2_SUPERVISOR" name="PAST2_SUPERVISOR" placeholder="supervisor name" value="<?php the_field_value($item_num, "PAST2_SUPERVISOR", ""); ?>" style="width:150px;"  />
                    </div>

                    <div style="float:left; padding-left:10px;">
                      <input type="text" id="PAST2_SUPERVISOR_TITLE" name="PAST2_SUPERVISOR_TITLE" placeholder="supervisor title" value="<?php the_field_value($item_num, "PAST2_SUPERVISOR_TITLE", ""); ?>" style="width:116px;" />
                    </div>


                    
                    <div style="float:left; padding-left:57px;">
                    	<?php echo getSelectedMonth($item_num,"PAST2_START_MONTH") ?> 
		    </div>
		    <div style="float:left; padding-left:25px;">
                    	<?php echo getSelectedYears($item_num,"PAST2_START_YEAR","year start") ?>                                             
                    </div>

                    <div style="clear:left; height:20px;"> </div>
                    <div style="float:left;  padding-top:2px;">

             	      <input type="text" id="PAST2_END_WAGE" name="PAST2_END_WAGE" placeholder="wages" value="<?php the_field_value($item_num, "PAST2_END_WAGE", "");?>" style="width:75px;"  />
		     </div>
		     <div style="float:left; Padding-Left:10px;">
		        <select id="PAST2_END_WAGE_TYPE" name="PAST2_END_WAGE_TYPE" style="width:120px;">
                           <?php WageOption("PAST2_END_WAGE_TYPE", ""); ?>
                        </select>
		     </div>


                    <div style="float:left; padding-left:140px;">
                    	<?php echo getSelectedMonth($item_num,"PAST2_END_MONTH") ?> 
		    </div>
		    <div style="float:left; padding-left:25px;">
                    	<?php echo getSelectedYears($item_num,"PAST2_END_YEAR","year end&nbsp;&nbsp;&nbsp;") ?>                                             
                    </div>

                    <div style="clear:left; height:20px;"> </div>
                    <div style="float:left;" class="graytxt1"> Job description: </div>
                    <div style="clear:left; height:2px;"> </div>
                    <div style="float:left;">
                      <textarea  id="PAST2_JOB_DUTIES" name="PAST2_JOB_DUTIES"  rows="7" cols="74" style="background-color:#edebeb; border:0px;"><?php the_field_value($item_num, "PAST2_JOB_DUTIES", ""); ?></textarea>
                    </div>
                  </div>
                </div>
              </div>
            </div>

<!-- -->

            <div style="float:left; padding-top:30px;">
              <div style="float:left;"> <img src="<?php echo  $_SERVER['PATH_INFO'] . $formpath; ?>/images/form_topbar.png" alt="" /> </div>
              <div style="float:left; padding:20px 0 0 70px;">
                <div style="float:left;" class="bottomarrow">Job No. 3: </div>




<!-- -->
<!-- -->
                <div style="clear:left; height:1px;"> </div>
                <div style="float:left; padding:20px 0 0 0px;">
                  <div style="float:left;">
                    <input type="text" id="PAST3_COMPANY" name="PAST3_COMPANY" placeholder="company name" value="<?php the_field_value($item_num, "PAST3_COMPANY", "");?>" style="width:305px;"  />
                  </div>
                  <div style="float:left; padding-left:42px;">
                    <input type="text" id="PDF_PAST3_URL" name="PDF_PAST3_URL" placeholder="company url" value="<?php the_field_value($item_num, "PDF_PAST3_URL", ""); ?>" style="width:257px;"   />
                  </div>
                  </div>
                <div style="float:left; padding:20px 0 0 0px;">

                  
                  <div style="float:left;">
                    <input type="text" name="PAST3_CITY" id="PAST3_CITY"  placeholder="city" value="<?php the_field_value($item_num, "PAST3_CITY", ""); ?>" style="width:305px;" />
                  </div>

		  <div style="float:left; padding-left:40px;">
                    <select id="PAST3_STATE" name="PAST3_STATE" style="width:154px;">
                       <?php StateOption("PAST3_STATE", ""); ?>
                    </select>
                  </div>
                  <div style="float:left;  padding-left:30px;">
                    <input type="text" id="PAST3_ZIP" name="PAST3_ZIP" maxlength="5" placeholder="zipcode" value="<?php the_field_value($item_num, "PAST3_ZIP", ""); ?>" style="width:76px;"  />
                  </div>

                  <div style="float:left;   padding:20px 0 0 0px;">
                    <div style="float:left;  padding-top:1px;">
                      <input type="text" id="PAST3_PHONE_AREA" name="PAST3_PHONE_AREA" 
                      onkeyup="if($(this).val().length == '3'){ $('#PAST3_PHONE_PREFIX').focus();}"
                      placeholder="area code" value="<?php the_field_value($item_num, "PAST3_PHONE_AREA", ""); ?>" style="width:25px;"  />
                    </div>
                    <div style="float:left; height:9px; padding:9px 3px 0 3px;"><img src="<?php echo  $_SERVER['PATH_INFO'] . $formpath; ?>/images/dot_img.png" alt="" style="vertical-align:top;" /></div>

                    <div style="float:left; padding-top:1px;">
                      <input type="text" 
                      onkeyup="if($(this).val().length == '3'){ $('#PAST3_PHONE_SUBSCRIBER').focus();}"
                      name="PAST3_PHONE_PREFIX" id="PAST3_PHONE_PREFIX" placeholder="123" value="<?php the_field_value($item_num, "PAST3_PHONE_PREFIX", ""); ?>" style="width:25px;" />
                    </div>
                    <div style="float:left; height:9px; padding:9px 3px 0 3px;"><img src="<?php echo  $_SERVER['PATH_INFO'] . $formpath; ?>/images/dot_img.png" alt="" style="vertical-align:top;" /></div>

                    <div style="float:left;  padding-top:1px;">
                      <input type="text" maxlength="4" id="PAST3_PHONE_SUBSCRIBER" name="PAST3_PHONE_SUBSCRIBER" placeholder="4567" value="<?php the_field_value($item_num, "PAST3_PHONE_SUBSCRIBER", ""); ?>" style="width:35px;"  />
                    </div>

                    <div style="float:left; padding-left:16px;">
                      <select  id="PAST3_CAN_CONTACT" name="PAST3_CAN_CONTACT" style="width:150px;">
                        <?php EmploymentContact("PAST3_CAN_CONTACT", ""); ?>
                      </select>
                    </div>

                    <div style="float:left; padding-left:48px;">
		      <input type="text"  id="PAST3_JOB_TITLE" name="PAST3_JOB_TITLE" placeholder="your job title" value="<?php the_field_value($item_num, "PAST3_JOB_TITLE", ""); ?>" style="width:257px;"  />
                    </div>


                    <div style="clear:left; height:20px;"> </div>

                    <div style="float:left;">
                    <input type="text" id="PAST3_SUPERVISOR" name="PAST3_SUPERVISOR" placeholder="supervisor name" value="<?php the_field_value($item_num, "PAST3_SUPERVISOR", ""); ?>" style="width:150px;"  />
                    </div>

                    <div style="float:left; padding-left:10px;">
                      <input type="text" id="PAST3_SUPERVISOR_TITLE" name="PAST3_SUPERVISOR_TITLE" placeholder="supervisor title" value="<?php the_field_value($item_num, "PAST3_SUPERVISOR_TITLE", ""); ?>" style="width:116px;" />
                    </div>


                    
                    <div style="float:left; padding-left:57px;">
                    	<?php echo getSelectedMonth($item_num,"PAST3_START_MONTH") ?> 
		    </div>
		    <div style="float:left; padding-left:25px;">
                    	<?php echo getSelectedYears($item_num,"PAST3_START_YEAR","year start") ?>                                             
                    </div>

                    <div style="clear:left; height:20px;"> </div>
                    <div style="float:left;  padding-top:2px;">

             	      <input type="text" id="PAST3_END_WAGE" name="PAST3_END_WAGE" placeholder="wages" value="<?php the_field_value($item_num, "PAST3_END_WAGE", "");?>" style="width:75px;"  />
		     </div>
		     <div style="float:left; Padding-Left:10px;">
		        <select id="PAST3_END_WAGE_TYPE" name="PAST3_END_WAGE_TYPE" style="width:120px;">
                           <?php WageOption("PAST3_END_WAGE_TYPE", ""); ?>
                        </select>
		     </div>


                    <div style="float:left; padding-left:140px;">
                    	<?php echo getSelectedMonth($item_num,"PAST3_END_MONTH") ?> 
		    </div>
		    <div style="float:left; padding-left:25px;">
                    	<?php echo getSelectedYears($item_num,"PAST3_END_YEAR","year end&nbsp;&nbsp;&nbsp;") ?>                                             
                    </div>

                    <div style="clear:left; height:20px;"> </div>
                    <div style="float:left;" class="graytxt1"> Job description: </div>
                    <div style="clear:left; height:2px;"> </div>
                    <div style="float:left;">
                      <textarea  id="PAST3_JOB_DUTIES" name="PAST3_JOB_DUTIES"  rows="7" cols="74" style="background-color:#edebeb; border:0px;"><?php the_field_value($item_num, "PAST3_JOB_DUTIES", ""); ?></textarea>
                    </div>
                  </div>
                </div>
              </div>
            </div>

<!-- -->
            <div style="float:left; padding-top:30px;">
              <div style="float:left;" class="btnbg">
                <div style="float:left; padding:16px 0 0 60px;"> <a href="#" class="whitetxt1"> Education</a> </div>
              </div>
                
              <div style="float:left; padding:20px 0 0 70px;">
                <div style="float:left;" class="bottomarrow">School No. 1: </div>
                <div style="clear:left; height:1px;"> </div>
                <div style="float:left; padding:20px 0 0 0px;">
                  <div style="float:left;">
                    <input type="text" id="EDUC1_SCHOOL_NAME" name="EDUC1_SCHOOL_NAME" placeholder="educational institution" value="<?php the_field_value($item_num, "EDUC1_SCHOOL_NAME", ""); ?>" style="width:305px;"   />
                  </div>
                  <div style="float:left; padding:3px 0px 0 5px;"><img src="<?php echo  $_SERVER['PATH_INFO'] . $formpath; ?>/images/star_img.png" alt="" /> </div>
                  <div style="float:left; padding-left:25px;">
                    <select id="EDUC1_DEGREE" name="EDUC1_DEGREE" style=" width:173px;">
                      <?php DegreeOption("EDUC1_DEGREE", "");?>
                    </select>
                  </div>
                  <div style="float:left; padding:3px 0px 0 5px;"><img src="<?php echo  $_SERVER['PATH_INFO'] . $formpath; ?>/images/star_img.png" alt="" /> </div>
                </div>
                <div style="clear:left; height:20px;"> </div>
                <div style="float:left;">
                  <input type="text" id="EDUC1_CITY" name="EDUC1_CITY" placeholder="city" value="<?php the_field_value($item_num, "EDUC1_CITY", ""); ?>" style="width:152px;"  />
                </div>
                <div style="float:left; padding:3px 0px 0 5px;"><img src="<?php echo  $_SERVER['PATH_INFO'] . $formpath; ?>/images/star_img.png" alt="" /> </div>
                <div style="float:left; padding-left:24px;">
                  <select id="EDUC1_STATE" name="EDUC1_STATE" style=" width:155px;">
                    <?php StateOption("EDUC1_STATE",""); ?>
                  </select>
                </div>
                <div style="float:left; padding:3px 0px 0 5px;"><img src="<?php echo  $_SERVER['PATH_INFO'] . $formpath; ?>/images/star_img.png" alt="" /> </div>
                <div style="clear:left; height:20px;"> </div>                        
                <div style="float:left;">
                  <input type="text"  id="EDUC1_MAJOR" name="EDUC1_MAJOR" placeholder="field of study" value="<?php the_field_value($item_num, "EDUC1_MAJOR", ""); ?>" style="width:220px;" />
                </div>
                <div style="float:left; padding:3px 0px 0 5px;"><img src="<?php echo  $_SERVER['PATH_INFO'] . $formpath; ?>/images/star_img.png" alt="" /> </div>
                    <div style="float:left; padding-left:24px;">  
                    
                         <?php echo getSelectedMonth($item_num,"EDUC1_END_MONTH") ?> 
                    	<?php echo getSelectedYears($item_num,"EDUC1_END_YEAR","year grad") ?>                      
                    </div>
                      <div style="float:left; padding:3px 0px 0 5px;"><img src="<?php echo  $_SERVER['PATH_INFO'] . $formpath; ?>/images/star_img.png" alt="" /> </div>
                    
                    

              </div>
            </div>
            <div style="float:left; padding-top:30px;">                
              <div style="float:left;"> <img src="<?php echo  $_SERVER['PATH_INFO'] . $formpath; ?>/images/form_topbar.png" alt="" /> </div>
                    <div style="float:left; padding:20px 0 0 70px;">
                    <div style="float:left;" class="bottomarrow">School No. 2: </div>
                    <div style="clear:left; height:1px;"> </div>
                    <div style="float:left; padding:20px 0 0 0px;">
                    <div style="float:left;">
                        <input type="text" id="EDUC2_SCHOOL_NAME" name="EDUC2_SCHOOL_NAME" value="<?php the_field_value($item_num, "EDUC2_SCHOOL_NAME", "educational institution"); ?>" style="width:305px;"  onfocus="if(this.value=='educational institution')this.value='';" onblur="if(this.value=='')this.value='educational institution';" />
                    </div>
                    <div style="float:left; padding:3px 0px 0 5px;"> </div>
                    <div style="float:left; padding-left:25px;">
                        <select id="EDUC2_DEGREE" name"EDUC2_DEGREE" style=" width:130px;">
                                <?php DegreeOption("EDUC2_DEGREE",""); ?>                      
                        </select>
                    </div>
                    <div style="float:left; padding:3px 0px 0 5px;"></div>
                    </div>
                    <div style="clear:left; height:20px;"> </div>
                    <div style="float:left;">
                    <input type="text"  id="EDUC2_CITY" name="EDUC2_CITY" value="<?php the_field_value($item_num, "EDUC2_CITY", "city"); ?>" style="width:152px;"  onfocus="if(this.value=='city')this.value='';" onblur="if(this.value=='')this.value='city';" />
                    </div>
                    <div style="float:left; padding-left:24px;">
                    <select id="EDUC2_STATE" name="EDUC2_STATE" style=" width:150px;">
                        <?php StateOption("EDUC2_STATE", "");?>                  
                    </select>
                    </div>
                    <div style="clear:left; height:20px;"> </div>                        
                        
                    <div style="float:left;">
                    <input type="text" id="EDUC2_MAJOR" name="EDUC2_MAJOR"  placeholder="field of study" value="<?php the_field_value($item_num, "EDUC2_MAJOR", ""); ?>" style="width:220px;"   />
                    </div>
                    <div style="float:left; padding-left:24px;"> 
                    
                         <?php echo getSelectedMonth($item_num,"EDUC2_END_MONTH") ?> 
                    	<?php echo getSelectedYears($item_num,"EDUC2_END_YEAR","year grad") ?>  
                    
                    </div>

                    <div style="float:left; padding:3px 0px 0 5px;"> </div>
                        
                </div>
<!--
                <div style="float:right; padding:40px 30px 0 0;"> <a href="#" onclick="submit_app();"> <img src="<?php echo  $_SERVER['PATH_INFO'] . $formpath; ?>/images/nextskills.png" alt="" /> </a> </div>
-->
<div style="float:right; padding:40px 60px 0 0;">
<input type="image" name="Submit" onclick="return all_required_filed_valid();" src="<?php echo  $_SERVER['PATH_INFO'] . $formpath; ?>/images/nextskills.png" style="height:auto; background-color:#FFF; border:none;">
</div>



            </div>
          </div>

        <input id="radio_field" name="radio_field" value="<?php echo $radio_field ?>" type="hidden"/>
        <input id="cbo_field" name="cbo_field" value="<?php echo $cbo_field ?>" type="hidden"/>        
        <input id="text_field" name="text_field" value="<?php echo $text_field ?>" type="hidden"/>
        <?php // show_variables();?>
        </form>
        
        </div>
      </div>
    </div>    