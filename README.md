# Rma Graphql

## How to install

Run the following command in Magento 2 root folder:

```
composer require mageplaza/module-rma-graphql
php bin/magento setup:upgrade
php bin/magento setup:static-content:deploy
```

## How to use

To start working with **RMA GraphQL** in Magento, you need to:

- Use Magento 2.3.x. Return your site to developer mode
- Install [chrome extension](https://chrome.google.com/webstore/detail/chromeiql/fkkiamalmpiidkljmicmjfbieiclmeij?hl=en) (currently does not support other browsers)
- Set **GraphQL endpoint** as `http://<magento2-3-server>/graphql` in url box, click **Set endpoint**. (e.g. http://develop.mageplaza.com/graphql/ce232/graphql)
- The queries and mutations that Mageplaza support can be used to view the details that customers have requested, create requests, request cancel by customers, ect. Details can be viewed [here](https://documenter.getpostman.com/view/5977924/SzKZqvQE?version=latest#c0b8d573-3278-48e9-9644-5f711e5d033c).
