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

        $this->prepareFlashMsg( $this->_view );

        $this->_view->render( 'index', 'pagination', 'modules/agenda' );
    }
}
