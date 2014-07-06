<?php
/**
 * ownCloud - passman
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Sander Brand <brantje@gmail.com>
 * @copyright Sander Brand 2014
 */

namespace OCA\Passman\Controller;

use \OCP\IRequest;
use \OCP\AppFramework\Http\TemplateResponse;
use \OCP\AppFramework\Controller;
use \OCP\CONFIG;
class PageController extends Controller {

    private $userId;

    public function __construct($appName, IRequest $request, $userId){
        parent::__construct($appName, $request);
        $this->userId = $userId;
    }


    /**
     * CAUTION: the @Stuff turn off security checks, for this page no admin is
     *          required and no CSRF check. If you don't know what CSRF is, read
     *          it up in the docs or you might create a security hole. This is
     *          basically the only required method to add this exemption, don't
     *          add it to any other method if you don't exactly know what it does
     *
     * @NoAdminRequired
     * @NoCSRFRequired
     */
    public function index() {
    	$conf= 1;//\OCP\CONFIG::getUserValue( \OCP\User::getUser() , 'firstpassmanrun' , 'show' , 1 );
		if($conf==1){
			\OCP\Util::addscript('passman', 'firstrun');
		}
        $params = array('user' => $this->userId,'firstpassmanrun',$conf);
        return new TemplateResponse('passman', 'main', $params);  // templates/main.php
    }
	/**
	 * @NoAdminRequired
	 */
	public function disablefirstrun() {
        \OCP\Config::setUserValue( \OCP\User::getUser(), 'firstpassmanrun', 'show', 0 );
		echo "Succes!";
    }
}