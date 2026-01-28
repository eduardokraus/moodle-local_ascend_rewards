# Setting Up Professional Moodle Code Checking Tools

## Overview

We've created a local checker, but for production submission to the Moodle Plugin Repository, you should use professional tools. This guide shows several options.

## Option 1: Using VS Code Extensions (RECOMMENDED - Easiest)

### Install PHP Code Sniffer Extension
1. Open VS Code
2. Go to Extensions (Ctrl+Shift+X)
3. Search for "PHP Code Sniffer"
4. Install "PHP Code Sniffer" by felixbecker
5. Search for "phpcs" and install phpcs extension
6. Press Ctrl+Shift+P and search "PHP: Select Interpreter"
7. Choose your PHP path: `C:\laragon\bin\php\php-8.2.28-nts-Win32-vs16-x64\php.exe`

### Configure VS Code Settings
Create or edit `.vscode/settings.json` in your workspace:

```json
{
    "[php]": {
        "editor.defaultFormatter": "felixbecker.php-intellisense",
        "editor.formatOnSave": true
    },
    "php.validate.executablePath": "C:\\laragon\\bin\\php\\php-8.2.28-nts-Win32-vs16-x64\\php.exe",
    "phpcs.enable": true,
    "phpcs.standard": "PSR12",
    "phpcs.ignorePatterns": ["*/vendor/*", "*/node_modules/*", "*/.git/*"]
}
```

## Option 2: Using PHPStan (Advanced - Recommended for CI/CD)

PHPStan is a static analysis tool that catches errors without running code.

### Installation
```bash
# From your plugin directory
C:\laragon\bin\composer\composer.bat require --dev phpstan/phpstan

# Then run:
.\vendor\bin\phpstan.bat analyse .
```

### Create phpstan.neon config:
```neon
parameters:
    level: 5
    paths:
        - .
    excludePaths:
        - vendor
        - node_modules
        - .git
    ignoreErrors:
        - '#Unknown builtin class#'
        - '#Call to undefined method moodle_database#'
```

## Option 3: Using PHP_CodeSniffer Directly (Professional Standard)

### Installation (try again with better approach)
```bash
# Add these to your composer.json
"require-dev": {
    "squizlabs/php_codesniffer": "^3.8",
    "moodlerooms/moodle-coding-standard": "5.1.0"
}
```

### Run CodeSniffer:
```bash
# Full report
.\vendor\bin\phpcs . --standard=moodle

# Fix automatically (some issues)
.\vendor\bin\phpcbf . --standard=moodle

# JSON report
.\vendor\bin\phpcs . --standard=moodle --report=json > report.json
```

## Option 4: Use GitHub Actions for CI/CD (For repo submission)

Create `.github/workflows/moodle-ci.yml`:

```yaml
name: Moodle CI

on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest
    strategy:
      matrix:
        php-version: ['8.0', '8.1', '8.2']
    
    steps:
    - uses: actions/checkout@v3
    
    - name: Setup PHP
      uses: shivammathur/setup-php@v2
      with:
        php-version: ${{ matrix.php-version }}
    
    - name: Install dependencies
      run: composer install
    
    - name: Run PHP Lint
      run: parallel-lint --exclude vendor .
    
    - name: Run CodeSniffer
      run: phpcs --standard=moodle --report=full .
```

## Option 5: Command Line Quick Checks

### Using our built-in checker:
```bash
# From your plugin directory
C:\laragon\bin\php\php-8.2.28-nts-Win32-vs16-x64\php.exe check_standards.php
```

### Using Moodle's official checker:
If you get moodle-plugin-ci working:
```bash
# Validate your plugin
moodle-plugin-ci validate

# Run all checks
moodle-plugin-ci install && moodle-plugin-ci codesniffer && moodle-plugin-ci phpcpd
```

## Troubleshooting

### PHP not found in terminal
Add to PATH:
```powershell
$env:Path += ";C:\laragon\bin\php\php-8.2.28-nts-Win32-vs16-x64"
```

### Composer timeout
Increase timeout:
```bash
C:\laragon\bin\composer\composer.bat config process-timeout 600
```

### Antivirus blocking Composer
- Exclude Composer folder from antivirus
- Exclude `C:\Users\{user}\AppData\Roaming\Composer\` from scanning

## Recommended Workflow

1. **Before coding:** Open VS Code with extensions installed
2. **While coding:** 
   - Keep PHPCS extension enabled
   - Watch for red/yellow squiggles
   - Use auto-format (Shift+Alt+F)
3. **Before commit:**
   - Run `check_standards.php` to get full report
   - Fix critical errors first
4. **Before submission:**
   - Run full moodle-plugin-ci suite
   - Get zero errors
   - Manual code review for security

## Important Notes

- Moodle uses **PSR-12** standard with modifications
- Key differences from PSR-12:
  - No space before opening parenthesis in control structures: `if($x)` instead of `if ($x)` 
  - Single quotes for strings without variables
  - 4-space indentation (no tabs)
  - Max 120 chars (soft), 200 chars (hard limit)

## Files Created for You

1. **MOODLE_STANDARDS.md** - Complete standards guide
2. **STANDARDS_REPORT.md** - Current issue report  
3. **check_standards.php** - Basic PHP checker
4. **.phpcs.xml** - Code sniffer configuration

## Next Steps

Choose your preferred approach and run the checks regularly!
