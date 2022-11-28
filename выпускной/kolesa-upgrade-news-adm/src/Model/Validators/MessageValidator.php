<?php

namespace App\Model\Validators;

class MessageValidator implements ValidatorInterface
{
    private const NOT_EMPTY_FIELDS = ['title', 'body'];
    private const MIN_TITLE_LENGTH = 2;
    private const MAX_TITLE_LENGTH = 255;
    private const MIN_MESSAGE_LENGTH = 2;
    private const MAX_MESSAGE_LENGTH = 1000;

    public function validate(array $data): array
    {
        $errors = $this->validateNotEmpty($data);

        if (!empty($errors)) {
            return $errors;
        }

        return array_merge(
            $this->validateLengthTitle($data),
            $this->validateLengthMessage($data)
        );
    }

    private function validateNotEmpty(array $data): array
    {
        $errors = [];

        foreach (self::NOT_EMPTY_FIELDS as $fieldName) {
            $value = $data[$fieldName] ?? null;

            if (empty($value)) {
                $errors[$fieldName] = 'Поле "' . $fieldName . '" не должно быть пустым';
            }
        }

        return $errors;
    }

    private function validateLengthTitle(array $data): array
    {
        $titleLength = mb_strlen($data['title']);

        if ($titleLength < self::MIN_TITLE_LENGTH) {
            return [
                'title' => 'Заголовок не может быть меньше ' . self::MIN_TITLE_LENGTH . ' символов'
            ];
        }

        if ($titleLength > self::MAX_TITLE_LENGTH) {
            return [
                'title' => 'Заголовок не может быть больше ' . self::MAX_TITLE_LENGTH . ' символов'
            ];
        }

        return [];
    }

    private function validateLengthMessage(array $data): array
    {
        $messLength = mb_strlen($data['body']);

        if ($messLength < self::MIN_MESSAGE_LENGTH) {
            return [
                'body' => 'Тело не может быть меньше ' . self::MIN_MESSAGE_LENGTH . ' символов'
            ];
        }

        if ($messLength > self::MAX_MESSAGE_LENGTH) {
            return [
                'body' => 'Тело не может быть больше ' . self::MAX_MESSAGE_LENGTH . ' символов'
            ];
        }

        return [];
    }
}