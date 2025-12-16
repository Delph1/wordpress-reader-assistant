<?php
/**
 * Main WordPress Reader Assistant class
 */

class WordPress_Reader_Assistant {

	public function __construct() {
		add_shortcode( 'wordpress-reader-assistant', array( $this, 'render_shortcode' ) );
	}

	/**
	 * Render the reader assistant shortcode
	 *
	 * @return string HTML for the reader assistant
	 */
	public function render_shortcode() {
		// Enqueue scripts and styles only when shortcode is used
		wp_enqueue_style(
			'wra-plugin-styles',
			WRA_PLUGIN_URL . 'assets/css/plugin.css',
			array(),
			WRA_PLUGIN_VERSION
		);

		wp_enqueue_script(
			'wra-plugin-script',
			WRA_PLUGIN_URL . 'assets/js/plugin.js',
			array(),
			WRA_PLUGIN_VERSION,
			true
		);

		// Extract headings from post content
		$headings = $this->extract_headings( get_the_content() );

		// Generate table of contents HTML
		$toc_html = $this->generate_table_of_contents( $headings );

		// Return the floating reader assistant HTML
		return $this->render_reader_assistant( $toc_html );
	}

	/**
	 * Extract h3 and h4 headings from content
	 *
	 * @param string $content The post content.
	 * @return array Array of headings with text and level
	 */
	private function extract_headings( $content ) {
		$headings = array();

		// Match h3 and h4 tags with or without id attributes
		if ( preg_match_all( '/<h([34])([^>]*)>(.+?)<\/h[34]>/i', $content, $matches ) ) {
			for ( $i = 0; $i < count( $matches[0] ); $i++ ) {
				$level = intval( $matches[1][ $i ] );
				$attributes = $matches[2][ $i ];
				$text = wp_strip_all_tags( $matches[3][ $i ] );

				// Extract existing id if present, otherwise generate one from text
				$id = '';
				if ( preg_match( '/id=["\']([^"\']+)["\']/', $attributes, $id_match ) ) {
					$id = $id_match[1];
				} else {
					// Generate id from heading text
					$id = sanitize_title_with_dashes( $text );
				}

				$headings[] = array(
					'level' => $level,
					'id'    => $id,
					'text'  => $text,
				);
			}
		}

		return $headings;
	}

	/**
	 * Generate table of contents HTML from headings
	 *
	 * @param array $headings Array of headings.
	 * @return string HTML for the table of contents
	 */
	private function generate_table_of_contents( $headings ) {
		if ( empty( $headings ) ) {
			return '<p class="wra-no-headings">' . __( 'No headings found in this post.', 'wordpress-reader-assistant' ) . '</p>';
		}

		$html = '<ul class="wra-toc-list">';

		foreach ( $headings as $heading ) {
			$indent_class = 'wra-toc-level-' . $heading['level'];
			$html         .= '<li class="wra-toc-item ' . $indent_class . '">';
			$html         .= '<a href="#' . esc_attr( $heading['id'] ) . '" class="wra-toc-link">' . wp_kses_post( $heading['text'] ) . '</a>';
			$html         .= '</li>';
		}

		$html .= '</ul>';

		return $html;
	}

	/**
	 * Render the floating reader assistant HTML
	 *
	 * @param string $toc_html The table of contents HTML.
	 * @return string HTML for the reader assistant
	 */
	private function render_reader_assistant( $toc_html ) {
		ob_start();
		?>
		<div class="wra-container">
			<div class="wra-header">
				<h3 class="wra-title"><?php esc_html_e( 'Innehåll', 'wordpress-reader-assistant' ); ?></h3>
				<button class="wra-toggle-btn" aria-label="<?php esc_attr_e( 'Toggle reader assistant', 'wordpress-reader-assistant' ); ?>">
					<span class="wra-toggle-icon">−</span>
				</button>
			</div>
			<div class="wra-content">
				<div class="wra-search-box">
					<input 
						type="text" 
						class="wra-search-input" 
						placeholder="<?php esc_attr_e( 'Search...', 'wordpress-reader-assistant' ); ?>" 
						aria-label="<?php esc_attr_e( 'Search page content', 'wordpress-reader-assistant' ); ?>"
					>
				</div>
				<div class="wra-toc">
					<?php echo wp_kses_post( $toc_html ); ?>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
}
