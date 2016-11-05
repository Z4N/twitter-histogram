<?php 
$I = new AcceptanceTester($scenario);

$I->wantTo('Test / endpoint');

$I->amOnPage('/');
$I->see('Try /hello/:name');
