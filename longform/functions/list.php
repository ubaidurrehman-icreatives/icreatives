<?php

function DateOption($name, $default)
{
    global $cbo_field;
    if ($cbo_field=="")
    {
        $cbo_field .=$name;
    }
    else
    {
        $cbo_field .= ";" . $name;
    }
    
    $val= loadValue($name);
    
?>
        <option value=""<?php if ($val=="") {echo "selected='selected'";} ?>>Day</option>
        <option value="01" <?php if ($val=="01") {echo "selected='selected'";} ?>>01</option>
        <option value="02" <?php if ($val=="02") {echo "selected='selected'";} ?>>02</option>
        <option value="03" <?php if ($val=="03") {echo "selected='selected'";} ?>>03</option>
        <option value="04" <?php if ($val=="04") {echo "selected='selected'";} ?>>04</option>
        <option value="05" <?php if ($val=="05") {echo "selected='selected'";} ?>>05</option>
        <option value="06" <?php if ($val=="06") {echo "selected='selected'";} ?>>06</option>
        <option value="07" <?php if ($val=="07") {echo "selected='selected'";} ?>>07</option>
        <option value="08" <?php if ($val=="08") {echo "selected='selected'";} ?>>08</option>
        <option value="09" <?php if ($val=="09") {echo "selected='selected'";} ?>>09</option>
        <option value="10" <?php if ($val=="10") {echo "selected='selected'";} ?>>10</option>
        <option value="11" <?php if ($val=="11") {echo "selected='selected'";} ?>>11</option>
        <option value="12" <?php if ($val=="12") {echo "selected='selected'";} ?>>12</option>
        <option value="13" <?php if ($val=="13") {echo "selected='selected'";} ?>>13</option>
        <option value="14" <?php if ($val=="14") {echo "selected='selected'";} ?>>14</option>
        <option value="15" <?php if ($val=="15") {echo "selected='selected'";} ?>>15</option>
        <option value="16" <?php if ($val=="16") {echo "selected='selected'";} ?>>16</option>
        <option value="17" <?php if ($val=="17") {echo "selected='selected'";} ?>>17</option>
        <option value="18" <?php if ($val=="18") {echo "selected='selected'";} ?>>18</option>
        <option value="19" <?php if ($val=="19") {echo "selected='selected'";} ?>>19</option>       
        <option value="20" <?php if ($val=="20") {echo "selected='selected'";} ?>>20</option>
        <option value="21" <?php if ($val=="21") {echo "selected='selected'";} ?>>21</option>
        <option value="22" <?php if ($val=="22") {echo "selected='selected'";} ?>>22</option>
        <option value="23" <?php if ($val=="23") {echo "selected='selected'";} ?>>23</option>
        <option value="24" <?php if ($val=="24") {echo "selected='selected'";} ?>>24</option>
        <option value="25" <?php if ($val=="25") {echo "selected='selected'";} ?>>25</option>
        <option value="26" <?php if ($val=="26") {echo "selected='selected'";} ?>>26</option>
        <option value="27" <?php if ($val=="27") {echo "selected='selected'";} ?>>27</option>
        <option value="28" <?php if ($val=="28") {echo "selected='selected'";} ?>>28</option
        <option value="29" <?php if ($val=="29") {echo "selected='selected'";} ?>>29</option>
        <option value="30" <?php if ($val=="30") {echo "selected='selected'";} ?>>30</option>
        <option value="31" <?php if ($val=="31") {echo "selected='selected'";} ?>>31</option>
        
 <?php  
    return;   
}

function MonthOption($name, $default)
{
  global $cbo_field;
    if ($cbo_field=="")
    {
        $cbo_field .=$name;
    }
    else
    {
        $cbo_field .= ";" . $name;
    }
    $val= loadValue($name);
?>
        <option value=""<?php if ($val=="") {echo "selected='selected'";} ?>>Month</option>
        <option value="01" <?php if ($val=="01") {echo "selected='selected'";} ?>>01</option>
        <option value="02" <?php if ($val=="02") {echo "selected='selected'";} ?>>02</option>
        <option value="03" <?php if ($val=="03") {echo "selected='selected'";} ?>>03</option>
        <option value="04" <?php if ($val=="04") {echo "selected='selected'";} ?>>04</option>
        <option value="05" <?php if ($val=="05") {echo "selected='selected'";} ?>>05</option>
        <option value="06" <?php if ($val=="06") {echo "selected='selected'";} ?>>06</option>
        <option value="07" <?php if ($val=="07") {echo "selected='selected'";} ?>>07</option>
        <option value="08" <?php if ($val=="08") {echo "selected='selected'";} ?>>08</option>
        <option value="09" <?php if ($val=="09") {echo "selected='selected'";} ?>>09</option>
        <option value="10" <?php if ($val=="10") {echo "selected='selected'";} ?>>10</option>
        <option value="11" <?php if ($val=="11") {echo "selected='selected'";} ?>>11</option>
        <option value="12" <?php if ($val=="12") {echo "selected='selected'";} ?>>12</option>
 <?php  
    return;   
}
?>
<?php
function RecruitingSourceOption($name, $default)
{
  global $cbo_field;
    if ($cbo_field=="")
    {
        $cbo_field .=$name;
    }
    else
    {
        $cbo_field .= ";" . $name;
    }
    $val= loadValue($name);
?>
    <option value="" <?php if ($val=="") {echo "selected='selected'";} ?>>How'd we find you?</option>
    <option value="25" <?php if ($val=="25") {echo "selected='selected'";} ?>>Art Directors Club</option>
    <option value="24" <?php if ($val=="24") {echo "selected='selected'";} ?>>Ad Fed</option>
    <option value="23" <?php if ($val=="23") {echo "selected='selected'";} ?>>AIGA</option>
    <option value="23" <?php if ($val=="BE") {echo "selected='selected'";} ?>>Behance</option>
    <option value="23" <?php if ($val=="CH") {echo "selected='selected'";} ?>>Creative Holist</option>
    <option value="23" <?php if ($val=="CL") {echo "selected='selected'";} ?>>Craigslist</option>
    <option value="4" <?php if ($val=="4") {echo "selected='selected'";} ?>>Customer Referral</option>
    <option value="17" <?php if ($val=="17") {echo "selected='selected'";} ?>>Google Search</option>
    <option value="17" <?php if ($val=="LI") {echo "selected='selected'";} ?>>LinkedIn</option>
    <option value="18" <?php if ($val=="18") {echo "selected='selected'";} ?>>Yahoo Search</option>
    <option value="3" <?php if ($val=="3") {echo "selected='selected'";} ?>>Employee Referral</option>
    <option value="10" <?php if ($val=="10") {echo "selected='selected'";} ?>>Radio</option>
    <option value="11" <?php if ($val=="11") {echo "selected='selected'";} ?>>Television</option>
    <option value="12" <?php if ($val=="12") {echo "selected='selected'";} ?>>Billboard</option>
    <option value="9" <?php if ($val=="10") {echo "selected='selected'";} ?>>Friend/Relative</option>
    <option value="14" <?php if ($val=="14") {echo "selected='selected'";} ?>>Trade Show/Presentation</option>
    <option value="7" <?php if ($val=="7") {echo "selected='selected'";} ?>>School Referral</option>
    <option value="8" <?php if ($val=="8") {echo "selected='selected'";} ?>>Direct Mail</option>
    <option value="19" <?php if ($val=="19") {echo "selected='selected'";} ?>>Bing</option>
    <option value="20" <?php if ($val=="20") {echo "selected='selected'";} ?>>Career Builder</option>
    <option value="21" <?php if ($val=="21") {echo "selected='selected'";} ?>>Craigs List</option>
    <option value="22" <?php if ($val=="22") {echo "selected='selected'";} ?>>Other Job Board</option>


 <?php  
    return;   
}
   
?>
<?php
function IndustryOption($name, $default)
{
  global $cbo_field;
    if ($cbo_field=="")
    {
        $cbo_field .=$name;
    }
    else
    {
        $cbo_field .= ";" . $name;
    }
    
    $val= loadValue($name);
    
?>
    <option value=""> -- Pick an industry --</option>
    <option value='Automotive' <?php if ($val=="Automotive") {echo "selected='selected'";} ?>> Automotive </option>
    <option value='Computers' <?php if ($val=="Computers") {echo "selected='selected'";} ?>> Computers </option>
    <option value='Consumer Products' <?php if ($val=="Consumer Products") {echo "selected='selected'";} ?>> Consumer Products </option>
    <option value='Electronics' <?php if ($val=="Electronics") {echo "selected='selected'";} ?>> Electronics </option>
    <option value='Energy' <?php if ($val=="Energy") {echo "selected='selected'";} ?>> Energy </option>
    <option value='Fashion/Apparel & Accessories' <?php if ($val=="Fashion/Apparel & Accessories") {echo "selected='selected'";} ?>> Fashion/Apparel & Accessories </option>
    <option value='Financial Services' <?php if ($val=="Financial Services") {echo "selected='selected'";} ?>> Financial Services </option>
    <option value='Healthcare' <?php if ($val=="Healthcare") {echo "selected='selected'";} ?>> Healthcare </option>
    <option value='Information Technology' <?php if ($val=="Information Technology") {echo "selected='selected'";} ?>> Information Technology </option>
    <option value='High Tech' <?php if ($val=="High Tech") {echo "selected='selected'";} ?>> High Tech </option>
    <option value='Marketing, Advertising & PR' <?php if ($val=="Marketing, Advertising & PR") {echo "selected='selected'";} ?>> Marketing, Advertising & PR </option>
    <option value='Manufacturing' <?php if ($val=="Manufacturing") {echo "selected='selected'";} ?>> Manufacturing </option>
    <option value='Media & Entertainment' <?php if ($val=="Media & Entertainment") {echo "selected='selected'";} ?>> Media & Entertainment </option>
    <option value='Non-Profit Organization' <?php if ($val=="Non-Profit Organization") {echo "selected='selected'";} ?>> Non-Profit Organization </option>
    <option value='Packaging and Related' <?php if ($val=="Packaging and Related") {echo "selected='selected'";} ?>> Packaging and Related </option>
    <option value='Pharmaceutical & Biotech' <?php if ($val=="Pharmaceutical & Biotech") {echo "selected='selected'";} ?>> Pharmaceutical & Biotech </option>
    <option value='Professional Services' <?php if ($val=="Professional Services") {echo "selected='selected'";} ?>> Professional Services </option>
    <option value='Real Estate & Development' <?php if ($val=="Real Estate & Development") {echo "selected='selected'";} ?>> Real Estate & Development </option>
    <option value='Retail/Ecommerce' <?php if ($val=="Retail/Ecommerce") {echo "selected='selected'";} ?>> Retail/Ecommerce </option>
    <option value='Sports Marketing' <?php if ($val=="Sports Marketing") {echo "selected='selected'";} ?>> Sports Marketing </option>
    <option value='Telecommunications' <?php if ($val=="Telecommunications") {echo "selected='selected'";} ?>> Telecommunications </option>
    <option value='Tourism & Travel' <?php if ($val=="Tourism & Travel") {echo "selected='selected'";} ?>> Tourism & Travel </option>
    <option value='Visual Merchandising' <?php if ($val=="Visual Merchandising") {echo "selected='selected'";} ?>> Visual Merchandising </option>
 <?php  
    return;   
}

?>
<?php
function YearOption($name, $default)
{
  global $cbo_field;
    if ($cbo_field=="")
    {
        $cbo_field .=$name;
    }
    else
    {
        $cbo_field .= ";" . $name;
    }
    $val= loadValue($name);
?>
                        <option value="">Year</option>
						<option value="2022" <?php if ($val=="2022") {echo "selected='selected'";} ?>>2022</option>
                        <option value="2021" <?php if ($val=="2021") {echo "selected='selected'";} ?>>2021</option>
                        <option value="2020" <?php if ($val=="2020") {echo "selected='selected'";} ?>>2020</option>
                        <option value="2019" <?php if ($val=="2019") {echo "selected='selected'";} ?>>2019</option>
                        <option value="2018" <?php if ($val=="2018") {echo "selected='selected'";} ?>>2018</option>
                        <option value="2017" <?php if ($val=="2017") {echo "selected='selected'";} ?>>2017</option>
                        <option value="2016" <?php if ($val=="2016") {echo "selected='selected'";} ?>>2016</option>
                        <option value="2015" <?php if ($val=="2015") {echo "selected='selected'";} ?>>2015</option>
                        <option value="2014" <?php if ($val=="2014") {echo "selected='selected'";} ?>>2014</option> 
                        <option value="2013" <?php if ($val=="2013") {echo "selected='selected'";} ?>>2013</option>  
                        <option value="2012" <?php if ($val=="2012") {echo "selected='selected'";} ?>>2012</option>  
                        <option value="2011" <?php if ($val=="2011") {echo "selected='selected'";} ?>>2011</option>                
                        <option value="2010" <?php if ($val=="2010") {echo "selected='selected'";} ?>>2010</option>
                        <option value="2009" <?php if ($val=="2009") {echo "selected='selected'";} ?>>2009</option>
                        <option value="2008" <?php if ($val=="2008") {echo "selected='selected'";} ?>>2008</option>
                        <option value="2007" <?php if ($val=="2007") {echo "selected='selected'";} ?>>2007</option>
                        <option value="2006" <?php if ($val=="2006") {echo "selected='selected'";} ?>>2006</option>
                        <option value="2005" <?php if ($val=="2005") {echo "selected='selected'";} ?>>2005</option>
                        <option value="2004" <?php if ($val=="2004") {echo "selected='selected'";} ?>>2004</option>
                        <option value="2003" <?php if ($val=="2003") {echo "selected='selected'";} ?>>2003</option>
                        <option value="2002" <?php if ($val=="2002") {echo "selected='selected'";} ?>>2002</option>
                        <option value="2001" <?php if ($val=="2001") {echo "selected='selected'";} ?>>2001</option>
                        <option value="2000" <?php if ($val=="2000") {echo "selected='selected'";} ?>>2000</option>
                        <option value="1999" <?php if ($val=="1999") {echo "selected='selected'";} ?>>1999</option>
                        <option value="1998" <?php if ($val=="1998") {echo "selected='selected'";} ?>>1998</option>
                        <option value="1997" <?php if ($val=="1997") {echo "selected='selected'";} ?>>1997</option>
                        <option value="1996" <?php if ($val=="1996") {echo "selected='selected'";} ?>>1996</option>
                        <option value="1995" <?php if ($val=="1995") {echo "selected='selected'";} ?>>1995</option>
                        <option value="1994" <?php if ($val=="1994") {echo "selected='selected'";} ?>>1994</option>
                        <option value="1993" <?php if ($val=="1993") {echo "selected='selected'";} ?>>1993</option>
                        <option value="1992" <?php if ($val=="1992") {echo "selected='selected'";} ?>>1992</option>
                        <option value="1991" <?php if ($val=="1991") {echo "selected='selected'";} ?>>1991</option>
                        <option value="1990" <?php if ($val=="1990") {echo "selected='selected'";} ?>>1990</option>
                        <option value="1989" <?php if ($val=="1989") {echo "selected='selected'";} ?>>1989</option>
                        <option value="1988" <?php if ($val=="1988") {echo "selected='selected'";} ?>>1988</option>
                        <option value="1987" <?php if ($val=="1987") {echo "selected='selected'";} ?>>1987</option>
                        <option value="1986" <?php if ($val=="1986") {echo "selected='selected'";} ?>>1986</option>
                        <option value="1985" <?php if ($val=="1985") {echo "selected='selected'";} ?>>1985</option>
                        <option value="1984" <?php if ($val=="1984") {echo "selected='selected'";} ?>>1984</option>
                        <option value="1983" <?php if ($val=="1983") {echo "selected='selected'";} ?>>1983</option>
                        <option value="1982" <?php if ($val=="1982") {echo "selected='selected'";} ?>>1982</option>
                        <option value="1981" <?php if ($val=="1981") {echo "selected='selected'";} ?>>1981</option>
                        <option value="1980" <?php if ($val=="1980") {echo "selected='selected'";} ?>>1980</option>
                        <option value="1979" <?php if ($val=="1979") {echo "selected='selected'";} ?>>1979</option>
                        <option value="1978" <?php if ($val=="1978") {echo "selected='selected'";} ?>>1978</option>
                        <option value="1977" <?php if ($val=="1977") {echo "selected='selected'";} ?>>1977</option>
                        <option value="1976" <?php if ($val=="1976") {echo "selected='selected'";} ?>>1976</option>
                        <option value="1975" <?php if ($val=="1975") {echo "selected='selected'";} ?>>1975</option>
                        <option value="1974" <?php if ($val=="1974") {echo "selected='selected'";} ?>>1974</option>
                        <option value="1973" <?php if ($val=="1973") {echo "selected='selected'";} ?>>1973</option>
                        <option value="1972" <?php if ($val=="1972") {echo "selected='selected'";} ?>>1972</option>
                        <option value="1971" <?php if ($val=="1971") {echo "selected='selected'";} ?>>1971</option>
                        <option value="1970" <?php if ($val=="1970") {echo "selected='selected'";} ?>>1970</option>
                        <option value="1969" <?php if ($val=="1969") {echo "selected='selected'";} ?>>1969</option>
                        <option value="1968" <?php if ($val=="1968") {echo "selected='selected'";} ?>>1968</option>
                        <option value="1967" <?php if ($val=="1967") {echo "selected='selected'";} ?>>1967</option>
                        <option value="1966" <?php if ($val=="1966") {echo "selected='selected'";} ?>>1966</option>
                        <option value="1965" <?php if ($val=="1965") {echo "selected='selected'";} ?>>1965</option>
                        <option value="1964" <?php if ($val=="1964") {echo "selected='selected'";} ?>>1964</option>
                        <option value="1963" <?php if ($val=="1963") {echo "selected='selected'";} ?>>1963</option>
                        <option value="1962" <?php if ($val=="1962") {echo "selected='selected'";} ?>>1962</option>
                        <option value="1961" <?php if ($val=="1961") {echo "selected='selected'";} ?>>1961</option>
                        <option value="1960" <?php if ($val=="1960") {echo "selected='selected'";} ?>>1960</option>
                        <option value="1959" <?php if ($val=="1959") {echo "selected='selected'";} ?>>1959</option>
                        <option value="1958" <?php if ($val=="1958") {echo "selected='selected'";} ?>>1958</option>
                        <option value="1957" <?php if ($val=="1957") {echo "selected='selected'";} ?>>1957</option>
                        <option value="1956" <?php if ($val=="1956") {echo "selected='selected'";} ?>>1956</option>
                        <option value="1955" <?php if ($val=="1955") {echo "selected='selected'";} ?>>1955</option>
                        <option value="1954" <?php if ($val=="1954") {echo "selected='selected'";} ?>>1954</option>
                        <option value="1953" <?php if ($val=="1953") {echo "selected='selected'";} ?>>1953</option>
                        <option value="1952" <?php if ($val=="1952") {echo "selected='selected'";} ?>>1952</option>
                        <option value="1951" <?php if ($val=="1951") {echo "selected='selected'";} ?>>1951</option>
                        <option value="1950" <?php if ($val=="1950") {echo "selected='selected'";} ?>>1950</option>
                        <option value="1949" <?php if ($val=="1949") {echo "selected='selected'";} ?>>1949</option>
                        <option value="1948" <?php if ($val=="1948") {echo "selected='selected'";} ?>>1948</option>
                        <option value="1947" <?php if ($val=="1947") {echo "selected='selected'";} ?>>1947</option>
                        <option value="1947" <?php if ($val=="1947") {echo "selected='selected'";} ?>>1946</option>
                        <option value="1945" <?php if ($val=="1945") {echo "selected='selected'";} ?>>1945</option>
                        <option value="1944" <?php if ($val=="1944") {echo "selected='selected'";} ?>>1944</option>
                        <option value="1943" <?php if ($val=="1943") {echo "selected='selected'";} ?>>1943</option>
                        <option value="1942" <?php if ($val=="1942") {echo "selected='selected'";} ?>>1942</option>
                        <option value="1941" <?php if ($val=="1941") {echo "selected='selected'";} ?>>1941</option>
                        <option value="1940" <?php if ($val=="1940") {echo "selected='selected'";} ?>>1940</option>
                        <option value="1939" <?php if ($val=="1939") {echo "selected='selected'";} ?>>1939</option>
                        <option value="1938" <?php if ($val=="1938") {echo "selected='selected'";} ?>>1938</option>
                        <option value="1937" <?php if ($val=="1937") {echo "selected='selected'";} ?>>1937</option>
                        <option value="1936" <?php if ($val=="1936") {echo "selected='selected'";} ?>>1936</option>
                        <option value="1935" <?php if ($val=="1935") {echo "selected='selected'";} ?>>1935</option>
                        <option value="1934" <?php if ($val=="1934") {echo "selected='selected'";} ?>>1934</option>
                        <option value="1933" <?php if ($val=="1933") {echo "selected='selected'";} ?>>1933</option>
                        <option value="1932" <?php if ($val=="1932") {echo "selected='selected'";} ?>>1932</option>
                        <option value="1931" <?php if ($val=="1931") {echo "selected='selected'";} ?>>1931</option>
                        <option value="1930" <?php if ($val=="1930") {echo "selected='selected'";} ?>>1930</option>
                        <option value="1929" <?php if ($val=="1929") {echo "selected='selected'";} ?>>1929</option>
                        <option value="1928" <?php if ($val=="1928") {echo "selected='selected'";} ?>>1928</option>
                        <option value="1927" <?php if ($val=="1927") {echo "selected='selected'";} ?>>1927</option>
                        <option value="1926" <?php if ($val=="1926") {echo "selected='selected'";} ?>>1926</option>
                        <option value="1925" <?php if ($val=="1925") {echo "selected='selected'";} ?>>1925</option>
                        <option value="1924" <?php if ($val=="1924") {echo "selected='selected'";} ?>>1924</option>
                        <option value="1923" <?php if ($val=="1923") {echo "selected='selected'";} ?>>1923</option>
                        <option value="1922" <?php if ($val=="1922") {echo "selected='selected'";} ?>>1922</option>
                        <option value="1921" <?php if ($val=="1921") {echo "selected='selected'";} ?>>1921</option>
                        <option value="1920" <?php if ($val=="1920") {echo "selected='selected'";} ?>>1920</option>
                        <option value="1919" <?php if ($val=="1919") {echo "selected='selected'";} ?>>1919</option>
                        <option value="1918" <?php if ($val=="1918") {echo "selected='selected'";} ?>>1918</option>
                        <option value="1917" <?php if ($val=="1917") {echo "selected='selected'";} ?>>1917</option>
                        <option value="1916" <?php if ($val=="1916") {echo "selected='selected'";} ?>>1916</option>
                        <option value="1915" <?php if ($val=="1915") {echo "selected='selected'";} ?>>1915</option>
                        <option value="1914" <?php if ($val=="1914") {echo "selected='selected'";} ?>>1914</option>
                        <option value="1913" <?php if ($val=="1913") {echo "selected='selected'";} ?>>1913</option>
                        <option value="1912" <?php if ($val=="1912") {echo "selected='selected'";} ?>>1912</option>
                        <option value="1911" <?php if ($val=="1911") {echo "selected='selected'";} ?>>1911</option>
                        <option value="1910" <?php if ($val=="1910") {echo "selected='selected'";} ?>>1910</option>
                        <option value="1909" <?php if ($val=="1909") {echo "selected='selected'";} ?>>1909</option>
                        <option value="1908" <?php if ($val=="1908") {echo "selected='selected'";} ?>>1908</option>
                        <option value="1907" <?php if ($val=="1907") {echo "selected='selected'";} ?>>1907</option>
                        <option value="1906" <?php if ($val=="1906") {echo "selected='selected'";} ?>>1906</option>
                        <option value="1905" <?php if ($val=="1905") {echo "selected='selected'";} ?>>1905</option>
 <?php  
    return;   
}  
?>
<?php
function DegreeOption($name, $default)
{
  global $cbo_field;
    if ($cbo_field=="")
    {
        $cbo_field .=$name;
    }
    else
    {
        $cbo_field .= ";" . $name;
    }
    $val= loadValue($name);
?>
                        <option value=""<?php if ($val=="") {echo "selected='selected'";} ?>> DEGREE TYPE </option>
                        <option value="A" <?php if ($val=="A") {echo "selected='selected'";} ?>>Associate Degree</option>
                        <option value="B" <?php if ($val=="B") {echo "selected='selected'";} ?>>Bachelor's Degree</option>
                        <option value="BA" <?php if ($val=="BA") {echo "selected='selected'";} ?>>Bachelor of Arts</option>
                        <option value="BS" <?php if ($val=="BS") {echo "selected='selected'";} ?>>Bachelor of Science</option>
                        <option value="C" <?php if ($val=="C") {echo "selected='selected'";} ?>>Certification</option>
                        <option value="CW" <?php if ($val=="CW") {echo "selected='selected'";} ?>>College in Progress</option>
                        <option value="D" <?php if ($val=="D") {echo "selected='selected'";} ?>>Doctorate Degree</option>
                        <option value="GED" <?php if ($val=="GED") {echo "selected='selected'";} ?>>GED</option>
                        <option value="H" <?php if ($val=="H") {echo "selected='selected'";} ?>>High School Diploma</option>
                        <option value="JD" <?php if ($val=="JD") {echo "selected='selected'";} ?>>Juris Doctorate</option>
                        <option value="M" <?php if ($val=="M") {echo "selected='selected'";} ?>>Master's Degree</option>
                        <option value="MA" <?php if ($val=="MA") {echo "selected='selected'";} ?>>Masters of Art</option>
                        <option value="MBA" <?php if ($val=="MBA") {echo "selected='selected'";} ?>>Master of Business Admin.</option>
                        <option value="MD" <?php if ($val=="MD") {echo "selected='selected'";} ?>>MD</option>
                        <option value="MS" <?php if ($val=="MS") {echo "selected='selected'";} ?>>Masters of Science</option>
                        <option value="NCD" <?php if ($val=="NCD") {echo "selected='selected'";} ?>>Attended College No Degree</option>
                        <option value="PHD" <?php if ($val=="PHD") {echo "selected='selected'";} ?>>Philosophiae Doctor</option>
                        <option value="TS" <?php if ($val=="TS") {echo "selected='selected'";} ?>>Trade Scholol</option>  
 <?php  
    return;   
}                        

function StateOption($name, $default)
{
  global $cbo_field;
    if ($cbo_field=="")
    {
        $cbo_field .=$name;
    }
    else
    {
        $cbo_field .= ";" . $name;
    }
    $val= loadValue($name);
?>
                        <option value=""<?php if ($val=="") {echo "selected='selected'";} ?>> pick a state </option>
                        <option value="AK" <?php if ($val=="AK") {echo "selected='selected'";} ?>>Alaska</option>
                        <option value="AL" <?php if ($val=="AL") {echo "selected='selected'";} ?>>Alabama</option>
                        <option value="AR" <?php if ($val=="AR") {echo "selected='selected'";} ?>>Arkansas</option>
                        <option value="AZ" <?php if ($val=="AZ") {echo "selected='selected'";} ?>>Arizona</option>
                        <option value="CA" <?php if ($val=="CA") {echo "selected='selected'";} ?>>California</option>
                        <option value="CO" <?php if ($val=="CO") {echo "selected='selected'";} ?>>Colorado</option>
                        <option value="CT" <?php if ($val=="CT") {echo "selected='selected'";} ?>>Connecticut</option>
                        <option value="DC" <?php if ($val=="DC") {echo "selected='selected'";} ?>>District of Columbia</option>
                        <option value="DE" <?php if ($val=="DE") {echo "selected='selected'";} ?>>Delaware</option>
                        <option value="FL" <?php if ($val=="FL") {echo "selected='selected'";} ?>>Florida</option>
                        <option value="GA" <?php if ($val=="GA") {echo "selected='selected'";} ?>>Georgia</option>
                        <option value="HI" <?php if ($val=="HI") {echo "selected='selected'";} ?>>Hawaii</option>
                        <option value="IA" <?php if ($val=="IA") {echo "selected='selected'";} ?>>Iowa</option>
                        <option value="ID" <?php if ($val=="ID") {echo "selected='selected'";} ?>>Idaho</option>
                        <option value="IL" <?php if ($val=="IL") {echo "selected='selected'";} ?>>Illinois</option>
                        <option value="IN" <?php if ($val=="IN") {echo "selected='selected'";} ?>>Indiana</option>
                        <option value="KS" <?php if ($val=="KS") {echo "selected='selected'";} ?>>Kansas</option>
                        <option value="KY" <?php if ($val=="KY") {echo "selected='selected'";} ?>>Kentucky</option>
                        <option value="LA" <?php if ($val=="LA") {echo "selected='selected'";} ?>>Louisiana</option>
                        <option value="MA" <?php if ($val=="MA") {echo "selected='selected'";} ?>>Massachusetts</option>
                        <option value="MD" <?php if ($val=="MD") {echo "selected='selected'";} ?>>Maryland</option>
                        <option value="ME" <?php if ($val=="ME") {echo "selected='selected'";} ?>>Maine</option>
                        <option value="MI" <?php if ($val=="MI") {echo "selected='selected'";} ?>>Michigan</option>
                        <option value="MN" <?php if ($val=="MN") {echo "selected='selected'";} ?>>Minnesota</option>
                        <option value="MN" <?php if ($val=="MN") {echo "selected='selected'";} ?>>Missouri</option>
                        <option value="MS" <?php if ($val=="MS") {echo "selected='selected'";} ?>>Mississippi</option>
                        <option value="MS" <?php if ($val=="MS") {echo "selected='selected'";} ?>>Montana</option>
                        <option value="NC" <?php if ($val=="NC") {echo "selected='selected'";} ?>>North Carolina</option>
                        <option value="ND" <?php if ($val=="ND") {echo "selected='selected'";} ?>>North Dakota</option>
                        <option value="NE" <?php if ($val=="NE") {echo "selected='selected'";} ?>>Nebraska</option>
                        <option value="NH" <?php if ($val=="NH") {echo "selected='selected'";} ?>>New Hampshire</option>
                        <option value="NJ" <?php if ($val=="NJ") {echo "selected='selected'";} ?>>New Jersey</option>
                        <option value="NM" <?php if ($val=="NM") {echo "selected='selected'";} ?>>New Mexico</option>
                        <option value="NV" <?php if ($val=="NV") {echo "selected='selected'";} ?>>Nevada</option>
                        <option value="NY" <?php if ($val=="NY") {echo "selected='selected'";} ?>>New York</option>
                        <option value="OH" <?php if ($val=="OH") {echo "selected='selected'";} ?>>Ohio</option>
                        <option value="OK" <?php if ($val=="OK") {echo "selected='selected'";} ?>>Oklahoma</option>
                        <option value="OR" <?php if ($val=="OR") {echo "selected='selected'";} ?>>Oregon</option>
                        <option value="PA" <?php if ($val=="PA") {echo "selected='selected'";} ?>>Pennsylvania</option>
                        <option value="RI" <?php if ($val=="RI") {echo "selected='selected'";} ?>>Rhode Island</option>
                        <option value="SC" <?php if ($val=="SC") {echo "selected='selected'";} ?>>South Carolina</option>
                        <option value="SD" <?php if ($val=="SD") {echo "selected='selected'";} ?>>South Dakota</option>
                        <option value="TN" <?php if ($val=="TN") {echo "selected='selected'";} ?>>Tennessee</option>
                        <option value="TX" <?php if ($val=="TX") {echo "selected='selected'";} ?>>Texas</option>
                        <option value="UT" <?php if ($val=="UT") {echo "selected='selected'";} ?>>Utah</option>
                        <option value="VA" <?php if ($val=="VA") {echo "selected='selected'";} ?>>Virginia</option>
                        <option value="VT" <?php if ($val=="VT") {echo "selected='selected'";} ?>>Vermont</option>
                        <option value="WA" <?php if ($val=="WA") {echo "selected='selected'";} ?>>Washington</option>
                        <option value="WV" <?php if ($val=="WV") {echo "selected='selected'";} ?>>West Virginia</option>
                        <option value="WI" <?php if ($val=="WI") {echo "selected='selected'";} ?>>Wisconsin</option>
                        <option value="WY" <?php if ($val=="WY") {echo "selected='selected'";} ?>>Wyoming</option>
                        <option value="PR" <?php if ($val=="PR") {echo "selected='selected'";} ?>>US Other : Puerto Rico</option>
                        <option value="VI" <?php if ($val=="VI") {echo "selected='selected'";} ?>>US Other : Virgin Islands</option>
                        <option value="AB" <?php if ($val=="AB") {echo "selected='selected'";} ?>>Canada Alberta</option>
                        <option value="BC" <?php if ($val=="BC") {echo "selected='selected'";} ?>>Canada British Columbia</option>
                        <option value="MB" <?php if ($val=="MB") {echo "selected='selected'";} ?>>Canada Manitoba</option>
                        <option value="NB" <?php if ($val=="NB") {echo "selected='selected'";} ?>>Canada New Brunswick</option>
                        <option value="NB" <?php if ($val=="NB") {echo "selected='selected'";} ?>>Canada Newfoundland</option>
                        <option value="NB" <?php if ($val=="NB") {echo "selected='selected'";} ?>>Canada N.W. Territories</option>
                        <option value="NS" <?php if ($val=="NS") {echo "selected='selected'";} ?>>Canada Nova Scotia</option>
                        <option value="ON" <?php if ($val=="ON") {echo "selected='selected'";} ?>>Canada Ontario</option>
                        <option value="PE" <?php if ($val=="PE") {echo "selected='selected'";} ?>>Canada Prince Edward Island</option>
                        <option value="QC" <?php if ($val=="QC") {echo "selected='selected'";} ?>>Canada Quebec</option>
                        <option value="SK" <?php if ($val=="SK") {echo "selected='selected'";} ?>>Canada Saskatchewan</option>
                        <option value="YT" <?php if ($val=="YT") {echo "selected='selected'";} ?>>Canada Yukon Territory</option>  
 <?php  
    return;   
}

function DisciplineOption($name, $default)
{
  global $cbo_field;
    if ($cbo_field=="")
    {
        $cbo_field .=$name;
    }
    else
    {
        $cbo_field .= ";" . $name;
    }
    $val= loadValue($name);
?>
        <option value="--Pick one-- "> --Pick one-- </option>
        <option value="Account Management" <?php if ($val=="Account Management") {echo "selected='selected'";} ?>>Account Management</option>
        <option value="Animation" <?php if ($val=="Animation") {echo "selected='selected'";} ?>>Animation</option>
        <option value="B-E Development" <?php if ($val=="B-E Development") {echo "selected='selected'";} ?>>B-E Development</option>
        <option value="Content Management" <?php if ($val=="Content Management") {echo "selected='selected'";} ?>>Content Management</option>
        <option value="Copywriting" <?php if ($val=="Copywriting") {echo "selected='selected'";} ?>>Copywriting</option>
        <option value="Editor" <?php if ($val=="Editor") {echo "selected='selected'";} ?>>Editor</option>
        <option value="Event coordinator" <?php if ($val=="Event coordinator") {echo "selected='selected'";} ?>>Event coordinator</option>
        <option value="Environmental Graphics" <?php if ($val=="Environmental Graphics") {echo "selected='selected'";} ?>>Environmental Graphics</option>
        <option value="Game Design-Development" <?php if ($val=="Game Design-Development") {echo "selected='selected'";} ?>>Game Design-Development</option>
        <option value="Illustration" <?php if ($val=="Illustration") {echo "selected='selected'";} ?>>Illustration</option>
        <option value="Marketing" <?php if ($val=="Marketing") {echo "selected='selected'";} ?>>Marketing</option>
        <option value="Marketing Research" <?php if ($val=="Marketing Research") {echo "selected='selected'";} ?>>Marketing Research</option>
        <option value="Media" <?php if ($val=="Media") {echo "selected='selected'";} ?>>Media</option>
        <option value="Mobile Application" <?php if ($val=="Mobile Application") {echo "selected='selected'";} ?>>Mobile Application</option>
        <option value="Motion Graphics" <?php if ($val=="Motion Graphics") {echo "selected='selected'";} ?>>Motion Graphics</option>
        <option value="Photo Retouching" <?php if ($val=="Photo Retouching") {echo "selected='selected'";} ?>>Photo Retoucing</option>
        <option value="Photography" <?php if ($val=="Photography") {echo "selected='selected'";} ?>>Photography</option>
        <option value="Print Design" <?php if ($val=="Print Design") {echo "selected='selected'";} ?>>Print Design</option>
        <option value="Print Production" <?php if ($val=="Print Production") {echo "selected='selected'";} ?>>Print Production</option>
        <option value="Producer Broadcast" <?php if ($val=="Producer Broadcast") {echo "selected='selected'";} ?>>Producer Broadcast</option>
        <option value="Producer Web" <?php if ($val=="Producer Web") {echo "selected='selected'";} ?>>Producer Web</option>
        <option value="Production Management" <?php if ($val=="Production Management") {echo "selected='selected'";} ?>>Production Management</option>
        <option value="Project Manager" <?php if ($val=="Project Manager") {echo "selected='selected'";} ?>>Project Manager</option>
        <option value="SEO" <?php if ($val=="SEO") {echo "selected='selected'";} ?>>SEO</option>
        <option value="Social Media" <?php if ($val=="Social Media") {echo "selected='selected'";} ?>>Social Media</option>
        <option value="Traffic Management" <?php if ($val=="Traffic Management") {echo "selected='selected'";} ?>>Traffic Management</option>
        <option value="3D Modeling" <?php if ($val=="3D Modeling") {echo "selected='selected'";} ?>>3D Modeling</option>
        <option value="Web Design" <?php if ($val=="Web Design") {echo "selected='selected'";} ?>>Web Design</option>
        <option value="Web Frontend Production" <?php if ($val=="Web Frontend Production") {echo "selected='selected'";} ?>>Web Frontend Production</option>
        <option value="Web Development" <?php if ($val=="Web Development") {echo "selected='selected'";} ?>>Web Development</option>
 <?php  
    return;   
}              

function RegionOption($name, $default)
{
  global $cbo_field;
    if ($cbo_field=="")
    {
        $cbo_field .=$name;
    }
    else
    {
        $cbo_field .= ";" . $name;
    }
    $val= loadValue($name);
?>

      <option value="all" <?php if ($val=="") {echo "selected='selected'";} ?>>-Pick Region-</option>
      <option value="northeast" <?php if ($val=="NORTHEAST") {echo "selected='selected'";} ?>>NORTHEAST</option>     
      <option value="midwest" <?php if ($val=="MW") {echo "selected='selected'";} ?>>MIDWEST</option>
      <option value="south" <?php if ($val=="SOUTH") {echo "selected='selected'";} ?>>SOUTH</option>
      <option value="west" <?php if ($val=="W") {echo "selected='selected'";} ?>>WEST</option>
      <option value="pacific" <?php if ($val=="PACIFIC") {echo "selected='selected'";} ?>>PACIFIC</option>
      <option value="canada" <?php if ($val=="CANADA") {echo "selected='selected'";} ?>>CANADA</option>
    
<?php  
return;   
}
function CellCarrierOption($name, $default)
{
  global $cbo_field;
    if ($cbo_field=="")
    {
        $cbo_field .=$name;
    }
    else
    {
        $cbo_field .= ";" . $name;
    }
    $val= loadValue($name);
?>
          <option value="" <?php if ($val=="") {echo "selected='selected'";} ?>>- Pick Cell Carrier -</option>
          <option value="ALL" <?php if ($val=="ALL") {echo "selected='selected'";} ?>>Alltel</option>
          <option value="ATT" <?php if ($val=="ATT") {echo "selected='selected'";} ?>>AT&amp;T Wireless</option>
          <option value="BEM" <?php if ($val=="BEM") {echo "selected='selected'";} ?>>Bell Mobility</option>
          <option value="C1" <?php if ($val=="C1") {echo "selected='selected'";} ?>>Cellular One</option>
          <option value="DNS" <?php if ($val=="DNS") {echo "selected='selected'";} ?>>Do Not Text Me</option>
          <option value="FI" <?php if ($val=="FI") {echo "selected='selected'";} ?>>Fido</option>
          <option value="KUD" <?php if ($val=="KUD") {echo "selected='selected'";} ?>>Kudo Mobile</option>
          <option value="MP" <?php if ($val=="MP") {echo "selected='selected'";} ?>>Metro PCS</option>
          <option value="MTS" <?php if ($val=="MTS") {echo "selected='selected'";} ?>>MTS</option>
          <option value="NEX" <?php if ($val=="NEX") {echo "selected='selected'";} ?>>Nextel</option>
          <option value="PC" <?php if ($val=="PC") {echo "selected='selected'";} ?>>President's Choice</option>
          <option value="QW" <?php if ($val=="QW") {echo "selected='selected'";} ?>>Qwest Wireless</option>
          <option value="RW" <?php if ($val=="RW") {echo "selected='selected'";} ?>>Rogers Wireless</option>
          <option value="SAS" <?php if ($val=="SAS") {echo "selected='selected'";} ?>>Sasktel</option>
          <option value="SOL" <?php if ($val=="SOL") {echo "selected='selected'";} ?>>Solo</option>
          <option value="SP" <?php if ($val=="SP") {echo "selected='selected'";} ?>>Sprint PCS</option>
          <option value="TEL" <?php if ($val=="TEL") {echo "selected='selected'";} ?>>Telus</option>
          <option value="TM" <?php if ($val=="TM") {echo "selected='selected'";} ?>>T-Mobile</option>
          <option value="USC" <?php if ($val=="USC") {echo "selected='selected'";} ?>>US Cellular</option>
          <option value="V" <?php if ($val=="V") {echo "selected='selected'";} ?>>Verizon Wireless</option>
          <option value="VC" <?php if ($val=="VC") {echo "selected='selected'";} ?>>Virgin Canada</option>
          <option value="VM" <?php if ($val=="VM") {echo "selected='selected'";} ?>>Virgin USA Mobile</option> 
          <option value="MM" <?php if ($val=="MM") {echo "selected='selected'";} ?>>Not Listed ABove</option>         
    <?php  
 return;   
} 


function PositionHeldOption($name, $default)
{
  global $cbo_field;
    if ($cbo_field=="")
    {
        $cbo_field .=$name;
    }
    else
    {
        $cbo_field .= ";" . $name;
    }
    $val= loadValue($name);
?>
        <option value="" <?php if ($val=="") {echo "selected='selected'";} ?>>--Pick One--</option>
        <option value="Manager" <?php if ($val=="Manager") {echo "selected='selected'";} ?>>Manager</option>
        <option value="Suppervisor" <?php if ($val=="Suppervisor") {echo "selected='selected'";} ?>>Suppervisor</option>
        <option value="Developer" <?php if ($val=="Developer") {echo "selected='selected'";} ?>>Developer</option>
        <option value="Designer" <?php if ($val=="Designer") {echo "selected='selected'";} ?>>Designer</option>
        <option value="Other" <?php if ($val=="Other") {echo "selected='selected'";} ?>>Other</option>    
<?php  
return;   
}


function SalaryOption($name, $default)
{
  global $cbo_field;
    if ($cbo_field=="")
    {
        $cbo_field .=$name;
    }
    else
    {
        $cbo_field .= ";" . $name;
    }
    $val= loadValue($name);
?>
        <option value="" <?php if ($val=="") {echo "selected='selected'";} ?>>--Pick One--</option>
        <option value="20000-40000" <?php if ($val=="20000-40000") {echo "selected='selected'";} ?>>20000-40000</option>
        <option value="40000-60000" <?php if ($val=="40000-60000") {echo "selected='selected'";} ?>>40000-60000</option>
<?php  
return;   
}


function WageOption($name, $default)
{
  global $cbo_field;
    if ($cbo_field=="")
    {
        $cbo_field .=$name;
    }
    else
    {
        $cbo_field .= ";" . $name;
    }
    $val= loadValue($name);
?>
	<option value="Hour" <?php if ($val=="") {echo "selected='selected'";} ?>>Hour</option>
        <option value="Hour" <?php if ($val=="Hour") {echo "selected='selected'";} ?>>Hour</option>
        <option value="Year" <?php if ($val=="Year") {echo "selected='selected'";} ?>>Year</option>
<?php  
return;   
}

function EmploymentContact($name, $default)
{
  global $cbo_field;
    if ($cbo_field=="")
    {
        $cbo_field .=$name;
    }
    else
    {
        $cbo_field .= ";" . $name;
    }
    $val= loadValue($name);
?>
	<option value="1" <?php if ($val=="") {echo "selected='selected'";} ?>>Can contact</option>
        <option value="1" <?php if ($val=="1") {echo "selected='selected'";} ?>>Can contact</option>
        <option value="0" <?php if ($val=="0") {echo "selected='selected'";} ?>>Do not contact</option>
<?php  
return;   
}


function EmploymentStatus($name, $default)
{
  global $cbo_field;
    if ($cbo_field=="")
    {
        $cbo_field .=$name;
    }
    else
    {
        $cbo_field .= ";" . $name;
    }
    $val= loadValue($name);
?>
<div style="float:left;">
	<input value="Yes" name="<?php echo $name?>" id="<?php echo $name?>" type="radio" class="styled">
</div>
<div style="float:left; padding-top:3px;">
	Yes
</div>	
<div style="float:left; padding-left:15px;">
	<input value="No" name="<?php echo $name?>" id="<?php echo $name?>" type="radio" class="styled">
</div>
<div style="float:left; padding-left:15px; display : none;">
	<input value="xx" name="<?php echo $name?>" id="<?php echo $name?>" type="radio" class="styled" checked>
</div>

<div style="float:left;; padding-top:3px;	">
	No
</div>	


<?php  
return;   
}





function EmploymentTypeOption($name, $default)
{
  global $cbo_field;
    if ($cbo_field=="")
    {
        $cbo_field .=$name;
    }
    else
    {
        $cbo_field .= ";" . $name;
    }
    $val= loadValue($name);
?>
        <option value="" <?php if ($val=="TS") {echo "selected='selected'";} ?>>--Pick One--</option>
        <option value="Full time" <?php if ($val=="Full time") {echo "selected='selected'";} ?>>Full time</option>
        <option value="Contractor" <?php if ($val=="Contractor") {echo "selected='selected'";} ?>>Contractor</option>
        <option value="Temporary" <?php if ($val=="Temporary") {echo "selected='selected'";} ?>>Temporary</option>
<?php  
return;   
}


function AvailHourOption($name, $default)
{
  global $cbo_field;
    if ($cbo_field=="")
    {
        $cbo_field .=$name;
    }
    else
    {
        $cbo_field .= ";" . $name;
    }
    $val= loadValue($name);
?>
        <option value="" <?php if ($val=="") {echo "selected='selected'";} ?>> CHOOSE TIME </option>
        <option value="40" <?php if ($val=="40") {echo "selected='selected'";} ?>>Full Time (More than 20 hours a week)</option>
        <option value="20" <?php if ($val=="20") {echo "selected='selected'";} ?>>Part Time (0 to 20 hours a week)</option>
        <option value="00" <?php if ($val=="00") {echo "selected='selected'";} ?>>None Full-Time Only</option>
<?php  
return;   
}

function AvailTimeOption($name, $default)
{
  global $cbo_field;
    if ($cbo_field=="")
    {
        $cbo_field .=$name;
    }
    else
    {
        $cbo_field .= ";" . $name;
    }
    $val= loadValue($name);
    
?>
        <option value="" <?php if ($val=="") {echo "selected='selected'";} ?>> CHOOSE TIME </option>
        <option selected value="All Shifts" <?php if ($val=="TS") {echo "selected='selected'";} ?>>Any Time</option>
        <option value="First Shift" <?php if ($val=="First Shift") {echo "selected='selected'";} ?>>9 AM to 6 PM Only</option>
        <option value= "Second and Third Shift" <?php if ($val=="Second and Third Shift") {echo "selected='selected'";} ?>>Nights and Weekends Only</option>
        
        <option value="Not available" <?php if ($val=="Not available") {echo "selected='selected'";} ?>>Not available - Full-Time Only</option>
        <option value="AM only" <?php if ($val=="AM only") {echo "selected='selected'";} ?>>AM only</option>
        <option value="PM only" <?php if ($val=="PM only") {echo "selected='selected'";} ?>>PM only</option>
<?php  
return;   
}

function MinWageOption($name, $default)
{
  global $cbo_field;
    if ($cbo_field=="")
    {
        $cbo_field .=$name;
    }
    else
    {
        $cbo_field .= ";" . $name;
    }
    $val= loadValue($name);
?>
        <option value="" <?php if ($val=="") {echo "selected='selected'";} ?>> CHOOSE RATE </option>
        <option value="20000-40000" <?php if ($val=="20000-40000") {echo "selected='selected'";} ?>>20000-40000</option>
        <option value="40000-60000" <?php if ($val=="40000-60000") {echo "selected='selected'";} ?>>40000-60000</option>
<?php  
return;   
}

function NoticePeriodOption($name, $default)
{
  global $cbo_field;
    if ($cbo_field=="")
    {
        $cbo_field .=$name;
    }
    else
    {
        $cbo_field .= ";" . $name;
    }
    $val= loadValue($name);
?>
        <option value="" <?php if ($val=="") {echo "selected='selected'";} ?>> CHOOSE TIME </option>
        <option value="One Day" <?php if ($val=="One Day") {echo "selected='selected'";} ?>>One Day</option>
        <option value = "1 Week" <?php if ($val=="1 Week") {echo "selected='selected'";} ?>>1 Week</option>
        <option value = "2 Weeks" <?php if ($val=="2 Weeks") {echo "selected='selected'";} ?>>2 Weeks</option>
        <option value = "3+ Weeks" <?php if ($val=="3+ Weeks") {echo "selected='selected'";} ?>>3+ Weeks</option>
        <option value = "More Than One Day" <?php if ($val=="More Than One Day") {echo "selected='selected'";} ?>>More Than One Day</option>
        <option value="Same Day" <?php if ($val=="Same Day") {echo "selected='selected'";} ?>>Same Day</option>
<?php  
return;   
}

function MilesDistanceOption($name, $default)
{
  global $cbo_field;
    if ($cbo_field=="")
    {
        $cbo_field .=$name;
    }
    else
    {
        $cbo_field .= ";" . $name;
    }
    $val= loadValue($name);
?>
        <option value="" <?php if ($val=="") {echo "selected='selected'";} ?>> CHOOSE MILES </option>
        <option value="10" <?php if ($val=="10") {echo "selected='selected'";} ?>>10</option>
        <option value="15" <?php if ($val=="15") {echo "selected='selected'";} ?>>15</option>
        <option value="20" <?php if ($val=="20") {echo "selected='selected'";} ?>>20</option>
        <option value="30" <?php if ($val=="30") {echo "selected='selected'";} ?>>30</option>
        <option value="40" <?php if ($val=="40") {echo "selected='selected'";} ?>>40</option>
        <option value="50" <?php if ($val=="50") {echo "selected='selected'";} ?>>50</option>
<?php  
return;   
}


function PortfolioCatOption($name, $default)
{
  global $cbo_field;
    if ($cbo_field=="")
    {
        $cbo_field .=$name;
    }
    else
    {
        $cbo_field .= ";" . $name;
    }
    $val= loadValue($name);
?>
        <option value="" <?php if ($val=="") {echo "selected='selected'";} ?>> PICK A SKILL USED</option>
        <option value="touching" <?php if ($val=="touching") {echo "selected='selected'";} ?>>touching</option>
        <option value="creatives" <?php if ($val=="creatives") {echo "selected='selected'";} ?>>creatives</option>
        <option value="copyright" <?php if ($val=="copyright") {echo "selected='selected'";} ?>>copyright</option>
<?php  
return;   
}

function JobTitleOption($name, $default)
{
  global $cbo_field;
    if ($cbo_field=="")
    {
        $cbo_field .=$name;
    }
    else
    {
        $cbo_field .= ";" . $name;
    }
    $val= loadValue($name);
?>
        <option value="" <?php if ($val=="") {echo "selected='selected'";} ?>> PICK A JOB TITLE</option>
        <option value="Manager" <?php if ($val=="TS") {echo "selected='selected'";} ?>>Manager</option>
        
<?php  
return;   
}
?>