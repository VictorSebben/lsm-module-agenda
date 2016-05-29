<?php

namespace lsm\modules\agenda;

use lsm\libs\Validator;
use lsm\models\BaseModel;

class AgendaModel extends BaseModel {

    public $id;
    public $date;
    public $time;
    public $description;
    public $venue;
    public $city;

    public function __construct() {
        parent::__construct();

        $this->tableName = 'agenda';

        $this->rules = array(
            'description' => array( 'fieldName' => 'descrição', 'rules' => 'max:300' ),
            'date' => array( 'fieldName' => 'data', 'type' => Validator::DATE ),
            'time' => array( 'fieldName' => 'horário', 'type' => Validator::TIME ),
            'venue' => array( 'fieldName' => 'local', 'rules' => 'max:300' ),
            'city' => array( 'fieldName' => 'cidade', 'rules' => 'max:100' )
        );
    }

    public function getFormattedDate( $format = 'br' ) {
        if ( $format == 'br' ) {
            $date = new \DateTime( $this->date );
            return $date->format( 'd/m/Y' );
        }

        return $this->date;
    }

    public function getFormattedTime( $format = 'br' ) {
        if ( $format == 'br' ) {
            return substr( $this->time, 0, 5 );
        }

        $time = \DateTime::createFromFormat( 'H:i:s', $this->time );
        return $time->format( 'h:i' );
    }
}
