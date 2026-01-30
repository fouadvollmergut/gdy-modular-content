# Contributing

Thank you for your interest in contributing to GDY Modular Content! This document provides guidelines for contributing to the project.

## Table of Contents

- [Code of Conduct](#code-of-conduct)
- [How to Contribute](#how-to-contribute)
- [Development Setup](#development-setup)
- [Coding Standards](#coding-standards)
- [Submitting Changes](#submitting-changes)
- [Reporting Bugs](#reporting-bugs)
- [Suggesting Features](#suggesting-features)

## Code of Conduct

This project follows a code of conduct based on respect and inclusivity:

- **Be respectful** - Treat all contributors with respect
- **Be constructive** - Provide helpful feedback
- **Be collaborative** - Work together to improve the project
- **Be patient** - Remember that everyone is learning

## How to Contribute

There are many ways to contribute to GDY Modular Content:

1. **Report bugs** - Help identify issues
2. **Suggest features** - Share ideas for improvements
3. **Write documentation** - Improve guides and examples
4. **Submit code** - Fix bugs or add features
5. **Create modules** - Share example modules
6. **Translate** - Help localize the plugin
7. **Test** - Try new versions and report issues

## Development Setup

### Prerequisites

- Node.js >= 20.14.0
- npm >= 10.7.0
- WordPress development environment
- Git

### Fork and Clone

1. **Fork the repository** on GitHub
2. **Clone your fork**:
```bash
git clone https://github.com/YOUR-USERNAME/gdy-modular-content.git
cd gdy-modular-content
```

3. **Add upstream remote**:
```bash
git remote add upstream https://github.com/fouadvollmergut/gdy-modular-content.git
```

### Install Dependencies

```bash
npm install
```

### Development Workflow

**Start development server** (watches for changes):
```bash
npm run start
```

**Build for production**:
```bash
npm run build
```

### Project Structure

```
gdy-modular-content/
├── classes/          # PHP classes
├── hooks/            # WordPress hooks
├── images/           # Plugin images
├── includes/         # Core PHP files
├── languages/        # Translation files
├── sass/             # SCSS source files
├── scripts/          # JavaScript source files
├── _dist/            # Build output (gitignored)
├── _styles/          # Compiled styles (gitignored)
├── gdy-modular-content.php  # Main plugin file
├── package.json      # npm configuration
└── webpack.config.js # Build configuration
```

### Development Environment

Set up a local WordPress installation:

1. **Use Local, XAMPP, or similar** for local WordPress
2. **Create a modules folder** in your theme
3. **Add example modules** for testing
4. **Enable WordPress debug mode** in `wp-config.php`:

```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
define('SCRIPT_DEBUG', true);
```

## Coding Standards

### PHP Standards

Follow [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/):

**Indentation:** Tabs (not spaces)

**Naming:**
- Functions: `gdymc_function_name()`
- Classes: `GDYMC_Class_Name`
- Variables: `$variable_name`

**Documentation:**
```php
/**
 * Function description
 *
 * @param string $param Description
 * @return mixed Description
 */
function gdymc_example_function( $param ) {
    // Code here
}
```

**Escaping output:**
```php
echo esc_html( $text );
echo esc_attr( $attribute );
echo esc_url( $url );
```

### JavaScript Standards

Follow WordPress JavaScript standards:

**Use strict mode:**
```javascript
'use strict';
```

**Variable naming:**
```javascript
const variableName = 'value';
```

**Comments:**
```javascript
/**
 * Function description
 * @param {string} param - Description
 * @return {boolean} Description
 */
function exampleFunction(param) {
    // Code here
}
```

### CSS/SCSS Standards

Follow [WordPress CSS Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/css/):

**Indentation:** Tabs

**Naming:** Use descriptive class names
```css
.gdymc_module_container {
    /* Styles */
}
```

**Organization:** Group related properties

## Submitting Changes

### Creating a Pull Request

1. **Create a new branch**:
```bash
git checkout -b feature/your-feature-name
# or
git checkout -b fix/bug-description
```

2. **Make your changes**:
   - Write clean, well-documented code
   - Follow coding standards
   - Test thoroughly

3. **Commit your changes**:
```bash
git add .
git commit -m "Brief description of changes"
```

Use clear commit messages:
- `feat: Add new feature`
- `fix: Fix bug in module loading`
- `docs: Update installation guide`
- `style: Format code per standards`
- `refactor: Reorganize module functions`
- `test: Add tests for content functions`

4. **Push to your fork**:
```bash
git push origin feature/your-feature-name
```

5. **Open a Pull Request** on GitHub:
   - Provide a clear title and description
   - Reference related issues
   - Explain what changed and why
   - Include screenshots for UI changes

### Pull Request Guidelines

**Good PR titles:**
- `Add gallery sorting feature`
- `Fix image cropper on mobile`
- `Update module development docs`

**PR description should include:**
- Summary of changes
- Motivation and context
- How to test
- Related issue numbers
- Screenshots (for UI changes)

**Example:**
```markdown
## Description
Adds drag-and-drop sorting for gallery images.

## Motivation
Users requested an easier way to reorder gallery images (#123).

## Changes
- Added sortable.js for drag-and-drop
- Updated gallery UI to support sorting
- Added save handler for new order

## Testing
1. Create a gallery with multiple images
2. Drag images to reorder
3. Save and verify order persists

Closes #123
```

## Reporting Bugs

### Before Reporting

1. **Search existing issues** - Your bug may already be reported
2. **Test with default theme** - Rule out theme conflicts
3. **Disable other plugins** - Check for plugin conflicts
4. **Update to latest version** - Bug may be fixed

### Creating a Bug Report

Open an issue on GitHub with:

**Title:** Clear, descriptive summary

**Description:**
```markdown
## Bug Description
Clear description of what's wrong.

## Steps to Reproduce
1. Go to...
2. Click on...
3. See error

## Expected Behavior
What should happen.

## Actual Behavior
What actually happens.

## Environment
- WordPress version: 6.0
- PHP version: 8.0
- Plugin version: 0.9.984
- Theme: Twenty Twenty-One
- Browser: Chrome 100

## Screenshots
[Attach if relevant]

## Console Errors
[Paste any JavaScript errors]

## Debug Log
[Paste relevant PHP errors]
```

## Suggesting Features

### Feature Request Guidelines

1. **Check existing requests** - May already be suggested
2. **Be specific** - Clearly describe the feature
3. **Explain use case** - Why is it needed?
4. **Consider alternatives** - Can it be achieved another way?

### Creating a Feature Request

```markdown
## Feature Description
Clear description of the proposed feature.

## Use Case
Why this feature would be useful.

## Proposed Solution
How you envision it working.

## Alternatives Considered
Other ways to achieve the same goal.

## Additional Context
Any other relevant information.
```

## Translation

Help translate GDYMC:

1. **WordPress.org GlotPress**: [Translate on WordPress.org](https://translate.wordpress.org/)
2. **POT file**: Located in `languages/gdy-modular-content.pot`
3. **Create .po and .mo files** for your language
4. **Submit via pull request** or WordPress.org

## Documentation

Improve documentation:

1. **Wiki pages**: Enhance guides in `/wiki/` folder
2. **Code comments**: Add/improve inline documentation
3. **Examples**: Create example modules or tutorials
4. **Screenshots**: Update or add screenshots

## Release Workflow

For maintainers:

### Creating a Release

1. **Update version numbers** in:
   - `gdy-modular-content.php`
   - `package.json`
   - `readme.txt`

2. **Update changelog** in `readme.txt`

3. **Run release script**:
```bash
sh ./release.sh 0.0.0  # Specify current version
```

4. **Push changes**:
```bash
git push origin main
git push --tags
```

5. **Create GitHub release** from the tag

6. **Upload to WordPress.org SVN** (if applicable)

### Undoing a Release

```bash
git tag --delete 0.0.0
git push --delete origin 0.0.0
# Delete GitHub release manually
```

## Testing

### Manual Testing

Test your changes:

1. **Clean install** - Test on fresh WordPress
2. **Upgrade path** - Test upgrading from previous version
3. **Different themes** - Test with various themes
4. **Different browsers** - Chrome, Firefox, Safari, Edge
5. **Different devices** - Desktop, tablet, mobile
6. **User roles** - Test with different permission levels

### Test Checklist

- [ ] Module creation works
- [ ] Content editing saves properly
- [ ] Image upload and cropping works
- [ ] Module options save correctly
- [ ] Preview modes function
- [ ] No JavaScript console errors
- [ ] No PHP warnings or errors
- [ ] Responsive on mobile
- [ ] Accessible (keyboard navigation, screen readers)
- [ ] Compatible with popular themes
- [ ] Compatible with popular plugins

## Getting Help

Questions about contributing?

- **GitHub Discussions**: Ask questions
- **GitHub Issues**: For bugs and features
- **Email maintainers**: For security issues

## Recognition

Contributors will be:

- Listed in release notes
- Mentioned in readme.txt
- Credited in commit history

Thank you for contributing to GDY Modular Content!
