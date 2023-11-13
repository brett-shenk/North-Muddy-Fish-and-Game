[Back](./README.md)

# Shields.io

## Step 1
Go to <https://shields.io> and scroll down to the `Dynamic` section.

## Step 2

Select the following to properly generate your badge.

 - Data Type: json
 - Label: The text shown, like NODE
 - The full url to your package.json file.
   - Must be in a valid format to be read properly.
   - Ex: https://stjosephchildrenshealth.org.synapseresults.com/wp-content/themes/stjchildhealth/package.json
 - Query: Pretty simple regex. Example below selects the node version from inside of engines.
   - Ex: $.engines.node
   - To help https://jsonpath.com/
 - Select your color

## Step 3
 - Click make badge!
 - Ex: ![node](https://img.shields.io/badge/dynamic/json?color=blue&label=NODE&query=%24.engines.node&url=https%3A%2F%2Fstjosephchildrenshealth.org.synapseresults.com%2Fwp-content%2Fthemes%2Fstjchildhealth%2Fpackage.json)