<?php
namespace DontBeAlone\module\form;

class Form {
    private $field;
    private $source;
    private $formatted;
    private $result_parse;

    function __construct(array $field) {
        $this->field = $field;
        $this->result_parse = array();
        $this->formatted = array(
            'value' => array(),
            'format' => array()
        );
    }

    public function setSource(array $source) {
        $this->source = $source;

        return $this;
    }

    public function removeFieldNull() {
        $field = $this->field;
        $source = $this->source;

        $this->result_parse = array_reduce(
            array_filter(array_map(static function($item) use ($source) {
                return array_merge(
                    $item,
                    array(
                        'value' => $source[$item['key']] === "" ? null : $source[$item['key']]
                    )
                );
            }, $field), static function($item) {
                return $item['value'] !== null;
            }),
            static function ($current, $response) {
                $current[$response['key']] = array(
                    'value' => $response['value'],
                    'format' => $response['format'],
                );

                return $current;
            },
            array()
        );

        return $this;
    }

    public function removeSpecialChar() {
        foreach($this->result_parse as $key => $value) {
            $this->result_parse[$key]['value'] = Form::secureInput($value['value']);
        }

        return $this;
    }

    public function result(): array {
        return array(
            'plain' => $this->result_parse,
            'value' => Form::getAttribute('value', $this->result_parse),
            'format' => Form::getAttribute('format', $this->result_parse)
        );
    }

    static function getAttribute(string $key, array $list) {
        return array_values(
            array_map(function($item) use ($key) {
                return $item[$key];
            }, $list)
        );
    }

    static function secureInput(string $data): string {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);

        return $data;
    }
}
