---
title: AutocheckinUpdated
layout: layout.html
eleventyNavigation:
  key: AutocheckinUpdated
  parent: Events
---

# AutocheckinUpdated

This event is meant to be emitted every time the config from the [Autocheckin product](/database/seeds/Autocheckin/) is updated.

## Content

This event contains the following payload to be sent:
```json
"payload" {
    "brand" {
        "id": integer,
    },
    "product" {
        "id": 21 (because it corresponds to Autocheckin),
    },
    "config": object (a big json)
}
```

## Associated endpoints

* All the fields are required.

### PUT

`{baseUrl}/brands/:brand_id/products/:product_id/configuration`

To update the configuration for a product for the brand.
