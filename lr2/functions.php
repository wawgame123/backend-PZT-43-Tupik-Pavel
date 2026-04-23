<?php
function calculateDiscountPrice($price, $discountPercent) {
    $discountAmount = $price * ($discountPercent / 100);
    return $price - $discountAmount;
}
$finalPrice = calculateDiscountPrice(1500, 15);
$squareRoot = sqrt(256);
$roundedNumber = round(14.789, 1);
$randomNumber = rand(1, 100);
$currentDate = date('d.m.Y H:i');
$nextWeek = strtotime('+1 week');
$nextWeekDate = date('d.m.Y', $nextWeek);