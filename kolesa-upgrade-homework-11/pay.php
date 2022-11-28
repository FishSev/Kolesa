<?php

declare(strict_types=1);

use App\ReceiptGenerator;
use App\ReceiptsRepository;
use App\Validation\PaymentRequestValidator;

require_once "vendor/autoload.php";

const RECEIPTS_DIR = __DIR__ . '/receipts';

// сейчас у нас только 1 товар - билет на vogue show
const DEFAULT_SERVICE = 'Vogue Night Show';
const DEFAULT_AMOUNT = 5000;

try {
    $validator = new PaymentRequestValidator();

    $errors = $validator->validate($_POST);

    // если валидатор вернул ошибки - склеиваем их в одну строку и выбрасываем исключение
    if (!empty($errors)) {
        $errorsString = '';
        
        foreach ($errors as $error) {
            $errorsString .= $error . "<br>" . PHP_EOL;
        }

        throw new \Exception('Невалидный запрос:' . "<br>" . PHP_EOL . $errorsString);
    }

    // производим оплату: списываем деньги, помечаем что услуга оплачена и т.д.
    // pay();

    $receiptGenerator = new ReceiptGenerator();

    $receipt = $receiptGenerator->make(
        $_POST['name'],
        DEFAULT_SERVICE,
        DEFAULT_AMOUNT
    );

    $receiptsRepository = new ReceiptsRepository(RECEIPTS_DIR);

    $receiptsRepository->save(time().'.txt', $receipt);
} catch (\Throwable $e) {
    die($e->getMessage());
}

header('Location: http://localhost:8080');
die('successful payment');