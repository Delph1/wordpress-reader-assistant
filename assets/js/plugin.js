/**
 * WordPress Reader Assistant Plugin JavaScript
 */

(function() {
	'use strict';

	document.addEventListener('DOMContentLoaded', function() {
		const container = document.querySelector('.wra-container');
		if (!container) return;

		const toggleBtn = container.querySelector('.wra-toggle-btn');
		const searchInput = container.querySelector('.wra-search-input');
		const tocLinks = container.querySelectorAll('.wra-toc-link');

		// Toggle minimized state
		if (toggleBtn) {
			toggleBtn.addEventListener('click', function() {
				container.classList.toggle('minimized');
				const isMinimized = container.classList.contains('minimized');
				toggleBtn.textContent = isMinimized ? '+' : '−';
			});
		}

		// Smooth scroll to heading on link click
		tocLinks.forEach(link => {
			link.addEventListener('click', function(e) {
				e.preventDefault();
				const targetId = this.getAttribute('href').substring(1);
				const targetElement = document.getElementById(targetId);
				
				if (targetElement) {
					targetElement.scrollIntoView({ behavior: 'smooth' });
				}
			});
		});

		// Search functionality
		if (searchInput) {
			searchInput.addEventListener('input', function() {
				const searchTerm = this.value.toLowerCase().trim();
				
				// Clear previous highlights
				clearHighlights();

				if (searchTerm.length > 0) {
					highlightText(searchTerm);
				}
			});
		}

		/**
		 * Clear all highlights from the page
		 */
		function clearHighlights() {
			const highlights = document.querySelectorAll('.wra-highlight');
			highlights.forEach(highlight => {
				const parent = highlight.parentNode;
				while (highlight.firstChild) {
					parent.insertBefore(highlight.firstChild, highlight);
				}
				parent.removeChild(highlight);
			});
		}

		/**
		 * Highlight text matching the search term in post content
		 * 
		 * @param {string} searchTerm The text to search for
		 */
		function highlightText(searchTerm) {
			const postContent = document.querySelector('.post-content, .entry-content, article, .content');
			if (!postContent) return;

			const walker = document.createTreeWalker(
				postContent,
				NodeFilter.SHOW_TEXT,
				null,
				false
			);

			const nodesToReplace = [];
			let node;

			while (node = walker.nextNode()) {
				if (node.nodeValue.toLowerCase().includes(searchTerm)) {
					nodesToReplace.push(node);
				}
			}

			nodesToReplace.forEach(textNode => {
				const text = textNode.nodeValue;
				const regex = new RegExp(`(${escapeRegex(searchTerm)})`, 'gi');
				const fragment = document.createDocumentFragment();
				let lastIndex = 0;
				let match;

				const tempDiv = document.createElement('div');
				tempDiv.innerHTML = text.replace(regex, '<mark class="wra-highlight">$1</mark>');

				while ((match = regex.exec(text)) !== null) {
					// Add text before match
					if (match.index > lastIndex) {
						fragment.appendChild(document.createTextNode(text.substring(lastIndex, match.index)));
					}

					// Add highlighted span
					const span = document.createElement('span');
					span.className = 'wra-highlight';
					span.textContent = match[0];
					fragment.appendChild(span);

					lastIndex = regex.lastIndex;
				}

				// Add remaining text
				if (lastIndex < text.length) {
					fragment.appendChild(document.createTextNode(text.substring(lastIndex)));
				}

				textNode.parentNode.replaceChild(fragment, textNode);
			});
		}

		/**
		 * Escape special regex characters
		 * 
		 * @param {string} string The string to escape
		 * @returns {string} The escaped string
		 */
		function escapeRegex(string) {
			return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
		}
	});
})();
