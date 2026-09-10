---
title: BrandUpdated
layout: layout.html
eleventyNavigation:
  key: BrandUpdated
  parent: Events
---

# BrandUpdated

This event is meant to be emitted every time the info for a Brand is updated.

## Content

This event contains the following payload to be sent:
```json
"payload" {
    "brand" {
        "id": integer,
        "name": string,
        "logo": string,
        "background_color": string,
        "background_image": string,
        "place_country": string,
        "email": string,
        "time_zone": string,
        "products": array,
        "activated": integer
    },
}
```

* Everything is optional except for the id field inside the brand, as the available info can vary, but the data types should be respected.

## Associated endpoints

### PUT

`{baseUrl}/brands/:brand_id/info`

To update the brand general info.

### PUT

`{baseUrl}/brands/:brand_id/products/:product_id/activate/:active`

To activate or deactivate a product for the brand.
