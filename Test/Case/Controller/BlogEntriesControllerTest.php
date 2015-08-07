<?php
/**
 * BlogEntriesController Test Case
 *
 * @author   Jun Nishikawa <topaz2@m0n0m0n0.com>
 * @link     http://www.netcommons.org NetCommons Project
 * @license  http://www.netcommons.org/license.txt NetCommons License
 */

App::uses('BlogEntriesController', 'Blogs.Controller');
App::uses('BlogsAppControllerTestBase', 'Blogs.Test/Case/Controller');

/**
 * Summary for BlogEntriesController Test Case
 */
class BlogEntriesControllerTest extends BlogsAppControllerTestBase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		Configure::write('Config.language', 'ja');
		$this->blogEntriesMock = $this->generate(
			'Blogs.BlogEntries',
			[
				'components' => [
					'Auth' => ['user'],
					'Session',
					'Security',
				]
			]
		);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		Configure::write('Config.language', null);
		CakeSession::write('Auth.User', null);
		parent::tearDown();
	}

/**
 * testIndex
 *
 * @return void
 */
	public function testIndex() {
		$this->testAction(
			'/blogs/blog_entries/index/1',
			array(
				'method' => 'get',
				//'return' => 'view',
			)
		);
		$this->assertInternalType('array', $this->vars['blogEntries']);
	}

/**
 * ブログ名が一覧に表示されるか
 *
 * @return void
 */
	public function testIndexTitle() {
		$return = $this->testAction(
			'/blogs/blog_entries/index/1',
			array(
				'method' => 'get',
				'return' => 'view',
			)
		);
		$this->assertRegExp('/<h1.*>ブログ名<\/h1>/', $return);
	}

/**
 * testTag
 *
 * @return void
 */
	public function testTag() {
		$this->testAction(
			'/blogs/blog_entries/tag/1/id:1',
			array(
				'method' => 'get',

				//'return' => 'view',
			)
		);
		$this->assertInternalType('array', $this->vars['blogEntries']);
	}

/**
 * testYearMonth
 *
 * @return void
 */
	public function testYearMonth() {
		$this->testAction(
			'/blogs/blog_entries/year_month/1/year_month:2014-02',
			array(
				'method' => 'get',
				//'return' => 'view',
			)
		);
		$this->assertInternalType('array', $this->vars['blogEntries']);
	}

/**
 * testView
 *
 * @return void
 */
	public function testView() {
		$this->testAction(
			'/blogs/blog_entries/view/1/origin_id:1',
			array(
				'method' => 'get',
				'return' => 'view',
			)
		);
		$this->assertInternalType('array', $this->vars['blogEntry']);
	}

/**
 * test view.編集リンクの表示テスト
 *
 * @param string $role ロール
 * @param bool $viewEditLink 編集リンクが表示されるか
 * @dataProvider editLinkDataProvider
 * @return void
 */
	public function testEditLink($role, $viewEditLink) {
		RolesControllerTest::login($this, $role);
		$view = $this->testAction(
			'/blogs/blog_entries/view/1/origin_id:6',
			array(
				'method' => 'get',
				'return' => 'view',
			)
		);
		if ($viewEditLink) {
			$this->assertTextContains('nc-blog-edit-link', $view);
		} else {
			$this->assertTextNotContains('nc-blog-edit-link', $view);
		}
		AuthGeneralControllerTest::logout($this);
	}

/**
 * testEditLink用dataProvider
 *
 * @return array
 */
	public function editLinkDataProvider() {
		$data = [
			['chief_editor', true],
			['editor', true],
			['general_user', true],
			['visitor', false],
		];
		return $data;
	}

/**
 * フレームがあってブロックがないときのテスト
 *
 * @return void
 */
	public function testNoBlock() {
		$result = $this->testAction(
			'/blogs/blog_entries/index/201',
			array(
				'method' => 'get',
				'return' => 'view',
			)
		);
		$this->assertEquals('', $result);
	}

/**
 * test view action まだ公開されてない記事はNotFoundException
 *
 * @return void
 */
	public function testViewNotFound() {
		$this->setExpectedException('NotFoundException');
		// origin_id:4はまだ公開されてない
		$this->testAction(
			'/blogs/blog_entries/view/1/origin_id:4',
			array(
				'method' => 'get',
				//'return' => 'view',
			)
		);
	}

/**
 * test view action content comment post fail -> bad request
 *
 * @return void
 */
	public function testViewContentCommentPostFailed() {
		$blogEntriesMock = $this->generate(
			'Blogs.BlogEntries',
			[
				'components' => [
					'Auth' => ['user'],
					'Session',
					'Security',
					'ContentComments.ContentComments' => ['comment']
				],
			]
		);
		$blogEntriesMock->ContentComments->expects($this->once())
			->method('comment')
			->will($this->returnValue(false));

		$this->setExpectedException('BadRequestException');

		$this->testAction(
			'/blogs/blog_entries/view/1/origin_id:1',
			array(
				'method' => 'post',
				//'return' => 'view',
			)
		);
	}

/**
 * カテゴリの記事一覧
 *
 * @return void
 */
	public function testCategory() {
		$return = $this->testAction(
			'/blogs/blog_entries/index/1/category_id:1',
			array(
				'method' => 'get',
				'return' => 'view',
			)
		);
		$this->assertRegExp('/<h1.*>カテゴリ:category_1<\/h1>/', $return);
	}
}
