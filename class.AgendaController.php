<?php

namespace lsm\modules\agenda;

use lsm\controllers\BaseController;
use lsm\libs\View;
use lsm\modules\agenda\AgendaModel;
use lsm\modules\agenda\AgendaMapper;
use lsm\libs\Pagination;
use lsm\libs\H;
use lsm\libs\Request;
use lsm\libs\Validator;
use Exception;
use PDOException;
use lsm\exceptions\PermissionDeniedException;

class AgendaController extends BaseController {
    /**
     * The Model object.
     *
     * @var AgendaModel
     */
    protected $_model;

    /**
     * The Mapper object, used to deal with database operations.
     *
     * @var AgendaMapper
     */
    protected $_mapper;

    public function __construct() {
        parent::__construct( 'Agenda', '\lsm\modules\agenda\\' );

        $this->_mapper = new AgendaMapper();
    }

    public function index() {
        // Load result of edit_contents permission test
        $this->_view->editContents = $this->_user->hasPrivilege( 'edit_contents' );

        // instantiate Pagination object and
        // pass it to the Mapper
        $pagination = new Pagination();
        $this->_mapper->pagination = $pagination;

        // load category-objects array for use in the view
        $this->_view->pagination = $pagination;
        $this->_view->objectList = $this->_mapper->index();

        $this->_view->addExtraScript( 'js/list.js' );
        $this->_view->addExtraScript( 'modules/agenda/js/agenda.js' );

        $this->prepareFlashMsg( $this->_view );

        $this->_view->render( 'index', 'pagination', 'modules/agenda' );
    }

    public function create() {
        if ( ! $this->_user->hasPrivilege( 'edit_contents' ) ) {
            throw new PermissionDeniedException();
        }

        $agenda = new AgendaModel();

        // Check if there is input data (we are redirecting the user back to the form
        // with an error message after he tried to submit it), in which case we will
        // give back the input data to the form
        $inputData = H::flashInput();
        if ( $inputData ) {
            $agenda->date = AgendaModel::formatDate( $inputData[ 'date' ], 'br' );
            $agenda->time = $inputData[ 'time' ];
            $agenda->description = $inputData[ 'description' ];
            $agenda->venue = $inputData[ 'venue' ];
            $agenda->city = $inputData[ 'city' ];
        }

        $this->_view->addExtraLink( 'modules/agenda/jquery-ui/jquery-ui.min.css' );
        $this->_view->addExtraLink( 'modules/agenda/css/agenda.css' );

        $this->_view->addExtraScript( 'modules/agenda/jquery-ui/jquery-ui.min.js' );
        $this->_view->addExtraScript( 'modules/agenda/js/datepicker-pt-BR.js' );
        $this->_view->addExtraScript( 'modules/agenda/js/agenda-form.js' );

        $this->_view->object = $agenda;
        $this->prepareFlashMsg( $this->_view );
        $this->_view->render( 'form', null, 'modules/agenda' );
    }

    public function insert() {
        if ( ! $this->_user->hasPrivilege( 'edit_contents' ) ) {
            throw new PermissionDeniedException();
        }

        $request = Request::getInstance();

        $validator = new Validator();
        if ( ! $validator->check( $_POST, $this->_model->rules ) ) {
            // Flash error messages
            H::flash( 'err-msg', $validator->getErrorsJson() );

            // Flash input data (data the user had typed in) so that we can
            // put it back in the form fields
            H::flashInput( $request->getInput() );

            header( 'Location: ' . $this->_url->create() );
        } else {
            $this->_model->date = AgendaModel::formatDate( $request->getInput( 'date' ), 'sys' );
            $this->_model->time = $request->getInput( 'time' );
            $this->_model->description = $request->getInput( 'description' );
            $this->_model->venue = $request->getInput( 'venue' );
            $this->_model->city = $request->getInput( 'city' );

            $this->_mapper->save( $this->_model );
            H::flash( 'success-msg', 'Item de Agenda criado com sucesso!' );
            header( 'Location: ' . $this->_url->index() );
        }
    }

    public function edit( $id ) {
        if ( ! $this->_user->hasPrivilege( 'edit_contents' ) ) {
            throw new PermissionDeniedException();
        }

        $this->_view->object = $this->_mapper->find( $id );
        $this->_view->object->date = AgendaModel::formatDate( $this->_view->object->date );
        $this->_view->object->time = AgendaModel::formatTime( $this->_view->object->time );

        if ( ! ( $this->_view->object instanceof AgendaModel ) ) {
            throw new Exception( 'Erro: Item de agenda não encontrado!' );
        }

        // Try to get input data from session (data that the user had typed
        // in the form before). There will be input data if the validation
        // failed, and we want to redirect the user to the form with an
        // error message, putting back the data she had typed
        $inputData = H::flashInput();
        if ( $inputData ) {
            $agenda = new AgendaModel();
            $agenda->date = $inputData[ 'date' ];
            $agenda->time = $inputData[ 'time' ];
            $agenda->description = $inputData[ 'description' ];
            $agenda->venue = $inputData[ 'venue' ];
            $agenda->city = $inputData[ 'city' ];

            $this->_view->object = $agenda;
        }

        $this->_view->addExtraLink( 'modules/agenda/jquery-ui/jquery-ui.min.css' );
        $this->_view->addExtraLink( 'modules/agenda/css/agenda.css' );

        $this->_view->addExtraScript( 'modules/agenda/jquery-ui/jquery-ui.min.js' );
        $this->_view->addExtraScript( 'modules/agenda/js/datepicker-pt-BR.js' );
        $this->_view->addExtraScript( 'modules/agenda/js/agenda-form.js' );

        $this->prepareFlashMsg( $this->_view );

        $this->_view->render( 'form', null, 'modules/agenda' );
    }

    public function update() {
        if ( ! $this->_user->hasPrivilege( 'edit_contents' ) ) {
            throw new PermissionDeniedException();
        }

        $request = Request::getInstance();

        // Get id from $_POST
        $id = $request->getInput( 'id' );

        $validator = new Validator();
        if ( ! $validator->check( $_POST, $this->_model->rules ) ) {
            // Flash error message
            H::flash( 'err-msg', $validator->getErrorsJson() );

            // Flash input data (the data the user had typed int he form)
            H::flashInput( $request->getInput() );

            header( 'Location: ' . $this->_url->edit( $id ) );
        } else {
            $this->_model->id = $id;
            $this->_model->date = AgendaModel::formatDate( $request->getInput( 'date' ), 'sys' );
            $this->_model->time = $request->getInput( 'time' );
            $this->_model->description = $request->getInput( 'description' );
            $this->_model->venue = $request->getInput( 'venue' );
            $this->_model->city = $request->getInput( 'city' );

            $this->_mapper->save( $this->_model );
            H::flash( 'success-msg', 'Item de agenda atualizado com sucesso!' );
            header( 'Location: ' . $this->_url->index() );
        }
    }

    public function delete( $id ) {
        if ( ! $this->_user->hasPrivilege( 'edit_contents' ) ) {
            throw new PermissionDeniedException();
        }

        // Give the view the AgendaModel object
        $this->_view->object = $this->_mapper->find( $id );
        if ( ! ( $this->_view->object instanceof AgendaModel ) ) {
            throw new Exception( 'Erro: Item de Agenda não encontrado!' );
        }

        $this->_view->render( 'delete', null, 'modules/agenda' );
    }

    public function destroy() {
        if ( ! $this->_user->hasPrivilege( 'edit_contents' ) ) {
            throw new Exception();
        }

        if ( ! H::checkToken( Request::getInstance()->getInput( 'token' ) ) ) {
            H::flash( 'err-msg', 'Nao foi possível processar a requisição!' );
            header( 'Location: ' . $this->_url->make( 'agenda/index' ) );
        }

        $id = Request::getInstance()->getInput( 'id' );

        $agenda = new AgendaModel();
        $agenda->id = $id;

        try {
            $this->_mapper->destroy( $agenda );

            H::flash( 'success-msg', 'Item de agenda removido com sucesso!' );
            header( 'Location: ' . $this->_url->index() );
        } catch ( PDOException $e ) {
            H::flash( 'err-msg', 'Não foi possível excluir o Item de agenda!' );
            header( 'Location: ' . $this->_url->index() );
        }
    }

    public function deleteAjax() {
        // Initialize error message and the $isOk flag to be sent back to the page
        $errorMsg = '';
        $isOk = false;

        // Get token that came with the request
        $token = filter_input( INPUT_POST, 'token', FILTER_SANITIZE_FULL_SPECIAL_CHARS );

        // Get array os Post IDs
        $items = $_POST[ 'items' ];

        try {
            // Validate token
            if ( ! H::checkToken( $token ) ) {
                // If token fails to validate, let's send back an error message
                $errorMsg = 'Não foi possível processar a requisição.';
            } // Validate permission to edit contents
            else if ( ! $this->_user->hasPrivilege( 'edit_contents' ) ) {
                $errorMsg = 'Permissão negada.';
            } // No problems occurred: we can carry through with the request
            else {
                if ( $this->_mapper->deleteAjax( $items ) ) {
                    $isOk = true;

                    // If everything worked out, we are going to redirect the user
                    // back to the first page on the view. Therefore, we have to
                    // add a success message to the session
                    H::flash( 'success-msg', 'Itens de agenda removidos com sucesso!' );
                } else {
                    $errorMsg = 'Não foi possível excluir os itens de agenda. Contate o suporte.';
                }
            }

            // At the end of the process, give back a new token
            // to the page, as well as the isOk flag and an eventual message.
            // We'll also send back the ids that were changed, so that we can
            // toggle the status checkboxes in the table.
            echo json_encode(
                array(
                    'isOk' => $isOk,
                    'token' => H::generateToken(),
                    'error' => $errorMsg,
                    'success' => 'Itens de agenda excluídos com sucesso',
                    'items' => $items
                )
            );
        } catch ( Exception $e ) {
            // If any exceptions were thrown in the process, send an error message
            if ( DEBUG )
                echo json_encode( array( 'isOk' => false, 'error' => $e->getMessage() ) );
            else
                echo json_encode( array( 'isOk' => false, 'error' => $errorMsg ) );
        }
    }
}
