<?php

namespace common\models;

class Country extends \yii\base\BaseObject
{
    // string $language
    // format 'en', 'en-US', 'en_US'
    private $language;

    public function init() {
        if (empty($this->language)) {
            $this->language = str_replace('-', '_', \Yii::$app->language);
        }
    }

    public function setLanguage($language)
    {
        if (empty($language)) {
            $this->language = str_replace('-', '_', \Yii::$app->language);
        } else {
            $this->language = str_replace('-', '_', $language);
        }
    }

    public function getCountriesList()
    {
        $list = include __DIR__ . "/country/{$this->language}/country.php";
        return $list;
    }

    public function getCurrenciesList()
    {
        $list = include __DIR__ . "/country/en/currency.php";
        return $list;
    }

    public static function timeZoneList()
    {
        $list = [];
        foreach (\DateTimeZone::listIdentifiers() as $timezone) {
            $list[$timezone] = $timezone;
        }

        return $list;
    }

    public static function phoneLines()
    {
        $list = include __DIR__ . "/country/en/phone.php";
        return $list;
    }
}