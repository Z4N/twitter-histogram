<?php
$I = new FunctionalTester($scenario);

$I->wantTo('Test /hello/:text endpoint');

$I->amOnPage('/hello/BigCommerce');
$I->see('Hello BigCommerce');