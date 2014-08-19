<?php
/**
 * Convert XML to an Array
 *
 * @param string  $XML
 * @return array
 *
 * Convert XML to an Array
 *
 * @param string  $XML
 * @return array
 */
function XMLtoArray($XML)
{
    $xml_parser = xml_parser_create();
    xml_parse_into_struct($xml_parser, $XML, $vals);
    xml_parser_free($xml_parser);
    // wyznaczamy tablice z powtarzajacymi sie tagami na tym samym poziomie
    $_tmp='';
    foreach ($vals as $xml_elem) {
        $x_tag=$xml_elem['tag'];
        $x_level=$xml_elem['level'];
        $x_type=$xml_elem['type'];
        if ($x_level!=1 && $x_type == 'close') {
            if (isset($multi_key[$x_tag][$x_level]))
                $multi_key[$x_tag][$x_level]=1;
            else
                $multi_key[$x_tag][$x_level]=0;
        }
        if ($x_level!=1 && $x_type == 'complete') {
            if ($_tmp==$x_tag)
                $multi_key[$x_tag][$x_level]=1;
            $_tmp=$x_tag;
        }
    }
    // jedziemy po tablicy
    foreach ($vals as $xml_elem) {
        $x_tag=$xml_elem['tag'];
        $x_level=$xml_elem['level'];
        $x_type=$xml_elem['type'];
        if ($x_type == 'open')
            $level[$x_level] = $x_tag;
        $start_level = 1;
        $php_stmt = '$xml_array';
        if ($x_type=='close' && $x_level!=1)
            $multi_key[$x_tag][$x_level]++;
        while ($start_level < $x_level) {
            $php_stmt .= '[$level['.$start_level.']]';
            if (isset($multi_key[$level[$start_level]][$start_level]) && $multi_key[$level[$start_level]][$start_level])
                $php_stmt .= '['.($multi_key[$level[$start_level]][$start_level]-1).']';
            $start_level++;
        }
        $add='';
        if (isset($multi_key[$x_tag][$x_level]) && $multi_key[$x_tag][$x_level] && ($x_type=='open' || $x_type=='complete')) {
            if (!isset($multi_key2[$x_tag][$x_level]))
                $multi_key2[$x_tag][$x_level]=0;
            else
                $multi_key2[$x_tag][$x_level]++;
            $add='['.$multi_key2[$x_tag][$x_level].']';
        }
        if (isset($xml_elem['value']) && trim($xml_elem['value'])!='' && !array_key_exists('attributes', $xml_elem)) {
            if ($x_type == 'open')
                $php_stmt_main=$php_stmt.'[$x_type]'.$add.'[\'content\'] = $xml_elem[\'value\'];';
            else
                $php_stmt_main=$php_stmt.'[$x_tag]'.$add.' = $xml_elem[\'value\'];';
            eval($php_stmt_main);
        }
        if (array_key_exists('attributes', $xml_elem)) {
            if (isset($xml_elem['value'])) {
                $php_stmt_main=$php_stmt.'[$x_tag]'.$add.'[\'content\'] = $xml_elem[\'value\'];';
                eval($php_stmt_main);
            }
            foreach ($xml_elem['attributes'] as $key=>$value) {
                $php_stmt_att=$php_stmt.'[$x_tag]'.$add.'[$key] = $value;';
                eval($php_stmt_att);
            }
        }
    }
    return $xml_array;
}


/*$string='<places xmlns="http://where.yahooapis.com/v1/schema.rng" xmlns:yahoo="http://www.yahooapis.com/v1/base.rng" yahoo:start="0" yahoo:count="2" yahoo:total="2">
<place yahoo:uri="http://where.yahooapis.com/v1/place/2347234" xml:lang="en-US">
<woeid>2347234</woeid>
<placeTypeName code="8">Concelho</placeTypeName>
<name>Sao Tome</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/2347233" xml:lang="en-US">
<woeid>2347233</woeid>
<placeTypeName code="8">Concelho</placeTypeName>
<name>Principe</name>
</place>
</places>';*/
$con=mysql_connect('localhost','root','');
mysql_select_db('woeidgrabber',$con);
$query=mysql_query("select * from states_list");
while($row=mysql_fetch_array($query))
{
$country=$row['state'];
$country_id=$row['id'];
    $countryen=urlencode($country);
$url = "http://where.yahooapis.com/v1/counties/".$countryen."?appid=[fRmfdqjV34EReUHcE8mwP.gOoCSAcAQEJKEogxIQTectfX5M3.v9_1yavm.hH2cViLM-]";

$xml = file_get_contents($url);
$parse=XMLtoArray($xml);
//print_r($parse);
$tot=count($parse['PLACES']['PLACE']);


for($i=0;$i<$tot;$i++)
{
    $state=mysql_real_escape_string($parse['PLACES']['PLACE'][$i]['NAME']);
    $woeid=mysql_real_escape_string($parse['PLACES']['PLACE'][$i]['WOEID']);
    $placetype=mysql_real_escape_string($parse['PLACES']['PLACE'][$i]['PLACETYPENAME']['content']);
    $code=mysql_real_escape_string($parse['PLACES']['PLACE'][$i]['PLACETYPENAME']['CODE']);
    $result=mysql_query("insert into town_list (town,woeid,placetype,code,state_id) values ('".$state."','".$woeid."','"
        .$placetype."','"
        .$code."','"
        .$country_id."')") or die(mysql_error());

//echo $tot;
    //echo $parse['PLACES']['PLACE'][$i]['NAME'].'<br>';
   //echo 'Country Name: '.$parse['PLACES']['PLACE'][$i]['NAME'].'<br> WOEID: '.$parse['PLACES']['PLACE'][$i]['WOEID'].'<br> Place Type:'.$parse['PLACES']['PLACE'][$i]['PLACETYPENAME']['content'].'<br> Code: '.$parse['PLACES']['PLACE'][$i]['PLACETYPENAME']['CODE'].'<br>';
}
    echo 'Success for State: '.$country." with id: ".$country_id."<br>";
}
?>
