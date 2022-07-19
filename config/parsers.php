<?php
 declare(strict_types=1);
 return array (
  'dominoParse' => 
  array (
    'parser' => 'App\\Services\\ParserManager\\Drivers\\DominoParseDriver',
    'method' => 'callConnectToParseDiDom',
    'url' => 'https://dominos.ua/uk/chornomorsk/',
    'status' => true,
  ),
  'zharPizza' => 
  array (
    'parser' => 'App\\Services\\ParserManager\\Drivers\\ZharPizzaParseDriver',
    'method' => 'callConnectToParseGuzzle',
    'url' => 'https://store.tildacdn.com/api/getproductslist/?storepartuid=261323000731&recid=264435121&c=1656336574041&getparts=true&getoptions=true&slice=1&&size=36',
    'status' => true,
  ),
  'vdhBar' => 
  array (
    'parser' => 'App\\Services\\ParserManager\\Drivers\\VdhPizzaParseDriver',
    'method' => 'callConnectToParseGuzzle',
    'url' => 'https://store.tildacdn.com/api/getproductslist/?storepartuid=261323000731&recid=264435121&c=1655380264126&getparts=true&getoptions=true',
    'status' => true,
  ),
  'origami' => 
  array (
    'parser' => 'App\\Services\\ParserManager\\Drivers\\OrigamiPizzaParseDriver',
    'method' => 'callConnectToParseDiDom',
    'url' => 'https://origami.od.ua/index.php?route=product/category&path=68',
    'status' => true,
  ),
) ;