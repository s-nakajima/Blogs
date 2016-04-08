<div class="blogs_entry_meta">
	<div>

		<?php echo __d(
			'blogs',
			'posted : %s',
			$this->Date->dateFormat($blogEntry['BlogEntry']['publish_start'])
		); ?>&nbsp;

		<?php echo $this->DisplayUser->handleLink($blogEntry, array('avatar' => true)); ?>&nbsp;
		<?php echo __d('blogs', 'Category') ?>:<?php echo $this->Html->link(
			$blogEntry['Category']['name'],
			$this->NetCommonsHtml->url(
				array(
					'controller' => 'blog_entries',
					'action' => 'index',
					'frame_id' => Current::read('Frame.id'),
					'category_id' => $blogEntry['BlogEntry']['category_id']
				)
			)
		); ?>
	</div>
</div>
