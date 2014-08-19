<?php

$url="https://api.twitter.com/1/trends/daily.json?date=".date("Y-m-d", strtotime("yesterday"));

$json_output = json_decode(file_get_contents($url));
$con=mysql_connect('localhost','root','');
mysql_select_db('woeidgrabber',$con);

foreach ($json_output->trends as $datetime=>$vals ) {
    $datetime;

    foreach($vals as $trend)
    {

        $name=mysql_real_escape_string($trend->name);
        $query=mysql_real_escape_string($trend->query);
        $promoted=mysql_real_escape_string($trend->promoted_content);
        $events=mysql_real_escape_string($trend->events);
        $result=mysql_query('select * from trends_master where name="'.$name.'"') or die(mysql_error());
        if(mysql_num_rows($result)==0)
        {
            mysql_query("insert into trends_master (name,query,promoted,events,date) values('".$name."','".$query."','".$promoted."','".$events."','".$datetime."')");
            echo $name." added successfully<br>";

        }
        else{
            echo $name ." already found<br>";
            while($row=mysql_fetch_array($result))
            {
            $count=$row['counts']+1;
            }
            mysql_query("update trends_master set counts='".$count."' where name='".$name."'")or die(mysql_error());
        }
    }
    echo "addition finished for ".$datetime."<br>";
}
?>