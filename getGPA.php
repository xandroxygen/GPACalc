
<?php

    $GRADELIST = array(
        "A+"=>"4.0",
        "A"=>"4.0",
        "A-"=>"3.7",
        "B+"=>"3.4",
        "B"=>"3.0",
        "B-"=>"2.7",
        "C+"=>"2.4",
        "C"=>"2.0",
        "C-+"=>"1.7",
        "D+"=>"1.4",
        "D"=>"1.0",
        "D-"=>"0.7",
        "E"=>"0.0",
    );
    
    // check input
    function validate_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    // convert grades to points per hour
    // returns -1 if grade not recognized
    function grade_convert($g)
    {   
        global $GRADELIST;
        $p = -1.0;
        if (array_key_exists($g,$GRADELIST))
        {
            $p = $GRADELIST[$g];
        }
        return $p;
    }

    // import JSON string
    $raw_data = file_get_contents('php://input');
    $json_data = json_decode($raw_data, true);

    // parse form input
    $name = "";
    $points =  $hours = $gpa = 0.0;
    
    $name = validate_input($json_data[0]["person_name"]);
    $points = validate_input($json_data[0]["points"]);
    $hours = validate_input($json_data[0]["hours"]);
    $classes = $json_data[0]["classes"];

    // add classes in
    foreach($classes as $c)
    {
        $c_pts_per_hour = grade_convert($c[grade]);
        if ($c_pts_per_hour > -1) // else, invalid grade (lowercase, P, IE, W, etc)
        {   
            // find grade points, multiply by class hours, add to total hours 
            $c_pts = $c_pts_per_hour * $c[hours]; 
            $points += $c_pts;
            $hours += $c[hours];
        }
    }

    // calculate and output GPA
        if (is_numeric($hours) && ($hours > 0))
        {
            $gpa = $points / $hours;
        }
    echo $name . ", your GPA is " . number_format($gpa,2) . ", and you have earned " . $hours . " total hours.";
?>