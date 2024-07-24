# Upgrade guide

## 4.1.x to 4.2.x

The 4.2.x release adds support for `justbetter/laravel-magento-stock` version `^2.0`.

### Refactor

A few classes like the resource have been renamed. The resource `Stock` is now `StockResource`. Make sure to update all references if you make use of it manually.

### Configuration file

The configuration file has been removed. If you wish to customize the fields in the resource, you can create your own resource.
