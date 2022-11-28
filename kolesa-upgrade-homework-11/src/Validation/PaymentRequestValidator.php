<?php

declare(strict_types=1);

namespace App\Validation;

class PaymentRequestValidator
{
    private $errors = [];

    public function validate(array $request): array
    {
        $this->validateName($request);
        $this->validateCardNumber($request);
        $this->validateExpiration($request);
        $this->validateCVV($request);

        return $this->errors;
    }

    private function validateName(array $data): array
    {
        $val = trim($data['name']);
        $subName = explode(" ", $val);

        if (empty($val)) {
            $this->addError('name', '- строка "Name on card" пустая');
        } elseif (count($subName) != 2) {
            $this->addError('name', '- в строке "Name on card" не два слова (имя, фамилия) разделённые пробелом');
        } else {
            $firstNameLen = strlen($subName[0]);
            $lastNameLen = strlen($subName[1]);

            if ($firstNameLen < 2 || $lastNameLen < 2) {
                $this->addError('name', '- длина имени и/или фамилии меньше двух символов');
            }
        }
        return $this->errors;
    }

    private function validateCardNumber(array $data): array
    {
        $val = trim($data['cardNumber']);
        if (empty($val)) {
            $this->addError('cardNumber', '- не введён номер карты');
        } elseif (!is_numeric($val) || strlen($val) != 12) {
            $this->addError('cardNumber', '- номер карты должен состоять не из 12 цифр');
        }
        return $this->errors;
    }

    private function validateExpiration(array $data): array
    {
        $val = trim($data['expiration']);

        if (empty($val)) {
            $this->addError('expiration', '- дата окончания пустая');
        } elseif (!preg_match('/^[0-9][0-9]\/[0-9][0-9]$/', $val)) {
            $this->addError('expiration', '- неправильный формат даты. Должен быть мм/гг');
        } else {
            $subDate = explode("/", $val);
            $month = (int) $subDate[0];
            $year = (int) $subDate[1];

            if ($month == 0 || $month > 12 || $year < 22 || $year > 25) {
                $this->addError('expiration', '- месяц не в интервале [01,12] и/или год не в [22,25]');
            }
        }
        return $this->errors;
    }

    private function validateCVV(array $data): array
    {
        $val = trim($data['cvv']);

        if (empty($val)) {
            $this->addError('cvv', '- CVV пустой');
        } elseif (!is_numeric($val) || strlen($val) != 3) {
            $this->addError('cvv', '- CVV не трёхзначное число');
        }
        return $this->errors;
    }

    private function addError($key, $val)
    {
        $this->errors[$key] = $val;
    }
}
