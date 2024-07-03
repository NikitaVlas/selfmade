# Importer

This package contains generic utility to help importing
data.

## What's included?

* A command controller (CLI) to launch your import presets
* DataProvider: used to prepare and cleanup data from external sources
* Importer: get the data from DataProvider and import these data

## A basic DataProvider

Every data provider must extend the ``AbstractDataProvider`` class
or implement the ``DataProviderInterface``.
It's important to update the count or implement your own ``getCount()``-Method.

```php
class BasicDataProvider extends AbstractDataProvider
{
    /**
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public function fetch(int $offset = null, int $limit = null)
    {
        $query = $this->myDataRepository->findAll()->getQuery();
        if ($offset !== null && $limit !== null) {
            $query->setOffset($offset)->setLimit($limit);
        }
        return $query->execute()->toArray();
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return $this->myDataRepository->countAll();
    }
}
```

## A basic Importer

Every Importer must extend the ``AbstractImporter``. In the method ``import()`` you can easily implement
your own import logic.


```php
class BasicImporter extends AbstractImporter
{
    /**
     * @param array $batch
     */
    public function import(array $batch)
    {
    }
}
```

## A basic preset

You can easily configure your own import presets in your ``Settings.yaml``.
A preset is split in multiple parts.

```yaml
Avency:
  Neos:
    Importer:
      presets:
        'base':
          parts:
            'basicPart':
              dataProviderClassName: 'Your\Package\Importer\DataProvider\BasicDataProvider'
              dataProviderOptions: [] # Optional: Options for the data provider
              importerClassName: 'Your\Package\Importer\Importer\BasicImporter'
              importerOptions: [] # Optional: Options for the importer
              removeImportDataDirectly: false # removes the data directly from import table if the single import was successfully instead of waiting to finish all imports
              skipPrepareIfDataExsists: false # skips the prepare data if old import data is existing - prevents the importing of duplicated data
              skipPrepareBatchIfDataExists: false # skips the prepare batch method if old import data is existing
      errors:
        senderEmail: 'errors@avency.de'
        senderName: 'avency GmbH'
        replyTo: 'errors@avency.de'
        # string - for only one address
        toAddress: 'errors@avency.de'
        # array - for additional addresses
        # additionalToAddresses:
          # - mail-1@test.de
          # - mail-2@test.de
```

## Start your import process

First you have to prepare your import data:

```shell
./flow import:preparedata --preset base
```

Then you can start you import job:

```shell
./flow import:batch --preset base
```

You can also filter the preset parts:

```shell
./flow import:preparedata --preset base --parts basicPart,secondPart
```

```shell
./flow import:batch --preset base --parts basicPart,secondPart
```

You can also specify a batch-size:

```shell
./flow import:preparedata --preset base --batch-size 1000
```

```shell
./flow import:batch --preset base --batch-size 1000
```

### Cleanup a preset

Cleanup all or specific parts of a preset:

```shell
./flow import:cleanupdata --preset base --parts basicPart,secondPart
```
