<?php


/**
 * @group edd_misc
 */
class Test_Template extends EDD_UnitTestCase {

	public function setUp() {
		parent::setUp();
	}

	public function test_pagination_onepage() {
		ob_start();
		$args = array(
			'type'    => 'test',
			'total'   => 1,
			'current' => 1,
		);

		edd_pagination( $args );
		$output = ob_get_clean();
		$this->assertEmpty( $output );
	}

	public function test_pagination_twopages_page1() {
		ob_start();
		$args = array(
			'type'    => 'test',
			'total'   => 2,
			'current' => 1,
		);

		edd_pagination( $args );
		$output = ob_get_clean();
		// Verify it has the current page as 1
		$this->assertContains( " class='page-numbers current'>1</span>", $output );

		// Verify that it contains page 2
		$this->assertContains( "<a class='page-numbers' href='http://example.org/?paged=2'>2</a>", $output );

		// Verify that the 'next' button appears.
		$this->assertContains( '<a class="next page-numbers" href="http://example.org/?paged=2">Next &raquo;</a>', $output );

		// Verify that the 'previous' button does not appear.
		$this->assertNotContains( 'class="prev ', $output );
	}

	public function test_pagination_threepages_page2() {
		ob_start();
		$args = array(
			'type'    => 'test',
			'total'   => 3,
			'current' => 2,
		);

		edd_pagination( $args );
		$output = ob_get_clean();
		// Verify it has the current page as 2
		$this->assertContains( " class='page-numbers current'>2</span>", $output );

		// Verify that it contains pages 1 and 3
		$this->assertContains( "<a class='page-numbers' href='http://example.org/?paged=1'>1</a>", $output );
		$this->assertContains( "<a class='page-numbers' href='http://example.org/?paged=3'>3</a>", $output );

		// Verify that the 'next' and 'previous' buttons appear.
		$this->assertContains( '<a class="next page-numbers" href="http://example.org/?paged=3">Next &raquo;</a>', $output );
		$this->assertContains( '<a class="prev page-numbers" href="http://example.org/?paged=1">&laquo; Previous</a>', $output );
	}

	public function test_pagination_threepages_page3() {
		ob_start();
		$args = array(
			'type'    => 'test',
			'total'   => 3,
			'current' => 3,
		);

		edd_pagination( $args );
		$output = ob_get_clean();
		// Verify it has the current page as 2
		$this->assertContains( " class='page-numbers current'>3</span>", $output );

		// Verify that it contains pages 1 and 3
		$this->assertContains( "<a class='page-numbers' href='http://example.org/?paged=1'>1</a>", $output );
		$this->assertContains( "<a class='page-numbers' href='http://example.org/?paged=2'>2</a>", $output );

		// Verify that the 'previous' button appears for the correct page.
		$this->assertContains( '<a class="prev page-numbers" href="http://example.org/?paged=2">&laquo; Previous</a>', $output );

		// Verify that the 'next' button does not appear.
		$this->assertNotContains( 'class="next ', $output );
	}

	public function test_pagination_has_elipses() {
		ob_start();
		$args = array(
			'type'    => 'test',
			'total'   => 100,
			'current' => 5,
		);

		edd_pagination( $args );
		$output = ob_get_clean();
		// Verify it has the current page as 2
		$this->assertContains( "class='page-numbers current'>5</span>", $output );

		// Verify that it contains page 1
		$this->assertContains( "<a class='page-numbers' href='http://example.org/?paged=1'>1</a>", $output );

		// Verify that it does not contain page 2 (replaced by elipses)
		$this->assertNotContains( "<a class='page-numbers' href='http://example.org/?paged=2'>2</a>", $output );

		// Verify that two elipses items are present.
		$this->assertSame( 2, substr_count( $output, '<span class="page-numbers dots">&hellip;</span>' ) );

		// Verify the last item shows.
		$this->assertContains( "<a class='page-numbers' href='http://example.org/?paged=100'>100</a>", $output );

		// Verify that the 'previous' button appears.
		$this->assertContains( '<a class="prev page-numbers" href="http://example.org/?paged=4">&laquo; Previous</a>', $output );
		$this->assertContains( '<a class="next page-numbers" href="http://example.org/?paged=6">Next &raquo;</a>', $output );
	}
}
