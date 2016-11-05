<?php 
$I = new AcceptanceTester($scenario);

$I->wantTo('Test /hello/:text endpoint');

$I->amOnPage('/hello/BigCommerce');
$I->see('Hello BigCommerce');
