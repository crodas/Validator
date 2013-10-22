$tmp = preg_replace('([^0-9])', '', {{$input}});
if (empty($tmp)) {
    {{$self->result}} = false;
} else {
    $sum = 0;
    $tmp = strrev($tmp);
    for ($i = 0; $i < strlen($tmp); $i++) {
        $current = substr($tmp, $i, 1);
        if ($i % 2 == 1) {
            $current *= 2;
            if ($current > 9) {
                $firstDigit = $current % 10;
                $secondDigit = ($current - $firstDigit) / 10;
                $current = $firstDigit + $secondDigit;
            }
        }
        $sum += $current;
    }

    {{$self->result}} = ($sum % 10 == 0);
}
