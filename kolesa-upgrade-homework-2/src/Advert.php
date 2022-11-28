
<?php

require_once 'AdvertInterface.php';

abstract class Advert implements AdvertInterface
{
    protected $rooms;
    protected $price;
    protected $type;
    protected $period;

    protected function getFormattedPrice()
    {
        $priceSize = (int) log10($this->price);
        if ($priceSize >= 6 && $this->price % 10 ** 5 == 0) {
            $formattedPrice = (string) ($this->price / 10 ** 6);
            return  $formattedPrice . ' млн. тг';
        } elseif ($priceSize < 6 && $priceSize >= 3 && $this->price % 10 ** 2 == 0) {
            $formattedPrice = (string) ($this->price / 10 ** 3);
            return $formattedPrice . ' тыс. тг';
        } else {
            $formattedPrice = (string) ((int) $this->price);
            return $formattedPrice . ' тг';
        }
    }

    protected function getHouseType()
    {
        if ($this->type == 'dom') {
            return 'комнатный дом';
        } elseif ($this->type == 'kvartira') {
            return 'комнатную квартиру';
        } else {
            return $this->type;
        }
    }

    protected function getRentPeriod()
    {
        if ($this->period == 'month') {
            return 'в месяц';
        } elseif ($this->period == 'day') {
            return 'в сутки';
        } else {
            return $this->period;
        }
    }

    abstract public function getTitle(): string;
}

// EOF