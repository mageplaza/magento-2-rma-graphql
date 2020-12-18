# Magento 2 RMA GraphQL / PWA

**Magento 2 RMA GraphQL is now a part of the Mageplaza RMA extension that adds GraphQL features. This supports PWA compatibility.**

[Mageplaza RMA for Magento 2](https://www.mageplaza.com/magento-2-rma/) is a great tool that helps online stores deal with returns effectively. 

With this extension, you can allow non-login customers who haven’t created an account to send requests for returning items they have placed orders. If your store allows guest visitors to place orders, this is incredibly helpful and convenient for them. The RMA link will be displayed clearly at the top or bottom of the homepage, so customers can quickly access it to send requests. 

The store admin can set up the conditions based on the orders or products to apply RMA. Customers with orders matching the configured conditions will be able to send the RMA requests and vice versa. The conditions by orders can be total, subtotal weight, status, purchase point, customer group, payment method, region, or country. The conditions by product attributes can be SKU, category, etc. There is no limit to creating conditions for RMA. 

The extension enables you to handle the return requests for an item, some items, or the entire order. So if customers want to change the request for a specific item to the whole order, it has nothing complicated to do for both customers and store owners. 

Along with the basic order information, such as Order ID, Billing Last Name, Email, RMA information includes details essential to process the returns efficiently. Especially, the store admin can create additional fields for the RMA form, such as reasons for return, solution, and title of the request. Allowing customers to upload images to make their requests more reasonable by enclosing images of the current situation of the items they want to return, refund, or replace. There will be suggested reasons and solutions in the RMA form so that customers can quickly fill in the form and make it easier for store owners to understand customers’ requests and process it quickly. 

Customers will get notification about the status of their requests, which is approved or rejected via email. Store owners and customers can also discuss and negotiate further via email to come to a final agreement. 

## 1. How to install

Run the following command in Magento 2 root folder:

```
composer require mageplaza/module-rma-graphql
php bin/magento setup:upgrade
php bin/magento setup:static-content:deploy
```

**Note:** 
Magentp 2 RMA GraphQL requires installing [Mageplaza RMA](https://www.mageplaza.com/magento-2-rma/) in your Magento installation. 

## 2. How to use

To start working with **RMA GraphQL** in Magento 2, you need to:

- Use Magento 2.3.x. Return your site to developer mode
- Install [chrome extension](https://chrome.google.com/webstore/detail/chromeiql/fkkiamalmpiidkljmicmjfbieiclmeij?hl=en) (currently does not support other browsers)
- Set **GraphQL endpoint** as `http://<magento2-3-server>/graphql` in url box, click **Set endpoint**. (e.g. http://develop.mageplaza.com/graphql/ce232/graphql)
- The queries and mutations that Mageplaza support can be used to view the details that customers have requested, create requests, request cancel by customers, ect. Details can be viewed [here](https://documenter.getpostman.com/view/5977924/SzKZqvQE?version=latest#c0b8d573-3278-48e9-9644-5f711e5d033c).

## 3. Devdocs
- [Magento 2 RMA API & examples](https://documenter.getpostman.com/view/10589000/SzS2y8cT?version=latest) 
- [Magento 2 RMA GraphQL & examples](https://documenter.getpostman.com/view/10589000/SzRyzVYU?version=latest) 

Click on Run in Postman to add these collections to your workspace quickly.

![Magento 2 blog graphql pwa](https://i.imgur.com/lhsXlUR.gif)

## 4. Contribute to this module 
Feel free to **Fork** and contribute to this module. 

If you have any ideas to improve this post, create a pull request. We will consider to merge your proposed changes in the main branch. 

## 5. Get support
- If you have any further questions, feel free to [contact us]((https://www.mageplaza.com/contact.html). We're happy to hear from you. 
- If this post is helpful for you, please give it a **Star** ![star](https://i.imgur.com/S8e0ctO.png)


