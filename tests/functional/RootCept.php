<?php 
$I = new FunctionalTester($scenario);

$I->wantTo('Test / endpoint');

$I->amOnPage('/');
$I->see('Try /hello/:name');
