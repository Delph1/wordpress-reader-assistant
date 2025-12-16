# WordPress Reader Assistant

A WordPress plugin that provides a floating, collapsible table of contents and search functionality to make it easier to navigate long-form posts.

## Features

- **Floating Table of Contents**: A fixed-position sidebar that displays a table of contents for your post
- **Automatic Heading Detection**: Automatically extracts h3 and h4 headings from post content
- **Collapsible UI**: Toggle to minimize/maximize the reader assistant to save screen space
- **Page Search**: Search within the post content with automatic text highlighting
- **Smooth Scrolling**: Click any heading in the table of contents to smoothly scroll to that section
- **Responsive Design**: Works great on desktop and mobile devices
- **Lightweight**: Scripts and styles are only loaded when the shortcode is used
- **Auto ID Generation**: Automatically generates anchor IDs for headings that don't have them

## Installation

1. Download or clone this plugin into your WordPress `wp-content/plugins/` directory
2. Activate the plugin through the WordPress admin panel
3. Use the `[wordpress-reader-assistant]` shortcode in any post where you want to enable the reader

## Usage

Simply add the shortcode to any post content:

```
[wordpress-reader-assistant]
```

When the page loads, the reader assistant will:
- Automatically detect all h3 and h4 headings in your post
- Display them in a floating sidebar on the right side of the page
- Allow readers to search your post content with real-time highlighting
- Enable smooth scrolling to sections when headings are clicked

## Customization

The plugin uses semantic HTML and CSS classes that can be easily customized:

- `.wra-container` - Main container for the reader assistant
- `.wra-header` - Header with title and toggle button
- `.wra-search-input` - Search input field
- `.wra-toc-list` - Table of contents list
- `.wra-toc-link` - Individual heading links
- `.wra-highlight` - Highlighted search results

Edit `assets/css/plugin.css` to customize colors, spacing, and layout to match your theme.

## Requirements

- WordPress 5.0 or higher
- PHP 7.2 or higher

## License

This project is licensed under the Apache License 2.0 - see the [LICENSE](LICENSE) file for details.
