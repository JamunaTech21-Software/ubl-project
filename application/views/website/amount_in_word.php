<?php
## Amount in word
function convert_number_to_words(float $number) {
    $decimal = round($number - ($no = floor($number)), 2) * 100;
    $decimal = min($decimal, 99); // Ensure decimal doesn't exceed 99
    
    $hundred = null;
    $digits_length = strlen($no);
    $i = 0;
    $str = array();
    $words = array(
        0 => 'zero', 1 => 'One', 2 => 'Two',
        3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six',
        7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
        10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve',
        13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
        16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',
        19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
        40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty',
        70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety'
    );
    $digits = array('', 'Hundred','Thousand','Lac','Crore');

    while($i < $digits_length) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += $divider == 10 ? 1 : 2;

        if($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            
            $str[] = ($number < 21) 
                ? $words[$number].' '.$digits[$counter].$plural.' '.$hundred
                : $words[floor($number/10)*10].' '.($number%10 ? $words[$number%10] : '').' '.$digits[$counter].$plural.' '.$hundred;
        } else {
            $str[] = null;
        }
    }

    $Rupees = implode('', array_reverse($str));
    
    $paise = '';
    if ($decimal > 0) {
        $paise = " and ";
        if ($decimal < 21) {
            $paise .= $words[$decimal];
        } else {
            $paise .= $words[floor($decimal / 10) * 10];
            if ($decimal % 10 != 0) {
                $paise .= ' ' . $words[$decimal % 10];
            }
        }
        $paise .= ' Paisa';
    }

    return ($Rupees ? $Rupees . 'Taka ' : '') . $paise . ' Only';
}
?>
