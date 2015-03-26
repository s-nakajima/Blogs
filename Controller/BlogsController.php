<?php
App::uses('BlogsAppController', 'Blogs.Controller');
/**
 * Blogs Controller
 *
 *
* @author   Jun Nishikawa <topaz2@m0n0m0n0.com>
* @link     http://www.netcommons.org NetCommons Project
* @license  http://www.netcommons.org/license.txt NetCommons License
 *
 * @property BlogEntry $BlogEntry
 */
class BlogsController extends BlogsAppController {

    public function index(){
		$frameId = $this->viewVars['frameId'];
		$html = $this->requestAction(array('controller' => 'blog_entries', 'action' => 'index', $frameId), array('return'));

		$this->set('html', $html);
		return;
    }

}