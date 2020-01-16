<?php

class Validate {
    private $_errors, $_db = null;

    public function __construct()
    {
        $this->_db = DB::getInstance();
    }

    public function check($source, $items = []) {
        $this->_errors = [];
        foreach ($items as $item => $rules) {
            $item = Input::sanitize($item);
            $display = $rules['display'];
            foreach ($rules as $rule => $rule_value) {
                $value = Input::sanitize(trim($source[$item]));
                if ($rule === 'required' && empty($value)) {
                    $this->addError("{$display} is required", $item);
                } elseif (!empty($value)) {
                    switch ($rule) {
                        case 'min':
                            if (strlen($value) < $rule_value) {
                                $this->addError("{$display} must be a minimum of {$rule_value} characters", $item);
                            }
                            break;
                        case 'max':
                            if (strlen($value) > $rule_value) {
                                $this->addError("{$display} must be a maximum of {$rule_value} characters", $item);
                            }
                            break;
                        case 'matches':
                            if ($value != $source[$rule_value]) {
                                $matchDisplay = $items[$rule_value]['display'];
                                $this->addError("{$matchDisplay} and {$display} must match.", $item);
                            }
                            break;
                        case 'unique':
                            $check = $this->_db->query("SELECT {$item} FROM {$rule_value} WHERE {$item} = ?", [$value]);
                            if ($check->count()) {
                                $this->addError("{$display} already exists. Please choose another {$display}.", $item);
                            }
                            break;
                        case 'numeric':
                            if (!is_numeric($value)) {
                                $this->addError("{$display} has to be a number.", $item);
                            }
                            break;
                        case 'string':
                            if (!is_string($value)) {
                                $this->addError("{$display} has to be a string.", $item);
                            }
                            break;
                        case 'email':
                            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                                $this->addError("{$display} has to be an email.", $item);
                            }
                            break;
                    }
                }
            }
        }
    }

    public function addError($error, $item) {
        $this->_errors[$item] = $error;
    }

    public function passed() {
        return empty($this->_errors);
    }

    public function errors() {
        return $this->_errors;
    }

    public function displayErrors($items) {
        $out = [];
        foreach ($items as $item => $value) {
            if (isset($this->_errors[$item])) {
                $out[$item] = '<ul class="error-list"><li>'.$this->_errors[$item].'</li></ul>';
            } else {
                $out[$item] = '';
            }
        }
        return $out;
    }
}