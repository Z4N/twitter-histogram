<?php 
$I = new FunctionalTester($scenario);

$I->wantTo('Test /histogram/:username endpoint');

$I->amOnPage('/histogram/Z4N.json');

$I->seeResponseCodeIs(200);
