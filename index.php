<?php
/**
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


$string='<places xmlns="http://where.yahooapis.com/v1/schema.rng" xmlns:yahoo="http://www.yahooapis.com/v1/base.rng" yahoo:start="0" yahoo:count="250" yahoo:total="250">
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424966" xml:lang="en-US">
<woeid>23424966</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Sao Tome E Principe</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424824" xml:lang="en-US">
<woeid>23424824</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Ghana</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424965" xml:lang="en-US">
<woeid>23424965</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Togo</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424854" xml:lang="en-US">
<woeid>23424854</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Ivory Coast</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424764" xml:lang="en-US">
<woeid>23424764</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Benin</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424804" xml:lang="en-US">
<woeid>23424804</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Equatorial Guinea</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424822" xml:lang="en-US">
<woeid>23424822</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Gabon</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424876" xml:lang="en-US">
<woeid>23424876</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Liberia</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424908" xml:lang="en-US">
<woeid>23424908</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Nigeria</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424978" xml:lang="en-US">
<woeid>23424978</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Burkina Faso</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424835" xml:lang="en-US">
<woeid>23424835</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Guinea</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424779" xml:lang="en-US">
<woeid>23424779</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Congo</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424946" xml:lang="en-US">
<woeid>23424946</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Sierra Leone</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424785" xml:lang="en-US">
<woeid>23424785</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Cameroon</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424944" xml:lang="en-US">
<woeid>23424944</woeid>
<placeTypeName code="12">Territory</placeTypeName>
<name>St Helena Ascension and Tristan da Cunha</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424891" xml:lang="en-US">
<woeid>23424891</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Mali</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424929" xml:lang="en-US">
<woeid>23424929</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Guinea-Bissau</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424943" xml:lang="en-US">
<woeid>23424943</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Senegal</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424821" xml:lang="en-US">
<woeid>23424821</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>The Gambia</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424906" xml:lang="en-US">
<woeid>23424906</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Niger</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424745" xml:lang="en-US">
<woeid>23424745</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Angola</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424780" xml:lang="en-US">
<woeid>23424780</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Democratic Republic of Congo</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424792" xml:lang="en-US">
<woeid>23424792</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Central African Republic</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424777" xml:lang="en-US">
<woeid>23424777</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Tchad</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424896" xml:lang="en-US">
<woeid>23424896</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Mauritania</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424740" xml:lang="en-US">
<woeid>23424740</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Algeria</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424794" xml:lang="en-US">
<woeid>23424794</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Cape Verde</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424990" xml:lang="en-US">
<woeid>23424990</woeid>
<placeTypeName code="12">Disputed Territory</placeTypeName>
<name>Western Sahara</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424987" xml:lang="en-US">
<woeid>23424987</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Namibia</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424937" xml:lang="en-US">
<woeid>23424937</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Rwanda</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424774" xml:lang="en-US">
<woeid>23424774</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Burundi</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424974" xml:lang="en-US">
<woeid>23424974</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Uganda</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23425003" xml:lang="en-US">
<woeid>23425003</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Zambia</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/56558055" xml:lang="en-US">
<woeid>56558055</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>The Republic of South Sudan</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424882" xml:lang="en-US">
<woeid>23424882</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Libya</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424755" xml:lang="en-US">
<woeid>23424755</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Botswana</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424893" xml:lang="en-US">
<woeid>23424893</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Morocco</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424952" xml:lang="en-US">
<woeid>23424952</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Sudan</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23425004" xml:lang="en-US">
<woeid>23425004</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Zimbabwe</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424973" xml:lang="en-US">
<woeid>23424973</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Tanzania</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424967" xml:lang="en-US">
<woeid>23424967</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Tunisia</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424863" xml:lang="en-US">
<woeid>23424863</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Kenya</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424825" xml:lang="en-US">
<woeid>23424825</woeid>
<placeTypeName code="12">Territory</placeTypeName>
<name>Gibraltar</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424942" xml:lang="en-US">
<woeid>23424942</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>South Africa</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424889" xml:lang="en-US">
<woeid>23424889</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Malawi</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424880" xml:lang="en-US">
<woeid>23424880</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Lesotho</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424950" xml:lang="en-US">
<woeid>23424950</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Spain</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424802" xml:lang="en-US">
<woeid>23424802</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Egypt</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424897" xml:lang="en-US">
<woeid>23424897</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Malta</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424993" xml:lang="en-US">
<woeid>23424993</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Swaziland</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424925" xml:lang="en-US">
<woeid>23424925</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Portugal</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424902" xml:lang="en-US">
<woeid>23424902</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Mozambique</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424744" xml:lang="en-US">
<woeid>23424744</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Andorra</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424808" xml:lang="en-US">
<woeid>23424808</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Ethiopia</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424806" xml:lang="en-US">
<woeid>23424806</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Eritrea</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424892" xml:lang="en-US">
<woeid>23424892</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Monaco</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424986" xml:lang="en-US">
<woeid>23424986</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Vatican City</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424852" xml:lang="en-US">
<woeid>23424852</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Israel</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/28289408" xml:lang="en-US">
<woeid>28289408</woeid>
<placeTypeName code="12">Disputed Territory</placeTypeName>
<name>Palestine</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424797" xml:lang="en-US">
<woeid>23424797</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Djibouti</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424819" xml:lang="en-US">
<woeid>23424819</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>France</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424853" xml:lang="en-US">
<woeid>23424853</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Italy</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424833" xml:lang="en-US">
<woeid>23424833</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Greece</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424786" xml:lang="en-US">
<woeid>23424786</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Comoros</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424997" xml:lang="en-US">
<woeid>23424997</woeid>
<placeTypeName code="12">Disputed Territory</placeTypeName>
<name>United Nations Neutral Zone</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424949" xml:lang="en-US">
<woeid>23424949</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Somalia</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/26812346" xml:lang="en-US">
<woeid>26812346</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Cyprus</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424873" xml:lang="en-US">
<woeid>23424873</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Lebanon</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424947" xml:lang="en-US">
<woeid>23424947</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>San Marino</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424860" xml:lang="en-US">
<woeid>23424860</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Jordan</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/28289406" xml:lang="en-US">
<woeid>28289406</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Terres Australes Et Antarctiques Francaises</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424742" xml:lang="en-US">
<woeid>23424742</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Albania</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424957" xml:lang="en-US">
<woeid>23424957</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Switzerland</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424890" xml:lang="en-US">
<woeid>23424890</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Macedonia</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424886" xml:lang="en-US">
<woeid>23424886</woeid>
<placeTypeName code="12">Overseas Collectivity</placeTypeName>
<name>Mayotte</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/20069817" xml:lang="en-US">
<woeid>20069817</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Montenegro</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424843" xml:lang="en-US">
<woeid>23424843</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Croatia</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424761" xml:lang="en-US">
<woeid>23424761</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Bosnia and Herzegovina</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424879" xml:lang="en-US">
<woeid>23424879</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Liechtenstein</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424956" xml:lang="en-US">
<woeid>23424956</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Syria</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424945" xml:lang="en-US">
<woeid>23424945</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Slovenia</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/20069818" xml:lang="en-US">
<woeid>20069818</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Serbia</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424881" xml:lang="en-US">
<woeid>23424881</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Luxembourg</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424757" xml:lang="en-US">
<woeid>23424757</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Belgium</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424771" xml:lang="en-US">
<woeid>23424771</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Bulgaria</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424750" xml:lang="en-US">
<woeid>23424750</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Austria</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424969" xml:lang="en-US">
<woeid>23424969</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Turkey</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23425002" xml:lang="en-US">
<woeid>23425002</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Yemen</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424883" xml:lang="en-US">
<woeid>23424883</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Madagascar</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424909" xml:lang="en-US">
<woeid>23424909</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Netherlands</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424938" xml:lang="en-US">
<woeid>23424938</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Saudi Arabia</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424844" xml:lang="en-US">
<woeid>23424844</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Hungary</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424811" xml:lang="en-US">
<woeid>23424811</woeid>
<placeTypeName code="12">Overseas Region</placeTypeName>
<name>French Guiana</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424829" xml:lang="en-US">
<woeid>23424829</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Germany</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424975" xml:lang="en-US">
<woeid>23424975</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>United Kingdom</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424933" xml:lang="en-US">
<woeid>23424933</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Romania</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424810" xml:lang="en-US">
<woeid>23424810</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Czech Republic</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/28289410" xml:lang="en-US">
<woeid>28289410</woeid>
<placeTypeName code="12">Dependency</placeTypeName>
<name>Bouvet Island</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424855" xml:lang="en-US">
<woeid>23424855</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Iraq</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424877" xml:lang="en-US">
<woeid>23424877</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Slovakia</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424998" xml:lang="en-US">
<woeid>23424998</woeid>
<placeTypeName code="12">Disputed Territory</placeTypeName>
<name>Iraq-Saudi Arabia Neutral Zone</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424803" xml:lang="en-US">
<woeid>23424803</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Ireland</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424941" xml:lang="en-US">
<woeid>23424941</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Seychelles</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424913" xml:lang="en-US">
<woeid>23424913</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Suriname</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424885" xml:lang="en-US">
<woeid>23424885</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Moldova</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424870" xml:lang="en-US">
<woeid>23424870</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Kuwait</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424743" xml:lang="en-US">
<woeid>23424743</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Armenia</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424923" xml:lang="en-US">
<woeid>23424923</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Poland</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424823" xml:lang="en-US">
<woeid>23424823</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Georgia</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424796" xml:lang="en-US">
<woeid>23424796</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Denmark</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424768" xml:lang="en-US">
<woeid>23424768</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Brazil</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424753" xml:lang="en-US">
<woeid>23424753</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Bahrain</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424976" xml:lang="en-US">
<woeid>23424976</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Ukraine</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424930" xml:lang="en-US">
<woeid>23424930</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Qatar</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424836" xml:lang="en-US">
<woeid>23424836</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Guyana</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424741" xml:lang="en-US">
<woeid>23424741</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Azerbaijan</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424738" xml:lang="en-US">
<woeid>23424738</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>United Arab Emirates</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424931" xml:lang="en-US">
<woeid>23424931</woeid>
<placeTypeName code="12">Overseas Region</placeTypeName>
<name>Reunion</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424898" xml:lang="en-US">
<woeid>23424898</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Oman</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424875" xml:lang="en-US">
<woeid>23424875</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Lithuania</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424765" xml:lang="en-US">
<woeid>23424765</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Belarus</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424816" xml:lang="en-US">
<woeid>23424816</woeid>
<placeTypeName code="12">Territory</placeTypeName>
<name>Faroe Islands</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424754" xml:lang="en-US">
<woeid>23424754</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Barbados</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424851" xml:lang="en-US">
<woeid>23424851</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Iran</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424894" xml:lang="en-US">
<woeid>23424894</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Mauritius</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424958" xml:lang="en-US">
<woeid>23424958</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Trinidad and Tobago</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424874" xml:lang="en-US">
<woeid>23424874</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Latvia</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424981" xml:lang="en-US">
<woeid>23424981</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Saint Vincent and the Grenadines</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424951" xml:lang="en-US">
<woeid>23424951</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>St. Lucia</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424826" xml:lang="en-US">
<woeid>23424826</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Grenada</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424884" xml:lang="en-US">
<woeid>23424884</woeid>
<placeTypeName code="12">Overseas Region</placeTypeName>
<name>Martinique</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424917" xml:lang="en-US">
<woeid>23424917</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Paraguay</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424979" xml:lang="en-US">
<woeid>23424979</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Uruguay</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424798" xml:lang="en-US">
<woeid>23424798</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Dominica</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/12577865" xml:lang="en-US">
<woeid>12577865</woeid>
<placeTypeName code="12">Province</placeTypeName>
<name>Aland Islands</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424831" xml:lang="en-US">
<woeid>23424831</woeid>
<placeTypeName code="12">Overseas Region</placeTypeName>
<name>Guadeloupe</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424955" xml:lang="en-US">
<woeid>23424955</woeid>
<placeTypeName code="12">Territory</placeTypeName>
<name>South Georgia and the South Sandwich Islands</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424805" xml:lang="en-US">
<woeid>23424805</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Estonia</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424737" xml:lang="en-US">
<woeid>23424737</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Antigua and Barbuda</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424954" xml:lang="en-US">
<woeid>23424954</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Sweden</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424888" xml:lang="en-US">
<woeid>23424888</woeid>
<placeTypeName code="12">Territory</placeTypeName>
<name>Montserrat</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424910" xml:lang="en-US">
<woeid>23424910</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Norway</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424982" xml:lang="en-US">
<woeid>23424982</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Venezuela</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424940" xml:lang="en-US">
<woeid>23424940</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Saint Kitts and Nevis</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/56042304" xml:lang="en-US">
<woeid>56042304</woeid>
<placeTypeName code="12">Overseas Collectivity</placeTypeName>
<name>Saint Barthelemy</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424762" xml:lang="en-US">
<woeid>23424762</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Bolivia</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/56558058" xml:lang="en-US">
<woeid>56558058</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Sint Maarten</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/56042305" xml:lang="en-US">
<woeid>56042305</woeid>
<placeTypeName code="12">Overseas Collectivity</placeTypeName>
<name>Saint-Martin</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424751" xml:lang="en-US">
<woeid>23424751</woeid>
<placeTypeName code="12">Territory</placeTypeName>
<name>Anguilla</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424939" xml:lang="en-US">
<woeid>23424939</woeid>
<placeTypeName code="12">Territory</placeTypeName>
<name>Saint-Pierre-Et-Miquelon</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424985" xml:lang="en-US">
<woeid>23424985</woeid>
<placeTypeName code="12">Territory</placeTypeName>
<name>US Virgin Islands</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424983" xml:lang="en-US">
<woeid>23424983</woeid>
<placeTypeName code="12">Territory</placeTypeName>
<name>British Virgin Islands</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424845" xml:lang="en-US">
<woeid>23424845</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Iceland</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424972" xml:lang="en-US">
<woeid>23424972</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Turkmenistan</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/56558056" xml:lang="en-US">
<woeid>56558056</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Caribbean Netherlands</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424935" xml:lang="en-US">
<woeid>23424935</woeid>
<placeTypeName code="12">Territory</placeTypeName>
<name>Puerto Rico</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/56558057" xml:lang="en-US">
<woeid>56558057</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Curacao</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424812" xml:lang="en-US">
<woeid>23424812</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Finland</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424814" xml:lang="en-US">
<woeid>23424814</woeid>
<placeTypeName code="12">Territory</placeTypeName>
<name>Falkland Islands</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424787" xml:lang="en-US">
<woeid>23424787</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Colombia</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424736" xml:lang="en-US">
<woeid>23424736</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Aruba</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424849" xml:lang="en-US">
<woeid>23424849</woeid>
<placeTypeName code="12">Territory</placeTypeName>
<name>British Indian Ocean Territory</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424756" xml:lang="en-US">
<woeid>23424756</woeid>
<placeTypeName code="12">Territory</placeTypeName>
<name>Bermuda</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424747" xml:lang="en-US">
<woeid>23424747</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Argentina</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424899" xml:lang="en-US">
<woeid>23424899</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Maldives</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424800" xml:lang="en-US">
<woeid>23424800</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Dominican Republic</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424980" xml:lang="en-US">
<woeid>23424980</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Uzbekistan</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424919" xml:lang="en-US">
<woeid>23424919</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Peru</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424801" xml:lang="en-US">
<woeid>23424801</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Ecuador</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424739" xml:lang="en-US">
<woeid>23424739</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Afghanistan</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424839" xml:lang="en-US">
<woeid>23424839</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Haiti</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424962" xml:lang="en-US">
<woeid>23424962</woeid>
<placeTypeName code="12">Territory</placeTypeName>
<name>Turks and Caicos Islands</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424922" xml:lang="en-US">
<woeid>23424922</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Pakistan</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424871" xml:lang="en-US">
<woeid>23424871</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Kazakhstan</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/28289409" xml:lang="en-US">
<woeid>28289409</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Antarctica</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424782" xml:lang="en-US">
<woeid>23424782</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Chile</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424924" xml:lang="en-US">
<woeid>23424924</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Panama</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424961" xml:lang="en-US">
<woeid>23424961</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Tajikistan</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/28289413" xml:lang="en-US">
<woeid>28289413</woeid>
<placeTypeName code="12">Territory</placeTypeName>
<name>Svalbard and Jan Mayen</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424858" xml:lang="en-US">
<woeid>23424858</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Jamaica</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424778" xml:lang="en-US">
<woeid>23424778</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Sri Lanka</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424793" xml:lang="en-US">
<woeid>23424793</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Cuba</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424828" xml:lang="en-US">
<woeid>23424828</woeid>
<placeTypeName code="12">Province</placeTypeName>
<name>Greenland</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424758" xml:lang="en-US">
<woeid>23424758</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>The Bahamas</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/28289411" xml:lang="en-US">
<woeid>28289411</woeid>
<placeTypeName code="12">Territory</placeTypeName>
<name>Heard Island and McDonald Islands</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424864" xml:lang="en-US">
<woeid>23424864</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Kyrgyzstan</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424791" xml:lang="en-US">
<woeid>23424791</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Costa Rica</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424783" xml:lang="en-US">
<woeid>23424783</woeid>
<placeTypeName code="12">Territory</placeTypeName>
<name>Cayman Islands</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424928" xml:lang="en-US">
<woeid>23424928</woeid>
<placeTypeName code="12">Disputed Territory</placeTypeName>
<name>Disputed Territory</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424915" xml:lang="en-US">
<woeid>23424915</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Nicaragua</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424848" xml:lang="en-US">
<woeid>23424848</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>India</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424841" xml:lang="en-US">
<woeid>23424841</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Honduras</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424911" xml:lang="en-US">
<woeid>23424911</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Nepal</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424807" xml:lang="en-US">
<woeid>23424807</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>El Salvador</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424760" xml:lang="en-US">
<woeid>23424760</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Belize</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424834" xml:lang="en-US">
<woeid>23424834</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Guatemala</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424759" xml:lang="en-US">
<woeid>23424759</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Bangladesh</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424770" xml:lang="en-US">
<woeid>23424770</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Bhutan</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424784" xml:lang="en-US">
<woeid>23424784</woeid>
<placeTypeName code="12">Territory</placeTypeName>
<name>Cocos (Keeling) Islands</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424763" xml:lang="en-US">
<woeid>23424763</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Myanmar</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424948" xml:lang="en-US">
<woeid>23424948</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Singapore</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424960" xml:lang="en-US">
<woeid>23424960</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Thailand</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424977" xml:lang="en-US">
<woeid>23424977</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>United States</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424869" xml:lang="en-US">
<woeid>23424869</woeid>
<placeTypeName code="12">Territory</placeTypeName>
<name>Christmas Island</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424776" xml:lang="en-US">
<woeid>23424776</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Cambodia</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424900" xml:lang="en-US">
<woeid>23424900</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Mexico</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424775" xml:lang="en-US">
<woeid>23424775</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Canada</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424872" xml:lang="en-US">
<woeid>23424872</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Laos</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424984" xml:lang="en-US">
<woeid>23424984</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Vietnam</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424846" xml:lang="en-US">
<woeid>23424846</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Indonesia</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424781" xml:lang="en-US">
<woeid>23424781</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>China</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424887" xml:lang="en-US">
<woeid>23424887</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Mongolia</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424901" xml:lang="en-US">
<woeid>23424901</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Malaysia</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424773" xml:lang="en-US">
<woeid>23424773</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Brunei</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424936" xml:lang="en-US">
<woeid>23424936</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Russia</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424921" xml:lang="en-US">
<woeid>23424921</woeid>
<placeTypeName code="12">Disputed Territory</placeTypeName>
<name>Spratly Islands</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/20070017" xml:lang="en-US">
<woeid>20070017</woeid>
<placeTypeName code="12">Special Administrative Region</placeTypeName>
<name>Macau</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/24865698" xml:lang="en-US">
<woeid>24865698</woeid>
<placeTypeName code="12">Special Administrative Region</placeTypeName>
<name>Hong Kong</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424934" xml:lang="en-US">
<woeid>23424934</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Philippines</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424971" xml:lang="en-US">
<woeid>23424971</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Taiwan</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424968" xml:lang="en-US">
<woeid>23424968</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>East Timor</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424918" xml:lang="en-US">
<woeid>23424918</woeid>
<placeTypeName code="12">Territory</placeTypeName>
<name>Pitcairn Islands</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424865" xml:lang="en-US">
<woeid>23424865</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>North Korea</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424868" xml:lang="en-US">
<woeid>23424868</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>South Korea</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424927" xml:lang="en-US">
<woeid>23424927</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Palau</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424748" xml:lang="en-US">
<woeid>23424748</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Australia</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424856" xml:lang="en-US">
<woeid>23424856</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Japan</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424926" xml:lang="en-US">
<woeid>23424926</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Papua New Guinea</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424832" xml:lang="en-US">
<woeid>23424832</woeid>
<placeTypeName code="12">Territory</placeTypeName>
<name>Guam</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424788" xml:lang="en-US">
<woeid>23424788</woeid>
<placeTypeName code="12">Territory</placeTypeName>
<name>Northern Mariana Islands</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424817" xml:lang="en-US">
<woeid>23424817</woeid>
<placeTypeName code="12">Overseas Collectivity</placeTypeName>
<name>French Polynesia</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424815" xml:lang="en-US">
<woeid>23424815</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Federated States of Micronesia</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424867" xml:lang="en-US">
<woeid>23424867</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Kiribati</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424766" xml:lang="en-US">
<woeid>23424766</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Solomon Islands</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/28289407" xml:lang="en-US">
<woeid>28289407</woeid>
<placeTypeName code="12">Territory</placeTypeName>
<name>United States Minor Outlying Islands</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424795" xml:lang="en-US">
<woeid>23424795</woeid>
<placeTypeName code="12">Territory</placeTypeName>
<name>Cook Islands</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424912" xml:lang="en-US">
<woeid>23424912</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Nauru</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424903" xml:lang="en-US">
<woeid>23424903</woeid>
<placeTypeName code="12">Territory</placeTypeName>
<name>New Caledonia</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424907" xml:lang="en-US">
<woeid>23424907</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Vanuatu</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424932" xml:lang="en-US">
<woeid>23424932</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Marshall Islands</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424963" xml:lang="en-US">
<woeid>23424963</woeid>
<placeTypeName code="12">Territory</placeTypeName>
<name>Tokelau</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424905" xml:lang="en-US">
<woeid>23424905</woeid>
<placeTypeName code="12">Territory</placeTypeName>
<name>Norfolk Island</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424746" xml:lang="en-US">
<woeid>23424746</woeid>
<placeTypeName code="12">Territory</placeTypeName>
<name>American Samoa</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424904" xml:lang="en-US">
<woeid>23424904</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Niue</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424916" xml:lang="en-US">
<woeid>23424916</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>New Zealand</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424992" xml:lang="en-US">
<woeid>23424992</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Samoa</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424989" xml:lang="en-US">
<woeid>23424989</woeid>
<placeTypeName code="12">Overseas Collectivity</placeTypeName>
<name>Wallis-Et-Futuna</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424964" xml:lang="en-US">
<woeid>23424964</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Tonga</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424970" xml:lang="en-US">
<woeid>23424970</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Tuvalu</name>
</place>
<place yahoo:uri="http://where.yahooapis.com/v1/place/23424813" xml:lang="en-US">
<woeid>23424813</woeid>
<placeTypeName code="12">Country</placeTypeName>
<name>Fiji</name>
</place>
</places>';

$parse=XMLtoArray($string);
$tot=count($parse['PLACES']['PLACE']);
$con=mysql_connect('localhost','root','');
mysql_select_db('woeidgrabber',$con);

for($i=0;$i<$tot;$i++)
{
    $result=mysql_query("insert into country_list (country,woeid,placetype,code) values ('".$parse['PLACES']['PLACE'][$i]['NAME']."','".$parse['PLACES']['PLACE'][$i]['WOEID']."','".$parse['PLACES']['PLACE'][$i]['PLACETYPENAME']['content']."','".$parse['PLACES']['PLACE'][$i]['PLACETYPENAME']['CODE']."')") or die(mysql_error());
    //echo 'Country Name: '.$parse['PLACES']['PLACE'][$i]['NAME'].'<br> WOEID: '.$parse['PLACES']['PLACE'][$i]['WOEID'].'<br> Place Type:'.$parse['PLACES']['PLACE'][$i]['PLACETYPENAME']['content'].'<br> Code: '.$parse['PLACES']['PLACE'][$i]['PLACETYPENAME']['CODE'].'<br>';
}
?>