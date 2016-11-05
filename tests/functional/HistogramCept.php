<?php 
$I = new FunctionalTester($scenario);

$I->wantTo('Test /histogram/:username endpoint');

$I->amOnPage('/histogram/BigCommerce');

$I->seeResponseCodeIs(200);
