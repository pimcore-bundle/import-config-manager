Import Config Manager
=====================

Includes command to extract and import importer configurations.

## Installation

composer require pimcore-bundle/import-config-manager

## Usage

If you want to export just some configurations, add following lines to config.yml
and those config names into config_names array. If you want to extract all just skip this step.

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