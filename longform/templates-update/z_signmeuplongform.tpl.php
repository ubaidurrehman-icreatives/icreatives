<div class="rightpart" style="width:1068px;" >
<!--
          <div style="float:left;">
            <div style="clear:left; padding:0px 0 0 0px;">
              <div style="float:left; width:200px; height:41px;"><a href="/"><img src="<?php echo  $_SERVER['PATH_INFO'] . StrToLower($formpath); ?>/images/returntocreativehome.png" alt="" /></a></div>
              <div style="float:left; width:468px;  height:41px;"><img src="<?php echo  $_SERVER['PATH_INFO'] . StrToLower($formpath); ?>/images/progressbar.png" alt="" /></div>
              <div style="float:left; width:118px;  height:41px;"><a href="#"><img src="<?php echo  $_SERVER['PATH_INFO'] . StrToLower($formpath); ?>/images/contact.png" alt="" /></a></div>

			</div>
          </div>
		             -->
      </div>
      <div style="float:left; width:960px;">
        <div style="float:left; width:142px;"> &nbsp; </div>
        <div style="float:right; width:794px; margin-right:10px; padding-bottom:20px;">
          <div style="float:left; width:784px; padding-top:15px;">
            <div style="float:left;">
              <div style="float:left;" class="largetxt1"><span class="redarrowclass1"> [</span> creative talent application </div>
              <div style="float:left; padding:3px 2px 0 3px;" class="redarrowclass1"> >>> </div>
            </div><!--
            <div style="float:right; padding:10px 40px 0 0px;"> <img src="<?php echo  $_SERVER['PATH_INFO'] . StrToLower($formpath); ?>/images/thirty_img.png" /> </div>
			-->
            <div style="float:right; padding:10px 40px 0 0px;"> <span style="font-size: 60px; color: #b22625; font-weight: thin;">10</span> <span style="font-size: 10px;">minutes</span> </div>
          </div>
          <div style="float:left; padding-top:8px;">
            <div style="float:left; width:310px;" > <img src="<?php echo  $_SERVER['PATH_INFO'] . StrToLower($formpath); ?>/images/applyimg.png" alt="" /> </div>
            <div style="float:left; width:445px; padding-top:40px;" class="applytxt"> Please fill out the form below to qualify for an interview.
              It will take approximately 10 Minutes to Complete. </div>
          </div>
			<div style="clear:left; float:left; padding-top:50px;color:white;">
          		   <div style="clear:left; float:left; width:45%; border-radius: 0px 30px 30px 0px; background: #9D9898; ">
            			<div style="float:left; padding:60px 50px; height:80px;">
					All icreatives applicants must have at least three years of practical experience in their field. i creatives recruits based on client needs.
				</div>
			   </div>
            		   <div style="float:left; width:45%; border-radius: 30px 0px 0px 30px; background: #9D9898; ">
				<div style="float:right; padding:60px 50px;  height:80px;"> 
					Before you fill out the application, <br>please prepare 2 to 3 links to your best portfolio pieces.
          			</div>
            		   </div>
			</div>
      
         <form action="/wp-content/themes/porto-child/longform/signmeuplongform2.php/" ENCTYPE="multipart/form-data" method="post" id="signupform" name="signupform">
                <input id="item_number" name="item_number" value="<?php echo $item_num ?>" type="hidden" value="" />
                <input id="current_state" name="current_state" value="s1" type="hidden"  />
                <input id="email" name="email" value="<?php echo $candidate_arr['address']; ?>" type="hidden"  />
                <input id="candidate_id" name="candidate_id" value="<?php echo $candidate_id; ?>" type="hidden"  />
                <input id="recruiter_id" name="recruiter_id" value="<?php echo $recruiter_id; ?>" type="hidden"  />
                <input id="recruiter_email" name="recruiter_email" value="<?php echo $recruiter_email; ?>" type="hidden"  />
                <input id="recruiter_name" name="recruiter_name" value="<?php echo $recruiter_name; ?>" type="hidden"  />
            
          <div style="clear:left; height:50px;"> &nbsp;</div>            
          <div style="float:left;" >
            <div style="float:left;" class="btnbg">
              <div style="float:left; width:300px;  padding:16px 0 0 60px;"> <a href="#" class="whitetxt1"> contact information </a> </div>
              <div style="float:right; height:37px; padding-right:100px;"><img src="<?php echo  $_SERVER['PATH_INFO'] . StrToLower($formpath); ?>/images/requiredfield.png" alt="" /></div>
            </div>
            <div style="clear:left; padding:20px 0 0 70px;">
			
              <div style="float:left;"> Moniker: <?php echo $candidate_arr['full_name']; ?>: </div>
			  <!-- 
              <div style="clear:left; padding:20px 0 0 0px;">
                <div style="float:left;">
				<input type="text" name="full_name" id="full_name" value="<?php echo $candidate_arr['full_name']; ?>" style="width:450px;" readonly />
                </div>
              </div> -->
            </div>
			
            <div style="float:left; padding:25px 0 0 0px;">
              <div style="float:left;"> <img src="<?php echo  $_SERVER['PATH_INFO'] . StrToLower($formpath); ?>/images/form_topbar.png" alt="" /> </div>
              <div style="float:left; padding-left:70px;">
                <div style="float:left;" class="bottomarrow"> I’m living large at: </div>
                <div style="clear:left;"> </div>
                <div style="float:left; padding:20px 0 0 0px;">
                  <div style="float:left;">
                    <input id="address" name="address" type="text"  placeholder="street number, name & apt" value="<?php echo $candidate_arr['address']; ?>" style="width:470px;" />	    
                  </div>
		  <div style="float:left;padding-left:7px;"><img src="<?php echo  $_SERVER['PATH_INFO'] . StrToLower($formpath); ?>/images/star_img.png" alt="" /> </div>
                  
                </div>
                
                <div style="float:left; padding:20px 0 0 0px;">
<!--
                  <div style="float:left;" >
                    <select id="Region" name="Region"  style="width:139px;">
                           <?php // RegionOption("Region", "");?>
                    </select>
                  </div>

                  <div style="float:left; padding:3px 20px 0 5px;"><img src="<?php echo  $_SERVER['PATH_INFO'] . StrToLower($formpath); ?>/images/star_img.png" alt="" /> </div>

                  <div style="float:left;">
                    <input type="text"  id="CITY" name="CITY" placeholder="city" value="<?php the_field_value($item_num, "CITY", ""); ?>" style="width:145px; z-index:1000;"   />
                  </div>
                  <div style="float:left; padding:3px 25px 0 5px;">
                    <img src="<?php echo  $_SERVER['PATH_INFO'] . StrToLower($formpath); ?>/images/star_img.png" alt="" /> 
                  </div>
                  <div style="float:left; padding-left:0px;">
                      <select size="1" name="State" id="State"  style="width:150px;">
                             <?php StateOption("State", ""); ?>
                      </select>
                  </div> 
                   <div style="float:left; padding:3px 6px 0 10px;"><img src="<?php echo  $_SERVER['PATH_INFO'] . StrToLower($formpath); ?>/images/star_img.png" alt="" /> </div>
               -->   
                  <div style="float:left; padding-left:20px;">
                    <input type="text"  id="postalcode" name="postalcode" maxlength="5" placeholder="zip-code" value="<?php echo $candidate_arr['custom_fields']['postalcode']; ?>" style="width:76px;"  />
                  </div>
                  <div style="float:left; padding:3px 0px 0 5px;"><img src="<?php echo  $_SERVER['PATH_INFO'] . StrToLower($formpath); ?>/images/star_img.png" alt="" /> </div>
                </div>
              </div>
            </div>
            <div style="float:left; padding:25px 0 0 0px; ">
              <div style="float:left; width:334px; ">
                <div style="float:left;"> <img src="<?php echo  $_SERVER['PATH_INFO'] . StrToLower($formpath); ?>/images/form_topbar.png" alt="" /> </div>
                <div style="float:left; padding-left:70px;">
                  <div style="float:left;" class="bottomarrow"> Here’s my digits: </div>
                  <div style="clear:left;"> </div>
                  <div style="float:left;   padding:20px 0 0 0px;">
                    <div style="float:left;;">
                    
                      <input type="text" maxlength="15" id="phone_number" maxlength="3" id="phone_number" name="phone_number" placeholder="phone number" value="<?php echo $candidate_arr['phone_number']; ?>" style="width:100px;"  />
                    </div>
                    <div style="float:left; padding:3px 0px 0 5px;"><img src="<?php echo  $_SERVER['PATH_INFO'] . StrToLower($formpath); ?>/images/star_img.png" alt="" /> </div>
                  </div>
                </div>
              </div>
              </div>
            </div>
            <div style="float:left; padding:25px 0 0 0px; ">
              <div style="float:left; ">
                <div style="float:left;"> <img src="<?php echo  $_SERVER['PATH_INFO'] . StrToLower($formpath); ?>/images/form_topbar.png" alt="" /> </div>
                <div style="float:left; padding-left:70px;">
                  <div style="float:left;" class="bottomarrow"> Now that we got "old school" out of the way: </div>
                  <div style="clear:left;"> </div>
                  <div style="float:left;   padding:20px 0 0 0px;">
                    <div style="float:left;;">
                      <input type="text" name="email" id="email" placeholder="email address" value="<?php echo $candidate_arr['email']; ?>" style="width:180px;" readonly  />
                    </div>
                    <div style="float:left; padding:3px 0px 0 5px;"> <img src="<?php echo  $_SERVER['PATH_INFO'] . StrToLower($formpath); ?>/images/star_img.png" alt="" /> </div>
                  </div>
                </div>
              </div>
            </div>
            <div style="float:left; padding:25px 0 0 0px; ">
              <div style="float:left;">
                <div style="float:left;"> <img src="<?php echo  $_SERVER['PATH_INFO'] . StrToLower($formpath); ?>/images/form_topbar.png" alt="" /> </div>
                <div style="float:left; padding-left:70px;">
                  <div style="float:left;" class="bottomarrow"> So how did you find us? : </div>
                  <div style="clear:left;"> </div>
                  <div style="float:left; padding:21px 0 0 0px;">
                    <div style="float:left;">                        
                      <select size="1" name="RECRUITING_SOURCE" id="RECRUITING_SOURCE"  style="width:193px; z-index:1000;">
                             <?php // RecruitingSourceOption("RECRUITING_SOURCE", ""); ?>

<?php
include $_SERVER['DOCUMENT_ROOT']."/wp-content/themes/porto-child/db5.php";
$sql = "SELECT Code, Description FROM ic_recruiting_source order by Code ASC" ;
$result = mysqli_query($conn,$sql) or die("Couldn't execut query");


while ($row = mysqli_fetch_array($result)) {
	
	echo "<option value=";
	echo $row['Code'];
	echo ">";
	echo $row['Description']; 
	echo "</option>";
}
?>


                      </select>
                        
                    </div>
                  </div>
                </div>
                
                
                    <div style="float:left; padding-left:40px; width:150px;">
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
	<!--
				    <div style="float:left; padding-left:40px; width:150px;">
                        <div style="float:left;" class="bottomarrow"> Current Position : </div>
                        <div style="clear:left;"> </div>
                        <div style="float:left; padding:21px 0 0 0px;">
                            <div style="float:left;">
                                   <input id="current_position" name="current_position" type="text"  placeholder="current position" value="<?php echo $candidate_arr['current_position']; ?>" style="width:200px;" />	    
                            </div>
                        </div>
                    </div>   
	-->
              </div>
              <div style="float:left; width:225px; padding:0px 0 0 35px;">
                <!-- <div style="float:left;" class="bottomarrow"> your job title </div> -->
                <div style="clear:left; height:3px;"> </div>
                <div style="float:left; padding:20px 0 0 0px;">               
                </div>
                <div style="clear:left;"> </div>

              </div>
            </div>
            <div style="float:left; padding-top:0px;">
              <div class="applytxt1" style="padding:30px 0 0 70px; float:left;"> Of course we would all prefer not to work at all but you’re here <br />
                for a reason so make your selections below. </div>
              <div style="clear:left; height:1px;"> </div>
                
              <div style="padding:30px 0 0 70px; float:left;">
                <div style="float:left; text-align:right; padding-top:25px;">
                  <ul class="blackclass1">
                    <li> Are you looking for contract positions?  </li>
                    <li> Are you looking for a full-time career position?<br> </li>
                    <li> Are you looking for contract to hire positions? </li>
                    <li> Are you looking for part-time positions? <br> <br></li>
                  </ul>
                </div>
				
				<div style="float:left; padding:0px 0 0 10px;" class="applytxt1"> Yes <br>       
					<input type="radio" name="worktype_contract" value = "on" id="worktype_contract" class="styled" <?php echo $worktype_contract; ?> required/>
					<input type="radio" name="worktype_full-time" value = "on" id="worktype_full-time" class="styled" <?php echo $worktype_full_time; ?> required/>
                    <input type="radio" name="worktype_contact-to-hire" value = "on" id="worktype_contact-to-hire" class="styled" <?php echo $worktype_contract_to_hire; ?> required/>
                    <input type="radio" name="worktype_part-time" value = "on" id="worktype_part-time" class="styled" <?php echo $worktype_part_time; ?> required/>
				
               </div>
			   
				<div style="float:left; padding-left:10px;" class="applytxt1"> No
				      	<img src="<?php echo  $_SERVER['PATH_INFO'] . StrToLower($formpath); ?>/images/star_img.png" alt="" />  <br />
          
					<input type="radio" name="worktype_contract" value = "off" id="worktype_contract" class="styled" <?php echo $worktype_contract !== 'checked' ? 'checked' : ''; ?>/>
					<input type="radio" name="worktype_full-time"  value = "off" id="worktype_full-time" class="styled" <?php echo $worktype_full_time !== 'checked' ? 'checked' : ''; ?>/>
                    <input type="radio" name="worktype_contact-to-hire" value = "off" id="worktype_contact-to-hire" class="styled" <?php echo $worktype_contract_to_hire !== 'checked' ? 'checked' : ''; ?> />
                    <input type="radio" name="worktype_part-time" value = "off" id="worktype_part-time" class="styled" <?php echo $worktype_part_time !== 'checked' ? 'checked' : ''; ?>/>
				
               </div>
			   

				<!--
                <div style='float:left'>
                <div style=" padding:28px 0px 0 5px;"> 
                	<img src="<?php echo  $_SERVER['PATH_INFO'] . StrToLower($formpath); ?>/images/star_img.png" alt="" /> 
                </div>
                <div style="padding:12px 0px 0 5px;"> 
                	<img src="<?php echo  $_SERVER['PATH_INFO'] . StrToLower($formpath); ?>/images/star_img.png" alt="" /> 
                </div>
                <div style=" padding:12px 0px 0 5px;"> 
                	<img src="<?php echo  $_SERVER['PATH_INFO'] . StrToLower($formpath); ?>/images/star_img.png" alt="" /> 
                </div>
				    <div style=" padding:13px 0px 0 5px;"> 
                	<img src="<?php echo  $_SERVER['PATH_INFO'] . StrToLower($formpath); ?>/images/star_img.png" alt="" /> 
                </div>

                </div>
                   -->
              </div>
            </div>

<!---               references ---->
 <div style="float:left; padding-top:10px;">
               <div style="float:left;"> <img src="<?php echo  $_SERVER['PATH_INFO'] . StrToLower($formpath); ?>/images/form_topbar.png" alt="" /> </div>

		<div style="float:left; padding:20px 0 0 70px;">
                <div style="float:left;" class="bottomarrow">Professional Reference 1: </div>
                <div style="clear:left; height:1px;"> </div>
                <div style="float:left; padding:20px 0 0 0px;">
                  <div style="float:left;">
                    <input type="text" id="referencename" name="referencename" placeholder="reference 1 name" value="<?php echo $candidate_arr['custom_fields']['referencename'];?>" style="width:305px;" required />
                  </div>
                  <div style="float:left; padding:3px 0px 0 9px;"><img src="<?php echo  $_SERVER['PATH_INFO'] . StrToLower($formpath); ?>/images/star_img.png" alt="" /> </div>
                  <div style="float:left; padding-left:22px;">
                    <input type="text" id="referenceurl" name="referenceurl" placeholder="reference 1 URL" value="<?php echo $candidate_arr['custom_fields']['referenceurl'];?>" style="width:305px;"  required />
                  </div>
                  <div style="float:left; padding:3px 0px 0 11px;"><img src="<?php echo  $_SERVER['PATH_INFO'] . StrToLower($formpath); ?>/images/star_img.png" alt="" /> </div>
                </div>
                <div style="float:left; padding:20px 0 0 0px;">
               
                  <div style="float:left;">
                    <input type="text" id="referenceemail" name="referenceemail" placeholder="reference 1 email" value="<?php echo $candidate_arr['custom_fields']['referenceemail'];?>" style="width:305px;"  required />
                  </div>

				<div style="float:left; padding:3px 0px 0 10px;"><img src="<?php echo  $_SERVER['PATH_INFO'] . StrToLower($formpath); ?>/images/star_img.png" alt="" /> </div>
                  <div style="float:left; padding-left:0px;">
                    <div style="float:left; padding-left:22px;">
                    <input type="text" id="referencerelationship" name="referencerelationship" placeholder="reference 1 relationship" value="<?php echo $candidate_arr['custom_fields']['referencerelationship'];?>" style="width:305px;"  required />
                    </div>
                  <div style="float:left; padding:3px 0px 0 11px;"><img src="<?php echo  $_SERVER['PATH_INFO'] . StrToLower($formpath); ?>/images/star_img.png" alt="" /> </div>
                  </div>
		</div>
	    </div>
	</div>

<!---         end      references ---->
<!---               references ---->
 <div style="float:left; padding-top:30px;">
		<div style="float:left; padding:20px 0 0 70px;">
                 <div style="float:left;" class="bottomarrow">Professional Reference 2: </div>
                <div style="clear:left; height:1px;"> </div>
                <div style="float:left; padding:20px 0 0 0px;">
                  <div style="float:left;">
                    <input type="text" id="referencename_b" name="referencename_b" placeholder="reference 2 name" value="<?php echo $candidate_arr['custom_fields']['referencename_b'];?>" style="width:305px;"  required />
                  </div>
                  <div style="float:left; padding:3px 0px 0 9px;"><img src="<?php echo  $_SERVER['PATH_INFO'] . StrToLower($formpath); ?>/images/star_img.png" alt="" /> </div>
                  <div style="float:left; padding-left:22px;">
                    <input type="text" id="referenceurl_b" name="referenceurl_b" placeholder="reference 2 URL" value="<?php echo $candidate_arr['custom_fields']['referenceurl_b'];?>" style="width:305px;"  required />
                  </div>
                  <div style="float:left; padding:3px 0px 0 11px;"><img src="<?php echo  $_SERVER['PATH_INFO'] . StrToLower($formpath); ?>/images/star_img.png" alt="" /> </div>
                </div>
                <div style="float:left; padding:20px 0 0 0px;">
               
                  <div style="float:left;">
                    <input type="text" id="referenceemail_b" name="referenceemail_b" placeholder="reference 2 email" value="<?php echo $candidate_arr['custom_fields']['referenceemail_b'];?>" style="width:305px;"  required />
                  </div>

				<div style="float:left; padding:3px 0px 0 10px;"><img src="<?php echo  $_SERVER['PATH_INFO'] . StrToLower($formpath); ?>/images/star_img.png" alt="" /> </div>
                  <div style="float:left; padding-left:0px;">
                    <div style="float:left; padding-left:22px;">
                    <input type="text" id="referencerelationship_b" name="referencerelationship_b" placeholder="reference 2 relationship" value="<?php echo $candidate_arr['custom_fields']['referencerelationship_b'];?>" style="width:305px;"  required />
                    </div>
                  <div style="float:left; padding:3px 0px 0 11px;"><img src="<?php echo  $_SERVER['PATH_INFO'] . StrToLower($formpath); ?>/images/star_img.png" alt="" /> </div>
                  </div>
		</div>
	    </div>
	</div>

<!---         end      references ---->
<!---               references ---->
 <div style="float:left; padding-top:30px;">
		<div style="float:left; padding:20px 0 0 70px;">
                <div style="float:left;" class="bottomarrow">Professional Reference 3: </div>
                <div style="clear:left; height:1px;"> </div>
                <div style="float:left; padding:20px 0 0 0px;">
                  <div style="float:left;">
                    <input type="text" id="referencename_c" name="referencename_c" placeholder="reference 3 name" value="<?php echo $candidate_arr['custom_fields']['referencename_c'];?>" style="width:305px;"  />
                  </div>
                  <div style="float:left; padding:3px 0px 0 9px;"><img src="<?php echo  $_SERVER['PATH_INFO'] . StrToLower($formpath); ?>/images/star_img.png" alt="" /> </div>
                  <div style="float:left; padding-left:22px;">
                    <input type="text" id="referenceurl_c" name="referenceurl_c" placeholder="reference 3 URL" value="<?php echo $candidate_arr['custom_fields']['referenceurl_c'];?>" style="width:305px;"  />
                  </div>
                  <div style="float:left; padding:3px 0px 0 11px;"><img src="<?php echo  $_SERVER['PATH_INFO'] . StrToLower($formpath); ?>/images/star_img.png" alt="" /> </div>
                </div>
                <div style="float:left; padding:20px 0 0 0px;">
               
                  <div style="float:left;">
                    <input type="text" id="referenceemail_c" name="referenceemail_c" placeholder="reference 3 email" value="<?php echo $candidate_arr['custom_fields']['referenceemail_c'];?>" style="width:305px;"  />
                  </div>

				<div style="float:left; padding:3px 0px 0 10px;"><img src="<?php echo  $_SERVER['PATH_INFO'] . StrToLower($formpath); ?>/images/star_img.png" alt="" /> </div>
                  <div style="float:left; padding-left:0px;">
                    <div style="float:left; padding-left:22px;">
                    <input type="text" id="referencerelationship_c" name="referencerelationship_c" placeholder="reference 3 relationship" value="<?php echo $candidate_arr['custom_fields']['referencerelationship_c'];?>" style="width:305px;"  />
                    </div>
                  <div style="float:left; padding:3px 0px 0 11px;"><img src="<?php echo  $_SERVER['PATH_INFO'] . StrToLower($formpath); ?>/images/star_img.png" alt="" /> </div>
                  </div>
		</div>
		    </div>
	</div>
<!--  start portfolio links  -->
	<div style="float:left; padding-top:30px;">
							  <div style="float:left;"> <img src="<?php echo  $_SERVER['PATH_INFO'] . StrToLower($formpath); ?>/images/form_topbar.png" alt="" /> </div>

			<div style="float:left; padding:0px 0 0 70px;">
                <div style="float:left;" class="bottomarrow">Portfolio Link Descriptions & URLs: </div>
                <div style="clear:left; height:1px;"> </div>
                <div style="float:left; padding:20px 0 0 0px;">
                  <div style="float:left;">
                    <input type="text" id="linkname" name="linkname" placeholder="link description" value="<?php echo $candidate_arr['custom_fields']['linkname'];?>" style="width:305px;"  />
                  </div>
                    <div style="float:left; padding-left:22px;">
                    <input type="text" id="link" name="link" placeholder="https://www.myportfolio.com" value="<?php echo $candidate_arr['custom_fields']['link'];?>" style="width:305px;"  />
                  </div>
                </div>
				<div style="clear:left; height:1px;"> </div>
                <div style="float:left; padding:20px 0 0 0px;">
                  <div style="float:left;">
                    <input type="text" id="linkname_b" name="linkname_b" placeholder="link description" value="<?php echo $candidate_arr['custom_fields']['linkname_b'];?>" style="width:305px;"  />
                  </div>
                    <div style="float:left; padding-left:22px;">
                    <input type="text" id="link_b" name="link_b" placeholder="https://www.myportfolio.com" value="<?php echo $candidate_arr['custom_fields']['link_b'];?>" style="width:305px;"  />
                  </div>
                </div>
				<div style="float:left; padding:20px 0 0 0px;">
                  <div style="float:left;">
                    <input type="text" id="linkname_c" name="linkname_c" placeholder="link description" value="<?php echo $candidate_arr['custom_fields']['linkname_c'];?>" style="width:305px;"  />
                  </div>
                    <div style="float:left; padding-left:22px;">
                    <input type="text" id="link_b" name="link_c" placeholder="https://www.myportfolio.com" value="<?php echo $candidate_arr['custom_fields']['link_c'];?>" style="width:305px;"  />
                  </div>
                </div>
	    </div>
	</div>

            <div style="float:left; padding-top:30px;">
			  <div style="float:left;"> <img src="<?php echo  $_SERVER['PATH_INFO'] . StrToLower($formpath); ?>/images/form_topbar.png" alt="" /> </div>
              <div style="float:left; padding:0px 0 0 70px;">
                <div style="float:left;" class="bottomarrow">Most Recent Full-Time Job: </div>
                <div style="clear:left; height:1px;"> </div>
                <div style="float:left; padding:20px 0 0 0px;">
                  <div style="float:left;">
                    <input type="text" id="current_company" name="current_company" placeholder="company name" value="<?php echo $candidate_arr['current_company'];?>" style="width:305px;" required  />
                  </div>
                  <div style="float:left; padding:3px 0px 0 9px;"><img src="<?php echo  $_SERVER['PATH_INFO'] . StrToLower($formpath); ?>/images/star_img.png" alt="" /> </div>
                  <div style="float:left; padding-left:22px;">
                    <input type="text" id="current_department" name="current_department" placeholder="department" value="<?php echo $candidate_arr['current_department']; ?>" style="width:257px;" required   />
                  </div>
                  <div style="float:left; padding:3px 0px 0 11px;"><img src="<?php echo  $_SERVER['PATH_INFO'] . StrToLower($formpath); ?>/images/star_img.png" alt="" /> </div>
                </div>
                <div style="float:left; padding:20px 0 0 0px;">

                  
                  <div style="float:left;">
                    <input type="text" name="current_position" id="current_position"  placeholder="current position" value="<?php ECHO $candidate_arr['current_position']; ?>" style="width:305px;" required />
                  </div>

		  <div style="float:left; padding:3px 15px 0 10px;"><img src="<?php echo  $_SERVER['PATH_INFO'] . StrToLower($formpath); ?>/images/star_img.png" alt="" /> </div>
                  

                  <div style="float:left;   padding:20px 0 0 0px;">                 

<!-- not sure if we want to use this field
                    <div style="clear:left; height:20px;"> </div>
                    <div style="float:left;" class="graytxt1"> Job description: </div>
                    <div style="clear:left; height:2px;"> </div>
                    <div style="float:left;">
                      <textarea  id="description" name="description"  rows="7" cols="74" style="background-color:#edebeb; border:0px;"><?php echo $candidate_arr['description']; ?></textarea>
                    </div>
                      <div style="float:left; padding:3px 0px 0 5px;"><img src="<?php echo  $_SERVER['PATH_INFO'] . StrToLower($formpath); ?>/images/star_img.png" alt="" /> </div>
					  -->
                  </div>
                </div>
              </div>
            </div>
<!-- -->
            <div style="float:left; padding-top:30px;">
				<div style="float:left;"> <img src="<?php echo  $_SERVER['PATH_INFO'] . StrToLower($formpath); ?>/images/form_topbar.png" alt="" /> </div>
                
              <div style="float:left; padding:0px 0 0 70px;">
                <div style="float:left;" class="bottomarrow">Latest Education: </div>
                <div style="clear:left; height:1px;"> </div>
                <div style="float:left; padding:20px 0 0 0px;">
                  <div style="float:left;">
                    <input type="text" id="latest_university" name="latest_university" placeholder="latest educational institution" value="<?php echo $candidate_arr['latest_university']; ?>" style="width:305px;"   />
                  </div>
                  <div style="float:left; padding:3px 0px 0 5px;"><img src="<?php echo  $_SERVER['PATH_INFO'] . StrToLower($formpath); ?>/images/star_img.png" alt="" /> </div>
                  <div style="float:left; padding-left:25px;">
					<input type="text" id="latest_degree" name="latest_degree" placeholder="latest degree" value="<?php echo $candidate_arr['latest_degree']; ?>" style="width:305px;"   />
                  </div>
                  <div style="float:left; padding:3px 0px 0 5px;"><img src="<?php echo  $_SERVER['PATH_INFO'] . StrToLower($formpath); ?>/images/star_img.png" alt="" /> </div>
                </div>
                <div style="clear:left; height:20px;"> </div>
                     

              </div>
            </div>
<!--
            <div style="float:left; padding-top:30px;">                
              <div style="float:left;"> <img src="<?php echo  $_SERVER['PATH_INFO'] . StrToLower($formpath); ?>/images/form_topbar.png" alt="" /> </div>
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
                    <input type="text"  id="EDUC2_ADDRESS" name="EDUC2_ADDRESS" value="<?php the_field_value($item_num, "EDUC2_ADDRESS", "city"); ?>" style="width:152px;"  onfocus="if(this.value=='city')this.value='';" onblur="if(this.value=='')this.value='city';" />
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
				-->
<!--
                <div style="float:right; padding:40px 30px 0 0;"> <a href="#" onclick="submit_app();"> <img src="<?php echo  $_SERVER['PATH_INFO'] . StrToLower($formpath); ?>/images/nextskills.png" alt="" /> </a> </div>
-->
<div style="float:right; padding:40px 60px 0 0;">
<input type="image" name="Submit" onclick="return all_required_filed_valid();" src="<?php echo  $_SERVER['PATH_INFO'] . StrToLower($formpath); ?>/images/nextskills.png" style="height:auto; background-color:#FFF; border:none;">
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