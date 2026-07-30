<?php

class Validator
{
    private $errors = [];

    public function validate($data, $rules)
    {
        foreach ($rules as $field => $ruleString) {

            $value = $data[$field] ?? '';

            $ruleArray = explode('|', $ruleString);

            foreach ($ruleArray as $rule) {

                $parameter = null;

                if (strpos($rule, ':') !== false) {

                    list($rule, $parameter) = explode(':', $rule);

                }

                $method = "validate" . ucfirst($rule);

                if (method_exists($this, $method)) {

                    $this->$method($field, $value, $parameter);

                }

            }

        }
    }

    private function validateRequired($field, $value)
    {
        if (trim($value) === '') {

            $this->errors[$field][] = ucfirst($field) . " is required.";

        }
    }

    private function validateEmail($field, $value)
    {
        if ($value !== '' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {

            $this->errors[$field][] = ucfirst($field) . " must be a valid email.";

        }
    }

    private function validateMin($field, $value, $length)
    {
        if (strlen($value) < $length) {

            $this->errors[$field][] =
                ucfirst($field) . " must be at least {$length} characters.";

        }
    }

    private function validateMax($field, $value, $length)
    {
        if (strlen($value) > $length) {

            $this->errors[$field][] =
                ucfirst($field) . " must not exceed {$length} characters.";

        }
    }

    private function validateNumeric($field, $value)
    {
        if (!is_numeric($value)) {

            $this->errors[$field][] =
                ucfirst($field) . " must be numeric.";

        }
    }

    public function passes()
    {
        return empty($this->errors);
    }

    public function errors()
    {
        return $this->errors;
    }

    public function first()
    {
        foreach ($this->errors as $fieldErrors) {

            return $fieldErrors[0];

        }

        return null;
    }

    public function fails()
    {
       return !$this->passes();
    }


}