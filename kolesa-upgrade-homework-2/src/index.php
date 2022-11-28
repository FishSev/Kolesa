<?php

require_once 'Advert.php';

class SaleAdvert extends Advert
{
    public function __construct(int $rooms = NULL, float $price = NULL, string $type = NULL)
    {
        $this->rooms = $rooms;
        $this->price = $price;
        $this->type = $type;
    }

    public function getTitle(): string
    {
        $formatted_txt = 'Продам %d-%s за %s' . PHP_EOL . '<br>';

        return sprintf(
            $formatted_txt,
            $this->rooms,
            $this->getHouseType(),
            $this->getFormattedPrice(),
        );
    }
}

class RentAdvert extends Advert
{
    public function __construct(int $rooms = NULL, float $price = NULL, string $type = NULL, string $period = NULL)
    {
        $this->rooms = $rooms;
        $this->price = $price;
        $this->type = $type;
        $this->period = $period;
    }

    public function getTitle(): string
    {
        $formatted_txt = 'Сдам %d-%s за %s %s' . PHP_EOL . '<br>';

        return sprintf(
            $formatted_txt,
            $this->rooms,
            $this->getHouseType(),
            $this->getFormattedPrice(),
            $this->getRentPeriod(),
        );
    }
}

$adverts = [
    ['rooms' => 5, 'category' => 'sale', 'price' => 55000000, 'type' => 'dom'],
    ['rooms' => 2, 'category' => 'sale', 'price' => 21500000, 'type' => 'kvartira'],
    ['rooms' => 2, 'category' => 'rent', 'price' => 200000, 'type' => 'kvartira', 'period' => 'month'],
    ['rooms' => 1],
    ['rooms' => 1, 'category' => 'rent', 'price' => 15000, 'type' => 'kvartira', 'period' => 'day'],
    ['category' => 1],
    ['rooms' => 1, 'category' => 'rent', 'price' => 15000, 'type' => 'UNK', 'period' => 'day'],
];

$wrongAdvert = [];

foreach ($adverts as $key => $advert) {
    if (!isset($advert['category']) || !isset($advert['rooms']) || !isset($advert['price']) || !isset($advert['type'])) {
        array_push($wrongAdvert, $key);
    } elseif ($advert['category'] == 'sale') {
        $advert = new SaleAdvert($advert['rooms'], $advert['price'], $advert['type']);
        echo $advert->getTitle();
    } elseif ($advert['category'] == 'rent') {
        $advert = new RentAdvert($advert['rooms'], $advert['price'], $advert['type'], $advert['period']);
        echo $advert->getTitle();
    }
}

echo '>>> неправильно заданы объявления:' . PHP_EOL . '<br>';
foreach ($wrongAdvert as $errors) {
    echo $errors . PHP_EOL . '<br>';
}

// EOF
