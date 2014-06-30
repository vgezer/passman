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

use \OCA\Passman\BusinessLayer\ItemBusinessLayer;
use \OCP\IRequest;
use \OCP\AppFramework\Http\TemplateResponse;
use \OCP\AppFramework\Controller;
use \OCP\AppFramework\Http;
use \OCP\AppFramework\Http\JSONResponse;

class ItemApiController extends Controller {
    private $userId;
	private $ItemBusinessLayer;
	public $request; 
	
    public function __construct($appName, IRequest $request,  ItemBusinessLayer $ItemBusinessLayer,$userId){
        parent::__construct($appName, $request);
        $this->userId = $userId;
		$this->ItemBusinessLayer = $ItemBusinessLayer;
		$this->request = $request;
    }


    /**
     * CAUTION: the @Stuff turn off security checks, for this page no admin is
     *          required and no CSRF check. If you don't know what CSRF is, read
     *          it up in the docs or you might create a security hole. This is
     *          basically the only required method to add this exemption, don't
     *          add it to any other method if you don't exactly know what it does
     * @NoAdminRequired
     */
	public function index($folderId) {
		$result['items'] = $this->ItemBusinessLayer->listItems($folderId,$this->userId); 
		return new JSONResponse($result);
	}
     
     public function get($itemId) {
     	$itemId = (int) $itemId;
		$result['item'] = $this->ItemBusinessLayer->get($itemId,$this->userId); 
		
		return new JSONResponse($result);
	}

	/**
	 * Create item function
	 * @param Folder ID 
	 *
	 * @NoAdminRequired
	 */
	public function create() {
		$userId = $this->userId;
		$label = $this->params('label');
		$folderId = $this->params('folderid');
		$desc = $this->params('desc');
		$account = $this->params('account');
		$pass = $this->params('pw1');
		$email = $this->params('email');
		$url = $this->params('url');
		$result['itemid'] = $this->ItemBusinessLayer->create($folderId,$userId,$label,$desc,$pass,$account,$email,$url); 
		
		return new JSONResponse($result); 
	}
	/**
	 * Update to create and edit items 
	 * @param Folder ID 
	 *
	 * @NoAdminRequired
	 */
	public function update($itemId) {
		$id = (int) $itemId;
		$userId = $this->userId;
		$label = $this->params('label');
		$folderId = $this->params('folderid');
		$desc = $this->params('desc');
		$account = $this->params('account');
		$pass = $this->params('pw1');
		$email = $this->params('email');
		$url = $this->params('url');

		$result['success'] = $this->ItemBusinessLayer->update($id,$folderId,$userId,$label,$desc,$pass,$account,$email,$url);

		return new JSONResponse($result); 
	}

	/**
	 * @NoAdminRequired
	 */
	public function search($itemName) {
		$deleted['deleted']	=$this->ItemBusinessLayer->search($itemName,$this->userId);
		return new JSONResponse($deleted['deleted']); 
	}
	
	/**
	 * @NoAdminRequired
	 */
	public function delete($itemId) {
		$deleted['deleted']	=$this->ItemBusinessLayer->delete($itemId,$this->userId);
		return new JSONResponse($deleted['deleted']); 
	}
	/**
	 * addFile
	 * File has to be encrypted with the users key.
	 * postData = {
						item_id : itemId,
						filename : file.name,
						type : file.type,
						mimetype : mimeType,
						size : file.size,
						content : encryptedFile
					}
	 */
	public function addfile($itemId){
		//echo $itemId;
		$file = array();
		$file['item_id'] = $this->params('item_id');
		$file['filename'] = $this->params('filename');
		$file['type'] = $this->params('type');
		$file['mimetype'] = $this->params('mimetype');
		$file['size'] = $this->params('size');
		$file['content'] = $this->params('content');
		$file['user_id'] = $this->userId;
		//print_r($this->request);
		return new JSONResponse($this->ItemBusinessLayer->addFileToItem($file));  
	}
	
	/**
	 * GetFile get a single file and his content
	 */
	 public function getfile($fileId){
		return new JSONResponse($this->ItemBusinessLayer->getFile($fileId,$this->userId));  
	}
	 
	public function deletefile($fileId){
		return new JSONResponse($this->ItemBusinessLayer->deleteFile($fileId,$this->userId));  
	}
	
}
