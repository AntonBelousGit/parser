<?php
 declare(strict_types=1);
 return array (
  'dominoParse' => 
  array (
    'enable' => true,
    'parser' => 'App\\Services\\ParserManager\\Drivers\\DominoParseDriver',
    'connection' => 'DiDom',
    'url' => 'https://dominos.ua/uk/chornomorsk/',
    'status' => false,
  ),
  'zharPizza' => 
  array (
    'enable' => true,
    'parser' => 'App\\Services\\ParserManager\\Drivers\\ZharPizzaParseDriver',
    'connection' => 'Guzzle',
    'url' => 'https://store.tildacdn.com/api/getproductslist/?storepartuid=261323000731&recid=264435121&c=1656336574041&getparts=true&getoptions=true&slice=1&&size=36',
  ),
  'vdhBar' => 
  array (
    'enable' => true,
    'parser' => 'App\\Services\\ParserManager\\Drivers\\VdhPizzaParseDriver',
    'connection' => 'Guzzle',
    'url' => 'https://store.tildacdn.com/api/getproductslist/?storepartuid=261323000731&recid=264435121&c=1655380264126&getparts=true&getoptions=true',
  ),
  'origami' => 
  array (
    'enable' => true,
    'parser' => 'App\\Services\\ParserManager\\Drivers\\OrigamiPizzaParseDriver',
    'connection' => 'DiDom',
    'url' => 'https://origami.od.ua/index.php?route=product/category&path=68',
  ),
) ;