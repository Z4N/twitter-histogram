<?php 
$I = new FunctionalTester($scenario);

$I->wantTo('Test /histogram/:username endpoint');

$I->amOnPage('/histogram/BigCommerce');

// Todo check return is correct Json

$I->seeResponseCodeIs(200);