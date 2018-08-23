Import Config Manager
=====================

Includes command to extract and import importer configurations.

## Installation

composer require pimcore-bundle/import-config-manager

## Usage

Add following lines to config.yml

```
import_config_manager:
  config_names: ["Product Import", "Category Import"]
```

### Extract importer configurations

```
bin/console export-importer-configs
```

### Import importer configrations

```
bin/console import-importer-configs
```