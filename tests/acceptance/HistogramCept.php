<?php 
$I = new AcceptanceTester($scenario);

$I->wantTo('Test /histogram/:username endpoint');

$I->amOnPage('/histogram/BigCommerce');

$I->seeResponseIsJson();
$I->seeResponseCodeIs(200);

$hourCounts = array();
for ($i=0;$i<=24;$i++) {
    $hourCounts[sprintf("%02d", $i).'h'] = 'integer';
}

// Check we have the wanted Json return with hours and counts
// Should look like {"00h":'integer',"01h":'integer',...}
$I->seeResponseMatchesJsonType($hourCounts);



