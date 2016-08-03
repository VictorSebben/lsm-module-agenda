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
            'description' => array( 'fieldName' => 'descrição', 'rules' => 'max:300|min:3' ),
            'date' => array( 'fieldName' => 'data', 'type' => Validator::DATE, 'rules' => 'required' ),
            'time' => array( 'fieldName' => 'horário', 'type' => Validator::TIME ),
            'venue' => array( 'fieldName' => 'local', 'rules' => 'max:300' ),
            'city' => array( 'fieldName' => 'cidade', 'rules' => 'max:100' )
        );
    }

    /**
     * Returns the date, formatted for the user (Brazilian format)
     * or the database (American format)
     *
     * @param $date
     * @param string $format
     * @return string
     */
    public static function formatDate( $date, $format = 'br' ) {
        if ( $format == 'br' ) {
            $date = new \DateTime( $date );
            return $date->format( 'd/m/Y' );
        }

        // If format different from 'br', assume American format
        $date = \DateTime::createFromFormat( 'd/m/Y', $date );
        return $date->format( 'Y-m-d' );
    }

    /**
     * Returns the time of the event formatted for the user,
     * removing the seconds
     *
     * @param $time
     * @return string
     */
    public static function formatTime( $time ) {
        return substr( $time, 0, 5 );
    }
}
