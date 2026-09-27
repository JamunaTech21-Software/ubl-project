<?php
function range_string($csv)
{
    // split string using the , character
    $number_array = array_map('intval', explode(',', $csv));
    sort($number_array);

    // Loop through array and build range string
    $previous_number = intval(array_shift($number_array)); 
    $range = false;
    $range_string = "" . $previous_number; 
    foreach ($number_array as $number) {
      $number = intval($number);
      if ($number == $previous_number + 1) {
        $range = true;
      }
      else {
        if ($range) {
          $range_string .= "-$previous_number";
          $range = false;
        }
        $range_string .= ",$number";
      }
      $previous_number = $number;
    }
    if ($range) {
      $range_string .= "-$previous_number";
    }

    return $range_string;
}
?>