---
title: Products
layout: layout.html
eleventyNavigation:
  key: Products
  order: 1
---
# Products (API)

We offer a list of products that can be activated/deactivated for each brand in the main app, the private section.

This section is intended to document how the API works for each product.

## Endpoints

### GET
`{baseUrl}/products`

To get all products.

Inside some of the products, a list of fields are defined in order to customize the behaviour of each product that brand has activated, adapting to the needs of each one.

We have currently defined this list of products:

{% assign navPages = collections.all | eleventyNavigation: "Products" %}
{{ navPages | eleventyNavigationToHtml }}

**Add the remaining products.*
