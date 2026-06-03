<center>
	<div style="justify-content: center; width:800px;">
        <div style="float:right; width:800px; margin-right:10px; padding-bottom:20px;">
          <div style="float:left; width:784px; padding-top:15px;">
            <div style="float:left;">
              <div style="float:left;" class="largetxt1"><span class="redarrowclass1"> [</span> creative talent application </div>
              <div style="float:left; padding:3px 2px 0 3px;" class="redarrowclass1"> >>> </div>
            </div>
		<div style="float:right; padding:10px 0px 0 0px;"> <span style="font-size: 60px; color: #b22625; font-weight: thin;">33%</span> <span style="font-size: 10px;">completed</span> </div>

          </div>
          
        <form action="/longform/signmeuplongform4.php/" ENCTYPE="multipart/form-data" method="post" id="signupform" name="signupform">
            <input id="item_number" name="item_number" value="<?php echo $item_num ?>" type="hidden" value="" />
            <input id="current_state" name="current_state" value="s2" type="hidden" value=""  />
            <input id="RMail" name="RMail" value="<?php echo $RMail ?>" type="hidden"  />
            <input id="full_name" name="full_name" value="<?php echo $candidate_arr['full_name'] ?>" type="hidden"  />
			
                   
				  <div style="clear:left; height:0px;"> &nbsp;</div>
				  <div style="float:left;" >
							<div style="clear:both; height:20px;"></div>
			<div style="float:left; padding:25px 0 0 0px; width:100%;">
               <div style="clear:both;" class="accordion-header active"><span class="icon">▶</span> Graphic Design Disciplines (blank for none)</div>
			   <div class="accordion-content" style="display: block;">
			               <div style="clear:left; padding:0px 0 0 0px;">
              <div style="clear:left; height:20px;"> </div>
              <div style="float:left; width:100px;" class="graytxt3"> Print Designer </div>
               <?php printRadio("GDD|Print Designer"); ?>
              <div style="float:left; width:100px;" class="graytxt3"> Packaging</div>
               <?php printRadio("GDD|Packaging Designer"); ?>     
              <div style="float:left; width:100px;" class="graytxt3"> Publication </div>
               <?php printRadio("GDD|Publication Designer"); ?>     		 
              <div style="float:left; width:100px;" class="graytxt3"> User Experience </div>
             <?php printRadio("GDD|UX Designer"); ?>              
              <div style="float:left; width:100px;" class="graytxt3"> User Interface </div>	  
               <?php printRadio("GDD|UI Designer"); ?>         
              <div style="float:left; width:100px;" class="graytxt3"> AR Designer </div>
                <?php printRadio("GDD|Augmented Reality Designer"); ?>
			  <div style="float:left; width:100px;" class="graytxt3"> VR Designer </div>
                <?php printRadio("GDD|Virtual Reality Designer"); ?>
			</div> </div></div>


			<div style="float:left; padding:25px 0 0 0px; width:100%;">
               <div style="clear:both;" class="accordion-header"><span class="icon">▶</span> Developer (blank for none)</div>
			   <div class="accordion-content">
			   <div style="clear:left; padding:0px 0 0 0px;">
              <div style="clear:left; height:20px;"> </div>
              <div style="float:left; width:100px;" class="graytxt3"> Back End Developer </div>
                <?php printRadio("DEV|BackEnd"); ?>
              <div style="float:left;  width:100px;" class="graytxt3"> Front End Developer </div>
                <?php printRadio("DEV|FrontEnd"); ?>        
            </div>
          </div></div>
		  
			<div style="float:left; padding:25px 0 0 0px; width:100%;">
               <div style="clear:both;" class="accordion-header"><span class="icon">▶</span> Graphic Design Software (blank for none) </div>
			    <div class="accordion-content">
				            <div style="clear:left; padding:0px 0 0 0px;">
              <div style="clear:left; height:20px;"> </div>
              <div style="float:left; width:100px;" class="graytxt3"> Indesign </div>
               <?php printRadio("GDS|Indesign"); ?>
              <div style="float:left; width:100px;" class="graytxt3"> Stylesheets</div>
               <?php printRadio("GDS|Indesign Stylesheets"); ?>     
              <div style="float:left; width:100px;" class="graytxt3"> Photoshop </div>
             <?php printRadio("GDS|PhotoShop"); ?>              
              <div style="float:left; width:100px;" class="graytxt3"> Illustrator </div>
			  
               <?php printRadio("GDS|Adobe Illustrator"); ?>         
              <div style="float:left; width:100px;" class="graytxt3"> Visio </div>
                <?php printRadio("Visio"); ?>
        </div>
        </div></div>
	
          <div style="float:left; padding:20px 0 0 0px; width:100%;">
 <div style="clear:both; height:00px;"></div>
               <div style="clear:both;" class="accordion-header"><span class="icon">▶</span> 3D Software (blank for none) </div>
			    <div class="accordion-content">
				            <div style="clear:left; padding:0px 0 0 0px;">
              <div style="clear:left; height:20px;"> </div>
              <div style="float:left; width:100px;" class="graytxt3"> Blender </div>
                <?php printRadio("3DS|Blender"); ?>

              <div style="float:left;  width:100px;" class="graytxt3"> Autodesk Maya </div>
                <?php printRadio("3DS|Autodesk Maya"); ?>
              
              <div style="clear:left; height:8px;"> </div>
              <div style="float:left; width:100px;" class="graytxt3"> Autodesk 3ds Max </div>
              <?php printRadio("Autodesk 3ds"); ?>
              
              <div style="float:left; width:100px;" class="graytxt3"> Cinema 4D </div>
               <?php printRadio("3DS|Cinema 4D"); ?>
            </div>
          </div></div>
		  
          <div style="float:left; padding:20px 0 0 0px; width:100%;">
 <div style="clear:both; height:00px;"></div>
               <div style="clear:both;" class="accordion-header"><span class="icon">▶</span> Presentation Software (blank for none)</div>
			    <div class="accordion-content">
				            <div style="clear:left; padding:0px 0 0 0px;">
              <div style="clear:left; height:20px;"> </div>
			
              <div style="float:left; width:100px;" class="graytxt3"> Keynote </div>
            	<?php printRadio("PRS|Keynote"); ?>  

              <div style="float:left; width:100px;" class="graytxt3"> PowerPoint </div>
            	<?php printRadio("PRS|PowerPoint"); ?>
				
              <div style="float:left; width:100px;" class="graytxt3"> Google Slides </div>
            	<?php printRadio("PRS|Google Slides"); ?>

              <div style="float:left; width:100px;" class="graytxt3"> Canva </div>
               <?php printRadio("PRS|Canva"); ?> 
 
            </div>
          </div></div>


          <div style="float:left; padding:20px 0 0 0px; width:100%;">
 <div style="clear:both; height:00px;"></div>
               <div style="clear:both;" class="accordion-header"><span class="icon">▶</span> UI & UX Software (blank for none) </div>
			    <div class="accordion-content">
				            <div style="clear:left; padding:0px 0 0 0px;">
              <div style="clear:left; height:20px;"> </div>
			  
			  <div style="float:left; width:100px;" class="graytxt3"> Figma </div>
              	<?php printRadio("UIX|Figma"); ?> 
              <div style="float:left; width:100px;" class="graytxt3"> Adobe XD </div>
              	<?php printRadio("UIX|Adobe XD"); ?> 
              <div style="float:left; width:100px;" class="graytxt3"> Webflow </div>
              	<?php printRadio("UIX|Webflo"); ?> 
              <div style="float:left; width:100px;" class="graytxt3"> Visual Studio Code</div>
              	<?php printRadio("UIX|Canva");?>
			  <div style="clear:left; float:left; width:100px;" class="graytxt3"> CSS</div>
              	<?php printRadio("UIX|CSS"); ?> 
              <div style="float:left; width:100px;" class="graytxt3"> HTML (handcode) </div>
              	<?php printRadio("UIX|HTML"); ?>
              <div style="float:left; width:100px;" class="graytxt3"> HTM5 (handcode) </div>
              	<?php printRadio("UIX|HTML5"); ?>
 
            </div>
          </div></div>


          <div style="float:left; padding:20px 0 0 0px; width:100%;">
 <div style="clear:both; height:00px;"></div>
               <div style="clear:both;" class="accordion-header"><span class="icon">▶</span> Web CMS (blank for none) </div>
			    <div class="accordion-content">
				            <div style="clear:left; padding:0px 0 0 0px;">
              <div style="clear:left; height:20px;"> </div>
              <div style="float:left; width:100px;" class="graytxt3"> WordPress </div>
               <?php printRadio("CMS|WordPress"); ?> 
              <div style="float:left;  width:100px;" class="graytxt3"> Shopify </div>
              <?php printRadio("CMS|Shopify"); ?> 
              <div style="float:left; width:100px;" class="graytxt3"> SharePoint </div>
               <?php printRadio("CMS|SharePoint"); ?>               
              <div style="float:left; width:100px;" class="graytxt3"> Wix </div>
              <?php printRadio("CMS|Wix"); ?> 
            </div>
          </div>

          <div style="float:left; padding:20px 0 0 0px; width:100%;">
 <div style="clear:both; height:00px;"></div>
               <div style="clear:both;" class="accordion-header"><span class="icon">▶</span> Advanced web (blank for none) </div>
			    <div class="accordion-content">
				<div style="clear:left; padding:0px 0 0 0px;">
              <div style="clear:left; height:20px;"> </div>

              <div style="float:left; width:100px;" class="graytxt3"> Java </div>
              <?php printRadio("ADV|Java"); ?> 

              <div style="float:left; width:100px;" class="graytxt3"> Android </div>
               <?php printRadio("ADV|Android"); ?>
              <div style="float:left; width:100px;" class="graytxt3"> Java Script </div>
              <?php printRadio("ADV|Java Script"); ?>
              <div style="float:left; width:100px;" class="graytxt3"> Active X </div>
              <?php printRadio("ADV|Active X "); ?> 
              <div style="float:left; width:100px;" class="graytxt3"> PaperVision </div>
              <?php printRadio("ADV|PaperVision"); ?>
              <div style="float:left; width:100px;" class="graytxt3"> ASP </div>
              <?php printRadio("ADV|ASP"); ?> 
              <div style="float:left; width:100px;" class="graytxt3"> Perl </div>
              <?php printRadio("ADV|Perl"); ?> 
              <div style="float:left; width:100px;" class="graytxt3"> C++ </div>
              <?php printRadio("ADV|C++"); ?>                 
              <div style="float:left; width:100px;" class="graytxt3"> PHP </div>
               <?php printRadio("ADV|PHP"); ?> 
              <div style="float:left; width:100px;" class="graytxt3"> C# </div>
              <?php printRadio("ADV|C#"); ?>
              <div style="float:left; width:100px;" class="graytxt3"> Ruby on Rails </div>
              <?php printRadio("ADV|Ruby on Rails"); ?>
              <div style="float:left; width:100px;" class="graytxt3"> Xcode </div>
                <?php printRadio("ADV|Xcode"); ?> 
			  <div style="float:left; width:100px;" class="graytxt3"> SwiftUI </div>
                <?php printRadio("ADV|SwiftUI"); ?>
			  <div style="float:left; width:100px;" class="graytxt3"> React Native </div>
                <?php printRadio("ADV|React Native"); ?>
			  <div style="float:left; width:100px;" class="graytxt3"> Flutter </div>
                <?php printRadio("ADV|Flutter"); ?>
              <div style="float:left; width:100px;" class="graytxt3"> SharePoint </div>
              <?php printRadio("SADV|SharePoint"); ?>
              <div style="float:left; width:100px;" class="graytxt3"> Simulator </div>
              <?php printRadio("ADV|Simulator"); ?>			 
              <div style="float:left; width:100px;" class="graytxt3"> DHTML </div>
               <?php printRadio("ADV|DHTML"); ?> 
              <div style="float:left; width:100px;" class="graytxt3"> SQL (MS or My) </div>
               <?php printRadio("ADV|SQL"); ?>
              <div style="float:left; width:100px;" class="graytxt3"> .Net </div>
              <?php printRadio("ADV|Dot Net"); ?>
              <div style="float:left; width:100px;" class="graytxt3"> VB Script </div>
              <?php printRadio("ADV|VB Script"); ?>
              <div style="float:left; width:100px;" class="graytxt3"> HTML5 </div>
              <?php printRadio("ADV|HTML5"); ?>
              <div style="float:left; width:100px;" class="graytxt3"> Visual Studio </div>
               <?php printRadio("ADV|Visual Studio"); ?> 
              <div style="float:left; width:100px;" class="graytxt3"> iOS </div>
               <?php printRadio("ADV|iOS"); ?> 
              <div style="float:left; width:100px;" class="graytxt3"> XML </div>
               <?php printRadio("ADV|XML"); ?> 
              <div style="float:left; width:100px;" class="graytxt3"> JSON </div>
               <?php printRadio("ADV|JSON"); ?> 
              <div style="float:left; width:100px;" class="graytxt3"> J++ </div>
              <?php printRadio("ADV|J++"); ?>         
            </div>
          </div></div>
		  
          <div style="float:left; padding:20px 0 0 0px; width:100%;">
 <div style="clear:both; height:00px;"></div>
               <div style="clear:both;" class="accordion-header"><span class="icon">▶</span> Video editing (blank for none) </div>
			    <div class="accordion-content">
				<div style="clear:left; padding:0px 0 0 0px;">
              <div style="clear:left; height:20px;"> </div>
              <div style="float:left; width:100px;" class="graytxt3">  Premiere </div>
              <?php printRadio("VID|Adobe Premiere"); ?>            
              <div style="float:left;  width:100px;" class="graytxt3"> Final Cut </div>
              <?php printRadio("VID|Final Cut Pro"); ?>                
              <div style="clear:left; height:8px;"> </div>
              <div style="float:left; width:100px;" class="graytxt3"> After Effects </div>
               <?php printRadio("VID|Adobe After Effects"); ?>       
              <div style="float:left; width:100px;" class="graytxt3"> Flame </div>
               <?php printRadio("VID|Autodesk Flame"); ?>    
              <div style="float:left; width:100px;" class="graytxt3"> DaVinci Resolve </div>
              <?php printRadio("VID|DaVinci Resolve"); ?>    
              <div style="float:left; width:100px;" class="graytxt3"> Cinema 4D </div>
              <?php printRadio("VID|DaCinema 4D"); ?> 
			  <div style="float:left; width:100px;" class="graytxt3"> Frame.io </div>
              <?php printRadio("VID|Frame.io"); ?> 
			  <div style="float:left; width:100px;" class="graytxt3"> ShotGrid </div>
              <?php printRadio("VID|ShotGrid"); ?> 
			  <div style="float:left; width:100px;" class="graytxt3"> Celtx </div>
              <?php printRadio("VID|Celtx"); ?> 
			  <div style="float:left; width:100px;" class="graytxt3"> StudioBinder </div>
              <?php printRadio("VID|StudioBinder"); ?> 
			  <div style="float:left; width:100px;" class="graytxt3"> Toon Boom </div>
              <?php printRadio("VID|Toon Boom"); ?> 
			  <div style="float:left; width:100px;" class="graytxt3"> Harmony </div>
              <?php printRadio("VID|Harmony"); ?> 			  
		      <div style="float:left; width:100px;" class="graytxt3"> Adobe Animate </div>
              <?php printRadio("VID|Adobe Animate"); ?> 			  			  
            </div>
          </div></div>
		  
          <div style="float:left; padding:20px 0 0 0px; width:100%;">
 <div style="clear:both; height:00px;"></div>
               <div style="clear:both;" class="accordion-header"><span class="icon">▶</span> Operating Systems (blank for none) </div>
			    <div class="accordion-content">
				<div style="clear:left; padding:0px 0 0 0px;">
              <div style="clear:left; height:20px;"> </div>
              <div style="float:left; width:100px;" class="graytxt3"> Mac OS </div>
              <?php printRadio("COS|Mac OS"); ?>    

              <div style="float:left;  width:100px;" class="graytxt3"> Windows </div>
              <?php printRadio("COS|Windows"); ?>    
                
              <div style="clear:left; height:8px;"> </div>
              <div style="float:left; width:100px;" class="graytxt3"> Linux/Unix </div>
              <?php printRadio("COS|Linux"); ?>


		<!--
		<a href="#" onclick="submit_app();"> <img src="https://<?php echo  $_SERVER['SERVER_NAME'] . '/' .  $_SERVER['PATH_INFO'] . StrToLower($formpath); ?>/images/nextexpertisearea.png" alt="" /> </a> 
		-->

          </div></div>
        </div>
      </div>
	     </div>
	  <div style="clear:left; height:1px;"> </div>
              <div style="float:right; padding-top:30px; padding-right:40px;">
			  
			  <button 
  type="submit"
  onclick="return all_required_filed_valid();"
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
  Next – Expertise
</button>
			  
        </div>
        <input id="cbo_field" name="cbo_field" value="<?php echo $cbo_field ?>" type="hidden"/>
        <input id="radio_field" name="radio_field" value="<?php echo $radio_field ?>" type="hidden"/>
        <input id="text_field" name="text_field" value="<?php echo $text_field ?>" type="hidden"/>
        <?php save_form_variables(); ?>
        </form>
 
  </div>
</center>